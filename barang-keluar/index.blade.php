@extends('layouts.app')

@include('barang-keluar.create')
@include('barang-keluar.show')
@include('barang-keluar.edit')

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
        <h1>Barang Keluar</h1>
        <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_barangKeluar"><i class="fa fa-plus"></i>
                Barang Keluar</a>
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
                                    <th>Tanggal Keluar</th>
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

    <!-- Select2 Autocomplete -->
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.js-example-basic-single').select2();

                $('#nama_barang').on('change', function() {
                    var selectedOption = $(this).find('option:selected');
                    var nama_barang = selectedOption.text();

                    $.ajax({
                        url: 'api/barang-keluar',
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
                url: "/barang-keluar/get-data",
                type: "GET",
                dataType: 'JSON',
                success: function(response) {
                    let counter = 1;
                    $('#table_id').DataTable().clear();
                    $.each(response.data, function(key, value) {
                        let customer = getCustomerName(response.customer, value.customer_id);
                        let barangKeluar = `
                        <tr class="barang-row" id="index_${value.id}">
                            <td>${counter++}</td>   
                            <td>${value.kode_transaksi}</td>
                            <td>${value.tgl_keluar}</td>
                            <td>${value.customer.customer}</td>
                            <td>       
                                <a href="javascript:void(0)" id="button_detail_barang_keluar" data-id="${value.id}" class="btn btn-icon btn-success btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_detail_barang_keluar"><i class="fas fa-eye"></i></a>
                                <a href="javascript:void(0)" id="button_edit_barang_keluar" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_edit_barang_keluar"><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" id="button_hapus_barangKeluar" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                            </td>
                        </tr>
                    `;
                        $('#table_id').DataTable().row.add($(barangKeluar)).draw(false);
                    });

                    function getCustomerName(customers, customerId) {
                        let customer = customers.find(s => s.id === customerId);
                        return customer ? customer.customer : '';
                    }
                }
            });
        });
    </script>

    <!-- Generate Kode Transaksi Otomatis -->
    <script>
        function generateKodeTransaksi() {
            var tanggal = new Date().toLocaleDateString('id-ID').split('/').reverse().join('-');
            var randomNumber = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
            var kodeTransaksi = 'TRX-OUT-' + tanggal + '-' + randomNumber;

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
                        '<label for="jumlah_keluar">Jumlah keluar</label>' +
                        '<input type="number" class="form-control" name="jumlah_keluar[]" required>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-2 d-flex align-items-end">' +
                        '<button type="button" class="btn btn-danger btn-remove" onclick="hapusBaris(this)"><i class="fas fa-solid fa-trash"></i></button>' +
                        '</div>' +
                        '</div>' +
                        '<div class="alert alert-danger mt-2 d-none" role="alert" id="alert-jumlah_keluar"></div>' +
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
        function simpanBarangKeluar() {
            var formData = $('#form_tambah_barang_keluar').serialize();
            formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'POST',
                url: '/barang-keluar',
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
                        url: "/barang-keluar/get-data",
                        type: "GET",
                        dataType: 'JSON',
                        success: function(response) {
                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let barangkeluars = `
                                <tr class="barang-row" id="index_${value.id}">
                                    <td>${counter++}</td>   
                                    <td>${value.kode_transaksi}</td>
                                    <td>${value.tgl_keluar}</td>
                                    <td>${value.customer.customer}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="javascript:void(0)" id="button_detail_barang_keluar" data-id="${value.id}" class="btn btn-icon btn-success btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_detail_barang_keluar"><i class="fas fa-eye"></i></a>
                                            <a href="javascript:void(0)" id="button_edit_barang_keluar" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_edit_barang_keluar"><i class="fas fa-edit"></i></a>
                                            <a href="javascript:void(0)" id="button_hapus_barang_keluar" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2 d-inline-block"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                `;
                                $('#table_id').DataTable().row.add($(barangkeluars)).draw(
                                    false);
                            });
                            $('#modal_tambah_barangKeluar').modal('hide');
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

                    if (error.responseJSON && error.responseJSON.customer_id && error.responseJSON.customer_id[
                            0]) {
                        $('#alert-customer_id').removeClass('d-none');
                        $('#alert-customer_id').addClass('d-block');

                        $('#alert-customer_id').html(error.responseJSON.customer_id[0]);
                    }
                    if (error.responseJSON && error.responseJSON['jumlah_keluar.0'] && error.responseJSON[
                            'jumlah_keluar.0'][0]) {
                        $('#alert-jumlah_keluar').removeClass('d-none');
                        $('#alert-jumlah_keluar').addClass('d-block');

                        $('#alert-jumlah_keluar').html(error.responseJSON['jumlah_keluar.0'][0]);
                    }
                }
            });
        }
    </script>

    <!-- Show Modal Tambah Jenis Barang -->
    <script>
        $('body').on('click', '#button_tambah_barangKeluar', function() {
            $('#modal_tambah_barangKeluar').modal('show');
            $('#kode_transaksi').val(generateKodeTransaksi());
        });
    </script>

    <!-- Show Detail Data Barang Keluar -->
    <script>
        $('body').on('click', '#button_detail_barang_keluar', function() {
            let barang_keluar_id = $(this).data('id');
            $.ajax({
                url: `/barang-keluar/${barang_keluar_id}`,
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    $('#barang_keluar_id').val(response.barang_keluar.id);
                    $('#detail_tgl_keluar').val(response.barang_keluar.tgl_keluar);
                    $('#detail_kode_transaksi').val(response.barang_keluar.kode_transaksi);
                    $('#detail_customer').val(response.barang_keluar.customer.customer);

                    let detailBarangKeluarsBody = $('#detail_barang_keluars_body');
                    detailBarangKeluarsBody.empty();

                    let detailBarangKeluars = response.detail_barang_keluars;

                    let counter = 1;
                    detailBarangKeluars.forEach(function(detailBarangKeluar) {
                        detailBarangKeluarsBody.append(`
                        <tr>
                            <td>${counter++}</td>
                            <td>${detailBarangKeluar.barang.nama_barang}</td>
                            <td>${detailBarangKeluar.jumlah_keluar}</td>
                        </tr>
                    `);
                    });

                    $('#modal_detail_barang_keluar').modal('show');
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
    </script>

    <!-- Edit Barang Keluar -->
    <script>
        $('body').on('click', '#button_edit_barang_keluar', function() {
            let barang_keluar_id = $(this).data('id');

            $.ajax({
                url: `/barang-keluar/${barang_keluar_id}/edit`,
                type: "GET",
                cache: false,
                success: function(response) {
                    editBarangKeluar(response.barang_keluar, response.detail_barang_keluars);
                    $('#modal_edit_barangKeluar').modal('show');
                }
            });
        });

        function editBarangKeluar(barangKeluar, detailBarangKeluars) {
            $('#barang_keluar_id').val(barangKeluar.id);
            $('#edit_kode_transaksi').val(barangKeluar.kode_transaksi);
            $('#edit_tgl_keluar').val(barangKeluar.tgl_keluar);
            $('#edit_customer_id').val(barangKeluar.customer_id);

            $('#form-edit-barang').empty();
            detailBarangKeluars.forEach(function(detail) {
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
                    '<label for="jumlah_keluar">Jumlah Keluar</label>' +
                    '<div class="input-group">' +
                    '<input type="number" class="form-control" name="jumlah_keluar[]" value="' + detail
                    .jumlah_keluar +
                    '" required>' +
                    '<div class="input-group-append">' +
                    '<button type="button" class="btn btn-danger btn-remove" onclick="hapusBaris(this)"><i class="fas fa-solid fa-x"></i></button>' +
                    '</div>' +
                    '</div>' +
                    '<div class="alert alert-danger mt-2 d-none" role="alert" id="alert-jumlah_keluar"></div>' +
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

        function updateBarangKeluar() {
            var formData = $('#form_edit_barang_keluar').serialize();
            formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');
            var barangKeluarId = $('#barang_keluar_id').val();
            $.ajax({
                type: 'PUT',
                url: '/barang-keluar/' + barangKeluarId,
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
                        url: "/barang-keluar/get-data",
                        type: "GET",
                        dataType: 'JSON',
                        success: function(response) {
                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let barangKeluars = `
                                <tr class="barang-row" id="index_${value.id}">
                                    <td>${counter++}</td>   
                                    <td>${value.kode_transaksi}</td>
                                    <td>${value.tgl_keluar}</td>
                                    <td>${value.customer.customer}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="javascript:void(0)" id="button_detail_barang_keluar" data-id="${value.id}" class="btn btn-icon btn-success btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_detail_barang_keluar"><i class="fas fa-eye"></i></a>
                                            <a href="javascript:void(0)" id="button_edit_barang_keluar" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_edit_barang_keluar"><i class="fas fa-edit"></i></a>
                                            <a href="javascript:void(0)" id="button_hapus_barang_keluar" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2 d-inline-block"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                `;
                                $('#table_id').DataTable().row.add($(barangKeluars)).draw(
                                    false);
                            });
                            $('#modal_edit_barang_keluar').modal('hide');
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
        $('body').on('click', '#button_hapus_barangKeluar', function() {
            let barangKeluar_id = $(this).data('id');
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
                        url: `/barang-keluar/${barangKeluar_id}`,
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
                            $(`#index_${barangKeluar_id}`).remove();

                            $.ajax({
                                url: "/barang-keluar/get-data",
                                type: "GET",
                                dataType: 'JSON',
                                success: function(response) {
                                    let counter = 1;
                                    $('#table_id').DataTable().clear();
                                    $.each(response.data, function(key, value) {
                                        let customer = getCustomerName(
                                            response.customer, value
                                            .customer_id);
                                        let barangKeluar = `
                                            <tr class="barang-row" id="index_${value.id}">
                                                <td>${counter++}</td>   
                                                <td>${value.kode_transaksi}</td>
                                                <td>${value.tgl_keluar}</td>
                                                <td>${value.customer.customer}</td>
                                                <td>    
                                                    <a href="javascript:void(0)" id="button_detail_barang_keluar" data-id="${value.id}" class="btn btn-icon btn-success btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_detail_barang_keluar"><i class="fas fa-eye"></i></a>
                                                    <a href="javascript:void(0)" id="button_edit_barang_keluar" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2 d-inline-block" data-toggle="modal" data-target="#modal_edit_barang_keluar"><i class="fas fa-edit"></i></a>   
                                                    <a href="javascript:void(0)" id="button_hapus_barangKeluar" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                                </td>
                                            </tr>
                                        `;
                                        $('#table_id').DataTable().row.add(
                                            $(barangKeluar)).draw(false);
                                    });

                                    function getCustomerName(customers,
                                        customerId) {
                                        let customer = customers.find(s => s.id ===
                                            customerId);
                                        return customer ? customer.customer : '';
                                    }
                                }
                            });
                        }
                    })
                }
            });
        });
    </script>

    <!-- Create Tanggal -->
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
        document.getElementById('tgl_keluar').value = formattedDate;
    </script>
@endsection
