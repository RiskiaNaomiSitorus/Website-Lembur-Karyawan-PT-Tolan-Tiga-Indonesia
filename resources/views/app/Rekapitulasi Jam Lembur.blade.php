<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Summary</title>
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

        .header-section h1 {
        margin-bottom: 20px; 
        }
        .form-container {
            margin: 20px 0;
        }
        .summary-footer {
            margin-top: 20px;
        }
        .bold {
            font-weight: bold;
        }
        .action-buttons {
            margin-bottom: 35px;
            display: flex;
            gap: 15px;
           position: relative;
           right: -25px;
        }
        .action-buttons .btn {
            margin-right: 10px;
        }
        .btn-csv {
            background-color: #28a745;
            color: white;
        }
        .btn-copy {
            background-color: #17a2b8;
            color: white;
        }
        .btn-colvis {
            background-color: #ffc107;
            color: white;
        }
        .btn-print {
            background-color: #6c757d;
            color: white;
        }
        .username {
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }
        .username i {
            margin-left: 5px;
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
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2>Lembur</h2>
            <ul>
                <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="{{ route('data-karyawan') }}"><i class="fas fa-user"></i> Data Karyawan</a></li>
                <li><a href="{{ route('rekapitulasi-jam-lembur') }}"><i class="fas fa-project-diagram"></i> Summary Jam Lembur</a></li>
                <li><a href="{{ route('perhitungan-lembur') }}"><i class="fas fa-address-book"></i> Perhitungan Lembur</a></li>
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
                <div class="date"> <h5 id="todayDate"></h5></div>
            </div>
            <div class="header-section">
                <h1>Summary Lembur Karyawan</h1>

                <div class="action-buttons">
                    <button class="btn btn-primary btn-sm" id="exportButton2">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                    <button class="btn btn-secondary btn-sm" id="openPrintModal2">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn btn-info" id="filterButton2">
    <i class="fa fa-filter" aria-hidden="true"></i> Filter Records
</button>

                </div>

                <div class="table-container">
                    <table class="table table-striped table-bordered" id="summaryTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jumlah Jam</th>
                                <th>Jumlah Upah Lembur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($groupedRecords as $namaLengkap => $totals)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $namaLengkap }}</td>
                                    <td>{{ $totals['totalJamKerja'] }}</td>
                                    <td>{{'Rp. '. number_format($totals['totalUpahLembur'] )}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">Total</td>
                                <td>{{ number_format($totalJamKerja, 1) }}</td>
                                <td>{{  'Rp. '. number_format($totalUpahLembur, 0) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
<div class="modal" id="filterModal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title" id="dateFilterModalLabel">Filter Lembur Records</h2>
        <form id="filterForm" method="GET" action="{{ route('rekapitulasi-jam-lembur') }}">
        @csrf
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap5" name="nama_lengkap5">
            </div>
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" class="form-control" id="start_date5" name="start_date5">
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" class="form-control" id="end_date5" name="end_date5">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Apply Filter</button>
                <button type="button" id="resetFilterButton" class="btn btn-secondary">Reset Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Print Filter Modal -->
<div id="printModal2" class="modal">
    <div class="modal-content">
        <span class="close" id="closePrintModal">&times;</span>
        <h2 class="modal-title">Print Filters</h2>
        <div class="modal-body">
            <form id="printForm" method="GET" action="{{ route('print.filtered.data') }}" target="_blank">
                @csrf
                <div class="form-group">
                    <label for="printnama_lengkap2">Nama Lengkap</label>
                    <input type="text" class="form-control" id="printnama_lengkap2" name="printnama_lengkap2">
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

<!-- Modal for Date Range Selection -->
<div id="dateRangeModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closedataRangeModal">&times;</span>
        <h2>Select Date Range</h2>
        <form id="dateRangeForm" action="{{ route('export.filtered.excel') }}" method="GET">
        @csrf
    <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap:</label>
        <input type="text" id="nama_lengkap_excel" name="nama_lengkap_excel" class="form-control">
    </div>
    <div class="form-group">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date_excel" name="start_date_excel" required class="form-control">
    </div>
    <div class="form-group">
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date_excel" name="end_date_excel" required class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Export</button>
</form>

    </div>
</div>
<script>
  //FILTER

document.addEventListener('DOMContentLoaded', function () {
    const filterModal = document.getElementById('filterModal');
    const filterButton = document.getElementById('filterButton2');
    const closeModal = filterModal.querySelector('.close');
    const resetButton = document.getElementById('resetFilterButton');
    const tableContainer = document.getElementById('tableContainer'); // Ensure this ID matches your table container

    // Open the modal
    if (filterButton) {
        filterButton.addEventListener('click', function () {
            filterModal.style.display = 'block';
        });
    }

    // Close the modal when clicking on the close button
    if (closeModal) {
        closeModal.addEventListener('click', function () {
            filterModal.style.display = 'none';
        });
    }

    // Close the modal when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target === filterModal) {
            filterModal.style.display = 'none';
        }
    });

    // Reset filter form
    if (resetButton) {
        resetButton.addEventListener('click', function () {
            document.getElementById('filterForm').reset();
            document.getElementById('filterForm').submit();
        });
    }
});

//FILTER
//PRINT
document.addEventListener('DOMContentLoaded', function () {
    // Get modal elements
    var printButton = document.getElementById('openPrintModal2');
    var printModal = document.getElementById('printModal2');
    var closePrintModal = document.getElementById('closePrintModal');
    
    // Show the modal
    printButton.onclick = function() {
        printModal.style.display = 'block';
    }
    
    // Close the modal
    closePrintModal.onclick = function() {
        printModal.style.display = 'none';
    }
    
    // Close the modal if clicking outside of it
    window.onclick = function(event) {
        if (event.target == printModal) {
            printModal.style.display = 'none';
        }
    }
});
//PRINT

//EXPORT

    document.addEventListener('DOMContentLoaded', function () {
        // Get modal elements
        var exportButton = document.getElementById('exportButton2');
        var dateRangeModal = document.getElementById('dateRangeModal');
        var closeModal = document.getElementById('closedataRangeModal');
        
        // Show the modal
        exportButton.onclick = function() {
            dateRangeModal.style.display = 'block';
        }
        
        // Close the modal
        closeModal.onclick = function() {
            dateRangeModal.style.display = 'none';
        }
        
        // Close the modal if clicking outside of it
        window.onclick = function(event) {
            if (event.target == dateRangeModal) {
                dateRangeModal.style.display = 'none';
            }
        }
    });


//EXPORT
//AUTOCOMPLETE
 // Autocomplete for Nama Lengkap
 $(document).ready(function() {
 $("#nama_lengkap5").autocomplete({
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
 $("#nama_lengkap_excel").autocomplete({
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
//AUTOCOMPLETE

//DATE
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
//DATE
    </script>
</body>
</html>
