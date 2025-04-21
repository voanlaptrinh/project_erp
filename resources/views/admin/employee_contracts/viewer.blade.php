@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý Hợp đồng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">Hợp đồng</li>
                <li class="breadcrumb-item active"></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title"></h5>

                            <a href="{{ route('admin.employee-contracts.index') }}" class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách hợp đồng</a>
                        </div>
                        <div class="container py-4">


                            <div style="height: 90vh;">
                                <iframe src="{{ asset($contract->file_hop_dong) }}" style="width: 100%; height: 100%;"
                                    frameborder="0">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
