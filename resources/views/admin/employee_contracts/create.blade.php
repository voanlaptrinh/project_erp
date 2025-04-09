<form action="{{ route('admin.employee-contracts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf

    
        <div>
            <label for="user_id">Nhân viên:</label>
            <select name="user_id" id="user_id" required class="form-select">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                @endforeach
            </select>
        </div>
   

    <div>
        <label>Loại hợp đồng:</label>
        <input type="text" name="loai_hop_dong" class="form-input" value="{{ old('loai_hop_dong') }}" required>
    </div>

    <div>
        <label>Ngày bắt đầu:</label>
        <input type="date" name="ngay_bat_dau" class="form-input" value="{{ old('ngay_bat_dau') }}" required>
    </div>

    <div>
        <label>Ngày kết thúc:</label>
        <input type="date" name="ngay_ket_thuc" class="form-input" value="{{ old('ngay_ket_thuc') }}">
    </div>

    <div>
        <label>Lương thỏa thuận:</label>
        <input type="text" name="luong_thoa_thuan" class="form-input" value="{{ old('luong_thoa_thuan') }}" required>
    </div>

    <div>
        <label>File hợp đồng:</label>
        <input type="file" name="file_hop_dong" accept=".pdf,.doc,.docx">
    </div>

    <button type="submit" class="btn btn-primary">Lưu</button>
</form>