@extends('welcome')

@section('body')
    <div class="pagetitle">
        <h1>Quản lý domain</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">Domains</li>
                <li class="breadcrumb-item active">Sửa thông tin domain</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Tạo mới thiết bị</h5>

                            <a href="{{ route('domains.index') }}" class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách thiết bị</a>
                        </div>
                        <form method="POST" action="{{ route('domains.update', $domain->id) }}" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="domain_name" class="form-label">Tên domain</label>
                                    <input type="text" name="domain_name" class="form-control"
                                        placeholder="vd:okuneva.com"
                                        value="{{ old('domain_name', $domain->domain_name ?? '') }}">
                                </div>
                                @error('domain_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="registrar" class="form-label">Người đăng ký</label>
                                <input type="text" name="registrar" class="form-control"
                                    value="{{ old('registrar', $domain->registrar ?? '') }}">
                            </div>
                            <div class="col-lg-6">
                                <label for="start_date" class="form-label">Ngày bắt đầu</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ old('start_date', $domain->start_date ?? '') }}">
                            </div>
                            <div class="col-lg-6">
                                <label for="expiry_date" class="form-label">Ngày hết hạn</label>
                                <input type="date" name="expiry_date" class="form-control"
                                    value="{{ old('expiry_date', $domain->expiry_date ?? '') }}">
                            </div>
                            <div class="col-lg-6">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select name="status" class="form-control">
                                    <option value="active"
                                        {{ old('status', $domain->status ?? '') == 'active' ? 'selected' : '' }}>Kích
                                        hoạt</option>
                                    <option value="inactive"
                                        {{ old('status', $domain->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                        Ngưng</option>
                                    <option value="pending"
                                        {{ old('status', $domain->status ?? '') == 'pending' ? 'selected' : '' }}>
                                        Chưa kích hoạt</option>
                                    <option value="expired"
                                        {{ old('status', $domain->status ?? '') == 'expired' ? 'selected' : '' }}>
                                        Hết hạn</option>
                                </select>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
