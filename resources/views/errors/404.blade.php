@extends('welcome')
@section('body')
<div class="container">

      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1 style="color: #F05729">404</h1>
        <h2>Trang bạn đang tìm kiếm không tồn tại.</h2>
       
        <img src="{{asset('source/images/not-found.svg')}}" class="img-fluid py-5" alt="Page Not Found">
        
      </section>

    </div>
  @endsection