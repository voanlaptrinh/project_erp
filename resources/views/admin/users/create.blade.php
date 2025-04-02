<!-- resources/views/admin/users/create.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tạo Người Dùng Mới</title>
</head>
<body>
    <h1>Tạo Người Dùng Mới</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <label for="name">Tên người dùng</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        <br>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        <br>

        <label for="password">Mật khẩu</label>
        <input type="password" name="password" id="password" required>
        <br>

        <label for="password_confirmation">Xác nhận mật khẩu</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required>
        <br>

        <label for="roles">Vai trò</label><br>
        @foreach($roles as $role)
            <input type="checkbox" name="roles[]" value="{{ $role->name }}">
            <label>{{ $role->name }}</label><br>
        @endforeach
        <br>

        <button type="submit">Tạo người dùng</button>
    </form>

    <a href="{{ route('admin.users.index') }}">Trở lại danh sách người dùng</a>
</body>
</html>
