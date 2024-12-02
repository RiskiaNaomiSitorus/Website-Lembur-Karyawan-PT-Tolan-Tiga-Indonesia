<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Data Karyawan</title>
    <link rel="stylesheet" href="{{ asset ('assets/styles.css')}}" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    />
    <style>
      html,
      body {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
      }
      .wrapper {
        display: flex;
        flex-direction: column;
        flex: 1;
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
      .main_content {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 20px;
        margin-left: 220px; /* Adjust the margin to account for the sidebar */
      }
      .sidebar {
        position: fixed;
        height: 100%;
        width: 220px;
        background-color: #f8f9fa;
        padding-top: 20px;
      }
      .header {
        width: 100%;
        background-color: #f8f9fa;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      .header-section {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        margin-bottom: 20px;
      }
      .card-header {
        margin: 20px;
        border: 1px solid #11a634;
        border-radius: 0; /* Menghapus border-radius untuk membuat persegi */
        box-shadow: 0 2px 4px rgba(0, 255, 38, 0.1);
        width: 245px; /* Menentukan lebar card */
        height: 40px;
        background-color: #1bbc53;
        color: white;
        display: flex;
        align-items: center; /* Menambahkan padding untuk header */
      }
      .card-header i {
        margin-right: 10px;
      }
      .card-header h4 {
        margin-left: 10px;
        margin: 0;
        flex-shrink: 0;
      }
      .action-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        margin-left: 20px;
      }
      .table-container {
        width: 100%;
        margin-left: 5px;
      }
      .table-container table {
        width: 100%;
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
      .logout-button {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
      }
      .logout-button:hover {
        background-color: #c82333;
      }
      .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
        }
        .dropdown-check-list {
  display: inline-block;
}

.dropdown-check-list .anchor {
  position: relative;
  cursor: pointer;
  display: inline-block;
  padding: 5px 50px 5px 10px;
  border: 1px solid #ccc;
}

.dropdown-check-list .anchor:after {
  position: absolute;
  content: "";
  border-left: 2px solid black;
  border-top: 2px solid black;
  padding: 5px;
  right: 10px;
  top: 20%;
  -moz-transform: rotate(-135deg);
  -ms-transform: rotate(-135deg);
  -o-transform: rotate(-135deg);
  -webkit-transform: rotate(-135deg);
  transform: rotate(-135deg);
}

.dropdown-check-list .anchor:active:after {
  right: 8px;
  top: 21%;
}

.dropdown-check-list ul.items {
  padding: 2px;
  display: none;
  margin: 0;
  border: 1px solid #ccc;
  border-top: none;
}

.dropdown-check-list ul.items li {
  list-style: none;
}

.dropdown-check-list.visible .anchor {
  color: #0094ff;
}

