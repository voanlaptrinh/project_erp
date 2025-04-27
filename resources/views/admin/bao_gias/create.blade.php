@extends('welcome')
@section('body')
    <div class="container">
        <h1 class="mb-4">Tạo Báo Giá</h1>

        <form action="{{ route('bao-gias.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="hop_dong_id">Hợp Đồng</label>
                <select name="hop_dong_id" id="hop_dong_id" class="form-control" required>
                    <option value="">Chọn hợp đồng</option>
                    @foreach ($hopDong as $contract)
                        <option value="{{ $contract->id }}">{{ $contract->project->ten_du_an }} (ID: {{ $contract->id }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="so_bao_gia">Số Báo Giá</label>
                <input type="text" name="so_bao_gia" id="so_bao_gia" class="form-control" value="{{ old('so_bao_gia') }}" required>
            </div>

            <div class="form-group">
                <label for="ngay_gui">Ngày Gửi</label>
                <input type="date" name="ngay_gui" id="ngay_gui" class="form-control" value="{{ old('ngay_gui') }}">
            </div>

            <div class="form-group">
                <label for="tong_gia_tri">Tổng Giá Trị</label>
                <input type="number" name="tong_gia_tri" id="tong_gia_tri" class="form-control" value="{{ old('tong_gia_tri') }}">
            </div>

            <div class="form-group">
                <label for="chi_tiet">Chi Tiết</label>
                <textarea name="chi_tiet" id="chi_tiet" class="form-control">{{ old('chi_tiet') }}</textarea>
            </div>

            <div class="form-group">
                <label for="trang_thai">Trạng Thái</label>
                <input type="text" name="trang_thai" id="trang_thai" class="form-control" value="{{ old('trang_thai') }}">
            </div>

            <button type="submit" class="btn btn-success">Tạo Báo Giá</button>
            <a href="{{ route('bao-gias.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
