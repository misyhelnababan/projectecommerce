@extends('layouts.dashboard')

@section('content')
<p>Ini Halaman Transaksi</p>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
Tambah Data Transaksi
</button>

{{--<div id="product-list" class="row">
    @foreach ($products as $product )
    <div class="col-md-3 m-2">
    <div class="card" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">{{$product['name']}}</h5>
    <p class="card-text">{{$product['description']}}</p>
    <p class="card-text">{{$product['price']}}</p>
  </div>
</div>
    </div>
   
    @endforeach
</div>--}}


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Transaksi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="product-form" action="{{route('products.store')}}" method="POST">
        @csrf
      <div class="modal-body">
       
            <div class="form-group">
                <label for="product name" class="col-form-label">Nama Transaksi : </label>
                <input type="text" class="form-control" id="product-name" name="name">

                <label for="product-description" class="col-form-label">Deskripsi Transaksi</label>
                <textarea type="text" class="form-control" id="product-description" name="description"></textarea>

                <label for="product-price" class="col-form-label">Jenis Transaksi</label>
                <input type="text" class="form-control" id="product-price" name="jenis"></input>

                </div>
      </div>            
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="submitForm()">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
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
            error : function(response){
                console.log(response);
            }
        });
    }
</script>
@endpush
