<style>
    .detail-barang-container {
        display: flex;
        flex-direction: column;
    }

    .detail-barang-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .detail-barang-item {
        flex: 1;
    }

    .nama-barang-item {
        flex: 2;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }
</style>

<div class="modal fade" role="dialog" id="modal_edit_barang_keluar" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white pb-3">
                <h5 class="modal-title">Edit Barang Masuk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data" id="form_edit_barang_keluar">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" id="barang_keluar_id">
                        <div class="col-lg-6">
                            <div class="col-md-12">
                                <label for="kode_transaksi">Kode Transaksi</label>
                                <input type="text" class="form-control" id="edit_kode_transaksi"
                                    name="kode_transaksi">
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-kode_transaksi">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="col-md-12">
                                <label for="tgl_keluar">Tanggal Keluar</label>
                                <input type="date" class="form-control" id="edit_tgl_keluar" name="tgl_keluar">
                            </div>
                        </div>
                        <div class="col">
                            <div class="col-md-12">
                                <label for="customer_id">Customer</label>
                                <select class="form-control" name="customer_id" id="edit_customer_id">
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->customer }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mt-4">
                            <div class="detail-barang-container" id="form-edit-barang">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="button" class="btn btn-primary" onclick="updateBarangKeluar()">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
