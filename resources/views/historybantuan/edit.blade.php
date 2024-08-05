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
                                <h4 class="card-title">Edit Data Program Bantuan</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form method="POST" action="{{ route('edit-history-process',$userId) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="history_id" value="{{ $history->id }}" id="">
                                        <div class="row">
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Nama Desa</label>
                                                    <input type="text" class="form-control" name="desa" value="{{ $history->desa }}" placeholder="Input Nama Desa" required>
                                                    @error('desa')
                                                        <p class="text-danger text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Potensi</label>
                                                    <textarea class="form-control" name="potensi" id="" cols="30" rows="10" required>{{ $history->potensi }}</textarea>
                                                    @error('potensi')
                                                        <p class="text-danger text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Permasalahan</label>
                                                    <textarea class="form-control" name="permasalahan" id="" cols="30" rows="10" required>{{ $history->permasalahan }}</textarea>
                                                    @error('permasalahan')
                                                        <p class="text-danger text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Bantuan</label>
                                                    <textarea class="form-control" name="bantuan" id="" cols="30" rows="10" required>{{ $history->bantuan }}</textarea>
                                                    @error('bantuan')
                                                        <p class="text-danger text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Perguruan Tinggi</label>
                                                    <input type="text" class="form-control" name="perguruan_tinggi" value="{{ $history->perguruan_tinggi }}" placeholder="Input Perguruan Tinggi" required>
                                                    @error('perguruan_tinggi')
                                                        <p class="text-danger text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                        <button type="submit" class="btn btn-primary">Edit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
					</div>
                </div>
            </div>
        </div>

@endsection
