@extends('layouts.app')

@section('content')

        <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
				<div class="mb-sm-4 d-flex flex-wrap align-items-center text-head">
					<h2 class="font-w600 mb-2 me-auto">Dashboard</h2>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-body d-flex">
								<div>
									<h3 style="line-height: 30px;">Selamat datang di dashboard Sistem Rekomendasi Program Bantuan, <br> <span class="text-primary">{{ $username }}</span></h3>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>

@endsection
