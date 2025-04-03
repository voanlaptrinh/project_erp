<!-- resources/views/admin/users/edit.blade.php -->
@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý tài khoản</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">Tài khoản người dùng</li>
                <li class="breadcrumb-item active">Sửa người dùng</li>
            </ol>
        </nav>
    </div>




    <section class="section">
        <div class="row">


            <div class="col-lg-12">



                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Chỉnh sửa người dùng</h5>

                            <a href="{{ route('admin.users.index') }}" class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách người dùng</a>
                        </div>
                        <form class="row g-3" action="{{ route('admin.users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $user->name }}" placeholder="Tên người dùng" required>
                                    <label for="name">Tên người dùng</label>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email"
                                    value="{{ $user->email }}" required disabled placeholder="Email người dùng">
                                    <label for="email">Email người dùng</label>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="roles">Vai trò</label>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            @foreach ($roles as $index => $role)
                                                @if ($index % 6 === 0 && $index > 0)
                                        </tr>
                                        <tr> <!-- Mở một dòng mới sau mỗi 12 vai trò -->
                                            @endif
                                            <td class="col-md-2">
                                                <div>
                                                    <div class="checkbox-wrapper-31">
                                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'checked' : '' }}/>
                                                        <svg viewBox="0 0 35.6 35.6">
                                                            <circle class="background" cx="17.8" cy="17.8"
                                                                r="17.8"></circle>
                                                            <circle class="stroke" cx="17.8" cy="17.8" r="14.37">
                                                            </circle>
                                                            <polyline class="check"
                                                                points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>
                                                        </svg>

                                                    </div>
                                                    {{ $role->name }}
                                                </div>
                                                {{-- <label class="checkbox-container">
                                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                                    {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                                    <span class="checkmark"></span>
                                                    {{ $role->name }}
                                                </label> --}}
                                            </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                                @error('roles')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Chỉnh sửa</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
{{-- <h1>Chỉnh Sửa Người Dùng: {{ $user->name }}</h1>

    @if (session('success'))
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
        @foreach ($roles as $role)
            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                {{ $user->hasRole($role->name) ? 'checked' : '' }}>
            <label>{{ $role->name }}</label><br>
        @endforeach
        <br>

        <button type="submit">Cập nhật người dùng</button>
    </form>

    <a href="{{ route('admin.users.index') }}">Trở lại danh sách người dùng</a> --}}
