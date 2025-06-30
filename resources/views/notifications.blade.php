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
            max-w-5xl mx-auto flex;
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
            align-items: center;
            min-height: 60vh;
            justify-content: flex-start;
        }
        .notification-card {
            background-color: white;
            padding: 20px;
            border-left: 5px solid #6366f1;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            width: 100%;
        }
        .notification-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .notification-time {
            font-size: 0.875rem;
            color: #6b7280;
        }
        footer {
            text-align: center;
            padding: 20px 0;
            color: #6b7280;
            font-size: 0.9rem;
        }
        footer a {
            color: #4f46e5;
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="header-title">Notifications</div>

            <div class="logo-wrapper">
                <div class="logo-circle">
                    <svg
                        class="logo-svg"
                        viewBox="0 0 100 100"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M50 15 L85 25 L50 35 L15 25 Z"
                            fill="#2563eb"
                            stroke="#1d4ed8"
                            stroke-width="1"
                        />
                        <path d="M50 35 L50 45 L85 35 L85 25 Z" fill="#1d4ed8" />
                        <circle cx="85" cy="25" r="3" fill="#dc2626" />
                        <rect
                            x="25"
                            y="45"
                            width="50"
                            height="35"
                            rx="3"
                            fill="#3b82f6"
                            stroke="#2563eb"
                            stroke-width="1"
                        />
                        <rect x="25" y="45" width="25" height="35" rx="3" fill="#60a5fa" />
                        <line
                            x1="35"
                            y1="52"
                            x2="65"
                            y2="52"
                            stroke="white"
                            stroke-width="1"
                        />
                        <line
                            x1="35"
                            y1="58"
                            x2="65"
                            y2="58"
                            stroke="white"
                            stroke-width="1"
                        />
                        <line
                            x1="35"
                            y1="64"
                            x2="65"
                            y2="64"
                            stroke="white"
                            stroke-width="1"
                        />
                        <line
                            x1="35"
                            y1="70"
                            x2="60"
                            y2="70"
                            stroke="white"
                            stroke-width="1"
                        />
                        <circle cx="20" cy="30" r="2" fill="#fbbf24" opacity="0.7" />
                        <circle cx="80" cy="50" r="1.5" fill="#fbbf24" opacity="0.7" />
                        <circle cx="15" cy="60" r="1" fill="#fbbf24" opacity="0.7" />
                    </svg>
                </div>
                <span class="system-name">Peer Tutor Matching System</span>
            </div>

            <div class="header-links">
            @if(auth()->user()->role === 'student')
                <a href="/home/student" class="icon-link" title="Home">
                    <i class="fas fa-home"></i>
                </a>
            @elseif(auth()->user()->role === 'tutor')
                <a href="/home/tutor" class="icon-link" title="Home">
                    <i class="fas fa-home"></i>
                </a>
            @endif
            <a href="{{ route('profile.show', auth()->user()->id) }}" class="icon-link" title="Profile">
                <i class="fas fa-user"></i>
            </a>
              @if(auth()->user()->role === 'student')
                <a href="/chat/student" class="icon-link" title="Chat">
                    <i class="fas fa-comment-dots"></i>
                </a>
            @elseif(auth()->user()->role === 'tutor')
                <a href="/chat/tutor" class="icon-link" title="Chat">
                    <i class="fas fa-comment-dots"></i>
                </a>
            @endif
            <a href="/notifications" class="icon-link" title="Notifications">
                <i class="fas fa-bell"></i>
            </a>
            </div>
        </div>
    </header>

    <div class="container">
        @if(auth()->user()->role === 'tutor')
            @if(isset($bookingRequests) && count($bookingRequests) > 0)
                @foreach($bookingRequests as $booking)
                    <div class="notification-card">
                        <div class="notification-title">New Booking Request</div>
                        <div>
                            <strong>Student:</strong> {{ $booking->student_name }}<br>
                            <strong>Subject:</strong> {{ $booking->subject }}<br>
                            <strong>Date:</strong> {{ $booking->date }}<br>
                            <strong>Time:</strong> {{ $booking->time }}
                        </div>
                        <div class="notification-time">{{ $booking->created_at->diffForHumans() }}</div>
                        <div style="margin-top: 10px;">
                            <form method="POST" action="{{ route('booking.confirm', $booking->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" style="background:#22c55e;color:white;padding:8px 16px;border:none;border-radius:6px;margin-right:8px;cursor:pointer;">Confirm</button>
                            </form>
                            <form method="POST" action="{{ route('booking.reject', $booking->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" style="background:#ef4444;color:white;padding:8px 16px;border:none;border-radius:6px;cursor:pointer;">Reject</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="notification-card">
                    <div class="notification-title">No new booking requests.</div>
                </div>
            @endif
        @endif

        @if(auth()->user()->role === 'student')
            @if(isset($studentBookings) && count($studentBookings) > 0)
                @foreach($studentBookings as $booking)
                    @if($booking->status === 'pending')
                        <div class="notification-card">
                            <div class="notification-title">Booking Pending Confirmation</div>
                            <div>
                                <strong>Tutor:</strong> {{ $booking->tutor_name }}<br>
                                <strong>Subject:</strong> {{ $booking->subject }}<br>
                                <strong>Date:</strong> {{ $booking->date }}<br>
                                <strong>Time:</strong> {{ $booking->time }}
                            </div>
                            <div class="notification-time">{{ $booking->created_at->diffForHumans() }}</div>
                            <div style="margin-top: 10px; color: #f59e42;">
                                Waiting for tutor confirmation.
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        @endif


    <footer>
        No more notifications. Visit your
        <a href="{{ route('profile.show', auth()->user()->id) }}">profile</a> for more details.
    </footer>

    <script>
        @auth
        window.Echo.private('App.Models.User.{{ auth()->id() }}')
            .notification((notification) => {
                // Dynamically add notification card to the container
                const container = document.querySelector('.container');
                if (!container) return;

                // Create notification card element
                const card = document.createElement('div');
                card.className = 'notification-card';

                // Build inner HTML based on notification type
                let innerHTML = '';
                if (notification.type === 'App\\Notifications\\BookingSessionRequested') {
                    innerHTML += '<div class="notification-title">New Booking Request</div>';
                    innerHTML += `<div>
                        <strong>Student:</strong> ${notification.student_name}<br>
                        <strong>Subject:</strong> ${notification.subject}<br>
                        <strong>Date:</strong> ${notification.date}<br>
                        <strong>Time:</strong> ${notification.time}
                    </div>`;
                    innerHTML += `<div style="margin-top: 10px;">
                        <form method="POST" action="/tutor/session-requests/${notification.booking_session_id}/confirm" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:#22c55e;color:white;padding:8px 16px;border:none;border-radius:6px;margin-right:8px;cursor:pointer;">Confirm</button>
                        </form>
                        <form method="POST" action="/tutor/session-requests/${notification.booking_session_id}/reject" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:#ef4444;color:white;padding:8px 16px;border:none;border-radius:6px;cursor:pointer;">Reject</button>
                        </form>
                    </div>`;
                } else {
                    innerHTML += '<div class="notification-title">Notification</div>';
                    innerHTML += '<div>New notification received.</div>';
                }

                card.innerHTML = innerHTML;

                // Insert new notification at the top
                if (container.firstChild) {
                    container.insertBefore(card, container.firstChild);
                } else {
                    container.appendChild(card);
                }
            });
        @endauth
    </script>
</body>
</html>
