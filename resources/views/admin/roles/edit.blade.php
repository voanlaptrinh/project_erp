<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh Sửa Vai trò</title>
</head>
<body>
    <h1>Chỉnh Sửa Vai trò: {{ $role->name }}</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Correct the method to PUT for updating -->

        <label for="name">Tên Vai trò</label>
        <input type="text" name="name" id="name" value="{{ $role->name }}" required>
        <br>

        <label for="permissions">Quyền</label><br>
        @foreach($permissions as $permission)
            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
            <label>{{ $permission->name }}</label><br>
        @endforeach
        <br>

        <button type="submit">Cập nhật vai trò</button>
    </form>

    <a href="{{ route('admin.roles.index') }}">Trở lại danh sách vai trò</a>
</body>
</html>
