@extends('welcome')
@section('body')
<div class="container">
    <h2>Thêm mới hợp đồng</h2>

    <form action="{{ route('hop_dong_khach_hang.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="project_id" class="form-label">Dự án</label>
            <select name="project_id"  id="user_id" class="form-control" required onchange="generateAlias()">
                <option value="">-- Chọn select --</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->ten_du_an }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="so_hop_dong" class="form-label">Số hợp đồng</label>
            <input type="text" name="so_hop_dong" class="form-control">
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">Tệp hợp đồng</label>
            <input type="file" name="file" class="form-control">
        </div>

        <div class="mb-3">
            <label for="ngay_ky" class="form-label">Ngày ký</label>
            <input type="date" name="ngay_ky" class="form-control">
        </div>

        <div class="mb-3">
            <label for="ngay_het_han" class="form-label">Ngày hết hạn</label>
            <input type="date" name="ngay_het_han" class="form-control">
        </div>

        <div class="mb-3">
            <label for="gia_tri" class="form-label">Giá trị</label>
            <input type="number" name="gia_tri" class="form-control">
        </div>

        <div class="mb-3">
            <label for="noi_dung" class="form-label">Nội dung</label>
            <textarea name="noi_dung" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label for="trang_thai" class="form-label">Trạng thái</label>
            <select name="trang_thai" class="form-control">
                <option value="đang hiệu lực">Đang hiệu lực</option>
                <option value="hết hiệu lực">Hết hiệu lực</option>
                <option value="hủy">Hủy</option>
            </select>
        </div>
  
        <button type="submit" class="btn btn-primary">Lưu hợp đồng</button>
    </form>
</div>


@endsection
