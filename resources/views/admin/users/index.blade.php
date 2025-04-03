@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý tài khoản</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Tài khoản người dùng</li>
            </ol>
        </nav>
    </div>


    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Datatables Licensekeys </h5>
                            @if (auth()->user()->hasPermissionTo('create users'))
                                <a href="{{ route('admin.users.create') }}" class="btn btn-success">

                                    <i class="bi bi-check-circle"></i>

                                    Tạo mới người dùng</a>
                            @endif
                        </div>
                        <form method="GET" action="{{ route('admin.users.index') }}">
                            <div class="row g-3">
                                <div class="col-lg-12 ">
                                    <div class="row g-2">
                                        <div class="col-lg-10">
                                            <label for="email" class="form-label">Email người dùng:</label>
                                            <input type="text" name="email" placeholder="email" class="form-control "
                                                value="{{ request('search') }}">
                                        </div>

                                        <div class="col-lg-2">
                                            <label for="license_key" class="form-label"></label>
                                            <button type="submit" class="me-2 ms-2 btn btn-success w-100 mt-2"><i
                                                    class="bi bi-search"></i></button>

                                        </div>

                                    </div>
                                </div>



                            </div>

                        </form>
                        <hr>
                        <div class="table-responsive">
                            <!-- Table with stripped rows -->
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>
                                            Tên
                                        </th>
                                        <th>Email</th>
                                        <th>Vai trò</th>

                                        <th colspan="2">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $user)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $user->name }}
                                            </td>

                                            <td>
                                                {{ $user->email }}
                                            </td>
                                            <td>
                                                @if ($user->roles->isNotEmpty())
                                                    @foreach ($user->roles as $role)
                                                        <span
                                                            class="badge rounded-pill bg-success">{{ $role->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge rounded-pill bg-secondary">Không có vai trò</span>
                                                @endif


                                            </td>


                                            <td colspan="2">
                                                @if (auth()->user()->hasPermissionTo('edit users'))
                                                    <a class="btn btn-warning"
                                                        href="{{ route('admin.users.edit', $user->id) }}">Chỉnh sửa</a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('delete users'))
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">Xóa</button>
                                                    </form>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                            <div class=" p-nav text-end d-flex justify-content-center">
                                {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           


        </div>
    </section>





    {{-- @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

   

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
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach ($user->roles as $role)
                            {{ $role->name }}
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}">Chỉnh sửa</a> |
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}
@endsection
