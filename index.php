<?php
/**
 * The main template file
 * 기본 글 목록을 보여주는 fallback 템플릿입니다.
 */
get_header(); ?>

<div class="fw-main-container" style="padding-top: 4rem;">
    <main id="main">

        <?php if ( have_posts() ) : ?>

            <header class="page-header" style="margin-bottom: 3rem; text-align: center;">
                <h1 class="page-title" style="color: var(--navy);">Latest Articles</h1>
            </header>

            <div class="fw-card-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    // 카드 디자인 불러오기
                    get_template_part( 'template-parts/content', 'card' );
                endwhile;
                ?>
            </div>

            <div class="pagination-area" style="margin-top: 3rem; text-align: center;">
                <?php the_posts_pagination(); ?>
            </div>

        <?php else : ?>

            <div class="no-results" style="text-align: center; padding: 4rem 0;">
                <h2>Nothing Found</h2>
                <p>It seems we can&rsquo;t find what you&rsquo;re looking for.</p>
            </div>

        <?php endif; ?>

    </main>
</div>

<?php get_footer(); ?>