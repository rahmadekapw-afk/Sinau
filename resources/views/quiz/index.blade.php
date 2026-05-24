<x-app-layout>
    <div class="py-8 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Dynamic Header Card -->
            <div style="background: linear-gradient(135deg, #B91C1C 0%, #EF4444 100%);" class="relative overflow-hidden rounded-2xl p-8 text-white shadow-xl border-l-8 border-[#991B1B]">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2 flex items-center gap-3">
                            📝 AI Quiz Generator
                        </h1>
                        <p class="text-white/90 text-lg font-medium">Uji pemahaman belajarmu dengan soal berkualitas yang dirancang instan oleh AI Sinau.</p>
                    </div>
                    <div class="shrink-0">
                        <span class="bg-white/20 text-white font-bold px-4 py-2 rounded-xl text-sm border border-white/10 shadow-sm backdrop-blur-md">
                            🎯 Kurikulum Nasional
                        </span>
                    </div>
                </div>
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/5 rounded-full blur-2xl"></div>
                <div class="absolute -right-5 -bottom-5 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
            </div>

            <!-- MAIN CONTAINER: Stage Controller -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden min-h-[450px] transition-all duration-300">
                
                <!-- STAGE 1: Generator Setup Form -->
                <div id="stage-setup" class="p-8 space-y-8 block">
                    <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Atur Kuis Baru</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pilih mata pelajaran, tingkat kesulitan, dan jumlah soal yang Anda inginkan.</p>
                    </div>

                    <form id="quiz-setup-form" class="space-y-6">
                        @csrf
                        
                        <!-- Subject Selection -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Pilih Mata Pelajaran</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <select name="subject_id" id="subject-select" class="w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#B91C1C] focus:ring focus:ring-[#B91C1C]/20 transition-all py-3 px-4" onchange="toggleCustomSubject()">
                                        @foreach($subjects as $subj)
                                            <option value="{{ $subj->id }}">{{ $subj->icon }} {{ $subj->name }}</option>
                                        @endforeach
                                        <option value="custom">✏️ Subjek Lain (Tulis Kustom)</option>
                                    </select>
                                </div>
                                <div id="custom-subject-container" class="hidden">
                                    <input type="text" id="custom-subject-input" placeholder="Misal: Biologi Sel, Trigonometri, dll" class="w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#B91C1C] focus:ring focus:ring-[#B91C1C]/20 transition-all py-3 px-4">
                                </div>
                            </div>
                        </div>

                        <!-- Difficulty Selection Cards -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Tingkat Kesulitan</label>
                            <div class="grid grid-cols-3 gap-4">
                                <label class="relative flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 border-2 border-transparent rounded-2xl cursor-pointer hover:bg-red-50/50 dark:hover:bg-red-900/10 transition-all group">
                                    <input type="radio" name="difficulty" value="mudah" class="sr-only" checked onclick="selectDifficulty(this)">
                                    <span class="text-3xl mb-1">🌱</span>
                                    <span class="font-bold text-sm text-gray-700 dark:text-gray-200">Mudah</span>
                                    <div class="absolute inset-0 border-2 border-transparent rounded-2xl pointer-events-none transition-all active-border" style="border-color: #B91C1C;"></div>
                                </label>

                                <label class="relative flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 border-2 border-transparent rounded-2xl cursor-pointer hover:bg-red-50/50 dark:hover:bg-red-900/10 transition-all group">
                                    <input type="radio" name="difficulty" value="sedang" class="sr-only" onclick="selectDifficulty(this)">
                                    <span class="text-3xl mb-1">🔥</span>
                                    <span class="font-bold text-sm text-gray-700 dark:text-gray-200">Sedang</span>
                                    <div class="absolute inset-0 border-2 border-transparent rounded-2xl pointer-events-none transition-all active-border"></div>
                                </label>

                                <label class="relative flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 border-2 border-transparent rounded-2xl cursor-pointer hover:bg-red-50/50 dark:hover:bg-red-900/10 transition-all group">
                                    <input type="radio" name="difficulty" value="sulit" class="sr-only" onclick="selectDifficulty(this)">
                                    <span class="text-3xl mb-1">⚡</span>
                                    <span class="font-bold text-sm text-gray-700 dark:text-gray-200">Sulit</span>
                                    <div class="absolute inset-0 border-2 border-transparent rounded-2xl pointer-events-none transition-all active-border"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Question Count Selector -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Jumlah Soal</label>
                            <div class="flex gap-4">
                                @foreach([5, 10, 15] as $cnt)
                                    <label class="flex-1">
                                        <input type="radio" name="count" value="{{ $cnt }}" class="sr-only" {{ $cnt === 5 ? 'checked' : '' }} onclick="selectCount(this)">
                                        <div class="text-center py-3 bg-gray-50 dark:bg-gray-700 rounded-2xl font-bold text-sm text-gray-700 dark:text-gray-200 cursor-pointer border-2 border-transparent hover:bg-red-50/50 transition-all count-pill" style="{{ $cnt === 5 ? 'border-color: #B91C1C; background-color: #FEF2F2; color: #B91C1C;' : '' }}">
                                            {{ $cnt }} Soal
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Error Message Area -->
                        <div id="setup-error" class="hidden bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-4 rounded-2xl border border-red-100 dark:border-red-900/30 text-sm font-medium"></div>

                        <!-- Submit Button -->
                        <button type="submit" style="background-color: #B91C1C;" class="w-full py-4 text-white font-bold text-lg rounded-2xl shadow-lg hover:opacity-95 transition-all border-b-4 border-[#991B1B] flex items-center justify-center gap-3">
                            🚀 Buat Kuis Sekarang
                        </button>
                    </form>
                </div>

                <!-- STAGE 2: Loader & Spinner Screen -->
                <div id="stage-loading" class="p-8 hidden flex-col items-center justify-center text-center space-y-6 py-20 animate-pulse">
                    <div class="relative w-24 h-24 flex items-center justify-center">
                        <div class="absolute inset-0 rounded-full border-4 border-red-100 dark:border-gray-700"></div>
                        <div style="border-top-color: #B91C1C;" class="absolute inset-0 rounded-full border-4 border-transparent animate-spin"></div>
                        <span class="text-4xl animate-bounce">🧠</span>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">AI Sedang Menyusun Soal Terbaik...</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm max-w-md mx-auto">Kami mengumpulkan kurikulum yang sesuai untuk membuat soal kuis yang menantang dan interaktif.</p>
                    </div>
                    <div class="bg-red-50 dark:bg-gray-700/50 p-4 rounded-2xl max-w-sm border border-red-100 dark:border-gray-600">
                        <p class="text-xs text-[#B91C1C] dark:text-red-400 font-bold mb-1">💡 Tips Cepat</p>
                        <p id="loading-tip" class="text-xs text-gray-600 dark:text-gray-300">Gunakan kuis ini untuk melatih ingatan jangka panjang Anda (active recall).</p>
                    </div>
                </div>

                <!-- STAGE 3: Active Quiz Gameplay Slider -->
                <div id="stage-quiz" class="p-8 hidden space-y-6">
                    <!-- Progress Bar Header -->
                    <div class="flex items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div>
                            <span id="quiz-subject-badge" class="bg-red-100 text-[#B91C1C] font-bold text-xs px-3 py-1.5 rounded-full uppercase tracking-wider">Matematika</span>
                            <span id="quiz-diff-badge" class="bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 font-bold text-xs px-3 py-1.5 rounded-full uppercase tracking-wider ml-2">Sedang</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Soal <span id="current-question-num">1</span> dari <span id="total-questions-num">5</span></span>
                        </div>
                    </div>

                    <!-- Visual Progress Line -->
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-2.5 rounded-full overflow-hidden">
                        <div id="quiz-progress-bar" style="background-color: #B91C1C; width: 20%;" class="h-full transition-all duration-300"></div>
                    </div>

                    <!-- Dynamic Question Box -->
                    <div class="py-4 space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6 border border-gray-100 dark:border-gray-600">
                            <p id="quiz-question-text" class="text-lg font-bold text-gray-800 dark:text-gray-100 leading-relaxed">
                                Apa hasil dari 2x + 5 = 15?
                            </p>
                        </div>

                        <!-- Options Choices grid -->
                        <div class="grid grid-cols-1 gap-4" id="quiz-options-container">
                            <!-- JS will inject options here -->
                        </div>
                    </div>

                    <!-- Slide Navigation Footer -->
                    <div class="flex justify-between items-center gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button id="btn-prev" onclick="prevQuestion()" class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 font-bold rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center gap-2">
                            ← Sebelumnya
                        </button>
                        <button id="btn-next" onclick="nextQuestion()" style="background-color: #B91C1C;" class="px-8 py-3 text-white font-bold rounded-2xl hover:opacity-95 shadow-md border-b-4 border-[#991B1B] transition flex items-center gap-2">
                            Selanjutnya →
                        </button>
                    </div>
                </div>

                <!-- STAGE 4: Quiz Grading & Review Explanations -->
                <div id="stage-results" class="p-8 hidden space-y-8">
                    <!-- Congratulatory & Score Card -->
                    <div class="text-center space-y-4 py-6 border-b border-gray-100 dark:border-gray-700">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-yellow-50 text-5xl animate-bounce">
                            🏆
                        </div>
                        <div class="space-y-1">
                            <h2 class="text-3xl font-extrabold text-gray-800 dark:text-gray-100">Kuis Selesai!</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Berikut adalah pencapaian performa belajar Anda kali ini.</p>
                        </div>
                        
                        <!-- Circular Score Display -->
                        <div class="inline-block relative">
                            <div class="flex flex-col items-center justify-center p-6 bg-red-50 dark:bg-gray-700/60 rounded-3xl border-2 border-[#B91C1C]/10 min-w-[200px]">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">SKOR ANDA</span>
                                <span class="text-5xl font-extrabold text-[#B91C1C]" id="result-score">80</span>
                                <span class="text-xs text-gray-400 mt-1" id="result-correct-ratio">4 dari 5 Soal Benar</span>
                            </div>
                        </div>
                    </div>

                    <!-- Collapsible Question Breakdown -->
                    <div class="space-y-6">
                        <h3 class="font-bold text-gray-800 dark:text-gray-100 text-lg flex items-center gap-2">
                            🔍 Tinjauan Soal & Pembahasan AI
                        </h3>
                        
                        <div class="space-y-4" id="results-breakdown-container">
                            <!-- JS will inject breakdown here -->
                        </div>
                    </div>

                    <!-- Reset Options -->
                    <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row gap-4">
                        <button onclick="resetQuiz()" class="flex-1 py-4 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 font-bold text-lg rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            🔄 Buat Kuis Baru
                        </button>
                        <a href="{{ route('dashboard') }}" style="background-color: #B91C1C;" class="flex-1 py-4 text-white font-bold text-lg rounded-2xl shadow-lg hover:opacity-95 transition border-b-4 border-[#991B1B] text-center flex items-center justify-center">
                            🏠 Kembali ke Dashboard
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Active Gameplay Javascript Logic -->
    <script>
        // Setup State
        let currentQuestions = [];
        let userAnswers = {};
        let currentQuestionIndex = 0;
        let activeSubjectName = '';
        let activeDiff = '';

        const tips = [
            "Latihan soal secara berkala (spaced repetition) memperkuat ingatan otak Anda.",
            "Salah dalam latihan adalah kesempatan emas untuk belajar kembali lewat pembahasan AI.",
            "Bacalah pembahasan AI dengan seksama untuk memahami cara pengerjaan yang benar.",
            "Tingkat kesulitan tinggi memberikan stimulus berpikir kritis yang lebih dalam.",
            "Gunakan visualisasi atau corat-coret kertas jika menemui soal perhitungan matematika/fisika."
        ];

        // Rotation timer for tips
        setInterval(() => {
            const tipEl = document.getElementById('loading-tip');
            if (tipEl) {
                tipEl.innerText = tips[Math.floor(Math.random() * tips.length)];
            }
        }, 4000);

        function toggleCustomSubject() {
            const select = document.getElementById('subject-select');
            const container = document.getElementById('custom-subject-container');
            if (select.value === 'custom') {
                container.classList.remove('hidden');
                document.getElementById('custom-subject-input').focus();
            } else {
                container.classList.add('hidden');
            }
        }

        function selectDifficulty(input) {
            // Reset colors of all border layers
            document.querySelectorAll('.active-border').forEach(el => el.style.borderColor = 'transparent');
            // Highlight selected card
            const border = input.parentNode.querySelector('.active-border');
            border.style.borderColor = '#B91C1C';
        }

        function selectCount(input) {
            // Reset color of all count pills
            document.querySelectorAll('.count-pill').forEach(el => {
                el.style.borderColor = 'transparent';
                el.style.backgroundColor = '';
                el.style.color = '';
            });
            // Highlight selected pill
            const pill = input.parentNode.querySelector('.count-pill');
            pill.style.borderColor = '#B91C1C';
            pill.style.backgroundColor = '#FEF2F2';
            pill.style.color = '#B91C1C';
        }

        // Setup Submit Form Event Listener
        document.getElementById('quiz-setup-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const errBox = document.getElementById('setup-error');
            errBox.classList.add('hidden');
            errBox.innerText = '';

            const subjectSelect = document.getElementById('subject-select').value;
            let subjectValue = subjectSelect;
            
            if (subjectSelect === 'custom') {
                const customVal = document.getElementById('custom-subject-input').value.trim();
                if (customVal === '') {
                    errBox.innerText = '⚠️ Silakan tulis nama subjek kustom Anda.';
                    errBox.classList.remove('hidden');
                    return;
                }
                subjectValue = customVal;
            }

            const difficulty = document.querySelector('input[name="difficulty"]:checked').value;
            const count = document.querySelector('input[name="count"]:checked').value;

            // Switch to Loading Screen
            document.getElementById('stage-setup').classList.replace('block', 'hidden');
            document.getElementById('stage-loading').classList.replace('hidden', 'flex');

            try {
                const response = await fetch('{{ route("quiz.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        subject_id: subjectValue,
                        difficulty: difficulty,
                        count: parseInt(count),
                    })
                });

                const data = await response.json();

                if (!response.ok || data.error) {
                    throw new Error(data.error || 'Terjadi kesalahan sistem saat membuat soal.');
                }

                // Initialize Quiz State
                currentQuestions = data.questions;
                userAnswers = {};
                currentQuestionIndex = 0;
                activeSubjectName = data.subject;
                activeDiff = data.difficulty;

                // Load First Question
                loadQuestion();

                // Switch to Quiz Game stage
                document.getElementById('stage-loading').classList.replace('flex', 'hidden');
                document.getElementById('stage-quiz').classList.replace('hidden', 'block');

            } catch (err) {
                // Return back to Setup stage and show error
                document.getElementById('stage-loading').classList.replace('flex', 'hidden');
                document.getElementById('stage-setup').classList.replace('hidden', 'block');
                
                errBox.innerText = '❌ ' + err.message;
                errBox.classList.remove('hidden');
            }
        });

        function loadQuestion() {
            if (currentQuestions.length === 0) return;

            const q = currentQuestions[currentQuestionIndex];
            
            // Header stats
            document.getElementById('quiz-subject-badge').innerText = activeSubjectName;
            document.getElementById('quiz-diff-badge').innerText = activeDiff;
            document.getElementById('current-question-num').innerText = currentQuestionIndex + 1;
            document.getElementById('total-questions-num').innerText = currentQuestions.length;

            // Progress bar
            const percent = ((currentQuestionIndex + 1) / currentQuestions.length) * 100;
            document.getElementById('quiz-progress-bar').style.width = percent + '%';

            // Question text
            document.getElementById('quiz-question-text').innerText = q.question;

            // Render options
            const optionsContainer = document.getElementById('quiz-options-container');
            optionsContainer.innerHTML = '';

            const keys = ['A', 'B', 'C', 'D'];
            keys.forEach(key => {
                const optText = q.options[key] || '';
                if (optText === '') return;

                const isSelected = userAnswers[currentQuestionIndex] === key;
                
                const btn = document.createElement('button');
                btn.className = `w-full text-left p-4 rounded-2xl border-2 transition-all flex items-center gap-4 dark:text-gray-200 dark:bg-gray-700/40 ${
                    isSelected 
                        ? 'border-[#B91C1C] bg-red-50/50 dark:bg-red-900/10 font-bold' 
                        : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/60'
                }`;
                btn.onclick = () => selectOption(key);
                btn.innerHTML = `
                    <span class="w-8 h-8 rounded-full flex items-center justify-center font-extrabold text-sm ${
                        isSelected 
                            ? 'bg-[#B91C1C] text-white' 
                            : 'bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-300'
                    }">${key}</span>
                    <span class="text-sm font-medium leading-relaxed">${escapeHtml(optText)}</span>
                `;
                optionsContainer.appendChild(btn);
            });

            // Adjust navigation buttons
            const btnPrev = document.getElementById('btn-prev');
            const btnNext = document.getElementById('btn-next');

            if (currentQuestionIndex === 0) {
                btnPrev.style.visibility = 'hidden';
            } else {
                btnPrev.style.visibility = 'visible';
            }

            if (currentQuestionIndex === currentQuestions.length - 1) {
                btnNext.innerText = 'Submit Kuis 📥';
                btnNext.style.backgroundColor = '#16A34A'; // Green for submit
                btnNext.style.borderColor = '#15803D';
            } else {
                btnNext.innerText = 'Selanjutnya →';
                btnNext.style.backgroundColor = '#B91C1C';
                btnNext.style.borderColor = '#991B1B';
            }
        }

        function selectOption(key) {
            userAnswers[currentQuestionIndex] = key;
            loadQuestion(); // Re-render to show active selection border
        }

        function prevQuestion() {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                loadQuestion();
            }
        }

        function nextQuestion() {
            // Validate if selected
            if (!userAnswers[currentQuestionIndex]) {
                alert('Silakan pilih salah satu opsi jawaban sebelum melanjutkan.');
                return;
            }

            if (currentQuestionIndex < currentQuestions.length - 1) {
                currentQuestionIndex++;
                loadQuestion();
            } else {
                // Submit Kuis
                gradeQuiz();
            }
        }

        function gradeQuiz() {
            let correctCount = 0;
            currentQuestions.forEach((q, idx) => {
                if (userAnswers[idx] === q.correct_answer) {
                    correctCount++;
                }
            });

            const score = Math.round((correctCount / currentQuestions.length) * 100);

            // Populate Results card
            document.getElementById('result-score').innerText = score;
            document.getElementById('result-correct-ratio').innerText = `${correctCount} dari ${currentQuestions.length} Soal Benar`;

            // Build review breakdown
            const breakdownContainer = document.getElementById('results-breakdown-container');
            breakdownContainer.innerHTML = '';

            currentQuestions.forEach((q, idx) => {
                const userAns = userAnswers[idx];
                const correctAns = q.correct_answer;
                const isCorrect = userAns === correctAns;

                const card = document.createElement('div');
                card.className = `rounded-2xl border p-5 space-y-4 transition-all ${
                    isCorrect 
                        ? 'border-green-200 bg-green-50/20 dark:border-green-900/30' 
                        : 'border-red-200 bg-red-50/20 dark:border-red-900/30'
                }`;

                card.innerHTML = `
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-xs font-bold ${isCorrect ? 'text-green-600' : 'text-red-600'} uppercase">
                                Soal ${idx + 1} · ${isCorrect ? '✨ Benar' : '❌ Salah'}
                            </span>
                            <h4 class="font-bold text-gray-800 dark:text-gray-100 leading-relaxed text-sm">${escapeHtml(q.question)}</h4>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                        <div class="p-3 rounded-xl bg-white dark:bg-gray-800 border ${isCorrect ? 'border-green-100' : 'border-red-100'}">
                            <span class="text-gray-400 block mb-0.5">Jawaban Anda:</span>
                            <span class="font-bold ${isCorrect ? 'text-green-600' : 'text-red-600'}">(${userAns}) ${escapeHtml(q.options[userAns] || '')}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-white dark:bg-gray-800 border border-green-100">
                            <span class="text-gray-400 block mb-0.5">Jawaban Benar:</span>
                            <span class="font-bold text-green-600">(${correctAns}) ${escapeHtml(q.options[correctAns] || '')}</span>
                        </div>
                    </div>

                    <!-- Collapsible Explanation Layer -->
                    <div class="rounded-xl overflow-hidden bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                        <button onclick="toggleExplanation(${idx})" class="w-full text-left p-3.5 flex items-center justify-between text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <span class="flex items-center gap-2">💡 Pembahasan AI</span>
                            <span id="exp-arrow-${idx}" class="transition-transform duration-200">▼</span>
                        </button>
                        <div id="exp-content-${idx}" class="hidden p-4 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-600 dark:text-gray-300 leading-relaxed style="white-space: pre-wrap;">
                            ${escapeHtml(q.explanation || 'Tidak ada penjelasan yang tersedia.')}
                        </div>
                    </div>
                `;

                breakdownContainer.appendChild(card);
            });

            // Switch to Results stage
            document.getElementById('stage-quiz').classList.replace('block', 'hidden');
            document.getElementById('stage-results').classList.replace('hidden', 'block');
        }

        function toggleExplanation(idx) {
            const content = document.getElementById(`exp-content-${idx}`);
            const arrow = document.getElementById(`exp-arrow-${idx}`);
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                arrow.style.transform = '';
            }
        }

        function resetQuiz() {
            // Restore back to setup stage
            document.getElementById('stage-results').classList.replace('block', 'hidden');
            document.getElementById('stage-setup').classList.replace('hidden', 'block');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }
    </script>
</x-app-layout>
