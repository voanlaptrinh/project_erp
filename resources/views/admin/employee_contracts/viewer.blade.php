@extends('welcome')
@section('body')
    <div class="container py-4">


        <div style="height: 90vh;">
            <iframe src="{{ asset($contract->file_hop_dong) }}" style="width: 100%; height: 100%;" frameborder="0">
            </iframe>
        </div>
    </div>
@endsection
