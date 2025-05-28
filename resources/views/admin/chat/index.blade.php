@extends('welcome')

@section('body')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-4 col-lg-3 p-0 chat-sidebar bg-white">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <h5 class="mb-0">Tin nhắn</h5>
                    @can('tạo nhóm chat')
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newGroupModal">+
                            Nhóm</button>
                    @endcan
                </div>
                {{-- chat list --}}
                <div class="list-group list-group-flush">
                    @foreach ($groups as $group)
                        <a href="{{ route('chat.index', ['group' => $group->id]) }}"
                            class="list-group-item list-group-item-action d-flex align-items-center chat-item {{ isset($selectedGroup) && $selectedGroup->id == $group->id ? 'active' : '' }}">
                            <div class="chat-avatar">
                                {{ $group->is_group ? strtoupper(substr($group->name, 0, 1)) : substr($group->users->where('id', '!=', Auth::id())->first()->name ?? '?', 0, 1) }}
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <strong>
                                    {{ $group->is_group ? $group->name : $group->users->where('id', '!=', Auth::id())->first()->name ?? 'Unknown' }}
                                </strong>
                                @if ($group->latestMessage)
                                    <div class="text-muted small text-truncate" style="max-width: 200px;">
                                        {{ $group->latestMessage->user_id == Auth::id() ? 'Bạn: ' : '' }}
                                        {{ $group->latestMessage->content ? Str::limit($group->latestMessage->content, 20) : 'Đã gửi 1 tệp đính kèm' }}
                                    </div>
                                @endif
                            </div>
                            @if ($group->unreadCount > 0)
                                <span class="badge bg-primary rounded-pill">{{ $group->unreadCount }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
                {{-- user list --}}
                <div class="border-top p-3">
                    <h6 class="text-muted">Người dùng</h6>
                    @foreach ($users as $user)
                        <a href="{{ route('chat.start-private', $user) }}"
                            class="list-group-item list-group-item-action d-flex align-items-center chat-item mb-2">
                            <div class="chat-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <div>{{ $user->name }}</div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-8 col-lg-9 chat-main bg-light">
                @if (isset($selectedGroup))
                    <div class="card h-100 d-flex flex-column">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">
                                    {{ $selectedGroup->is_group ? $selectedGroup->name : $selectedGroup->users->where('id', '!=', Auth::id())->first()->name ?? 'Chat' }}
                                </h5>
                                <small class="text-muted typing-indicator" style="display: none;">
                                    <span class="typing-user"></span> đang soạn...
                                </small>
                            </div>
                            @if ($selectedGroup->is_group)
                                <div>
                                    @can('cập nhật nhóm chat')
                                        <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal"
                                            data-bs-target="#addUserModal">
                                            <i class="fas fa-user-plus"></i> Thêm thành viên
                                        </button>
                                    @endcan
                                    @can('cập nhật nhóm chat')
                                        <button class="btn btn-sm btn-outline-secondary me-2" data-bs-toggle="modal"
                                            data-bs-target="#membersModal">
                                            <i class="fas fa-users"></i> Thành viên
                                        </button>
                                    @endcan
                                    @can('xóa nhóm chat')
                                        <form action="{{ route('chat.destroy', $selectedGroup) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa nhóm này?')">
                                                <i class="fas fa-trash"></i> Xóa nhóm
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            @endif
                        </div>
                        {{-- chat messages --}}
                        <div class="card-body chat-messages flex-grow-1" style="overflow-y: auto;">
                            @foreach ($messages as $message)
                                <div
                                    class="message mb-3 d-flex {{ $message->user_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">

                                    <div class="{{ $message->user_id == Auth::id() ? 'text-black' : 'text-muted' }} p-3 rounded"
                                        style="max-width: 70%;">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small
                                                class="{{ $message->user_id == Auth::id() ? 'text-black-50' : 'text-muted' }}">
                                                {{ $message->user_id == Auth::id() ? 'Bạn' : $message->sender->name }}
                                            </small>
                                            <small
                                                class="{{ $message->user_id == Auth::id() ? 'text-white-50' : 'text-muted' }}">
                                                &nbsp; {{ $message->created_at->format('h:i A') }}
                                            </small>
                                        </div>
                                        @if ($message->content)
                                            <p class="{{ $message->user_id == Auth::id() ? 'bg-primary text-white' : 'bg-white text-muted' }} p-3 rounded"
                                                style="max-width: 100%;">{{ $message->content }}</p>
                                        @endif
                                        @if ($message->attachment)
                                            <div class="mt-2 d-flex flex-wrap gap-2">
                                                @php
                                                    $attachments = json_decode($message->attachment);
                                                    if (!is_array($attachments)) {
                                                        $attachments = [$message->attachment];
                                                    }
                                                @endphp
                                                @foreach ($attachments as $index => $attachment)
                                                    <div class="image-container">
                                                        <img src="{{ asset('storage/' . $attachment) }}" alt="attachment"
                                                            class="chat-image"
                                                            data-full="{{ asset('storage/' . $attachment) }}"
                                                            data-sender="{{ $message->user_id == Auth::id() ? 'Bạn' : $message->sender->name }}"
                                                            data-time="{{ $message->created_at->format('h:i A, d/m/Y') }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                            <!-- Overlay zoom ảnh -->
                            <div id="imageOverlay" class="image-overlay">
                                <span class="close-btn">&times;</span>
                                <div class="image-container">
                                    <img id="expandedImage" class="expanded-image">
                                    <div class="image-info">
                                        <span id="imageSender"></span>
                                        <span id="imageTime"></span>
                                    </div>
                                    <a id="downloadBtn" class="download-btn" download>
                                        <i class="fas fa-download"></i> Tải xuống
                                    </a>
                                </div>
                            </div>

                        </div>


                        <!-- Trong phần card-footer -->
                        <div class="card-footer p-3 bg-light border-top">
                            <!-- Khu vực hiển thị preview ảnh -->
                            <div id="image-preview" class="mb-2 d-flex flex-wrap gap-2"></div>

                            <form id="chatForm" action="{{ route('messages.store', $selectedGroup) }}" method="POST"
                                enctype="multipart/form-data" class="message-form">
                                @csrf
                                <div class="input-group">
                                    <!-- Nút emoji -->
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                        data-bs-target="#emojiModal" title="Chọn biểu tượng cảm xúc">
                                        <i class="far fa-smile"></i>
                                    </button>

                                    <!-- Ô nhập tin nhắn -->
                                    <input type="text" name="content" id="messageInput" class="form-control"
                                        placeholder="Aa" autocomplete="off">

                                    <!-- Nút đính kèm và gửi -->
                                    <div class="input-group-append d-flex align-items-center">
                                        <!-- Thay đổi input file để chấp nhận nhiều file -->
                                        <label class="btn btn-outline-secondary mb-0" title="Đính kèm tập tin">
                                            <i class="fas fa-paperclip"></i>
                                            <input type="file" name="attachments[]" id="attachmentInput" multiple
                                                hidden>
                                        </label>

                                        <!-- Nút gửi -->
                                        <button type="submit" class="btn btn-primary ms-2">Gửi</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                        <i class="fas fa-comments fa-4x mb-3 text-muted"></i>
                        <p class="lead text-muted">Chọn một cuộc trò chuyện để bắt đầu</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Tạo Nhóm Mới -->
    <div class="modal fade" id="newGroupModal" tabindex="-1" aria-labelledby="newGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('chat.create-group') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="newGroupModalLabel">Tạo Nhóm Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="groupName" class="form-label">Tên nhóm</label>
                            <input type="text" class="form-control" id="groupName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="groupMembers" class="form-label">Chọn thành viên</label>
                            <select name="users[]" id="groupMembers" class="form-select" multiple required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Tạo nhóm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Thành Viên -->
    @if (isset($selectedGroup) && $selectedGroup->is_group)
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-sm rounded-3">
                    <form action="{{ route('chat.add-users', $selectedGroup) }}" method="POST">
                        @csrf
                        <div class="modal-header border-bottom">
                            <h5 class="modal-title fw-semibold" id="addUserModalLabel">➕ Thêm thành viên vào nhóm</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="addMembers" class="form-label">Chọn thành viên</label>
                                <select name="users[]" id="addMembers" class="form-select" multiple required>
                                    @foreach ($users->whereNotIn('id', $selectedGroup->users->pluck('id')) as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Giữ Ctrl (hoặc Cmd trên macOS) để chọn nhiều thành viên.</div>
                            </div>
                        </div>

                        <div class="modal-footer border-top">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Hủy
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus me-1"></i> Thêm thành viên
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Danh sách thành viên -->
        <div class="modal fade" id="membersModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thành viên nhóm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            @foreach ($selectedGroup->users as $user)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="chat-avatar d-inline-block d-flex align-items-center me-2">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        {{ $user->name }}
                                        @if ($user->id === Auth::id())
                                            <span class="badge bg-primary ms-2">Bạn</span>
                                        @endif
                                    </div>
                                    @if ($user->id !== Auth::id() && Gate::allows('cập nhật nhóm chat', $selectedGroup))
                                        <form action="{{ route('chat.remove-user', [$selectedGroup, $user]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Xóa {{ $user->name }} khỏi nhóm?')">
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thêm modal emoji picker (đặt cùng cấp với các modal khác) -->
        <div class="modal fade" id="emojiModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn biểu tượng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="emoji-container">
                            <!-- Danh sách emoji sẽ được thêm bằng JavaScript -->
                            <div class="emoji-category mb-3">
                                <h6>Smileys & People</h6>
                                <div class="emoji-grid" data-category="smileys"></div>
                            </div>
                            <div class="emoji-category mb-3">
                                <h6>Animals & Nature</h6>
                                <div class="emoji-grid" data-category="animals"></div>
                            </div>
                            <div class="emoji-category mb-3">
                                <h6>Food & Drink</h6>
                                <div class="emoji-grid" data-category="food"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Danh sách emoji
        const emojiCategories = {
            smileys: ["😀", "😃", "😄", "😁", "😆", "😅", "😂", "🤣", "😊", "😇", "🙂", "🙃", "😉", "😌", "😍", "🥰",
                "😘", "😗", "😙", "😚", "😋", "😛", "😝", "😜", "🤪", "🤨", "🧐", "🤓", "😎", "🥸", "🤩", "🥳",
                "😏", "😒", "😞", "😔", "😟", "😕", "🙁", "☹️", "😣", "😖", "😫", "😩", "🥺", "😢", "😭", "😤",
                "😠", "😡", "🤬", "🤯", "😳", "🥵", "🥶", "😱", "😨", "😰", "😥", "😓", "🤗", "🤔", "🤭", "🤫",
                "🤥", "😶", "😐", "😑", "😬", "🙄", "😯", "😦", "😧", "😮", "😲", "🥱", "😴", "🤤", "😪", "😵",
                "🤐", "🥴", "🤢", "🤮", "🤧", "😷", "🤒", "🤕", "🤑", "🤠", "😈", "👿", "👹", "👺", "🤡", "💩",
                "👻", "💀", "☠️", "👽", "👾", "🤖", "🎃", "😺", "😸", "😹", "😻", "😼", "😽", "🙀", "😿", "😾"
            ],
            animals: ["🐶", "🐱", "🐭", "🐹", "🐰", "🦊", "🐻", "🐼", "🐨", "🐯", "🦁", "🐮", "🐷", "🐽", "🐸", "🐵",
                "🙈", "🙉", "🙊", "🐒", "🐔", "🐧", "🐦", "🐤", "🐣", "🐥", "🦆", "🦅", "🦉", "🦇", "🐺", "🐗",
                "🐴", "🦄", "🐝", "🐛", "🦋", "🐌", "🐞", "🐜", "🦟", "🦗", "🕷", "🦂", "🐢", "🐍", "🦎", "🦖",
                "🦕", "🐙", "🦑", "🦐", "🦞", "🦀", "🐡", "🐠", "🐟", "🐬", "🐳", "🐋", "🦈", "🐊", "🐅", "🐆",
                "🦓", "🦍", "🦧", "🦣", "🐘", "🦛", "🦏", "🐪", "🐫", "🦒", "🦘", "🦬", "🐃", "🐂", "🐄", "🐎",
                "🐖", "🐏", "🐑", "🦙", "🐐", "🦌", "🐕", "🐩", "🦮", "🐕‍🦺", "🐈", "🐈‍⬛", "🐓", "🦃", "🦚", "🦜",
                "🦢", "🦩", "🕊", "🐇", "🦝", "🦨", "🦡", "🦫", "🦦", "🦥", "🐁", "🐀", "🐿", "🦔"
            ],
            food: ["🍏", "🍎", "🍐", "🍊", "🍋", "🍌", "🍉", "🍇", "🍓", "🫐", "🍈", "🍒", "🍑", "🥭", "🍍", "🥥", "🥝",
                "🍅", "🍆", "🥑", "🥦", "🥬", "🥒", "🌶", "🫑", "🌽", "🥕", "🫒", "🧄", "🧅", "🥔", "🍠", "🥐",
                "🥯", "🍞", "🥖", "🥨", "🧀", "🥚", "🍳", "🧈", "🥞", "🧇", "🥓", "🥩", "🍗", "🍖", "🦴", "🌭",
                "🍔", "🍟", "🍕", "🫓", "🥪", "🥙", "🧆", "🌮", "🌯", "🫔", "🥗", "🥘", "🫕", "🥫", "🍝", "🍜",
                "🍲", "🍛", "🍣", "🍱", "🥟", "🦪", "🍤", "🍙", "🍚", "🍘", "🍥", "🥠", "🥮", "🍢", "🍡", "🍧",
                "🍨", "🍦", "🥧", "🧁", "🍰", "🎂", "🍮", "🍭", "🍬", "🍫", "🍿", "🍩", "🍪", "🌰", "🥜", "🫘", "🍯"
            ]
        };

        // Khởi tạo emoji picker
        document.addEventListener('DOMContentLoaded', function() {
            // Tải emoji vào modal
            const emojiGrids = document.querySelectorAll('.emoji-grid');
            emojiGrids.forEach(grid => {
                const category = grid.dataset.category;
                emojiCategories[category].forEach(emoji => {
                    const emojiItem = document.createElement('div');
                    emojiItem.className = 'emoji-item';
                    emojiItem.textContent = emoji;
                    emojiItem.addEventListener('click', function(e) {
                        const messageInput = document.getElementById('messageInput');
                        messageInput.value += emoji;
                        messageInput.focus();
                    });
                    grid.appendChild(emojiItem);
                });
            });

            // Tự động cuộn xuống tin nhắn mới nhất
            const chatMessages = document.querySelector('.chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

        });

        document.addEventListener('DOMContentLoaded', function() {
            let chatInput = document.querySelector('.message-form input[name="content"]');
            if (chatInput) chatInput.focus();

            const cancelBtn = document.querySelector('#addUserModal .btn-secondary');
            cancelBtn?.addEventListener('click', (e) => {
                console.log('Nút Hủy được bấm, không submit.');
            });

            const form = document.querySelector('#addUserModal form');
            form?.addEventListener('submit', () => {
                console.log('Form ĐƯỢC gửi');
            });

            const chatMessages = document.querySelector('.chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Hiển thị ảnh preview khi chọn file
            const attachmentInput = document.getElementById('attachmentInput');
            const imagePreview = document.getElementById('image-preview');
            if (attachmentInput && imagePreview) {
                attachmentInput.addEventListener('change', function(event) {
                    imagePreview.innerHTML = '';
                    const files = event.target.files;

                    if (files.length > 0) {
                        // Hiển thị số lượng ảnh đã chọn
                        const countBadge = document.createElement('span');
                        countBadge.className = 'badge bg-secondary mb-2';
                        countBadge.textContent = `Đã chọn ${files.length} ảnh`;
                        imagePreview.appendChild(countBadge);

                        // Hiển thị preview từng ảnh
                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];
                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const previewWrapper = document.createElement('div');
                                    previewWrapper.className =
                                        'image-preview-wrapper position-relative';
                                    previewWrapper.style.width = '100px';
                                    previewWrapper.innerHTML = `
                            <img src="${e.target.result}" class="img-thumbnail" alt="preview">
                            <button type="button" class="btn-close position-absolute top-0 end-0 bg-white" 
                                data-index="${i}" title="Xóa ảnh"></button>
                        `;
                                    imagePreview.appendChild(previewWrapper);

                                    // Thêm sự kiện cho nút xóa ảnh
                                    const removeBtn = previewWrapper.querySelector('.btn-close');
                                    removeBtn.addEventListener('click', function() {
                                        removeImageFromList(i);
                                    });
                                };
                                reader.readAsDataURL(file);
                            }
                        }
                    }
                });
            }

            // Hàm xóa ảnh khỏi danh sách
            function removeImageFromList(index) {
                const input = document.getElementById('attachmentInput');
                const files = Array.from(input.files);
                files.splice(index, 1);

                // Tạo DataTransfer mới và thêm các file còn lại
                const dataTransfer = new DataTransfer();
                files.forEach(file => dataTransfer.items.add(file));
                input.files = dataTransfer.files;

                // Render lại preview
                const event = new Event('change');
                input.dispatchEvent(event);
            }

            const overlay = document.getElementById('imageOverlay');
            const expandedImg = document.getElementById('expandedImage');
            const closeBtn = document.querySelector('.close-btn');
            const imageSender = document.getElementById('imageSender');
            const imageTime = document.getElementById('imageTime');
            const downloadBtn = document.getElementById('downloadBtn');

            // Mở overlay khi click vào ảnh
            document.querySelectorAll('.chat-image').forEach(img => {
                img.addEventListener('click', function() {
                    expandedImg.src = this.dataset.full;
                    expandedImg.classList.remove('zoomed');
                    imageSender.textContent = this.dataset.sender;
                    imageTime.textContent = this.dataset.time;
                    downloadBtn.href = this.dataset.full;
                    overlay.classList.add('show');
                    document.body.style.overflow = 'hidden'; // Ngăn scroll khi overlay mở
                });
            });

            // Đóng overlay
            closeBtn.addEventListener('click', function() {
                overlay.classList.remove('show');
                document.body.style.overflow = 'auto';
            });

            // Đóng khi click bên ngoài ảnh
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }
            });

            // Zoom in/out khi click vào ảnh
            expandedImg.addEventListener('click', function(e) {
                e.stopPropagation();
                this.classList.toggle('zoomed');
            });

            // Đóng bằng phím ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && overlay.classList.contains('show')) {
                    overlay.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }
            });
        });
    </script>
@endsection
