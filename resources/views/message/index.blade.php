@extends('layouts.app')
@section('content')
    <style>
        .chat-container {
            height: calc(100vh - 2rem);
            max-height: 900px;
            background-color: #f8f9fa;
        }
        .chat-list {
            height: calc(100vh - 220px);
            overflow-y: auto;
        }
        .chat-item {
            transition: background-color 0.2s ease;
        }
        .chat-item:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }
        .search-input {
            border-radius: 20px;
            border: 1px solid #dee2e6;
        }
        .search-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            border-color: #86b7fe;
        }
        .profile-image {
            width: 48px;
            height: 48px;
            object-fit: cover;
        }
        .message-preview {
            color: #6c757d;
            font-size: 0.875rem;
        }
        .timestamp {
            font-size: 0.75rem;
            color: #6c757d;
        }
    </style>

    <div class="py-3">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-3">Chats</h5>
                    <form action="{{ route('admin.message.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text"
                                   name="search"
                                   class="form-control search-input"
                                   placeholder="Search conversations..."
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary ms-2 rounded-pill px-4" type="submit">Search</button>
                        </div>
                    </form>
                </div>

                <div class="chat-list">
                    @foreach($chats as $chat)
                        <a href="{{ route('admin.message.show', $chat->id) }}" class="text-decoration-none">
                            <div class="chat-item p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $chat->userOne->profile_image ? Storage::url($chat->userOne->profile_image) : 'https://placehold.co/45x45' }}"
                                         class="profile-image rounded-circle"
                                         alt="Profile image">

                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 text-truncate text-dark">
                                                {{ $chat->userOne->first_name }} {{ $chat->userOne->last_name }} &
                                                {{ $chat->userTwo->first_name }} {{ $chat->userTwo->last_name }}
                                            </h6>
                                            @if($chat->messages->isNotEmpty())
                                                <small class="timestamp">{{ $chat->messages->last()->created_at->diffForHumans() }}</small>
                                            @endif
                                        </div>
                                        <p class="mb-0 message-preview text-truncate">
                                            @if($chat->messages->isNotEmpty())
                                                {{ $chat->messages->last()->body }}
                                            @else
                                                No messages yet
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
