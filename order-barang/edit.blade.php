<div class="modal fade" role="dialog" id="modal_edit_order-barang">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edits Order Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="order_barang_id" name="order_barang_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" class="form-control" name="tanggal" id="edit_tanggal" readonly>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-tanggal">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Pilih Barang</label>
                                <select class="form-control" name="nama_barang" id="edit_nama_barang"
                                    style="width: 100%">
                                    <option selected>Pilih Barang</option>
                                    @foreach ($barangs as $barang)
                                        <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
                                    @endforeach
                                </select>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-nama_barang"></div>
                            </div>

                            <div class="form-group">
                                <label>Supplier</label>
                                <select class="form-control" name="supplier_id" id="edit_supplier_id">
                                    <option value="">Pilih Supplier</option>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="number" class="form-control" name="jumlah" id="edit_jumlah">
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-jumlah">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" id="edit_keterangan" cols="30" rows="5" class="form-control"></textarea>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-keterangan">
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="button" class="btn btn-primary" id="update">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
