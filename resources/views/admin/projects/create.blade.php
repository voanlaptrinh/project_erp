@extends('welcome')
@section('body')
<div class="container">
    <h2>Thêm Dự Án Mới</h2>
    <form action="{{ route('admin.projects.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="ten_du_an">Tên Dự Án</label>
            <input type="text" name="ten_du_an" id="ten_du_an" class="form-control" value="{{ old('ten_du_an') }}"
                required>
        </div>
        <div class="form-group">
            <label for="mo_ta">Mô Tả</label>
            <textarea name="mo_ta" id="mo_ta" class="form-control" rows="4">{{ old('mo_ta') }}</textarea>
        </div>
        <div class="form-group">
            <label for="trang_thai">Trạng Thái</label>
            <select name="trang_thai" id="trang_thai" class="form-control">
                <option value="Chưa bắt đầu" {{ old('trang_thai') == 'Chưa bắt đầu' ? 'selected' : '' }}>Chưa bắt đầu
                </option>
                <option value="Đang thực hiện" {{ old('trang_thai') == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực
                    hiện</option>
                <option value="Tạm dừng" {{ old('trang_thai') == 'Tạm dừng' ? 'selected' : '' }}>Tạm dừng</option>
                <option value="Hoàn thành" {{ old('trang_thai') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
            </select>
        </div>
        <div class="form-group">
            <label for="ngay_bat_dau">Ngày Bắt Đầu</label>
            <input type="date" name="ngay_bat_dau" id="ngay_bat_dau" class="form-control"
                value="{{ old('ngay_bat_dau') }}">
        </div>
        <div class="form-group">
            <label for="ngay_ket_thuc">Ngày Kết Thúc</label>
            <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control"
                value="{{ old('ngay_ket_thuc') }}">
        </div>
        <div class="col-md-12">
            <label for="user_ids[]">Vai trò</label>
            <table class="table">
                <tbody>
                    <tr>
                        @foreach ($users as $index => $user)
                            @if ($index % 6 === 0 && $index > 0)
                    </tr>
                    <tr> <!-- Mở một dòng mới sau mỗi 12 vai trò -->
                        @endif
                        <td class="col-md-2">
                            <div>
                                <div class="checkbox-wrapper-31">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"  {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }}/>
                                    <svg viewBox="0 0 35.6 35.6">
                                        <circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>
                                        <circle class="stroke" cx="17.8" cy="17.8" r="14.37">
                                        </circle>
                                        <polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87">
                                        </polyline>
                                    </svg>

                                </div>
                                {{ $user->name }}
                            </div>


                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
            @error('user_ids')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Lưu Dự Án</button>
    </form>
</div>
@endsection