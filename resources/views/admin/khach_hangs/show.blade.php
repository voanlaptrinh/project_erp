@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Chi tiết khách hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Chi tiết khách hàng {{ $khachHang->ten }}</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row align-items-top">
            <div class="col-lg-12">



                <!-- Card with header and footer -->
                <div class="card">
                    <div class="card-header">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Tên khách hàng:{{ $khachHang->ten }}</h5>
                         
                                <a href="{{ route('khach-hangs.index') }}" class="btn btn-success">
                                    <i class="bi bi-eye-fill"></i>
                                   Về trang khách hàng</a>
                        
                        </div>
                        <p>Email: {{ $khachHang->email }} </p>
                        <p> Số điện thoại: {{ $khachHang->so_dien_thoai }}</p>
                        <p>Địa chỉ: {{$khachHang->dia_chi}}</p>
                        <p class="text-dark">Dự án: {{$khachHang->project->ten_du_an ?? '---'}}</p>
                    </div>
                    <div class="card-body pt-3 content-chitiet">
                        {!! $khachHang->ghi_chu !!}
                    </div>
                    {{-- <div class="card-footer">
                        @forelse ($project->users as $item)
                            <span class="badge rounded-pill bg-success">{{ $item->name }}</span>
                        @empty
                            <span>Chưa có người được phân công</span>
                        @endforelse

                    </div> --}}
                </div><!-- End Card with header and footer -->


            </div>


        </div>
    </section>
@endsection
