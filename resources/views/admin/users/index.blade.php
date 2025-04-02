<!-- resources/views/admin/users/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Người Dùng</title>
</head>
<body>
    <h1>Danh sách Người Dùng</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.users.create') }}">Tạo mới người dùng</a>

    <table border="1">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach($user->roles as $role)
                            {{ $role->name }} 
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}">Chỉnh sửa</a> |
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
