@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý Hợp đồng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">Hợp đồng</li>
                <li class="breadcrumb-item active">Thêm mới hợp đồng</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Tạo mới hợp đồng</h5>

                            <a href="{{ route('admin.employee-contracts.index') }}" class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách hợp đồng</a>
                        </div>

                        <form action="{{ route('admin.employee-contracts.store') }}" method="POST"
                            enctype="multipart/form-data" class="space-y-4 row g-3">
                            @csrf


                            <div class="col-lg-6">
                                <label for="user_id" class="form-label">Nhân viên:</label>
                                <select name="user_id" id="user_id" required class="form-select">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="loai_hop_dong" class="form-label">Loại hợp đồng:</label>

                                    <input type="text" name="loai_hop_dong" class="form-control"
                                        value="{{ old('loai_hop_dong') }}" placeholder="VD: Thưc tập sinh, Nhân viên chính thức">
                                </div>
                                @error('loai_hop_dong')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label for="ngay_bat_dau" class="form-label">Ngày bắt đầu:</label>
                                <input type="date" name="ngay_bat_dau" class="form-control"
                                    value="{{ old('ngay_bat_dau') }}">
                                @error('ngay_bat_dau')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label">Ngày kết thúc:</label>
                                <input type="date" name="ngay_ket_thuc" class="form-control"
                                    value="{{ old('ngay_ket_thuc') }}">
                                @error('ngay_ket_thuc')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label">Lương thỏa thuận:</label>
                                <input type="text" name="luong_thoa_thuan" class="form-control"
                                    value="{{ old('luong_thoa_thuan') }}" placeholder="VD: 50000000 ">
                                @error('luong_thoa_thuan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label">File hợp đồng (chỉ file pdf):</label>
                                <input type="file" name="file_hop_dong" accept=".pdf" class="form-control">
                                @error('file_hop_dong')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Thêm mới dự án</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
