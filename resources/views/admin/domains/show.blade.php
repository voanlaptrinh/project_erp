@extends('welcome')

@section('body')
<div class="container">
    <h2>Chi tiết Domain</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $domain->domain_name }}</h5>
            <p><strong>Registrar:</strong> {{ $domain->registrar ?? 'Không có' }}</p>
            <p><strong>Bắt đầu:</strong> {{ $domain->start_date }}</p>
            <p><strong>Hết hạn:</strong> {{ $domain->expiry_date }}</p>
            <p><strong>Trạng thái:</strong> {{ ucfirst($domain->status) }}</p>
        </div>
    </div>

    <a href="{{ route('domains.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
</div>
@endsection
