@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý task của dự án</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Task trong dự án ({{ $project->ten_du_an }})</li>
            </ol>
        </nav>
    </div>


    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Quản lý task </h5>
                            @if (auth()->user()->hasPermissionTo('tạo task'))
                                <a href="{{ route('admin.tasks.create', $project->alias) }}" class="btn btn-success">

                                    <i class="bi bi-check-circle"></i>

                                    Tạo mới task trong dự án ({{ $project->ten_du_an }})</a>
                            @endif
                        </div>
                        <form method="GET"
                            action="{{ route('admin.projects.tasks', ['project' => $project->alias ?? null]) }}"
                            class="row g-2 mb-3 align-items-end">
                            <div class="col-md-4">
                                <label for="tieu_de" class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" name="tieu_de" id="tieu_de"
                                    placeholder="Tìm theo tiêu đề" value="{{ request('tieu_de') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="do_uu_tien" class="form-label">Độ ưu tiên</label>
                                <select class="form-select" name="do_uu_tien" id="do_uu_tien">
                                    <option value="">-- Chọn độ ưu tiên --</option>
                                    <option value="Thấp" {{ request('do_uu_tien') == 'Thấp' ? 'selected' : '' }}>Thấp
                                    </option>
                                    <option value="Trung bình"
                                        {{ request('do_uu_tien') == 'Trung bình' ? 'selected' : '' }}>Trung bình</option>
                                    <option value="Cao" {{ request('do_uu_tien') == 'Cao' ? 'selected' : '' }}>Cao
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="trang_thai" class="form-label">Trạng thái</label>
                                <select class="form-select" name="trang_thai" id="trang_thai">
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="Mới" {{ request('trang_thai') == 'Mới' ? 'selected' : '' }}>Mới
                                    </option>
                                    <option value="Đang thực hiện"
                                        {{ request('trang_thai') == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực hiện
                                    </option>
                                    <option value="Hoàn thành"
                                        {{ request('trang_thai') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                            </div>
                        </form>
                        <hr>
                        <div class="table-responsive">
                            <!-- Table with stripped rows -->
                            <table class="table ">
                                <thead>
                                    <tr>

                                        <th>Tiêu đề</th>
                                        <th>Dự án</th>
                                        <th>Người phụ trách</th>
                                        <th>Độ ưu tiên</th>
                                        <th>Trạng thái</th>
                                        <th>Hạn hoàn thành</th>
                                        <th>Trạng thái hạn</th>

                                        <th colspan="3">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tasks as $key => $task)
                                        <tr>
                                            <td>{{ $task->tieu_de }}</td>
                                            <td>{{ $task->project->ten_du_an }}</td>
                                            <td>{{ $task->user->name ?? 'Chưa giao' }}</td>
                                            <td>{{ $task->do_uu_tien ?? '-' }}</td>
                                            <td>{{ $task->trang_thai ?? '-' }}</td>
                                            <td>{{ $task->han_hoan_thanh ?? '-' }}</td>
                                            <td>
                                                @if($task->han_hoan_thanh && \Carbon\Carbon::parse($task->han_hoan_thanh)->lt(\Carbon\Carbon::now()))
                                                    <span class="badge bg-danger">Có</span>
                                                @else
                                                    <span class="badge bg-success">Không</span>
                                                @endif
                                            </td>
                                            

                                            <td colspan="3">
                                                @if (auth()->user()->hasPermissionTo('xem task') ||
                                                        auth()->user()->hasPermissionTo('sửa task') ||
                                                        auth()->user()->hasPermissionTo('xóa task'))
                                                    @if (auth()->user()->hasPermissionTo('xem task'))
                                                        <a href="{{ route('admin.projects.tasks.show', [$project->alias, $task->id]) }}"
                                                            class="btn  btn-info">
                                                            Xem chi tiết
                                                        </a>
                                                    @endif
                                                    @if (auth()->user()->hasPermissionTo('sửa task'))
                                                        <a href="{{ route('admin.projects.tasks.edit', [$project->alias, $task->id]) }}"
                                                            class="btn btn-warning">
                                                            Sửa
                                                        </a>
                                                    @endif
                                                    @if (auth()->user()->hasPermissionTo('xóa task'))
                                                        <form
                                                            action="{{ route('admin.projects.tasks.destroy', [$project->alias, $task->id]) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger"
                                                                onclick="return confirm('Bạn có chắc chắn muốn xóa task này?')">Xóa</button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <div class="alert alert-danger">
                                                    Không có task cho dự án này
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                            <div class=" p-nav text-end d-flex justify-content-center">

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

{{-- <h3>Danh sách Task</h3>
<a href="{{ route('admin.tasks.create', $project->alias) }}" class="btn btn-success">Thêm Task</a>

<table class="table">
    <thead>
        <tr>
            <th>Tiêu đề</th>
            <th>Dự án</th>
            <th>Người phụ trách</th>
            <th>Hạn hoàn thành</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
            <tr>
                <td>{{ $task->tieu_de }}</td>
                <td>{{ $task->project->ten_du_an }}</td>
                <td>{{ $task->user->name ?? 'Chưa giao' }}</td>
                <td>{{ $task->han_hoan_thanh ?? '-' }}</td>
                <td>
                    <!-- Nút Sửa -->
                    <a href="{{ route('admin.projects.tasks.edit', [$project->alias, $task->id]) }}"
                        class="inline-block px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
                        Sửa
                    </a>

                    <!-- Nút Xóa (nếu cần) -->
                    <form action="{{ route('admin.projects.tasks.destroy', [$project->alias, $task->id]) }}"
                        method="POST" class="inline-block"
                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa task này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table> --}}
