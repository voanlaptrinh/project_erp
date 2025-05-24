@extends('welcome')

@section('body')
    <div class="pagetitle">
        <h1>Chi tiết Server</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('servers.index') }}">Server</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin Server</h5>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Tên server</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ $server->server_name }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Nhà cung cấp</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ $server->provider }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">IP Address</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ $server->ip_address }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Hệ điều hành</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ $server->os }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Tài khoản đăng nhập</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ $server->login_user }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Mật khẩu</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ $server->login_password }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Ngày bắt đầu</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ $server->start_date }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Ngày hết hạn</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ $server->expiry_date }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Trạng thái</label>
                            <div class="col-sm-10">
                                <span class="badge bg-{{ 
                                    $server->status == 'active' ? 'success' : 
                                    ($server->status == 'inactive' ? 'secondary' : 
                                    ($server->status == 'expired' ? 'danger' : 'warning'))
                                }}">
                                    {{ ucfirst($server->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="text-center d-flex justify-content-end">
                            <a href="{{ route('servers.index') }}" class="btn btn-primary">Quay lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection