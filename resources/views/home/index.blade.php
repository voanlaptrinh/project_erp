@extends('welcome')
@section('body')
    <div class="container mt-5">
      @if (!empty(auth()->user()))
      {{-- <h2>Chào mừng, {{ auth()->user()->name }}</h2>
      <img src="{{ auth()->user()->avatar }}" alt="Avatar" width="100" class="rounded-circle">
      <p>Email: {{ auth()->user()->email }}</p> --}}

      @endif
        {{-- <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Đăng xuất</button>
        </form> --}}
    </div>
@endsection
