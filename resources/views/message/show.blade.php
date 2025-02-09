@extends('layouts.app')

@section('content')
    <style>
        .messages-container {
            height: calc(85vh - 130px);
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .messages-container::-webkit-scrollbar {
            width: 6px;
        }

        .messages-container::-webkit-scrollbar-thumb {
            background-color: rgba(0,0,0,0.2);
            border-radius: 3px;
        }
    </style>

    <body class="bg-light min-vh-100 py-4">
    <div class="container-fluid container-lg p-0 bg-white shadow-lg rounded-3 overflow-hidden mx-auto" style="height: 85vh;">
        <div class="d-flex flex-column h-100">
            <!-- Chat Header -->
            <div class="px-4 py-3 border-bottom d-flex align-items-center">
                <a href="{{ route('admin.message.index') }}" class="btn d-md-none me-2">
                    <i class="uil uil-arrow-left"></i>
                </a>
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $chat->userOne->profile_image ? Storage::url($chat->userOne->profile_image) : 'https://placehold.co/45x45' }}" class="rounded-circle" width="45" height="45" alt="User">
                    <h6 class="mb-0">{{ $chat->userOne->first_name }} {{ $chat->userOne->last_name }} & {{ $chat->userTwo->first_name }} {{ $chat->userTwo->last_name }}</h6>
                </div>
            </div>

            <!-- Chat Messages -->
            <div id="messagesContainer" class="messages-container p-4 bg-light">
                @foreach($chat->messages as $message)
                    <div class="d-flex {{ $message->sender_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                        <div class="d-flex flex-column {{ $message->sender_id == auth()->id() ? 'align-items-end' : 'align-items-start' }}">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $message->sender->profile_image ? Storage::url($message->sender->profile_image) : 'https://placehold.co/45x45' }}" class="rounded-circle" width="30" height="30" alt="User">
                                <div class="bg-white p-3 rounded-3 shadow-sm">
                                    <strong>{{ $message->sender->first_name }} {{ $message->sender->last_name }}</strong>
                                    @if($message->body)
                                        <p class="mb-0">{{ $message->body }}</p>
                                    @endif
                                    @if($message->attachment)
                                        <img src="{{ Storage::url($message->attachment) }}" class="img-fluid mt-2" alt="Attachment">
                                    @endif
                                </div>
                            </div>
                            <small class="text-muted mt-1">{{ $message->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Chat Input -->
            <div class="mt-auto p-3 bg-white border-top">
                <form id="messageForm" action="{{ route('admin.message.store', $chat->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                    <input type="hidden" name="receiver_id" value="{{ $chat->userOne->id == auth()->id() ? $chat->userTwo->id : $chat->userOne->id }}">
                    <div class="input-group">
                        <input type="text" name="body" class="form-control rounded-pill bg-light border-0" placeholder="Type a message...">
                        <input type="file" id="attachment" name="attachment" class="d-none">
                        <button type="button" class="btn btn-secondary rounded-circle ms-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" onclick="document.getElementById('attachment').click();">
                            <i class="bx bx-paperclip"></i>
                        </button>
                        <button class="btn btn-primary rounded-circle ms-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bx bx-send"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('messagesContainer');
            let lastMessageId = {{ $chat->messages->last()->id ?? 0 }};

            function fetchMessages() {
                fetch('{{ route('admin.message.fetch', $chat->id) }}')
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(message => {
                            if (message.id > lastMessageId) {
                                const messageElement = document.createElement('div');
                                messageElement.classList.add('d-flex', message.sender_id == {{ auth()->id() }} ? 'justify-content-end' : 'justify-content-start', 'mb-3');
                                messageElement.innerHTML = `
                            <div class="d-flex flex-column ${message.sender_id == {{ auth()->id() }} ? 'align-items-end' : 'align-items-start'}">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="${message.sender.profile_image ? '{{ Storage::url('') }}' + message.sender.profile_image : 'https://placehold.co/45x45'}" class="rounded-circle" width="30" height="30" alt="User">
                                    <div class="bg-white p-3 rounded-3 shadow-sm">
                                        <strong>${message.sender.first_name} ${message.sender.last_name}</strong>
                                        ${message.body ? `<p class="mb-0">${message.body}</p>` : ''}
                                        ${message.attachment ? `<img src="{{ Storage::url('') }}${message.attachment}" class="img-fluid mt-2" alt="Attachment">` : ''}
                                    </div>
                                </div>
                                <small class="text-muted mt-1">${new Date(message.created_at).toLocaleString()}</small>
                            </div>
                        `;
                                messagesContainer.appendChild(messageElement);
                                lastMessageId = message.id;
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching messages:', error));
            }

            setInterval(fetchMessages, 5000); // Poll every 5 seconds

            document.getElementById('messageForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const form = event.target;
                const formData = new FormData(form);

                fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        if (response.status === 201) {
                            return response.json();
                        } else {
                            throw new Error('Failed to send message');
                        }
                    })
                    .then(data => {
                        fetchMessages(); // Fetch new messages after sending
                        form.reset(); // Reset the form
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while sending the message');
                    });
            });

            document.getElementById('attachment').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file && !['image/jpeg', 'image/png'].includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid file type',
                        text: 'Please select a JPG or PNG image.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    event.target.value = ''; // Clear the input
                } else {
                    const form = document.getElementById('messageForm');
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: form.method,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (response.status === 201) {
                                return response.json();
                            } else {
                                throw new Error('Failed to send attachment');
                            }
                        })
                        .then(data => {
                            fetchMessages(); // Fetch new messages after sending
                            form.reset(); // Reset the form
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while sending the attachment');
                        });
                }
            });
        });
    </script>
    </body>
@endsection
