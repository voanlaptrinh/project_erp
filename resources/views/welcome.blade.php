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
    <!-- Select2 core CSS -->
    <link href="{{ asset('/source/css/select2.min.css') }}" rel="stylesheet" />

    <!-- Theme Bootstrap 5 -->
    <link href="{{ asset('/source/css/select2-bootstrap4.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    @if (!($no_layout ?? false))
        <header id="header" class="header fixed-top d-flex align-items-center">

            <div class="d-flex align-items-center justify-content-between">
                <a href="index.html" class="logo d-flex align-items-center">
                    <img src="assets/img/logo.png" alt="">
                    <span class="d-none d-lg-block">Metasoft</span>
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

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" style="max-height: 400px; overflow-y: auto;">
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

                    {{-- thong bao --}}
                    <li class="nav-item dropdown">

                        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-chat-left-text"></i>
                            <span class="badge bg-success badge-number">
                                {{ auth()->user()->thongBaoChats()->where('is_read', false)->count() }}</span>
                        </a><!-- End Messages Icon -->

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications"
                            style="max-height: 400px; overflow-y: auto;">
                            <li class="dropdown-header">
                                Bạn có {{ auth()->user()->thongBaoChats()->where('is_read', false)->count() }} thông
                                báo
                                mới chưa đọc
                                <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View
                                        all</span></a>
                            </li>
                            @forelse (auth()->user()->thongBaoChats->where('is_read', false) as $thongBaoChats)
                                {{-- <li><strong>{{ $notification->title }}</strong>: {{ $notification->message }}</li> --}}
                                <li class="notification-item">
                                    <a href="{{ route('thongbao.chat.goto', $thongBaoChats->id) }}"
                                        class="d-flex text-decoration-none text-dark">
                                        <i class="bi bi-info-circle text-primary"></i>
                                        <div>
                                            <h4>{{ $thongBaoChats->title }}</h4>
                                            <p>{{ $thongBaoChats->message }}</p>
                                            <p> {{ $thongBaoChats->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
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

                    </li><!-- End Messages Nav -->

                    <li class="nav-item dropdown pe-3">

                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                            data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar ?? asset('source/images/icon_usser.png') }}"
                                alt="Profile" class="rounded-circle">
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
        <aside id="sidebar" class="sidebar d-flex flex-column justify-content-between">

            <ul class="sidebar-nav" id="sidebar-nav">

                <li class="nav-item">
                    <a class="nav-link collapsed" href="index.html">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @if (auth()->user()->hasPermissionTo('xem người dùng') ||
                        auth()->user()->hasPermissionTo('view roles') ||
                        auth()->user()->hasPermissionTo('xem dự án') ||
                        auth()->user()->hasPermissionTo('xem toàn bộ dự án') ||
                        auth()->user()->hasPermissionTo('xem hợp đồng') ||
                        auth()->user()->hasPermissionTo('xem toàn bộ hợp đồng') ||
                        auth()->user()->hasPermissionTo('toàn bộ chấm công') ||
                        auth()->user()->hasPermissionTo('xem chấm công') ||
                        auth()->user()->hasPermissionTo('xem thiết bị'))
                    <li class="nav-heading">Nhận sự</li>
                @endif
                @if (auth()->user()->hasPermissionTo('xem người dùng'))
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
                            <span>Quản lý quyền</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('xem dự án') || auth()->user()->hasPermissionTo('xem toàn bộ dự án'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['admin.projects.show', 'admin.projects.index', 'admin.projects.create', 'admin.projects.edit', 'admin.projects.tasks', 'admin.projects.tasks.edit', 'admin.tasks.create', 'admin.projects.tasks.show']) ? '' : 'collapsed' }}"
                            href="{{ route('admin.projects.index') }}">
                            <i class="bi bi-kanban"></i>
                            <span>Dự án</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('xem thiết bị'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['thietbi.show', 'thietbi.index', 'thietbi.create', 'thietbi.edit', 'thietbi.show']) ? '' : 'collapsed' }}"
                            href="{{ route('thietbi.index') }}">
                            <i class="bi bi-pc-display"></i>
                            <span>Quản lý thiết bị</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('xem hợp đồng') || auth()->user()->hasPermissionTo('xem toàn bộ hợp đồng'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['admin.employee-contracts.index', 'admin.employee-contracts.create', 'admin.employee-contracts.view', 'admin.employee-contracts.edit']) ? '' : 'collapsed' }}"
                            href="{{ route('admin.employee-contracts.index') }}">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Xem hợp đồng</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('toàn bộ chấm công') || auth()->user()->hasPermissionTo('xem chấm công'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['admin.chamcong.index']) ? '' : 'collapsed' }}"
                            href="{{ route('admin.chamcong.index') }}">
                            <i class="bi bi-clock me-2"></i>
                            <span>Chấm công</span>
                        </a>
                    </li>
                @endif



                @if (auth()->user()->hasPermissionTo('xem khách hàng') ||
                        auth()->user()->hasPermissionTo('xem hợp đồng dự án') ||
                        auth()->user()->hasPermissionTo('xem hỗ trợ khách hàng') ||
                        auth()->user()->hasPermissionTo('xem toàn bộ hỗ trợ khách hàng'))
                    <li class="nav-heading">Khách hàng</li>
                @endif

                <!-- End Profile Page Nav -->
                @if (auth()->user()->hasPermissionTo('xem khách hàng'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['khach-hangs.index', 'khach-hangs.create', 'khach-hangs.edit']) ? '' : 'collapsed' }}"
                            href="{{ route('khach-hangs.index') }}">
                            <i class="bi bi-people me-2"></i>
                            <span>Khách hàng</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('xem hợp đồng dự án'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['hop_dong_khach_hang.index', 'hop_dong_khach_hang.create', 'hop_dong_khach_hang.edit']) ? '' : 'collapsed' }}"
                            href="{{ route('hop_dong_khach_hang.index') }}">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Hợp đồng dự án</span>
                        </a>
                    </li><!-- End F.A.Q Page Nav -->
                @endif
                @if (auth()->user()->hasPermissionTo('xem hỗ trợ khách hàng') ||
                        auth()->user()->hasPermissionTo('xem toàn bộ hỗ trợ khách hàng'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['ho_tro_khach_hangs.index', 'ho_tro_khach_hangs.create', 'ho_tro_khach_hangs.edit']) ? '' : 'collapsed' }}"
                            href="{{ route('ho_tro_khach_hangs.index') }}">
                            <i class="bi bi-headset"></i>
                            <span>Hỗ trợ khách hàng</span>
                        </a>
                    </li><!-- End F.A.Q Page Nav -->
                @endif

                {{-- DOMAIN, HOSTING, SERVER --}}
                @if (auth()->user()->hasPermissionTo('xem domain') ||
                        auth()->user()->hasPermissionTo('xem hosting') ||
                        auth()->user()->hasPermissionTo('xem server'))
                    <li class="nav-heading">domain</li>
                @endif
                @if (auth()->user()->hasPermissionTo('xem domain'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['domains.index', 'domains.create', 'domains.edit']) ? '' : 'collapsed' }}"
                            href="{{ route('domains.index') }}">
                            <i class="bi bi-postcard"></i>
                            <span>Domains</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('xem hosting'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['hostings.index', 'hostings.create', 'hostings.edit']) ? '' : 'collapsed' }}"
                            href="{{ route('hostings.index') }}">
                            <i class="bi bi-cpu"></i>
                            <span>Hostings</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('xem server'))
                    <li class="nav-item">
                        <a class="nav-link {{ in_array(Request::route()->getName(), ['servers.index', 'servers.create', 'servers.edit']) ? '' : 'collapsed' }}"
                            href="{{ route('servers.index') }}">
                            <i class="bi bi-hdd-stack"></i>
                            <span>Servers</span>
                        </a>
                    </li>
                @endif
                {{-- End F.A.Q Page Nav
                <li class="nav-heading">Hợp đồng</li>
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
                </li><!-- End Blank Page Nav --> --}}

            </ul>
            <div class="text-white text-center py-3 w-100 border-top-clock shadow " style="background-color: #F05729">
                🕒 <span id="realtime-clock" style="font-weight: 900;">--:--:--</span>
            </div>
        </aside><!-- End Sidebar-->
    @endif
    <main id="main" class="main">
        @yield('body')
    </main>
    @if (!($no_layout ?? false))
        <footer id="footer" class="footer">
            <div class="copyright">
                &copy; Bản quyền <strong><span>Metasoft</span></strong>. Mọi quyền được bảo lưu
            </div>
            <div class="credits">

                Thiết kế bời <a href="https://www.facebook.com/leduykhanh309/">Lê Duy Khánh Metasoft</a>
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
    <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.4/dist/index.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/medium-zoom@1.1.0/dist/medium-zoom.min.js"></script>
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
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ asset('/source/js/select2.min.js') }}"></script>
    <script src="{{ asset('/source/js/style.js') }}"></script>
</body>

</html>
