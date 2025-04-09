<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link href="{{ asset('/source/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/source/css/bootstrap-icons.css') }}" rel="stylesheet">

    <link href="{{ asset('/source/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/source/css/toastr.min.css') }}">

</head>

<body>
    @if (!($no_layout ?? false))
        <header id="header" class="header fixed-top d-flex align-items-center">

            <div class="d-flex align-items-center justify-content-between">
                <a href="index.html" class="logo d-flex align-items-center">
                    <img src="assets/img/logo.png" alt="">
                    <span class="d-none d-lg-block">NiceAdmin</span>
                </a>
                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div><!-- End Logo -->

            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">



                    <li class="nav-item dropdown">

                        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span
                                class="badge bg-primary badge-number">{{ auth()->user()->notifications()->where('is_read', false)->count() }}</span>
                        </a><!-- End Notification Icon -->

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                            <li class="dropdown-header">
                                Bạn có {{ auth()->user()->notifications()->where('is_read', false)->count() }} thông báo
                                mới chưa đọc
                                <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View
                                        all</span></a>
                            </li>
                            @forelse (auth()->user()->notifications->where('is_read', false) as $notification)
                                {{-- <li><strong>{{ $notification->title }}</strong>: {{ $notification->message }}</li> --}}
                                <li class="notification-item">
                                    <i class="bi bi-info-circle text-primary"></i>
                                    <div>
                                        <h4>{{ $notification->title }}</h4>
                                        <p>{{ $notification->message }}</p>
                                        <p> {{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </li>
                            @empty
                                <span class="dropdown-item text-center">Không có thông báo</span>
                            @endforelse


                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li class="dropdown-footer">
                                <a href="#">Show all notifications</a>
                            </li>

                        </ul><!-- End Notification Dropdown Items -->

                    </li><!-- End Notification Nav -->

                    <li class="nav-item dropdown">

                        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-chat-left-text"></i>
                            <span class="badge bg-success badge-number">3</span>
                        </a><!-- End Messages Icon -->

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                            <li class="dropdown-header">
                                You have 3 new messages
                                <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View
                                        all</span></a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li class="message-item">
                                <a href="#">
                                    <img src="assets/img/messages-1.jpg" alt="" class="rounded-circle">
                                    <div>
                                        <h4>Maria Hudson</h4>
                                        <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                        <p>4 hrs. ago</p>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li class="message-item">
                                <a href="#">
                                    <img src="assets/img/messages-2.jpg" alt="" class="rounded-circle">
                                    <div>
                                        <h4>Anna Nelson</h4>
                                        <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                        <p>6 hrs. ago</p>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li class="message-item">
                                <a href="#">
                                    <img src="assets/img/messages-3.jpg" alt="" class="rounded-circle">
                                    <div>
                                        <h4>David Muldon</h4>
                                        <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                        <p>8 hrs. ago</p>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li class="dropdown-footer">
                                <a href="#">Show all messages</a>
                            </li>

                        </ul><!-- End Messages Dropdown Items -->

                    </li><!-- End Messages Nav -->

                    <li class="nav-item dropdown pe-3">

                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                            data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar ?? '' }}" alt="Profile" class="rounded-circle">
                            <span
                                class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->name ?? '' }}</span>
                        </a><!-- End Profile Iamge Icon -->

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                            <li class="dropdown-header">
                                <h6>{{ auth()->user()->name ?? '' }}</h6>
                                <span>{{ auth()->user()->email ?? '' }}</span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                                    <i class="bi bi-person"></i>
                                    <span>My Profile</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                                    <i class="bi bi-gear"></i>
                                    <span>Account Settings</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>


                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn w-100 text-start"> <i
                                            class="bi bi-box-arrow-right"></i> Đăng Xuất</button>
                                </form>
                                {{-- <a class="dropdown-item d-flex align-items-center" href="#">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Sign Out</span>
                                </a> --}}
                            </li>

                        </ul><!-- End Profile Dropdown Items -->
                    </li><!-- End Profile Nav -->

                </ul>
            </nav><!-- End Icons Navigation -->

        </header><!-- End Header -->

        <!-- ======= Sidebar ======= -->
        <aside id="sidebar" class="sidebar">

            <ul class="sidebar-nav" id="sidebar-nav">

                <li class="nav-item">
                    <a class="nav-link collapsed" href="index.html">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @if (auth()->user()->hasPermissionTo('view users'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['admin.users.index', 'admin.users.create', 'admin.users.edit']) ? '' : 'collapsed' }}"
                            href="{{ route('admin.users.index') }}">
                            <i class="bi bi-person"></i>
                            <span>Quản lý người dùng</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('view roles'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['admin.roles.index', 'admin.roles.create', 'admin.roles.edit']) ? '' : 'collapsed' }}"
                            href="{{ route('admin.roles.index') }}">
                            <i class="bi-shield-lock-fill"></i>
                            <span>Quản lý quyền người dùng</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('xem dự án'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['admin.projects.show', 'admin.projects.index', 'admin.projects.create', 'admin.projects.edit', 'admin.projects.tasks', 'admin.projects.tasks.edit', 'admin.tasks.create', 'admin.projects.tasks.show']) ? '' : 'collapsed' }}"
                            href="{{ route('admin.projects.index') }}">
                            <i class="bi bi-kanban"></i>
                            <span>Dự án</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('xem hợp đồng'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['']) ? '' : 'collapsed' }}"
                            href="{{ route('admin.employee-contracts.index') }}">
                            <i class="bi bi-kanban"></i>
                            <span>Xem hợp đồng</span>
                        </a>
                    </li>
                @endif




                <li class="nav-heading">Pages</li>

                <!-- End Profile Page Nav -->

                <li class="nav-item">
                    <a class="nav-link collapsed" href="pages-faq.html">
                        <i class="bi bi-question-circle"></i>
                        <span>F.A.Q</span>
                    </a>
                </li><!-- End F.A.Q Page Nav -->

                <li class="nav-item">
                    <a class="nav-link collapsed" href="pages-contact.html">
                        <i class="bi bi-envelope"></i>
                        <span>Contact</span>
                    </a>
                </li><!-- End Contact Page Nav -->

                <li class="nav-item">
                    <a class="nav-link collapsed" href="pages-register.html">
                        <i class="bi bi-card-list"></i>
                        <span>Register</span>
                    </a>
                </li><!-- End Register Page Nav -->

                <li class="nav-item">
                    <a class="nav-link collapsed" href="pages-login.html">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Login</span>
                    </a>
                </li><!-- End Login Page Nav -->

                <li class="nav-item">
                    <a class="nav-link collapsed" href="pages-error-404.html">
                        <i class="bi bi-dash-circle"></i>
                        <span>Error 404</span>
                    </a>
                </li><!-- End Error 404 Page Nav -->

                <li class="nav-item">
                    <a class="nav-link collapsed" href="pages-blank.html">
                        <i class="bi bi-file-earmark"></i>
                        <span>Blank</span>
                    </a>
                </li><!-- End Blank Page Nav -->

            </ul>

        </aside><!-- End Sidebar-->
    @endif
    <main id="main" class="main">
        @yield('body')
    </main>
    @if (!($no_layout ?? false))
        <footer id="footer" class="footer">
            <div class="copyright">
                &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
            </div>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </footer>
    @endif
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->

    <script src="{{ asset('source/js/bootstrap.bundle.min.js') }}"></script>



    <!-- Template Main JS File -->
    <script src="{{ asset('source/js/main.js') }}"></script>
    <script src="{{ asset('/source/js/jquery-3.3.1.js') }}"></script>
    <script src="{{ asset('/source/js/toastr.min.js') }}"></script>

    <script src="{{ asset('/source/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: '#tyni',
            plugins: 'advlist autolink lists link charmap preview anchor table image',
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help | table | link image | blocks fontfamily fontsize',
            images_upload_url: "/admin/upload-image",
            relative_urls: false,
            document_base_url: "{{ url('/') }}",
            automatic_uploads: true,
            setup: function(editor) {
                editor.on('NodeChange', function(event) {
                    const currentImages = Array.from(editor.getDoc().querySelectorAll('img')).map(img =>
                        img.src);

                    if (!editor.oldImages) editor.oldImages = currentImages;

                    const removedImages = editor.oldImages.filter(img => !currentImages.includes(img));
                    editor.oldImages = currentImages;

                    removedImages.forEach(imageUrl => {
                        fetch('/admin/delete-image', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    image: imageUrl
                                })
                            })
                            .then(response => response.json())
                            .then(data => console.log(data.message))
                            .catch(error => console.error('Lỗi khi xóa ảnh:', error));
                    });
                });
            }
        })
    </script>
    <script>
        toastr.options = {

            "progressBar": true, // Hiển thị thanh tiến trình
            "timeOut": 2000, // Thời gian hiển thị thông báo (2 giây)
            "extendedTimeOut": 1000, // Thời gian hiển thị khi người dùng di chuột vào thông báo (1 giây)

        };

        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif

        @if (Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif

        @if (Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
        @endif

        @if (Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}");
        @endif
    </script>

</body>

</html>
