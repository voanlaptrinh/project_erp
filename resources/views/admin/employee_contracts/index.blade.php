@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý hợp đồng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Hợp đồng</li>
            </ol>
        </nav>
    </div>


    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Quản lý hợp đồng</h5>
                            @if (auth()->user()->hasPermissionTo('tạo hợp đồng'))
                                <a href="{{ route('admin.employee-contracts.create') }}" class="btn btn-success">

                                    <i class="bi bi-check-circle"></i>

                                    Tạo mới hợp đồng</a>
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
                                            Loại hợp đồng
                                        </th>
                                        <th>Người</th>

                                        <th>Ngày Bắt Đầu</th>
                                        <th>Ngày Kết Thúc</th>
                                        <th>Lương thỏa thuận</th>
                                        <th>Chi tiết hợp đồng</th>
                                        @if (auth()->user()->hasPermissionTo('sửa hợp đồng') || auth()->user()->hasPermissionTo('xóa hợp đồng'))
                                            <th colspan="2">Thao tác</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($contracts as $contract)
                                        <tr>
                                            <td>{{ $contract->loai_hop_dong }}</td>
                                            <td>{{ $contract->user->name }}</td>
                                            <td>{{ $contract->ngay_bat_dau }}</td>
                                            <td>{{ $contract->ngay_ket_thuc ?? 'Không xác định' }}</td>
                                            <td>{{ number_format($contract->luong_thoa_thuan, 0, ',', '.') }} ₫ </td>
                                            <td>
                                                @if ($contract->file_hop_dong)
                                                <a href="{{ route('admin.employee-contracts.view', $contract) }}" class="btn btn-sm btn-primary" target="_blank">
                                                    Xem hợp đồng
                                                </a>
                                                
                                                @else
                                                    <span class="text-muted">Không có</span>
                                                @endif
                                            </td>
                                            
                                            <td colspan="2">
                                                @if (auth()->user()->hasPermissionTo('sửa hợp đồng') || auth()->user()->hasPermissionTo('xóa hợp đồng'))
                                                    @if (auth()->user()->hasPermissionTo('sửa hợp đồng'))
                                                        <a href="{{ route('admin.employee-contracts.edit', $contract) }}"
                                                            class="btn btn-sm btn-warning">Sửa</a>
                                                    @endif
                                                    @if (auth()->user()->hasPermissionTo('xóa hợp đồng'))
                                                        <form
                                                            action="{{ route('admin.employee-contracts.destroy', $contract) }}"
                                                            method="POST" style="display:inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Xóa hợp đồng này?')">Xoá</button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </td>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <div class="alert alert-danger">
                                                        Không có hợp đồng
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                            <div class=" p-nav text-end d-flex justify-content-center">
                                {{ $contracts->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
@endsection
