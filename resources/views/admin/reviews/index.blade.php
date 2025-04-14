<!-- admin/reviews/index.blade.php -->

<form method="GET" class="mb-3">
    <input type="month" name="month" class="form-control w-auto d-inline" value="{{ $date->format('Y-m') }}">
    <button type="submit" class="btn btn-primary">Lọc</button>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nhân viên</th>
            <th>Số ngày công</th>
            <th>Số task hoàn thành</th>
            <th>Đi muộn</th>
            <th>Về sớm</th>
            <th>Hiệu suất (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item['user']->name }}</td>
                <td>{{ $item['ngay_cong'] }}</td>
                <td>{{ $item['so_task_hoan_thanh'] }}</td>
                <td>{{ $item['di_muon'] }}</td>
                <td>{{ $item['ve_som'] }}</td>
                <td>{{ $item['hieu_suat'] }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
