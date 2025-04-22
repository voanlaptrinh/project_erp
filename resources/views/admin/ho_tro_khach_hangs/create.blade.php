@extends('welcome')
@section('body')

<div class="container">
    <h4><i class="bi bi-headset me-2"></i>Thêm mới yêu cầu hỗ trợ</h4>

    <form action="{{ route('ho_tro_khach_hangs.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="khach_hang_id" class="form-label">Khách hàng</label>
            <select name="khach_hang_id" class="form-select" >
                <option value="">-- Chọn select --</option>
                @foreach ($khachHangs as $kh)
                    <option value="{{ $kh->id }}">{{ $kh->ten }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="nguoi_xu_ly_id" class="form-label">User xử lý</label>
            <select name="nguoi_xu_ly_id" class="form-select" >
                <option value="">-- Chọn select --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tieu_de" class="form-label">Tiêu đề</label>
            <input type="text" name="tieu_de" class="form-control" >
        </div>

        <div class="mb-3">
            <label for="noi_dung" class="form-label">Nội dung</label>
            <textarea name="noi_dung" class="form-control" rows="4" ></textarea>
        </div>

        <div class="mb-3">
            <label for="uu_tien" class="form-label">Mức độ ưu tiên</label>
            <select name="uu_tien" class="form-select">
                <option value="thấp">Thấp</option>
                <option value="trung bình" selected>Trung bình</option>
                <option value="cao">Cao</option>
                <option value="khẩn cấp">Khẩn cấp</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="trang_thai" class="form-label">Trạng thái</label>
            <select name="trang_thai" class="form-select">
                <option value="mới" selected>Mới</option>
                <option value="đang xử lý">Đang xử lý</option>
                <option value="đã xử lý">Đã xử lý</option>
                <option value="đã đóng">Đã đóng</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Lưu yêu cầu</button>
        <a href="{{ route('ho_tro_khach_hangs.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>



@endsection