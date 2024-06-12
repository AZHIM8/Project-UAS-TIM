@extends('layouts.app')

@include('barang-masuk.create')
@include('barang-masuk.show')
@include('barang-masuk.edit')

<style>
    .fixed-width {
        flex: 0 0 auto;
        max-width: 100%;
    }

    .fixed-width .form-group {
        margin-bottom: 0.5rem;
    }

    .fixed-width .form-group label {
        display: block;
        margin-bottom: 0.25rem;
    }

    .fixed-width .form-control,
    .fixed-width .btn {
        width: 100%;
    }

    .detail-barang-container {
        padding-left: 0;
        padding-right: 0;
    }

    .detail-barang-container hr {
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
        border-top: 1px solid #ccc;
    }
</style>

@section('content')
    <div class="section-header">
        <h1>Barang Masuk</h1>
        <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_barangMasuk"><i class="fa fa-plus"></i>
                Barang Masuk</a>
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
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal Masuk</th>
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


    <!-- Select2 Autocomplete -->
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.js-example-basic-single').select2();

                $('#nama_barang').on('change', function() {
                    var selectedOption = $(this).find('option:selected');
                    var nama_barang = selectedOption.text();

                    $.ajax({
                        url: '/api/barang-masuk',
                        type: 'GET',
                        data: {
                            nama_barang: nama_barang,
                        },
                        success: function(response) {
                            if (response && (response.stok || response.stok === 0) &&
                                response.satuan_id) {
                                $('#stok').val(response.stok);
                                getSatuanName(response.satuan_id, function(satuan) {
                                    $('#satuan_id').val(satuan);
                                });
                            } else if (response && response.stok === 0) {
                                $('#stok').val(0);
                                $('#satuan_id').val('');
                            }
                        },
                    });

                    function getSatuanName(satuanId, callback) {
                        $.getJSON('{{ url('api/satuan') }}', function(satuans) {
                            var satuan = satuans.find(function(s) {
                                return s.id === satuanId;
                            });
                            callback(satuan ? satuan.satuan : '');
                        });
                    }
                });
            }, 500);
        });
    </script>

    <!-- Datatable -->
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                paging: true
            });

            $.ajax({
                url: "/barang-masuk/get-data",
                type: "GET",
                dataType: 'JSON',
                success: function(response) {
                    let counter = 1;
                    $('#table_id').DataTable().clear();
                    $.each(response.data, function(key, value) {
                        let barangMasuk = `
                        <tr class="barang-row" id="index_${value.id}">
                            <td>${counter++}</td>   
                            <td>${value.kode_transaksi}</td>
                            <td>${value.tgl_masuk}</td>
                            <td>${value.supplier.supplier}</td>
                            <td>
                                <a href="javascript:void(0)" id="button_detail_barang_masuk" data-id="${value.id}" class="btn btn-icon btn-success btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_detail_barang_masuk"><i class="fas fa-eye"></i></a>
                                <a href="javascript:void(0)" id="button_edit_barang_masuk" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_edit_barang_masuk"><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" id="button_hapus_barang_masuk" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                            </td>
                        </tr>
                    `;
                        $('#table_id').DataTable().row.add($(barangMasuk)).draw(false);
                    });
                }
            });
        });
    </script>

    <!-- Generate Kode Transaksi Otomatis -->
    <script>
        function generateKodeTransaksi() {
            var tanggal = new Date().toLocaleDateString('id-ID').split('/').reverse().join('-');
            var randomNumber = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
            var kodeTransaksi = 'TRX-IN-' + tanggal + '-' + randomNumber;

            $('#kode_transaksi').val(kodeTransaksi);
            return kodeTransaksi;
        }

        $(document).ready(function() {
            generateKodeTransaksi();
        });
    </script>

    <script>
        function tambahBarang() {
            $('#form-tambah-barang').show();
            $.ajax({
                type: 'GET',
                url: '/api/daftar_barang',
                success: function(response) {
                    var options = '';
                    for (var i = 0; i < response.length; i++) {
                        options += '<option value="' + response[i].id + '">' + response[i].nama_barang +
                            '</option>';
                    }

                    var newRow =
                        '<div class="container detail-barang-container">' +
                        '<div class="row align-items-center mb-3">' +
                        '<div class="col-md-6">' +
                        '<div class="form-group">' +
                        '<label for="barang_id">Barang</label>' +
                        '<select class="form-control js-example-basic-single" name="barang_id[]" required>' +
                        options +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-4">' +
                        '<div class="form-group">' +
                        '<label for="jumlah_masuk">Jumlah Masuk</label>' +
                        '<input type="number" class="form-control" name="jumlah_masuk[]" required>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-2 d-flex align-items-end">' +
                        '<button type="button" class="btn btn-danger btn-remove" onclick="hapusBaris(this)"><i class="fas fa-solid fa-trash"></i></button>' +
                        '</div>' +
                        '</div>' +
                        '<div class="alert alert-danger mt-2 d-none" role="alert" id="alert-jumlah_masuk"></div>' +
                        '<hr class="my-3" style="border-top: 1px solid #ccc;">' +
                        '</div>';

                    $('#form-tambah-barang').append(newRow);

                    $(document).ready(function() {
                        $('.js-example-basic-single').select2();
                    });
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function hapusBaris(element) {
            $(element).closest('.detail-barang-container').remove();
        }
    </script>

    <script>
        function simpanBarangMasuk() {
            var formData = $('#form_tambah_barang_masuk').serialize();
            formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'POST',
                url: '/barang-masuk',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: true,
                        timer: 3000
                    });
                    $("#form-tambah-barang").empty();

                    $.ajax({
                        url: "/barang-masuk/get-data",
                        type: "GET",
                        dataType: 'JSON',
                        success: function(response) {
                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let barangMasuks = `
                                <tr class="barang-row" id="index_${value.id}">
                                    <td>${counter++}</td>   
                                    <td>${value.kode_transaksi}</td>
                                    <td>${value.tgl_masuk}</td>
                                    <td>${value.supplier.supplier}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="javascript:void(0)" id="button_detail_barang_masuk" data-id="${value.id}" class="btn btn-icon btn-success btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_detail_barang_masuk"><i class="fas fa-eye"></i></a>
                                              <a href="javascript:void(0)" id="button_edit_barang_masuk" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_edit_barang_masuk"><i class="fas fa-edit"></i></a>
                                            <a href="javascript:void(0)" id="button_hapus_barang_masuk" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2 d-inline-block"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                `;
                                $('#table_id').DataTable().row.add($(barangMasuks)).draw(
                                    false);
                            });
                            $('#modal_tambah_barangMasuk').modal('hide');
                        }
                    });

                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.kode_transaksi && error.responseJSON
                        .kode_transaksi[
                            0]) {
                        $('#alert-kode_transaksi').removeClass('d-none');
                        $('#alert-kode_transaksi').addClass('d-block');

                        $('#alert-kode_transaksi').html(error.responseJSON.kode_transaksi[0]);
                    }

                    if (error.responseJSON && error.responseJSON.supplier_id && error.responseJSON.supplier_id[
                            0]) {
                        $('#alert-supplier_id').removeClass('d-none');
                        $('#alert-supplier_id').addClass('d-block');

                        $('#alert-supplier_id').html(error.responseJSON.supplier_id[0]);
                    }
                    if (error.responseJSON && error.responseJSON['jumlah_masuk.0'] && error.responseJSON[
                            'jumlah_masuk.0'][0]) {
                        $('#alert-jumlah_masuk').removeClass('d-none');
                        $('#alert-jumlah_masuk').addClass('d-block');

                        $('#alert-jumlah_masuk').html(error.responseJSON['jumlah_masuk.0'][0]);
                    }
                }
            });
        }
    </script>

    <script>
        function tambahBarang() {
            $('#form-tambah-barang').show();
            $.ajax({
                type: 'GET',
                url: '/api/daftar_barang',
                success: function(response) {
                    var options = '';
                    for (var i = 0; i < response.length; i++) {
                        options += '<option value="' + response[i].id + '">' + response[i].nama_barang +
                            '</option>';
                    }

                    var newRow =
                        '<div class="container detail-barang-container">' +
                        '<div class="row align-items-center mb-3">' +
                        '<div class="col-md-6 fixed-width">' +
                        '<div class="form-group">' +
                        '<label for="barang_id">Barang</label>' +
                        '<select class="form-control js-example-basic-single" name="barang_id[]" required>' +
                        options +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-4 fixed-width">' +
                        '<div class="form-group">' +
                        '<label for="jumlah_masuk">Jumlah Masuk</label>' +
                        '<input type="number" class="form-control" name="jumlah_masuk[]" required>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-2 d-flex align-items-end fixed-width">' +
                        '<button type="button" class="btn btn-danger btn-remove" onclick="hapusBaris(this)"><i class="fas fa-solid fa-trash"></i></button>' +
                        '</div>' +
                        '</div>' +
                        '<div class="alert alert-danger mt-2 d-none" role="alert" id="alert-jumlah_masuk"></div>' +
                        '</div>';

                    $('#form-tambah-barang').append(newRow);

                    // Inisialisasi select2 setelah menambahkan baris baru
                    $(document).ready(function() {
                        $('.js-example-basic-single').select2();
                    });
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function hapusBaris(element) {
            $(element).closest('.detail-barang-container').remove();
        }
    </script>


    <!-- Show Modal Tambah Jenis Barang -->
    <script>
        $('body').on('click', '#button_tambah_barangMasuk', function() {
            $('#modal_tambah_barangMasuk').modal('show');
            $('#kode_transaksi').val(generateKodeTransaksi());
        });

        function clearAlert() {
            $('#alert-supplier_id').removeClass('d-block').addClass('d-none');
            $('#alert-jumlah_masuk').removeClass('d-block').addClass('d-none');
            $('#alert-kode_transaksi').removeClass('d-block').addClass('d-none');
            $('#alert-tgl_masuk').removeClass('d-block').addClass('d-none');
        }
    </script>


    <!-- Edit Barang Masuk -->
    <script>
        $('body').on('click', '#button_edit_barang_masuk', function() {
            let barang_masuk_id = $(this).data('id');

            $.ajax({
                url: `/barang-masuk/${barang_masuk_id}/edit`,
                type: "GET",
                cache: false,
                success: function(response) {
                    editBarangMasuk(response.barang_masuk, response.detail_barang_masuks);
                    $('#modal_edit_barangMasuk').modal('show');
                }
            });
        });

        function editBarangMasuk(barangMasuk, detailBarangMasuks) {
            $('#barang_masuk_id').val(barangMasuk.id);
            $('#edit_kode_transaksi').val(barangMasuk.kode_transaksi);
            $('#edit_tgl_masuk').val(barangMasuk.tgl_masuk);
            $('#edit_supplier_id').val(barangMasuk.supplier_id);

            $('#form-edit-barang').empty();
            detailBarangMasuks.forEach(function(detail) {
                var newRow =
                    '<div class="detail-barang-container">' +
                    '<div class="detail-barang-row">' +
                    '<div class="detail-barang-item">' +
                    '<label for="barang_id">Barang</label>' +
                    '<select class="form-control w-100 js-example-basic-single" name="barang_id[]" required>' +
                    '<option value="' + detail.barang_id + '">' + detail.barang.nama_barang + '</option>' +
                    '</select>' +
                    '</div>' +

                    '<div class="detail-barang-item">' +
                    '<label for="jumlah_masuk">Jumlah Masuk</label>' +
                    '<div class="input-group">' +
                    '<input type="number" class="form-control" name="jumlah_masuk[]" value="' + detail
                    .jumlah_masuk +
                    '" required>' +
                    '<div class="input-group-append">' +
                    '<button type="button" class="btn btn-danger btn-remove" onclick="hapusBaris(this)"><i class="fas fa-solid fa-x"></i></button>' +
                    '</div>' +
                    '</div>' +
                    '<div class="alert alert-danger mt-2 d-none" role="alert" id="alert-jumlah_masuk"></div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<hr style="border: 1px solid black">';


                $('#form-edit-barang').append(newRow);
            });
            $(document).ready(function() {
                $('.js-example-basic-single').select2();
            });
        }

        function updateBarangMasuk() {
            var formData = $('#form_edit_barang_masuk').serialize();
            formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');
            var barangMasukId = $('#barang_masuk_id').val();
            $.ajax({
                type: 'PUT',
                url: '/barang-masuk/' + barangMasukId,
                data: formData,
                success: function(response) {
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: true,
                        timer: 3000
                    });
                    $.ajax({
                        url: "/barang-masuk/get-data",
                        type: "GET",
                        dataType: 'JSON',
                        success: function(response) {
                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let barangMasuks = `
                                <tr class="barang-row" id="index_${value.id}">
                                    <td>${counter++}</td>   
                                    <td>${value.kode_transaksi}</td>
                                    <td>${value.tgl_masuk}</td>
                                    <td>${value.supplier.supplier}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="javascript:void(0)" id="button_detail_barang_masuk" data-id="${value.id}" class="btn btn-icon btn-success btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_detail_barang_masuk"><i class="fas fa-eye"></i></a>
                                            <a href="javascript:void(0)" id="button_edit_barang_masuk" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_edit_barang_masuk"><i class="fas fa-edit"></i></a>
                                            <a href="javascript:void(0)" id="button_hapus_barang_masuk" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2 d-inline-block"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                `;
                                $('#table_id').DataTable().row.add($(barangMasuks)).draw(
                                    false);
                            });
                            $('#modal_edit_barang_masuk').modal('hide');
                        }
                    });
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
    </script>


    <!-- Hapus Data Barang -->
    <script>
        $('body').on('click', '#button_hapus_barang_masuk', function() {
            let barangMasuk_id = $(this).data('id');
            let token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Apakah Kamu Yakin?',
                text: "ingin menghapus data ini !",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'TIDAK',
                confirmButtonText: 'YA, HAPUS!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/barang-masuk/${barangMasuk_id}`,
                        type: "DELETE",
                        cache: false,
                        data: {
                            "_token": token
                        },
                        success: function(response) {
                            Swal.fire({
                                type: 'success',
                                icon: 'success',
                                title: `${response.message}`,
                                showConfirmButton: true,
                                timer: 3000
                            });
                            $(`#index_${barangMasuk_id}`).remove();

                            $.ajax({
                                url: "/barang-masuk/get-data",
                                type: "GET",
                                dataType: 'JSON',
                                success: function(response) {
                                    let counter = 1;
                                    $('#table_id').DataTable().clear();
                                    $.each(response.data, function(key, value) {
                                        let barangMasuk = `
                                        <tr class="barang-row" id="index_${value.id}">
                                                <td>${counter++}</td>   
                                                <td>${value.kode_transaksi}</td>
                                                <td>${value.tgl_masuk}</td>
                                                <td>${value.supplier.supplier}</td>
                                                <td>
                                                    <a href="javascript:void(0)" id="button_detail_barang_masuk" data-id="${value.id}" class="btn btn-icon btn-success btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_detail_barang_masuk"><i class="fas fa-eye"></i></a>
                                                    <a href="javascript:void(0)" id="button_edit_barang_masuk" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_edit_barang_masuk"><i class="fas fa-edit"></i></a>
                                                    <a href="javascript:void(0)" id="button_hapus_barang_masuk" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                                </td>
                                            </tr>
                                        `;
                                        $('#table_id').DataTable().row.add(
                                            $(barangMasuk)).draw(false);
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>

    <!-- Show Detail Data Barang Masuk -->
    <script>
        $('body').on('click', '#button_detail_barang_masuk', function() {
            let barang_masuk_id = $(this).data('id');
            $.ajax({
                url: `/barang-masuk/${barang_masuk_id}`,
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    $('#barang_masuk_id').val(response.barang_masuk.id);
                    $('#detail_tgl_masuk').val(response.barang_masuk.tgl_masuk);
                    $('#detail_kode_transaksi').val(response.barang_masuk.kode_transaksi);
                    $('#detail_supplier').val(response.barang_masuk.supplier.supplier);

                    let detailBarangMasuksBody = $('#detail_barang_masuks_body');
                    detailBarangMasuksBody.empty();

                    let detailBarangMasuks = response.detail_barang_masuks;

                    let counter = 1;
                    detailBarangMasuks.forEach(function(detailBarangMasuk) {
                        detailBarangMasuksBody.append(`
                        <tr>
                            <td>${counter++}</td>
                            <td>${detailBarangMasuk.barang.nama_barang}</td>
                            <td>${detailBarangMasuk.jumlah_masuk}</td>
                        </tr>
                    `);
                    });

                    $('#modal_detail_barang_masuk').modal('show');
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
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
        document.getElementById('tgl_masuk').value = formattedDate;
    </script>
@endsection
