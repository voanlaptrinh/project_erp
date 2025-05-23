@extends('welcome')

@section('body')

    <div class="pagetitle">
        <h1>Quản lý thiết bị làm việc</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Thiết bị</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Quản lý thiết bị </h5>
                            @if (auth()->user()->hasPermissionTo('thêm thiết bị'))
                                <a href="{{ route('thietbi.create') }}" class="btn btn-success">

                                    <i class="bi bi-check-circle"></i>

                                    Thêm mới thiết bị</a>
                            @endif
                        </div>
                        {{--  phần hiển thị thông báo tìm kiếm phía trên bảng --}}
                        @if (request()->hasAny(['search', 'start_date', 'end_date']))
                            <div class="alert alert-info">
                                Đang hiển thị kết quả tìm kiếm:
                                @if (request('search'))
                                    - Từ khóa: "{{ request('search') }}"
                                @endif
                                @if (request('start_date'))
                                    - Từ ngày: {{ request('start_date') }}
                                @endif
                                @if (request('end_date'))
                                    - Đến ngày: {{ request('end_date') }}
                                @endif
                                @if (request('loai_thiet_bi'))
                                    - Loại: {{ request('loai_thiet_bi') }}
                                @endif
                                <a href="{{ route('thietbi.index') }}" class="float-end">Xóa bộ lọc</a>
                            </div>
                        @endif

                        <form method="GET" action="{{ route('thietbi.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Tìm kiếm:</label>
                                    <input type="text" name="search" placeholder="Tên hoặc loại thiết bị"
                                        class="form-control" value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Loại thiết bị:</label>
                                    <select name="loai_thiet_bi" class="form-select">
                                        <option value="">Tất cả loại</option>
                                        @foreach ($loaiThietBis as $loai)
                                            <option value="{{ $loai }}"
                                                {{ request('loai_thiet_bi') == $loai ? 'selected' : '' }}>
                                                {{ $loai }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Từ ngày:</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Đến ngày:</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-search"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('thietbi.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </a>
                                </div>

                            </div>
                        </form>
                        <hr>
                        <div class="table-responsive">
                            <!-- Table with stripped rows -->
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th>Loại thiết bị</th>
                                        <th>Tên</th>
                                        <th>Người dùng</th>
                                        <th>Ngày bàn giao</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($devices as $device)
                                        <td>{{ $device->loai_thiet_bi }}</td>
                                        <td>{{ $device->ten_thiet_bi }}</td>
                                        <td>{{ $device->user->name ?? 'Không rõ' }}</td>
                                        <td>
                                            {{ $device->ngay_ban_giao ? \Carbon\Carbon::parse($device->ngay_ban_giao)->format('d/m/Y') : '' }}
                                        </td>

                                        <td colspan="2">
                                            @if (auth()->user()->hasPermissionTo('sửa thiết bị') ||
                                                    auth()->user()->hasPermissionTo('xóa thiết bị') ||
                                                    auth()->user()->hasPermissionTo('xem thiết bị'))
                                                @if (auth()->user()->hasPermissionTo('xem thiết bị'))
                                                    <a href="{{ route('thietbi.show', $device) }}"
                                                        class="btn btn-sm btn-info">Xem</a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('sửa thiết bị'))
                                                    <a href="{{ route('thietbi.edit', $device) }}"
                                                        class="btn btn-sm btn-warning">Sửa</a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('xóa thiết bị'))
                                                    <form action="{{ route('thietbi.destroy', $device) }}" method="POST"
                                                        style="display:inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Xóa hợp thiết bị này?')">Xoá</button>
                                                    </form>
                                                @endif
                                            @endif
                                            </tr>
                                            <tr>

                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="alert alert-danger">
                                                        Không có thiết bị nào
                                                    </div>
                                                </td>
                                            </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                            <div class="d-flex justify-content-center mt-3">
                                {{ $devices->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
