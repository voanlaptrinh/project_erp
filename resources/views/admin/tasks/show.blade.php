@extends('welcome')
@section('body')
    <div class="pagetitle">
        <h1>Quản lý task của dự án</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Xem chi tiết task trong dự án {{ $project->ten_du_an }}</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Chi tiết task cho dự án</h5>

                            <a href="{{ route('admin.projects.tasks', ['project' => $project->alias ?? null]) }}"
                                class="btn btn-success">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                                Trở lại danh sách Task</a>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="shadow p-3 content-chitiet">
                                    <h4>{{ $task->tieu_de }}</h4>
                                    <p><strong>Độ ưu tiên:</strong> {{ $task->do_uu_tien }}</p>
                                    <p><strong>Trạng thái:</strong> {{ $task->trang_thai }}</p>
                                    <p><strong>Dự án:</strong> {{ $project->ten_du_an }}</p>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="shadow p-3 content-chitiet">
                                    {!! $task->mo_ta !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
