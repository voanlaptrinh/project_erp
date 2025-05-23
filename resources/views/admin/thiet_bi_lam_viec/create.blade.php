@extends('welcome')

@section('body')
    <div class="pagetitle">
        <h1>Quản lý thiết bị làm việc</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">Thiết bị</li>
                <li class="breadcrumb-item active">Thêm mới thiết bị</li>
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

                            <a href="{{ route('thietbi.index') }}" class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách thiết bị</a>
                        </div>
                        <form action="{{ route('thietbi.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-lg-6">
                                <label for="user_id" class="form-label">Người sử dụng</label>
                                <select class="form-control" name="user_id">
                                    <option value="">-- Chọn --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id', $device->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="loai_thiet_bi" class="form-label">Loại thiết bị</label>
                                    <input type="text" class="form-control" name="loai_thiet_bi" placeholder="Nhập loại thiết bị"
                                        value="{{ old('loai_thiet_bi', $device->loai_thiet_bi ?? '') }}">
                                </div>
                                @error('loai_thiet_bi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="ten_thiet_bi" class="form-label">Tên thiết bị</label>
                                    <input type="text" class="form-control" name="ten_thiet_bi" placeholder="Nhập tên thiết bị"
                                        value="{{ old('ten_thiet_bi', $device->ten_thiet_bi ?? '') }}">
                                </div>
                                @error('ten_thiet_bi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="he_dieu_hanh" class="form-label">Hệ điều hành</label>
                                    <input type="text" class="form-control" name="he_dieu_hanh" placeholder="Pc, laptop, tablet..."
                                        value="{{ old('he_dieu_hanh', $device->he_dieu_hanh ?? '') }}">
                                </div>
                                @error('he_dieu_hanh')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="cau_hinh" class="form-label">Cấu hình</label>
                                        <textarea class="form-control" name="cau_hinh" placeholder="Nhập cấu hình">{{ old('cau_hinh', $device->cau_hinh ?? '') }}</textarea>
                                    </div>
                                </div>
                                @error('cau_hinh')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="so_serial" class="form-label">Số serial</label>
                                    <input type="text" class="form-control" name="so_serial" placeholder="Nhập số serial"
                                        value="{{ old('so_serial', $device->so_serial ?? '') }}">
                                </div>
                                @error('so_serial')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="ngay_ban_giao" class="form-label">Ngày bàn giao</label>
                                    <input type="date" class="form-control" name="ngay_ban_giao"
                                        value="{{ old('ngay_ban_giao', $device->ngay_ban_giao ?? '') }} ">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label for="ghi_chu" class="form-label">Ghi chú</label>
                                <textarea class="form-control" id="tyni" name="ghi_chu">{{ old('ghi_chu', $device->ghi_chu ?? '') }}</textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Thêm mới thiết bị</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
