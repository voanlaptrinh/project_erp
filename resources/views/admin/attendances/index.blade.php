@extends('welcome')
@section('body')

    <div class="col-12 d-sm-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Quản lý Chấm công</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Tài khoản Chấm công</li>
                </ol>
            </nav>
        </div>
        @if (auth()->user()->hasPermissionTo('thống kê chấm công'))
            <form method="POST" action="{{ route('admin.chamcong.generateThongKe') }}"
                class="d-flex gap-2 align-items-center mb-3">
                @csrf
                <select name="thang" class="form-select w-auto">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ now()->month == $i ? 'selected' : '' }}>Tháng
                            {{ $i }}</option>
                    @endfor
                </select>

                <select name="nam" class="form-select w-auto">
                    @for ($y = now()->year - 2; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}
                        </option>
                    @endfor
                </select>

                <button type="submit" class="btn btn-danger">📊 Thống kê tháng</button>
            </form>
        @endif
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">

                            <div class="card-title">
                                🕒 Thời gian hiện tại: <span id="realtime-clock"
                                    style="color: rgb(255, 60, 0); font-weight: 900;">--:--:--</span>
                            </div>
                            @unless (auth()->user()->hasRole('Super Admin'))
                                <div class="card-title">
                                    <div>
                                        <form method="POST" action="{{ route('chamcong.vao') }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success">🕒 Chấm công giờ vào</button>
                                        </form>
                                        <form method="POST" action="{{ route('chamcong.ra') }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success">🕔 Chấm công giờ ra</button>
                                        </form>
                                    </div>
                                </div>
                            @endunless
                        </div>
                        <form method="GET" action="{{ route('admin.chamcong.index') }}"
                            class="mb-3 d-flex gap-2 align-items-center">
                            @can('xem toàn bộ chấm công')
                                <select name="user_id" id="user_id" class="form-select">
                                    <option value="">-- Chọn nhân viên --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endcan

                            <input type="date" name="ngay" class="form-control" value="{{ request('ngay') }}">
                            <button type="submit" class="btn btn-primary">Lọc</button>
                            <a href="{{ route('admin.chamcong.index') }}" class="btn btn-secondary">Reset</a>
                        </form>



                        <div class="table-responsive">
                            <!-- Table with stripped rows -->
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th>Nhân viên</th>
                                        <th>Ngày</th>
                                        <th>Giờ vào</th>
                                        <th>Giờ ra</th>
                                        <th>Đi muộn</th>
                                        <th>Về sớm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($chamCongs as $cc)
                                        <tr>
                                            <td>{{ $cc->nhanVien->name ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cc->ngay)->format('d/m/Y') }}</td>
                                            <td>
                                                {{ $cc->gio_vao ? \Carbon\Carbon::parse($cc->gio_vao)->format('H:i:s d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                {{ $cc->gio_ra ? \Carbon\Carbon::parse($cc->gio_ra)->format('H:i:s d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                @if ($cc->di_muon)
                                                    <span class="badge text-bg-danger">Có</span>
                                                @else
                                                    <span class="badge text-bg-success">Không</span>
                                                @endif
                                                {{-- {{ $cc->di_muon ? 'Có' : '' }} --}}
                                            </td>
                                            <td>
                                                @if ($cc->ve_som)
                                                    <span class="badge text-bg-danger">Có</span>
                                                @else
                                                    <span class="badge text-bg-success">Không</span>
                                                @endif
                                                {{-- {{ $cc->ve_som ? 'Có' : 'Không' }} --}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <div class="alert alert-danger">
                                                    Không có thông tin
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                            <div class=" p-nav text-end d-flex justify-content-center">
                                {{ $chamCongs->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <div class="container">
        <h2>Bảng chấm công</h2>


        @unless (auth()->user()->hasRole('Super Admin'))
            <div style="margin-bottom: 15px;">
                <form method="POST" action="{{ route('chamcong.vao') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-success">🕒 Chấm công giờ vào</button>
                </form>

                <form method="POST" action="{{ route('chamcong.ra') }}" style="display:inline; margin-left:10px;">
                    @csrf
                    <button type="submit" class="btn btn-outline-success">🕔 Chấm công giờ ra</button>
                </form>
            </div>
        @endunless

        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr>
                    @if (auth()->user()->hasRole('Super Admin'))
                        <th>Nhân viên</th>
                    @endif
                    <th>Ngày</th>
                    <th>Giờ vào</th>
                    <th>Giờ ra</th>
                    <th>Đi muộn</th>
                    <th>Về sớm</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chamCongs as $cc)
                    <tr>
                        @if (auth()->user()->hasRole('Super Admin'))
                            <td>{{ $cc->nhanVien->name ?? 'N/A' }}</td>
                        @endif
                        <td>{{ \Carbon\Carbon::parse($cc->ngay)->format('d/m/Y') }}</td>
                        <td>
                            {{ $cc->gio_vao ? \Carbon\Carbon::parse($cc->gio_vao)->format('H:i:s d/m/Y') : '-' }}
                        </td>
                        <td>
                            {{ $cc->gio_ra ? \Carbon\Carbon::parse($cc->gio_ra)->format('H:i:s d/m/Y') : '-' }}
                        </td>
                        <td style="color:{{ $cc->di_muon ? 'red' : 'green' }}">
                            {{ $cc->di_muon ? 'Có' : 'Không' }}
                        </td>
                        <td style="color:{{ $cc->ve_som ? 'red' : 'green' }}">
                            {{ $cc->ve_som ? 'Có' : 'Không' }}
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        <div class="mt-3">
            {{ $chamCongs->links() }}
        </div>
    </div> --}}
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;
            document.getElementById('realtime-clock').textContent = timeString;
        }

        setInterval(updateClock, 1000);
        updateClock(); // chạy ngay lần đầu
    </script>
@endsection
