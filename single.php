<?php
/**
 * The template for displaying all single posts
 */
get_header(); ?>

<div class="fw-main-container" style="padding-top: 4rem;">
    <main id="main" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('fw-single-post'); ?>>
                
                <!-- 게시글 헤더 -->
                <header class="entry-header" style="text-align: center; max-width: 800px; margin: 0 auto 3rem;">
                    <?php 
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) : ?>
                        <span class="fw-card-cat-badge" style="position:relative; top:0; right:0; display:inline-block; margin-bottom:1rem;">
                            <?php echo esc_html( $categories[0]->name ); ?>
                        </span>
                    <?php endif; ?>

                    <h1 class="entry-title" style="font-size: 2.5rem; font-weight: 800; color: var(--navy); margin-bottom: 1rem; line-height: 1.2;">
                        <?php the_title(); ?>
                    </h1>

                    <div class="entry-meta" style="color: var(--text-muted); font-size: 0.95rem;">
                        <span class="posted-on"><?php echo get_the_date('M d, Y'); ?></span>
                        <span class="byline">
                            by <span class="author vcard" style="color: var(--navy); font-weight: 600;"><?php echo get_the_author(); ?></span>
                        </span>
                    </div>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class=\"post-thumbnail\" style=\"margin-bottom: 3rem; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);\">
                        <?php the_post_thumbnail('full', array( 'style' => 'width: 100%; height: auto; display: block;' )); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content" style="max-width: 800px; margin: 0 auto; font-size: 1.1rem; color: #444; line-height: 1.8;">
                    <?php
                    the_content();
                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'financewise' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>

                <footer class="entry-footer" style="max-width: 800px; margin: 4rem auto 0; padding-top: 2rem; border-top: 1px solid #eee;">
                    <?php
                    if ( has_tag() ) {
                        echo '<div class="tags-links" style="color:var(--text-muted);">';
                        the_tags( '#', ' #', '' );
                        echo '</div>';
                    }
                    ?>
                </footer>

            </article>

            <!-- [추가] 시각적 포스트 내비게이션 영역 -->
            <nav class="fw-post-navigation" style="max-width: 800px; margin: 4rem auto;">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>
                
                <div class="fw-nav-links">
                    <?php if ( ! empty( $prev_post ) ) : ?>
                        <a href="<?php echo get_permalink( $prev_post->ID ); ?>" class="fw-nav-card prev">
                            <div class="nav-content">
                                <span class="nav-label">&larr; Previous Post</span>
                                <h4 class="nav-title"><?php echo esc_html( $prev_post->post_title ); ?></h4>
                            </div>
                        </a>
                    <?php else : ?>
                        <div class="fw-nav-card empty"></div>
                    <?php endif; ?>

                    <?php if ( ! empty( $next_post ) ) : ?>
                        <a href="<?php echo get_permalink( $next_post->ID ); ?>" class="fw-nav-card next">
                            <div class="nav-content">
                                <span class="nav-label">Next Post &rarr;</span>
                                <h4 class="nav-title"><?php echo esc_html( $next_post->post_title ); ?></h4>
                            </div>
                        </a>
                    <?php else : ?>
                        <div class="fw-nav-card empty"></div>
                    <?php endif; ?>
                </div>
            </nav>

            <?php
            if ( comments_open() || get_comments_number() ) :
                echo '<div style="max-width: 800px; margin: 3rem auto;">';
                comments_template();
                echo '</div>';
            endif;

        endwhile; ?>

    </main>
</div>

<?php get_footer(); ?>