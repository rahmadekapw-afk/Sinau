<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sinau — Asisten Belajar AI</title>
    <meta name="description"
        content="Platform belajar cerdas berbasis AI. Tanya jawab, kuis otomatis, dan pembelajaran adaptif untuk pelajar Indonesia.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite('resources/css/landing.css')
</head>

<body>



        <!-- NAV -->
        <nav id="navbar">
            <div class="container nav-inner">
                <a href="/" class="logo">
                    <div class="logo-icon">🎓</div>
                    Sinau
                </a>
                <div class="nav-links">
                    <a href="#features">Fitur</a>
                    <a href="#how">Cara Kerja</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-ghost">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar Gratis</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- HERO -->
        <section class="hero">
            <div class="container">
                <div class="badge"><span class="badge-dot"></span> Powered by Gemini AI</div>
                <h1>Belajar Lebih Cerdas<br>dengan <span class="gradient">Sinau</span></h1>
                <p>Sinau adalah platform belajar berbasis AI yang membantu pelajar Indonesia memahami materi dengan cara
                    yang interaktif, personal, dan menyenangkan.</p>
                <div class="hero-btns">
                    @auth
                        <a href="{{ route('chat.index') }}" class="btn btn-primary">🤖 Mulai Belajar</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">Mulai Gratis →</a>
                        <a href="#features" class="btn btn-outline">Pelajari Lebih</a>
                    @endauth
                </div>

                <div class="hero-visual">
                    <div class="hero-card">
                        <div class="card-header">
                            <span class="dot dot-r"></span>
                            <span class="dot dot-y"></span>
                            <span class="dot dot-g"></span>
                        </div>
                        <div class="card-body">
                            <div class="chat-msg">
                                <div class="chat-avatar user">👤</div>
                                <div class="chat-bubble user">Jelaskan rumus luas lingkaran dengan analogi sederhana
                                </div>
                            </div>
                            <div class="chat-msg">
                                <div class="chat-avatar ai">🤖</div>
                                <div class="chat-bubble ai">Bayangkan kamu punya pizza bulat 🍕 Luas lingkaran = π × r²
                                    artinya kamu menghitung berapa banyak "kotak kecil" yang bisa masuk ke dalam pizza
                                    itu. <strong>r</strong> adalah jarak dari tengah pizza ke pinggirnya, dan <strong>π
                                        ≈ 3.14</strong> adalah angka ajaib yang selalu muncul di lingkaran!</div>
                            </div>
                            <div class="chat-msg">
                                <div class="chat-avatar user">👤</div>
                                <div class="chat-bubble user">Wah paham! Kalau r = 7cm, luasnya berapa?</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section class="features" id="features">
            <div class="container">
                <div class="features-header">
                    <div class="section-label">Fitur Unggulan</div>
                    <h2 class="section-title">Semua yang Kamu Butuhkan untuk Belajar</h2>
                    <p class="section-desc">Teknologi AI terbaru dipadukan dengan pendekatan edukatif yang menyenangkan.
                    </p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon fi-1">🤖</div>
                        <h3>Tanya Jawab AI</h3>
                        <p>Tanyakan materi apapun dan dapatkan penjelasan yang mudah dipahami, lengkap dengan contoh dan
                            analogi.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon fi-2">📚</div>
                        <h3>Multi Mata Pelajaran</h3>
                        <p>Matematika, IPA, Bahasa, Sejarah, dan banyak lagi — semua dalam satu platform.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon fi-3">💬</div>
                        <h3>Percakapan Kontekstual</h3>
                        <p>AI mengingat konteks percakapanmu, sehingga kamu bisa bertanya lanjutan tanpa mengulang.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon fi-4">📜</div>
                        <h3>Riwayat Belajar</h3>
                        <p>Semua sesi belajarmu tersimpan rapi. Kembali kapan saja untuk review materi.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon fi-5">🇮🇩</div>
                        <h3>Bahasa Indonesia</h3>
                        <p>AI berbicara dalam Bahasa Indonesia yang ramah dan mudah dimengerti oleh pelajar.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon fi-6">📝</div>
                        <h3>Quiz Generator</h3>
                        <p>Segera hadir — latihan soal otomatis yang disesuaikan dengan kemampuanmu.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- HOW IT WORKS -->
        <section class="how" id="how">
            <div class="container">
                <div class="features-header">
                    <div class="section-label">Cara Kerja</div>
                    <h2 class="section-title">Mulai Belajar dalam 3 Langkah</h2>
                    <p class="section-desc">Tidak perlu setup rumit. Daftar, tanya, dan pelajari.</p>
                </div>
                <div class="steps">
                    <div class="step">
                        <div class="step-num">1</div>
                        <h3>Buat Akun</h3>
                        <p>Daftar gratis hanya dengan email dan password. Tidak perlu kartu kredit.</p>
                    </div>
                    <div class="step">
                        <div class="step-num">2</div>
                        <h3>Tanyakan Materi</h3>
                        <p>Ketik pertanyaanmu tentang pelajaran apapun. AI akan menjawab dengan jelas.</p>
                    </div>
                    <div class="step">
                        <div class="step-num">3</div>
                        <h3>Pahami & Ulangi</h3>
                        <p>Baca jawaban, tanyakan lanjutan, dan review kapan saja dari riwayat belajar.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta">
            <div class="container">
                <div class="cta-box">
                    <h2>Siap Belajar Lebih Cerdas?</h2>
                    <p>Bergabung sekarang dan rasakan pengalaman belajar yang dipersonalisasi oleh AI.</p>
                    @auth
                        <a href="{{ route('chat.index') }}" class="btn btn-white">🤖 Buka Chat AI</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-white">Daftar Gratis Sekarang →</a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer>
            <div class="container footer-inner">
                <div class="footer-copy">© {{ date('Y') }} Sinau. Platform belajar berbasis AI untuk pelajar Indonesia.
                </div>
                <div class="footer-links">
                    <a href="#features">Fitur</a>
                    <a href="#how">Cara Kerja</a>
                    @guest <a href="{{ route('login') }}">Masuk</a> @endguest
                </div>
            </div>
        </footer>

        <script>
            // Navbar scroll effect
            const nb = document.getElementById('navbar');
            window.addEventListener('scroll', () => nb.classList.toggle('scrolled', window.scrollY > 20));

            // Smooth scroll
            document.querySelectorAll('a[href^="#"]').forEach(a => {
                a.addEventListener('click', e => {
                    e.preventDefault();
                    const t = document.querySelector(a.getAttribute('href'));
                    if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });
        </script>
    </body>

</html>