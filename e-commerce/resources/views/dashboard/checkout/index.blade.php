@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-header">
            Checkout
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    @foreach ($errors->all() as $error)
                        {{ $error }},
                    @endforeach
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="alert alert-primary" role="alert">
                Kota Asal: Tangerang, Banten -> Kota Tujuan: Malang, Jawa Timur
            </div>
            <form action="{{ route('dashboard.checkout.store') }}" method="POST">
                @csrf
                <div id="product" class="mb-4 d-flex">
                    @foreach ($products as $item)
                        <div class="card mr-3" style="width: 18rem;">
                            <img src="https://img.freepik.com/premium-vector/default-image-icon-vector-missing-picture-page-website-design-mobile-app-no-photo-available_87543-11093.jpg"
                                class="card-img-top" alt="">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <p class="card-text">[{{ $item->category->name }}] - {{ $item->description }}</p>
                                <p class="card-text">Rp. {{ $item->price }}/kg</p>
                                <input type="hidden" name="product_price[]" value="{{ $item->price }}" disabled>
                                <input type="hidden" name="product_id[]" value="{{ $item->id }}">
                                <input type="number" class="form-control" name="product_quantity[]"
                                    id="product_quantity_{{ $item->id }}">
                            </div>
                        </div>
                    @endforeach
                </div>
                <div id="courier" class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="courier">Courier</label>
                            <select class="form-control" name="courier_type" id="courier_type">
                                <option value="jne">JNE</option>
                                <option value="tiki">TIKI</option>
                                <option value="pos">POS</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="courier">Service</label>
                            <select class="form-control" name="courier_service" id="courier_service" disabled>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        Detail Transaksi
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush" id="transaction_details">
                        </ul>
                    </div>
                    <div class="card-footer">
                        <div id="product_total" class="d-none">0</div>
                        <div class="d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong>Rp. <span id="detail_total">0</span></strong>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" id="btnSubmit">Checkout</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this,
                    args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function() {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        function initData() {
            let courier = $('#courier_type').val();
            let origin = 456;
            let destination = 256;
            let weight = '2000'
            let url = `{{ url('api/cost') }}`;
            $('#courier_service').attr('disabled', true);
            $.ajax({
                url: 'http://localhost:8000/api/cost',
                type: 'POST',
                data: {
                    origin: origin,
                    destination: destination,
                    weight: weight,
                    courier: courier
                },
                beforeSend: function() {
                    $('#courier_service').html('<option>Loading...</option>')
                    $('#btnSubmit').attr('disabled', true)
                },
                success: function(result) {
                    $('#btnSubmit').attr('disabled', false)
                    let results = result.rajaongkir.results;
                    $('#cost-content').html('')
                    results.forEach(result => {
                        $('#courier_service').html('')
                        result.costs.forEach(cost => {
                            $('#courier_service').append(`
                                    <option value="${cost.service}-${cost.description}-${cost.cost[0].value}">${cost.service} - ${cost.description} - Rp. ${cost.cost[0].value}</option>
                                `)
                        })
                    });
                    let service = $('#courier_service').val();
                    let split = service.split('-');
                    let service_price = split[2];
                    $('#detail_ongkir').html(service_price);
                    $('#courier_service').attr('disabled', false);
                }
            })
        }

        $(document).ready(function() {
            initData();
            const products = @json($products);
            $('[name="product_quantity[]"]').on('keyup', delay(function() {
                let total = 0;
                $('[name="product_quantity[]"]').each(function(index, item) {
                    let price = $(`[name="product_price[]"]`).eq(index).val();
                    let productId = $(`[name="product_id[]"]`).eq(index).val();
                    let quantity = $(item).val();
                    if (quantity == '') {
                        quantity = 0;
                    }
                    $(`#detail_quantity_${productId}`).html(quantity);
                    $(`#detail_price_${productId}`).html(price * quantity);
                    total += price * quantity;
                    $('#product_total').html(total);
                });
                let ongkir = $('#detail_ongkir').html();
                total += parseInt(ongkir);
                $('#detail_total').html(total);
            }, 500));

            $('#transaction_details').html('');
            products.forEach(product => {
                $('#transaction_details').append(`
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span>${product.name}</span>
                            <span>(<span id="detail_quantity_${product.id}">0</span>) ${product.price} - Rp. <span id="detail_price_${product.id}">0</span></span>
                        </div>
                    </li>
                `);
            });
            $('#transaction_details').append(`
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span>Ongkir</span>
                            <span>Rp. <span id="detail_ongkir">0</span></span>
                        </div>
                    </li>
                `);
            $('#courier_service').change(function() {
                let service = $(this).val();
                let split = service.split('-');
                let service_price = split[2];
                let product_total = $('#product_total').html();
                let total = parseInt(product_total) + parseInt(service_price);
                $('#detail_total').html(total);
                $('#detail_ongkir').html(service_price);
            })

            $('#courier_type').change(function() {
                let courier = $(this).val();
                let origin = 456;
                let destination = 256;
                let weight = '2000'
                let url = `{{ url('api/cost') }}`;
                $('#courier_service').attr('disabled', true);
                $.ajax({
                    url: 'http://localhost:8000/api/cost',
                    type: 'POST',
                    data: {
                        origin: origin,
                        destination: destination,
                        weight: weight,
                        courier: courier
                    },
                    beforeSend: function() {
                        $('#courier_service').html('<option>Loading...</option>')
                        $('#btnSubmit').attr('disabled', true)
                    },
                    success: function(result) {
                        $('#btnSubmit').attr('disabled', false)
                        let results = result.rajaongkir.results;
                        $('#cost-content').html('')
                        results.forEach(result => {
                            $('#courier_service').html('')
                            result.costs.forEach(cost => {
                                $('#courier_service').append(`
                                    <option value="${cost.service}-${cost.description}-${cost.cost[0].value}">${cost.service} - ${cost.description} - Rp. ${cost.cost[0].value}</option>
                                `)
                            })
                        });
                        $('#courier_service').attr('disabled', false);
                    }
                })
            });
        });
    </script>
@endpush
