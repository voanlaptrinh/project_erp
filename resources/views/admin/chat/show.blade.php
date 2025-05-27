@extends('welcome')

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Chats</h5>
                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newGroupModal">
                            New Group
                        </a>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach (Auth::user()->messageGroups as $chatGroup)
                                <a href="{{ route('chat.show', $chatGroup) }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $chatGroup->id == $group->id ? 'active' : '' }}">
                                    <div>
                                        @if ($chatGroup->is_group)
                                            <strong>{{ $chatGroup->name }}</strong>
                                        @else
                                            @foreach ($chatGroup->users as $user)
                                                @if ($user->id != Auth::id())
                                                    {{ $user->name }}
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    @if ($chatGroup->unreadCount > 0)
                                        <span class="badge bg-primary rounded-pill">{{ $chatGroup->unreadCount }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            @if ($group->is_group)
                                <h5 class="mb-0">{{ $group->name }}</h5>
                                <small class="text-muted">
                                    Members: {{ $group->users->pluck('name')->implode(', ') }}
                                </small>
                            @else
                                <h5 class="mb-0">
                                    @foreach ($group->users as $user)
                                        @if ($user->id != Auth::id())
                                            {{ $user->name }}
                                        @endif
                                    @endforeach
                                </h5>
                            @endif
                        </div>
                        @if ($group->is_group)
                            <div>
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                    data-bs-target="#addMembersModal">
                                    Add Members
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="card-body chat-messages" style="height: 400px; overflow-y: auto;">
                        @foreach ($messages as $message)
                            <div
                                class="message mb-3 d-flex {{ $message->user_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="{{ $message->user_id == Auth::id() ? 'bg-primary text-white' : 'bg-light' }} p-3 rounded"
                                    style="max-width: 70%;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small
                                            class="{{ $message->user_id == Auth::id() ? 'text-white-50' : 'text-muted' }}">
                                            {{ $message->sender->name }}
                                        </small>
                                        <small
                                            class="{{ $message->user_id == Auth::id() ? 'text-white-50' : 'text-muted' }}">
                                            &nbsp; {{ $message->created_at->format('h:i A') }}
                                        </small>
                                    </div>
                                    @if ($message->content)
                                        <p class="mb-1">{{ $message->content }}</p>
                                    @endif
                                    @if ($message->attachment)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $message->attachment) }}" alt="attachment"
                                                class="img-fluid mt-2" style="max-height:200px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer">
                        <form action="{{ route('messages.store', $group) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="content" class="form-control"
                                    placeholder="Type your message...">
                                <div class="input-group-append">
                                    <label class="btn btn-outline-secondary">
                                        <i class="fas fa-paperclip"></i>
                                        <input type="file" name="attachment" style="display: none;">
                                    </label>
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($group->is_group)
        <!-- Add Members Modal -->
        <div class="modal fade" id="addMembersModal" tabindex="-1" aria-labelledby="addMembersModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('chat.add-users', $group) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addMembersModalLabel">Add Members to Group</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="newMembers" class="form-label">Select Members</label>
                                <select name="users[]" id="newMembers" class="form-select" multiple required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Members</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        // Auto-scroll to bottom of chat messages
        const chatMessages = document.querySelector('.chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    </script>
@endpush
