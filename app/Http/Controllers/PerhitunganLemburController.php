<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lembur;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LemburExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class PerhitunganLemburController extends Controller
{
    public function index(Request $request)
{
    Carbon::setLocale('id'); // Set the locale to Indonesian

    $query = Lembur::query();

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('tanggal_lembur', [$request->input('start_date'), $request->input('end_date')]);
    }

    if ($request->filled('nama_lengkap2')) {
        $query->where('nama_lengkap', 'like', '%' . $request->input('nama_lengkap2') . '%');
    }

    if ($request->filled('id_karyawan2')) {
        $query->where('id_karyawan', $request->input('id_karyawan2'));
    }

    // Append query parameters to pagination links
    $lemburRecords = $query->orderBy('tanggal_lembur', 'desc')
        ->paginate(15)
        ->appends($request->query());

    // Fetch gaji and jabatan from the karyawan table based on the selected nama_lengkap2
    $karyawan = Karyawan::where('nama_karyawan', $request->input('nama_lengkap2'))->first();

    $gajiPokok = $karyawan ? $karyawan->gaji : 0;
    $jabatan = $karyawan ? $karyawan->jabatan : 'N/A';
    $namaLengkap = $karyawan ? $karyawan->nama_lengkap : 'N/A';

    // Calculate upah lembur per jam
    $upahLemburPerJam = $gajiPokok ? floor($gajiPokok / 173) : 0;

    // Format start_date and end_date for Periode
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $periode = 'Tidak ada periode yang ditentukan';
    $namaLengkap = $request->input('nama_lengkap2');

    if ($startDate && $endDate) {
        $formattedStartDate = Carbon::parse($startDate)->translatedFormat(' d F Y');
        $formattedEndDate = Carbon::parse($endDate)->translatedFormat(' d F Y');
        $periode = $formattedStartDate . ' - ' . $formattedEndDate;
    }

    // Format dates for each record
    $lemburRecords->transform(function ($item) {
        $item->formatted_tanggal_lembur = $item->tanggal_lembur->locale('id')->translatedFormat('l, d F Y');
        return $item;
    });

    return view('app.Perhitungan Lembur', compact('lemburRecords', 'gajiPokok', 'jabatan', 'namaLengkap', 'upahLemburPerJam', 'periode'));
}

    
    public function store(Request $request)
    {
        // Define validation rules and custom messages
        $validator = Validator::make($request->all(), [
            'namaLengkap' => 'required|string|max:255',
            'IDKaryawan' => 'required|numeric',
            'tanggalLembur' => 'required|date',
            'jamMasuk' => 'required|date_format:H:i',
            'jamKeluar' => 'required|date_format:H:i',
            'jenisLembur' => 'required|string',
            'gaji' => 'required|numeric',
            'jamKerjaLembur' => 'nullable|numeric',
            'jamI' => 'nullable|numeric',
            'jamII' => 'nullable|numeric',
            'jamIII' => 'nullable|numeric',
            'jamIV' => 'nullable|numeric',
            'totalJamLembur' => 'nullable|numeric',
            'upahLembur' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
        ], [
            'namaLengkap.required' => 'Nama Lengkap harus diisi.',
            'IDKaryawan.required' => 'ID Karyawan harus diisi.',
            'tanggalLembur.required' => 'Tanggal Lembur harus diisi.',
            'jamMasuk.required' => 'Jam Masuk harus diisi.',
            'jamKeluar.required' => 'Jam Keluar harus diisi.',
            'jenisLembur.required' => 'Jenis Lembur harus dipilih.',
            'gaji.required' => 'Gaji harus diisi.',
            'jamKerjaLembur.numeric' => 'Jam Kerja Lembur harus berupa angka.',
            'jamI.numeric' => 'Jam I harus berupa angka.',
            'jamII.numeric' => 'Jam II harus berupa angka.',
            'jamIII.numeric' => 'Jam III harus berupa angka.',
            'jamIV.numeric' => 'Jam IV harus berupa angka.',
            'totalJamLembur.numeric' => 'Total Jam Lembur harus berupa angka.',
            'upahLembur.numeric' => 'Upah Lembur harus berupa angka.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->route('perhitungan-lembur')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal menambahkan data lembur.'); // Custom error message
        }

        // Check if ID Karyawan and Nama Lengkap exist in Karyawan table
        $karyawan = Karyawan::where('id_karyawan', $request->input('IDKaryawan'))
            ->where('nama_karyawan', $request->input('namaLengkap'))
            ->first();

        // Check if karyawan exists and is active
        if (!$karyawan || $karyawan->status === 'Tidak Aktif') {
            return redirect()->route('perhitungan-lembur')
                ->with('error', 'ID Karyawan dan Nama Lengkap tidak terdaftar, tidak aktif, atau tidak cocok di Data Karyawan.')
                ->withInput();
        }

        $lembur = new Lembur();
        $lembur->nama_lengkap = $request->input('namaLengkap');
        $lembur->id_karyawan = $request->input('IDKaryawan');
        $lembur->tanggal_lembur = $request->input('tanggalLembur');
        $lembur->jam_masuk = $request->input('jamMasuk');
        $lembur->jam_keluar = $request->input('jamKeluar');
        $lembur->jenis_lembur = $request->input('jenisLembur');
        $lembur->gaji = $request->input('gaji');
        $lembur->jam_kerja_lembur = $request->input('jamKerjaLembur');
        $lembur->jam_i = $request->input('jamI');
        $lembur->jam_ii = $request->input('jamII');
        $lembur->jam_iii = $request->input('jamIII');
        $lembur->jam_iv = $request->input('jamIV');
        $lembur->total_jam_lembur = $request->input('totalJamLembur');
        $lembur->upah_lembur = $request->input('upahLembur');
        $lembur->keterangan = $request->input('keterangan');
        $lembur->save();

        // Redirect back with success message
        return redirect()->route('perhitungan-lembur')->with('success', 'Data Lembur berhasil ditambahkan.');
    }

    public function update(Request $request)
    {
        $id = $request->input('editID');
   // Dump request data before validation
    // dd('Before Validation:', $request->all());

        // Define validation rules and custom messages
        $validator = Validator::make($request->all(), [
            'editIDKaryawan' => 'required|numeric',
            'editnamaLengkap' => 'required|string|max:255',
            'edittanggalLembur' => 'required|date',
            'editjamMasuk' => 'required|date_format:H:i',
            'editjamKeluar' => 'required|date_format:H:i',
            'editjenisLembur' => 'required|string',
            'editgaji' => 'required|numeric',
            'editjamKerjaLembur' => 'nullable|numeric',
            'editjamI' => 'nullable|numeric',
            'editjamII' => 'nullable|numeric',
            'editjamIII' => 'nullable|numeric',
            'editjamIV' => 'nullable|numeric',
            'edittotalJamLembur' => 'nullable|numeric',
            'editupahLembur' => 'nullable|numeric',
            'editKeterangan' => 'nullable|string',
        ], [
            'editnamaLengkap.required' => 'Nama Lengkap harus diisi.',
            'editIDKaryawan.required' => 'ID Karyawan harus diisi.',
            'edittanggalLembur.required' => 'Tanggal Lembur harus diisi.',
            'editjamMasuk.required' => 'Jam Masuk harus diisi.',
            'editjamKeluar.required' => 'Jam Keluar harus diisi.',
            'editjenisLembur.required' => 'Jenis Lembur harus dipilih.',
            'editgaji.required' => 'Gaji harus diisi.',
            'editjamKerjaLembur.numeric' => 'Jam Kerja Lembur harus berupa angka.',
            'editjamI.numeric' => 'Jam I harus berupa angka.',
            'editjamII.numeric' => 'Jam II harus berupa angka.',
            'editjamIII.numeric' => 'Jam III harus berupa angka.',
            'editjamIV.numeric' => 'Jam IV harus berupa angka.',
            'edittotalJamLembur.numeric' => 'Total Jam Lembur harus berupa angka.',
            'editupahLembur.numeric' => 'Upah Lembur harus berupa angka.',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            dd('Validation Errors:', $validator->errors()->all());
            return redirect()->route('perhitungan-lembur')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal memperbarui data lembur.'); // Custom error message
        }

         // Dump request data after validation
        // dd('After Validation:', $request->all());
        // dd($id);
    
        // Check if ID Karyawan and Nama Lengkap exist in Karyawan table
        $karyawan = Karyawan::where('id_karyawan', $request->input('editIDKaryawan'))
            ->where('nama_karyawan', $request->input('editnamaLengkap'))
            ->first();
    
        // Check if karyawan exists and is active
        if (!$karyawan || $karyawan->status === 'Tidak Aktif') {
            return redirect()->route('perhitungan-lembur')
                ->with('error', 'ID Karyawan dan Nama Lengkap tidak terdaftar, tidak aktif, atau tidak cocok di Data Karyawan.')
                ->withInput();
        }
    
        $lembur = Lembur::findOrFail($id);
        $lembur->nama_lengkap = $request->input('editnamaLengkap');
        $lembur->id_karyawan = $request->input('editIDKaryawan');
        $lembur->tanggal_lembur = $request->input('edittanggalLembur');
        $lembur->jam_masuk = $request->input('editjamMasuk');
        $lembur->jam_keluar = $request->input('editjamKeluar');
        $lembur->jenis_lembur = $request->input('editjenisLembur');
        $lembur->gaji = $request->input('editgaji');
        $lembur->jam_kerja_lembur = $request->input('editjamKerjaLembur');
        $lembur->jam_i = $request->input('editjamI');
        $lembur->jam_ii = $request->input('editjamII');
        $lembur->jam_iii = $request->input('editjamIII');
        $lembur->jam_iv = $request->input('editjamIV');
        $lembur->total_jam_lembur = $request->input('edittotalJamLembur');
        $lembur->upah_lembur = $request->input('editupahLembur');
        $lembur->keterangan = $request->input('editKeterangan');
        $lembur->save();
    
        // Redirect back with success message
        return redirect()->route('perhitungan-lembur')->with('success', 'Data Lembur berhasil diperbarui.');
    }
    

    public function destroy($id)
    {
        // dd($id); // Use this to debug and check if the correct ID is being passed.
    
        // Find and delete the Lembur record
        $lembur = Lembur::where('id', $id)->first();
    
        if ($lembur) {
            // Delete the Lembur record
            $lembur->delete();
    
            // Redirect back with success message
            return redirect()->route('perhitungan-lembur')->with('success', 'Data Lembur berhasil dihapus.');
        } else {
            // Redirect back with error message
            return redirect()->route('perhitungan-lembur')->with('error', 'Gagal menghapus data lembur.');
        }
    }
    

    public function exportExcel(Request $request)
    {
        // Get the filter values from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $namaLengkap = $request->input('nama_lengkap');
        $idKaryawan = $request->input('id_karyawan');

        // Create an instance of the LemburExport class with the filter values
        $lemburExport = new LemburExport($startDate, $endDate, $namaLengkap, $idKaryawan);

        // Export the data as an Excel file
        return Excel::download($lemburExport, 'lembur.xlsx');
    }

    public function printableView(Request $request)
    {
        // Initialize the query builder
        $query = Lembur::query()
            ->join('karyawan', 'lembur.nama_lengkap', '=', 'karyawan.nama_karyawan')
            ->select(
                'lembur.*', // Select all columns from lembur table
                'karyawan.jabatan', // Select jabatan from karyawan table
                'karyawan.gaji' // Select gaji from karyawan table
            );
    
        // Filtering logic
        if ($request->has('printid_karyawan2') && !empty($request->input('printid_karyawan2'))) {
            $query->where('lembur.id_karyawan', $request->input('printid_karyawan2'));
        }
    
        if ($request->has('printnama_lengkap2') && !empty($request->input('printnama_lengkap2'))) {
            $query->where('lembur.nama_lengkap', $request->input('printnama_lengkap2'));
        }
    
        if ($request->has('printstart_date') && !empty($request->input('printstart_date'))) {
            $query->whereDate('lembur.tanggal_lembur', '>=', $request->input('printstart_date'));
            $startDate = $request->input('printstart_date');
        } else {
            $startDate = null;
        }
    
        if ($request->has('printend_date') && !empty($request->input('printend_date'))) {
            $query->whereDate('lembur.tanggal_lembur', '<=', $request->input('printend_date'));
            $endDate = $request->input('printend_date');
        } else {
            $endDate = null;
        }
    
        $lemburRecords = $query->get()->map(function($item) {
            $formattedDate = $item->tanggal_lembur->locale('id')->translatedFormat('d F Y');
            $dayOfWeek = $item->tanggal_lembur->locale('id')->translatedFormat('l');
            return [
                'id_karyawan' => $item->id_karyawan,
                'nama_lengkap' => $item->nama_lengkap,
                'jabatan' => $item->jabatan, // Get jabatan from karyawan table
                'formatted_tanggal_lembur2' => $dayOfWeek,
                'formatted_tanggal_lembur' => $formattedDate,
                'jenis_lembur' => $item->jenis_lembur,
                'jam_masuk' => $item->jam_masuk->format('H:i'),
                'jam_keluar' => $item->jam_keluar->format('H:i'),
                'gaji' => $item->gaji, // Get gaji from karyawan table
                'jam_kerja_lembur' => $item->jam_kerja_lembur,
                'jam_i' => $item->jam_i,
                'jam_ii' => $item->jam_ii,
                'jam_iii' => $item->jam_iii,
                'jam_iv' => $item->jam_iv,
                'total_jam_lembur' => $item->total_jam_lembur,
                'upah_lembur' => $item->upah_lembur,
                'keterangan' => $item->keterangan
            ];
        });
    
        return view('printable_view', compact('lemburRecords', 'startDate', 'endDate'));
    }
    
    
}

