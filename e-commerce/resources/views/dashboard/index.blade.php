@extends('layouts.dashboard')

@section('content')
     <!-- Page Heading -->
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                     
                    </div>
                    <div class="row">

                   
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Pendapatan (BULAN)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp.400.000</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                     
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                               Pendapatan(Tahunan)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp.1000.000</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    
                    <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                    <div class="card-deck">
                    <div class="card">
                    <img class="card-img-top" src="{{ asset('img/baju.png') }}" style="width: 200px; height: auto;" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Baju Anime</h5>
                        <p class="card-text">Rp.150.000,00</p>
                    </div>
                    </div>
                </div>
                </div>
                </div>
                </div>
                </div>

                

                

@endsection
