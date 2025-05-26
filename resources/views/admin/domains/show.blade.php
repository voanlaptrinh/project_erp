@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý domain</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Xem chi tiết domain {{ $domain->name }}</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Chi tiết domain</h5>

                            <a href="{{ route('domains.index') }}" class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách domain</a>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $domain->domain_name }}</h5>
                                <p><strong>Registrar:</strong> {{ $domain->registrar ?? 'Không có' }}</p>
                                <p><strong>Bắt đầu:</strong> {{ $domain->start_date }}</p>
                                <p><strong>Hết hạn:</strong> {{ $domain->expiry_date }}</p>
                                <p><strong>Trạng thái:</strong> {{ ucfirst($domain->status) }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
