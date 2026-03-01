<?php
/**
 * Template part for displaying posts in a grid card layout
 * 이 파일은 template-parts 폴더 안에 저장해야 합니다.
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('fw-card'); ?>>
    <div class="fw-card-thumb">
        <a href="<?php the_permalink(); ?>">
            <?php if ( has_post_thumbnail() ) : 
                the_post_thumbnail('large'); 
            else : ?>
                <div class="fw-card-placeholder">FW</div>
            <?php endif; ?>
        </a>
        
        <?php 
        // 첫 번째 카테고리를 배지로 표시
        $categories = get_the_category();
        if ( ! empty( $categories ) ) : ?>
            <span class="fw-card-cat-badge"><?php echo esc_html( $categories[0]->name ); ?></span>
        <?php endif; ?>
    </div>
    
    <div class="fw-card-body">
        <span class="fw-card-date"><?php echo get_the_date('M d, Y'); ?></span>
        
        <h3 class="fw-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <div class="fw-card-excerpt">
            <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
        </div>
        
        <a href="<?php the_permalink(); ?>" class="fw-read-more">Read Article &rarr;</a>
    </div>
</article>