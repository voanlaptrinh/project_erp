@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Chi tiết dự án</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Chi tiết dự án {{ $project->ten_du_an }}</li>
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
                            <h5 class="card-title">{{ $project->ten_du_an }}</h5>
                            @if (auth()->user()->hasPermissionTo('xem task'))
                                <a href="{{ route('admin.projects.tasks', $project->alias) }}" class="btn btn-success">
                                    <i class="bi bi-eye-fill"></i>
                                    Xem task</a>
                            @endif
                        </div>
                        Ngày bắt đầu: {{ $project->ngay_bat_dau }}
                        Ngày kết thúc: {{ $project->ngay_ket_thuc }}
                    </div>
                    <div class="card-body pt-3 content-chitiet">
                        {!! $project->mo_ta !!}
                    </div>
                    <div class="card-footer">
                        @forelse ($project->users as $item)
                            <span class="badge rounded-pill bg-success">{{ $item->name }}</span>
                        @empty
                            <span>Chưa có người được phân công</span>
                        @endforelse

                    </div>
                </div><!-- End Card with header and footer -->


            </div>


        </div>
    </section>
@endsection
