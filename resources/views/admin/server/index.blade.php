@extends('welcome')

@section('body')
    <div class="pagetitle">
        <h1>Quản lý Server</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                <li class="breadcrumb-item active">Server</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 d-sm-flex justify-content-between align-items-center">
                            <h5 class="card-title">Danh sách Server</h5>
                            @if (auth()->user()->hasPermissionTo('thêm server'))
                                <a href="{{ route('servers.create') }}" class="btn btn-success">
                                    <i class="bi bi-plus-circle"></i> Thêm mới Server
                                </a>
                            @endif
                        </div>

                        <!-- Form tìm kiếm -->
                        <form method="GET" action="{{ route('servers.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="form-label">Tên server:</label>
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
                                    <label class="form-label">Nhà cung cấp:</label>
                                    <input type="text" name="provider" class="form-control"
                                        value="{{ request('provider') }}" placeholder="Nhà cung cấp">
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
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-search"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('servers.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>

                        @if (request()->hasAny(['search', 'status', 'provider', 'expiry_date_from', 'expiry_date_to']))
                            <div class="alert alert-info">
                                Đang hiển thị kết quả tìm kiếm:
                                @if (request('search'))
                                    - Tên chứa: "{{ request('search') }}"
                                @endif
                                @if (request('status'))
                                    - Trạng thái: {{ ucfirst(request('status')) }}
                                @endif
                                @if (request('provider'))
                                    - Nhà cung cấp: "{{ request('provider') }}"
                                @endif
                                @if (request('expiry_date_from'))
                                    - Từ ngày: {{ request('expiry_date_from') }}
                                @endif
                                @if (request('expiry_date_to'))
                                    - Đến ngày: {{ request('expiry_date_to') }}
                                @endif
                                <a href="{{ route('servers.index') }}" class="float-end">Xóa bộ lọc</a>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tên Server</th>
                                        <th>Nhà cung cấp</th>
                                        <th>IP Address</th>
                                        <th>Hệ điều hành</th>
                                        <th>Ngày hết hạn</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($servers as $server)
                                        <tr>
                                            <td>{{ $server->server_name }}</td>
                                            <td>{{ $server->provider }}</td>
                                            <td>{{ $server->ip_address }}</td>
                                            <td>{{ $server->os }}</td>
                                            <td>{{ $server->expiry_date }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $server->status == 'active'
                                                        ? 'success'
                                                        : ($server->status == 'inactive'
                                                            ? 'secondary'
                                                            : ($server->status == 'expired'
                                                                ? 'danger'
                                                                : 'warning')) }}">
                                                    {{ ucfirst($server->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if (auth()->user()->hasPermissionTo('xem server'))
                                                    <a href="{{ route('servers.show', $server->id) }}"
                                                        class="btn btn-sm btn-info" title="Xem chi tiết">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('sửa server'))
                                                    <a href="{{ route('servers.edit', $server->id) }}"
                                                        class="btn btn-sm btn-warning" title="Sửa">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endif
                                                @if (auth()->user()->hasPermissionTo('xóa server'))
                                                    <form action="{{ route('servers.destroy', $server->id) }}"
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
                                                Không có server nào
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="p-nav text-end d-flex justify-content-end">
                                {{ $servers->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
