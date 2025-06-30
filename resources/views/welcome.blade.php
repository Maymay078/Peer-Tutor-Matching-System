<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Peer Tutor Matching System</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Figtree', sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                color: #333;
            }
            
            /* Navigation */
            .navbar {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                padding: 1rem 0;
                position: sticky;
                top: 0;
                z-index: 100;
            }
            
            .nav-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .logo {
                display: flex;
                align-items: center;
                gap: 15px;
            }
            
            .logo-icon {
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            }
            
            .logo-svg {
                width: 30px;
                height: 30px;
            }
            
            .logo h1 {
                font-size: 1.8rem;
                font-weight: 700;
                color: #1f2937;
                margin: 0;
            }
            
            .nav-links {
                display: flex;
                gap: 2rem;
                align-items: center;
            }
            
            .nav-links a {
                text-decoration: none;
                color: #374151;
                font-weight: 500;
                transition: color 0.3s ease;
                padding: 0.5rem 1rem;
                border-radius: 8px;
                transition: all 0.3s ease;
            }
            
            .nav-links a:hover {
                color: #4f46e5;
                background: rgba(79, 70, 229, 0.1);
            }
            
            .btn-primary {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                color: white !important;
                padding: 0.75rem 1.5rem !important;
                border-radius: 12px;
                font-weight: 600;
                box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
                transition: all 0.3s ease;
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
                background: linear-gradient(135deg, #3730a3 0%, #6d28d9 100%) !important;
            }
            
            /* Hero Section */
            .hero {
                padding: 6rem 2rem;
                text-align: center;
                color: white;
                position: relative;
                overflow: hidden;
            }
            
            .hero::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
                animation: float 20s ease-in-out infinite;
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(1deg); }
            }
            
            .hero-content {
                position: relative;
                z-index: 2;
                max-width: 800px;
                margin: 0 auto;
            }
            
            .hero h1 {
                font-size: 3.5rem;
                font-weight: 700;
                margin-bottom: 1.5rem;
                text-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                line-height: 1.2;
            }
            
            .hero p {
                font-size: 1.3rem;
                opacity: 0.9;
                margin-bottom: 3rem;
                line-height: 1.6;
            }
            
            /* Features Section */
            .features {
                padding: 6rem 2rem;
                background: white;
                position: relative;
            }
            
            .container {
                max-width: 1200px;
                margin: 0 auto;
            }
            
            .section-title {
                text-align: center;
                margin-bottom: 4rem;
            }
            
            .section-title h2 {
                font-size: 2.5rem;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 1rem;
            }
            
            .section-title p {
                font-size: 1.1rem;
                color: #6b7280;
                max-width: 600px;
                margin: 0 auto;
                line-height: 1.6;
            }
            
            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
                gap: 3rem;
                margin-bottom: 4rem;
            }
            
            .feature-card {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                padding: 3rem 2rem;
                border-radius: 20px;
                text-align: center;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            
            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            }
            
            .feature-card.students {
                background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
                border-color: rgba(59, 130, 246, 0.2);
            }
            
            .feature-card.tutors {
                background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
                border-color: rgba(34, 197, 94, 0.2);
            }
            
            .feature-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 2rem;
                background: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
                font-size: 2.5rem;
            }
            
            .feature-card h3 {
                font-size: 1.8rem;
                font-weight: 600;
                margin-bottom: 1rem;
                color: #1f2937;
            }
            
            .feature-list {
                list-style: none;
                text-align: left;
                margin-bottom: 2rem;
            }
            
            .feature-list li {
                padding: 0.75rem 0;
                color: #4b5563;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 1rem;
            }
            
            .feature-list li::before {
                content: '✓';
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                color: white;
                width: 24px;
                height: 24px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.8rem;
                font-weight: bold;
                flex-shrink: 0;
            }
            
            /* Key Features */
            .key-features {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
                margin-top: 4rem;
            }
            
            .key-feature {
                text-align: center;
                padding: 2rem;
                background: rgba(255, 255, 255, 0.5);
                border-radius: 16px;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                transition: all 0.3s ease;
            }
            
            .key-feature:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            }
            
            .key-feature-icon {
                font-size: 3rem;
                margin-bottom: 1rem;
                display: block;
            }
            
            .key-feature h4 {
                font-size: 1.3rem;
                font-weight: 600;
                margin-bottom: 1rem;
                color: #1f2937;
            }
            
            .key-feature p {
                color: #6b7280;
                line-height: 1.6;
            }
            
            /* Footer */
            .footer {
                background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
                color: white;
                padding: 3rem 2rem 2rem;
                text-align: center;
            }
            
            .footer p {
                opacity: 0.8;
                margin-bottom: 1rem;
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .nav-container {
                    padding: 0 1rem;
                }
                
                .nav-links {
                    gap: 1rem;
                }
                
                .hero {
                    padding: 4rem 1rem;
                }
                
                .hero h1 {
                    font-size: 2.5rem;
                }
                
                .hero p {
                    font-size: 1.1rem;
                }
                
                .features {
                    padding: 4rem 1rem;
                }
                
                .features-grid {
                    grid-template-columns: 1fr;
                    gap: 2rem;
                }
                
                .feature-card {
                    padding: 2rem 1.5rem;
                }
                
                .key-features {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo">
                    <div class="logo-icon">
                        <svg class="logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <!-- Book base -->
                            <rect x="25" y="45" width="50" height="35" rx="3" fill="white"/>
                            <!-- Book pages -->
                            <rect x="25" y="45" width="25" height="35" rx="3" fill="#e0e7ff"/>
                            <!-- Graduation cap -->
                            <path d="M50 15L85 25L50 35L15 25Z" fill="white"/>
                            <path d="M50 35L50 45L85 35L85 25Z" fill="#e0e7ff"/>
                            <!-- Lines on book -->
                            <line x1="35" y1="52" x2="65" y2="52" stroke="#4f46e5" stroke-width="1"/>
                            <line x1="35" y1="58" x2="65" y2="58" stroke="#4f46e5" stroke-width="1"/>
                            <line x1="35" y1="64" x2="65" y2="64" stroke="#4f46e5" stroke-width="1"/>
                            <line x1="35" y1="70" x2="60" y2="70" stroke="#4f46e5" stroke-width="1"/>
                        </svg>
                    </div>
                    <h1>Peer Tutor Matching System</h1>
                </div>
                <div class="nav-links">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}">Log in</a>
                        @if (Route::has('register'))
                            <button id="registerBtn" class="btn-primary">Register</button>
                        @endif
                    @endif
                </div>
            </div>
        </nav>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const registerBtn = document.getElementById('registerBtn');
                if (!registerBtn) return;
                registerBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Overlay for background dim
                    const overlay = document.createElement('div');
                    overlay.style.position = 'fixed';
                    overlay.style.top = 0;
                    overlay.style.left = 0;
                    overlay.style.width = '100vw';
                    overlay.style.height = '100vh';
                    overlay.style.background = 'rgba(31, 41, 55, 0.45)';
                    overlay.style.zIndex = 9998;
                    overlay.style.display = 'flex';
                    overlay.style.alignItems = 'center';
                    overlay.style.justifyContent = 'center';

                    // Dialog
                    const dialog = document.createElement('div');
                    dialog.style.background = 'white';
                    dialog.style.borderRadius = '22px';
                    dialog.style.boxShadow = '0 12px 48px rgba(79,70,229,0.18), 0 2px 8px rgba(0,0,0,0.08)';
                    dialog.style.padding = '3rem 2.8rem 2.2rem 2.8rem';
                    dialog.style.maxWidth = '440px';
                    dialog.style.width = '98vw';
                    dialog.style.display = 'flex';
                    dialog.style.flexDirection = 'column';
                    dialog.style.alignItems = 'center';
                    dialog.style.position = 'relative';
                    dialog.style.zIndex = 9999;
                    dialog.style.border = '1.5px solid #e0e7ff';
                    dialog.style.transition = 'box-shadow 0.2s';

                    dialog.innerHTML = `
                        <div style="display:flex;align-items:center;gap:0.7rem;margin-bottom:0.5rem;">
                            <span style="font-size:2.1rem;">✨</span>
                            <h2 style="font-size:1.7rem;font-weight:800;color:#4f46e5;letter-spacing:-1px;">Welcome!</h2>
                        </div>
                        <p style="font-size:1.13rem;color:#374151;margin-bottom:1.7rem;text-align:justify;line-height:1.6;">
                            <span style="font-size:1.05rem;color:#6366f1;font-weight:600;">Peer Tutor Matching System</span><br>
                            Please select your registration type to get started. <b>Student</b> to find tutors, or <b>Tutor</b> to offer your expertise.
                        </p>
                        <div style="display:flex;gap:2.2rem;margin-bottom:1.7rem;">
                            <button id="studentBtn" type="button" style="display:flex;flex-direction:column;align-items:center;gap:0.6rem;padding:1.3rem 1.7rem;background:#eef2ff;border:2.5px solid #6366f1;color:#4f46e5;border-radius:14px;font-weight:700;cursor:pointer;transition:background 0.18s, color 0.18s, box-shadow 0.18s;box-shadow:0 2px 8px rgba(99,102,241,0.08);font-size:1.09rem;">
                                <span style="font-size:2.5rem;">🎓</span>
                                Student
                            </button>
                            <button id="tutorBtn" type="button" style="display:flex;flex-direction:column;align-items:center;gap:0.6rem;padding:1.3rem 1.7rem;background:#ecfdf5;border:2.5px solid #10b981;color:#059669;border-radius:14px;font-weight:700;cursor:pointer;transition:background 0.18s, color 0.18s, box-shadow 0.18s;box-shadow:0 2px 8px rgba(16,185,129,0.08);font-size:1.09rem;">
                                <span style="font-size:2.5rem;">👨‍🏫</span>
                                Tutor
                            </button>
                        </div>
                        <button id="cancelBtn" type="button" style="margin-top:0.5rem;background:none;border:none;color:#6b7280;text-decoration:underline;cursor:pointer;font-size:1.07rem;">Cancel</button>
                    `;

                    // Center dialog in overlay
                    overlay.appendChild(dialog);
                    document.body.appendChild(overlay);

                    // Animate dialog in
                    dialog.animate([{ opacity: 0, transform: 'scale(0.95)' }, { opacity: 1, transform: 'scale(1)' }], { duration: 180, fill: 'forwards' });

                    // Button handlers
                    dialog.querySelector('#studentBtn').onclick = function() {
                        window.location.href = "{{ route('register.student.form') }}";
                        document.body.removeChild(overlay);
                    };
                    dialog.querySelector('#tutorBtn').onclick = function() {
                        window.location.href = "{{ route('register.tutor.form') }}";
                        document.body.removeChild(overlay);
                    };
                    dialog.querySelector('#cancelBtn').onclick = function() {
                        document.body.removeChild(overlay);
                    };
                    // Close on overlay click (but not dialog click)
                    overlay.onclick = function(ev) {
                        if (ev.target === overlay) document.body.removeChild(overlay);
                    };
                    // ESC key closes
                    document.addEventListener('keydown', function escHandler(ev) {
                        if (ev.key === 'Escape') {
                            if (document.body.contains(overlay)) document.body.removeChild(overlay);
                            document.removeEventListener('keydown', escHandler);
                        }
                    });
                });
            });
        </script>

        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Connect. Learn. Excel.</h1>
                <p>Join our peer tutoring community where students and tutors come together to enhance learning experiences and achieve academic success.</p>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <div class="container">
                <div class="section-title">
                    <h2>Empowering Learning Together</h2>
                    <p>Whether you're seeking help or sharing knowledge, our platform connects you with the right people at the right time.</p>
                </div>

                <div class="features-grid">
                    <!-- For Students -->
                    <div class="feature-card students">
                        <div class="feature-icon">
                            🎓
                        </div>
                        <h3>For Students</h3>
                        <ul class="feature-list">
                            <li>Find qualified tutors in your subject area</li>
                            <li>Schedule flexible tutoring sessions</li>
                            <li>Access study resources and materials</li>
                            <li>Track your learning progress</li>
                            <li>Connect with study groups</li>
                        </ul>
                    </div>

                    <!-- For Tutors -->
                    <div class="feature-card tutors">
                        <div class="feature-icon">
                            👨‍🏫
                        </div>
                        <h3>For Tutors</h3>
                        <ul class="feature-list">
                            <li>Share your knowledge and expertise</li>
                            <li>Set your own schedule and rates</li>
                            <li>Build your tutoring profile</li>
                            <li>Connect with students who need help</li>
                            <li>Grow your teaching experience</li>
                        </ul>
                    </div>
                </div>

                <!-- Key Features -->
                <div class="key-features">
                    <div class="key-feature">
                        <span class="key-feature-icon">🚀</span>
                        <h4>Expert Tutors</h4>
                        <p>Connect with knowledgeable tutors who are passionate about helping you succeed in your academic journey.</p>
                    </div>
                    <div class="key-feature">
                        <span class="key-feature-icon">⏰</span>
                        <h4>Flexible Scheduling</h4>
                        <p>Book tutoring sessions that fit perfectly into your busy schedule, available 24/7 for your convenience.</p>
                    </div>
                    <div class="key-feature">
                        <span class="key-feature-icon">💬</span>
                        <h4>Easy Communication</h4>
                        <p>Seamless messaging system between tutors and students for effective learning collaboration.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <p>&copy; {{ date('Y') }} Peer Tutor Matching System. Empowering education through peer learning.</p>
            </div>
        </footer>
    </body>
</html>
</html>
