@extends('layouts.admin')

@section('content')
    <section class="bg-pink-100 min-h-screen pt-20 px-6 pl-[270px] flex flex-col">

        <!-- Header -->
        <div class="p-6 bg-white rounded-t-2xl mt-3">
            <h2 class="text-2xl font-semibold text-pink-600">
                <i class="fa-solid fa-comment-dots mr-2"></i>Chats
            </h2>
        </div>

        <!-- Content (user list + chat) -->
        <div class="flex flex-1 gap-3  min-h-0">

            <!-- User List -->
            <div class="w-1/5 bg-white p-4 rounded-bl-2xl shadow-md overflow-y-auto" style="height: calc(100vh - 190px)">
                <h4 class="font-semibold text-lg mb-4 text-pink-600">Customers</h4>
                <ul id="userList" class="space-y-2">
                    @foreach ($users as $user)
                        <li class="user-item cursor-pointer py-2 px-3 rounded-lg hover:bg-pink-100 transition relative"
                            data-id="{{ $user->id }}" data-name="{{ $user->name }}">

                            <div class="flex items-start gap-3">
                                {{-- Avatar --}}
                                @if ($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar"
                                        class="w-10 h-10 rounded-full object-cover border border-gray-300">
                                @else
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-circle text-pink-600 text-[36px]"></i>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-gray-800 truncate">{{ $user->name }}</span>

                                        {{-- Badge --}}
                                        @if ($user->unread_count > 0)
                                            <span
                                                class="badge bg-pink-500 text-white text-xs font-semibold rounded-full px-2 py-0.5 shadow unread-badge"
                                                data-user-id="{{ $user->id }}">
                                                {{ $user->unread_count }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 truncate">{{ $user->last_message }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Chat Box -->
            <div class="w-4/5 bg-white p-4 rounded-br-2xl shadow-md flex flex-col" style="height: calc(100vh - 190px)">
                <div class=" mb-4 border-b pb-3">
                    <div>
                        <p class="text-sm text-gray-500">Chatting with</p>
                        <h4 class="font-semibold text-lg text-pink-600" id="chatUserName">-</h4>
                    </div>
                </div>

                <div id="chat-box" class="border rounded p-3 overflow-y-auto bg-gray-50 mb-3 flex-1 min-h-0">
                    <div id="messages"></div>
                </div>

                <form id="chat-form" class="flex items-center gap-2">
                    <div class="flex-grow relative">
                        <input type="text" id="messageInput"
                            class="w-full border border-gray-200 bg-gray-100 text-gray-400 px-4 py-2 pl-4 pr-12 rounded-full focus:outline-none shadow-sm transition"
                            placeholder="Select a user to start chatting..." disabled>
                    </div>
                    <button type="submit" id="sendButton"
                        class="bg-gray-300 text-gray-500 px-5 py-2 rounded-full shadow-inner transition duration-200 cursor-not-allowed"
                        disabled>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <style>
        .active-user {
            background-color: #fbcfe8;
            /* Tailwind bg-pink-200 */
        }
    </style>


    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        let currentUserId = null;
        let currentUserName = null;
        const adminId = {{ auth()->id() }};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        document.querySelectorAll('.user-item').forEach(item => {
            item.addEventListener('click', function() {
                const userId = this.dataset.id;
                const userName = this.dataset.name;

                document.getElementById('chatUserName').textContent = userName;

                const messageInput = document.getElementById('messageInput');
                messageInput.disabled = false;
                messageInput.classList.remove('bg-gray-100', 'text-gray-400');
                messageInput.classList.add('bg-white', 'text-gray-800');
                messageInput.value = '';
                messageInput.placeholder = 'Type your message...'; 

                const sendButton = document.getElementById('sendButton');
                sendButton.disabled = false;
                sendButton.classList.remove('bg-pink-300', 'cursor-not-allowed');
                sendButton.classList.add('bg-pink-500', 'hover:bg-pink-600');
                sendButton.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed',
                    'shadow-inner');
                sendButton.classList.add('bg-pink-500', 'hover:bg-pink-600', 'text-white', 'shadow-md');
            });
        });


        $('.user-item').on('click', function() {
            $('.user-item').removeClass('active-user');
            $(this).addClass('active-user');

            currentUserId = $(this).data('id');
            currentUserName = $(this).data('name');
            $('#chatUserName').text(currentUserName);

            // HILANGKAN BADGE
            $(`.unread-badge[data-user-id="${currentUserId}"]`).remove();

            subscribeToUserChannel(currentUserId);
            loadMessages();
        });

        $('#chat-form').on('submit', function(e) {
            e.preventDefault();
            const message = $('#messageInput').val();
            if (!message || !currentUserId) return;

            $.post(`/admin/chat/send-message`, {
                message: message,
                receiver_id: currentUserId
            }, function() {
                $('#messageInput').val('');
                loadMessages();
            });
        });


        function loadMessages() {
            $.get(`/admin/chat/fetch-messages/${currentUserId}`, function(messages) {
                $('#messages').html('');
                messages.forEach(msg => {
                    const isAdmin = msg.sender_role === 'admin';
                    const align = isAdmin ? 'justify-end' : 'justify-start';
                    const bubbleAlign = 'items-start';
                    const bubbleColor = isAdmin ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-900';


                    const avatar = msg.sender_profile_photo ?
                        `<img src="/storage/${msg.sender_profile_photo}" alt="Profile" class="w-10 h-10 rounded-full">` :
                        `<div class="w-10 h-10 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-circle text-pink-600 text-[36px]"></i>
                    </div>`;

                    $('#messages').append(`
                        <div class="flex ${align} mb-3">
                            <div class="flex gap-2 ${bubbleAlign} max-w-md">
                                ${!isAdmin ? avatar : ''}
                                <div>
                                    <p class="text-sm ${isAdmin ? 'text-right' : 'text-left'} font-semibold mb-1">${msg.sender_name}</p>
                                    <div class="relative px-4 py-2 rounded-2xl ${bubbleColor}">
                                        <p class="whitespace-pre-line break-words break-all">${msg.message}</p>
                                    </div>
                                </div>
                                ${isAdmin ? avatar : ''}
                            </div>
                        </div>
                    `);
                });
                scrollToBottom();
            });
        }

        function initEchoListener() {
            if (typeof window.Echo === 'undefined') {
                return setTimeout(initEchoListener, 100);
            }

            const id = {{ auth()->id() }};
            window.Echo.private('chat.' + id)
                .listen('.message.sent', (e) => {
                    console.log('Pesan diterima:', e);

                    if (parseInt(currentUserId) === e.sender_id) {
                        loadMessages(); // sedang dibuka
                    } else {
                        refreshUserList(); // refresh user list + badge
                    }
                });
        }
        initEchoListener();

        let currentChannel = null;

        function subscribeToUserChannel(userId) {
            if (currentChannel) {
                currentChannel.stopListening('.message.sent');
            }

            currentChannel = Echo.private('chat.' + userId)
                .listen('.message.sent', (e) => {
                    console.log('Pesan baru diterima oleh admin:', e);
                    loadMessages();
                });
        }

        function refreshUserList() {
            $.get('/admin/chat/user-list', function(response) {
                $('#userList').html(response);

                // Rebind click handler setelah user list di-refresh
                $('.user-item').on('click', function() {
                    $('.user-item').removeClass('active-user');
                    $(this).addClass('active-user');

                    currentUserId = $(this).data('id');
                    currentUserName = $(this).data('name');
                    $('#chatUserName').text(currentUserName);

                    subscribeToUserChannel(currentUserId);
                    loadMessages();
                });
            });
        }

        function scrollToBottom() {
            const chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    </script>
@endsection
