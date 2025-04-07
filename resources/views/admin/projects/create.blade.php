@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý dự án</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">Dự án</li>
                <li class="breadcrumb-item active">Thêm mới dự án</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Tạo mới Dự án</h5>

                            <a href="{{ route('admin.projects.index') }}" class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách dự án</a>
                        </div>
                        <form action="{{ route('admin.projects.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="ten_du_an" name="ten_du_an"
                                        value="{{ old('ten_du_an') }}" placeholder="Tên Dự Án">
                                    <label for="ten_du_an">Tên Dự Án</label>
                                </div>
                                @error('ten_du_an')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div>
                                        <label for="trang_thai" class="form-label">Trạng Thái</label>
                                        <select name="trang_thai" id="trang_thai" class="form-select">
                                            <option value="Chưa bắt đầu"
                                                {{ old('trang_thai') == 'Chưa bắt đầu' ? 'selected' : '' }}>Chưa bắt đầu
                                            </option>
                                            <option value="Đang thực hiện"
                                                {{ old('trang_thai') == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực
                                                hiện</option>
                                            <option value="Tạm dừng"
                                                {{ old('trang_thai') == 'Tạm dừng' ? 'selected' : '' }}>Tạm dừng</option>
                                            <option value="Hoàn thành"
                                                {{ old('trang_thai') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="ngay_bat_dau" class="form-label">Ngày Bắt Đầu</label>
                                        <input type="date" name="ngay_bat_dau" id="ngay_bat_dau" class="form-control"
                                            value="{{ old('ngay_bat_dau') }}">
                                    </div>
                                    @error('ngay_bat_dau')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="ngay_ket_thuc" class="form-label">Ngày kết thúc</label>
                                        <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control"
                                            value="{{ old('ngay_ket_thuc') }}">
                                    </div>
                                    @error('ngay_ket_thuc')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="user_ids[]" class="form-label">User tham gia dự án</label>
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
                                                        <input type="checkbox" name="user_ids[]"
                                                            value="{{ $user->id }}"
                                                            {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }} />
                                                        <svg viewBox="0 0 35.6 35.6">
                                                            <circle class="background" cx="17.8" cy="17.8"
                                                                r="17.8"></circle>
                                                            <circle class="stroke" cx="17.8" cy="17.8" r="14.37">
                                                            </circle>
                                                            <polyline class="check"
                                                                points="11.78 18.12 15.55 22.23 25.17 12.87">
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
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="mo_ta" class="form-label">Mô Tả</label>
                                    <textarea name="mo_ta" id="tyni" class="form-control" rows="4">{{ old('mo_ta') }}</textarea>

                                </div>
                                @error('mo_ta')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Thêm mới dự án</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

   
@endsection
