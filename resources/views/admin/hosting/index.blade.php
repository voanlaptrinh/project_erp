@extends('welcome')

@section('body')
<div class="container">
    <h2>Danh sách Hosting</h2>

    @if(request()->has('idDomain'))
        <div class="alert alert-info">
            Đang lọc theo domain ID: {{ request()->idDomain }}
            <a href="{{ route('hostings.index') }}" class="btn btn-sm btn-secondary ml-2">Bỏ lọc</a>
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Dịch vụ</th>
                <th>Domain</th>
                <th>Provider</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hostings as $hosting)
            <tr>
                <td>{{ $hosting->service_name }}</td>
                <td>
                    @if($hosting->domain)
                        <a href="{{ route('domains.index') }}">{{ $hosting->domain->domain_name }}</a>
                    @else
                        <em>Không liên kết</em>
                    @endif
                </td>
                <td>{{ $hosting->provider }}</td>
                <td>{{ ucfirst($hosting->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
