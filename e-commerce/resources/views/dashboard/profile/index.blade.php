@extends('layouts.dashboard')

@section('content')
<form action="">
    <div class="form-group">
        <label for="name">Nama : </label>
        <input type="text" class="form-control" name="name" id="name" value="{{$profile->name}}">
    </div>
    <div class="form-group">
        <label for="email">Email :</label>
        <input type="text" class="form-control"name="email" id="email" value="{{$profile->email}}">
    </div>
    <div class="form-group">
    <label for="avatar">Avatar</label>
    <input type="file" class="file-input" name="avatar" id="avatar">
    </div>
    @if($profile->avatar)
    <img src="{{asset('storage/'. $profile->avatar)}}" alt="Avatar" style="width:100px;"height="100px;">
        @endif
    <div class="custom-file">
    <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="avatar">
    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
  </div>
</div>
</form>
@endsection

@push('scripts')
<script>
    function loadProductData(id) {
        $.ajax({
            url: `/products/${id}/edit`,
            type: 'GET',
            success: function(response) {
                $('#edit-product-name').val(response.name);
                $('#edit-category').val(response.category_id);
                $('#edit-product-description').val(response.description);
                $('#edit-product-price').val(response.price);
                $('#edit-product-form').attr('action', `/products/${id}`);
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

    function submitForm() {
        let form = document.getElementById('product-form');
        let data = new FormData(form);

        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#exampleModal').modal('hide');
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
