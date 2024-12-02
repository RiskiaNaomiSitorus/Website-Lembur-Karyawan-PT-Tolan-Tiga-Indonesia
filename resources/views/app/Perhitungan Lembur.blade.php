<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Perhitungan Lembur</title>
    <link rel="stylesheet" href="{{ asset ('assets/styles.css')}}" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Include jQuery and jQuery UI -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <style>
      /* Add your styles here */
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      body {
        display: flex;
        flex-direction: column;
      }
      .wrapper {
        display: flex;
        flex: 1;
      }
      .sidebar {
        width: 250px;
        background: #333;
        color: white;
        padding: 20px;
        box-sizing: border-box;
      }
      .main_content {
        flex: 1;
        padding: 20px;
        box-sizing: border-box;
      }
      .card-header {
        margin: 20px;
        border: 1px solid #11a634;
        border-radius: 0; /* Menghapus border-radius untuk membuat persegi */
        box-shadow: 0 2px 4px rgba(0, 255, 38, 0.1);
        width: 225px; /* Menentukan lebar card */
        height: 40px;
        background-color: #1bbc53;
        color: white;
        display: flex;
        align-items: center; /* Menambahkan padding untuk header */
      }
      .card-header i {
        margin-right: 10px;
      }
      .action-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        margin-left: 20px;
      }
      .header-title {
        display: flex;
        align-items: center;
      }
      .table-container {
        width: 100%;
        margin-left: 5px;
      }
      .table-container table {
        width: 100%;
      }
      table,
      th,
      td {
        border: 1px solid black;
      }
      th,
      td {
        padding: 8px;
        text-align: center;
      }
      th {
        background-color: #f2f2f2;
      }
      .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
      }
      .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        position: relative;
      }
      .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
      }
      .close:hover,
      .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
      }
    </style>
  </head>
  <body>
    <div class="wrapper">
      <div class="sidebar">
        <h2>Lembur</h2>
        <ul>
        <li>
    <a href="{{ route('home') }}"><i class="fas fa-home"></i> Dashboard</a>
</li>
<li>
    <a href="{{ route('data-karyawan') }}"><i class="fas fa-user"></i> Data Karyawan</a>
</li>
<li>
    <a href="{{ route('rekapitulasi-jam-lembur') }}"><i class="fas fa-project-diagram"></i> Summary Jam Lembur</a>
</li>
<li>
    <a href="{{ route('perhitungan-lembur') }}"><i class="fas fa-address-book"></i> Perhitungan Lembur</a>
</li>
        </ul>
      </div>
      <div class="main_content">
        <div class="header">
          <div class="title">PT. Tolan Tiga Indonesia</div>
          <div class="user-info">
            <i class="fa fa-bell"></i>
            <div class="dropdown">
            <div class="username">
    {{ Auth::user()->name }} <i class="fa fa-caret-down"></i>
</div>
              <div class="dropdown-content">
              <a href="{{ url('logout') }}" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
<form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
              </div>
            </div>
          </div>
        </div>
        <div class="info">
        <div class="date">
        <h5 id="todayDate"></h5>
    </div>
        </div>
        <div class="dashboard"><h2>Perhitungan Lembur</h2></div>
        <div class="header-section">
          <div class="card-header" id="tambahDataLembur">
            <i></i>
            <i class="fas fa-plus-circle"></i>
            <h4>Tambah Data Lembur</h4>
          </div>
          <div class="action-buttons">
           <!-- Button to Open Modal -->
<button class="btn btn-primary btn-sm" id="exportButton">
    <i class="fas fa-file-excel"></i> Excel
</button>
<button class="btn btn-secondary btn-sm" id="openPrintModal">
    <i class="fas fa-print"></i> Print
</button>
            <button class="btn btn-warning btn-sm">
              <i class="fas fa-eye"></i> Column Visibility
            </button>
           <!-- Button to open the modal -->
<button class="btn btn-info" id="filterButton">
<i class="fa fa-filter" aria-hidden="true"></i> Filter Records
</button>
          </div>          
        </div>
        
       <!-- Display Information -->
<div style="margin-left:20px">
    <label>Nama</label>
    <span class="value">: <span id="namaLengkap6">{{ $namaLengkap ?? 'N/A'}}</span></span><br>

    <label>Jabatan</label>
    <span class="value">: <span id="jabatan6">{{ $jabatan }}</span></span><br>

    <label>Periode</label>
    <span class="value">: <span id="periode6">{{ $periode }}</span></span><br>

    <label>Gaji Pokok</label>
    <span class="value">: <span id="gajiPokok6">{{ number_format($gajiPokok, 0, ',', '.') }}</span></span><br>

    <label>Upah lembur per jam</label>
    <span class="value">: <span id="upahLemburPerJam6">{{ number_format($upahLemburPerJam, 0, ',', '.') }}</span></span>
</div>

        <div class="container table-container">
        <table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="col-no">No</th>
            <th class="col-idkaryawan">REG Karyawan</th>
            <th class="col-namalengkap">Nama Lengkap</th>
            <th class="col-tanggallembur">Tanggal Lembur</th>
            <th class="col-jenislembur">Jenis Lembur</th>
            <th class="col-jammasuk">Jam Masuk</th>
            <th class="col-jamkeluar">Jam Keluar</th>
            <th class="col-gaji">Gaji</th>
            <th class="col-jamkerjalembur">Jam Kerja Lembur</th>
            <th class="col-jami">Jam I</th>
            <th class="col-jamii">Jam II</th>
            <th class="col-jamiii">Jam III</th>
            <th class="col-jamiv">Jam IV</th>
            <th class="col-upahlembur">Upah Lembur</th>
            <th class="col-keterangan">Keterangan</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($lemburRecords as $lembur)
    <tr>
        <td class="col-no">{{ $loop->iteration }}</td>
        <td class="col-idkaryawan">{{ $lembur->id_karyawan }}</td>
        <td class="col-namalengkap">{{ $lembur->nama_lengkap }}</td>
        <td class="col-tanggallembur">{{ $lembur->formatted_tanggal_lembur }}</td>
        <td class="col-jenislembur">{{ $lembur->jenis_lembur }}</td>
        <td class="col-jammasuk">{{ $lembur->jam_masuk->format('H:i') }}</td>
        <td class="col-jamkeluar">{{ $lembur->jam_keluar->format('H:i') }}</td>
        <td class="col-gaji">{{ 'Rp. ' . number_format($lembur->gaji, 0, ',', '.') }}</td>
        <td class="col-jamkerjalembur">{{ number_format($lembur->jam_kerja_lembur, 1, ',', '.') }}</td>
        <td class="col-jami">{{ number_format($lembur->jam_i, 1, ',', '.') }}</td>
        <td class="col-jamii">{{ number_format($lembur->jam_ii, 1, ',', '.') }}</td>
        <td class="col-jamiii">{{ number_format($lembur->jam_iii, 1, ',', '.') }}</td>
        <td class="col-jamiv">{{ number_format($lembur->jam_iv, 1, ',', '.') }}</td>
        <td class="col-upahlembur">{{ 'Rp. ' . number_format($lembur->upah_lembur, 0, ',', '.') }}</td>
        <td class="col-keterangan">{{ $lembur->keterangan }}</td>
        <td>
            <button 
                class="btn btn-warning btn-sm edit-buttonLembur" 
                data-id="{{ $lembur->id }}"
                data-idkaryawan="{{ $lembur->id_karyawan }}"
                data-nama="{{ $lembur->nama_lengkap }}"
                data-tanggal="{{ $lembur->tanggal_lembur->format('Y-m-d') }}"
                data-jenis="{{ $lembur->jenis_lembur }}"
                data-jammasuk="{{ $lembur->jam_masuk->format('H:i') }}"
                data-jamkeluar="{{ $lembur->jam_keluar->format('H:i') }}"
                data-gaji="{{ $lembur->gaji }}"
                data-jamkerjalembur="{{ $lembur->jam_kerja_lembur }}"
                data-jami="{{ $lembur->jam_i }}"
                data-jamii="{{ $lembur->jam_ii }}"
                data-jamiii="{{ $lembur->jam_iii }}"
                data-jamiv="{{ $lembur->jam_iv }}"
                data-totaljamlembur="{{ $lembur->total_jam_lembur }}"
                data-upahlembur="{{ $lembur->upah_lembur }}"
                data-keterangan="{{ $lembur->keterangan }}"
            >
                Edit
            </button>
            <button class="btn btn-danger btn-sm delete-buttonLembur"
            data-id="{{ $lembur->id }}">
                Hapus
            </button>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>


