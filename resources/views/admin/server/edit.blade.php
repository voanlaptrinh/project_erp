@extends('welcome')

@section('body')
    <div class="pagetitle">
        <h1>Chỉnh sửa Server</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('servers.index') }}">Server</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin Server</h5>

                        <form action="{{ route('servers.update', $server->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="from-group">
                                            <label class="form-label">Tên server*</label>

                                            <input type="text" name="server_name" class="form-control"
                                                value="{{ $server->server_name }}" required>

                                        </div>
                                        @error('server_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="from-group">
                                            <label class="form-label">Nhà cung cấp*</label>

                                            <input type="text" name="provider" class="form-control"
                                                value="{{ $server->provider }}" required>

                                        </div>
                                        @error('provider')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="from-group">
                                            <label class="form-label">IP Address*</label>

                                            <input type="text" name="ip_address" class="form-control"
                                                value="{{ $server->ip_address }}" required>

                                        </div>
                                        @error('ip_address')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="from-group">
                                            <label class="form-label">Hệ điều hành*</label>

                                            <input type="text" name="os" class="form-control"
                                                value="{{ $server->os }}" required>

                                        </div>
                                        @error('os')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="from-group">
                                            <label class="form-label">Tài khoản đăng nhập*</label>

                                            <input type="text" name="login_user" class="form-control"
                                                value="{{ $server->login_user }}" required>

                                        </div>
                                        @error('login_user')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="from-group">
                                            <label class="form-label">Mật khẩu*</label>

                                            <input type="text" name="login_password" class="form-control"
                                                value="{{ $server->login_password }}" required>

                                        </div>
                                        @error('login_password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="from-group">
                                            <label class="form-label">Ngày bắt đầu*</label>

                                            <input type="date" name="start_date" class="form-control"
                                                value="{{ $server->start_date }}" required>

                                        </div>
                                        @error('start_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="row mb-3">
                                        <div class="from-group">
                                            <label class="form-label">Ngày hết hạn*</label>

                                            <input type="date" name="expiry_date" class="form-control"
                                                value="{{ $server->expiry_date }}" required>

                                        </div>
                                        @error('expiry_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="from-group">
                                            <label class="form-label">Trạng thái*</label>

                                            <select name="status" class="form-select" required>
                                                <option value="active" {{ $server->status == 'active' ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="inactive"
                                                    {{ $server->status == 'inactive' ? 'selected' : '' }}>
                                                    Inactive</option>
                                                <option value="expired"
                                                    {{ $server->status == 'expired' ? 'selected' : '' }}>
                                                    Expired</option>
                                                <option value="suspended"
                                                    {{ $server->status == 'suspended' ? 'selected' : '' }}>Suspended
                                                </option>
                                            </select>

                                        </div>
                                        @error('status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="text-center d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary mx-2">Cập nhật</button>
                                <a href="{{ route('servers.index') }}" class="btn btn-secondary">Hủy bỏ</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
