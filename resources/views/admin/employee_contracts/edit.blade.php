<form action="{{ route('admin.employee-contracts.update', $contract->alias) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
    @method('PUT')

    @role('Super Admin')
        <div>
            <label for="user_id">Nhân viên:</label>
            <select name="user_id" id="user_id" class="form-select" disabled>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $contract->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} - {{ $user->email }}
                    </option>
                @endforeach
            </select>
        </div>
    @endrole

    <div>
        <label>Loại hợp đồng:</label>
        <input type="text" name="loai_hop_dong" class="form-input" value="{{ old('loai_hop_dong', $contract->loai_hop_dong) }}" required>
    </div>

    <div>
        <label>Ngày bắt đầu:</label>
        <input type="date" name="ngay_bat_dau" class="form-input" value="{{ old('ngay_bat_dau', $contract->ngay_bat_dau) }}" required>
    </div>

    <div>
        <label>Ngày kết thúc:</label>
        <input type="date" name="ngay_ket_thuc" class="form-input" value="{{ old('ngay_ket_thuc', $contract->ngay_ket_thuc) }}">
    </div>

    <div>
        <label>Lương thỏa thuận:</label>
        <input type="text" name="luong_thoa_thuan" class="form-input" value="{{ old('luong_thoa_thuan', $contract->luong_thoa_thuan) }}" required>
    </div>

    <div>
        <label>File hợp đồng:</label>
        @if($contract->file_hop_dong)
            <div class="mb-2">
                <strong>File hiện tại:</strong> <a href="{{ asset('contracts/' . basename($contract->file_hop_dong)) }}" target="_blank">{{ basename($contract->file_hop_dong) }}</a>
            </div>
        @endif
        <input type="file" name="file_hop_dong" accept=".pdf,.doc,.docx">
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>