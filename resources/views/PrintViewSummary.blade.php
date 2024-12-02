<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <style>
  #summaryTable th, #summaryTable td {
        text-align: center;
        vertical-align: middle; /* Optional: to center vertically */
    }
        /* Set font to Book Antiqua */
        body, h4, h5, .table th, .table td {
            font-family: "Book Antiqua", serif;
            margin-right: 10px;
        }
        
        /* Add some styles for printing */
        @media print {
            body {
                font-family: "Book Antiqua", serif;
            }
            .container {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .table {
                width: 100%;
                border-collapse: collapse;
            }
            .table th, .table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            .table th {
                background-color: #f2f2f2;
            }
            .table tfoot tr {
                font-weight: bold;
            }
        }
        @font-face {
    font-family: 'Book Antiqua';
    src: url('/path-to-your-fonts/book-antiqua.woff2') format('woff2'),
         url('/path-to-your-fonts/book-antiqua.woff') format('woff');
    font-weight: normal;
    font-style: normal;
}

h4, h5 {
    font-family: 'Book Antiqua', serif;
}
    </style>

</head>
<body>
    <div class="container">
    <h4 style="text-align: left; font-family: 'Book Antiqua';"><strong>PT. Tolan Tiga</strong></h4>
<h5 style="text-align: left; font-family: 'Book Antiqua';"><strong>HR</strong></h5>
<h5 style="text-align: center; font-family: 'Book Antiqua';"><strong>Daftar Pembayaran Lembur Non-Staff</strong></h5>
<h5 style="text-align: center; font-family: 'Book Antiqua';"><strong>Di Bayarkan Perusahaan</strong></h5>
<h5 style="text-align: center; font-family: 'Book Antiqua';">
    <strong>{{ $printstart_date ? \Carbon\Carbon::parse($printstart_date)->translatedFormat('d F Y') : '' }} 
    - 
    {{ $printend_date ? \Carbon\Carbon::parse($printend_date)->translatedFormat('d F Y') : '' }}</strong>
</h5>



        <table class="table table-striped table-bordered" id="summaryTable" >
        <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Nama</th>
            <th colspan="2">Jumlah</th>
        </tr>
        <tr>
            <th>Jam</th>
            <th>Rp.</th>
        </tr>
    </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($groupedRecords as $namaLengkap => $totals)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $namaLengkap }}</td>
                    <td>{{ number_format($totals['totalJamKerja'], 1, ',', '.') }}</td>
                    <td>Rp. {{ number_format($totals['totalUpahLembur'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total</td>
                    <td>{{ number_format($totalJamKerja, 1, ',', '.') }}</td>
                    <td>Rp. {{ number_format($totalUpahLembur, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        <h5 style="text-align: right;">
    Medan, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
</h5>

        <h5 style="text-align: right;">Approved By,</h5>
        <script>
            window.print();
        </script>
    </div>
</body>
</html>