@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<!-- Pagination Links -->
<div class="pagination">
    {{ $lemburRecords ->links() }}
</div>
        </div>
      </div>
    </div>
    <footer
      style="
        background-color: #f8f9fa;
        padding: 20px;
        text-align: center;
        margin-top: 20px;
      "
    >
      <p>&copy; 2024 PT. Tolan Tiga Indonesia. All rights reserved.</p>
      <div style="display: flex; justify-content: center; gap: 20px">
        <a href="#" style="text-decoration: none; color: #007bff"
          >Privacy Policy</a
        >
        <a href="#" style="text-decoration: none; color: #007bff"
          >Terms of Service</a
        >
        <a href="#" style="text-decoration: none; color: #007bff">Contact Us</a>
      </div>
    </footer>

    <!-- Modal for Adding Lembur -->
    <div id="addLemburModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeAddLemburModal">&times;</span>
        <h3 style="margin-bottom: 30px"><strong>Tambah Data Lembur</strong></h3>
        <form id="addlemburForm" method="POST" action="{{ route('store-lembur') }}">
            @csrf
            <div class="form-group">
                <label for="addIDKaryawan">REG Karyawan</label>
                <input type="text" class="form-control @error('IDKaryawan') is-invalid @enderror" id="addIDKaryawan" name="IDKaryawan" pattern="\d*" title="Please enter numbers only" value="{{ old('IDKaryawan') }}" required />
                @error('IDKaryawan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addnamaLengkap">Nama Lengkap</label>
                <input type="text" class="form-control @error('namaLengkap') is-invalid @enderror" id="addnamaLengkap" name="namaLengkap" value="{{ old('namaLengkap') }}" required />
                @error('namaLengkap')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addTanggalLembur">Tanggal Lembur</label>
                <input
                    type="date"
                    class="form-control @error('tanggalLembur') is-invalid @enderror"
                    id="addtanggalLembur"
                    name="tanggalLembur"
                    onchange="checkDate('add')"
                    value="{{ old('tanggalLembur') }}"
                    required
                />
                @error('tanggalLembur')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addJenisLembur">Jenis Lembur</label>
                <select class="form-control @error('jenisLembur') is-invalid @enderror" id="addjenisLembur" name="jenisLembur" required>
                    <option value="" selected readOnly></option>
                    <option value="Hari Biasa" {{ old('jenisLembur') == 'Hari Biasa' ? 'selected' : '' }}>Hari Biasa</option>
                    <option value="Weekend" {{ old('jenisLembur') == 'Weekend' ? 'selected' : '' }}>Weekend</option>
                    <option value="Libur" {{ old('jenisLembur') == 'Libur' ? 'selected' : '' }}>Libur</option>
                </select>
                @error('jenisLembur')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addJamMasuk">Jam Masuk</label>
                <input type="time" class="form-control @error('jamMasuk') is-invalid @enderror" id="addjamMasuk" name="jamMasuk" value="{{ old('jamMasuk') }}" required />
                @error('jamMasuk')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addJamKeluar">Jam Keluar</label>
                <input type="time" class="form-control @error('jamKeluar') is-invalid @enderror" id="addjamKeluar" name="jamKeluar" value="{{ old('jamKeluar') }}" required />
                <button type="button" class="btn btn-secondary" onclick="updateAddJamKerjaLembur()">Calculate Total Jam Lembur</button>

                @error('jamKeluar')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addGaji">Gaji (Rp)</label>
                <input type="number" class="form-control @error('gaji') is-invalid @enderror" id="addgaji" name="gaji" value="{{ old('gaji') }}" readOnly/>
                <span>*Isi REG Karyawan dan Nama Lengkap secara berurutan untuk memunculkan Gaji</span><br>
                <button type="button" class="btn btn-secondary" onclick="addcalculateUpahLembur()">Calculate Upah Lembur</button>
                @error('gaji')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addJamKerjaLembur">Total Waktu Kerja</label>
                <input type="number" class="form-control @error('jamKerjaLembur') is-invalid @enderror" id="addjamKerjaLembur" name="jamKerjaLembur" value="{{ old('jamKerjaLembur') }}" readOnly />
                @error('jamKerjaLembur')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addJamI">Jam I</label>
                <input type="number" class="form-control @error('jamI') is-invalid @enderror" id="addjamI" name="jamI" value="{{ old('jamI') }}" readOnly />
                @error('jamI')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addJamII">Jam II</label>
                <input type="number" class="form-control @error('jamII') is-invalid @enderror" id="addjamII" name="jamII" value="{{ old('jamII') }}" readOnly />
                @error('jamII')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addJamIII">Jam III</label>
                <input type="number" class="form-control @error('jamIII') is-invalid @enderror" id="addjamIII" name="jamIII" value="{{ old('jamIII') }}" readOnly />
                @error('jamIII')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addJamIV">Jam IV</label>
                <input type="number" class="form-control @error('jamIV') is-invalid @enderror" id="addjamIV" name="jamIV" value="{{ old('jamIV') }}" readOnly />
                @error('jamIV')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addTotalJamLembur">Total Jam Lembur</label>
                <input type="number" class="form-control @error('totalJamLembur') is-invalid @enderror" id="addtotalJamLembur" name="totalJamLembur" value="{{ old('totalJamLembur') }}" readOnly />
                @error('totalJamLembur')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addUpahLembur">Upah Lembur (Rp)</label>
                <input type="number" class="form-control @error('upahLembur') is-invalid @enderror" id="addupahLembur" name="upahLembur" value="{{ old('upahLembur') }}" readOnly />
                @error('upahLembur')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="addKeterangan">Keterangan</label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="addKeterangan" name="keterangan">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

    <!-- Modal for Editing Lembur -->
<div id="editLemburModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEditLemburModal">&times;</span>
        <h3 style="margin-bottom: 30px"><strong>Edit Data Lembur</strong></h3>
        <form id="editLemburForm" method="POST" action="{{ route('perhitungan-lembur.update') }}">
    @csrf
    @method('PUT')
    <input type="hidden" id="editID" name="editID">
            <div class="form-group">
                <label for="editIDKaryawan">REG Karyawan</label>
                <input
                    type="text"
                    class="form-control"
                    id="editIDKaryawan"
                    name="editIDKaryawan"
                    pattern="\d*"
                    title="Please enter numbers only"
                    value="{{ old('editIDKaryawan') }}"
                    required
                />
                @error('editIDKaryawan')
                    <div id="editIDKaryawanError" style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editnamaLengkap">Nama Lengkap</label>
                <input
                    type="text"
                    class="form-control"
                    id="editnamaLengkap"
                    name="editnamaLengkap"
                    value="{{ old('editnamaLengkap') }}"
                    required
                />
                @error('editnamaLengkap')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="edittanggalLembur">Tanggal Lembur</label>
                <input
                    type="date"
                    class="form-control"
                    id="edittanggalLembur"
                    name="edittanggalLembur"
                    value="{{ old('edittanggalLembur') }}"
                    onchange="checkDate('edit')"
                    required
                />
                @error('edittanggalLembur')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editjenisLembur">Jenis Lembur</label>
                <select class="form-control" id="editjenisLembur" name="editjenisLembur" required>
                    <option value="" selected disabled></option>
                    <option value="Hari Biasa" {{ old('editjenisLembur') == 'Hari Biasa' ? 'selected' : '' }}>Hari Biasa</option>
                    <option value="Weekend" {{ old('editjenisLembur') == 'Weekend' ? 'selected' : '' }}>Weekend</option>
                    <option value="Libur" {{ old('editjenisLembur') == 'Libur' ? 'selected' : '' }}>Libur</option>
                </select>
                @error('editjenisLembur')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editjamMasuk">Jam Masuk</label>
                <input
                    type="time"
                    class="form-control"
                    id="editjamMasuk"
                    name="editjamMasuk"
                    value="{{ old('editjamMasuk') }}"
                    required
                />
                @error('editjamMasuk')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editjamKeluar">Jam Keluar</label>
                <input
                    type="time"
                    class="form-control"
                    id="editjamKeluar"
                    name="editjamKeluar"
                    value="{{ old('editjamKeluar') }}"
                    required
                />
                <button type="button" class="btn btn-secondary" onclick="updateEditJamKerjaLembur()">Recalculate Total Jam Lembur</button>
                @error('editjamKeluar')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editgaji">Gaji (Rp)</label>
                <input
                    type="number"
                    class="form-control"
                    id="editgaji"
                    name="editgaji"
                    value="{{ old('editgaji') }}"
                    required
                    readOnly
                />
                <span>*Isi REG Karyawan dan Nama Lengkap secara berurutan untuk memunculkan Gaji</span><br>
                <button type="button" class="btn btn-secondary" onclick="editcalculateUpahLembur()">Recalculate Upah Lembur</button>
                @error('editgaji')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editjamKerjaLembur">Total Waktu Kerja</label>
                <input
                    type="number"
                    class="form-control"
                    id="editjamKerjaLembur"
                    name="editjamKerjaLembur"
                    value="{{ old('editjamKerjaLembur') }}"
                    readOnly
                />
                @error('editjamKerjaLembur')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editjamI">Jam I</label>
                <input
                    type="number"
                    class="form-control"
                    id="editjamI"
                    name="editjamI"
                    value="{{ old('editjamI') }}"
                    readOnly
                />
                @error('editjamI')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editjamII">Jam II</label>
                <input
                    type="number"
                    class="form-control"
                    id="editjamII"
                    name="editjamII"
                    value="{{ old('editjamII') }}"
                    readOnly
                />
                @error('editjamII')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editjamIII">Jam III</label>
                <input
                    type="number"
                    class="form-control"
                    id="editjamIII"
                    name="editjamIII"
                    value="{{ old('editjamIII') }}"
                    readOnly
                />
                @error('editjamIII')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editjamIV">Jam IV</label>
                <input
                    type="number"
                    class="form-control"
                    id="editjamIV"
                    name="editjamIV"
                    value="{{ old('editjamIV') }}"
                    readOnly
                />
                @error('editjamIV')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="edittotalJamLembur">Total Jam Lembur</label>
                <input
                    type="number"
                    class="form-control"
                    id="edittotalJamLembur"
                    name="edittotalJamLembur"
                    value="{{ old('edittotalJamLembur') }}"
                    readOnly
                />
                @error('edittotalJamLembur')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editupahLembur">Upah Lembur (Rp)</label>
                <input
                    type="number"
                    class="form-control"
                    id="editupahLembur"
                    name="editupahLembur"
                    value="{{ old('editupahLembur') }}"
                    readOnly
                />
                @error('editupahLembur')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editKeterangan">Keterangan</label>
                <textarea
                    class="form-control"
                    id="editKeterangan"
                    name="editKeterangan"
                >{{ old('editKeterangan') }}</textarea>
                @error('editKeterangan')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>


    <!-- Modal for Deleting Data -->
    <div id="deleteLemburModal" class="modal">
      <div class="modal-content">
        <span class="close" id="closeDeleteLemburModal">&times;</span>
        <h3 style="margin-bottom: 30px">
          <strong>Hapus Data Lembur</strong>
        </h3>
        <p>Apakah Anda yakin ingin menghapus data lembur ini?</p>
        <form id="deleteLemburForm" method="POST">
        @csrf
        @method('DELETE')
          <input type="hidden" id="deleteLemburID" name="deleteLemburID" />
          <button type="submit" class="btn btn-danger">Hapus</button>
          <button
            type="button"
            class="btn btn-secondary"
            id="cancelLemburDelete"
          >
            Batal
          </button>
        </form>
      </div>
    </div>

<!-- Modal for Date Range Selection -->
<div id="dateRangeModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closedataRangeModal">&times;</span>
        <h2>Select Date Range</h2>
        <form id="dateRangeForm" action="{{ route('export.excel') }}" method="GET">
            <!-- <div class="form-group">
                <label for="id_karyawan">REG Karyawan:</label>
                <input type="text" id="id_karyawan" name="id_karyawan" class="form-control">
            </div> -->
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap:</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required class="form-control">
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Export</button>
        </form>
    </div>
</div>


<!-- Modal -->
<div class="modal" id="dateFilterModal">
    <div class="modal-content">
        <span class="close" id="closedataFilterModal">&times;</span>
        <h2 class="modal-title" id="dateFilterModalLabel">Filter Lembur Records</h2>
        <div class="modal-body">
            <form id="filterForm" method="GET" action="{{ route('perhitungan-lembur') }}">
                @csrf
                <!-- <div class="form-group">
                    <label for="id_karyawan">REG Karyawan</label>
                    <input type="text" class="form-control" id="id_karyawan2" name="id_karyawan2">
                </div> -->
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap2" name="nama_lengkap2" required>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date">
                </div>       
                <div class="form-group d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                    <button type="button" id="resetFilterButton" class="btn btn-secondary">Reset Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Column Visibility Modal -->
<div id="columnVisibilityModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeColumnVisibilityModal">&times;</span>
        <h2 class="modal-title">Column Visibility</h2>
        <form id="columnVisibilityForm">
            <div class="form-group">
                <label><input type="checkbox" id="colNo" checked> No</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colIdKaryawan" checked> REG Karyawan</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colNamaLengkap" checked> Nama Lengkap</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colTanggalLembur" checked> Tanggal Lembur</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colJenisLembur" checked> Jenis Lembur</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colJamMasuk" checked> Jam Masuk</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colJamKeluar" checked> Jam Keluar</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colGaji" checked> Gaji</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colJamKerjaLembur" checked> Jam Kerja Lembur</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colJamI" checked> Jam I</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colJamII" checked> Jam II</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colJamIII" checked> Jam III</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colJamIV" checked> Jam IV</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colUpahLembur" checked> Upah Lembur</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="colKeterangan" checked> Keterangan</label>
            </div>
        </form>
    </div>
</div>
<!-- Print Filter Modal -->
<div id="printModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closePrintModal">&times;</span>
        <h2 class="modal-title">Print Filters</h2>
        <div class="modal-body">
            <form id="printForm" method="GET" action="{{ route('printableView') }}">
                @csrf
                <!-- <div class="form-group">
                    <label for="printid_karyawan2">REG Karyawan</label>
                    <input type="text" class="form-control" id="printid_karyawan2" name="printid_karyawan2">
                </div> -->
                <div class="form-group">
                    <label for="printnama_lengkap2">Nama Lengkap</label>
                    <input type="text" class="form-control" id="printnama_lengkap2" name="printnama_lengkap2" required>
                </div>
                <div class="form-group">
                    <label for="printstart_date">Start Date</label>
                    <input type="date" class="form-control" id="printstart_date" name="printstart_date">
                </div>
                <div class="form-group">
                    <label for="printend_date">End Date</label>
                    <input type="date" class="form-control" id="printend_date" name="printend_date">
                </div>       
                <div class="form-group d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Print Filtered Data</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Printable View for Printing -->
<div id="printableView" style="display: none;">
    <h2>Lembur Records</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th >No</th>
                <th >REG Karyawan</th>
                <th >Nama Lengkap</th>
                <th >Tanggal Lembur</th>
                <th >Jenis Lembur</th>
                <th >Jam Masuk</th>
                <th >Jam Keluar</th>
                <th >Gaji</th>
                <th >Jam Kerja Lembur</th>
                <th >Jam I</th>
                <th >Jam II</th>
                <th >Jam III</th>
                <th >Jam IV</th>
                <th >Upah Lembur</th>
                <th >Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lemburRecords as $lembur)
            <tr>
                <td >{{ $loop->iteration }}</td>
                <td >{{ $lembur->id_karyawan }}</td>
                <td >{{ $lembur->nama_lengkap }}</td>
                <td >{{ $lembur->formatted_tanggal_lembur }}</td>
                <td >{{ $lembur->jenis_lembur }}</td>
                <td >{{ $lembur->jam_masuk->format('H:i') }}</td>
                <td >{{ $lembur->jam_keluar->format('H:i') }}</td>
                <td >{{ 'Rp. ' . number_format($lembur->gaji, 0, ',', '.') }}</td>
                <td >{{ number_format($lembur->jam_kerja_lembur, 1, ',', '.') }}</td>
                <td >{{ number_format($lembur->jam_i, 1, ',', '.') }}</td>
                <td >{{ number_format($lembur->jam_ii, 1, ',', '.') }}</td>
                <td >{{ number_format($lembur->jam_iii, 1, ',', '.') }}</td>
                <td >{{ number_format($lembur->jam_iv, 1, ',', '.') }}</td>
                <td >{{ 'Rp. ' . number_format($lembur->upah_lembur, 0, ',', '.') }}</td>
                <td >{{ $lembur->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
    <script>
    // Get modal elements
    var addModal = document.getElementById("addLemburModal");
    var editModal = document.getElementById("editLemburModal");
    var deleteModal = document.getElementById("deleteLemburModal");

    // Get open modal buttons
    var addBtn = document.getElementById("tambahDataLembur");
    var editButtons = document.getElementsByClassName("edit-buttonLembur");
    var deleteButtons = document.getElementsByClassName("delete-buttonLembur");

    // Get close buttons
    var closeAddModal = document.getElementById("closeAddLemburModal");
    var closeEditModal = document.getElementById("closeEditLemburModal");
    var closeDeleteModal = document.getElementById("closeDeleteLemburModal");
    var cancelDelete = document.getElementById("cancelLemburDelete");

    // Function to open Add Lembur Modal
    function openAddModal() {
        addModal.style.display = "block";
    }

    // Function to close Add Lembur Modal
    function closeAddModalFunc() {
        addModal.style.display = "none";
    }

// Function to open Edit Lembur Modal
function openEditModal() {
    var button = this; // The clicked button
    var id = button.getAttribute("data-id");
    var idKaryawan = button.getAttribute("data-idkaryawan");
    var nama = button.getAttribute("data-nama");
    var tanggal = button.getAttribute("data-tanggal");
    var jenis = button.getAttribute("data-jenis");
    var jamMasuk = button.getAttribute("data-jammasuk");
    var jamKeluar = button.getAttribute("data-jamkeluar");
    var gaji = button.getAttribute("data-gaji");
    var jamKerjaLembur = button.getAttribute("data-jamkerjalembur");
    var jamI = button.getAttribute("data-jami");
    var jamII = button.getAttribute("data-jamii");
    var jamIII = button.getAttribute("data-jamiii");
    var jamIV = button.getAttribute("data-jamiv");
    var totalJamLembur = button.getAttribute("data-totaljamlembur");
    var upahLembur = button.getAttribute("data-upahlembur");
    var keterangan = button.getAttribute("data-keterangan");

    // Populate the edit modal with data
    document.getElementById("editID").value = id;
    document.getElementById("editIDKaryawan").value = idKaryawan;
    document.getElementById("editnamaLengkap").value = nama;
    document.getElementById("edittanggalLembur").value = tanggal;
    document.getElementById("editjenisLembur").value = jenis;
    document.getElementById("editjamMasuk").value = jamMasuk;
    document.getElementById("editjamKeluar").value = jamKeluar;
    document.getElementById("editgaji").value = gaji;
    document.getElementById("editjamKerjaLembur").value = jamKerjaLembur;
    document.getElementById("editjamI").value = jamI;
    document.getElementById("editjamII").value = jamII;
    document.getElementById("editjamIII").value = jamIII;
    document.getElementById("editjamIV").value = jamIV;
    document.getElementById("edittotalJamLembur").value = totalJamLembur;
    document.getElementById("editupahLembur").value = upahLembur;
    document.getElementById("editKeterangan").value = keterangan;

      // Display the edit modal
      editModal.style.display = "block";
}


// Ensure editModal is defined and properly selected
var editModal = document.getElementById("editLemburModal");

// Select all edit buttons and add click event listeners
var editButtons = document.querySelectorAll('.edit-buttonLembur');
editButtons.forEach(function (button) {
    button.addEventListener("click", openEditModal);
});



    // Function to close Edit Lembur Modal
    function closeEditModalFunc() {
        editModal.style.display = "none";
    }

    // Function to open Delete Lembur Modal
    function openDeleteModal() {
        var employeeID = this.closest("tr").querySelector("td:nth-child(3)").innerText;
        document.getElementById("deleteLemburID").value = employeeID;
        deleteModal.style.display = "block";
    }

    // Function to close Delete Lembur Modal
    function closeDeleteModalFunc() {
        deleteModal.style.display = "none";
    }

    // Listen for open click for Add Lembur Modal
    addBtn.addEventListener("click", openAddModal);

    // Listen for open click for Delete Lembur Modal
    Array.from(deleteButtons).forEach(function (button) {
        button.addEventListener("click", openDeleteModal);
    });

    // Listen for close click for Add Lembur Modal
    closeAddModal.addEventListener("click", closeAddModalFunc);

    // Listen for close click for Edit Lembur Modal
    closeEditModal.addEventListener("click", closeEditModalFunc);

    // Listen for close click for Delete Lembur Modal
    closeDeleteModal.addEventListener("click", closeDeleteModalFunc);
    cancelDelete.addEventListener("click", closeDeleteModalFunc);



     // Add Lembur Modal

// Determine if a date is a weekend
function isAddWeekend(date) {
  var day = new Date(date).getDay();
  return day === 0 || day === 6;
}

// Set default times based on date and jenisLembur
function setAddDefaultTimes(date) {
  var jamMasuk = document.getElementById("addjamMasuk");
  var jamKeluar = document.getElementById("addjamKeluar");
  var jenisLembur = document.getElementById("addjenisLembur").value;

  if (jenisLembur === "Weekend" || jenisLembur === "Libur") {
    jamMasuk.value = "";
    jamKeluar.value = "";
    jamMasuk.readOnly = false;
    jamKeluar.readOnly = false;
  } else if (jenisLembur === "Hari Biasa") {
    jamMasuk.value = "07:30";
    jamKeluar.value = "";
    jamMasuk.readOnly = false;
    jamKeluar.readOnly = false;
  }
}
document
  .getElementById("addjenisLembur")
  .addEventListener("change", function () {
    var date = document.getElementById("addtanggalLembur").value;
    setAddDefaultTimes(date);
  });

// Calculate time difference in hours
function calculateAddTimeDifference(start, end) {
  var startTime = new Date("1970-01-01T" + start + ":00Z");
  var endTime = new Date("1970-01-01T" + end + ":00Z");
  return (endTime - startTime) / 1000 / 60 / 60;
}

function roundAddToHalfHour(value) {
    // Multiply by 2 to get to the nearest 0.5, then floor it to always round down
    return Math.floor(value * 2) / 2;
}

// Examples:
// roundAddToHalfHour(0.75) -> 0.5
// roundAddToHalfHour(0.4)  -> 0.0
// roundAddToHalfHour(1.3)  -> 1.0
// roundAddToHalfHour(2.7)  -> 2.5


// Update addjamKerjaLembur based on addjamKeluar
function updateAddJamKerjaLembur() {
  var tanggalLembur = document.getElementById("addtanggalLembur").value;
  var jamMasuk = document.getElementById("addjamMasuk").value;
  var jamKeluar = document.getElementById("addjamKeluar").value;
  var jamKerjaLembur = document.getElementById("addjamKerjaLembur");
  var jenisLembur = document.getElementById("addjenisLembur").value;

  if (!tanggalLembur || !jamKeluar) {
    jamKerjaLembur.value = "";
    calculateAddTotalJamLembur();
    return;
  }

  if (jenisLembur === "Weekend" || jenisLembur === "Libur") {
    if (jamMasuk && jamKeluar) {
      var difference = calculateAddTimeDifference(jamMasuk, jamKeluar);
      jamKerjaLembur.value = roundAddToHalfHour(difference).toFixed(1);
    } else {
      jamKerjaLembur.value = "";
    }
  } else {
    var startNormal = "07:30";
    var totalOvertime = 0;
    var dayOfWeek = new Date(tanggalLembur).getDay();
    var endOfDay = dayOfWeek === 5 ? "15:30" : "16:30";
   // Calculate the time difference and ensure negative values are set to 0
var difference3 = Math.max(0, calculateAddTimeDifference(endOfDay, jamKeluar));
var difference2 = Math.max(0, calculateAddTimeDifference(jamMasuk, startNormal));
    var difference = difference3 + difference2;
    jamKerjaLembur.value = roundAddToHalfHour(difference).toFixed(1);
  }

  updateAddJamCategories();
}

// Update jamI, jamII, jamIII, and jamIV based on jenisLembur
function updateAddJamCategories() {
  var jenisLembur = document.getElementById("addjenisLembur").value;
  var jamKerjaLembur =
    parseFloat(document.getElementById("addjamKerjaLembur").value) || 0;

  var jamI = document.getElementById("addjamI");
  var jamII = document.getElementById("addjamII");
  var jamIII = document.getElementById("addjamIII");
  var jamIV = document.getElementById("addjamIV");

  jamI.value = "";
  jamII.value = "";
  jamIII.value = "";
  jamIV.value = "";

  if (jenisLembur === "Hari Biasa") {
    if (jamKerjaLembur > 0) {
      jamI.value = Math.min(jamKerjaLembur, 1).toFixed(1);
      jamII.value = (
        jamKerjaLembur > 1 ? jamKerjaLembur - 1 : ""
      ).toFixed(1);
    }
  } else if (jenisLembur === "Weekend" || jenisLembur === "Libur") {
    if (jamKerjaLembur > 0) {
      jamII.value = Math.min(jamKerjaLembur, 8).toFixed(1);
      jamIII.value =
        jamKerjaLembur > 8
          ? Math.min(jamKerjaLembur - 8, 1).toFixed(1)
          : "";
      jamIV.value =
        jamKerjaLembur > 9 ? (jamKerjaLembur - 9).toFixed(1) : "";
    }
  }

  calculateAddTotalJamLembur();
}

function calculateAddTotalJamLembur() {
  var jamI = parseFloat(document.getElementById("addjamI").value) || 0;
  var jamII = parseFloat(document.getElementById("addjamII").value) || 0;
  var jamIII =
    parseFloat(document.getElementById("addjamIII").value) || 0;
  var jamIV = parseFloat(document.getElementById("addjamIV").value) || 0;

  var totalJamLembur = jamI * 1.5 + jamII * 2 + jamIII * 3 + jamIV * 4;
  document.getElementById("addtotalJamLembur").value =
    totalJamLembur.toFixed(1);
}


function addcalculateUpahLembur() {
    // Ambil nilai gaji dan total jam lembur
    var gaji =
        parseFloat(
            document.getElementById("addgaji").value.replace(/[^0-9.]/g, "")
        ) || 0;
    var totalJamLembur =
        parseFloat(document.getElementById("addtotalJamLembur").value) || 0;

    // Pastikan gaji dan totalJamLembur bukan NaN
    if (isNaN(gaji) || isNaN(totalJamLembur)) {
        document.getElementById("addupahLembur").value = '';
        return;
    }
    
    // Hitung hourly wage
    var hourlyWage = Math.round(gaji / 173);
    
    // Hitung upah lembur
    var upahLembur = hourlyWage * totalJamLembur;
    
    // Set nilai ke input dan pastikan bulat
    document.getElementById("addupahLembur").value = Math.round(upahLembur);
}





// Function to calculate upah lembur in edit mode
function editcalculateUpahLembur() {
    // Retrieve and parse the gaji value, ensuring no decimals
    var gaji = parseFloat(
        document.getElementById("editgaji").value.replace(/[^0-9.]/g, "")
    ).toFixed(0) || 0; // Convert to integer-like value

    var totalJamLembur = parseFloat(
        document.getElementById("edittotalJamLembur").value
    ) || 0;

    // Calculate hourly wage and round to nearest whole number
    var hourlyWage = parseFloat(gaji) / 173;
    hourlyWage = Math.round(hourlyWage);

    // Calculate upah lembur
    var upahLembur = hourlyWage * totalJamLembur;

    // Set the values directly, with no decimals
    document.getElementById("editgaji").value = parseFloat(gaji).toFixed(0);
    document.getElementById("editupahLembur").value = upahLembur.toFixed(0);
}




// Event listeners for Add Lembur Modal
document
  .getElementById("addjamKeluar")
  .addEventListener("change", addcalculateUpahLembur);
  document
  .getElementById("editjamKeluar")
  .addEventListener("change", editcalculateUpahLembur);
document
  .getElementById("addgaji")
  .addEventListener("change", addcalculateUpahLembur);
  document
  .getElementById("editgaji")
  .addEventListener("change", editcalculateUpahLembur);
document
  .getElementById("addjenisLembur")
  .addEventListener("change", updateAddJamCategories);
document
  .getElementById("addjamKerjaLembur")
  .addEventListener("input", updateAddJamCategories);
document
  .getElementById("addjamKeluar")
  .addEventListener("change", updateAddJamKerjaLembur);
document
  .getElementById("addjamI")
  .addEventListener("input", calculateAddTotalJamLembur);
document
  .getElementById("addjamII")
  .addEventListener("input", calculateAddTotalJamLembur);
document
  .getElementById("addjamIII")
  .addEventListener("input", calculateAddTotalJamLembur);
document
  .getElementById("addjamIV")
  .addEventListener("input", calculateAddTotalJamLembur);
document
  .getElementById("addtanggalLembur")
  .addEventListener("change", function (e) {
    var date = e.target.value;
    var jenisLembur = document.getElementById("addjenisLembur");

    jenisLembur.value = isAddWeekend(date)
      ? "Weekend" || "Libur"
      : "Hari Biasa";
    setAddDefaultTimes(date);
  });
document
  .getElementById("addjenisLembur")
  .addEventListener("change", function () {
    updateAddJamCategories();

    if (
      document.getElementById("addjenisLembur").value === "Weekend" ||
      document.getElementById("addjenisLembur").value === "Libur"
    ) {
      document.getElementById("addjamMasuk").value = "";
      document.getElementById("addjamKeluar").value = "";
      document.getElementById("addjamMasuk").readOnly = false;
    } else {
      document.getElementById("addjamMasuk").value = "07:30";
      document.getElementById("addjamKeluar").readOnly = false;
    }
  });
// Edit Lembur Modal
// Handle input for editIDKaryawan field
document
  .getElementById("editIDKaryawan")
  .addEventListener("input", function (e) {
    var value = e.target.value;
    var isValid = /^\d*$/.test(value);
    document.getElementById("editIDKaryawanError").style.display = isValid
      ? "none"
      : "block";
    e.target.value = value.replace(/\D/g, "");
  });

// Determine if a date is a weekend
function isEditWeekend(date) {
  var day = new Date(date).getDay();
  return day === 0 || day === 6;
}

// Set default times based on date and jenisLembur
function setEditDefaultTimes(date) {
  var jamMasuk = document.getElementById("editjamMasuk");
  var jamKeluar = document.getElementById("editjamKeluar");
  var jenisLembur = document.getElementById("editjenisLembur").value;

  if (jenisLembur === "Weekend" || jenisLembur === "Libur") {
    jamMasuk.readOnly = false;
    jamKeluar.readOnly = false;
  } else if (jenisLembur === "Hari Biasa") {
    jamMasuk.value = "07:30";
    jamMasuk.readOnly = false;
 jamKeluar.readOnly = false;
  }
}

document
  .getElementById("editjenisLembur")
  .addEventListener("change", function () {
    var date = document.getElementById("edittanggalLembur").value;
    setEditDefaultTimes(date);
  });

// Calculate time difference in hours
function calculateEditTimeDifference(start, end) {
  var startTime = new Date("1970-01-01T" + start + ":00Z");
  var endTime = new Date("1970-01-01T" + end + ":00Z");
  return (endTime - startTime) / 1000 / 60 / 60;
}

// Round hours to the nearest 0.5
function roundEditToHalfHour(value) {
  return Math.round(value * 2) / 2;
}

// Update editjamKerjaLembur based on editjamKeluar
function updateEditJamKerjaLembur() {
  var tanggalLembur = document.getElementById("edittanggalLembur").value;
  var jamMasuk = document.getElementById("editjamMasuk").value;
  var jamKeluar = document.getElementById("editjamKeluar").value;
  var jamKerjaLembur = document.getElementById("editjamKerjaLembur");
  var jenisLembur = document.getElementById("editjenisLembur").value;

  if (!tanggalLembur || !jamKeluar) {
    jamKerjaLembur.value = "";
    calculateEditTotalJamLembur();
    return;
  }

  if (jenisLembur === "Weekend" || jenisLembur === "Libur") {
    if (jamMasuk && jamKeluar) {
      var difference = calculateEditTimeDifference(jamMasuk, jamKeluar);
      jamKerjaLembur.value = roundEditToHalfHour(difference).toFixed(1);
    } else {
      jamKerjaLembur.value = "";
    }
  } else {
    var dayOfWeek = new Date(tanggalLembur).getDay();
    var endOfDay = dayOfWeek === 5 ? "15:30" : "16:30";
    var difference = calculateEditTimeDifference(endOfDay, jamKeluar);
    jamKerjaLembur.value = roundEditToHalfHour(difference).toFixed(1);
  }

  updateEditJamCategories();
}

// Update jamI, jamII, jamIII, and jamIV based on jenisLembur
function updateEditJamCategories() {
  var jenisLembur = document.getElementById("editjenisLembur").value;
  var jamKerjaLembur =
    parseFloat(document.getElementById("editjamKerjaLembur").value) || 0;

  var jamI = document.getElementById("editjamI");
  var jamII = document.getElementById("editjamII");
  var jamIII = document.getElementById("editjamIII");
  var jamIV = document.getElementById("editjamIV");

  jamI.value = "";
  jamII.value = "";
  jamIII.value = "";
  jamIV.value = "";

  if (jenisLembur === "Hari Biasa") {
    if (jamKerjaLembur > 0) {
      jamI.value = Math.min(jamKerjaLembur, 1).toFixed(1);
      jamII.value = (
        jamKerjaLembur > 1 ? jamKerjaLembur - 1 : ""
      ).toFixed(1);
    }
  } else if (jenisLembur === "Weekend" || jenisLembur === "Libur") {
    if (jamKerjaLembur > 0) {
      jamII.value = Math.min(jamKerjaLembur, 8).toFixed(1);
      jamIII.value =
        jamKerjaLembur > 8
          ? Math.min(jamKerjaLembur - 8, 1).toFixed(1)
          : "";
      jamIV.value =
        jamKerjaLembur > 9 ? (jamKerjaLembur - 9).toFixed(1) : "";
    }
  }

  calculateEditTotalJamLembur();
}

function calculateEditTotalJamLembur() {
  var jamI = parseFloat(document.getElementById("editjamI").value) || 0;
  var jamII = parseFloat(document.getElementById("editjamII").value) || 0;
  var jamIII =
    parseFloat(document.getElementById("editjamIII").value) || 0;
  var jamIV = parseFloat(document.getElementById("editjamIV").value) || 0;

  var totalJamLembur = jamI * 1.5 + jamII * 2 + jamIII * 3 + jamIV * 4;
  document.getElementById("edittotalJamLembur").value =
    totalJamLembur.toFixed(1);
}

// Event listeners for Edit Lembur Modal
document
  .getElementById("editjenisLembur")
  .addEventListener("change", updateEditJamCategories);
document
  .getElementById("editjamKerjaLembur")
  .addEventListener("input", updateEditJamCategories);
document
  .getElementById("editjamKeluar")
  .addEventListener("change", updateEditJamKerjaLembur.resetUpahLembur);
document
  .getElementById("editjamI")
  .addEventListener("input", calculateEditTotalJamLembur);
document
  .getElementById("editjamII")
  .addEventListener("input", calculateEditTotalJamLembur);
document
  .getElementById("editjamIII")
  .addEventListener("input", calculateEditTotalJamLembur);
document
  .getElementById("editjamIV")
  .addEventListener("input", calculateEditTotalJamLembur);
document
  .getElementById("edittanggalLembur")
  .addEventListener("change", function (e) {
    var date = e.target.value;
    var jenisLembur = document.getElementById("editjenisLembur");

    jenisLembur.value = isEditWeekend(date)
      ? "Weekend" || "Libur"
      : "Hari Biasa";
    setEditDefaultTimes(date);
  }.resetUpahLembur);
document
  .getElementById("editjenisLembur")
  .addEventListener("change", function () {
    updateEditJamCategories();

    if (
      document.getElementById("editjenisLembur").value === "Weekend" ||
      document.getElementById("editjenisLembur").value === "Libur"
    ) {
      document.getElementById("editjamMasuk").value = "";
      document.getElementById("editjamKeluar").value = "";
      document.getElementById("editjamMasuk").readOnly = false;
    } else {
      document.getElementById("editjamMasuk").value = "07:30";
      document.getElementById("editjamMasuk").readOnly = false;
    }
  });    

      //Batas Delete Lembur Modal

      function checkDate(prefix) {
        var dateField = document.getElementById(prefix + "tanggalLembur");
        var jenisLemburField = document.getElementById(prefix + "jenisLembur");

        var selectedDate = new Date(dateField.value);
        var dayOfWeek = selectedDate.getDay(); // 0 (Sunday) to 6 (Saturday)

        var weekendOption = jenisLemburField.querySelector(
          'option[value="Weekend"]'
        );
        var hariBiasaOption = jenisLemburField.querySelector(
          'option[value="Hari Biasa"]'
        );
        var liburOption = jenisLemburField.querySelector(
          'option[value="Libur"]'
        );

        if (dayOfWeek === 0 || dayOfWeek === 6) {
          // Weekend
          weekendOption.disabled = false;
          hariBiasaOption.disabled = true;
          liburOption.disabled = false;
        } else {
          // Weekday
          weekendOption.disabled = true;
          hariBiasaOption.disabled = false;
          liburOption.disabled = false;
          // If currently selected value is 'Weekend', change it to 'Hari Biasa'
          if (jenisLemburField.value === "Weekend") {
            jenisLemburField.value = "Hari Biasa";
          }
        }
      }


      // Function to open delete modal
    function openDeleteModal() {
        var employeeID =
            this.closest("tr").querySelector("td:nth-child(2)").innerText;
        document.getElementById("deleteLemburID").value = employeeID;
        deleteModal.style.display = "block";
        
        // Set the delete form action URL dynamically
        var deleteForm = document.getElementById("deleteLemburForm");
        deleteForm.action = `/delete-lembur/${employeeID}`;
    }

    $(document).ready(function() {
        // Autocomplete for ID Karyawan
        $("#addIDKaryawan").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('autocomplete.id_karyawan') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2 // Minimum number of characters to trigger the autocomplete
        });

        // Autocomplete for Nama Lengkap
        $("#addnamaLengkap").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('autocomplete.nama_lengkap') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2 // Minimum number of characters to trigger the autocomplete
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
    const idKaryawanInput = document.getElementById('addIDKaryawan');
    const namaLengkapInput = document.getElementById('addnamaLengkap');
    const gajiInput = document.getElementById('addgaji');

    function editupdateGaji() {
        const idKaryawan = idKaryawanInput.value;
        const namaLengkap = namaLengkapInput.value;

        // Make an AJAX request to fetch the gaji
        fetch(`/get-gaji?id_karyawan=${idKaryawan}&nama_lengkap=${namaLengkap}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.gaji) {
                    gajiInput.value = data.gaji;
                } else {
                    gajiInput.value = ''; // Clear the gaji if no match
                }
            })
            .catch(error => {
                console.error('Error fetching gaji:', error);
                gajiInput.value = ''; // Clear the gaji if there is an error
            });
    }

    idKaryawanInput.addEventListener('input', editupdateGaji);
    namaLengkapInput.addEventListener('input', editupdateGaji);
});

$(document).ready(function() {
        // Autocomplete for ID Karyawan
        $("#editIDKaryawan").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('autocomplete.id_karyawan') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1 // Minimum number of characters to trigger the autocomplete
        });

        // Autocomplete for Nama Lengkap
        $("#editnamaLengkap").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('autocomplete.nama_lengkap') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1 // Minimum number of characters to trigger the autocomplete
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
    const idKaryawanInput = document.getElementById('editIDKaryawan');
    const namaLengkapInput = document.getElementById('editnamaLengkap');
    const gajiInput = document.getElementById('editgaji');

    function updateGaji() {
        const idKaryawan = idKaryawanInput.value;
        const namaLengkap = namaLengkapInput.value;

        // Make an AJAX request to fetch the gaji
        fetch(`/get-gaji?id_karyawan=${idKaryawan}&nama_lengkap=${namaLengkap}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.gaji) {
                    gajiInput.value = data.gaji;
                } else {
                    gajiInput.value = ''; // Clear the gaji if no match
                }
            })
            .catch(error => {
                console.error('Error fetching gaji:', error);
                gajiInput.value = ''; // Clear the gaji if there is an error
            });
    }

    idKaryawanInput.addEventListener('input', updateGaji);
    namaLengkapInput.addEventListener('input', updateGaji);
});

