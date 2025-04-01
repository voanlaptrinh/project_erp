<div class="container mt-5">
    <h2 class="text-center">Đăng nhập</h2>
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('auth.google') }}" class="btn btn-danger">
            <img src="https://developers.google.com/identity/images/g-logo.png" width="20" alt="Google Logo">
            Đăng nhập bằng Google
        </a>
    </div>
</div>