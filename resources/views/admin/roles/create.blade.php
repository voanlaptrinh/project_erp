<!-- resources/views/admin/roles/create.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tạo Vai trò Mới</title>
</head>
<body>
    <h1>Create New Role</h1>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
    
        <label for="name">Tên Vai trò</label>
        <input type="text" name="name" id="name" required>
        <br>
    
        <label for="permissions">Quyền</label><br>
        @foreach($permissions as $permission)
            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"> <!-- Sử dụng name thay vì ID -->
            <label>{{ $permission->name }}</label><br>
        @endforeach
        <br>
    
        <button type="submit">Tạo Vai trò</button>
    </form>

    <a href="{{ route('admin.roles.index') }}">Back to Role List</a>
</body>
</html>
