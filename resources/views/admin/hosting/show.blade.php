@extends('welcome')

@section('body')
    <div class="pagetitle">
        <h1>Chi tiết Hosting</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hostings.index') }}">Hosting</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin Hosting</h5>
                        <div class="row">

                            <div class="col-md-6">
                                <label class="col-form-label">Tên hosting</label>
                                <div class="">
                                    <input type="text" class="form-control" value="{{ $hosting->service_name }}"
                                        readonly>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <label class=" col-form-label">Domain</label>
                                <div class="">
                                    <input type="text" class="form-control"
                                        value="{{ $hosting->domain->domain_name ?? 'N/A' }}" readonly>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <label class=" col-form-label">Nhà cung cấp</label>
                                <div class="">
                                    <input type="text" class="form-control" value="{{ $hosting->provider }}" readonly>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <label class=" col-form-label">Gói dịch vụ</label>
                                <div class="">
                                    <input type="text" class="form-control" value="{{ $hosting->package }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class=" col-form-label">IP Address</label>
                                <div class="">
                                    <input type="text" class="form-control" value="{{ $hosting->ip_address ?? 'N/A' }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class=" col-form-label">Ngày bắt đầu</label>
                                <div class="">
                                    <input type="text" class="form-control" value="{{ $hosting->start_date }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class=" col-form-label">Ngày hết hạn</label>
                                <div class="">
                                    <input type="text" class="form-control" value="{{ $hosting->expiry_date }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class=" col-form-label">Control Panel</label>
                                <div class="">
                                    <input type="text" class="form-control"
                                        value="{{ $hosting->control_panel ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class=" col-form-label">Trạng thái</label>
                                <div class="">
                                    <span
                                        class="badge bg-{{ $hosting->status == 'active'
                                            ? 'success'
                                            : ($hosting->status == 'inactive'
                                                ? 'secondary'
                                                : ($hosting->status == 'expired'
                                                    ? 'danger'
                                                    : 'warning')) }}">
                                        {{ ucfirst($hosting->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center d-flex justify-content-end">
                            <a href="{{ route('hostings.index') }}" class="btn btn-primary">Quay lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
