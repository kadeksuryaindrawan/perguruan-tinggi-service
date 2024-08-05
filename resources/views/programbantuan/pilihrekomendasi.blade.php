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
                                <h4 class="card-title">Data Program Bantuan</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form method="POST" action="{{ route('pilih_process',$userId) }}" enctype="multipart/form-data">
                                        @csrf
                                        @if ($permasalahan != null)
                                            <input type="hidden" name="id_desa" value="{{ $permasalahan->id_desa }}" id="">
                                            <input type="hidden" name="id_permasalahan" value="{{ $permasalahan->id }}" id="">
                                        @endif

                                        <div class="row">
                                            @if ($permasalahan !=null)
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Nama Desa</label>
                                                    <input type="text" class="form-control" name="desa" value="{{ $permasalahan->desa->desa }}" placeholder="Input Nama Desa" required>
                                                    @error('desa')
                                                        <p class="text-danger text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Potensi</label>
                                                    <textarea class="form-control" name="potensi" id="" cols="30" rows="10" required>{{ $permasalahan->potensi }}</textarea>
                                                    @error('potensi')
                                                        <p class="text-danger text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Permasalahan</label>
                                                    <textarea class="form-control" name="permasalahan" id="" cols="30" rows="10" required>{{ $permasalahan->permasalahan }}</textarea>
                                                    @error('permasalahan')
                                                        <p class="text-danger text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                @if ($history == null)
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">Bantuan</label>
                                                        <textarea class="form-control" name="bantuan" id="" cols="30" rows="10" required></textarea>
                                                        @error('bantuan')
                                                            <p class="text-danger text-sm">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                @else
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">Bantuan</label>
                                                        <textarea class="form-control" name="bantuan" id="" cols="30" rows="10" required>{{ $history->bantuan }}</textarea>
                                                        @error('bantuan')
                                                            <p class="text-danger text-sm">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                @endif

                                            @else
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Nama Desa</label>
                                                    <input type="text" class="form-control" name="desa" placeholder="Input Nama Desa" required>
                                                    @error('desa')
                                                        <p class="text-danger text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Potensi</label>
                                                    <textarea class="form-control" name="potensi" id="" cols="30" rows="10" required></textarea>
                                                    @error('potensi')
                                                        <p class="text-danger text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Permasalahan</label>
                                                    <textarea class="form-control" name="permasalahan" id="" cols="30" rows="10" required></textarea>
                                                    @error('permasalahan')
                                                        <p class="text-danger text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                @if ($history == null)
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">Bantuan</label>
                                                        <textarea class="form-control" name="bantuan" id="" cols="30" rows="10" required></textarea>
                                                        @error('bantuan')
                                                            <p class="text-danger text-sm">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                @else
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">Bantuan</label>
                                                        <textarea class="form-control" name="bantuan" id="" cols="30" rows="10" required>{{ $history->bantuan }}</textarea>
                                                        @error('bantuan')
                                                            <p class="text-danger text-sm">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                @endif
                                            @endif


                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label">Perguruan Tinggi</label>
                                                    <input type="text" class="form-control" name="perguruan_tinggi" placeholder="Input Perguruan Tinggi" required>
                                                    @error('perguruan_tinggi')
                                                        <p class="text-danger text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
					</div>
                </div>
            </div>
        </div>

@endsection
