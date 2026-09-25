<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tenant Chat</title>

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
       LEFT TENANT PANEL
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
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
        color: var(--chat-primary);
        font-size: 13px;
    }

    .message-row.mine .message-check {
        color: #2962ff;
    }

    /* =========================
       EMPTY STATE
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
       MESSAGE COMPOSER
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
       NO CONVERSATION
    ========================= */

    .no-conversation {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .no-conversation-icon {
        width: 85px;
        height: 85px;
        margin: auto;
        border-radius: 50%;
        background: #eaf0ff;
        color: var(--chat-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
    }

    .no-conversation h5 {
        margin-top: 20px;
        color: #454c58;
    }

    .no-conversation p {
        color: #929aaa;
        font-size: 13px;
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

</head> <body>
@include('admin.nav')

<div class="chat-page">
<div class="chat-wrapper d-flex">

    {{-- =====================================================
         LEFT: TENANT LIST
    ====================================================== --}}

    <aside class="tenant-panel">

        <div class="tenant-panel-header">

            <h5>
                Tenant Chats
            </h5>

            <small>
                All tenant conversations
            </small>

            <div class="tenant-search">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="tenantSearch"
                    class="form-control"
                    placeholder="Search tenant...">

            </div>

        </div>

        <div class="tenant-list" id="tenantList">

            @forelse(($conversations ?? collect()) as $item)

                @php
                    $isActive = isset($conversation)
                        && $conversation
                        && $conversation->id == $item->id;

                    $tenantName = $item->tenant->name ?? 'Tenant';
                @endphp

                <a
                    href="{{ route('admin.chat.conversation', $item) }}"
                    class="tenant-item {{ $isActive ? 'active' : '' }}"
                    data-name="{{ strtolower($tenantName) }}">

                    <div class="tenant-avatar">

                        {{ strtoupper(substr($tenantName, 0, 1)) }}

                        <span class="tenant-online"></span>

                    </div>

                    <div class="tenant-info">

                        <div class="tenant-name">
                            {{ $tenantName }}
                        </div>

                        <div class="tenant-subtitle">
                            <i class="bi bi-chat-dots me-1"></i>
                            Support Chat
                        </div>

                    </div>

                </a>

            @empty

                <div class="text-center p-4 text-muted">

                    <i class="bi bi-people fs-2"></i>

                    <p class="small mt-2 mb-0">
                        No tenant conversations found.
                    </p>

                </div>

            @endforelse

        </div>

    </aside>

    {{-- =====================================================
         RIGHT: CHAT AREA
    ====================================================== --}}

    <main class="chat-area">

        {{-- =================================================
             CHAT HEADER
        ================================================== --}}

        <div class="chat-header">

            <div class="chat-user">

                <a
                    href="{{ url('/admin/chat') }}"
                    class="mobile-back me-1">

                    <i class="bi bi-arrow-left"></i>

                </a>

                @if(isset($conversation) && $conversation)

                    @php
                        $activeTenant = $conversation->tenant->name ?? 'Tenant';
                    @endphp

                    <div class="chat-user-avatar">

                        {{ strtoupper(substr($activeTenant, 0, 1)) }}

                        <span class="chat-user-status"></span>

                    </div>

                    <div class="chat-user-info">

                        <h5>
                            {{ $activeTenant }}
                        </h5>

                        <small>
                            <i
                                class="bi bi-circle-fill me-1"
                                style="font-size: 7px;">
                            </i>
                            Active conversation
                        </small>

                    </div>

                @else

                    <div class="chat-user-avatar">

                        <i class="bi bi-chat-dots"></i>

                    </div>

                    <div class="chat-user-info">

                        <h5>
                            Tenant Chat
                        </h5>

                        <small style="color: #929aaa;">
                            Select a conversation
                        </small>

                    </div>

                @endif

            </div>

            @if(isset($conversation) && $conversation)

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

            @endif

        </div>

        {{-- =================================================
             MESSAGES
        ================================================== --}}

        <div
            id="adminChatMessages"
            class="chat-messages">

            @if(isset($conversation) && $conversation)

                @forelse(($messages ?? collect()) as $message)

                    @php
                        $isMine = (int) $message->sender_id === (int) auth()->id();
                    @endphp

                    <div class="message-row {{ $isMine ? 'mine' : '' }}">

                        <div class="message-content">

                            @if(!$isMine)

                                <div class="message-sender">
                                    {{ $message->sender->name ?? 'Tenant' }}
                                </div>

                            @endif

                            <div class="message-bubble">
                                {{ $message->message }}
                            </div>

                            <div class="message-time">

                                {{ $message->created_at->format('d M Y, h:i A') }}

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
                                No messages yet
                            </h6>

                            <p>
                                Start a conversation with this tenant.
                            </p>

                        </div>

                    </div>

                @endforelse

            @else

                <div class="no-conversation">

                    <div>

                        <div class="no-conversation-icon">

                            <i class="bi bi-chat-square-text"></i>

                        </div>

                        <h5>
                            Select a tenant
                        </h5>

                        <p>
                            Choose a tenant conversation from the left
                            panel to view messages.
                        </p>

                    </div>

                </div>

            @endif

        </div>

        {{-- =================================================
             MESSAGE COMPOSER
        ================================================== --}}

        @if(isset($conversation) && $conversation)

            <div class="chat-composer">

                <form
                    action="{{ route('admin.chat.send', $conversation) }}"
                    method="POST"
                    id="adminChatForm">

                    @csrf

                    <div class="composer-box">

                        <textarea
                            name="message"
                            id="adminMessageInput"
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

        @endif

    </main>

</div>

</div>
@include('admin.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {

/*
|--------------------------------------------------------------------------
| Tenant Search
|--------------------------------------------------------------------------
*/

const tenantSearch = document.getElementById('tenantSearch');
const tenantItems = document.querySelectorAll('.tenant-item');

if (tenantSearch) {

    tenantSearch.addEventListener('input', function () {

        const search = this.value
            .toLowerCase()
            .trim();

        tenantItems.forEach(function (item) {

            const name = item.dataset.name || '';

            if (name.includes(search)) {

                item.style.display = 'flex';

            } else {

                item.style.display = 'none';

            }

        });

    });

}

/*
|--------------------------------------------------------------------------
| Chat Elements
|--------------------------------------------------------------------------
*/

const form = document.getElementById('adminChatForm');
const input = document.getElementById('adminMessageInput');
const chatMessages = document.getElementById('adminChatMessages');
const sendBtn = document.getElementById('sendMessageBtn');

/*
|--------------------------------------------------------------------------
| No Active Conversation
|--------------------------------------------------------------------------
*/

if (!form || !input || !chatMessages || !sendBtn) {
    return;
}

/*
|--------------------------------------------------------------------------
| Scroll Chat To Bottom
|--------------------------------------------------------------------------
*/

function scrollToBottom() {

    if (chatMessages) {

        chatMessages.scrollTop =
            chatMessages.scrollHeight;

    }

}

scrollToBottom();

/*
|--------------------------------------------------------------------------
| SEND MESSAGE WITHOUT RELOADING PAGE
|--------------------------------------------------------------------------
*/

form.addEventListener('submit', async function (e) {

    e.preventDefault();

    const message = input.value.trim();

    if (!message) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Disable Send Button
    |--------------------------------------------------------------------------
    */

    sendBtn.disabled = true;

    const oldHtml = sendBtn.innerHTML;

    sendBtn.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
    `;

    try {

        const formData = new FormData(form);

        const response = await fetch(
            form.action,
            {
                method: 'POST',

                body: formData,

                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Check HTTP Response
        |--------------------------------------------------------------------------
        */

        if (!response.ok) {

            let errorMessage =
                'Message send failed.';

            try {

                const errorData =
                    await response.json();

                if (errorData.message) {
                    errorMessage =
                        errorData.message;
                }

            } catch (jsonError) {

                // Ignore JSON parsing error

            }

            throw new Error(errorMessage);

        }

        /*
        |--------------------------------------------------------------------------
        | Parse JSON
        |--------------------------------------------------------------------------
        */

        const data =
            await response.json();

        if (!data.success) {

            throw new Error(
                data.message ||
                'Unable to send message.'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Remove Empty Chat State
        |--------------------------------------------------------------------------
        */

        const emptyChat =
            chatMessages.querySelector('.empty-chat');

        if (emptyChat) {

            emptyChat.remove();

        }

        const noConversation =
            chatMessages.querySelector('.no-conversation');

        if (noConversation) {

            noConversation.remove();

        }

        /*
        |--------------------------------------------------------------------------
        | Get Returned Message
        |--------------------------------------------------------------------------
        */

        const msg = data.message;

        /*
        |--------------------------------------------------------------------------
        | Create Message Row
        |--------------------------------------------------------------------------
        */

        const row =
            document.createElement('div');

        row.className =
            'message-row mine';

        /*
        |--------------------------------------------------------------------------
        | Create Message Content
        |--------------------------------------------------------------------------
        */

        const content =
            document.createElement('div');

        content.className =
            'message-content';

        /*
        |--------------------------------------------------------------------------
        | Create Message Bubble
        |--------------------------------------------------------------------------
        */

        const bubble =
            document.createElement('div');

        bubble.className =
            'message-bubble';

        /*
        | Safe text insertion
        */

        bubble.textContent =
            msg.message;

        /*
        |--------------------------------------------------------------------------
        | Create Time
        |--------------------------------------------------------------------------
        */

        const time =
            document.createElement('div');

        time.className =
            'message-time';

        const timeText =
            document.createTextNode(
                msg.time || ''
            );

        time.appendChild(timeText);

        const check =
            document.createElement('i');

        check.className =
            'bi bi-check2-all message-check ms-1';

        time.appendChild(check);

        /*
        |--------------------------------------------------------------------------
        | Append Everything
        |--------------------------------------------------------------------------
        */

        content.appendChild(bubble);

        content.appendChild(time);

        row.appendChild(content);

        chatMessages.appendChild(row);

        /*
        |--------------------------------------------------------------------------
        | Clear Input
        |--------------------------------------------------------------------------
        */

        input.value = '';

        input.style.height =
            'auto';

        /*
        |--------------------------------------------------------------------------
        | Scroll To New Message
        |--------------------------------------------------------------------------
        */

        scrollToBottom();

        /*
        |--------------------------------------------------------------------------
        | Focus Input
        |--------------------------------------------------------------------------
        */

        input.focus();

    } catch (error) {

        console.error(
            'Chat send error:',
            error
        );

        alert(
            error.message ||
            'Something went wrong while sending the message.'
        );

    } finally {

        /*
        |--------------------------------------------------------------------------
        | Restore Send Button
        |--------------------------------------------------------------------------
        */

        sendBtn.disabled = false;

        sendBtn.innerHTML =
            oldHtml;

    }

});

/*
|--------------------------------------------------------------------------
| ENTER TO SEND
|--------------------------------------------------------------------------
*/

input.addEventListener(
    'keydown',
    function (e) {

        if (
            e.key === 'Enter' &&
            !e.shiftKey
        ) {

            e.preventDefault();

            form.requestSubmit();

        }

    }
);

/*
|--------------------------------------------------------------------------
| AUTO RESIZE TEXTAREA
|--------------------------------------------------------------------------
*/

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

});

</script> </body> </html>