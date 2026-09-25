<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Support Chat</title>

    <style>
        :root {
            --chat-primary: #2962ff;
            --chat-primary-dark: #1e4fd8;
            --chat-bg: #f4f7fb;
            --chat-border: #e8edf3;
            --chat-text: #2b2f38;
            --chat-muted: #8b95a7;
        }

        body {
            background: #f4f6f9;
        }

        .chat-page {
            padding: 24px;
        }

        .chat-wrapper {
            height: calc(100vh - 125px);
            min-height: 620px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 25px rgba(30, 40, 60, .08);
            border: 1px solid #edf0f5;
        }

        /* =========================
           LEFT PANEL
        ========================= */

        .tenant-panel {
            width: 320px;
            flex: 0 0 320px;
            border-right: 1px solid var(--chat-border);
            background: #fff;
            display: flex;
            flex-direction: column;
        }

        .tenant-panel-header {
            padding: 22px 20px 16px;
            border-bottom: 1px solid var(--chat-border);
        }

        .tenant-panel-header h5 {
            font-size: 18px;
            font-weight: 600;
            color: var(--chat-text);
            margin-bottom: 3px;
        }

        .tenant-panel-header small {
            color: var(--chat-muted);
        }

        .tenant-search {
            margin-top: 16px;
            position: relative;
        }

        .tenant-search i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #a2aaba;
            z-index: 2;
        }

        .tenant-search input {
            height: 42px;
            padding-left: 38px;
            border-radius: 7px;
            border: 1px solid #e2e7ee;
            background: #f8fafc;
            font-size: 14px;
        }

        .tenant-search input:focus {
            border-color: var(--chat-primary);
            box-shadow: 0 0 0 3px rgba(41, 98, 255, .08);
            background: #fff;
        }

        .tenant-list {
            flex: 1;
            overflow-y: auto;
        }

        .tenant-list::-webkit-scrollbar,
        .chat-messages::-webkit-scrollbar {
            width: 5px;
        }

        .tenant-list::-webkit-scrollbar-thumb,
        .chat-messages::-webkit-scrollbar-thumb {
            background: #d5dbe4;
            border-radius: 10px;
        }

        .tenant-item {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 15px 18px;
            text-decoration: none;
            color: var(--chat-text);
            border-bottom: 1px solid #f0f2f5;
            transition: all .2s ease;
        }

        .tenant-item:hover {
            background: #f7f9fc;
            color: var(--chat-text);
        }

        .tenant-item.active {
            background: #eef4ff;
            border-left: 3px solid var(--chat-primary);
            padding-left: 15px;
        }

        .tenant-avatar {
            width: 46px;
            height: 46px;
            min-width: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2962ff, #5080ff);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 600;
            position: relative;
        }

        .tenant-online {
            position: absolute;
            right: 0;
            bottom: 1px;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: #2ecc71;
            border: 2px solid #fff;
        }

        .tenant-info {
            min-width: 0;
            flex: 1;
        }

        .tenant-name {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .tenant-subtitle {
            font-size: 12px;
            color: var(--chat-muted);
        }

        /* =========================
           CHAT AREA
        ========================= */

        .chat-area {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: var(--chat-bg);
        }

        .chat-header {
            height: 78px;
            flex-shrink: 0;
            background: #fff;
            border-bottom: 1px solid var(--chat-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 22px;
        }

        .chat-user {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .chat-user-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2962ff, #5080ff);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            position: relative;
        }

        .chat-user-status {
            position: absolute;
            right: -1px;
            bottom: 1px;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: #2ecc71;
            border: 2px solid #fff;
        }

        .chat-user-info h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: var(--chat-text);
        }

        .chat-user-info small {
            color: #2ecc71;
            font-size: 12px;
        }

        .chat-header-actions {
            display: flex;
            gap: 7px;
        }

        .chat-header-btn {
            width: 38px;
            height: 38px;
            border: 0;
            background: #f5f7fa;
            color: #7b8495;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-header-btn:hover {
            background: #eef3ff;
            color: var(--chat-primary);
        }

        /* =========================
           MESSAGES
        ========================= */

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 28px;
            background:
                radial-gradient(
                    circle at 20% 20%,
                    rgba(41, 98, 255, .025),
                    transparent 25%
                ),
                var(--chat-bg);
        }

        .message-row {
            display: flex;
            margin-bottom: 18px;
        }

        .message-row.mine {
            justify-content: flex-end;
        }

        .message-content {
            max-width: 68%;
        }

        .message-sender {
            font-size: 11px;
            color: #8a94a6;
            margin-bottom: 5px;
        }

        .message-row.mine .message-sender {
            text-align: right;
        }

        .message-bubble {
            padding: 11px 15px;
            border-radius: 10px;
            font-size: 14px;
            line-height: 1.55;
            word-break: break-word;
            display: inline-block;
        }

        .message-row:not(.mine) .message-bubble {
            background: #fff;
            color: #414854;
            border: 1px solid #e8edf3;
            border-top-left-radius: 3px;
            box-shadow: 0 2px 6px rgba(30, 40, 60, .035);
        }

        .message-row.mine .message-bubble {
            background: var(--chat-primary);
            color: #fff;
            border-top-right-radius: 3px;
            box-shadow: 0 3px 8px rgba(41, 98, 255, .18);
        }

        .message-time {
            margin-top: 5px;
            font-size: 10px;
            color: #9aa3b2;
        }

        .message-row.mine .message-time {
            text-align: right;
        }

        .message-check {
            color: #2962ff;
            font-size: 13px;
        }

        /* =========================
           EMPTY CHAT
        ========================= */

        .empty-chat {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .empty-chat-icon {
            width: 75px;
            height: 75px;
            margin: auto;
            border-radius: 50%;
            background: #eaf0ff;
            color: var(--chat-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }

        .empty-chat h6 {
            margin-top: 18px;
            margin-bottom: 6px;
            font-size: 16px;
            color: #454c58;
        }

        .empty-chat p {
            color: #929aaa;
            font-size: 13px;
        }

        /* =========================
           COMPOSER
        ========================= */

        .chat-composer {
            flex-shrink: 0;
            background: #fff;
            border-top: 1px solid var(--chat-border);
            padding: 14px 18px;
        }

        .composer-box {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            background: #f7f9fc;
            border: 1px solid #e4e9f0;
            border-radius: 10px;
            padding: 7px;
            transition: .2s ease;
        }

        .composer-box:focus-within {
            background: #fff;
            border-color: rgba(41, 98, 255, .45);
            box-shadow: 0 0 0 3px rgba(41, 98, 255, .06);
        }

        .composer-box textarea {
            flex: 1;
            resize: none;
            border: 0 !important;
            outline: 0 !important;
            box-shadow: none !important;
            background: transparent;
            padding: 8px 10px;
            font-size: 14px;
            min-height: 42px;
            max-height: 110px;
        }

        .send-btn {
            height: 43px;
            min-width: 95px;
            border: 0;
            border-radius: 7px;
            background: var(--chat-primary);
            color: #fff;
            font-weight: 500;
            transition: .2s ease;
        }

        .send-btn:hover {
            background: var(--chat-primary-dark);
            transform: translateY(-1px);
        }

        .send-btn:disabled {
            opacity: .7;
            cursor: not-allowed;
            transform: none;
        }

        .composer-hint {
            font-size: 10px;
            color: #a0a8b5;
            margin-top: 6px;
            padding-left: 4px;
        }

        /* =========================
           MOBILE
        ========================= */

        .mobile-back {
            display: none;
        }

        @media (max-width: 991.98px) {

            .chat-page {
                padding: 12px;
            }

            .chat-wrapper {
                height: calc(100vh - 90px);
                min-height: 550px;
            }

            .tenant-panel {
                display: none;
            }

            .chat-area {
                width: 100%;
            }

            .mobile-back {
                display: flex;
                width: 35px;
                height: 35px;
                border: 0;
                background: #f3f5f8;
                border-radius: 7px;
                align-items: center;
                justify-content: center;
                color: #596273;
                text-decoration: none;
            }

            .chat-header {
                padding: 0 14px;
            }

            .chat-header-actions {
                display: none;
            }

            .chat-messages {
                padding: 18px 13px;
            }

            .message-content {
                max-width: 82%;
            }

            .chat-composer {
                padding: 10px;
            }

            .send-btn {
                min-width: 48px;
                width: 48px;
                padding: 0;
            }

            .send-btn span {
                display: none;
            }

            .composer-hint {
                display: none;
            }
        }

        @media (max-width: 575.98px) {

            .chat-page {
                padding: 0;
            }

            .chat-wrapper {
                border-radius: 0;
                border: 0;
                height: calc(100vh - 65px);
            }

            .chat-user-info h5 {
                font-size: 14px;
            }

            .chat-user-info small {
                font-size: 11px;
            }

            .chat-user-avatar {
                width: 40px;
                height: 40px;
            }

            .message-bubble {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>

@include('tenant.nav')

<div class="chat-page">

    <div class="chat-wrapper d-flex">

        {{-- =====================================================
             LEFT: SUPPORT CONTACT
        ====================================================== --}}

        <aside class="tenant-panel">

            <div class="tenant-panel-header">

                <h5>
                    Support Chat
                </h5>

                <small>
                    Contact Super Admin
                </small>

                <div class="tenant-search">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        id="contactSearch"
                        class="form-control"
                        placeholder="Search contact..."
                        autocomplete="off">

                </div>

            </div>


            <div class="tenant-list" id="tenantList">

                <a
                    href="javascript:void(0)"
                    class="tenant-item active"
                    data-name="super admin support">

                    <div class="tenant-avatar">

                        <i class="bi bi-headset"></i>

                        <span class="tenant-online"></span>

                    </div>

                    <div class="tenant-info">

                        <div class="tenant-name">
                            Super Admin Support
                        </div>

                        <div class="tenant-subtitle">
                            <i class="bi bi-chat-dots me-1"></i>
                            Support Team
                        </div>

                    </div>

                </a>

            </div>

        </aside>


        {{-- =====================================================
             RIGHT CHAT
        ====================================================== --}}

        <main class="chat-area">

            {{-- HEADER --}}

            <div class="chat-header">

                <div class="chat-user">

                    <a
                        href="{{ url('/tenant/chat') }}"
                        class="mobile-back me-1">

                        <i class="bi bi-arrow-left"></i>

                    </a>

                    <div class="chat-user-avatar">

                        <i class="bi bi-headset"></i>

                        <span class="chat-user-status"></span>

                    </div>

                    <div class="chat-user-info">

                        <h5>
                            Super Admin Support
                        </h5>

                        <small>
                            <i
                                class="bi bi-circle-fill me-1"
                                style="font-size: 7px;">
                            </i>
                            Active conversation
                        </small>

                    </div>

                </div>


                <div class="chat-header-actions">

                    <button
                        type="button"
                        class="chat-header-btn"
                        title="Search">

                        <i class="bi bi-search"></i>

                    </button>

                    <button
                        type="button"
                        class="chat-header-btn"
                        title="More">

                        <i class="bi bi-three-dots-vertical"></i>

                    </button>

                </div>

            </div>


            {{-- =================================================
                 MESSAGES
            ================================================== --}}

            <div
                id="chatMessages"
                class="chat-messages">

                @forelse($messages as $message)

                    @php
                        $isMine = $message->sender_id === auth()->id();
                    @endphp

                    <div
                        class="message-row {{ $isMine ? 'mine' : '' }}">

                        <div class="message-content">

                            @if(!$isMine)

                                <div class="message-sender">
                                    Super Admin Support
                                </div>

                            @endif

                            <div class="message-bubble">
                                {{ $message->message }}
                            </div>

                            <div class="message-time">

                                {{ $message->created_at->format('h:i A') }}

                                @if($isMine)

                                    <i
                                        class="bi bi-check2-all message-check ms-1">
                                    </i>

                                @endif

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="empty-chat">

                        <div>

                            <div class="empty-chat-icon">

                                <i class="bi bi-chat-dots"></i>

                            </div>

                            <h6>
                                Start a conversation
                            </h6>

                            <p>
                                Send a message to Super Admin Support.
                            </p>

                        </div>

                    </div>

                @endforelse

            </div>


            {{-- =================================================
                 MESSAGE COMPOSER
            ================================================== --}}

            <div class="chat-composer">

                <form
                    action="{{ route('tenant.chat.send', $conversation) }}"
                    method="POST"
                    id="tenantChatForm">

                    @csrf

                    <div class="composer-box">

                        <textarea
                            name="message"
                            id="messageInput"
                            class="form-control"
                            rows="1"
                            maxlength="5000"
                            placeholder="Type your message..."
                            required></textarea>

                        <button
                            type="submit"
                            class="send-btn"
                            id="sendMessageBtn">

                            <i class="bi bi-send-fill me-1"></i>

                            <span>
                                Send
                            </span>

                        </button>

                    </div>

                    <div class="composer-hint">
                        Press Enter to send · Shift + Enter for new line
                    </div>

                </form>

            </div>

        </main>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const form =
        document.getElementById('tenantChatForm');

    const input =
        document.getElementById('messageInput');

    const chatMessages =
        document.getElementById('chatMessages');

    const sendBtn =
        document.getElementById('sendMessageBtn');

    const contactSearch =
        document.getElementById('contactSearch');


    /* =========================================================
       SCROLL TO BOTTOM
    ========================================================= */

    function scrollToBottom() {

        if (chatMessages) {

            chatMessages.scrollTop =
                chatMessages.scrollHeight;

        }

    }

    scrollToBottom();


    /* =========================================================
       SEND MESSAGE USING AJAX
       IMPORTANT:
       Page reload nahi hoga.
    ========================================================= */

    form.addEventListener('submit', async function (e) {

        e.preventDefault();


        const message =
            input.value.trim();


        if (!message) {
            return;
        }


        /* Disable send button */

        sendBtn.disabled = true;


        const oldButtonHTML =
            sendBtn.innerHTML;


        sendBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm"></span>
        `;


        try {

            const formData =
                new FormData(form);


            const response =
                await fetch(
                    form.action,
                    {
                        method: 'POST',

                        body: formData,

                        headers: {
                            'X-Requested-With':
                                'XMLHttpRequest',

                            'Accept':
                                'application/json'
                        }
                    }
                );


            /*
             * Agar Laravel ne error response diya
             */

            if (!response.ok) {

                throw new Error(
                    'Message send failed.'
                );

            }


            const data =
                await response.json();


            /*
             * Laravel JSON response check
             */

            if (!data.success) {

                throw new Error(
                    data.message ||
                    'Unable to send message.'
                );

            }


            /* =================================================
               REMOVE EMPTY STATE
            ================================================= */

            const emptyChat =
                chatMessages.querySelector(
                    '.empty-chat'
                );


            if (emptyChat) {
                emptyChat.remove();
            }


            /* =================================================
               ADD NEW MESSAGE
            ================================================= */

            const msg =
                data.message;


            const row =
                document.createElement('div');

            row.className =
                'message-row mine';


            const content =
                document.createElement('div');

            content.className =
                'message-content';


            const bubble =
                document.createElement('div');

            bubble.className =
                'message-bubble';


            /*
             * textContent use kar rahe hain
             * taake HTML/XSS issue na ho.
             */

            bubble.textContent =
                msg.message;


            const time =
                document.createElement('div');

            time.className =
                'message-time';


            time.innerHTML = `
                ${msg.time}
                <i class="bi bi-check2-all message-check ms-1"></i>
            `;


            content.appendChild(bubble);

            content.appendChild(time);

            row.appendChild(content);

            chatMessages.appendChild(row);


            /* =================================================
               CLEAR INPUT
            ================================================= */

            input.value = '';

            input.style.height =
                'auto';


            /* =================================================
               SCROLL TO NEW MESSAGE
            ================================================= */

            scrollToBottom();


            /*
             * Cursor dobara input mein
             */

            input.focus();


        } catch (error) {

            console.error(
                'Chat Error:',
                error
            );


            alert(
                error.message ||
                'Something went wrong while sending message.'
            );


        } finally {

            sendBtn.disabled =
                false;

            sendBtn.innerHTML =
                oldButtonHTML;

        }

    });


    /* =========================================================
       ENTER = SEND
       SHIFT + ENTER = NEW LINE
    ========================================================= */

    input.addEventListener(
        'keydown',
        function (e) {

            if (
                e.key === 'Enter' &&
                !e.shiftKey
            ) {

                e.preventDefault();


                /*
                 * requestSubmit() use hoga,
                 * is liye submit event AJAX wala chalega.
                 */

                if (
                    input.value.trim() !== ''
                ) {

                    form.requestSubmit();

                }

            }

        }
    );


    /* =========================================================
       AUTO RESIZE TEXTAREA
    ========================================================= */

    input.addEventListener(
        'input',
        function () {

            this.style.height =
                'auto';


            this.style.height =
                Math.min(
                    this.scrollHeight,
                    110
                ) + 'px';

        }
    );


    /* =========================================================
       CONTACT SEARCH
    ========================================================= */

    if (contactSearch) {

        contactSearch.addEventListener(
            'input',
            function () {

                const searchValue =
                    this.value
                        .toLowerCase()
                        .trim();


                const contacts =
                    document.querySelectorAll(
                        '#tenantList .tenant-item'
                    );


                contacts.forEach(
                    function (contact) {

                        const name =
                            (
                                contact.dataset.name ||
                                contact.textContent
                            )
                            .toLowerCase();


                        contact.style.display =
                            name.includes(searchValue)
                                ? 'flex'
                                : 'none';

                    }
                );

            }
        );

    }

});

</script>


@include('tenant.footer')

</body>
</html>
