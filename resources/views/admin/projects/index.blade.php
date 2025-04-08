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
                            <h5 class="card-title">Quản lý dự án </h5>
                            @if (auth()->user()->hasPermissionTo('tạo dự án'))
                                <a href="{{ route('admin.projects.create') }}" class="btn btn-success">

                                    <i class="bi bi-check-circle"></i>

                                    Tạo mới dự án</a>
                            @endif
                        </div>
                        <form method="GET" action="{{ route('admin.projects.index') }}">
                            <div class="row g-3">
                                <div class="col-lg-12 ">
                                    <div class="row g-2">
                                        <div class="col-lg-10">
                                            <label for="email" class="form-label">Tên dự án:</label>
                                            <input type="text" name="search" placeholder="Tên dự án"
                                                class="form-control " value="{{ request('search') }}">
                                        </div>

                                        <div class="col-lg-2">
                                            <label for="license_key" class="form-label"></label>
                                            <button type="submit" class="me-2 ms-2 btn btn-success w-100 mt-2"><i
                                                    class="bi bi-search"></i></button>

                                        </div>

                                    </div>
                                </div>



                            </div>

                        </form>
                        <hr>
                        <div class="table-responsive">
                            <!-- Table with stripped rows -->
                            <table class="table ">
                                <thead>
                                    <tr>

                                        <th>
                                            Tên dự án
                                        </th>
                                        <th>Người phụ trách</th>
                                        <th>Trạng Thái</th>
                                        <th>Ngày Bắt Đầu</th>
                                        <th>Ngày Kết Thúc</th>
                                        <th colspan="4">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($projects as $key => $project)
                                        <tr>
                                            <td>
                                                {{ $project->ten_du_an }}
                                            </td>
                                            <td>

                                                @forelse ($project->users as $item)
                                                    <span class="badge rounded-pill bg-success">{{ $item->name }}</span>
                                                @empty
                                                    <span>Chưa có người được phân công</span>
                                                @endforelse

                                            </td>

                                            <td>
                                                @if ($project->trang_thai == 'Chưa bắt đầu')
                                                    <span class="badge rounded-pill bg-secondary">Chưa bắt đầu</span>
                                                @elseif ($project->trang_thai == 'Đang thực hiện')
                                                    <span class="badge rounded-pill bg-primary">Đang thực hiện</span>
                                                @elseif ($project->trang_thai == 'Tạm dừng')
                                                    <span class="badge rounded-pill bg-warning">Tạm dừng</span>
                                                @else
                                                    <span class="badge rounded-pill bg-success">Đã hoàn thành</span>
                                                @endif


                                            </td>
                                            <td>
                                                {{ $project->ngay_bat_dau }}
                                            </td>

                                            <td>
                                                {{ $project->ngay_ket_thuc }}
                                            </td>
                                            <td colspan="4">
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
                                                    <form action="{{ route('admin.projects.destroy', $project->alias) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa dự án này?')">Xóa</button>
                                                    </form>
                                                @endif

                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <div class="alert alert-danger">
                                                    Không có dự án nào
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                            <div class=" p-nav text-end d-flex justify-content-center">
                                {{ $projects->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    {{-- <div class="container">
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
                @foreach ($projects as $project)
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
                                <form action="{{ route('admin.projects.destroy', $project->alias) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa dự án này?')">Xóa</button>
                                </form>
                            @endcan

                            <a href="{{ route('admin.projects.show', $project->alias) }}" class="btn btn-info">Xem</a>
                            <a href="{{ route('admin.projects.tasks', $project->alias) }}" class="btn btn-info">Xem
                                Task</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div> --}}
@endsection
