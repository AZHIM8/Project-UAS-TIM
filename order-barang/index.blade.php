@extends('layouts.app')

@include('order-barang.create')
@include('order-barang.edit')

@section('content')
    <div class="section-header">
        <h1>Order Barang</h1>
        <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_order-barang"><i class="fa fa-plus"></i>
                Tambah Order</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_id" class="display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Supplier</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Datatables Jquery -->
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                paging: true
            });

            $.ajax({
                url: "/order-barang/get-data",
                type: "GET",
                dataType: 'JSON',
                success: function(response) {
                    let counter = 1;
                    $('#table_id').DataTable().clear();
                    $.each(response.data, function(key, value) {
                        let kotakSaran = `
                            <tr class="order-barang-row" id="index_${value.id}">
                                <td>${counter++}</td>
                                <td>${value.tanggal}</td>
                                <td>${value.nama_barang}</td>
                                <td>${value.jumlah}</td>
                                <td>${value.keterangan}</td>
                                <td>${value.supplier.supplier}</td>
                                <td>
                                    <a href="javascript:void(0)" id="button_edit_order-barang" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                    <a href="javascript:void(0)" id="button_hapus_order-barang" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                </td>
                            </tr>
                        `;
                        $('#table_id').DataTable().row.add($(kotakSaran)).draw(false);
                    });
                }
            });
        });
    </script>

    <!-- Show Modal Tambah order-barang -->
    <script>
        $('body').on('click', '#button_tambah_order-barang', function() {
            $('#modal_tambah_order-barang').modal('show');
        });

        $('#store').click(function(e) {
            e.preventDefault();

            let tanggal = $('#tanggal').val();
            let nama_barang = $('#nama_barang').val();
            let jumlah = $('#jumlah').val();
            let keterangan = $('#keterangan').val();
            let supplier_id = $('#supplier_id').val();
            let token = $("meta[name='csrf-token']").attr("content");

            let formData = new FormData();
            formData.append('tanggal', tanggal);
            formData.append('nama_barang', nama_barang);
            formData.append('jumlah', jumlah);
            formData.append('keterangan', keterangan);
            formData.append('supplier_id', supplier_id);
            formData.append('_token', token);

            $.ajax({
                url: '/order-barang',
                type: "POST",
                cache: false,
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: true,
                        timer: 3000
                    });

                    $.ajax({
                        url: "/order-barang/get-data",
                        type: "GET",
                        dataType: 'JSON',
                        success: function(response) {
                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let kotakSaran = `
                                <tr class="order-barang-row" id="index_${value.id}">
                                    <td>${counter++}</td>
                                    <td>${value.tanggal}</td>
                                    <td>${value.nama_barang}</td>
                                    <td>${value.jumlah}</td>
                                    <td>${value.keterangan}</td>
                                    <td>${value.supplier.supplier}</td>
                                    <td>
                                        <a href="javascript:void(0)" id="button_edit_order-barang" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                        <a href="javascript:void(0)" id="button_hapus_order-barang" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                    </td>
                                </tr>
                            `;
                                $('#table_id').DataTable().row.add($(kotakSaran))
                                    .draw(false);
                            });
                        }
                    });

                    $('#modal_tambah_order-barang').modal('hide');
                    $('#nama_barang').val('');
                    $('#jumlah').val('');
                    $('#keterangan').val('');
                    $('#supplier_id').val('');

                },

                error: function(error) {
                    if (error.responseJSON && error.responseJSON.tanggal && error.responseJSON.tanggal[
                            0]) {
                        $('#alert-tanggal').removeClass('d-none');
                        $('#alert-tanggal').addClass('d-block');

                        $('#alert-tanggal').html(error.responseJSON.tanggal[0]);
                    }

                    if (error.responseJSON && error.responseJSON.nama_barang && error.responseJSON
                        .nama_barang[0]) {
                        $('#alert-nama_barang').removeClass('d-none');
                        $('#alert-nama_barang').addClass('d-block');

                        $('#alert-nama_barang').html(error.responseJSON.nama_barang[0]);
                    }

                    if (error.responseJSON && error.responseJSON.jumlah && error.responseJSON
                        .jumlah[0]) {
                        $('#alert-jumlah').removeClass('d-none');
                        $('#alert-jumlah').addClass('d-block');

                        $('#alert-jumlah').html(error.responseJSON.jumlah[0]);
                    }

                    if (error.responseJSON && error.responseJSON.keterangan && error.responseJSON
                        .keterangan[0]) {
                        $('#alert-keterangan').removeClass('d-none');
                        $('#alert-keterangan').addClass('d-block');

                        $('#alert-keterangan').html(error.responseJSON.keterangan[0]);
                    }

                    if (error.responseJSON && error.responseJSON.supplier_id && error.responseJSON
                        .supplier_id[0]) {
                        $('#alert-supplier_id').removeClass('d-none');
                        $('#alert-supplier_id').addClass('d-block');

                        $('#alert-supplier_id').html(error.responseJSON.supplier_id[0]);
                    }
                }
            });
        });
    </script>


    <!-- Edit Data order-barang -->
    <script>
        // Menampilkan Form Modal Edit
        $('body').on('click', '#button_edit_order-barang', function() {
            let orderBarang_id = $(this).data('id');

            $.ajax({
                url: `/order-barang/${orderBarang_id}/edit`,
                type: "GET",
                cache: false,
                success: function(response) {
                    $('#order_barang_id').val(response.data.id);
                    $('#edit_tanggal').val(response.data.tanggal);
                    $('#edit_nama_barang').val(response.data.nama_barang);
                    $('#edit_jumlah').val(response.data.jumlah);
                    $('#edit_keterangan').val(response.data.keterangan);
                    $('#edit_supplier_id').val(response.data.supplier_id);

                    $('#modal_edit_order-barang').modal('show');
                }
            });
        });

        // Proses Update Data
        $('#update').click(function(e) {
            e.preventDefault();

            let orderBarang_id = $('#order_barang_id').val();
            let nama_barang = $('#edit_nama_barang').val();
            let jumlah = $('#edit_jumlah').val();
            let keterangan = $('#edit_keterangan').val();
            let supplier_id = $('#edit_supplier_id').val();
            let token = $("meta[name='csrf-token']").attr("content");

            // Buat objek FormData
            let formData = new FormData();
            formData.append('nama_barang', nama_barang);
            formData.append('jumlah', jumlah);
            formData.append('keterangan', keterangan);
            formData.append('supplier_id', supplier_id);
            formData.append('_token', token);
            formData.append('_method', 'PUT');

            $.ajax({
                url: `/order-barang/${orderBarang_id}`,
                type: "POST",
                cache: false,
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: true,
                        timer: 3000
                    });

                    $.ajax({
                        url: '/order-barang/get-data',
                        type: "GET",
                        cache: false,
                        success: function(response) {
                            $('#table-order-barangs').html(
                                ''); // kosongkan tabel terlebih dahulu

                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let kotakSaran = `
                                    <tr class="order-barang-row" id="index_${value.id}">
                                        <td>${counter++}</td>
                                        <td>${value.tanggal}</td>
                                        <td>${value.nama_barang}</td>
                                        <td>${value.jumlah}</td>
                                        <td>${value.keterangan}</td>
                                        <td>${value.supplier.supplier}</td>
                                        <td>
                                            <a href="javascript:void(0)" id="button_edit_order-barang" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                            <a href="javascript:void(0)" id="button_hapus_order-barang" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                        </td>
                                    </tr>
                                `;
                                $('#table_id').DataTable().row.add($(kotakSaran))
                                    .draw(
                                        false);
                            });

                            $('#nama_barang').val('');
                            $('#jumlah').val('');
                            $('#keterangan').val('');
                            $('#supplier_id').val('');

                            $('#modal_edit_order-barang').modal('hide');

                            let table = $('#table_id').DataTable();
                            table.draw();
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                },

                error: function(error) {
                    if (error.responseJSON && error.responseJSON.tanggal && error.responseJSON.tanggal[
                            0]) {
                        $('#alert-tanggal').removeClass('d-none');
                        $('#alert-tanggal').addClass('d-block');

                        $('#alert-tanggal').html(error.responseJSON.tanggal[0]);
                    }
                    if (error.responseJSON && error.responseJSON.nama_barang && error.responseJSON
                        .nama_barang[
                            0]) {
                        $('#alert-nama_barang').removeClass('d-none');
                        $('#alert-nama_barang').addClass('d-block');

                        $('#alert-nama_barang').html(error.responseJSON.nama_barang[0]);
                    }

                    if (error.responseJSON && error.responseJSON.jumlah && error.responseJSON
                        .jumlah[0]) {
                        $('#alert-jumlah').removeClass('d-none');
                        $('#alert-jumlah').addClass('d-block');

                        $('#alert-jumlah').html(error.responseJSON.jumlah[0]);
                    }

                    if (error.responseJSON && error.responseJSON.keterangan && error.responseJSON
                        .keterangan[0]) {
                        $('#alert-keterangan').removeClass('d-none');
                        $('#alert-keterangan').addClass('d-block');

                        $('#alert-keterangan').html(error.responseJSON.keterangan[0]);
                    }

                    if (error.responseJSON && error.responseJSON.supplier_id && error.responseJSON
                        .supplier_id[0]) {
                        $('#alert-supplier_id').removeClass('d-none');
                        $('#alert-supplier_id').addClass('d-block');

                        $('#alert-supplier_id').html(error.responseJSON.supplier_id[0]);
                    }

                }
            })
        })
    </script>

    <!-- Hapus Data order-barang -->
    <script>
        $('body').on('click', '#button_hapus_order-barang', function() {
            let orderBarang_id = $(this).data('id');
            let token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Apakah Kamu Yakin?',
                text: "Ingin menghapus data ini!",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'TIDAK',
                confirmButtonText: 'YA, HAPUS!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/order-barang/${orderBarang_id}`,
                        type: "DELETE",
                        cache: false,
                        data: {
                            "_token": token
                        },
                        success: function(response) {
                            // Periksa apakah objek response memiliki properti message
                            if (response.hasOwnProperty('message')) {
                                Swal.fire({
                                    type: 'success',
                                    icon: 'success',
                                    title: response.message,
                                    showConfirmButton: true,
                                    timer: 3000
                                });
                            }
                            $('#table_id').DataTable().clear().draw();

                            // Ambil ulang data tabel setelah menghapus item
                            $.ajax({
                                url: "/order-barang/get-data",
                                type: "GET",
                                dataType: 'JSON',
                                success: function(response) {
                                    let counter = 1;
                                    $('#table_id').DataTable().clear();
                                    $.each(response.data, function(key, value) {
                                        let kotakSaran = `
                                        <tr class="order-barang-row" id="index_${value.id}">
                                            <td>${counter++}</td>
                                            <td>${value.tanggal}</td>
                                            <td>${value.nama_barang}</td>
                                            <td>${value.jumlah}</td>
                                            <td>${value.keterangan}</td>
                                            <td>${value.supplier.supplier}</td>
                                            <td>
                                                <a href="javascript:void(0)" id="button_edit_order-barang" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                                <a href="javascript:void(0)" id="button_hapus_order-barang" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                            </td>
                                        </tr>
                                    `;
                                        $('#table_id').DataTable().row.add(
                                            $(kotakSaran)).draw(false);
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>


    <script>
        // Mendapatkan tanggal hari ini
        var today = new Date();

        // Mendapatkan nilai tahun, bulan, dan tanggal
        var year = today.getFullYear();
        var month = (today.getMonth() + 1).toString().padStart(2, '0'); // Ditambahkan +1 karena indeks bulan dimulai dari 0
        var day = today.getDate().toString().padStart(2, '0');

        // Menggabungkan nilai tahun, bulan, dan tanggal menjadi format "YYYY-MM-DD"
        var formattedDate = year + '-' + month + '-' + day;

        // Mengisi nilai input field dengan tanggal hari ini
        document.getElementById('tanggal').value = formattedDate;
    </script>
@endsection
