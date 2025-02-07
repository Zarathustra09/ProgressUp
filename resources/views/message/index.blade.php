@extends('layouts.app')

@section('content')
    <style>
        .chat-list-container {
            height: calc(85vh - 80px);
            overflow-y: auto;
        }
    </style>

    <body class="bg-light min-vh-100 py-4">
    <div class="container-fluid container-lg p-0 bg-white shadow-lg rounded-3 overflow-hidden mx-auto" style="height: 85vh;">
        <div class="p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0">Chats</h5>
            </div>
            <form action="{{ route('admin.message.index') }}" method="GET">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control rounded-pill bg-light border-0" placeholder="Search..." value="{{ request('search') }}">
                    <button class="btn btn-primary rounded-pill ms-2" type="submit">Search</button>
                </div>
            </form>

            <div class="chat-list-container">
                <div class="list-group list-group-flush">
                    @foreach($chats as $chat)
                        <a href="{{ route('admin.message.show', $chat->id) }}" class="list-group-item list-group-item-action py-3 px-3 border-0 rounded-3 mb-2">
                            <div class="d-flex gap-3">
                                <img src="{{ $chat->userOne->profile_image ? Storage::url($chat->userOne->profile_image) : 'https://placehold.co/45x45' }}" class="rounded-circle" width="45" height="45" alt="User">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-truncate">{{ $chat->userOne->first_name }} {{ $chat->userOne->last_name }} & {{ $chat->userTwo->first_name }} {{ $chat->userTwo->last_name }}</h6>
                                        @if($chat->messages->isNotEmpty())
                                            <small>{{ $chat->messages->last()->created_at->diffForHumans() }}</small>
                                        @endif
                                    </div>
                                    @if($chat->messages->isNotEmpty())
                                        <p class="mb-0 text-truncate small">{{ $chat->messages->last()->body }}</p>
                                    @else
                                        <p class="mb-0 text-truncate small">No messages yet</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    </body>
@endsection
