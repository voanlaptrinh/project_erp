@extends('welcome')
@section('body')
<div class="container">
    <h2>Sửa hợp đồng</h2>

    <form action="{{ route('hop_dong_khach_hang.update', $hopDong->alias) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="project_id" class="form-label">Dự án</label>
            <select name="project_id" class="form-control">
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" {{ $hopDong->project_id == $project->id ? 'selected' : '' }}>{{ $project->ten_du_an }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="so_hop_dong" class="form-label">Số hợp đồng</label>
            <input type="text" name="so_hop_dong" class="form-control" value="{{ $hopDong->so_hop_dong }}">
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">Tệp hợp đồng</label>
            <input type="file" name="file" class="form-control">
            @if ($hopDong->file)
                <a href="{{ asset( $hopDong->file) }}" target="_blank">Xem tệp hiện tại</a>
            @endif
        </div>

        <div class="mb-3">
            <label for="ngay_ky" class="form-label">Ngày ký</label>
            <input type="date" name="ngay_ky" class="form-control" value="{{ $hopDong->ngay_ky }}">
        </div>

        <div class="mb-3">
            <label for="ngay_het_han" class="form-label">Ngày hết hạn</label>
            <input type="date" name="ngay_het_han" class="form-control" value="{{ $hopDong->ngay_het_han }}">
        </div>

        <div class="mb-3">
            <label for="gia_tri" class="form-label">Giá trị</label>
            <input type="number" name="gia_tri" class="form-control" value="{{ $hopDong->gia_tri }}">
        </div>

        <div class="mb-3">
            <label for="noi_dung" class="form-label">Nội dung</label>
            <textarea name="noi_dung" class="form-control" rows="4">{{ $hopDong->noi_dung }}</textarea>
        </div>

        <div class="mb-3">
            <label for="trang_thai" class="form-label">Trạng thái</label>
            <select name="trang_thai" class="form-control">
                <option value="đang hiệu lực" {{ $hopDong->trang_thai == 'đang hiệu lực' ? 'selected' : '' }}>Đang hiệu lực</option>
                <option value="hết hiệu lực" {{ $hopDong->trang_thai == 'hết hiệu lực' ? 'selected' : '' }}>Hết hiệu lực</option>
                <option value="hủy" {{ $hopDong->trang_thai == 'hủy' ? 'selected' : '' }}>Hủy</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
