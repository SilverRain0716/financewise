jQuery(document).ready(function($) {
    console.log("Tax Calculator Script Loaded - Accordion UI & Accurate URL Version (Stable)");

    // 2026 예상 연방 세율 데이터
    const FED_BRACKETS_2026 = {
        single: [
            { limit: 11925, rate: 0.10 },
            { limit: 48475, rate: 0.12 },
            { limit: 103350, rate: 0.22 },
            { limit: 197300, rate: 0.24 },
            { limit: 250525, rate: 0.32 },
            { limit: 626350, rate: 0.35 },
            { limit: Infinity, rate: 0.37 }
        ],
        married: [
            { limit: 23850, rate: 0.10 },
            { limit: 96950, rate: 0.12 },
            { limit: 206700, rate: 0.22 },
            { limit: 394600, rate: 0.24 },
            { limit: 501050, rate: 0.32 },
            { limit: 751600, rate: 0.35 },
            { limit: Infinity, rate: 0.37 }
        ],
        head: [
            { limit: 17000, rate: 0.10 },
            { limit: 64850, rate: 0.12 },
            { limit: 103350, rate: 0.22 },
            { limit: 197300, rate: 0.24 },
            { limit: 250500, rate: 0.32 },
            { limit: 626350, rate: 0.35 },
            { limit: Infinity, rate: 0.37 }
        ]
    };

    const STANDARD_DEDUCTION = { single: 15000, married: 30000, head: 22500 };
    
    // 주별 세금 데이터
    const STATE_DATA = {
        'AL': { tax_rate: 0.04, col_index: 88.1 }, 'AK': { tax_rate: 0.00, col_index: 126.6 },
        'AZ': { tax_rate: 0.025, col_index: 106.4 }, 'AR': { tax_rate: 0.049, col_index: 89.2 },
        'CA': { tax_rate: 0.08, col_index: 138.5 }, 'CO': { tax_rate: 0.044, col_index: 105.6 },
        'CT': { tax_rate: 0.055, col_index: 116.8 }, 'DE': { tax_rate: 0.05, col_index: 105.1 },
        'FL': { tax_rate: 0.00, col_index: 102.3 }, 'GA': { tax_rate: 0.0549, col_index: 89.1 },
        'HI': { tax_rate: 0.07, col_index: 179.0 }, 'ID': { tax_rate: 0.058, col_index: 106.1 },
        'IL': { tax_rate: 0.0495, col_index: 91.5 }, 'IN': { tax_rate: 0.0315, col_index: 90.0 },
        'IA': { tax_rate: 0.05, col_index: 89.7 }, 'KS': { tax_rate: 0.057, col_index: 87.5 },
        'KY': { tax_rate: 0.045, col_index: 93.8 }, 'LA': { tax_rate: 0.04, col_index: 93.6 },
        'ME': { tax_rate: 0.06, col_index: 111.5 }, 'MD': { tax_rate: 0.05, col_index: 119.5 },
        'MA': { tax_rate: 0.05, col_index: 148.4 }, 'MI': { tax_rate: 0.0425, col_index: 92.7 },
        'MN': { tax_rate: 0.06, col_index: 94.4 }, 'MS': { tax_rate: 0.05, col_index: 85.3 },
        'MO': { tax_rate: 0.054, col_index: 88.4 }, 'MT': { tax_rate: 0.05, col_index: 106.9 },
        'NE': { tax_rate: 0.06, col_index: 92.6 }, 'NV': { tax_rate: 0.00, col_index: 103.2 },
        'NH': { tax_rate: 0.00, col_index: 115.0 }, 'NJ': { tax_rate: 0.06, col_index: 112.4 },
        'NM': { tax_rate: 0.049, col_index: 94.2 }, 'NY': { tax_rate: 0.06, col_index: 125.1 },
        'NC': { tax_rate: 0.045, col_index: 96.1 }, 'ND': { tax_rate: 0.02, col_index: 94.6 },
        'OH': { tax_rate: 0.035, col_index: 90.8 }, 'OK': { tax_rate: 0.04, col_index: 86.0 },
        'OR': { tax_rate: 0.08, col_index: 114.3 }, 'PA': { tax_rate: 0.0307, col_index: 99.0 },
        'RI': { tax_rate: 0.05, col_index: 112.0 }, 'SC': { tax_rate: 0.06, col_index: 95.9 },
        'SD': { tax_rate: 0.00, col_index: 101.0 }, 'TN': { tax_rate: 0.00, col_index: 90.4 },
        'TX': { tax_rate: 0.00, col_index: 93.0 }, 'UT': { tax_rate: 0.0485, col_index: 104.5 },
        'VT': { tax_rate: 0.07, col_index: 114.9 }, 'VA': { tax_rate: 0.05, col_index: 101.4 },
        'WA': { tax_rate: 0.00, col_index: 115.1 }, 'WV': { tax_rate: 0.05, col_index: 88.5 },
        'WI': { tax_rate: 0.05, col_index: 95.2 }, 'WY': { tax_rate: 0.00, col_index: 92.8 },
        'DC': { tax_rate: 0.07, col_index: 149.3 }
    };

    // 401k 슬라이더 라벨 업데이트
    $('#contribution-401k').on('input', function() {
        $('#401k-val').text($(this).val() + '%');
    });

    // 계산 버튼 클릭 이벤트
    $('#calc-btn').off('click').on('click', function() {
        const grossSalary = parseFloat($('#salary').val());
        const stateCode = $('#state').val();
        const status = $('#status').val();
        const k401Rate = parseFloat($('#contribution-401k').val()) / 100;

        if (!grossSalary || grossSalary <= 0 || !stateCode) {
            alert("Please enter a valid salary and select a state.");
            return;
        }

        const stateInfo = STATE_DATA[stateCode] || { tax_rate: 0, col_index: 100 };

        // 계산 로직
        const k401Amount = grossSalary * k401Rate;
        const deduction = STANDARD_DEDUCTION[status] || STANDARD_DEDUCTION['single'];
        
        let taxableIncome = grossSalary - k401Amount - deduction;
        if (taxableIncome < 0) taxableIncome = 0;

        let federalTax = 0;
        const brackets = FED_BRACKETS_2026[status] || FED_BRACKETS_2026.single;
        let prevLim = 0;
        
        for (let i = 0; i < brackets.length; i++) {
            const b = brackets[i];
            const width = b.limit - prevLim;
            
            if (taxableIncome > b.limit) {
                federalTax += width * b.rate;
                prevLim = b.limit;
            } else {
                federalTax += (taxableIncome - prevLim) * b.rate;
                break;
            }
        }

        const ssCap = 176100; 
        const ssTax = Math.min(grossSalary, ssCap) * 0.062;
        const medTax = grossSalary * 0.0145;
        const ficaTax = ssTax + medTax;
        const stateTax = (grossSalary - k401Amount) * stateInfo.tax_rate;

        const totalTax = federalTax + ficaTax + stateTax;
        const netPayYearly = grossSalary - k401Amount - totalTax;
        const netPayMonthly = netPayYearly / 12;

        // 결과 포맷팅
        const fmt = (num) => {
            if (isNaN(num)) return "$0"; 
            return '$' + num.toLocaleString('en-US', {maximumFractionDigits: 0});
        };

        $('#monthly-net').text(fmt(netPayMonthly));
        $('#res-gross').text(fmt(grossSalary/12));
        $('#res-fed').text('-' + fmt(federalTax/12));
        $('#res-fica').text('-' + fmt(ficaTax/12));
        $('#res-state').text('-' + fmt(stateTax/12));
        $('#res-state-name').text(stateCode);
        $('#res-401k').text('-' + fmt(k401Amount/12));

        // 생활비(COL) 분석
        const usMedian = 60000;
        const adjustedSalary = (grossSalary / stateInfo.col_index) * 100;
        const ratio = adjustedSalary / usMedian; 
        
        let colScore = 0;
        let barColor = "";
        
        if (ratio > 2.0) { colScore = 95; barColor = "#22c55e"; } 
        else if (ratio > 1.5) { colScore = 85; barColor = "#22c55e"; } 
        else if (ratio > 1.1) { colScore = 70; barColor = "#3b82f6"; } 
        else if (ratio > 0.9) { colScore = 50; barColor = "#eab308"; } 
        else if (ratio > 0.7) { colScore = 35; barColor = "#f97316"; } 
        else { colScore = 20; barColor = "#ef4444"; }

        $('#col-score').text(colScore + '/100');
        $('#col-bar').css('width', colScore + '%').css('background', barColor);


        // ==========================================
        // [아코디언 UI 적용] 재계산 시 상태 리셋 및 데이터 바인딩
        // ==========================================
        
        // 1. 계산 버튼 클릭 시 아코디언 상태 리셋 (Step 1을 무조건 열기)
        $('#acc-header-1').removeClass('locked'); 
        $('#acc-header-2, #acc-header-3').addClass('locked').removeClass('active');
        $('.fw-accordion-body').not('#panel-1').slideUp();
        
        // 만약 Step 1이 닫혀있다면 다시 열어줌
        if (!$('#acc-header-1').hasClass('active')) {
            $('#acc-header-1').click();
        }

        // 2. Step 2 데이터: 예산 50/30/20 계산
        const budgetNeeds = netPayMonthly * 0.50;
        const budgetWants = netPayMonthly * 0.30;
        const budgetSavings = netPayMonthly * 0.20;

        $('#budget-needs').text(fmt(budgetNeeds));
        $('#budget-wants').text(fmt(budgetWants));
        $('#budget-savings').text(fmt(budgetSavings));

        // 3. Step 3 데이터: 소득 구간별 One-Pick Premium Banner 동적 생성
        let recHtml = '';
        if (netPayMonthly < 2000) {
            recHtml = `
                <div class="rec-msg">Investing starts with small habits. If the 50/30/20 rule feels heavy, build an automated system to save/invest just $50 a month.</div>
                <div class="premium-banner-wrap">
                    <a href="https://financewise-blog.com/micro-investing-strategies/" class="premium-banner">
                        <span class="premium-badge">🔥 Editor's Choice</span>
                        <h4 class="premium-title">Micro-Investing Strategies 2026: Build Wealth with Just $50</h4>
                        <span class="premium-action">Read the Guide &rarr;</span>
                    </a>
                </div>
            `;
        } else if (netPayMonthly < 4000) {
            recHtml = `
                <div class="rec-msg">It's the golden time to build your wealth. Take a portion of the available funds (from Step 2) and start investing in US blue-chip stocks.</div>
                <div class="premium-banner-wrap">
                    <a href="https://financewise-blog.com/investing-for-beginners-how-to-start-with-just-100/" class="premium-banner">
                        <span class="premium-badge">💎 Premium Insight</span>
                        <h4 class="premium-title">Investing for Beginners: How to Start with Just $100</h4>
                        <span class="premium-action">Start Investing &rarr;</span>
                    </a>
                </div>
            `;
        } else if (netPayMonthly < 8000) {
            recHtml = `
                <div class="rec-msg">It's time to maximize compound interest by investing your seed money in tax-advantaged accounts.</div>
                <div class="premium-banner-wrap">
                    <a href="https://financewise-blog.com/roth-ira-vs-traditional-ira-which-to-choose-in-your-20s/" class="premium-banner">
                        <span class="premium-badge">🔥 Editor's Choice</span>
                        <h4 class="premium-title">Roth IRA vs Traditional IRA: Which to Choose in Your 20s?</h4>
                        <span class="premium-action">Optimize Your Taxes &rarr;</span>
                    </a>
                </div>
            `;
        } else {
            recHtml = `
                <div class="rec-msg">It's time to shift your high earned income into capital assets. Tax defense strategies are just as important as investment returns.</div>
                <div class="premium-banner-wrap">
                    <a href="https://financewise-blog.com/2026-tax-changes-that-affect-your-paycheck-what-to-know/" class="premium-banner">
                        <span class="premium-badge">💎 Premium Insight</span>
                        <h4 class="premium-title">2026 Tax Changes That Affect Your Paycheck: What to Know</h4>
                        <span class="premium-action">View Tax Strategy &rarr;</span>
                    </a>
                </div>
            `;
        }

        $('#dynamic-recommendation-content').html(recHtml);
    });

    // ==========================================
    // 아코디언 및 버튼 클릭 이벤트 로직
    // ==========================================

    // 아코디언 헤더 직접 클릭 시 (잠금 해제된 상태에서만 작동)
    $('.fw-accordion-header').off('click').on('click', function() {
        if ($(this).hasClass('locked')) return; // 잠겨있으면 클릭 무시

        const targetPanel = $(this).next('.fw-accordion-body');
        const isCurrentlyActive = $(this).hasClass('active');

        if (!isCurrentlyActive) {
            // 다른 열려있는 패널 부드럽게 닫기
            $('.fw-accordion-body').slideUp(300);
            $('.fw-accordion-header').removeClass('active');

            // 클릭한 패널 부드럽게 열기
            $(this).addClass('active');
            targetPanel.slideDown(300, function() {
                // 패널이 열린 후 헤더 위치로 부드럽게 오토 스크롤 (모바일 UX 개선)
                $('html, body').animate({
                    scrollTop: $(this).prev('.fw-accordion-header').offset().top - 40
                }, 300);
            });
        }
    });

    // Step 1에서 Step 2로 넘어가는 버튼 클릭
    $('#btn-show-step2').off('click').on('click', function() {
        // Step 2 헤더 잠금 해제 및 자동으로 클릭(열기) 이벤트 발생
        $('#acc-header-2').removeClass('locked').click();
    });

    // Step 2에서 Step 3으로 넘어가는 버튼 클릭
    $('#btn-show-step3').off('click').on('click', function() {
        // Step 3 헤더 잠금 해제 및 자동으로 클릭(열기) 이벤트 발생
        $('#acc-header-3').removeClass('locked').click();
    });

});