@extends('welcome')

@section('body')
    @if (request()->hasAny(['search', 'start_date', 'end_date']))
        <div class="alert alert-info">
            Đang hiển thị kết quả tìm kiếm:
            @if (request('search'))
                - Tên domain chứa: "{{ request('search') }}"
            @endif
            @if (request('start_date'))
                - Từ ngày: {{ request('start_date') }}
            @endif
            @if (request('end_date'))
                - Đến ngày: {{ request('end_date') }}
            @endif
            <a href="{{ route('domains.index') }}" class="float-end">Xóa bộ lọc</a>
        </div>
    @endif
    <div class="pagetitle">
        <h1>Quản lý domain</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Domain</li>
            </ol>
        </nav>
    </div>


    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Quản lý domain </h5>
                            @if (auth()->user()->hasPermissionTo('thêm domain'))
                                <a href="{{ route('domains.create') }}" class="btn btn-success">

                                    <i class="bi bi-check-circle"></i>

                                    Thêm mới domain</a>
                            @endif
                        </div>

                        <form method="GET" action="{{ route('domains.search') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="domain_name" class="form-label">Tên domain:</label>
                                    <input type="text" name="search" placeholder="Tên domain" class="form-control"
                                        value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="start_date" class="form-label">Từ ngày:</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date" class="form-label">Đến ngày:</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-search"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('domains.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="table-responsive">
                            <!-- Table with stripped rows -->
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th>Tên Domain</th>
                                        <th>Ngày tạo</th>
                                        <th>Người tạo</th>
                                        <th>Ngày hết hạn</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($domains as $domain)
                                        <td>{{ $domain->domain_name }}</td>

                                        <td>{{ $domain->created_at }}</td>
                                        <td>{{ $domain->user->name ?? 'Không rõ' }}</td>
                                        <td>{{ $domain->expiry_date }}</td>
                                        {{-- chuyển đổi ký tự đầu tiên của chuỗi thành chữ hoa --}}
                                        <td>{{ ucfirst($domain->status) }}</td>
                                        <td colspan="2">
                                            <a href="{{ route('hostings.index', ['idDomain' => $domain->id]) }}"
                                                class="btn btn-sm btn-primary">
                                                Xem hosting liên quan
                                            </a>
                                            @if (auth()->user()->hasPermissionTo('sửa domain') ||
                                                    auth()->user()->hasPermissionTo('xóa thiết bị') ||
                                                    auth()->user()->hasPermissionTo('xem thiết bị'))
                                                @if (auth()->user()->hasPermissionTo('xem domain'))
                                                    <a href="{{ route('domains.show', $domain) }}"
                                                        class="btn btn-sm btn-info">Xem</a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('sửa domain'))
                                                    <a href="{{ route('domains.edit', $domain) }}"
                                                        class="btn btn-sm btn-warning">Sửa</a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('xóa domain'))
                                                    <form action="{{ route('domains.destroy', $domain) }}" method="POST"
                                                        style="display:inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Xóa hợp thiết bị này?')">Xoá</button>
                                                    </form>
                                                @endif
                                            @endif

                                        </td>
                                        </tr>
                                        <tr>

                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <div class="alert alert-danger">
                                                    Không có thiết bị nào
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                            <div class="p-nav text-end d-flex justify-content-center">
                                {{ $domains->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
