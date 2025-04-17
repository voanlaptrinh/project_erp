@extends('welcome')
@section('body')
<h2>Danh sách hợp đồng</h2>
<a href="{{ route('hop_dong_khach_hang.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Dự án</th>
            <th>Số HĐ</th>
            <th>Ngày ký</th>
            <th>Giá trị</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($hopDongs as $hd)
        <tr>
            <td>{{ $hd->project->ten_du_an ?? '---' }}</td>
            <td>{{ $hd->so_hop_dong }}</td>
            <td>{{ $hd->ngay_ky }}</td>
            <td>{{ number_format($hd->gia_tri, 0, ',', '.') }}đ</td>
            <td>{{ $hd->trang_thai }}</td>
            <td>
                <a href="{{ route('hop_dong_khach_hang.edit', $hd->alias) }}" class="btn btn-sm btn-warning">Sửa</a>
                <form action="{{ route('hop_dong_khach_hang.destroy', $hd->alias) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
