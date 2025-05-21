@extends('welcome')

@section('body')
    <div class=" mt-4">

        <div class="pagetitle mb-4">
            <h1 class="display-6">📱 Chi tiết thiết bị</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('thietbi.index') }}">Thiết bị</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </nav>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-4">Thông tin thiết bị</h5>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>🔧 Loại:</strong> {{ $device->loai_thiet_bi }}</li>
                    <li class="list-group-item"><strong>💻 Tên thiết bị:</strong> {{ $device->ten_thiet_bi }}</li>
                    <li class="list-group-item"><strong>👤 Người dùng:</strong> {{ $device->user->name ?? 'Không rõ' }}</li>
                    <li class="list-group-item"><strong>🖥 Hệ điều hành:</strong> {{ $device->he_dieu_hanh }}</li>
                    <li class="list-group-item"><strong>⚙️ Cấu hình:</strong> {{ $device->cau_hinh }}</li>
                    <li class="list-group-item"><strong>🔢 Số serial:</strong> {{ $device->so_serial }}</li>
                    <li class="list-group-item"><strong>📅 Ngày bàn giao:</strong>
                        {{ \Carbon\Carbon::parse($device->ngay_ban_giao)->format('d/m/Y') }}</li>
                    <li class="list-group-item"><strong>📝 Ghi chú:</strong>
                        <div class="mt-2">{!! $device->ghi_chu !!}</div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('thietbi.index') }}" class="btn btn-outline-secondary">
                ⬅ Quay lại danh sách
            </a>
        </div>
    </div>
@endsection
