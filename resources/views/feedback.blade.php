@extends('layouts.profile-layout-home')

@section('header')
    <div class="header-left">
        <h1 class="text-white font-bold text-xl">Feedback</h1>
    </div>
@endsection

@section('content')
    <style>
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
        .bordered-frame {
            border: 2px solid #4f46e5;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(79, 70, 229, 0.3);
            background-color: #f9fafb;
        }
        .tab-button {
            padding: 8px 16px;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .tab-button.active {
            background-color: #4f46e5;
            color: white;
            font-weight: 700;
            border-bottom: 3px solid #3730a3;
        }
        .tab-button.inactive {
            background-color: transparent;
            color: #6b7280;
            font-weight: 500;
        }
        .tab-button {
            box-shadow: 0 2px 6px rgba(79, 70, 229, 0.4);
        }
        .tab-button:hover {
            background-color: #4338ca;
            color: white;
        }
        .feedback-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(79,70,229,0.07);
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        .feedback-user-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #6366f1;
        }
        .feedback-content {
            flex: 1;
        }
        .feedback-user-name {
            font-weight: 600;
            color: #4f46e5;
            margin-bottom: 4px;
        }
        .feedback-rating {
            color: #fbbf24;
            margin-bottom: 6px;
        }
        .feedback-comment {
            color: #374151;
        }
    </style>
    <div class="container mx-auto p-6 bg-white rounded shadow mt-6 max-w-4xl">
        @if(auth()->user()->role === 'tutor')
            <h3 class="text-xl font-bold mb-4">Feedback Received from Students</h3>
            @if(isset($feedbacks) && count($feedbacks) > 0)
                @foreach($feedbacks as $feedback)
                    <div class="feedback-card">
                        <img src="{{ $feedback->from_user_profile_image ? (Str::startsWith($feedback->from_user_profile_image, ['http://', 'https://']) ? $feedback->from_user_profile_image : asset('storage/' . $feedback->from_user_profile_image)) : 'https://ui-avatars.com/api/?name=' . urlencode($feedback->from_user_name) . '&background=random&color=fff' }}" alt="Student Image" class="feedback-user-img" />
                        <div class="feedback-content">
                            <div class="feedback-user-name">{{ $feedback->from_user_name }}</div>
                            <div class="feedback-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $feedback->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="feedback-comment">{{ $feedback->comment }}</div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-gray-500 italic">No feedback received yet.</div>
            @endif
        @else
            <h2 class="text-2xl font-semibold mb-6">Feedback & Ratings</h2>
            <div class="flex space-x-6 border-b border-gray-300 mb-6 text-lg font-semibold">
                <button id="toRateBtn" class="tab-button active">Rate My Tutors</button>
                <button id="myFeedbackBtn" class="tab-button inactive">Feedback Given</button>
            </div>
            <div id="toRateSection">
                @forelse ($tutors as $tutor)
                    <div class="flex items-start border rounded-lg p-4 mb-6 shadow-sm">
                        <div class="flex-shrink-0 mr-4">
                            @php
                                $profileImage = $tutor->user->profile_image;
                                $imageSrc = $profileImage
                                    ? (Str::startsWith($profileImage, ['http://', 'https://'])
                                        ? $profileImage
                                        : asset('storage/' . $profileImage))
                                    : 'https://api.dicebear.com/7.x/micah/svg?seed=' . urlencode($tutor->user->full_name) . '&backgroundColor=e0f2fe,c7d2fe,fae8ff&radius=50';
                            @endphp
                            <img src="{{ $imageSrc }}" alt="Profile Image" class="w-24 h-24 rounded-full object-cover" />
                        </div>
                        <div class="flex-grow">
                            <div class="mb-2 font-semibold text-lg">{{ $tutor->user->full_name }}</div>
                            <div class="mb-4 text-gray-600">
                                @php
                                    $subjects = $tutor->expertise ?? [];
                                    if (is_string($subjects)) {
                                        $subjects = json_decode($subjects, true) ?: [];
                                    }
                                @endphp
                                @foreach ($subjects as $subject)
                                    <span class="inline-block bg-indigo-100 text-indigo-700 px-2 py-1 rounded mr-2 text-sm">{{ $subject['name'] ?? '' }}</span>
                                @endforeach
                            </div>
                            <form method="POST" action="{{ route('feedback.submit') }}">
                                @csrf
                                <input type="hidden" name="tutor_id" value="{{ $tutor->id }}" />
                                <div class="flex items-center mb-4 space-x-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <label>
                                            <input type="radio" name="rating_{{ $tutor->id }}" value="{{ $i }}" class="hidden peer" required />
                                            <svg class="w-6 h-6 cursor-pointer text-gray-300 peer-checked:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.39 2.462a1 1 0 00-.364 1.118l1.287 3.974c.3.921-.755 1.688-1.54 1.118l-3.39-2.462a1 1 0 00-1.175 0l-3.39 2.462c-.784.57-1.838-.197-1.539-1.118l1.287-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z" />
                                            </svg>
                                        </label>
                                    @endfor
                                </div>
                                <div class="mb-4">
                                    <textarea name="comments_{{ $tutor->id }}" rows="4" placeholder="Open-ended Comments" class="w-full border border-gray-300 rounded p-2 resize-none"></textarea>
                                </div>
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Rate</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-500 italic">No completed sessions with tutors yet. Complete a session to provide feedback.</div>
                @endforelse
            </div>
            <div id="myFeedbackSection" style="display:none;">
                <h3 class="text-xl font-bold mb-4">Feedback Given to Tutors</h3>
                @if(isset($feedbacks) && count($feedbacks) > 0)
                    @foreach($feedbacks as $feedback)
                        <div class="feedback-card">
                            <img src="{{ $feedback['to_user_profile_image'] ? (Str::startsWith($feedback['to_user_profile_image'], ['http://', 'https://']) ? $feedback['to_user_profile_image'] : asset('storage/' . $feedback['to_user_profile_image'])) : 'https://ui-avatars.com/api/?name=' . urlencode($feedback['to_user_name']) . '&background=random&color=fff' }}" alt="Tutor Image" class="feedback-user-img" />
                            <div class="feedback-content">
                                <div class="feedback-user-name">{{ $feedback['to_user_name'] }}</div>
                                <div class="feedback-date text-gray-500 text-sm mb-2">{{ $feedback['created_at'] }}</div>
                                <div class="feedback-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $feedback['rating'])
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="feedback-comment">{{ $feedback['comment'] }}</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-gray-500 italic">No feedback given yet.</div>
                @endif
            </div>
        @endif
    </div>
    <script>
        @if(!(auth()->user()->role === 'tutor'))
        document.getElementById('toRateBtn').addEventListener('click', function() {
            document.getElementById('toRateSection').style.display = 'block';
            document.getElementById('myFeedbackSection').style.display = 'none';
            this.classList.add('active');
            this.classList.remove('inactive');
            document.getElementById('myFeedbackBtn').classList.remove('active');
            document.getElementById('myFeedbackBtn').classList.add('inactive');
        });

        document.getElementById('myFeedbackBtn').addEventListener('click', function() {
            document.getElementById('toRateSection').style.display = 'none';
            document.getElementById('myFeedbackSection').style.display = 'block';
            this.classList.add('active');
            this.classList.remove('inactive');
            document.getElementById('toRateBtn').classList.remove('active');
            document.getElementById('toRateBtn').classList.add('inactive');
        });
        @endif
    </script>
@endsection
