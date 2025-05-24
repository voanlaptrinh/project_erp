@extends('welcome')

@section('body')
    <div class="pagetitle">
        <h1>Quản lý Hosting</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Hosting</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Danh sách Hosting</h5>
                            @if (auth()->user()->hasPermissionTo('thêm hosting'))
                                <a href="{{ route('hostings.create') }}" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Thêm mới Hosting
                                </a>
                            @endif
                        </div>

                        <!-- Form tìm kiếm -->
                        <form method="GET" action="{{ route('hostings.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Tên hosting:</label>
                                    <input type="text" name="search" class="form-control"
                                        value="{{ request('search') }}" placeholder="Tìm kiếm...">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Trạng thái:</label>
                                    <select name="status" class="form-select">
                                        <option value="">Tất cả</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                                            Expired</option>
                                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>
                                            Suspended</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Từ ngày:</label>
                                    <input type="date" name="expiry_date_from" class="form-control"
                                        value="{{ request('expiry_date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Đến ngày:</label>
                                    <input type="date" name="expiry_date_to" class="form-control"
                                        value="{{ request('expiry_date_to') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-search"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('hostings.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>

                        @if (request()->hasAny(['search', 'status', 'expiry_date_from', 'expiry_date_to']))
                            <div class="alert alert-info">
                                Đang hiển thị kết quả tìm kiếm:
                                @if (request('search'))
                                    - Tên chứa: "{{ request('search') }}"
                                @endif
                                @if (request('status'))
                                    - Trạng thái: {{ ucfirst(request('status')) }}
                                @endif
                                @if (request('expiry_date_from'))
                                    - Từ ngày: {{ request('expiry_date_from') }}
                                @endif
                                @if (request('expiry_date_to'))
                                    - Đến ngày: {{ request('expiry_date_to') }}
                                @endif
                                <a href="{{ route('hostings.index') }}" class="float-end">Xóa bộ lọc</a>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tên Hosting</th>
                                        <th>Domain</th>
                                        <th>Nhà cung cấp</th>
                                        <th>Gói dịch vụ</th>
                                        <th>Ngày hết hạn</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($hostings as $hosting)
                                        <tr>
                                            <td>{{ $hosting->service_name }}</td>
                                            <td>{{ $hosting->domain->domain_name ?? 'N/A' }}</td>
                                            <td>{{ $hosting->provider }}</td>
                                            <td>{{ $hosting->package }}</td>
                                            <td>{{ $hosting->expiry_date }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $hosting->status == 'active'
                                                        ? 'success'
                                                        : ($hosting->status == 'inactive'
                                                            ? 'secondary'
                                                            : ($hosting->status == 'expired'
                                                                ? 'danger'
                                                                : 'warning')) }}">
                                                    {{ ucfirst($hosting->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if (auth()->user()->hasPermissionTo('xem hosting'))
                                                    <a href="{{ route('hostings.show', $hosting->id) }}"
                                                        class="btn btn-sm btn-info" title="Xem chi tiết">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('sửa hosting'))
                                                    <a href="{{ route('hostings.edit', $hosting->id) }}"
                                                        class="btn btn-sm btn-warning" title="Sửa">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('xóa hosting'))
                                                    <form action="{{ route('hostings.destroy', $hosting->id) }}"
                                                        method="POST" style="display:inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <div class="alert alert-danger">
                                                    Không có hosting nào
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="p-nav text-end d-flex justify-content-end">
                                {{ $hostings->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
