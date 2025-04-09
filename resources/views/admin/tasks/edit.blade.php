@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý task của dự án</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Sửa task trong dự án {{ $project->ten_du_an }}</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Sửa task cho dự án</h5>

                            <a href="{{ route('admin.projects.tasks', ['project' => $project->alias ?? null]) }}"
                                class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách Task</a>
                        </div>
                        <form action="{{ route('admin.projects.tasks.update', [$project->alias, $task->id]) }}"
                            method="POST" class="row g-3">
                            @csrf
                            @method('PUT') <!-- Đặt phương thức PUT để cập nhật dữ liệu -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="tieu_de" class="form-label">Tên Vai trò</label>
                                    <input type="text" class="form-control" id="tieu_de" name="tieu_de"
                                        value="{{ old('tieu_de', $task->tieu_de) }}" placeholder="Tên Vai trò">
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
                                            @foreach (['Thấp', 'Trung bình', 'Cao'] as $option)
                                                <option value="{{ $option }}"
                                                    {{ $task->do_uu_tien == $option ? 'selected' : '' }}>{{ $option }}
                                                </option>
                                            @endforeach
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
                                            @foreach (['Mới', 'Đang thực hiện', 'Hoàn thành'] as $option)
                                                <option value="{{ $option }}"
                                                    {{ $task->trang_thai == $option ? 'selected' : '' }}>
                                                    {{ $option }}</option>
                                            @endforeach
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
                                            class="form-control" value="{{ old('han_hoan_thanh', $task->han_hoan_thanh) }}">
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
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
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
                                        <textarea name="mo_ta" id="tyni" class="form-control">{{ old('mo_ta', $task->mo_ta) }}</textarea>
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











    {{-- <h1 class="text-xl font-bold mb-4">Chỉnh sửa Task</h1>

<form method="POST" action="{{ route('admin.projects.tasks.update', [$project->alias, $task->id]) }}">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block mb-1">Tiêu đề</label>
        <input type="text" name="tieu_de" value="{{ old('tieu_de', $task->tieu_de) }}" class="form-input w-full">
    </div>

    <div class="mb-4">
        <label class="block mb-1">Mô tả</label>
        <textarea name="mo_ta" class="form-textarea w-full">{{ old('mo_ta', $task->mo_ta) }}</textarea>
    </div>

    <div class="mb-4">
        <label class="block mb-1">Độ ưu tiên</label>
        <select name="do_uu_tien" class="form-select w-full">
            @foreach (['Thấp', 'Trung bình', 'Cao'] as $option)
                <option value="{{ $option }}" {{ $task->do_uu_tien == $option ? 'selected' : '' }}>{{ $option }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block mb-1">Trạng thái</label>
        <select name="trang_thai" class="form-select w-full">
            @foreach (['Mới', 'Đang thực hiện', 'Hoàn thành'] as $option)
                <option value="{{ $option }}" {{ $task->trang_thai == $option ? 'selected' : '' }}>{{ $option }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block mb-1">Người được giao</label>
        <select name="assigned_to" class="form-select w-full">
            <option value="">-- Không giao --</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block mb-1">Hạn hoàn thành</label>
        <input type="date" name="han_hoan_thanh" value="{{ old('han_hoan_thanh', $task->han_hoan_thanh) }}" class="form-input w-full">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Cập nhật</button>
</form> --}}
@endsection
