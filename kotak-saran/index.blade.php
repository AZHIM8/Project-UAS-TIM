@extends('layouts.app')

@include('kotak-saran.create')
@include('kotak-saran.edit')

@section('content')
    <div class="section-header">
        <h1>Kotak Saran</h1>
        <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_kotak-saran"><i class="fa fa-plus"></i>
                Tambah Saran</a>
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
                                    <th>Ide Gagasan</th>
                                    <th>Inovasi</th>
                                    <th>Keluhan Operasional</th>
                                    <th>Customer</th>
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
                url: "/kotak-saran/get-data",
                type: "GET",
                dataType: 'JSON',
                success: function(response) {
                    let counter = 1;
                    $('#table_id').DataTable().clear();
                    $.each(response.data, function(key, value) {
                        let kotakSaran = `
                            <tr class="kotak-saran-row" id="index_${value.id}">
                                <td>${counter++}</td>
                                <td>${value.tanggal}</td>
                                <td>${value.nama_barang}</td>
                                <td>${value.ide_gagasan}</td>
                                <td>${value.inovasi}</td>
                                <td>${value.keluhan_operasional}</td>
                                <td>${value.customer.customer}</td>
                                <td>
                                    <a href="javascript:void(0)" id="button_edit_kotak-saran" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                    <a href="javascript:void(0)" id="button_hapus_kotak-saran" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                </td>
                            </tr>
                        `;
                        $('#table_id').DataTable().row.add($(kotakSaran)).draw(false);
                    });
                }
            });
        });
    </script>

    <!-- Show Modal Tambah kotak-saran -->
    <script>
        $('body').on('click', '#button_tambah_kotak-saran', function() {
            $('#modal_tambah_kotak-saran').modal('show');
        });

        $('#store').click(function(e) {
            e.preventDefault();

            let tanggal = $('#tanggal').val();
            let nama_barang = $('#nama_barang').val();
            let ide_gagasan = $('#ide_gagasan').val();
            let inovasi = $('#inovasi').val();
            let keluhan_operasional = $('#keluhan_operasional').val();
            let customer_id = $('#customer_id').val();
            let token = $("meta[name='csrf-token']").attr("content");

            let formData = new FormData();
            formData.append('tanggal', tanggal);
            formData.append('nama_barang', nama_barang);
            formData.append('ide_gagasan', ide_gagasan);
            formData.append('inovasi', inovasi);
            formData.append('keluhan_operasional', keluhan_operasional);
            formData.append('customer_id', customer_id);
            formData.append('_token', token);

            $.ajax({
                url: '/kotak-saran',
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
                        url: "/kotak-saran/get-data",
                        type: "GET",
                        dataType: 'JSON',
                        success: function(response) {
                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let kotakSaran = `
                                <tr class="kotak-saran-row" id="index_${value.id}">
                                    <td>${counter++}</td>
                                    <td>${value.tanggal}</td>
                                    <td>${value.nama_barang}</td>
                                    <td>${value.ide_gagasan}</td>
                                    <td>${value.inovasi}</td>
                                    <td>${value.keluhan_operasional}</td>
                                    <td>${value.customer.customer}</td>
                                    <td>
                                        <a href="javascript:void(0)" id="button_edit_kotak-saran" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                        <a href="javascript:void(0)" id="button_hapus_kotak-saran" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                    </td>
                                </tr>
                            `;
                                $('#table_id').DataTable().row.add($(kotakSaran))
                                    .draw(false);
                            });
                        }
                    });

                    $('#modal_tambah_kotak-saran').modal('hide');
                    $('#nama_barang').val('');
                    $('#ide_gagasan').val('');
                    $('#inovasi').val('');
                    $('#keluhan_operasional').val('');
                    $('#customer_id').val('');

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

                    if (error.responseJSON && error.responseJSON.ide_gagasan && error.responseJSON
                        .ide_gagasan[0]) {
                        $('#alert-ide_gagasan').removeClass('d-none');
                        $('#alert-ide_gagasan').addClass('d-block');

                        $('#alert-ide_gagasan').html(error.responseJSON.ide_gagasan[0]);
                    }

                    if (error.responseJSON && error.responseJSON.inovasi && error.responseJSON
                        .inovasi[0]) {
                        $('#alert-inovasi').removeClass('d-none');
                        $('#alert-inovasi').addClass('d-block');

                        $('#alert-inovasi').html(error.responseJSON.inovasi[0]);
                    }

                    if (error.responseJSON && error.responseJSON.keluhan_operasional && error
                        .responseJSON
                        .keluhan_operasional[0]) {
                        $('#alert-keluhan_operasional').removeClass('d-none');
                        $('#alert-keluhan_operasional').addClass('d-block');

                        $('#alert-keluhan_operasional').html(error.responseJSON.keluhan_operasional[0]);
                    }

                    if (error.responseJSON && error.responseJSON.customer_id && error.responseJSON
                        .customer_id[0]) {
                        $('#alert-customer_id').removeClass('d-none');
                        $('#alert-customer_id').addClass('d-block');

                        $('#alert-customer_id').html(error.responseJSON.customer_id[0]);
                    }
                }
            });
        });
    </script>


    <!-- Edit Data kotak-saran -->
    <script>
        // Menampilkan Form Modal Edit
        $('body').on('click', '#button_edit_kotak-saran', function() {
            let kotakSaran_id = $(this).data('id');

            $.ajax({
                url: `/kotak-saran/${kotakSaran_id}/edit`,
                type: "GET",
                cache: false,
                success: function(response) {
                    $('#kotak_saran_id').val(response.data.id);
                    $('#edit_tanggal').val(response.data.tanggal);
                    $('#edit_nama_barang').val(response.data.nama_barang);
                    $('#edit_ide_gagasan').val(response.data.ide_gagasan);
                    $('#edit_inovasi').val(response.data.inovasi);
                    $('#edit_keluhan_operasional').val(response.data.keluhan_operasional);
                    $('#edit_customer_id').val(response.data.customer_id);

                    $('#modal_edit_kotak-saran').modal('show');
                }
            });
        });

        // Proses Update Data
        $('#update').click(function(e) {
            e.preventDefault();

            let kotakSaran_id = $('#kotak_saran_id').val();
            let nama_barang = $('#edit_nama_barang').val();
            let ide_gagasan = $('#edit_ide_gagasan').val();
            let inovasi = $('#edit_inovasi').val();
            let keluhan_operasional = $('#edit_keluhan_operasional').val();
            let customer_id = $('#edit_customer_id').val();
            let token = $("meta[name='csrf-token']").attr("content");

            // Buat objek FormData
            let formData = new FormData();
            formData.append('nama_barang', nama_barang);
            formData.append('ide_gagasan', ide_gagasan);
            formData.append('inovasi', inovasi);
            formData.append('keluhan_operasional', keluhan_operasional);
            formData.append('customer_id', customer_id);
            formData.append('_token', token);
            formData.append('_method', 'PUT');

            $.ajax({
                url: `/kotak-saran/${kotakSaran_id}`,
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
                        url: '/kotak-saran/get-data',
                        type: "GET",
                        cache: false,
                        success: function(response) {
                            $('#table-kotak-sarans').html(
                                ''); // kosongkan tabel terlebih dahulu

                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let kotakSaran = `
                                    <tr class="kotak-saran-row" id="index_${value.id}">
                                        <td>${counter++}</td>
                                        <td>${value.tanggal}</td>
                                        <td>${value.nama_barang}</td>
                                        <td>${value.ide_gagasan}</td>
                                        <td>${value.inovasi}</td>
                                        <td>${value.keluhan_operasional}</td>
                                        <td>${value.customer.customer}</td>
                                        <td>
                                            <a href="javascript:void(0)" id="button_edit_kotak-saran" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                            <a href="javascript:void(0)" id="button_hapus_kotak-saran" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                        </td>
                                    </tr>
                                `;
                                $('#table_id').DataTable().row.add($(kotakSaran))
                                    .draw(
                                        false);
                            });

                            $('#nama_barang').val('');
                            $('#ide_gagasan').val('');
                            $('#inovasi').val('');
                            $('#keluhan_operasional').val('');
                            $('#customer_id').val('');

                            $('#modal_edit_kotak-saran').modal('hide');

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

                    if (error.responseJSON && error.responseJSON.ide_gagasan && error.responseJSON
                        .ide_gagasan[0]) {
                        $('#alert-ide_gagasan').removeClass('d-none');
                        $('#alert-ide_gagasan').addClass('d-block');

                        $('#alert-ide_gagasan').html(error.responseJSON.ide_gagasan[0]);
                    }

                    if (error.responseJSON && error.responseJSON.inovasi && error.responseJSON
                        .inovasi[0]) {
                        $('#alert-inovasi').removeClass('d-none');
                        $('#alert-inovasi').addClass('d-block');

                        $('#alert-inovasi').html(error.responseJSON.inovasi[0]);
                    }

                    if (error.responseJSON && error.responseJSON.keluhan_operasional && error
                        .responseJSON
                        .keluhan_operasional[0]) {
                        $('#alert-keluhan_operasional').removeClass('d-none');
                        $('#alert-keluhan_operasional').addClass('d-block');

                        $('#alert-keluhan_operasional').html(error.responseJSON.keluhan_operasional[0]);
                    }

                    if (error.responseJSON && error.responseJSON.customer_id && error.responseJSON
                        .customer_id[0]) {
                        $('#alert-customer_id').removeClass('d-none');
                        $('#alert-customer_id').addClass('d-block');

                        $('#alert-customer_id').html(error.responseJSON.customer_id[0]);
                    }

                }
            })
        })
    </script>

    <!-- Hapus Data kotak-saran -->
    <script>
        $('body').on('click', '#button_hapus_kotak-saran', function() {
            let kotakSaran_id = $(this).data('id');
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
                        url: `/kotak-saran/${kotakSaran_id}`,
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
                                url: "/kotak-saran/get-data",
                                type: "GET",
                                dataType: 'JSON',
                                success: function(response) {
                                    let counter = 1;
                                    $('#table_id').DataTable().clear();
                                    $.each(response.data, function(key, value) {
                                        let kotakSaran = `
                                        <tr class="kotak-saran-row" id="index_${value.id}">
                                            <td>${counter++}</td>
                                            <td>${value.tanggal}</td>
                                            <td>${value.nama_barang}</td>
                                            <td>${value.ide_gagasan}</td>
                                            <td>${value.inovasi}</td>
                                            <td>${value.keluhan_operasional}</td>
                                            <td>${value.customer.customer}</td>
                                            <td>
                                                <a href="javascript:void(0)" id="button_edit_kotak-saran" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                                <a href="javascript:void(0)" id="button_hapus_kotak-saran" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
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
