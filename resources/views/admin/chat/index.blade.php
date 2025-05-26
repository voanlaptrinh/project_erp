@extends('welcome')

@section('body')
    <div class="container">
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
                            @foreach ($groups as $group)
                                <a href="{{ route('chat.show', $group) }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        @if ($group->is_group)
                                            <strong>{{ $group->name }}</strong>
                                        @else
                                            @foreach ($group->users as $user)
                                                @if ($user->id != Auth::id())
                                                    {{ $user->name }}
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    @if ($group->unreadCount > 0)
                                        <span class="badge bg-primary rounded-pill">{{ $group->unreadCount }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Select a chat to start messaging</h5>
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                        <p class="text-muted">Choose a conversation from the list or create a new group</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Group Modal -->
    <div class="modal fade" id="newGroupModal" tabindex="-1" aria-labelledby="newGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('chat.create-group') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="newGroupModalLabel">Create New Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="groupName" class="form-label">Group Name</label>
                            <input type="text" class="form-control" id="groupName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="groupMembers" class="form-label">Add Members</label>
                            <select name="users[]" id="groupMembers" class="form-select" multiple required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
