@extends('welcome')

@section('body')
    <div class="pagetitle">
        <h1>Chỉnh sửa Hosting</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hostings.index') }}">Hosting</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin Hosting</h5>

                        <form action="{{ route('hostings.update', $hosting->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Tên hosting</label>
                                            <input type="text" name="service_name" class="form-control"
                                                value="{{ old('service_name', $hosting->service_name ?? '') }}">
                                        </div>
                                        @error('service_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Domain</label>
                                            <select name="domain_id" class="form-select" required>
                                                @foreach ($domains as $domain)
                                                    <option value="{{ $domain->id }}"
                                                        {{ $hosting->domain_id == $domain->id ? 'selected' : '' }}>
                                                        {{ $domain->domain_name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('domain_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Nhà cung cấp</label>
                                            <input type="text" name="provider" class="form-control"
                                                value="{{ old('provider', $hosting->provider ?? '') }}">
                                        </div>
                                        @error('provider')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Gói dịch vụ</label>

                                            <input type="text" name="package" class="form-control"
                                                value="{{ old('package', $hosting->package ?? '') }}">

                                        </div>
                                        @error('package')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="form-group">
                                            <label class="form-label">IP Address</label>

                                            <input type="text" name="ip_address" class="form-control"
                                                value="{{ old('ip_address', $hosting->ip_address ?? '') }}">

                                        </div>
                                        @error('ip_address')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Ngày bắt đầu</label>

                                            <input type="date" name="start_date" class="form-control"
                                                value="{{ old('start_date', $hosting->start_date ?? '') }}">

                                        </div>
                                        @error('start_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Ngày hết hạn</label>
                                            <input type="date" name="expiry_date" class="form-control"
                                                value="{{ old('expiry_date', $hosting->expiry_date ?? '') }}">
                                        </div>
                                        @error('expiry_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Control Panel</label>
                                            <input type="text" name="control_panel" class="form-control"
                                                value="{{ old('control_panel', $hosting->control_panel ?? '') }}">
                                        </div>
                                        @error('control_panel')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Trạng thái</label>

                                            <select name="status" class="form-select" required>
                                                <option value="active"
                                                    {{ $hosting->status == 'active' ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="inactive"
                                                    {{ $hosting->status == 'inactive' ? 'selected' : '' }}>
                                                    Inactive</option>
                                                <option value="expired"
                                                    {{ $hosting->status == 'expired' ? 'selected' : '' }}>
                                                    Expired</option>
                                                <option value="suspended"
                                                    {{ $hosting->status == 'suspended' ? 'selected' : '' }}>
                                                    Suspended</option>
                                            </select>

                                        </div>
                                        @error('status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                    </div>
                    <div class="text-center d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mx-2">Cập nhật</button>
                        <a href="{{ route('hostings.index') }}" class="btn btn-secondary">Hủy bỏ</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection