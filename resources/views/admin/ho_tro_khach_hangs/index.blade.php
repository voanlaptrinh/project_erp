@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý hỗ trợ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Hỗ trợ</li>
            </ol>
        </nav>
    </div>


    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Quản lý hỗ trợ khách hàng</h5>
                            @if (auth()->user()->hasPermissionTo('tạo hỗ trợ khách hàng'))
                                <a href="{{ route('ho_tro_khach_hangs.create') }}" class="btn btn-success">

                                    <i class="bi bi-check-circle"></i>

                                    Tạo mới hỗ trợ khách hàng</a>
                            @endif

                        </div>

                        <div class="table-responsive">
                            <!-- Table with stripped rows -->
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th>
                                            Tên khách hàng
                                        </th>
                                        <th>
                                            Tiêu đề
                                        </th>
                                        <th>
                                            Trạng thái
                                        </th>
                                        <th>
                                            Ưu tiên
                                        </th>
                                        <th>
                                            Người xử lý
                                        </th>
                                        <th>
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tickets as $tk)
                                        <tr>
                                            <td>
                                                {{ $tk->khachHang->ten }}
                                            </td>
                                            <td>{{ $tk->tieu_de }}</td>
                                            <td>
                                                @if ($tk->trang_thai == 'mới')
                                                    <span class="badge bg-primary">Mới</span>
                                                @elseif($tk->trang_thai == 'đang xử lý')
                                                    <span class="badge bg-warning">Đang xử lý</span>
                                                @elseif($tk->trang_thai == 'đã xử lý')
                                                    <span class="badge bg-success">Đã xử lý</span>
                                                @else
                                                    <span class="badge bg-danger">Đã đóng</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($tk->uu_tien == 'thấp')
                                                    <span class="badge bg-primary">Thấp</span>
                                                @elseif($tk->uu_tien == 'trung bình')
                                                    <span class="badge bg-warning">Trung bình</span>
                                                @elseif($tk->uu_tien == 'cao')
                                                    <span class="badge bg-success">Cao</span>
                                                @else
                                                    <span class="badge bg-danger">Khẩn cấp</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($tk->nguoiXuLy)
                                                    <span
                                                        class="badge border-success border-1 text-success">{{ $tk->nguoiXuLy->name }}</span>
                                                @else
                                                    <span class="badge border-secondary border-1 text-secondary">Chưa có
                                                        người xử lý</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (auth()->user()->hasPermissionTo('sửa hỗ trợ khách hàng'))
                                                <a href="{{ route('ho_tro_khach_hangs.edit', $tk->id) }}"
                                                    class="btn btn-sm btn-warning">Sửa</a>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $tk->id }}">
                                                    Xem
                                                </button>
                                                @if (auth()->user()->hasPermissionTo('xóa hỗ trợ khách hàng'))
                                                <form action="{{ route('ho_tro_khach_hangs.destroy', $tk->id) }}"
                                                    method="POST" style="display:inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Xóa hợp đồng hỗ trợ khách hàng này?')">Xoá</button>
                                                </form>
                                                    
                                                @endif
                                               
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="viewModal{{ $tk->id }}" tabindex="-1"
                                            aria-labelledby="viewModalLabel{{ $tk->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewModalLabel{{ $tk->id }}">Nội
                                                            dung yêu cầu: {{ $tk->tieu_de }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Đóng"></button>
                                                    </div>
                                                    <div class="modal-body content-chitiet">
                                                        {!! $tk->noi_dung !!}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Đóng</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
