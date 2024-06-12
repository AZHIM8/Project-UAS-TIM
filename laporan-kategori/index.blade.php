@extends('layouts.app')

@section('content')
    <div class="section-header">
        <h1>Laporan Per Kategori / Jenis</h1>
        <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-danger" id="print-kategori"><i
                    class="fa fa-sharp fa-light fa-print"></i>
                Print PDF</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="opsi-laporan-kategori">Filter Stok Berdasarkan Kategori / Jenis:</label>
                        <select class="form-control" name="opsi-laporan-kategori" id="opsi-laporan-kategori">
                            <option value="semua" selected>Semua</option>
                            @foreach ($jenises as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->jenis_barang }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_id" class="display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori / Jenis</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-laporan-kategori">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Dropdown -->
    <script>
        $(document).ready(function() {
            var table = $('#table_id').DataTable({
                paging: true
            });

            loadData('semua');

            $('#opsi-laporan-kategori').on('change', function() {
                var selectedOption = $(this).val();
                loadData(selectedOption);
            });

            function loadData(selectedOption) {
                $.ajax({
                    url: '/laporan-kategori/get-data',
                    type: 'GET',
                    data: {
                        opsi: selectedOption
                    },
                    success: function(response) {
                        table.clear().draw();

                        let counter = 1;
                        $.each(response, function(index, item) {
                            var row = [
                                counter++,
                                item.kode_barang,
                                item.nama_barang,
                                item.jenis.jenis_barang
                            ];
                            table.row.add(row);
                        });
                        table.draw();
                    }
                });

            }

            $('#print-kategori').on('click', function() {
                var selectedOption = $('#opsi-laporan-kategori').val();
                window.location.href = '/laporan-kategori/print-kategori?opsi=' + selectedOption;
            });
        });
    </script>
@endsection
