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
                <td>
                     <!-- Nút Sửa -->
        <a href="{{ route('admin.projects.tasks.edit', [$project->alias, $task->id]) }}"
            class="inline-block px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
             Sửa
         </a>
 
         <!-- Nút Xóa (nếu cần) -->
         <form action="{{ route('admin.projects.tasks.destroy', [$project->alias, $task->id]) }}"
               method="POST" class="inline-block"
               onsubmit="return confirm('Bạn có chắc chắn muốn xóa task này?')">
             @csrf
             @method('DELETE')
             <button type="submit" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                 Xóa
             </button>
         </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
