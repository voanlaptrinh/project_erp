<div class="container">
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
                @foreach($project->users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success mt-3">Tạo Task</button>
    </form>
</div>