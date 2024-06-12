<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1,
        p {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: center;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <h1>Laporan Barang Masuk</h1>
    @if ($tanggalMulai && $tanggalSelesai)
        <p>Rentang Tanggal : {{ $tanggalMulai }} - {{ $tanggalSelesai }}
        <p>
        @else
        <p>Rentang Tanggal : Semua</p>
    @endif


    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal Masuk</th>
                <th>Nama Barang</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kode_transaksi }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>
                        <ul>
                            @foreach ($item->detailBarangMasuks as $detailItem)
                                <li>{{ $detailItem->barang->nama_barang }} ({{ $detailItem->jumlah_masuk }})
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $item->supplier->supplier }} </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot style="background-color: #A4FF82">
            <tr>
                <td colspan="6"><strong>Total Stok Masuk :
                        {{ $data->reduce(function ($carry, $item) {
                            return $carry + $item->detailBarangMasuks->sum('jumlah_masuk');
                        }, 0) }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name }}<br>
        Tanggal: {{ date('d-m-Y') }}
    </div>
</body>

</html>
