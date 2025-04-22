@extends('welcome')
@section('body')
<div class="container mt-4">
    <h3>Chỉnh sửa yêu cầu hỗ trợ</h3>

    <form action="{{ route('ho_tro_khach_hangs.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="khach_hang_id" class="form-label">Khách hàng</label>
            <select name="khach_hang_id" id="khach_hang_id" class="form-select" required>
                @foreach($khachHangs as $kh)
                    <option value="{{ $kh->id }}" {{ $ticket->khach_hang_id == $kh->id ? 'selected' : '' }}>
                        {{ $kh->ten }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tieu_de" class="form-label">Tiêu đề</label>
            <input type="text" name="tieu_de" id="tieu_de" class="form-control" value="{{ old('tieu_de', $ticket->tieu_de) }}" required>
        </div>

        <div class="mb-3">
            <label for="noi_dung" class="form-label">Nội dung</label>
            <textarea name="noi_dung" id="noi_dung" rows="5" class="form-control" required>{{ old('noi_dung', $ticket->noi_dung) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="uu_tien" class="form-label">Ưu tiên</label>
            <select name="uu_tien" id="uu_tien" class="form-select">
                <option value="">-- Không xác định --</option>
                <option value="thấp" {{ $ticket->uu_tien == 'thấp' ? 'selected' : '' }}>Thấp</option>
                <option value="trung bình" {{ $ticket->uu_tien == 'trung bình' ? 'selected' : '' }}>Trung bình</option>
                <option value="cao" {{ $ticket->uu_tien == 'cao' ? 'selected' : '' }}>Cao</option>
                <option value="khẩn cấp" {{ $ticket->uu_tien == 'khẩn cấp' ? 'selected' : '' }}>Khẩn cấp</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="trang_thai" class="form-label">Trạng thái</label>
            <select name="trang_thai" id="trang_thai" class="form-select">
                <option value="đang chờ" {{ $ticket->trang_thai == 'đang chờ' ? 'selected' : '' }}>Đang chờ</option>
                <option value="đang xử lý" {{ $ticket->trang_thai == 'đang xử lý' ? 'selected' : '' }}>Đang xử lý</option>
                <option value="đã xử lý" {{ $ticket->trang_thai == 'đã xử lý' ? 'selected' : '' }}>Đã xử lý</option>
                <option value="đã đóng" {{ $ticket->trang_thai == 'đã đóng' ? 'selected' : '' }}>Đã đóng</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="nguoi_xu_ly_id" class="form-label">Người xử lý</label>
            <select name="nguoi_xu_ly_id" id="nguoi_xu_ly_id" class="form-select">
                <option value="">-- Chưa phân công --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $ticket->nguoi_xu_ly_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('ho_tro_khach_hangs.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
