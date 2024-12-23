@extends('layouts.dashboard')

@section('content')
<p>Body Transaksi</p>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTransaksiModal">
    Tambah Transaksi
</button>

<div id="transaksi-list" class="row">
    @foreach ($transaksi as $item)
    <div class="col-md-3 m-2">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
            <img src="{{ asset('img/transaksi.png') }}"style="width: 250px; height: 300px;" alt="...">
                <h5 class="card-title">Transaksi #{{ $item->id }}</h5>
                <p class="card-text">Pelanggan: {{ $item->pelanggan->nama }}</p>
                <p class="card-text">Total Harga: {{ number_format($item->total_harga, 2) }}</p>
                <p class="card-text">Tanggal: {{ $item->tanggal_transaksi }}</p>
                <p class="card-text">Status: {{ $item->status }}</p>
                <div class="d-flex">
                    <button class="btn btn-warning" data-toggle="modal" data-target="#editTransaksiModal" onclick="loadTransaksiData({{ $item->id }})">Edit</button>
                    <button class="btn btn-danger" onclick="deleteTransaksi('{{ route('transaksi.destroy', $item->id) }}')">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal untuk menambah transaksi -->
<div class="modal fade" id="addTransaksiModal" tabindex="-1" role="dialog" aria-labelledby="addTransaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTransaksiModalLabel">Tambah Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="transaksi-form" action="{{ route('transaksi.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="transaksi-pelanggan" class="col-form-label">Pelanggan:</label>
                        <select class="form-control" id="transaksi-pelanggan" name="pelanggan_id" required>
                            @foreach ($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transaksi-total_harga" class="col-form-label">Total Harga:</label>
                        <input type="number" step="0.01" class="form-control" id="transaksi-total_harga" name="total_harga" required>
                    </div>
                    <div class="form-group">
                        <label for="transaksi-tanggal" class="col-form-label">Tanggal Transaksi:</label>
                        <input type="date" class="form-control" id="transaksi-tanggal" name="tanggal_transaksi" required>
                    </div>
                    <div class="form-group">
                        <label for="transaksi-status" class="col-form-label">Status:</label>
                        <select class="form-control" id="transaksi-status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="submitTransaksiForm()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk edit transaksi -->
<div class="modal fade" id="editTransaksiModal" tabindex="-1" role="dialog" aria-labelledby="editTransaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTransaksiModalLabel">Edit Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-transaksi-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-transaksi-pelanggan" class="col-form-label">Pelanggan:</label>
                        <select class="form-control" id="edit-transaksi-pelanggan" name="pelanggan_id" required>
                            @foreach ($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-transaksi-total_harga" class="col-form-label">Total Harga:</label>
                        <input type="number" step="0.01" class="form-control" id="edit-transaksi-total_harga" name="total_harga" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-transaksi-tanggal" class="col-form-label">Tanggal Transaksi:</label>
                        <input type="date" class="form-control" id="edit-transaksi-tanggal" name="tanggal_transaksi" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-transaksi-status" class="col-form-label">Status:</label>
                        <select class="form-control" id="edit-transaksi-status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="submitEditTransaksiForm()">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function submitTransaksiForm() {
    let form = document.getElementById('transaksi-form');
    let data = new FormData(form);

    $.ajax({
        url: $(form).attr('action'), // Pastikan action form sesuai dengan rute POST
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF
        },
        success: function(response) {
            $('#addTransaksiModal').modal('hide');
            window.location.reload(); // Reload halaman setelah berhasil
        },
        error: function(response) {
            if (response.status === 422) { // Validasi gagal
                let errors = response.responseJSON.errors;
                for (let key in errors) {
                    $(`[name=${key}]`).addClass('is-invalid'); // Tambahkan kelas is-invalid pada input yang error
                    $(`[name=${key}]`).next('.invalid-feedback').text(errors[key]); // Tambahkan pesan error
                }
            }
        }
    });
}


function loadTransaksiData(id) {
    $.ajax({
        url: `/transaksi/${id}/edit`,
        type: 'GET',
        success: function(response) {
            $('#edit-transaksi-pelanggan').val(response.pelanggan_id);
            $('#edit-transaksi-total_harga').val(response.total_harga);
            $('#edit-transaksi-tanggal').val(response.tanggal_transaksi);
            $('#edit-transaksi-status').val(response.status);
            $('#edit-transaksi-form').attr('action', `/transaksi/${id}`);
            $('#editTransaksiModal').modal('show');
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function submitEditTransaksiForm() {
    let form = document.getElementById('edit-transaksi-form');
    let data = new FormData(form);

    $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        success: function(response) {
            $('#editTransaksiModal').modal('hide');
            window.location.reload();
        },
        error: function(response) {
            if (response.status === 422) {
                let errors = response.responseJSON.errors;
                for (let key in errors) {
                    $(`[name=${key}]`).addClass('is-invalid');
                }
            }
        }
    });
}

function deleteTransaksi(url) {
    if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                window.location.reload();
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
}
</script>
@endpush
