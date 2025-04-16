@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý khách hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Khách hàng</li>
            </ol>
        </nav>
    </div>


    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Quản lý khách hàng</h5>
                            @if (auth()->user()->hasPermissionTo('tạo khách hàng'))
                                <a href="{{ route('khach-hangs.create') }}" class="btn btn-success">

                                    <i class="bi bi-check-circle"></i>

                                    Tạo mới khách hàng</a>
                            @endif
                        </div>
                        <form method="GET" action="{{ route('khach-hangs.index') }}" class="row mb-3">
                            <div class="col-md-5">
                                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
                                    placeholder="Tìm theo tên khách hàng...">
                            </div>

                            <div class="col-md-5">
                                <select name="project_id" class="form-select" id="user_id">
                                    <option value="">-- Tất cả dự án --</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->ten_du_an }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                <a href="{{ route('khach-hangs.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>

                        <hr>
                        <div class="table-responsive">
                            <!-- Table with stripped rows -->
                            <table class="table ">
                                <thead>
                                    <tr>

                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>SĐT</th>
                                        <th>Dự án</th>
                                        @if (auth()->user()->hasPermissionTo('xem task') ||
                                                auth()->user()->hasPermissionTo('xem dự án') ||
                                                auth()->user()->hasPermissionTo('sửa dự án') ||
                                                auth()->user()->hasPermissionTo('xóa dự án'))
                                            <th colspan="4">Thao tác</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($khachHangs as $kh)
                                        <tr>
                                            <td>{{ $kh->ten }}</td>
                                            <td>{{ $kh->email }}</td>
                                            <td>{{ $kh->so_dien_thoai }}</td>
                                            <td>{{ $kh->project->ten_du_an ?? '---' }}</td>
                                            <td>
                                                <a href="{{ route('khach-hangs.show', $kh->alias) }}"
                                                    class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Xem</a>
                                                @if (auth()->user()->hasPermissionTo('sửa khách hàng'))
                                                    <a href="{{ route('khach-hangs.edit', $kh->alias) }}"
                                                        class="btn btn-sm btn-warning"><i class="bi bi-tools"></i> Sửa</a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('xóa khách hàng'))
                                                    <form action="{{ route('khach-hangs.destroy', $kh->alias) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf @method('DELETE')
                                                        <button onclick="return confirm('Xóa khách hàng này?')"
                                                            class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xóa</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <div class="alert alert-danger">
                                                    Không có khách hàng nào
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                            <div class=" p-nav text-end d-flex justify-content-center">
                                {{ $khachHangs->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <div class="container">
    <h2>Danh sách khách hàng</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('khach-hangs.create') }}" class="btn btn-primary mb-3">+ Thêm khách hàng</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Dự án</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($khachHangs as $kh)
                <tr>
                    <td>{{ $kh->ten }}</td>
                    <td>{{ $kh->email }}</td>
                    <td>{{ $kh->so_dien_thoai }}</td>
                    <td>{{ $kh->project->ten_du_an ?? '---' }}</td>
                    <td>
                        <a href="{{ route('khach-hangs.edit', $kh->alias) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('khach-hangs.destroy', $kh->alias) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Xóa khách hàng này?')" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $khachHangs->links() }}
</div> --}}
@endsection
