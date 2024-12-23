@extends('layouts.dashboard')

@section('content')
<h1>Edit Produk</h1>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">
  Edit Produk
</button>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Produk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="edit-product-form" action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label for="product-name" class="col-form-label">Nama Produk:</label>
            <input type="text" class="form-control" id="product-name" name="name" value="{{ $product->name }}" required>

            <label for="category" class="col-form-label">Nama Kategori:</label>
            <select name="category_id" id="category" class="form-control" required>
              <option value="" selected disabled>Pilih Kategori Produk</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>

            <label for="product-description" class="col-form-label">Deskripsi:</label>
            <textarea class="form-control" id="product-description" name="description" required>{{ $product->description }}</textarea>

            <label for="product-price" class="col-form-label">Harga:</label>
            <input type="number" class="form-control" id="product-price" name="price" value="{{ $product->price }}" required>
          </div>
        </div>            
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" onclick="submitEditForm()">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    function submitEditForm() {
        let form = document.getElementById('edit-product-form');
        let data = new FormData(form);

        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#editModal').modal('hide');
                window.location.reload();
            },
            error: function(response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    for (let key in errors) {
                        $(`[name="${key}"]`).addClass('is-invalid');
                    }
                }
            }
        });
    }
</script>
@endpush  