.dropdown-check-list.visible .items {
  display: block;
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
        <div class="dashboard">
          <h2>Data Karyawan</h2>
        </div>
        <div class="header-section">
          <div class="card-header" id="tambahDataKaryawan">
            <i></i>
            <i class="fas fa-plus-circle"></i>
            <h4>Tambah Data Karyawan</h4>
          </div>
          <div class="action-buttons">
          <a href="{{ route('karyawan.export') }}" class="btn btn-primary btn-sm">
    <i class="fas fa-file-excel"></i> Excel
</a>

          <!-- Print Button -->
<button id="printButton" class="btn btn-secondary btn-sm">
    <i class="fas fa-print"></i> Print
</button>
<!-- Button to open the column visibility modal -->
<button class="btn btn-warning btn-sm" id="columnVisibilityButton">
    <i class="fas fa-eye"></i> Column Visibility
</button>
<button class="btn btn-info" id="searchButton">
        <i class="fas fa-search"></i> Search
    </button>
          </div>
        </div>
        <div class="container table-container">
        <table class="table table-striped table-bordered " id="dataTable">
    <thead>
        <tr>
            <th class="col-no">No</th>
            <th class="col-idkaryawan">REG Karyawan</th>
            <th class="col-namakaryawan">Nama Karyawan</th>
            <th class="col-jabatan">Jabatan</th>
            <th class="col-status">Status</th>
            <th class="col-gaji">Gaji</th>
            <th class="col-action">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($karyawan as $item)
        <tr>
            <td class="col-no">{{ $loop->iteration }}</td>
            <td class="col-idkaryawan">{{ $item->id_karyawan }}</td>
            <td class="col-namakaryawan">{{ $item->nama_karyawan }}</td>
            <td class="col-jabatan">{{ $item->jabatan }}</td>
            <td class="col-status">{{ $item->status }}</td>
            <td class="col-gaji">{{ 'Rp. ' . number_format($item->gaji, 0, ',', '.') }}</td>
            <td class="col-action">
                <button 
                    class="btn btn-warning btn-sm edit-button" 
                    data-id="{{ $item->id }}"
                    data-idkaryawan="{{ $item->id_karyawan }}"
                    data-nama="{{ $item->nama_karyawan }}"
                    data-gender="{{ $item->jenis_kelamin }}"
                    data-position="{{ $item->jabatan }}"
                    data-status="{{ $item->status }}"
                    data-salary="{{ $item->gaji }}"
                >
                    Edit
                </button>
                <button class="btn btn-danger btn-sm delete-button">
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
    {{ $karyawan->links() }}
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
     <!-- Modal for Editing Data -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h3 style="margin-bottom: 30px">
            <strong>Edit Data Karyawan</strong>
        </h3>
        <form id="editForm" method="POST" action="{{ route('update-karyawan') }}">
    @csrf
    @method('PUT')
    <input type="hidden" id="editID" name="editID" value="{{ old('editID') }}" />
    <div class="form-group">
        <label for="editIDKaryawan">REG Karyawan</label>
        <input
            type="text"
            class="form-control"
            id="editIDKaryawan"
            name="editIDKaryawan"
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
        <label for="editName">Nama Karyawan</label>
        <input
            type="text"
            class="form-control"
            id="editName"
            name="editName"
            value="{{ old('editName') }}"
            required
        />
        @error('editName')
            <div style="color: red">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group" style="display: none;">
        <label for="editGender">Jenis Kelamin</label>
        <select
            class="form-control"
            id="editGender"
            name="editGender"

        >
            <option value="" selected disabled></option>
            <option value="Laki-laki" {{ old('editGender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="Perempuan" {{ old('editGender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
        @error('editGender')
            <div style="color: red">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group">
        <label for="editPosition">Jabatan</label>
        <input
            type="text"
            class="form-control"
            id="editPosition"
            name="editPosition"
            value="{{ old('editPosition') }}"
            required
        />
        @error('editPosition')
            <div style="color: red">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group">
        <label for="editStatus">Status</label>
        <select
            class="form-control"
            id="editStatus"
            name="editStatus"
            required
        >
            <option value="" selected disabled></option>
            <option value="Aktif" {{ old('editStatus') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="Tidak Aktif" {{ old('editStatus') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
        @error('editStatus')
            <div style="color: red">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group">
        <label for="editSalary">Gaji (Rp)</label>
        <input
            type="number"
            class="form-control"
            id="editSalary"
            name="editSalary"
            value="{{ old('editSalary') }}"
            required
        />
        @error('editSalary')
            <div style="color: red">
                {{ $message }}
            </div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>

    </div>
</div>
 <!-- Modal -->
<div id="searchModal" class="modal">
  <div class="modal-content">
    <span class="close" id="closeSearchModal">&times;</span>
    <h3 style="margin-bottom: 30px">
      <strong>Search Data REG Karyawan</strong>
    </h3>
    <form id="searchId" method="GET" action="{{ route('data-karyawan') }}">
      <div class="form-group">
        <div class="form-outline" data-mdb-input-init>
          <input type="search" name="query" id="form1" class="form-control" placeholder="Search..." />
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Search</button>
      <button type="button" id="resetSearch" class="btn btn-secondary">Reset</button>
    </form>
  </div>
</div>




   <!-- Modal for Deleting Data -->
<div id="deleteModal" class="modal">
  <div class="modal-content">
    <span class="close" id="closeDeleteModal">&times;</span>
    <h3 style="margin-bottom: 30px">
      <strong>Hapus Data Karyawan</strong>
    </h3>
    <p>Apakah Anda yakin ingin menghapus data karyawan ini?</p>
    <form id="deleteForm" method="POST" action="">
      @csrf
      @method('DELETE')
      <input type="hidden" id="deleteID" name="id" />
      <button type="submit" class="btn btn-danger">Hapus</button>
      <button type="button" class="btn btn-secondary" id="cancelDelete">
        Batal
      </button>
    </form>
  </div>
</div>


    <!-- Modal for Adding Data -->
    <div id="addModal" class="modal">
      <div class="modal-content">
        <span class="close" id="closeAddModal">&times;</span>
        <h3 style="margin-bottom: 30px">
          <strong>Tambah Data Karyawan</strong>
        </h3>

        <form id="addForm" method="POST" action="{{ route('store-karyawan') }}">
            @csrf
            <div class="form-group">
                <label for="addID">REG Karyawan</label>
                <input type="text" class="form-control" id="addID" name="id_karyawan" value="{{ old('id_karyawan') }}" required />
                @error('id_karyawan')
                    <div id="addIDKaryawanError" style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="addName">Nama Karyawan</label>
                <input type="text" class="form-control" id="addName" name="nama_karyawan" value="{{ old('nama_karyawan') }}" required />
                @error('nama_karyawan')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="addPosition">Jabatan</label>
                <input type="text" class="form-control" id="addPosition" name="jabatan" value="{{ old('jabatan') }}" required />
                @error('jabatan')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="addStatus">Status</label>
                <select class="form-control" id="addStatus" name="status" required>
                    <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="addSalary">Gaji (Rp)</label>
                <input type="number" class="form-control" id="addSalary" name="gaji" value="{{ old('gaji') }}" required />
                @error('gaji')
                    <div style="color: red">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Add Karyawan</button>
        </form>
      </div>
    </div>

    <!-- Printable View for Printing -->
<div id="printableView" style="display: none;">
    <h2>Data Karyawan</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>REG Karyawan</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th>Status</th>
                <th>Gaji</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($karyawan as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->id_karyawan }}</td>
                <td>{{ $item->nama_karyawan }}</td>
                <td>{{ $item->jabatan }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ 'Rp. ' . number_format($item->gaji, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

    <!-- Column Visibility Modal -->
<div id="columnVisibilityModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" id="closeColumnVisibilityModal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h2 class="modal-title">Column Visibility</h2>
            </div>
            <div class="modal-body">
                <form id="columnVisibilityForm">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="colNo" checked>
                        <label class="form-check-label" for="colNo">No</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="colIdKaryawan" checked>
                        <label class="form-check-label" for="colIdKaryawan">ID Karyawan</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="colNamaKaryawan" checked>
                        <label class="form-check-label" for="colNamaKaryawan">Nama Karyawan</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="colJabatan" checked>
                        <label class="form-check-label" for="colJabatan">Jabatan</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="colStatus" checked>
                        <label class="form-check-label" for="colStatus">Status</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="colGaji" checked>
                        <label class="form-check-label" for="colGaji">Gaji</label>
                    </div>
                    <!-- <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="colAction" checked>
                        <label class="form-check-label" for="colAction">Action</label>
                    </div> -->
                </form>
            </div>
        </div>
    </div>
</div>

    <script>
    // Get modal elements
    var editModal = document.getElementById("editModal");
    var addModal = document.getElementById("addModal");
    var deleteModal = document.getElementById("deleteModal");

    // Get open modal buttons
    var editButtons = document.getElementsByClassName("edit-button");
    var tambahDataKaryawan = document.getElementById("tambahDataKaryawan");
    var deleteButtons = document.getElementsByClassName("delete-button");

    // Get close buttons
    var closeModal = document.getElementById("closeModal");
    var closeAddModal = document.getElementById("closeAddModal");
    var closeDeleteModal = document.getElementById("closeDeleteModal");
    var cancelDelete = document.getElementById("cancelDelete");

    // Listen for open click for edit modal
    Array.from(editButtons).forEach(function (button) {
        button.addEventListener("click", openEditModal);
    });

    // Listen for open click for add modal
    tambahDataKaryawan.addEventListener("click", openAddModal);

    // Listen for open click for delete modal
    Array.from(deleteButtons).forEach(function (button) {
        button.addEventListener("click", openDeleteModal);
    });

    // Listen for close click
    closeModal.addEventListener("click", closeEditModal);
    closeAddModal.addEventListener("click", closeAddModalFunc);
    closeDeleteModal.addEventListener("click", closeDeleteModalFunc);
    cancelDelete.addEventListener("click", closeDeleteModalFunc);

    // Function to open edit modal and populate data
    function openEditModal() {
        var button = this;
        var id = button.getAttribute("data-id");
        var idKaryawan = button.getAttribute("data-idkaryawan");
        var nama = button.getAttribute("data-nama");
        var gender = button.getAttribute("data-gender");
        var position = button.getAttribute("data-position");
        var status = button.getAttribute("data-status");
        var salary = button.getAttribute("data-salary");

        // Populate the edit modal with data
        document.getElementById("editID").value = id;
        document.getElementById("editIDKaryawan").value = idKaryawan;
        document.getElementById("editName").value = nama;
        document.getElementById("editGender").value = gender;
        document.getElementById("editPosition").value = position;
        document.getElementById("editStatus").value = status;
        document.getElementById("editSalary").value = salary;

        editModal.style.display = "block";
    }

    // Function to open add modal
    function openAddModal() {
        addModal.style.display = "block";
    }

    // Function to open delete modal
    function openDeleteModal() {
        var employeeID =
            this.closest("tr").querySelector("td:nth-child(2)").innerText;
        document.getElementById("deleteID").value = employeeID;
        deleteModal.style.display = "block";
        
        // Set the delete form action URL dynamically
        var deleteForm = document.getElementById("deleteForm");
        deleteForm.action = `/delete-karyawan/${employeeID}`;
    }

    // Function to close edit modal
    function closeEditModal() {
        editModal.style.display = "none";
    }

    // Function to close add modal
    function closeAddModalFunc() {
        addModal.style.display = "none";
    }

    // Function to close delete modal
    function closeDeleteModalFunc() {
        deleteModal.style.display = "none";
    }

    // Input validation for ID fields
    document
        .getElementById("addID")
        .addEventListener("input", function (e) {
            var value = e.target.value;
            var isValid = /^\d*$/.test(value);
            document.getElementById("addIDKaryawanError").style.display = isValid
                ? "none"
                : "block";
            e.target.value = value.replace(/\D/g, "");
        });

    document
        .getElementById("editID")
        .addEventListener("input", function (e) {
            var value = e.target.value;
            var isValid = /^\d*$/.test(value);
            document.getElementById("editIDKaryawanError").style.display = isValid
                ? "none"
                : "block";
            e.target.value = value.replace(/\D/g, "");
        });

        document.addEventListener('DOMContentLoaded', function() {
    const columnVisibilityButton = document.getElementById('columnVisibilityButton');
    const columnVisibilityModal = document.getElementById('columnVisibilityModal');
    const closeColumnVisibilityModal = document.getElementById('closeColumnVisibilityModal');
    const columnVisibilityForm = document.getElementById('columnVisibilityForm');

    // Open Modal
    columnVisibilityButton.onclick = function() {
        columnVisibilityModal.style.display = 'block';
    };

    // Close Modal when clicking on the close button
    closeColumnVisibilityModal.onclick = function() {
        columnVisibilityModal.style.display = 'none';
    };

    // Update column visibility based on checkbox status
    columnVisibilityForm.addEventListener('change', function(event) {
        const checkbox = event.target;
        if (checkbox.type === 'checkbox') {
            const columnClass = 'col-' + checkbox.id.replace('col', '').toLowerCase();
            const columns = document.querySelectorAll('.' + columnClass);
            columns.forEach(column => {
                column.style.display = checkbox.checked ? '' : 'none';
            });
        }
    });
});

document.getElementById('printButton').addEventListener('click', function() {
    var printContent = document.getElementById('printableView').innerHTML;
    
    // Create a new window
    var printWindow = window.open('', '', 'width=800,height=600');

    // Write the print content into the new window
    printWindow.document.open();
    printWindow.document.write(`
 <!DOCTYPE html>
<html>
<head>
    <title>Data Karyawan</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <style>
        html, body {
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
            border-radius: 0; /* Removed border-radius to make it square */
            box-shadow: 0 2px 4px rgba(0, 255, 38, 0.1);
            width: 225px; /* Set width for card */
            height: 40px;
            background-color: #1bbc53;
            color: white;
            display: flex;
            align-items: center; /* Align items in header */
        }
        .card-header i {
            margin-right: 10px;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 40px;
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
        table, th, td {
            border: 1px solid black;
        }
        th, td {
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
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Data Karyawan</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Karyawan</th>
                <th>Nama Karyawan</th>

                <th>Jabatan</th>
                <th>Status</th>
                <th>Gaji</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($karyawan as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->id_karyawan }}</td>
                <td>{{ $item->nama_karyawan }}</td>

                <td>{{ $item->jabatan }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ 'Rp. ' . number_format($item->gaji, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

    `);
    printWindow.document.close();

    // Wait for the new window to load the content before printing
    printWindow.onload = function() {
        printWindow.focus(); // Ensure the new window is focused
        printWindow.print(); // Trigger the print dialog
    };
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

        document.addEventListener('DOMContentLoaded', function() {
            const searchButton = document.getElementById('searchButton');
    const searchModal = document.getElementById('searchModal');
    const closeSearchModal = document.getElementById('closeSearchModal');
            // Open the modal when the search button is clicked
            searchButton.onclick = function() {
                searchModal.style.display = 'block';
            };
            //close
            closeSearchModal.onclick = function() {
                searchModal.style.display = 'none';
            };

            
        });
        document.getElementById('resetSearch').onclick = function() {
    // Clear the search input
    document.getElementById('form1').value = '';

    // Submit the form with an empty query to reset the search results
    document.getElementById('searchId').submit();
  }
</script>
    <!-- Modal HTML -->
    <div id="detailModal" class="modal">
      <div class="modal-content">
        <span class="close-button">&times;</span>
        <h3 id="modalTitle"></h3>
        <div id="modalBody"></div>
      </div>
    </div>
  </body>
</html>
