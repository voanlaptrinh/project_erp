{{-- resources/views/admin/chamcong/thongke.blade.php --}}
<form method="GET" action="{{ route('admin.chamcong.thongke') }}" class="d-flex gap-2 align-items-center mb-3">
    @csrf
    <select name="thang" class="form-select w-auto">
        @for ($i = 1; $i <= 12; $i++)
            <option value="{{ $i }}" {{ $thang == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
        @endfor
    </select>

    <select name="nam" class="form-select w-auto">
        @for ($y = now()->year - 2; $y <= now()->year + 1; $y++)
            <option value="{{ $y }}" {{ $nam == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endfor
    </select>

    <button type="submit" class="btn btn-danger">📊 Xem thống kê</button>
</form>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nhân viên</th>
                    <th>Tháng</th>
                    <th>Năm</th>
                    <th>Ngày đi muộn</th>
                    <th>Ngày về sớm</th>
                    <th>Ngày nghỉ</th>
                    <th>Ngày đủ</th>
                    <th>Tổng công</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($thongKeChamCong as $item)
                    <tr>
                        <td>{{ $item->user->name ?? 'N/A' }}</td>
                        <td>{{ $item->thang }}</td>
                        <td>{{ $item->nam }}</td>
                        <td>{{ $item->ngay_di_muon }}</td>
                        <td>{{ $item->ngay_ve_som }}</td>
                        <td>{{ $item->ngay_nghi }}</td>
                        <td>{{ $item->ngay_du }}</td>
                        <td>{{ $item->tong_cong }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

