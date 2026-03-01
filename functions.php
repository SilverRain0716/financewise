<?php
/**
 * FinanceWise Magazine Functions
 * 테마의 핵심 기능을 설정하고 스크립트와 스타일을 로드합니다.
 */

function financewise_premium_scripts() {
    // 1. 부모 테마(GeneratePress) 스타일시트 먼저 로드
    wp_enqueue_style( 'generatepress-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme('generatepress')->get('Version') );

    // 2. 차일드 테마 메인 스타일시트 로드 (부모 스타일 뒤에 로드)
    wp_enqueue_style( 'financewise-style', get_stylesheet_uri(), array('generatepress-style'), '1.2.0' );
    
    // 3. Google Fonts
    wp_enqueue_style( 'financewise-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Playfair+Display:ital,wght@1,700&display=swap', array(), null );

    // 4. 계산기 스크립트는 page-calculator.php 템플릿에서 직접 로드합니다.
    //    (LiteSpeed Cache 등 캐시 플러그인과의 호환성을 위해)
}
add_action( 'wp_enqueue_scripts', 'financewise_premium_scripts' );

// 테마 지원 기능 활성화
add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

// 요약문(Excerpt) 길이 조정
add_filter( 'excerpt_length', function($length) { return 25; }, 999 );

// 아카이브 제목 정리
add_filter( 'get_the_archive_title', function ( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    }
    return $title;
});

// 푸터 저작권 문구 커스터마이징 (wp_footer 훅에 등록)
add_action( 'wp_footer', 'financewise_copyright' );
function financewise_copyright() {
    ?>
    <div class="fw-footer-bottom">
        <span class="copyright">&copy; <?php echo esc_html( wp_date('Y') ); ?> FinanceWise Magazine. All rights reserved.</span>
        <div class="footer-legal-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact Us</a>
        </div>
    </div>
    <?php
}

// 검색 결과 페이지에서도 카드 레이아웃이 적용되도록 posts_per_page 설정
add_action( 'pre_get_posts', function( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
        $query->set( 'posts_per_page', 9 );
    }
});