@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý dự án</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Dự án hiện tại</li>
            </ol>
        </nav>
    </div>


    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Quản lý Hợp đồng dự án </h5>
                            @if (auth()->user()->hasPermissionTo('tạo hợp đồng dự án'))
                                <a href="{{ route('hop_dong_khach_hang.create') }}" class="btn btn-success">

                                    <i class="bi bi-check-circle"></i>

                                    Tạo mới hợp đồng dự án</a>
                            @endif
                        </div>
                        <form method="GET" action="{{ route('hop_dong_khach_hang.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="license_key" class="form-label">Dự án</label>
                                    <select name="project_id" class="form-control" id="user_id">
                                        <option value="">-- Chọn select --</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->ten_du_an }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="license_key" class="form-label"></label>
                                    <button type="submit" class="me-2 ms-2 btn btn-success w-100 mt-2"><i
                                            class="bi bi-search"></i></button>

                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="table-responsive">
                            <!-- Table with stripped rows -->
                            <table class="table ">
                                <thead>
                                    <tr>

                                        <th>Dự án</th>
                                        <th>Số HĐ</th>
                                        <th>Ngày ký</th>
                                        <th>Giá trị</th>
                                        <th>Trạng thái</th>
                                        <th colspan="4">Thao tác</th>
                               
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($hopDongs as $hd)
                                        <tr>
                                            <td>{{ $hd->project->ten_du_an ?? '---' }}</td>
                                            <td>{{ $hd->so_hop_dong }}</td>
                                            <td>{{ $hd->ngay_ky }}</td>
                                            <td>{{ number_format($hd->gia_tri, 0, ',', '.') }}đ</td>
                                            <td>{{ $hd->trang_thai }}</td>


                                            <td colspan="4">
                                                @if (auth()->user()->hasPermissionTo('sửa hợp đồng dự án'))
                                                    <a href="{{ route('admin.projects.edit', $project->alias) }}"
                                                        class="btn btn-warning">Sửa</a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('xóa hợp đồng dự án'))
                                                    <form
                                                        action="{{ route('admin.projects.destroy', $project->alias) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa hợp đồng dự án này?')">Xóa</button>
                                                    </form>
                                                @endif
                                                {{-- @if (auth()->user()->hasPermissionTo('xem task') || auth()->user()->hasPermissionTo('xem dự án') || auth()->user()->hasPermissionTo('sửa dự án') || auth()->user()->hasPermissionTo('xóa dự án'))
                                                @if (auth()->user()->hasPermissionTo('xem task'))
                                                    <a href="{{ route('admin.projects.tasks', $project->alias) }}"
                                                        class="btn btn-info">Xem
                                                        Task</a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('xem dự án'))
                                                    <a href="{{ route('admin.projects.show', $project->alias) }}"
                                                        class="btn btn-info">Xem</a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('sửa dự án'))
                                                    <a href="{{ route('admin.projects.edit', $project->alias) }}"
                                                        class="btn btn-warning">Sửa</a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('xóa dự án'))
                                                    <form
                                                        action="{{ route('admin.projects.destroy', $project->alias) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa dự án này?')">Xóa</button>
                                                    </form>
                                                @endif
                                            @endif --}}


                                            </td>

                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="alert alert-danger">
                                                        Không có hợp đồng dự án nào
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <!-- End Table with stripped rows -->
                                <div class=" p-nav text-end d-flex justify-content-center">
                                    {{ $hopDongs->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- <h2>Danh sách hợp đồng</h2>
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
</table> --}}
    @endsection
