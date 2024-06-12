<div class="modal fade" role="dialog" id="modal_tambah_barangMasuk">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Masuk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data" id="form_tambah_barang_masuk">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tanggal Masuk</label>
                                        <input type="text" class="form-control" name="tgl_masuk" id="tgl_masuk"
                                            readonly>
                                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-tgl_masuk">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Kode Transaksi</label>
                                        <input type="text" class="form-control" name="kode_transaksi"
                                            id="kode_transaksi" readonly>
                                        <div class="alert alert-danger mt-2 d-none" role="alert"
                                            id="alert-kode_transaksi">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Supplier</label>
                                <select class="form-control" name="supplier_id" id="supplier_id">
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach ($suppliers as $supplier)
                                        @if (old('supplier_id') == $supplier->id)
                                            <option value="{{ $supplier->id }}" selected>{{ $supplier->supplier }}
                                            </option>
                                        @else
                                            <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-supplier_id"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 mt-4">
                                <div class="detail-barang-container" id="form-tambah-barang">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="button" class="btn btn-light float-right" onclick="tambahBarang()"><i
                                            class="fas fa-solid fa-plus"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="button" class="btn btn-primary" onclick="simpanBarangMasuk()">Tambah</button>
            </form>
        </div>
    </div>
</div>
</div>
