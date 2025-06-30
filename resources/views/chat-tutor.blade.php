<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ auth()->user()->full_name ?? config('app.name', 'Laravel') }}</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
        rel="stylesheet"
    />
    <style>
        body {
            margin: 0;
            font-family: "Figtree", sans-serif;
            background-color: #f9fafb;
        }
        header {
            background-color: #4f46e5;
            padding: 16px 0;
        }
        .header-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-title {
            color: white;
            font-weight: bold;
            font-size: 1.125rem;
        }
        .logo-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .logo-circle {
            width: 100px;
            height: 100px;
            padding: 8px;
            background: white;
            border-radius: 9999px;
            border: 4px solid white;
        }
        .logo-svg {
            width: 100%;
            height: 100%;
        }
        .system-name {
            color: white;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }
        .header-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .header-links a {
            color: white;
            font-weight: 600;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border: 1px solid white;
            border-radius: 6px;
            transition: background 0.3s;
        }
        .header-links a:hover {
            background-color: #e0e7ff;
            color: #4f46e5;
        }
        .icon-link {
            color: white;
            font-size: 1.25rem;
            position: relative;
            border: 1px solid white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
        }
        .icon-link:hover {
            background-color: #e0e7ff;
            color: #4f46e5;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 40px 24px;
            display: flex;
            flex-direction: column;
            min-height: 60vh;
            gap: 1rem;
        }
        .messenger-layout {
            display: flex;
            height: 80vh;
            background: #f0f2f5;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        .messenger-sidebar {
            width: 340px;
            background: #fff;
            border-right: 1.5px solid #e5e7eb;
            display: flex;
            flex-direction: column;
        }
        .messenger-sidebar-header {
            padding: 22px 24px 10px 24px;
            font-size: 1.2rem;
            font-weight: 700;
            color: #4f46e5;
            border-bottom: 1px solid #f3f4f6;
        }
        .messenger-search {
            padding: 10px 24px;
            border-bottom: 1px solid #f3f4f6;
        }
        .messenger-search input {
            width: 100%;
            padding: 10px 16px;
            border-radius: 9999px;
            border: 1.5px solid #e5e7eb;
            font-size: 1em;
            background: #f9fafb;
        }
        .messenger-chat-list {
            flex: 1;
            overflow-y: auto;
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .messenger-chat-list-item {
            cursor: pointer;
            padding: 18px 24px 14px 24px;
            border-bottom: 1px solid #f3f4f6;
            background: transparent;
            transition: background 0.18s;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .messenger-chat-list-item.selected, .messenger-chat-list-item:hover {
            background: #eef2ff;
        }
        .messenger-chat-list-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            background: #e5e7eb;
        }
        .messenger-chat-list-info {
            flex: 1;
            min-width: 0;
        }
        .messenger-chat-list-name {
            font-weight: 600;
            color: #4f46e5;
            font-size: 1.07em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .messenger-chat-list-last {
            font-size: 0.97em;
            color: #6b7280;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .messenger-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #fff;
        }
        .messenger-header-bar {
            padding: 20px 32px 12px 32px;
            border-bottom: 1px solid #f3f4f6;
            font-weight: 700;
            font-size: 1.13rem;
            color: #374151;
            background: #f9fafb;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .messenger-header-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            background: #e5e7eb;
        }
        .messenger-header-name {
            font-weight: 700;
            color: #4f46e5;
            font-size: 1.13rem;
        }
        .messenger-messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 32px 32px 12px 32px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #f0f2f5;
        }
        .messenger-message-row {
            display: flex;
            align-items: flex-end;
            margin-bottom: 2px;
        }
        .messenger-message-row.self {
            justify-content: flex-end;
        }
        .messenger-message-bubble {
            max-width: 60%;
            padding: 14px 20px;
            border-radius: 22px;
            font-size: 1.08em;
            background: #e4e6eb;
            color: #374151;
            margin-bottom: 2px;
            box-shadow: 0 2px 8px rgba(99,102,241,0.06);
            word-break: break-word;
        }
        .messenger-message-row.self .messenger-message-bubble {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            border-bottom-right-radius: 8px;
            border-bottom-left-radius: 22px;
        }
        .messenger-message-row:not(.self) .messenger-message-bubble {
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 22px;
        }
        .messenger-message-meta {
            font-size: 0.85em;
            color: #9ca3af;
            margin: 0 8px;
            margin-bottom: 2px;
        }
        .messenger-input-bar {
            display: flex;
            gap: 10px;
            padding: 18px 32px 18px 32px;
            border-top: 1px solid #f3f4f6;
            background: #fff;
        }
        .messenger-input-bar input {
            flex-grow: 1;
            padding: 14px 18px;
            border-radius: 9999px;
            border: 1.5px solid #d1d5db;
            outline: none;
            font-size: 1.07em;
            background: #f9fafb;
            transition: border-color 0.2s;
        }
        .messenger-input-bar input:focus {
            border-color: #6366f1;
            background: #fff;
        }
        .messenger-input-bar button {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 9999px;
            padding: 12px 32px;
            font-size: 1.07em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .messenger-input-bar button:hover {
            background: linear-gradient(135deg, #3730a3 0%, #6d28d9 100%);
        }
        @media (max-width: 1100px) {
            .messenger-layout { flex-direction: column; }
            .messenger-sidebar { width: 100%; max-width: 100%; min-width: 0; margin-bottom: 18px; }
            .messenger-main { height: 60vh; }
        }
        @media (max-width: 768px) {
            .messenger-layout { flex-direction: column; gap: 10px; }
            .messenger-sidebar, .messenger-main { border-radius: 10px; }
            .messenger-header-bar, .messenger-messages-area, .messenger-input-bar { padding-left: 12px; padding-right: 12px; }
        }
    </style>
    <!-- ...existing code... -->
</head>
<body>
<header>
    <div class="header-container">
        <div class="header-title">Tutor Chat</div>
       <div class="logo-wrapper">
            <div class="logo-circle">
                <svg class="logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <path d="M50 15 L85 25 L50 35 L15 25 Z" fill="#2563eb" stroke="#1d4ed8" stroke-width="1"/>
                    <path d="M50 35 L50 45 L85 35 L85 25 Z" fill="#1d4ed8"/>
                    <circle cx="85" cy="25" r="3" fill="#dc2626"/>
                    <rect x="25" y="45" width="50" height="35" rx="3" fill="#3b82f6" stroke="#2563eb" stroke-width="1"/>
                    <rect x="25" y="45" width="25" height="35" rx="3" fill="#60a5fa"/>
                    <line x1="35" y1="52" x2="65" y2="52" stroke="white" stroke-width="1"/>
                    <line x1="35" y1="58" x2="65" y2="58" stroke="white" stroke-width="1"/>
                    <line x1="35" y1="64" x2="65" y2="64" stroke="white" stroke-width="1"/>
                    <line x1="35" y1="70" x2="60" y2="70" stroke="white" stroke-width="1"/>
                    <circle cx="20" cy="30" r="2" fill="#fbbf24" opacity="0.7"/>
                    <circle cx="80" cy="50" r="1.5" fill="#fbbf24" opacity="0.7"/>
                    <circle cx="15" cy="60" r="1" fill="#fbbf24" opacity="0.7"/>
                </svg>
            </div>
            <span class="system-name">Peer Tutor Matching System</span>
        </div>
        <div class="header-links">
            <a href="/home/tutor" class="icon-link" title="Home">
                <i class="fas fa-home"></i>
            </a>
            <a href="{{ route('profile.show', auth()->user()->id) }}" class="icon-link" title="Profile">
                <i class="fas fa-user"></i>
            </a>
            <a href="/chat/tutor" class="icon-link" title="Chat">
                <i class="fas fa-comment-dots"></i>
            </a>
            <a href="/notifications" class="icon-link" title="Notifications">
                <i class="fas fa-bell"></i>
            </a>
        </div>
    </div>
</header>

<div class="container">
    <div class="messenger-layout">
        <!-- Sidebar: Chat List (Student Names Only) -->
        <div class="messenger-sidebar" style="width: 260px;">
            <div class="messenger-sidebar-header">Students</div>
            <!-- Mini Search Bar -->
            <div class="messenger-search" style="padding:10px 16px;">
                <input type="text" id="chat-sidebar-search" placeholder="Search student..." style="width:100%;padding:8px 12px;border-radius:9999px;border:1.5px solid #e5e7eb;font-size:1em;background:#f9fafb;" />
            </div>
            <ul id="messenger-chat-list" class="messenger-chat-list" style="padding:0;margin:0;">
                <!-- Student names will be rendered here -->
            </ul>
        </div>
        <!-- Main: Chat Area (Center) -->
        <div class="messenger-main" style="flex:2;">
            <div id="messenger-header-bar" class="messenger-header-bar"></div>
            <div id="messenger-messages-area" class="messenger-messages-area"></div>
            <form id="messenger-input-bar" class="messenger-input-bar">
                <input type="text" id="messenger-message-input" placeholder="Aa" autocomplete="off" />
                <button type="submit"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
        <!-- Right: Student Details -->
        <div class="messenger-sidebar" style="width: 340px; min-width:260px; max-width:400px; border-left: 1.5px solid #e5e7eb;">
            <div class="messenger-sidebar-header">
                Student Details
            </div>
            <div id="student-details-panel" style="padding: 24px;">
                <div id="student-details-content">
                    @if(isset($studentDetails) && is_array($studentDetails) && !empty($studentDetails['name']))
                        <div style="margin-bottom:12px;">
                            <strong>Name:</strong> {{ $studentDetails['name'] ?? '' }}
                        </div>
                        <div style="margin-bottom:12px;">
                            <strong>Email:</strong> {{ $studentDetails['email'] ?? '' }}
                        </div>
                        <div style="margin-bottom:12px;">
                            <strong>Major:</strong> {{ $studentDetails['major'] ?? 'N/A' }}
                        </div>
                        <div style="margin-bottom:12px;">
                            <strong>Year:</strong> {{ $studentDetails['year'] ?? 'N/A' }}
                        </div>
                        <div style="margin-bottom:12px;">
                            <strong>Preferred Subjects:</strong>
                            @if(!empty($studentDetails['preferred_subjects']))
                                <ul style="margin:0 0 8px 0;padding-left:18px;">
                                    @foreach((array)$studentDetails['preferred_subjects'] as $subj)
                                        <li>{{ $subj }}</li>
                                    @endforeach
                                </ul>
                            @else
                                N/A
                            @endif
                        </div>
                    @else
                        <p style="color:#6b7280;">Select a student to view details.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
const userId = {{ auth()->id() }};
const authUserRole = 'tutor';
let selectedChatId = null;
let chatList = [];
let chatPollingInterval = null;
let studentDetailsMap = {}; // Map chatId -> student details
let chatSidebarSearchValue = '';

function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Fetch chat list for the logged-in tutor
function fetchChatList(search = '') {
    fetch('/chats')
        .then(res => res.json())
        .then(data => {
            chatList = Array.isArray(data) ? data : [];
            // Build student details map for right panel
            studentDetailsMap = {};
            chatList.forEach(chat => {
                studentDetailsMap[chat.id] = chat.student_details || {};
            });
            // Use sidebar search value if present
            let filterVal = chatSidebarSearchValue || search;
            if (filterVal) {
                chatList = chatList.filter(chat =>
                    (chat.name || '').toLowerCase().includes(filterVal.toLowerCase())
                );
            }
            renderChatList();

            // If ?student_id= is present, select that chat (or create it if not exists)
            const studentIdParam = getQueryParam('student_id');
            if (studentIdParam) {
                let chatWithStudent = chatList.find(chat => String(chat.student_id) === String(studentIdParam));
                if (chatWithStudent) {
                    selectChat(chatWithStudent.id);
                    return;
                } else {
                    window.location.href = `/chat/tutor?student_id=${studentIdParam}`;
                    return;
                }
            }

            if (chatList.length === 0) {
                clearChatArea();
            } else if (!selectedChatId) {
                selectChat(chatList[0].id);
            }
        });
}

// Render chat list in sidebar (Student Names Only)
function renderChatList() {
    const ul = document.getElementById('messenger-chat-list');
    ul.innerHTML = '';
    if (chatList.length === 0) {
        const noChatsLi = document.createElement('li');
        noChatsLi.className = 'messenger-chat-list-item';
        noChatsLi.textContent = 'No chats available';
        ul.appendChild(noChatsLi);
        document.getElementById('messenger-header-bar').innerHTML = '';
        document.getElementById('messenger-messages-area').innerHTML = '';
        document.getElementById('student-details-content').innerHTML = '<p style="color:#6b7280;">Select a student to view details.</p>';
        return;
    }
    chatList.forEach(chat => {
        const li = document.createElement('li');
        li.className = 'messenger-chat-list-item' + (chat.id === selectedChatId ? ' selected' : '');
        li.style.justifyContent = 'flex-start';
        li.style.gap = '0';
        li.innerHTML = `
            <span class="messenger-chat-list-name" style="font-size:1.08em;">${chat.name || 'Student'}</span>
        `;
        li.onclick = () => selectChat(chat.id);
        ul.appendChild(li);
    });
}

// Select a chat and fetch its messages
function selectChat(chatId) {
    selectedChatId = chatId;
    renderChatList();
    fetchChatMessages();
    const chat = chatList.find(c => c.id === chatId);
    document.getElementById('messenger-header-bar').textContent = chat ? (chat.name || 'Chat') : '';
    renderStudentDetails(chatId);
    if (chatPollingInterval) clearInterval(chatPollingInterval);
    chatPollingInterval = setInterval(fetchChatMessages, 3000);
}

// Show student details in right panel
function renderStudentDetails(chatId) {
    const detailsDiv = document.getElementById('student-details-content');
    const chat = chatList.find(c => c.id === chatId);
    if (!chat) {
        detailsDiv.innerHTML = `<p style="color:#6b7280;">Select a student to view details.</p>`;
        return;
    }
    const details = chat.student_details || null;
    if (!details) {
        detailsDiv.innerHTML = `<p style="color:#6b7280;">Select a student to view details.</p>`;
        return;
    }
    let subjects = '';
    if (Array.isArray(details.preferred_subjects) && details.preferred_subjects.length > 0) {
        subjects = '<ul style="margin:0 0 8px 0;padding-left:18px;">';
        details.preferred_subjects.forEach(subj => {
            subjects += `<li>${subj}</li>`;
        });
        subjects += '</ul>';
    } else {
        subjects = 'N/A';
    }
    detailsDiv.innerHTML = `
        <div style="margin-bottom:12px;">
            <strong>Name:</strong> ${details.name || ''}
        </div>
        <div style="margin-bottom:12px;">
            <strong>Email:</strong> ${details.email || ''}
        </div>
        <div style="margin-bottom:12px;">
            <strong>Major:</strong> ${details.major || 'N/A'}
        </div>
        <div style="margin-bottom:12px;">
            <strong>Year:</strong> ${details.year || 'N/A'}
        </div>
        <div style="margin-bottom:12px;">
            <strong>Preferred Subjects:</strong> ${subjects}
        </div>
    `;
}

// Fetch messages for the selected chat
function fetchChatMessages() {
    if (!selectedChatId) return;
    fetch(`/chats/${selectedChatId}/messages`)
        .then(res => res.json())
        .then(data => {
            renderMessages(data.messages || []);
        });
}

// Render messages in the chat box
function renderMessages(messages) {
    const box = document.getElementById('messenger-messages-area');
    box.innerHTML = '';
    messages.forEach(msg => {
        const row = document.createElement('div');
        row.className = 'messenger-message-row' + (msg.sender_id == userId ? ' self' : '');
        const bubble = document.createElement('div');
        bubble.className = 'messenger-message-bubble';
        bubble.textContent = msg.content;
        row.appendChild(bubble);
        box.appendChild(row);
    });
    box.scrollTop = box.scrollHeight;
}

// Send message via AJAX
document.getElementById('messenger-input-bar').addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('messenger-message-input');
    const content = input.value.trim();
    if (!content || !selectedChatId) return;
    fetch(`/chats/${selectedChatId}/messages`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ content })
    })
    .then(response => response.json())
    .then(data => {
        input.value = '';
        fetchChatMessages();
        fetchChatList();
    })
    .catch(error => {
        console.error('Error sending message:', error);
    });
});

// Mini search bar event
document.addEventListener('DOMContentLoaded', function() {
    const sidebarSearch = document.getElementById('chat-sidebar-search');
    if (sidebarSearch) {
        sidebarSearch.addEventListener('input', function() {
            chatSidebarSearchValue = this.value.trim();
            fetchChatList();
        });
    }
});

// Add this function to fix the ReferenceError if not present
function clearChatArea() {
    document.getElementById('messenger-header-bar').innerHTML = '';
    document.getElementById('messenger-messages-area').innerHTML = '';
    document.getElementById('student-details-content').innerHTML = '<p style="color:#6b7280;">Select a student to view details.</p>';
}

// Initial load
fetchChatList();
</script>
</body>
</html>
