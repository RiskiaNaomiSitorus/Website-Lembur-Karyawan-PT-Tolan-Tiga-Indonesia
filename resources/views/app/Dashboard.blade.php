<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Add CSRF token here -->
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
    <style>
     /* General Styles */
body {
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* Ensure body takes up at least full viewport height */
    margin: 0;
}

/* Wrapper for main content */
.wrapper {
    flex: 1; /* Grow to fill available space */
}

/* Header Styles */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #f8f9fa;
}

/* Sidebar Styles */
.sidebar {
    width: 200px;
    float: left;
    height: calc(100vh - 60px); /* Adjust based on header height */
    position: fixed;
    top: 0;
    left: 0;
    background-color: #f8f9fa;
    border-right: 1px solid #ddd;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
}

/* Main Content Styles */
.main_content {
    margin-left: 220px; /* Space for the sidebar */
    padding: 20px;
}

/* Cards Container Styles */
.cards-container {
    display: flex;
    gap: 20px; /* Add gap between cards */
    flex-wrap: wrap; /* Allow wrapping to fit content */
    margin-top: 20px;
}

/* Card Styles */
.card {
    flex: 1;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    background-color: #fff;
    margin: 0 10px; /* Add some space between cards */
}

/* Flex Container for Table and Calendar */
.flex-container {
    display: flex;
    gap: 20px; /* Space between the table and calendar */
    margin-top: 20px;
}
.table-container {
    flex: 3; /* Table takes up 3/4 of the space */
}
.calendar-container {
    flex: 1; /* Calendar takes up 1/4 of the space */
}

/* Table Styles */
.users-table {
  margin-left: 10px;
    width: 95%;
    border-collapse: collapse;
}
.users-table th, .users-table td {
    border: 1px solid #ddd;
    padding: 8px;
}
.users-table th {
    background-color: #f2f2f2;
    text-align: left;
}

/* Pagination Styles */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}
.pagination a, .pagination span {
    margin: 0 5px;
    padding: 8px 16px;
    border: 1px solid #ddd;
    text-decoration: none;
    color: #007bff;
}
.pagination .active {
    background-color: #007bff;
    color: white;
    border: 1px solid #007bff;
}

/* Footer Styles */
footer {
    background-color: #f8f9fa;
    padding: 10px;
    text-align: center;
    border-top: 1px solid #ddd;
}
.footer-links {
    margin-top: 10px;
}
.footer-links a {
    margin: 0 10px;
    text-decoration: none;
    color: #007bff;
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
        <div class="dashboard-title">Dashboard</div>
        <div class="cards-container">
          <div class="card card-karyawan">
            <h3>Data Karyawan</h3>
            <div class="data-content">
              <p>{{ $karyawanCount }}</p>
              <i class="glyphicon glyphicon-user"></i>
            </div>
            <div class="label-container">
              <div class="label">Karyawan Terdaftar</div>
            </div>
          </div>
          <div class="card card-absen">
            <h3>Data Admin</h3>
            <div class="data-content">
              <p>{{ $userCount }}</p>
              <i class="glyphicon glyphicon-list-alt"></i>
            </div>
            <div class="label-container">
              <div class="label">Admin Terdaftar</div>
            </div>
          </div>
          <div class="card card-lembur">
            <div class="data-content">
              <p id="currentTime" class="time"></p>
              <p id="currentDate" class="date"></p>
            </div>
            <div class="label-container">
              <div class="label">Waktu Saat Ini</div>
            </div>
          </div>
        </div>
        <div class="flex-container">
          <!-- Users Table -->
          <div class="table-container">
            <h3>Admin Terdaftar</h3>
            <table class="users-table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Email</th>
                  <th>Name</th>
                  <th>Last Login</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $user)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->name }}</td>
                    <td>    {{ $user->updated_at ? $user->updated_at->format('d-m-Y H:i:s') : 'Never' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <!-- Pagination Links -->
            <div class="pagination">
              {{ $users->links() }}
            </div>
          </div>
          <div class="calendar-container">
            <!-- Calendar Card -->
            <div id="calendar"></div>
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
        <script>
          $(document).ready(function() {
            // Initialize datepicker
            $("#datepicker").datepicker();
            $("#calendar").datepicker();

            // Toggle kalender dropdown
            $("#calendarButton").on("click", function() {
              $("#calendar").toggle();
            });

            // Set current time and date in WIB
            function updateTime() {
              var now = new Date();
              var optionsTime = { 
                timeZone: 'Asia/Jakarta', 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'  
              };
              var optionsDate = { 
                timeZone: 'Asia/Jakarta', 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric'
              };
              var formatterTime = new Intl.DateTimeFormat('id-ID', optionsTime);
              var formatterDate = new Intl.DateTimeFormat('id-ID', optionsDate);
              var formattedTime = formatterTime.format(now);
              var formattedDate = formatterDate.format(now);
              $("#currentTime").text(formattedTime);
              $("#currentDate").text(formattedDate);
            }

            updateTime();
            setInterval(updateTime, 1000); // Update time every second
          });
        </script>
      </div>
    </div>
</body>
</html>
