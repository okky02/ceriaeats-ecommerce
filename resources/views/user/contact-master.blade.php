@extends('layouts.user')

@section('content')
    <section class="flex-grow bg-pink-50 flex items-center">
        <div class="mx-auto w-full max-w-screen-xl py-10 px-4 lg:px-6">
            <!-- Heading -->
            <div class="mb-10 text-center">
                <h2 class="text-4xl font-extrabold text-pink-600">Contact Us</h2>
                <p class="mt-2 text-lg text-gray-600">Jangan ragu untuk menghubungi kami jika Anda memiliki pertanyaan atau umpan balik, Tim kami siap membantu Anda.
                </p>
            </div>

            <!-- Contact Form and Info -->
            <div class="flex flex-col lg:flex-row items-start gap-8">

                <!-- Contact Form -->
                <div class="w-full lg:w-2/3 bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-semibold mb-3"> <i class="fa-solid fa-comment-dots text-pink-600 mr-2"></i>Live
                        Chat with Admin</h3>

                    <div class="flex gap-4">
                        <!-- Chat Box -->
                        <div class="w-full bg-white rounded-2xl flex flex-col">
                            <div id="chat-box" class="border rounded p-3 h-[360px] overflow-y-auto bg-gray-50 mb-3">
                                <div id="messages"></div>
                            </div>

                            <form id="chat-form" class="flex items-center gap-2">
                                @csrf
                                <div class="flex-grow relative">
                                    <input type="text" id="message"
                                        class="w-full border border-gray-300 px-4 py-2 pl-4 pr-12 rounded-full focus:outline-none focus:ring-2 focus:ring-pink-400 shadow-sm"
                                        placeholder="Type your message..." required>
                                </div>
                                <button type="submit"
                                    class="bg-pink-500 hover:bg-pink-600 text-white px-5 py-2 rounded-full shadow-md transition duration-200">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>

                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="w-full lg:w-1/3 bg-white p-6 rounded-2xl shadow-md space-y-6">
                    <div class="flex items-start gap-4">
                        <i class="fas fa-map-marker-alt text-pink-500 text-xl"></i>
                        <div>
                            <h4 class="text-gray-800 font-semibold">Address</h4>
                            <p class="text-sm text-gray-600">
                                <a href="https://www.google.com/maps?q=Bekasi+Timur+Regensi,+Jl.+Murai+8,+Blok+H+19+no+49,+Kota+Bekasi,+Jawa+Barat+17151"
                                    class="hover:text-pink-400 transition-colors" target="_blank" rel="noopener noreferrer">
                                    Bekasi Timur Regensi, Jl. Murai 8, Blok H 19 no 49, Kota Bekasi, Jawa Barat 17151
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <i class="fas fa-phone-alt text-pink-500 text-xl"></i>
                        <div>
                            <h4 class="text-gray-800 font-semibold">Phone</h4>
                            <a href="tel:+6281295264742"
                                class="text-sm text-gray-600 hover:text-pink-400 transition-colors">0812-9526-4742</a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <i class="fas fa-envelope text-pink-500 text-xl"></i>
                        <div>
                            <h4 class="text-gray-800 font-semibold">Email</h4>
                            <a href="mailto:admin@ceriaeats.com"
                                class="text-sm text-gray-600 hover:text-pink-400  transition-colors">admin@ceriaeats.com</a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <i class="fas fa-clock text-pink-500 text-xl"></i>
                        <div>
                            <h4 class="text-gray-800 font-semibold">Working Hours</h4>
                            <p class="text-sm text-gray-600">Mon - Fri: 9 AM - 9 PM</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        const userId = @json(Auth::id());
        const userName = @json(auth()->user()->name);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $(document).ready(function() {
            loadMessages();
        });

        $('#chat-form').on('submit', function(e) {
            e.preventDefault();
            const message = $('#message').val();
            if (!message) return;

            $.post(`/user/chat/send-message`, {
                message: message,
            }, function() {
                $('#message').val('');
            });
        });

        function loadMessages() {
            $.get(`/user/chat/fetch-messages`, function(messages) {
                $('#messages').html('');
                messages.forEach(msg => {
                    const isUser = msg.sender_name === 'You';
                    const align = isUser ? 'justify-end' : 'justify-start';
                    const bubbleAlign = 'items-start';
                    const bubbleColor = isUser ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-900';

                    const avatar = msg.sender_profile_photo ?
                        `<img src="/storage/${msg.sender_profile_photo}" alt="Profile" class="w-10 h-10 rounded-full">` :
                        `<div class="w-10 h-10 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-circle text-pink-600 text-[36px]"></i>
                    </div>`;

                    $('#messages').append(`
                            <div class="flex ${align} mb-3">
                                <div class="flex gap-2 ${bubbleAlign} max-w-md">
                                    ${!isUser ? avatar : ''}
                                    <div>
                                        <p class="text-sm ${isUser ? 'text-right' : 'text-left'} font-semibold mb-1">${msg.sender_name}</p>
                                        <div class="relative px-4 py-2 rounded-2xl ${bubbleColor}">
                                            <p class="whitespace-pre-line break-words break-all">${msg.message}</p>
                                        </div>
                                    </div>
                                    ${isUser ? avatar : ''}
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
                    if (typeof loadMessages === 'function') {
                        loadMessages();
                    }
                });
        }
        initEchoListener();

        function scrollToBottom() {
            const chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    </script>
@endsection
