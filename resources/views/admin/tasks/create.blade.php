@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý task của dự án</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Tạo mới task trong dự án {{ $project->ten_du_an }}</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Tạo mới task cho dự án</h5>

                            <a href="{{ route('admin.projects.tasks', ['project' => $project->alias ?? null]) }}"
                                class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách Task</a>
                        </div>
                        <form action="{{ route('admin.tasks.store', $project->alias) }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="tieu_de" class="form-label">Tên Vai trò</label>
                                    <input type="text" class="form-control" id="tieu_de" name="tieu_de"
                                        value="{{ old('tieu_de') }}" placeholder="Tên Vai trò">
                                </div>
                                @error('tieu_de')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row g-2">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="do_uu_tien" class="form-label">Độ ưu tiên</label>
                                        <select name="do_uu_tien" id="do_uu_tien" class="form-select">
                                            <option value="Thấp" {{ old('do_uu_tien') == 'Thấp' ? 'selected' : '' }}>Thấp
                                            </option>
                                            <option value="Trung bình"
                                                {{ old('do_uu_tien') == 'Trung bình' ? 'selected' : '' }}>Trung bình
                                            </option>
                                            <option value="Cao" {{ old('do_uu_tien') == 'Cao' ? 'selected' : '' }}>Cao
                                            </option>
                                        </select>
                                    </div>
                                    @error('do_uu_tien')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="trang_thai" class="form-label">Trạng thái</label>
                                        <select name="trang_thai" id="trang_thai" class="form-select">
                                            <option value="Mới" {{ old('trang_thai') == 'Mới' ? 'selected' : '' }}>Mới
                                            </option>
                                            <option value="Đang thực hiện"
                                                {{ old('trang_thai') == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực
                                                hiện
                                            </option>
                                            <option value="Hoàn thành"
                                                {{ old('trang_thai') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành
                                            </option>
                                        </select>
                                    </div>
                                    @error('trang_thai')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="do_uu_tien" class="form-label">Hạn hoàn thành</label>
                                        <input type="date" name="han_hoan_thanh" id="han_hoan_thanh"
                                            class="form-control">
                                    </div>
                                    @error('han_hoan_thanh')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="trang_thai" class="form-label">Giao cho</label>
                                        <select name="assigned_to" id="assigned_to" class="form-select">
                                            <option value="">-- Chọn người thực hiện --</option>
                                            @foreach ($project->users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('assigned_to')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="mo_ta" class="form-label">Mô tả</label>
                                        <textarea name="mo_ta" id="tyni" class="form-control">{{old('mo_ta')}}</textarea>
                                    </div>
                                    @error('mo_ta')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Tạo Task</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <div class="container">
        <h2>Thêm Task cho Dự Án: {{ $project->ten_du_an }}</h2>

        <form action="{{ route('admin.tasks.store', $project->alias) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="tieu_de">Tiêu đề</label>
                <input type="text" name="tieu_de" id="tieu_de" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="mo_ta">Mô tả</label>
                <textarea name="mo_ta" id="mo_ta" class="form-control" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label for="do_uu_tien">Độ ưu tiên</label>
                <select name="do_uu_tien" id="do_uu_tien" class="form-control">
                    <option value="Thấp">Thấp</option>
                    <option value="Trung bình" selected>Trung bình</option>
                    <option value="Cao">Cao</option>
                </select>
            </div>

            <div class="form-group">
                <label for="trang_thai">Trạng thái</label>
                <select name="trang_thai" id="trang_thai" class="form-control">
                    <option value="Mới">Mới</option>
                    <option value="Đang thực hiện">Đang thực hiện</option>
                    <option value="Hoàn thành">Hoàn thành</option>
                </select>
            </div>

            <div class="form-group">
                <label for="han_hoan_thanh">Hạn hoàn thành</label>
                <input type="date" name="han_hoan_thanh" id="han_hoan_thanh" class="form-control">
            </div>

            <div class="form-group">
                <label for="assigned_to">Giao cho</label>
                <select name="assigned_to" id="assigned_to" class="form-control">
                    <option value="">-- Chọn người thực hiện --</option>
                    @foreach ($project->users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success mt-3">Tạo Task</button>
        </form>
    </div> --}}
@endsection
