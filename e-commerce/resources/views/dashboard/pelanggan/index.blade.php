@extends('layouts.dashboard')

@section('content')
<p>Daftar Pelanggan</p>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPelangganModal">
    Tambah Pelanggan
</button>

<div id="pelanggan-list" class="row">
    @foreach ($pelanggan as $item)
    <div class="col-md-3 m-2">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
            <img src="{{ asset('img/customer.png') }}"style="width: 250px; height: 300px;" alt="...">
                <h5 class="card-title">{{ $item->nama }}</h5>
                <p class="card-text">Email: {{ $item->email }}</p>
                <p class="card-text">Telepon: {{ $item->telepon }}</p>
                <p class="card-text">Alamat: {{ $item->alamat }}</p>
                <div class="d-flex">
                    <button class="btn btn-warning edit-button" data-toggle="modal" data-target="#editPelangganModal" onclick="loadPelangganData({{ $item->id }})">Edit</button>
                    <button class="btn btn-danger" onclick="deletePelanggan('{{ route('pelanggan.destroy', $item->id) }}')">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal untuk menambah pelanggan -->
<div class="modal fade" id="addPelangganModal" tabindex="-1" role="dialog" aria-labelledby="addPelangganModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPelangganModalLabel">Tambah Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="pelanggan-form" method="POST" action="{{ route('pelanggan.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="pelanggan-nama" class="col-form-label">Nama Pelanggan:</label>
                        <input type="text" class="form-control" id="pelanggan-nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="pelanggan-email" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" id="pelanggan-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="pelanggan-telepon" class="col-form-label">Telepon:</label>
                        <input type="text" class="form-control" id="pelanggan-telepon" name="telepon" required>
                    </div>
                </div>
                <div class="form-group">
                        <label for="pelanggan-telepon" class="col-form-label">Alamat:</label>
                        <input type="text" class="form-control" id="pelanggan-alamat" name="alamat" required>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitPelangganForm()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk edit pelanggan -->
<div class="modal fade" id="editPelangganModal" tabindex="-1" role="dialog" aria-labelledby="editPelangganModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPelangganModalLabel">Edit Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-pelanggan-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-pelanggan-nama" class="col-form-label">Nama Pelanggan:</label>
                        <input type="text" class="form-control" id="edit-pelanggan-nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-pelanggan-email" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" id="edit-pelanggan-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-pelanggan-telepon" class="col-form-label">Telepon:</label>
                        <input type="text" class="form-control" id="edit-pelanggan-telepon" name="telepon" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-pelanggan-telepon" class="col-form-label">Telepon:</label>
                        <input type="text" class="form-control" id="edit-pelanggan-alamat" name="alamat" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitEditPelangganForm()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function submitPelangganForm() {
    let form = document.getElementById('pelanggan-form');
    let data = new FormData(form);

    $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        success: function(response) {
            $('#addPelangganModal').modal('hide');
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

function loadPelangganData(id) {
    $.ajax({
        url: `/pelanggan/${id}/edit`,
        type: 'GET',
        success: function(response) {
            // Cek respons
            console.log(response); // Menampilkan respons di konsol

            // Pastikan untuk menggunakan property yang tepat dari response
            $('#edit-pelanggan-nama').val(response.data.nama);
            $('#edit-pelanggan-email').val(response.data.email);
            $('#edit-pelanggan-telepon').val(response.data.telepon);
            $('#edit-pelanggan-alamat').val(response.data.alamat); // Pastikan alamat ada di response
            $('#edit-pelanggan-form').attr('action', `/pelanggan/${id}`);
            $('#editPelangganModal').modal('show');
        },
        error: function(error) {
            console.log('Error:', error);
            alert('Gagal memuat data pelanggan.'); // Notifikasi error
        }
    });
}


function submitEditPelangganForm() {
    let form = document.getElementById('edit-pelanggan-form');
    let data = new FormData(form);

    $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        success: function(response) {
            $('#editPelangganModal').modal('hide');
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

function deletePelanggan(url) {
    if (confirm("Apakah Anda yakin ingin menghapus pelanggan ini?")) {
        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                window.location.reload(); // Refresh halaman setelah berhasil dihapus
            },
            error: function(response) {
                console.log(response); // Log error di konsol
                alert('Gagal menghapus pelanggan.'); // Notifikasi error
            }
        });
    }
}

</script>
@endpush