// Function to format a date string to yyyy-mm-dd
function formatDateForInput(dateString) {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

// Open Modal
document.getElementById('exportButton').onclick = function() {
    document.getElementById('dateRangeModal').style.display = 'block';
};

// Close Modal when clicking on the close button
document.getElementById('closedataRangeModal').onclick = function() {
    document.getElementById('dateRangeModal').style.display = 'none';
};

// Open Modal
document.getElementById('filterButton').onclick = function() {
    document.getElementById('dateFilterModal').style.display = 'block';
};

// Close Modal when clicking on the close button
document.getElementById('closedataFilterModal').onclick = function() {
    document.getElementById('dateFilterModal').style.display = 'none';
};
// Reset button functionality
document.getElementById('resetFilterButton').onclick = function() {
    document.getElementById('filterForm').reset();
    // Trigger form submission with empty filters to show all data
    document.getElementById('filterForm').submit();
};

// Open Modal
document.querySelector('.btn-warning').addEventListener('click', function() {
    document.getElementById('columnVisibilityModal').style.display = 'block';
});

// Close Modal when clicking on the close button
document.getElementById('closeColumnVisibilityModal').onclick = function() {
    document.getElementById('columnVisibilityModal').style.display = 'none';
};

// Update column visibility based on checkbox status
document.getElementById('columnVisibilityForm').addEventListener('change', function(event) {
    const checkbox = event.target;
    if (checkbox.type === 'checkbox') {
        const columnClass = 'col-' + checkbox.id.replace('col', '').toLowerCase();
        const columns = document.querySelectorAll('.' + columnClass);
        columns.forEach(column => {
            column.style.display = checkbox.checked ? '' : 'none';
        });
    }
});


$(document).ready(function() {
        // Autocomplete for ID Karyawan
        $("#id_karyawan").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('autocomplete.id_karyawan') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1 // Minimum number of characters to trigger the autocomplete
        });

        // Autocomplete for Nama Lengkap
        $("#nama_lengkap").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('autocomplete.nama_lengkap') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1 // Minimum number of characters to trigger the autocomplete
        });
    });

    $(document).ready(function() {
        // Autocomplete for ID Karyawan
        $("#id_karyawan2").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('autocomplete.id_karyawan') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1 // Minimum number of characters to trigger the autocomplete
        });

        // Autocomplete for Nama Lengkap
        $("#nama_lengkap2").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('autocomplete.nama_lengkap') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1 // Minimum number of characters to trigger the autocomplete
        });
    });

    document.getElementById('openPrintModal').addEventListener('click', function() {
        document.getElementById('printModal').style.display = 'block';
    });

    document.getElementById('closePrintModal').addEventListener('click', function() {
        document.getElementById('printModal').style.display = 'none';
    });

    window.onclick = function(event) {
        if (event.target == document.getElementById('printModal')) {
            document.getElementById('printModal').style.display = 'none';
        }
    };


