@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý hỗ trợ khách hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">Hỗ trợ khách hàng</li>
                <li class="breadcrumb-item active">Sửa hỗ trợ khách hàng</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Sửa hỗ trợ khách hàng</h5>

                            <a href="{{ route('ho_tro_khach_hangs.index') }}" class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách hỗ trợ khách hàng</a>
                        </div>

                        <form action="{{ route('ho_tro_khach_hangs.update', $ticket->id) }}" method="POST" class="row g-3">
                            @csrf
                            @method('PUT')
                        
                            <div class="col-lg-6">
                                <label for="khach_hang_id" class="form-label">Khách hàng</label>
                                <select name="khach_hang_id" class="form-select select_ted @error('khach_hang_id') is-invalid @enderror" required>
                                    @foreach ($khachHangs as $kh)
                                        <option value="{{ $kh->id }}" {{ old('khach_hang_id', $ticket->khach_hang_id) == $kh->id ? 'selected' : '' }}>
                                            {{ $kh->ten }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('khach_hang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-lg-6">
                                <label for="nguoi_xu_ly_id" class="form-label">Người xử lý</label>
                                <select name="nguoi_xu_ly_id" class="form-select select_ted @error('nguoi_xu_ly_id') is-invalid @enderror">
                                    <option value="">-- Chưa phân công --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('nguoi_xu_ly_id', $ticket->nguoi_xu_ly_id) == $user->id ? 'selected' : '' }}>
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
                                <input type="text" name="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror"
                                       value="{{ old('tieu_de', $ticket->tieu_de) }}" required>
                                @error('tieu_de')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-lg-6">
                                <label for="uu_tien" class="form-label">Ưu tiên</label>
                                <select name="uu_tien" class="form-select @error('uu_tien') is-invalid @enderror">
                                    <option value="">-- Không xác định --</option>
                                    @foreach(['thấp', 'trung bình', 'cao', 'khẩn cấp'] as $level)
                                        <option value="{{ $level }}" {{ old('uu_tien', $ticket->uu_tien) == $level ? 'selected' : '' }}>
                                            {{ ucfirst($level) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('uu_tien')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-lg-6">
                                <label for="trang_thai" class="form-label">Trạng thái</label>
                                <select name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror">
                                    @foreach(['đang chờ', 'đang xử lý', 'đã xử lý', 'đã đóng'] as $status)
                                        <option value="{{ $status }}" {{ old('trang_thai', $ticket->trang_thai) == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('trang_thai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-lg-12">
                                <label for="noi_dung" class="form-label">Nội dung</label>
                                <textarea name="noi_dung" id="tyni" rows="5"
                                          class="form-control @error('noi_dung') is-invalid @enderror"
                                          required>{{ old('noi_dung', $ticket->noi_dung) }}</textarea>
                                @error('noi_dung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                <a href="{{ route('ho_tro_khach_hangs.index') }}" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
