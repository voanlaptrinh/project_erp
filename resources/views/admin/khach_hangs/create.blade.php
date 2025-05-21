@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý khách hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">Khách hàng</li>
                <li class="breadcrumb-item active">Thêm mới khách hàng</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Tạo mới khách hàng</h5>

                            <a href="{{ route('khach-hangs.index') }}" class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách khách hàng</a>
                        </div>
                        <form action="{{ route('khach-hangs.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-lg-6">
                                <label for="project_id" class="form-label">Dự án liên quan</label>
                                <select name="project_id" id="user_id" class="form-select">
                                    <option value="">-- Chọn dự án --</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->ten_du_an }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="ten" class="form-label">Tên khách hàng</label>
                                <input type="text" name="ten" class="form-control @error('ten') is-invalid @enderror"
                                    value="{{ old('ten') }}">
                                @error('ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="ten" class="form-label">Email</label>
                                <input type="text" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                                <input type="text" name="so_dien_thoai"
                                    class="form-control @error('so_dien_thoai') is-invalid @enderror"
                                    value="{{ old('so_dien_thoai') }}">
                                @error('so_dien_thoai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label for="dia_chi" class="form-label">Địa chỉ</label>
                                <input type="text" name="dia_chi"
                                    class="form-control @error('dia_chi') is-invalid @enderror"
                                    value="{{ old('dia_chi') }}">
                                @error('dia_chi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="ghi_chu" class="form-label">Ghi chú</label>
                                    <textarea name="ghi_chu" class="form-control" id="tyni" rows="3">{{ old('ghi_chu') }}</textarea>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Thêm mới khách hàng</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="container">
            <h2>Thêm khách hàng</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('khach-hangs.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="project_id" class="form-label">Dự án liên quan</label>
                    <select name="project_id" id="project_id" class="form-select">
                        <option value="">-- Chọn dự án --</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->ten_du_an }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="ten" class="form-label">Tên khách hàng</label>
                    <input type="text" name="ten" class="form-control @error('ten') is-invalid @enderror"
                        value="{{ old('ten') }}">
                    @error('ten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                    <input type="text" name="so_dien_thoai" class="form-control" value="{{ old('so_dien_thoai') }}">
                </div>

                <div class="mb-3">
                    <label for="dia_chi" class="form-label">Địa chỉ</label>
                    <input type="text" name="dia_chi" class="form-control" value="{{ old('dia_chi') }}">
                </div>

                <div class="mb-3">
                    <label for="ghi_chu" class="form-label">Ghi chú</label>
                    <textarea name="ghi_chu" class="form-control" rows="3">{{ old('ghi_chu') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Thêm khách hàng</button>
            </form>
        </div> --}}
    @endsection
