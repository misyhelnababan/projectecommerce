@extends('layouts.dashboard')

@section('content')
<p>Body Category</p>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">
  Tambah Kategori
</button>

<div id="category-list" class="row">
    @foreach ($categories as $category)
    <div class="col-md-3 m-2">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">{{ $category['name'] }}</h5>
                <p class="card-text">Slug: {{ $category['slug'] }}</p>
                <div class="d-flex">
                    <button class="btn btn-warning edit-button" data-toggle="modal" data-target="#editCategoryModal" onclick="loadCategoryData({{ $category->id }})">Edit</button>
                    <button class="btn btn-danger" onclick="deleteCategory('{{ route('categories.destroy', $category['id']) }}')">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal untuk menambah kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="category-form" method="POST" action="{{ route('categories.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category-name" class="col-form-label">Nama Kategori:</label>
                        <input type="text" class="form-control" id="category-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="category-slug" class="col-form-label">Slug:</label>
                        <input type="text" class="form-control" id="category-slug" name="slug" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitCategoryForm()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk edit kategori -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-category-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-category-name" class="col-form-label">Nama Kategori:</label>
                        <input type="text" class="form-control" id="edit-category-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-category-slug" class="col-form-label">Slug:</label>
                        <input type="text" class="form-control" id="edit-category-slug" name="slug" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitEditCategoryForm()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function submitCategoryForm() {
    let form = document.getElementById('category-form');
    let data = new FormData(form);

    $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        success: function(response) {
            $('#addCategoryModal').modal('hide');
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

function loadCategoryData(id) {
    $.ajax({
        url: `/categories/${id}/edit`,
        type: 'GET',
        success: function(response) {
            $('#edit-category-name').val(response.name);
            $('#edit-category-slug').val(response.slug);  // Menambahkan pengambilan slug
            $('#edit-category-form').attr('action', `/categories/${id}`);
            $('#editCategoryModal').modal('show');
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function submitEditCategoryForm() {
    let form = document.getElementById('edit-category-form');
    let data = new FormData(form);

    $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        success: function(response) {
            $('#editCategoryModal').modal('hide');
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

function deleteCategory(url) {
    if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                window.location.reload();
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
}
</script>
@endpush
