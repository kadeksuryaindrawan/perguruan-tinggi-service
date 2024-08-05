@extends('layouts.app')

@section('content')

        <div class="content-body">
            <div class="container-fluid">
                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                            @if(session('success'))
                            <div class="alert alert-success solid" role="alert">
                                {{session('success')}}
                            </div>
                            @endif

                            @if(session('error'))
                            <div class="alert alert-danger solid" role="alert">
                                {{session('error')}}
                            </div>
                            @endif
                        </div>
					<div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Detail Data Program Bantuan</h4>
                            </div>
                            <div class="card-body">
                                <p>Nama Desa : {{ $history->desa }}</p>
                                <p>Potensi : {{ $history->potensi }}</p>
                                <p>Permasalahan : {{ $history->permasalahan }}</p>
                                <p>Bantuan : {{ $history->bantuan }}</p>
                                <p>Perguruan Tinggi : {{ $history->perguruan_tinggi }}</p>
                                <a href="{{ url('/history?id='.$userId) }}"><button class="btn btn-primary btn-sm">Kembali</button></a>
                            </div>
                        </div>
					</div>
                </div>
            </div>
        </div>

@endsection
