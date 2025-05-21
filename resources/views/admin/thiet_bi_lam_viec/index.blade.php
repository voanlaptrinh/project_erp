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

                        <form method="GET" action="{{ route('thietbi.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="email" class="form-label">Tên thiết bị:</label>
                                    <input type="text" name="search" placeholder="Tên thiết bị" class="form-control "
                                        value="{{ request('search') }}">
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
