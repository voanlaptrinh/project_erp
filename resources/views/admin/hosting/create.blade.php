@extends('welcome')

@section('body')
    <div class="pagetitle">
        <h1>Thêm mới Hosting</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hostings.index') }}">Hosting</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin Hosting</h5>
                        <form action="{{ route('hostings.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Tên hosting</label>

                                            <input type="text" name="service_name" class="form-control"
                                                placeholder="vd: Hosting 1">

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

                                            <select name="domain_id" class="form-select">
                                                <option value="">-- Chọn domain --</option>
                                                @foreach ($domains as $domain)
                                                    <option value="{{ $domain->id }}">{{ $domain->domain_name }}
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
                                                placeholder="vd: Google Cloud">

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
                                                placeholder="vd: Cloud">

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
                                                placeholder="vd: 1.1.1.1">

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

                                            <input type="date" name="start_date" class="form-control">

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

                                            <input type="date" name="expiry_date" class="form-control">

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
                                                placeholder="vd: Cloud">

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
                            </div>
                            <div class="text-center d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary mx-2">Lưu lại</button>
                                <a href="{{ route('hostings.index') }}" class="btn btn-secondary">Hủy bỏ</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
