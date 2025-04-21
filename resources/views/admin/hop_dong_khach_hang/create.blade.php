@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý Hợp đồng dự án</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">Hợp đồng dự án</li>
                <li class="breadcrumb-item active">Thêm mới hợp đồng dự án</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Tạo mới hợp đồng dự án</h5>

                            <a href="{{ route('hop_dong_khach_hang.index') }}" class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách hợp đồng dự án</a>
                        </div>
                        <form action="{{ route('hop_dong_khach_hang.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                            @csrf
                        
                            <div class="col-lg-6">
                                <label for="project_id" class="form-label">Dự án</label>
                                <select name="project_id" id="user_id" class="form-control @error('project_id') is-invalid @enderror">
                                    <option value="">-- Chọn dự án --</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->ten_du_an }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-lg-6">
                                <label for="trang_thai" class="form-label">Trạng thái</label>
                                <select name="trang_thai" class="form-control @error('trang_thai') is-invalid @enderror">
                                    <option value="đang hiệu lực" {{ old('trang_thai') == 'đang hiệu lực' ? 'selected' : '' }}>Đang hiệu lực</option>
                                    <option value="hết hiệu lực" {{ old('trang_thai') == 'hết hiệu lực' ? 'selected' : '' }}>Hết hiệu lực</option>
                                    <option value="hủy" {{ old('trang_thai') == 'hủy' ? 'selected' : '' }}>Hủy</option>
                                </select>
                                @error('trang_thai')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-lg-6">
                                <label for="so_hop_dong" class="form-label">Số hợp đồng</label>
                                <input type="text" name="so_hop_dong" class="form-control @error('so_hop_dong') is-invalid @enderror" value="{{ old('so_hop_dong') }}">
                                @error('so_hop_dong')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-lg-6">
                                <label for="gia_tri" class="form-label">Giá trị</label>
                                <input type="number" name="gia_tri" class="form-control @error('gia_tri') is-invalid @enderror" value="{{ old('gia_tri') }}">
                                @error('gia_tri')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-lg-12">
                                <div class="text-center justify-content-center align-items-center p-4 p-sm-5 border border-2 border-dashed position-relative rounded-3">
                                    <img src="{{ asset('source/images/gallery.png') }}" class="h-50px" alt="">
                                    <div>
                                        <h6 class="my-2">Upload course file here, or <a href="#!" class="text-primary">Browse</a></h6>
                                        <label style="cursor:pointer;">
                                            <input class="form-control stretched-link @error('file') is-invalid @enderror" type="file" name="file">
                                        </label>
                                      
                                        <p class="small mb-0 mt-2"><b>Ghi chú:</b> Đưa bản mềm hợp đồng dự án lên đây</p>
                                    </div>
                                </div>
                                @error('file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            </div>
                        
                            <div class="col-lg-6">
                                <label for="ngay_ky" class="form-label">Ngày ký</label>
                                <input type="date" name="ngay_ky" class="form-control @error('ngay_ky') is-invalid @enderror" value="{{ old('ngay_ky') }}">
                                @error('ngay_ky')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-lg-6">
                                <label for="ngay_het_han" class="form-label">Ngày hết hạn</label>
                                <input type="date" name="ngay_het_han" class="form-control @error('ngay_het_han') is-invalid @enderror" value="{{ old('ngay_het_han') }}">
                                @error('ngay_het_han')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-lg-12">
                                <label for="noi_dung" class="form-label">Nội dung</label>
                                <textarea name="noi_dung" id="tyni" class="form-control @error('noi_dung') is-invalid @enderror" rows="4">{{ old('noi_dung') }}</textarea>
                                @error('noi_dung')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Thêm mới hợp đồng dự án</button>
                            </div>
                        </form>
                        

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
