@extends('welcome')

@section('body')
    <div class="pagetitle">
        <h1>Thêm mới Server</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('servers.index') }}">Server</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin Server</h5>

                        <form action="{{ route('servers.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">Tên server*</label>
                                        <input type="text" name="server_name" class="form-control"
                                            placeholder="vd:ab_server_302" value="{{ old('server_name') }}">
                                    </div>
                                    @error('server_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">Nhà cung cấp*</label>
                                        <input type="text" name="provider" class="form-control"
                                            placeholder="vd:Google Cloud" value="{{ old('provider') }}">
                                    </div>
                                    @error('provider')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-6form-label">IP Address*</label>
                                        <input type="text" name="ip_address" class="form-control"
                                            placeholder="vd:1.1.1.1" value="{{ old('ip_address') }}">
                                    </div>
                                    @error('ip_address')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-6form-label">Hệ điều hành*</label>
                                        <input type="text" name="os" class="form-control"
                                            placeholder="vd:Windows 10" value="{{ old('os') }}">
                                    </div>
                                    @error('os')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">Tài khoản đăng nhập*</label>
                                        <input type="text" name="login_user" class="form-control" placeholder="vd:admin"
                                            value="{{ old('login_user') }}">
                                    </div>
                                    @error('login_user')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">Mật khẩu*</label>
                                        <input type="text" name="login_password" class="form-control"
                                            placeholder="vd:123456" value="{{ old('login_password') }}">
                                    </div>
                                    @error('login_password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">Ngày bắt đầu*</label>
                                        <input type="date" name="start_date" class="form-control"
                                            value="{{ old('start_date') }}">
                                    </div>
                                    @error('start_date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">Ngày hết hạn*</label>
                                        <input type="date" name="expiry_date" class="form-control"
                                            value="{{ old('expiry_date') }}">
                                    </div>
                                    @error('expiry_date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">Trạng thái*</label>
                                        <select name="status" class="form-select">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="expired">Expired</option>
                                            <option value="suspended">Suspended</option>
                                        </select>
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-center d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary mx-2">Lưu lại</button>
                                <a href="{{ route('servers.index') }}" class="btn btn-secondary">Hủy bỏ</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
