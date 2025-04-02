<!-- resources/views/admin/users/edit.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh Sửa Người Dùng</title>
</head>
<body>
    <h1>Chỉnh Sửa Người Dùng: {{ $user->name }}</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('POST')

        <label for="name">Tên người dùng</label>
        <input type="text" name="name" id="name" value="{{ $user->name }}" required>
        <br>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ $user->email }}" required disabled>
        <br>

        <label for="roles">Vai trò</label><br>
        @foreach($roles as $role)
            <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                {{ $user->hasRole($role->name) ? 'checked' : '' }}>
            <label>{{ $role->name }}</label><br>
        @endforeach
        <br>

        <button type="submit">Cập nhật người dùng</button>
    </form>

    <a href="{{ route('admin.users.index') }}">Trở lại danh sách người dùng</a>
</body>
</html>
