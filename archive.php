<?php 
/**
 * Archive Template (Blog Page) - Single Column News Feed
 * * 사이드바 없이 프론트 페이지와 동일한 1단 레이아웃을 사용합니다.
 */
get_header(); ?>

<!-- 1. 아카이브 전용 영웅 섹션 (프론트 페이지의 네이비 톤앤매너와 통일) -->
<section class="fw-hero" style="position: relative; padding: 5rem 2rem; background: var(--navy); color: var(--white); text-align: center;">
    <div class="fw-hero-inner" style="position: relative; z-index: 1;">
        <span class="fw-hero-label" style="text-transform:uppercase; letter-spacing:2px; font-size:0.9rem; color: var(--primary);">
            Archive
        </span>
        <h1 class="fw-hero-title" style="margin-top: 0.5rem; font-size: 2.5rem; font-weight: 900;">
            <?php the_archive_title(); ?>
        </h1>
        <?php if ( get_the_archive_description() ) : ?>
            <div class="hero-description" style="max-width:600px; margin:1rem auto; opacity:0.9; line-height: 1.6;">
                <?php the_archive_description(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- 2. 메인 컨테이너 (프론트 페이지와 동일한 넓고 시원한 1단 구조) -->
<div class="fw-main-container" style="margin-top: 3rem; margin-bottom: 5rem;">
    <!-- 사이드바(<aside>)를 완전히 제거하고 <main>만 전체 너비로 사용 -->
    <main>
        <?php if ( have_posts() ) : ?>
            <div class="fw-news-feed-wrapper">
                
                <?php while ( have_posts() ) : the_post(); ?>
                    <article class="fw-news-row">
                        
                        <!-- 날짜 및 카테고리 -->
                        <div class="fw-news-meta">
                            <?php 
                                $categories = get_the_category();
                                if ( ! empty( $categories ) ) {
                                    echo '<span class="cat-name">' . esc_html( $categories[0]->name ) . '</span>';
                                }
                            ?>
                            <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('M j, Y'); ?></time>
                        </div>
                        
                        <!-- 제목 및 요약문 -->
                        <div class="fw-news-text">
                            <h2>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <div class="fw-excerpt">
                                <?php echo wp_trim_words( get_the_excerpt(), 30, '...' ); ?>
                            </div>
                        </div>
                        
                        <!-- 16:9 썸네일 이미지 -->
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="fw-news-thumb">
                                <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                                    <?php the_post_thumbnail( 'medium_large', array( 
                                        'loading' => 'lazy',
                                        'alt' => get_the_title()
                                    ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                    </article>
                <?php endwhile; ?>
                
            </div>

            <!-- 페이지네이션 -->
            <div class="pagination-area-premium" style="margin-top:4rem; padding-top: 2rem; border-top: 1px solid #eaeaea; text-align:center;">
                <?php the_posts_pagination(array(
                    'mid_size'  => 2, 
                    'prev_text' => '<b>&larr; Previous</b>', 
                    'next_text' => '<b>Next &rarr;</b>'
                )); ?>
            </div>

        <?php else : ?>
            <div style="text-align:center; padding:100px 0;">
                <h2 style="font-weight:900; color:var(--navy);">No Articles Found</h2>
                <p>Please try a different search or category.</p>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php get_footer(); ?>