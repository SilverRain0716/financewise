<?php
/**
 * Template Name: Tax Calculator Template
 * Description: 3단 레이아웃이 적용된 세금 계산기 템플릿 (아코디언 접기 UI 통합 버전)
 */
wp_enqueue_script( 'fw-tax-calculator', get_stylesheet_directory_uri() . '/js/tax-calculator.js', array('jquery'), '1.4.0', true );
get_header(); ?>

<!-- 헤더와 겹치지 않도록 상단 여백 확보 -->
<div class="fw-main-container" style="padding-top: 60px; padding-bottom: 60px;">
    
    <!-- 계산기 통합 래퍼 (3단 그리드: 타이틀 | 입력 | 결과) -->
    <div class="fw-calc-wrapper">
        
        <!-- [1] 왼쪽 사이드바: 타이틀 영역 -->
        <div class="fw-calc-title-section">
            <span class="fw-tools-label">FINANCIAL TOOLS</span>
            <h1 class="fw-tools-title">Tax<br><em>Analyzer</em></h1>
        </div>

        <!-- [2] 중앙: 입력 폼 -->
        <div class="fw-calc-input-section">
            <h3 class="fw-calc-heading">Your Information</h3>
            
            <form id="fw-tax-form" onsubmit="return false;">
                <!-- 연봉 입력 -->
                <div class="fw-form-group">
                    <label for="salary">Annual Gross Salary ($)</label>
                    <div class="input-icon-wrap">
                        <input type="number" id="salary" class="fw-input" placeholder="e.g. 85000" required>
                    </div>
                </div>

                <!-- 주(State) 선택 -->
                <div class="fw-form-group">
                    <label for="state">State of Residence</label>
                    <select id="state" class="fw-select" required>
                        <option value="" disabled selected>Select your state...</option>
                        <option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option>
                        <option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option>
                        <option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="FL">Florida</option>
                        <option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option>
                        <option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option>
                        <option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option>
                        <option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option>
                        <option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option>
                        <option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option>
                        <option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option>
                        <option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option>
                        <option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option>
                        <option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option>
                        <option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option>
                        <option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option>
                        <option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option>
                        <option value="WI">Wisconsin</option><option value="WY">Wyoming</option>
                        <option value="DC">District of Columbia</option>
                    </select>
                </div>

                <!-- 신고 상태 -->
                <div class="fw-form-group">
                    <label for="status">Filing Status</label>
                    <select id="status" class="fw-select">
                        <option value="single">Single</option>
                        <option value="married">Married Filing Jointly</option>
                        <option value="head">Head of Household</option>
                    </select>
                </div>

                <!-- 401k 기여금 -->
                <div class="fw-form-group">
                    <label for="contribution-401k">401(k) Contribution (%)</label>
                    <div class="range-wrap">
                        <input type="range" id="contribution-401k" class="fw-range" min="0" max="30" value="0" step="1">
                        <span class="range-value" id="401k-val" style="min-width:40px; font-weight:bold;">0%</span>
                    </div>
                </div>

                <button type="button" id="calc-btn" class="fw-calc-btn">Calculate Paycheck</button>
            </form>
        </div>

        <!-- [3] 오른쪽: 결과 화면 (아코디언 UI 적용) -->
        <div class="fw-calc-result-section" id="result-section">
            <h3 class="fw-calc-heading" style="color:white; border-bottom-color:rgba(255,255,255,0.2); margin-bottom: 2rem;">Your Financial Report</h3>

            <div class="fw-accordion" id="result-accordion">
                
                <!-- Step 1: 세금 결과 아코디언 -->
                <div class="fw-accordion-item">
                    <button class="fw-accordion-header active" id="acc-header-1">
                        <div><span class="step-num">1</span> Estimated Take-Home Pay</div>
                        <span class="icon">&#9660;</span>
                    </button>
                    <div class="fw-accordion-body" id="panel-1" style="display: block;">
                        
                        <div class="fw-big-number" style="margin-top: 0;">
                            <span class="label">Monthly Net Pay</span>
                            <span class="value" id="monthly-net">$0</span>
                        </div>

                        <div class="fw-breakdown-list">
                            <div class="item">
                                <span>Gross Pay (Monthly)</span>
                                <span id="res-gross">$0</span>
                            </div>
                            <div class="item text-danger">
                                <span>Federal Tax</span>
                                <span id="res-fed">-$0</span>
                            </div>
                            <div class="item text-danger">
                                <span>FICA (SS + Medicare)</span>
                                <span id="res-fica">-$0</span>
                            </div>
                            <div class="item text-danger">
                                <span>State Tax (<span id="res-state-name">--</span>)</span>
                                <span id="res-state">-$0</span>
                            </div>
                            <div class="item text-success">
                                <span>401(k) Savings</span>
                                <span id="res-401k">$0</span>
                            </div>
                        </div>

                        <!-- 생활비 분석 (COL) -->
                        <div class="fw-col-analysis" style="margin-top:2rem; padding-top:1rem; border-top:1px solid rgba(255,255,255,0.1);">
                            <h4 class="col-title" style="margin-bottom:0.5rem; color:white;">Cost of Living Analysis</h4>
                            <p id="col-desc" class="col-desc" style="font-size:0.9rem; opacity:0.8; margin-bottom:1rem; color: #ccd6f6;">Select a state and enter salary to see insights.</p>
                            
                            <div class="col-meter-wrap" style="background:rgba(255,255,255,0.1); height:10px; border-radius:5px; overflow:hidden;">
                                <div class="col-bar" id="col-bar" style="width: 0%; height:100%; transition:width 1s ease;"></div>
                            </div>
                            <div style="text-align:right; font-size:0.8rem; margin-top:5px; color:white;">
                                Score: <span id="col-score">--/100</span>
                            </div>
                        </div>

                        <!-- Step 1 CTA (예산 비율 보기) -->
                        <div id="step1-cta-wrap" class="fw-step-cta" style="margin-top:2rem; text-align:center;">
                            <!-- 애드센스 배너 자리 -->
                            <div class="ad-placeholder"><span>Google AdSense Banner Area</span></div>
                            <button type="button" id="btn-show-step2" class="fw-action-btn">View Your Ideal '50/30/20' Budget &rarr;</button>
                        </div>

                    </div>
                </div>

                <!-- Step 2: 50/30/20 예산 분석 아코디언 -->
                <div class="fw-accordion-item">
                    <button class="fw-accordion-header locked" id="acc-header-2">
                        <div><span class="step-num">2</span> 50/30/20 Budget Rule</div>
                        <span class="icon">&#9660;</span>
                    </button>
                    <div class="fw-accordion-body" id="panel-2" style="display: none;">
                        
                        <p style="font-size:0.95rem; color:#ccd6f6; margin-top:0; margin-bottom:1.5rem; line-height: 1.5;">A recommended budget breakdown for healthy financial management based on your calculated net pay.</p>
                        
                        <div class="fw-budget-bar">
                            <div class="budget-segment needs" style="width: 50%;">50%</div>
                            <div class="budget-segment wants" style="width: 30%;">30%</div>
                            <div class="budget-segment savings" style="width: 20%;">20%</div>
                        </div>
                        
                        <div class="fw-budget-legend">
                            <div class="legend-item"><span class="dot needs"></span> Needs <br><strong id="budget-needs">$0</strong></div>
                            <div class="legend-item"><span class="dot wants"></span> Wants <br><strong id="budget-wants">$0</strong></div>
                            <div class="legend-item highlight"><span class="dot savings"></span> Savings <br><strong id="budget-savings">$0</strong></div>
                        </div>

                        <div id="step2-cta-wrap" class="fw-step-cta" style="margin-top:2rem; text-align:center;">
                            <!-- 애드센스 배너 자리 -->
                            <div class="ad-placeholder"><span>Google AdSense Banner Area</span></div>
                            <button type="button" id="btn-show-step3" class="fw-action-btn primary">How should I invest this money? &rarr;</button>
                        </div>

                    </div>
                </div>

                <!-- Step 3: 원픽 프리미엄 배너 아코디언 -->
                <div class="fw-accordion-item">
                    <button class="fw-accordion-header locked" id="acc-header-3">
                        <div><span class="step-num">3</span> Your Custom Strategy</div>
                        <span class="icon">&#9660;</span>
                    </button>
                    <div class="fw-accordion-body" id="panel-3" style="display: none; padding-bottom: 1rem;">
                        <div id="dynamic-recommendation-content">
                            <!-- JS에서 소득 구간별로 One-Pick 배너가 동적 삽입됩니다 -->
                        </div>
                    </div>
                </div>

            </div> <!-- End of Accordion Wrapper -->
        </div> <!-- End of Result Section -->
    </div>
</div>

<?php get_footer(); ?>