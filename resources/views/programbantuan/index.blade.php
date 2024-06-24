@extends('layouts.app')

@section('content')

        <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
				<div class="mb-sm-4 d-flex flex-wrap align-items-center text-head">
					<h2 class="font-w600 mb-2 me-auto">Program Bantuan</h2>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-body row align-items-center">
                                <div class="col-lg-6">
                                    <div class="icon me-3">
                                        <img src="{{ asset('assets/images/search.svg') }}" alt="" width="80%">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div>
                                        <h2 class="invoice-num">Rekomendasi Program Bantuan</h2>
                                        <p>Dapatkan rekomendasi program bantuan dari kami dengan input potensi ataupun permasalahan dari desa.</p>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#searchModal"><button class="btn btn-sm btn-primary">Dapatkan Rekomendasi Program Bantuan</button></a>
                                    </div>
                                </div>
							</div>
						</div>
					</div>

                    <div class="col-lg-12">
                        <!-- Modal -->
                        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="searchModalLabel">Dapatkan Rekomendasi Program Bantuan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('rekomendasi_bantuan') }}" method="POST">
                                            @csrf
                                            <div class="row mb-5">
                                                <div class="col-lg-8">
                                                    <label for="searchInput" class="form-label">Input Potensi atau permasalahan</label>
                                                    <input type="text" name="inputTeks" class="form-control" id="searchInput" placeholder="Potensi atau permasalahan desa" required>

                                                </div>
                                                <div class="col-lg-4">
                                                    <label for=""></label>
                                                    <button type="submit" class="btn btn-sm btn-primary" style="margin-top: 35px;">Dapatkan Rekomendasi</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- Daftar Bantuan -->
                                        <h5>Daftar Desa Yang Memerlukan Bantuan</h5>
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <span>Desa : ini desa</span><br>
                                                <span>Potensi : ini potensi</span><br>
                                                <span>Permasalahan : ini permasalahan</span><br><br>
                                                <a href=""><button class="btn btn-sm btn-secondary">Dapatkan Rekomendasi</button></a>
                                            </li>
                                            <li class="list-group-item">
                                                <span>Desa : ini desa</span><br>
                                                <span>Potensi : ini potensi</span><br>
                                                <span>Permasalahan : ini permasalahan</span><br><br>
                                                <a href=""><button class="btn btn-sm btn-secondary">Dapatkan Rekomendasi</button></a>
                                            </li>
                                            <li class="list-group-item">
                                                <span>Desa : ini desa</span><br>
                                                <span>Potensi : ini potensi</span><br>
                                                <span>Permasalahan : ini permasalahan</span><br><br>
                                                <a href=""><button class="btn btn-sm btn-secondary">Dapatkan Rekomendasi</button></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
				</div>
            </div>
        </div>

@endsection
