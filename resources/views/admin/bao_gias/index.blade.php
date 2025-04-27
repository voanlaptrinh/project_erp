@extends('welcome')
@section('body')
    <div class="container">
        <h1 class="mb-4">Danh sách Báo Giá</h1>

        @if ($hopDong)
            <h4>Hợp Đồng: {{ $hopDong->name }} (ID: {{ $hopDong->id }})</h4>
        @else
            <h4>Tất cả báo giá</h4>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table for displaying the quotations -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Số Báo Giá</th>
                    <th>Ngày Gửi</th>
                    <th>Tổng Giá Trị</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($baoGias as $baoGia)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $baoGia->so_bao_gia }}</td>
                        <td>{{ $baoGia->ngay_gui ? \Carbon\Carbon::parse($baoGia->ngay_gui)->format('d/m/Y') : 'Chưa có' }}</td>
                        <td>{{ number_format($baoGia->tong_gia_tri) }} VND</td>
                        <td>{{ $baoGia->trang_thai ?? 'Chưa xác định' }}</td>
                        <td>
                            <a href="{{ route('bao-gias.edit', $baoGia->id) }}" class="btn btn-warning btn-sm">Chỉnh sửa</a>
                            <form action="{{ route('bao-gias.destroy', $baoGia->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa báo giá này?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có báo giá nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ route('bao-gias.create') }}" class="btn btn-primary">Tạo Báo Giá</a>
            </div>
            <div>
            </div>
        </div>
    </div>
@endsection
