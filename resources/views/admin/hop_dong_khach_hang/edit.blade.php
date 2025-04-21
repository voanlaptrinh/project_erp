@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý Hợp đồng dự án</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item">Hợp đồng dự án</li>
                <li class="breadcrumb-item active">Sửa hợp đồng dự án</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Sửa hợp đồng dự án</h5>

                            <a href="{{ route('hop_dong_khach_hang.index') }}" class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách hợp đồng dự án</a>
                        </div>
                        <form action="{{ route('hop_dong_khach_hang.update', $hopDong->alias) }}" method="POST"
                            enctype="multipart/form-data" class="row g-3">
                          @csrf
                          @method('PUT')
                      
                          <div class="mb-3">
                              <label for="project_id" class="form-label">Dự án</label>
                              <select name="project_id" class="form-control">
                                  @foreach ($projects as $project)
                                      <option value="{{ $project->id }}" 
                                              {{ old('project_id', $hopDong->project_id) == $project->id ? 'selected' : '' }}>
                                          {{ $project->ten_du_an }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                      
                          <div class="mb-3">
                              <label for="so_hop_dong" class="form-label">Số hợp đồng</label>
                              <input type="text" name="so_hop_dong" class="form-control" 
                                     value="{{ old('so_hop_dong', $hopDong->so_hop_dong) }}">
                          </div>
                      
                          <div class="mb-3">
                              <label for="file" class="form-label">Tệp hợp đồng</label>
                              <input type="file" name="file" class="form-control">
                              @if ($hopDong->file)
                                  <a href="{{ asset($hopDong->file) }}" target="_blank">Xem tệp hiện tại</a>
                              @endif
                          </div>
                      
                          @if ($hopDong->file)
                              <iframe src="{{ asset($hopDong->file) }}" width="100%" height="600px"></iframe>
                          @endif
                      
                          <div class="mb-3">
                              <label for="ngay_ky" class="form-label">Ngày ký</label>
                              <input type="date" name="ngay_ky" class="form-control" 
                                     value="{{ old('ngay_ky', $hopDong->ngay_ky) }}">
                          </div>
                      
                          <div class="mb-3">
                              <label for="ngay_het_han" class="form-label">Ngày hết hạn</label>
                              <input type="date" name="ngay_het_han" class="form-control" 
                                     value="{{ old('ngay_het_han', $hopDong->ngay_het_han) }}">
                          </div>
                      
                          <div class="mb-3">
                              <label for="gia_tri" class="form-label">Giá trị</label>
                              <input type="number" name="gia_tri" class="form-control" 
                                     value="{{ old('gia_tri', $hopDong->gia_tri) }}">
                          </div>
                      
                          <div class="mb-3">
                              <label for="noi_dung" class="form-label">Nội dung</label>
                              <textarea name="noi_dung" class="form-control" rows="4">{{ old('noi_dung', $hopDong->noi_dung) }}</textarea>
                          </div>
                      
                          <div class="mb-3">
                              <label for="trang_thai" class="form-label">Trạng thái</label>
                              <select name="trang_thai" class="form-control">
                                  <option value="đang hiệu lực" {{ old('trang_thai', $hopDong->trang_thai) == 'đang hiệu lực' ? 'selected' : '' }}>Đang hiệu lực</option>
                                  <option value="hết hiệu lực" {{ old('trang_thai', $hopDong->trang_thai) == 'hết hiệu lực' ? 'selected' : '' }}>Hết hiệu lực</option>
                                  <option value="hủy" {{ old('trang_thai', $hopDong->trang_thai) == 'hủy' ? 'selected' : '' }}>Hủy</option>
                              </select>
                          </div>
                      
                          <button type="submit" class="btn btn-primary">Cập nhật</button>
                      </form>
                      
                    </div>
                </div>
            </div>
        </div>
    </section>


   
@endsection
