@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-header">
            Cek Ongkir
        </div>
        <div class="card-body">
            <form action="#" method="post" id="cekOngkirForm">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select name="province_origin" id="province_origin" class="form-control">
                            <option value="">Pilih Provinsi Asal</option>
                            <option value="1">Banten</option>
                            <option value="1">Jawa Timur</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="city_origin" id="city_origin" class="form-control" disabled>
                            <option value="">Pilih Kota Asal</option>
                            <option value="1">Tangerang</option>
                            <option value="1">Kota Malang</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select name="province_destination" id="province_destination" class="form-control">
                            <option value="">Pilih Provinsi Tujuan</option>
                            <option value="1">Banten</option>
                            <option value="1">Jawa Timur</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="city_destination" id="city_destination" class="form-control" disabled>
                            <option value="">Pilih Kota Tujuan</option>
                            <option value="1">Tangerang</option>
                            <option value="1">Kota Malang</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="number" name="weight" id="weight" class="form-control" placeholder="Berat (gram)">
                    </div>
                    <div class="col-md-4">
                        <select name="courier" id="courier" class="form-control">
                            <option value="">Pilih Kurir</option>
                            <option value="jne">JNE</option>
                            <option value="tiki">TIKI</option>
                            <option value="pos">Pos Indonesia</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Cek Ongkir</button>
                    </div>
                </div>
            </form>
            <div class="row" id="cost-content">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">REG - Layanan Reguler</h5>
                            <p class="card-text">Estimasi Pengiriman: 1-2 Hari (Rp. 23.000)</p>
                            <a href="#" class="btn btn-primary">Pilih Layanan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Setup data provinsi untuk select option
            $.ajax({
                url: 'http://localhost:8000/api/provinces',
                type: 'GET',
                success: function(result) {
                    let provinces = result.rajaongkir.results;
                    let optionsAsal = '<option value="">Pilih Provinsi Asal</option>';
                    let optionsTujuan = '<option value="">Pilih Provinsi Tujuan</option>';
                    provinces.forEach(province => {
                        optionsAsal +=
                            `<option value="${province.province_id}">${province.province}</option>`;
                        optionsTujuan +=
                            `<option value="${province.province_id}">${province.province}</option>`;
                    });

                    $('#province_origin').html(optionsAsal)
                    $('#province_destination').html(optionsTujuan)
                }
            })

            // Initialize select2
            $('#province_origin').select2();
            $('#city_origin').select2();
            $('#province_destination').select2();
            $('#city_destination').select2();
            $('#courier').select2();

            // Untuk mengisi data kota asal berdasarkan provinsi asal
            $('#province_origin').on('change', function() {
                let provinceId = $(this).val()
                $.ajax({
                    url: 'http://localhost:8000/api/cities/' + provinceId,
                    type: 'GET',
                    success: function(result) {
                        let cities = result.rajaongkir.results;
                        let optionsAsal = '<option value="">Pilih Kota Asal</option>';
                        cities.forEach(city => {
                            optionsAsal +=
                                `<option value="${city.city_id}">${city.type} ${city.city_name}</option>`;
                        });

                        $('#city_origin').removeAttr('disabled')
                        $('#city_origin').html(optionsAsal)
                    }
                })
            })

            // Untuk mengisi data kota tujuan berdasarkan provinsi tujuan
            $('#province_destination').on('change', function() {
                let provinceId = $(this).val()
                $.ajax({
                    url: 'http://localhost:8000/api/cities/' + provinceId,
                    type: 'GET',
                    success: function(result) {
                        let cities = result.rajaongkir.results;
                        let optionsTujuan = '<option value="">Pilih Kota Tujuan</option>';
                        cities.forEach(city => {
                            optionsTujuan +=
                                `<option value="${city.city_id}">${city.type} ${city.city_name}</option>`;
                        });

                        $('#city_destination').removeAttr('disabled')
                        $('#city_destination').html(optionsTujuan)
                    }
                })
            })

            // Untuk menghitung ongkir
            $('#cekOngkirForm').on('submit', function(e) {
                e.preventDefault()
                let origin = $('#city_origin').val()
                let destination = $('#city_destination').val()
                let weight = $('#weight').val()
                let courier = $('#courier').val()
                $.ajax({
                    url: 'http://localhost:8000/api/cost',
                    type: 'POST',
                    data: {
                        origin: origin,
                        destination: destination,
                        weight: weight,
                        courier: courier
                    },
                    success: function(result) {
                        let results = result.rajaongkir.results;
                        $('#cost-content').html('')
                        results.forEach(result => {
                            result.costs.forEach(cost => {
                                $('#cost-content').append(`
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${cost.service} - ${cost.description}</h5>
                                        <p class="card-text">Estimasi Pengiriman: ${cost.cost[0].etd} Hari (Rp. ${cost.cost[0].value})</p>
                                        <a href="#" class="btn btn-primary">Pilih Layanan</a>
                                    </div>
                                </div>
                            </div>
                            `)
                            })
                        });
                    }
                })
            })
        });
    </script>
@endpush
