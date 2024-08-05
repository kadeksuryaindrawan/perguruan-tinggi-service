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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Data History Program Bantuan</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Desa</th>
                                                <th>Perguruan Tinggi</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=1;
                                            @endphp
                                                @foreach ($histories as $history)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $history->desa }}</td>
                                                        <td>{{ $history->perguruan_tinggi }}</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button id="toa" class="btn btn-sm btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                    <i class="flaticon-043-menu"></i>
                                                                </button>
                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                    <a href="{{ url('/history/detail?id=' . $userId . '&history_id=' . $history->id) }}" class="dropdown-item">Detail</a>
                                                                    <a href="{{ url('/history/edit?id=' . $userId . '&history_id=' . $history->id) }}" class="dropdown-item">Edit</a>
                                                                    <form action="{{ route('delete-process',$userId) }}" method="post" onsubmit="return confirm('Yakin hapus data history?')">
                                                                        @csrf
                                                                        <input type="hidden" name="history_id" value="{{ $history->id }}" id="">
                                                                        @method('delete')
                                                                        <button class="dropdown-item">Hapus</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
            </div>
        </div>

@endsection
