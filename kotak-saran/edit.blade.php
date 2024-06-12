<div class="modal fade" role="dialog" id="modal_edit_kotak-saran">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Saran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" id="kotak_saran_id" name="kotak_saran_id">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" class="form-control" name="tanggal" id="edit_tanggal" readonly>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-tanggal"></div>
                            </div>
                            <div class="form-group">
                                <label>Pilih Barang</label>
                                <select class="form-control" name="nama_barang" id="edit_nama_barang"
                                    style="width: 100%">
                                    <option value="" selected>Pilih Barang</option>
                                    @foreach ($barangs as $barang)
                                        <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
                                    @endforeach
                                </select>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-nama_barang"></div>
                            </div>
                            <div class="form-group">
                                <label>Customer</label>
                                <select class="form-control" name="customer_id" id="edit_customer_id">
                                    <option value="">Pilih Customer</option>
                                    @foreach ($customers as $customer)
                                        @if (old('customer_id') == $customer->id)
                                            <option value="{{ $customer->id }}" selected>{{ $customer->customer }}
                                            </option>
                                        @else
                                            <option value="{{ $customer->id }}">{{ $customer->customer }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-customer_id"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ide Gagasan</label>
                                <textarea name="ide_gagasan" id="edit_ide_gagasan" cols="30" rows="5" class="form-control"></textarea>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-ide_gagasan"></div>
                            </div>
                            <div class="form-group">
                                <label>Inovasi</label>
                                <textarea name="inovasi" id="edit_inovasi" cols="30" rows="5" class="form-control"></textarea>
                                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-inovasi"></div>
                            </div>
                            <div class="form-group">
                                <label>Keluhan Operasional</label>
                                <textarea name="keluhan_operasional" id="edit_keluhan_operasional" cols="30" rows="5" class="form-control"></textarea>
                                <div class="alert alert-danger mt-2 d-none" role="alert"
                                    id="alert-keluhan_operasional"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="submit" class="btn btn-primary" id="update">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
