@extends('welcome')
@section('body')
<div class="container">
    <h2>Danh Sách Dự Án</h2>

    @can('tạo dự án')
        <a href="{{ route('admin.projects.create') }}" class="btn btn-success mb-3">Thêm Dự Án</a>
    @endcan

    <table class="table">
        <thead>
            <tr>
                <th>Tên Dự Án</th>
                <th>Trạng Thái</th>
                <th>Ngày Bắt Đầu</th>
                <th>Ngày Kết Thúc</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
                <tr>
                    <td>{{ $project->ten_du_an }}</td>
                    <td>{{ $project->trang_thai }}</td>
                    <td>{{ $project->ngay_bat_dau }}</td>
                    <td>{{ $project->ngay_ket_thuc }}</td>
                    <td>
                        @can('sửa dự án')
                            <a href="{{ route('admin.projects.edit', $project->alias) }}" class="btn btn-primary">Sửa</a>
                        @endcan
                
                        @can('xóa dự án')
                            <form action="{{ route('admin.projects.destroy', $project->alias) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa dự án này?')">Xóa</button>
                            </form>
                        @endcan
                
                        <a href="{{ route('admin.projects.show', $project->alias) }}" class="btn btn-info">Xem</a>
                        <a href="{{ route('admin.projects.tasks', $project->alias) }}" class="btn btn-info">Xem Task</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection