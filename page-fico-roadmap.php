<?php
/**
 * Template Name: FICO Score Roadmap (커스텀 진단 도구)
 * Description: FICO Score Builder & Interactive Roadmap을 구동하기 위한 워드프레스 커스텀 페이지 템플릿입니다.
 */

// 1. 워드프레스 <head> 영역에 필요한 외부 스크립트와 스타일을 주입합니다.
add_action('wp_head', 'fico_roadmap_custom_assets');
function fico_roadmap_custom_assets() {
    ?>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind 및 애니메이션 커스텀 설정 -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        slate: { 850: '#151e2e' },
                        brand: { 50: '#eef2ff', 500: '#6366f1', 600: '#4f46e5', 900: '#312e81' }
                    }
                }
            }
        }
    </script>
    <style>
        /* 전역 스타일 설정 - 워드프레스 테마와의 충돌을 최소화하기 위해 컨테이너 내부로 한정하는 것이 좋습니다. */
        .fico-tool-container { 
            font-family: 'Inter', sans-serif; 
            /* [수정됨] 제너레이트프레스 테마의 가로폭 제한을 강제로 벗어나 브라우저 전체 너비(Full-width)를 꽉 채우는 CSS 트릭입니다. */
            width: 100vw !important;
            position: relative;
            left: 50%;
            transform: translateX(-50%);
            overflow-x: hidden; /* 좌우 스크롤바 방지 */
        }
        
        /* 1. 페이드인 애니메이션 */
        .fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* 2. 점수 팝업 애니메이션 */
        .score-pulse { animation: pulseGreen 0.4s ease-in-out; }
        @keyframes pulseGreen {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); color: #10b981; }
            100% { transform: scale(1); }
        }
        
        /* 3. 선택지 버튼 호버 효과 */
        .option-btn { transition: all 0.2s ease; }
        .option-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
    </style>
    <?php
}

// 2. 현재 사용 중인 워드프레스 테마의 헤더를 불러옵니다.
get_header(); 
?>

<!-- 3. 메인 콘텐츠 영역: 기존 HTML 코드가 이곳에 들어갑니다. -->
<!-- [수정됨] flex, justify-center, items-start 클래스를 추가하여 내부 카드가 무조건 수평 중앙에 위치하도록 강제합니다. -->
<main class="fico-tool-container py-12 px-4 md:px-8 bg-slate-50 min-h-screen flex justify-center items-start">

    <!-- [수정됨] w-full 클래스를 추가하여 넓이를 보장하고, 인라인 스타일로 테마의 float이나 margin 속성을 무력화합니다. -->
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-100" style="margin-left: auto !important; margin-right: auto !important; float: none !important;">
        
        <!-- Header -->
        <div class="bg-slate-900 p-8 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-brand-900 to-slate-900 opacity-90 z-0"></div>
            <div class="relative z-10">
                <h2 class="text-3xl font-bold mb-3 tracking-tight" style="color: #ffffff !important;">Smart FICO® Score Roadmap</h2>
                <p class="max-w-xl mx-auto text-sm md:text-base" style="color: #cbd5e1 !important;">Analyze your credit profile using our advanced algorithm based on the 5 core FICO factors. Get a personalized, step-by-step action plan to maximize your score.</p>
            </div>
        </div>

        <!-- Step 1: Start Screen -->
        <div id="step-start" class="p-10 text-center fade-in">
            <div class="mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-brand-50 text-brand-600 mb-6">
                    <i class="fas fa-chart-pie text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3 text-slate-800">What is your current FICO® Score?</h3>
                <p class="text-slate-500 text-sm">Enter a valid score between 300 and 850. <br class="hidden md:block"/>This is a soft simulation and will not impact your credit.</p>
            </div>
            
            <div class="max-w-sm mx-auto">
                <div class="relative">
                    <i class="fas fa-tachometer-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 text-xl"></i>
                    <input type="number" id="baseScoreInput" min="300" max="850" placeholder="e.g., 650" 
                           class="w-full text-center text-4xl font-bold py-5 pl-12 pr-4 border-2 border-slate-200 rounded-xl focus:border-brand-500 focus:ring-4 focus:ring-brand-50 outline-none transition-all mb-2 text-slate-700 shadow-inner">
                </div>
                <p id="scoreError" class="text-red-500 text-sm hidden mb-6 font-medium">Please enter a valid score (300 - 850).</p>
                
                <button onclick="startDiagnostic()" class="w-full bg-brand-600 hover:bg-brand-500 text-white font-semibold py-4 px-6 rounded-xl transition-all shadow-lg shadow-brand-500/30 flex items-center justify-center group mt-6">
                    Start Free Analysis <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: Quiz Screen -->
        <div id="step-quiz" class="hidden relative fade-in">
            <!-- Progress Header -->
            <div class="bg-slate-50 border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-20">
                <div class="w-1/2 md:w-1/3">
                    <div class="flex justify-between text-xs text-slate-500 font-bold tracking-widest uppercase mb-2">
                        <span>Progress</span>
                        <span id="progressText">1/5</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5">
                        <div id="progressBar" class="bg-brand-600 h-1.5 rounded-full transition-all duration-500 ease-out" style="width: 20%"></div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-slate-500 font-bold tracking-wider uppercase mb-1">Potential Boost</div>
                    <div class="inline-flex items-center bg-emerald-50 text-emerald-700 font-bold px-3 py-1.5 rounded-lg border border-emerald-100 shadow-sm">
                        <i class="fas fa-arrow-trend-up mr-2 text-emerald-500"></i> +<span id="potentialScoreDisplay" class="text-lg">0</span> pts
                    </div>
                </div>
            </div>

            <!-- Question Area -->
            <div class="p-8 md:p-12 bg-white">
                <div class="inline-block px-3 py-1 rounded-full bg-brand-50 text-brand-700 text-xs font-bold uppercase tracking-wide mb-4" id="questionCategory">Category</div>
                <h3 class="text-2xl md:text-3xl font-bold mb-8 text-slate-800 leading-snug" id="questionText">Question Text</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="answerButtons"></div>
            </div>
        </div>

        <!-- Step 3: Result & Roadmap Screen -->
        <div id="step-result" class="hidden fade-in bg-slate-50">
            <!-- Result Dashboard -->
            <div class="p-8 md:p-12 bg-white border-b border-slate-200">
                <div class="text-center mb-10">
                    <h3 class="text-2xl font-bold text-slate-800">Your Credit Growth Simulation</h3>
                    <p class="text-slate-500 mt-2">Based on the 5 pillars of the FICO® algorithm.</p>
                </div>
                
                <div class="flex flex-col md:flex-row justify-center items-center gap-12">
                    <!-- Gauge Chart -->
                    <div class="relative w-64 h-32 flex flex-col items-center">
                        <canvas id="ficoGauge" width="256" height="128" class="absolute top-0"></canvas>
                        <div class="absolute bottom-0 text-center w-full pb-2">
                            <div class="text-sm text-slate-400 font-semibold uppercase tracking-wider">Target Score</div>
                            <div class="text-4xl font-black text-slate-800" id="resultTargetScore">0</div>
                        </div>
                    </div>

                    <!-- Connectors -->
                    <div class="hidden md:flex flex-col space-y-2">
                        <div class="w-[1px] h-12 bg-slate-200 mx-auto"></div>
                        <i class="fas fa-plus text-slate-300 text-sm text-center"></i>
                        <div class="w-[1px] h-12 bg-slate-200 mx-auto"></div>
                    </div>

                    <!-- Breakdown -->
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 min-w-[200px] text-center shadow-sm">
                        <div class="text-sm text-slate-500 font-medium mb-1">Starting Score</div>
                        <div class="text-2xl font-bold text-slate-400 mb-4 line-through" id="resultBaseScore">0</div>
                        <div class="w-full h-[1px] bg-slate-200 mb-4"></div>
                        <div class="text-sm text-emerald-600 font-bold mb-1">Projected Increase</div>
                        <div class="text-3xl font-extrabold text-emerald-500">+<span id="finalBoostDisplay">0</span></div>
                    </div>
                </div>
            </div>

            <!-- Action Plan Timeline -->
            <div class="p-8 md:p-12 max-w-3xl mx-auto">
                <h4 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                    <i class="fas fa-road text-brand-500 mr-3"></i> Your Actionable Roadmap
                </h4>
                <div id="actionPlanList" class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                </div>
                
                <div class="mt-12 text-center">
                    <button onclick="location.reload()" class="text-slate-500 font-medium hover:text-brand-600 px-6 py-2 rounded-lg hover:bg-brand-50 transition-colors flex items-center justify-center mx-auto border border-transparent hover:border-brand-200">
                        <i class="fas fa-redo-alt mr-2"></i> Recalculate Scenario
                    </button>
                </div>
            </div>
        </div>

    </div>

</main>

<!-- 4. JavaScript 로직 -->
<script>
    let state = {
        baseScore: 0,
        potentialPoints: 0,
        currentQuestionIndex: 0,
        actions: []
    };

    const questions = [
        {
            category: "Payment History (35% of FICO)",
            text: "Do you have any late payments (30+ days), collections, or derogatory marks on your credit report from the last 24 months?",
            options: [
                { 
                    label: "Yes, I have recent negative marks.", 
                    points: 35, 
                    action: { title: "Erase Derogatory Marks (Goodwill Strategy)", desc: "Payment history is the most critical factor (35%). If you've already paid the late account, write a 'Goodwill Letter' to the creditor asking for a courtesy removal. For collections, request a 'Pay-for-Delete' agreement.", timeframe: "Within 30 Days", impact: "Critical", icon: "fa-file-signature", color: "red" }
                },
                { 
                    label: "No, my recent history is clean.", 
                    points: 5, 
                    action: { title: "Automate Minimum Payments", desc: "Your payment history is strong. Protect it by setting up AutoPay for the minimum due amount on all cards. A single 30-day late payment can drop an excellent score by up to 100 points.", timeframe: "Immediate", impact: "Preventative", icon: "fa-robot", color: "slate" }
                }
            ]
        },
        {
            category: "Amounts Owed / Utilization (30% of FICO)",
            text: "What is your total credit card utilization rate? (Total Balances ÷ Total Credit Limits)",
            options: [
                { 
                    label: "High (Over 50%)", 
                    points: 45, 
                    action: { title: "Implement the '15/3' Utilization Hack", desc: "High utilization crushes your score. Pay half your balance 15 days before the statement date, and the rest 3 days before. This forces the banks to report a $0 or low balance to the bureaus.", timeframe: "Next Billing Cycle", impact: "High (Fastest Boost)", icon: "fa-credit-card", color: "brand" }
                },
                { 
                    label: "Moderate (10% - 49%)", 
                    points: 20, 
                    action: { title: "Request a Credit Limit Increase (CLI)", desc: "To easily lower utilization without paying down more debt, ask your current credit card issuers for a Credit Limit Increase. Ensure they do a 'Soft Pull' so it doesn't affect your score.", timeframe: "Within 14 Days", impact: "Medium", icon: "fa-arrow-up-right-dots", color: "brand" }
                },
                { label: "Excellent (Under 10%)", points: 0, action: null } 
            ]
        },
        {
            category: "Length of Credit History (15% of FICO)",
            text: "Is your oldest open credit card less than 3 years old, or do you frequently close old accounts?",
            options: [
                { 
                    label: "Yes, my history is short/I closed old cards.", 
                    points: 15, 
                    action: { title: "Piggyback via Authorized User Strategy", desc: "Average Age of Accounts (AAoA) matters. Ask a trusted family member with a long, pristine credit card history to add you as an 'Authorized User'. You inherit that card's positive history immediately.", timeframe: "1-2 Months", impact: "Medium", icon: "fa-users", color: "emerald" }
                },
                { label: "No, I have well-aged open accounts.", points: 0, action: null }
            ]
        },
        {
            category: "Credit Mix (10% of FICO)",
            text: "Does your credit profile consist ONLY of credit cards? (Meaning, no auto loans, student loans, or mortgages)",
            options: [
                { 
                    label: "Yes, I only use credit cards.", 
                    points: 15, 
                    action: { title: "Diversify with a Credit Builder Loan", desc: "FICO rewards a mix of 'Revolving' (cards) and 'Installment' (loans) credit. Consider a low-cost Credit Builder Loan (like Self or Kikoff) to easily add an installment tradeline to your mix.", timeframe: "2-3 Months", impact: "Low", icon: "fa-building-columns", color: "indigo" }
                },
                { label: "No, I have a mix of loans and cards.", points: 0, action: null }
            ]
        },
        {
            category: "Alternative Data (Extra Boost)",
            text: "Are you utilizing alternative data reporting tools to get credit for utility or rent payments?",
            options: [
                { label: "Yes, I use tools like Experian Boost or Bilt.", points: 0, action: null },
                { 
                    label: "No, I didn't know I could.", 
                    points: 12, 
                    action: { title: "Activate Free Rent & Utility Reporting", desc: "Connect free tools like Experian Boost to your bank account. It scans for on-time Netflix, cell phone, and utility payments, adding them to your Experian report for an instant boost.", timeframe: "Today (Takes 5 mins)", impact: "Quick Win", icon: "fa-bolt", color: "amber" }
                }
            ]
        }
    ];

    function startDiagnostic() {
        const input = document.getElementById('baseScoreInput').value;
        const errorMsg = document.getElementById('scoreError');
        
        if(input >= 300 && input <= 850) {
            state.baseScore = parseInt(input);
            errorMsg.classList.add('hidden');
            
            document.getElementById('step-start').classList.add('hidden');
            document.getElementById('step-quiz').classList.remove('hidden');
            
            renderQuestion();
        } else {
            errorMsg.classList.remove('hidden');
        }
    }

    function renderQuestion() {
        const q = questions[state.currentQuestionIndex];
        
        document.getElementById('questionCategory').innerText = q.category;
        document.getElementById('questionText').innerText = q.text;
        
        const progressPercent = ((state.currentQuestionIndex + 1) / questions.length) * 100;
        document.getElementById('progressBar').style.width = progressPercent + '%';
        document.getElementById('progressText').innerText = `${state.currentQuestionIndex + 1} / ${questions.length}`;

        const buttonsContainer = document.getElementById('answerButtons');
        buttonsContainer.innerHTML = ''; 
        
        q.options.forEach(option => {
            const btn = document.createElement('button');
            btn.className = "option-btn group p-6 border border-slate-200 rounded-xl text-left bg-white text-slate-700 font-medium hover:border-brand-500 hover:bg-brand-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 flex flex-col justify-center h-full min-h-[120px]";
            btn.innerHTML = `
                <div class="flex justify-between items-start w-full">
                    <span class="text-lg leading-snug pr-4">${option.label}</span>
                    <i class="fas fa-circle-check text-slate-300 text-xl mt-1 opacity-50 group-hover:opacity-100 group-hover:text-brand-500 transition-colors"></i>
                </div>
            `;
            
            btn.onclick = () => handleAnswer(option);
            buttonsContainer.appendChild(btn);
        });
    }

    function handleAnswer(option) {
        if(option.points > 0) {
            state.potentialPoints += option.points;
            
            const scoreDisplay = document.getElementById('potentialScoreDisplay');
            scoreDisplay.innerText = state.potentialPoints;
            
            scoreDisplay.classList.remove('score-pulse');
            void scoreDisplay.offsetWidth; 
            scoreDisplay.classList.add('score-pulse');
        }

        if(option.action) {
            state.actions.push(option.action);
        }

        state.currentQuestionIndex++;
        
        if(state.currentQuestionIndex < questions.length) {
            renderQuestion();
        } else {
            showResults();
        }
    }

    function showResults() {
        document.getElementById('step-quiz').classList.add('hidden');
        document.getElementById('step-result').classList.remove('hidden');

        let targetScore = Math.min(850, state.baseScore + state.potentialPoints);

        document.getElementById('resultBaseScore').innerText = state.baseScore;
        document.getElementById('resultTargetScore').innerText = targetScore;
        document.getElementById('finalBoostDisplay').innerText = state.potentialPoints;

        drawFicoGauge('ficoGauge', targetScore);
        renderActionPlan();
    }

    function renderActionPlan() {
        const container = document.getElementById('actionPlanList');
        container.innerHTML = '';

        if(state.actions.length === 0) {
            container.innerHTML = `
                <div class="bg-emerald-50 p-8 rounded-2xl text-center border border-emerald-100 shadow-sm">
                    <i class="fas fa-award text-5xl text-emerald-400 mb-4"></i>
                    <h5 class="text-xl font-bold text-emerald-800 mb-2">Exceptional Credit Profile!</h5>
                    <p class="text-emerald-600">You are optimizing all major FICO variables. Keep your utilization low, pay in full, and monitor your reports regularly.</p>
                </div>
            `;
            return;
        }

        const priorityOrder = { "Critical": 1, "High (Fastest Boost)": 2, "Quick Win": 3, "Medium": 4, "Low": 5, "Preventative": 6 };
        state.actions.sort((a, b) => priorityOrder[a.impact] - priorityOrder[b.impact]);

        state.actions.forEach((act, index) => {
            const themes = {
                red: { bg: 'bg-rose-50', border: 'border-rose-200', text: 'text-rose-600', badgeBg: 'bg-rose-100', badgeText: 'text-rose-700' },
                brand: { bg: 'bg-indigo-50', border: 'border-indigo-200', text: 'text-indigo-600', badgeBg: 'bg-indigo-100', badgeText: 'text-indigo-700' },
                emerald: { bg: 'bg-emerald-50', border: 'border-emerald-200', text: 'text-emerald-600', badgeBg: 'bg-emerald-100', badgeText: 'text-emerald-700' },
                indigo: { bg: 'bg-violet-50', border: 'border-violet-200', text: 'text-violet-600', badgeBg: 'bg-violet-100', badgeText: 'text-violet-700' },
                amber: { bg: 'bg-amber-50', border: 'border-amber-200', text: 'text-amber-600', badgeBg: 'bg-amber-100', badgeText: 'text-amber-800' },
                slate: { bg: 'bg-slate-50', border: 'border-slate-200', text: 'text-slate-600', badgeBg: 'bg-slate-200', badgeText: 'text-slate-700' }
            };
            const theme = themes[act.color] || themes.brand;

            const card = document.createElement('div');
            card.className = `relative flex items-start fade-in`;
            card.style.animationDelay = `${index * 0.15}s`; 
            
            card.innerHTML = `
                <div class="hidden md:flex items-center justify-center w-10 h-10 rounded-full bg-white border-4 border-slate-100 shadow-sm z-10 absolute left-1/2 -translate-x-1/2 text-slate-400 font-bold text-sm">
                    ${index + 1}
                </div>
                <div class="w-full md:w-[calc(50%-2.5rem)] ${index % 2 === 0 ? 'md:pr-8 md:text-right md:ml-0' : 'md:pl-8 md:ml-auto'}">
                    <div class="${theme.bg} border ${theme.border} p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3 ${index % 2 === 0 ? 'md:flex-row-reverse' : ''}">
                            <div class="flex items-center space-x-2 ${index % 2 === 0 ? 'md:space-x-reverse' : ''}">
                                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm ${theme.text}">
                                    <i class="fas ${act.icon} text-sm"></i>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-wider ${theme.badgeText} bg-white px-2 py-1 rounded shadow-sm border ${theme.border}">
                                    ${act.timeframe}
                                </span>
                            </div>
                            <span class="${theme.badgeBg} ${theme.badgeText} text-xs font-bold px-2 py-1 rounded-md">
                                ${act.impact}
                            </span>
                        </div>
                        <h5 class="text-lg font-bold text-slate-800 mb-2 leading-tight">${act.title}</h5>
                        <p class="text-slate-600 text-sm leading-relaxed">${act.desc}</p>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
    }

    function drawFicoGauge(canvasId, score) {
        const canvas = document.getElementById(canvasId);
        const ctx = canvas.getContext('2d');
        const width = canvas.width;
        const height = canvas.height;
        
        const centerX = width / 2;
        const centerY = height;
        const radius = width / 2 - 20;

        ctx.clearRect(0, 0, width, height);

        const segments = [
            { color: '#ef4444', min: 300, max: 579 },
            { color: '#f97316', min: 580, max: 669 },
            { color: '#eab308', min: 670, max: 739 },
            { color: '#84cc16', min: 740, max: 799 },
            { color: '#10b981', min: 800, max: 850 } 
        ];

        const minScore = 300;
        const maxScore = 850;
        const totalRange = maxScore - minScore;

        segments.forEach(seg => {
            const startRatio = (seg.min - minScore) / totalRange;
            const endRatio = (seg.max - minScore) / totalRange;
            
            const startAngle = Math.PI - (startRatio * Math.PI);
            const endAngle = Math.PI - (endRatio * Math.PI);

            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, startAngle, endAngle, true);
            ctx.lineWidth = 16;
            ctx.strokeStyle = seg.color;
            ctx.stroke();
        });

        let clampedScore = Math.max(minScore, Math.min(score, maxScore));
        const scoreRatio = (clampedScore - minScore) / totalRange;
        const needleAngle = Math.PI - (scoreRatio * Math.PI);
        const needleLength = radius - 10;
        
        const needleX = centerX + needleLength * Math.cos(needleAngle);
        const needleY = centerY - needleLength * Math.sin(needleAngle);

        ctx.beginPath();
        ctx.moveTo(centerX, centerY);
        ctx.lineTo(needleX, needleY);
        ctx.lineWidth = 4;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#1e293b';
        ctx.stroke();

        ctx.beginPath();
        ctx.arc(centerX, centerY, 8, 0, Math.PI * 2);
        ctx.fillStyle = '#1e293b';
        ctx.fill();
        
        ctx.beginPath();
        ctx.arc(centerX, centerY, 3, 0, Math.PI * 2);
        ctx.fillStyle = '#ffffff';
        ctx.fill();
    }
</script>

<?php
// 5. 현재 사용 중인 워드프레스 테마의 푸터를 불러옵니다.
get_footer(); 
?>