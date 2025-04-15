@extends('welcome')
@section('body')
<div class="container">
    <h2>Thêm khách hàng</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('khach-hangs.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="project_id" class="form-label">Dự án liên quan</label>
            <select name="project_id" id="project_id" class="form-select">
                <option value="">-- Chọn dự án --</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                        {{ $project->ten_du_an }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="ten" class="form-label">Tên khách hàng</label>
            <input type="text" name="ten" class="form-control @error('ten') is-invalid @enderror" value="{{ old('ten') }}">
            @error('ten') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
            <input type="text" name="so_dien_thoai" class="form-control" value="{{ old('so_dien_thoai') }}">
        </div>

        <div class="mb-3">
            <label for="dia_chi" class="form-label">Địa chỉ</label>
            <input type="text" name="dia_chi" class="form-control" value="{{ old('dia_chi') }}">
        </div>

        <div class="mb-3">
            <label for="ghi_chu" class="form-label">Ghi chú</label>
            <textarea name="ghi_chu" class="form-control" rows="3">{{ old('ghi_chu') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Thêm khách hàng</button>
    </form>
</div>
@endsection
