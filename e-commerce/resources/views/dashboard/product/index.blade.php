@extends('layouts.dashboard')

@section('content')
<p>Daftar Produk</p>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">
    Tambah Produk
</button>

<div id="product-list" class="row">
    @foreach ($products as $product)
    <div class="col-md-3 m-2">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
            <img src="{{ asset('img/baju.png') }}"style="width: 250px; height: 300px;" alt="...">
                <h5 class="card-title">{{ $product->name }}</h5>
                <span class="badge text-bg-primary">{{ $product->category->name }}</span>
                <p class="card-text">{{ $product->description }}</p>
                <p class="card-text">{{ $product->price }}</p>
                <div class="d-flex">
                    <button class="btn btn-warning" data-toggle="modal" data-target="#editProductModal" onclick="loadProductData({{ $product->id }})">Edit</button>
                    <button class="btn btn-danger" onclick="deleteProduct('{{ route('dashboard.products.destroy', $product->id) }}')">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal untuk menambah produk -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="product-form" action="{{ route('dashboard.products.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="product-name" class="col-form-label">Nama Produk:</label>
                        <input type="text" class="form-control" id="product-name" name="name" required>

                        <label for="category" class="col-form-label">Kategori Produk:</label>
                        <select name="category_id" id="category" class="form-control">
                            <option value="selected disabled">Pilih Kategori Produk</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <label for="product-description" class="col-form-label">Deskripsi:</label>
                        <textarea class="form-control" id="product-description" name="description" required></textarea>

                        <label for="product-price" class="col-form-label">Harga:</label>
                        <input type="text" class="form-control" id="product-price" name="price" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="submitProductForm()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk edit produk -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-product-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-product-name" class="col-form-label">Nama Produk:</label>
                        <input type="text" class="form-control" id="edit-product-name" name="name" required>

                        <label for="edit-category" class="col-form-label">Kategori Produk:</label>
                        <select name="category_id" id="edit-category" class="form-control">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <label for="edit-product-description" class="col-form-label">Deskripsi:</label>
                        <textarea class="form-control" id="edit-product-description" name="description" required></textarea>

                        <label for="edit-product-price" class="col-form-label">Harga:</label>
                        <input type="text" class="form-control" id="edit-product-price" name="price" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="submitEditProductForm()">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function submitProductForm() {
        let form = document.getElementById('product-form');
        let data = new FormData(form);

        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#addProductModal').modal('hide');
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

    function loadProductData(id) {
    $.ajax({
        url: `/dashboard/products/${id}/edit`, // updated with 'dashboard' prefix
        type: 'GET',
        success: function(response) {
            $('#edit-product-name').val(response.name);
            $('#edit-category').val(response.category_id);
            $('#edit-product-description').val(response.description);
            $('#edit-product-price').val(response.price);
            $('#edit-product-form').attr('action', `/dashboard/products/${id}`);
            $('#editProductModal').modal('show');
        },
        error: function(error) {
            console.log(error);
        }
    });
}


    function submitEditProductForm() {
        let form = document.getElementById('edit-product-form');
        let data = new FormData(form);

        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#editProductModal').modal('hide');
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

    function deleteProduct(url) {
        if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
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
