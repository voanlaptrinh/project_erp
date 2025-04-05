<h3>Danh sách Task</h3>
<a href="{{ route('admin.tasks.create', $project->alias) }}" class="btn btn-success">Thêm Task</a>

<table class="table">
    <thead>
        <tr>
            <th>Tiêu đề</th>
            <th>Dự án</th>
            <th>Người phụ trách</th>
            <th>Hạn hoàn thành</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
            <tr>
                <td>{{ $task->tieu_de }}</td>
                <td>{{ $task->project->ten_du_an }}</td>
                <td>{{ $task->user->name ?? 'Chưa giao' }}</td>
                <td>{{ $task->han_hoan_thanh ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
