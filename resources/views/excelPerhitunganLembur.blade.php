<!DOCTYPE html>
<html>
<head>
</head>
<body>

@if(!empty($lemburData))
    @php
        $firstLembur = $lemburData->first();
        $nama = $namaLengkap;
        $jabatan = $firstLembur->jabatan ?? 'N/A';
        $gaji = (float) ($firstLembur->gaji ?? 0);
    @endphp
<table class="new-table">>
        <thead>
            <tr>
                <th colspan="3">Nama</th>
                <th>:</th>
                <th colspan="3">{{ $nama ?? 'Tidak ada di Data Lembur'}}</th>
            </tr>
            <tr>
                <th colspan="3">Jabatan</th>
                <th>:</th>
                <th colspan="3">{{ $jabatan ?? 'Tidak ada di Data Lembur' }}</th>
            </tr>
            @php
        $formattedStartDate = \Carbon\Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y');
        $formattedEndDate = \Carbon\Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y');
    @endphp
            <tr>
                <th colspan="3">Periode</th>
                <th>:</th>
                <th colspan="3">{{ $formattedStartDate }} - {{ $formattedEndDate }}</th>
            </tr>
            <tr>
    <th colspan="3">Gaji Pokok</th>
    <th>:</th>
    <th colspan="3">{{ isset($gaji) ? number_format($gaji, 0, ',', '.') : 'Tidak ada di Data Lembur' }}</th>
</tr>
<tr>
    <th colspan="3">Upah lembur per jam</th>
    <th>:</th>
    <th colspan="3">
        {{ isset($gaji) ? number_format($gaji, 0, ',', '.') : 'Tidak ada di Data Lembur' }} 
        / 173 = 
        {{ isset($gaji) ? number_format($gaji / 173, 0, ',', '.') : 'Tidak ada di Data Lembur' }}
    </th>
</tr>

        </thead>
    </table>
    @else
    <p>No records found for the given criteria.</p>
@endif

    <table>
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Hari</th>
            <th rowspan="2">Tanggal</th>
            <th colspan="3">Waktu Kerja</th>
            <th colspan="9">Perhitungan Lembur</th>
            <th rowspan="2">Upah Lembur <br> Rp.</th>
            <th rowspan="2">Keterangan</th>
        </tr>
        <tr>
            <th style="border: 1px solid #dddddd; padding: 5px;">In</th>
            <th>Out</th>
            <th>Total</th>
            <th>Jam I</th>
            <th>x 1.5</th>
            <th>Jam II</th>
            <th>x 2</th>
            <th>Jam IX</th>
            <th>x 3</th>
            <th>Jam X</th>
            <th>x 4</th>
            <th>Total Jam Lembur</th>
        </tr>
    </thead>
    <tbody>
    @php
        // Initialize totals
        $totalJamKerjaLembur = 0;
        $totalJamI = 0;
        $totalJamI1_5 = 0;
        $totalJamII = 0;
        $totalJamII2 = 0;
        $totalJamIII = 0;
        $totalJamIII3 = 0;
        $totalJamIV = 0;
        $totalJamIV4 = 0;
        $totalTotalJamLembur = 0;
        $totalUpahLembur = 0;
    @endphp

    @foreach ($lemburData as $lembur)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ \Carbon\Carbon::parse($lembur->tanggal_lembur)->locale('id')->translatedFormat('l') }}</td>
        <td>{{ \Carbon\Carbon::parse($lembur->tanggal_lembur)->locale('id')->translatedFormat('d F Y') }}</td>
        <td>{{ \Carbon\Carbon::parse($lembur->jam_masuk)->format('H:i') }}</td>
        <td>{{ \Carbon\Carbon::parse($lembur->jam_keluar)->format('H:i') }}</td>
        <td>{{ number_format($lembur['jam_kerja_lembur'] ?? 0, 1, ',', '.') }}</td>
        <td>{{ number_format($lembur['jam_i'] ?? 0, 1, ',', '.') }}</td>
        <td>{{ number_format($lembur['jam_i'] * 1.5, 1, ',', '.') }}</td>
        <td>{{ number_format($lembur['jam_ii'] ?? 0, 1, ',', '.') }}</td>
        <td>{{ number_format($lembur['jam_ii'] * 2, 1, ',', '.') }}</td>
        <td>{{ number_format($lembur['jam_iii'] ?? 0, 1, ',', '.') }}</td>
        <td>{{ number_format($lembur['jam_iii'] * 3, 1, ',', '.') }}</td>
        <td>{{ number_format($lembur['jam_iv'] ?? 0, 1, ',', '.') }}</td>
        <td>{{ number_format($lembur['jam_iv'] * 4, 1, ',', '.') }}</td>
        <td>{{ number_format($lembur['total_jam_lembur'], 1, ',', '.') }}</td>
        <td>{{$lembur['upah_lembur'] }}</td>
        <td>{{ $lembur['keterangan'] }}</td>
        
        @php
            // Accumulate totals
            $totalJamKerjaLembur += $lembur['jam_kerja_lembur'];
            $totalJamI += $lembur['jam_i'];
            $totalJamI1_5 += $lembur['jam_i'] * 1.5;
            $totalJamII += $lembur['jam_ii'];
            $totalJamII2 += $lembur['jam_ii'] * 2;
            $totalJamIII += $lembur['jam_iii'];
            $totalJamIII3 += $lembur['jam_iii'] * 3;
            $totalJamIV += $lembur['jam_iv'];
            $totalJamIV4 += $lembur['jam_iv'] * 4;
            $totalTotalJamLembur += $lembur['total_jam_lembur'];
            $totalUpahLembur += $lembur['upah_lembur'];
        @endphp
    </tr>
    @endforeach
</tbody>
<tfoot>
    <tr>
        <th colspan="5">Total</th>
        <td>{{ number_format($totalJamKerjaLembur, 1, ',', '.') }}</td>
        <td>{{ number_format($totalJamI, 1, ',', '.') }}</td>
        <td>{{ number_format($totalJamI1_5, 1, ',', '.') }}</td>
        <td>{{ number_format($totalJamII, 1, ',', '.') }}</td>
        <td>{{ number_format($totalJamII2, 1, ',', '.') }}</td>
        <td>{{ number_format($totalJamIII, 1, ',', '.') }}</td>
        <td>{{ number_format($totalJamIII3, 1, ',', '.') }}</td>
        <td>{{ number_format($totalJamIV, 1, ',', '.') }}</td>
        <td>{{ number_format($totalJamIV4, 1, ',', '.') }}</td>
        <td>{{ $totalTotalJamLembur}}</td>
        <td>{{ $totalUpahLembur}}</td>
        <td></td>
    </tr>
</tfoot>

</table>


</body>
</html>
