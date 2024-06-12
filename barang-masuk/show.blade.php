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
        margin-right: 10px;
    }

    .nama-barang-item {
        flex: 2;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }
</style>

<div class="modal fade" role="dialog" id="modal_detail_barang_masuk" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white pb-3">
                <h5 class="modal-title">Detail Barang Masuk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data" id="form_show_barang_masuk" disabled>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" id="barang_masuk_id">
                        <div class="col-lg-4">
                            <div class="col-md-12">
                                <label for="kode_transaksi">Kode Transaksi</label>
                                <input type="text" class="form-control" id="detail_kode_transaksi"
                                    name="kode_transaksi" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="col-md-12">
                                <label for="tgl_masuk">Tanggal Masuk</label>
                                <input type="date" class="form-control" id="detail_tgl_masuk" name="tgl_masuk"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="col-md-12">
                                <label for="supplier">Supplier</label>
                                <input type="text" class="form-control" id="detail_supplier" name="supplier"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mt-3">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah Masuk</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail_barang_masuks_body">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                </div>
            </form>
        </div>
    </div>
</div>
