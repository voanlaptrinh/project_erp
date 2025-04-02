<!-- resources/views/admin/roles/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Vai trò</title>
</head>
<body>
    <h1>Danh sách Vai trò</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.roles.create') }}">Tạo vai trò mới</a>

    <table border="1">
        <thead>
            <tr>
                <th>Tên Vai trò</th>
                <th>Quyền</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>
                        @foreach($role->permissions as $permission)
                            {{ $permission->name }} <br>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('admin.roles.edit', $role->id) }}">Chỉnh sửa</a> |
                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa vai trò này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
