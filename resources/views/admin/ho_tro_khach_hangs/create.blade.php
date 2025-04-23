@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý hỗ trợ khách hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">Hỗ trợ khách hàng</li>
                <li class="breadcrumb-item active">Thêm mới hỗ trợ khách hàng</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Tạo mới hỗ trợ khách hàng</h5>

                            <a href="{{ route('ho_tro_khach_hangs.index') }}" class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách hỗ trợ khách hàng</a>
                        </div>


                        <form action="{{ route('ho_tro_khach_hangs.store') }}" method="POST" class="row g-3">
                            @csrf
                        
                            <div class="col-lg-6">
                                <label for="khach_hang_id" class="form-label">Khách hàng</label>
                                <select name="khach_hang_id" class="form-select select_ted @error('khach_hang_id') is-invalid @enderror">
                                    <option value="">-- Chọn select --</option>
                                    @foreach ($khachHangs as $kh)
                                        <option value="{{ $kh->id }}" {{ old('khach_hang_id') == $kh->id ? 'selected' : '' }}>
                                            {{ $kh->ten }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('khach_hang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-lg-6">
                                <label for="nguoi_xu_ly_id" class="form-label">User xử lý</label>
                                <select name="nguoi_xu_ly_id" class="form-select  select_ted @error('nguoi_xu_ly_id') is-invalid @enderror">
                                    <option value="">-- Chọn select --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('nguoi_xu_ly_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('nguoi_xu_ly_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-lg-12">
                                <label for="tieu_de" class="form-label">Tiêu đề</label>
                                <input type="text" name="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror" value="{{ old('tieu_de') }}">
                                @error('tieu_de')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-lg-6">
                                <label for="uu_tien" class="form-label">Mức độ ưu tiên</label>
                                <select name="uu_tien" class="form-select">
                                    @foreach(['thấp', 'trung bình', 'cao', 'khẩn cấp'] as $item)
                                        <option value="{{ $item }}" {{ old('uu_tien', 'trung bình') == $item ? 'selected' : '' }}>
                                            {{ ucfirst($item) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <div class="col-lg-6">
                                <label for="trang_thai" class="form-label">Trạng thái</label>
                                <select name="trang_thai" class="form-select">
                                    @foreach(['mới', 'đang xử lý', 'đã xử lý', 'đã đóng'] as $item)
                                        <option value="{{ $item }}" {{ old('trang_thai', 'mới') == $item ? 'selected' : '' }}>
                                            {{ ucfirst($item) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <div class="col-lg-12">
                                <label for="noi_dung" class="form-label">Nội dung</label>
                                <textarea name="noi_dung" id="tyni" class="form-control @error('noi_dung') is-invalid @enderror" rows="4">{{ old('noi_dung') }}</textarea>
                                @error('noi_dung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Lưu yêu cầu</button>
                                <a href="{{ route('ho_tro_khach_hangs.index') }}" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
