
@extends('welcome')
@section('body')

<h1 class="text-xl font-bold mb-4">Chỉnh sửa Task</h1>

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
            @foreach(['Thấp', 'Trung bình', 'Cao'] as $option)
                <option value="{{ $option }}" {{ $task->do_uu_tien == $option ? 'selected' : '' }}>{{ $option }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block mb-1">Trạng thái</label>
        <select name="trang_thai" class="form-select w-full">
            @foreach(['Mới', 'Đang thực hiện', 'Hoàn thành'] as $option)
                <option value="{{ $option }}" {{ $task->trang_thai == $option ? 'selected' : '' }}>{{ $option }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block mb-1">Người được giao</label>
        <select name="assigned_to" class="form-select w-full">
            <option value="">-- Không giao --</option>
            @foreach($users as $user)
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
</form>
@endsection