document.getElementById('printForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    
    var form = this;
    var action = form.action;
    var formData = new FormData(form);

    var url = new URL(action);
    url.search = new URLSearchParams(formData).toString();

    window.open(url, '_blank'); // Open the printable view in a new tab

    // Optionally, you can close the modal after submitting
    document.getElementById('printModal').style.display = 'none';
});

$(document).ready(function() {
        // Autocomplete for ID Karyawan
        $("#printid_karyawan2").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('autocomplete.id_karyawan') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1 // Minimum number of characters to trigger the autocomplete
        });

        // Autocomplete for Nama Lengkap
        $("#printnama_lengkap2").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('autocomplete.nama_lengkap') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1 // Minimum number of characters to trigger the autocomplete
        });
    });

    // Function to close any open modal if clicking outside
function closeModalOnOutsideClick(e) {
    // List of all modals
    const modals = document.querySelectorAll('.modal');

    // Check if click was outside of any modal
    modals.forEach(modal => {
        if (e.target === modal) {
            // Close the modal
            modal.style.display = 'none';
        }
    });
}

// Event listener for outside click
window.addEventListener("click", closeModalOnOutsideClick);

// Function to open modal (example)
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
    }
}

// Function to close modal (example)
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
            // Function to format date as "Jumat, 09 Agustus 2024"
            function formatDate(date) {
                const daysOfWeek = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
                const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                
                const dayOfWeek = daysOfWeek[date.getDay()];
                const day = date.getDate().toString().padStart(2, '0'); // Add leading zero if needed
                const month = months[date.getMonth()];
                const year = date.getFullYear();
                
                return `${dayOfWeek}, ${day} ${month} ${year}`;
            }

            // Get today's date
            const today = new Date();

            // Format the date
            const formattedDate = formatDate(today);

            // Set the date in the <h5> element
            document.getElementById('todayDate').innerText = formattedDate;
        });

        document.querySelectorAll('.delete-buttonLembur').forEach(button => {
    button.addEventListener('click', function () {
        const lemburId = this.getAttribute('data-id');
        document.getElementById('deleteLemburID').value = lemburId;
        document.getElementById('deleteLemburForm').action = '/lembur/' + lemburId; // Set the action URL dynamically
        document.getElementById('deleteLemburModal').style.display = 'block';
    });
});

// Close the modal
document.getElementById('closeDeleteLemburModal').addEventListener('click', function() {
    document.getElementById('deleteLemburModal').style.display = 'none';
});

document.getElementById('cancelLemburDelete').addEventListener('click', function() {
    document.getElementById('deleteLemburModal').style.display = 'none';
});

    </script>
  </body>
</html>