<?php
/**
 * Template Name: Blog Page
 * Description: A custom page template that integrates all categories into a sleek 3-column grid.
 */
get_header(); ?>

<!-- Tailwind CSS CDN (Scoped to this page only to avoid theme conflicts) -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        corePlugins: {
            preflight: false, // Prevents Tailwind from overriding default GeneratePress/Theme styles
        },
        theme: {
            extend: {
                colors: {
                    navy: '#0a192f', // Matched with your style.css --navy variable
                }
            }
        }
    }
</script>
<style>
    /* Custom CSS for Blog Page interactions */
    .fw-blog-wrapper {
        font-family: 'Inter', sans-serif;
        background-color: #f9fafb;
    }
    .fw-blog-wrapper .post-card {
        transition: all 0.3s ease;
        border: 1px solid #f3f4f6;
    }
    .fw-blog-wrapper .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        border-color: #e5e7eb;
    }
    .fw-blog-wrapper .category-btn {
        transition: all 0.2s ease;
    }
    .fw-blog-wrapper .category-btn.active {
        background-color: #0a192f;
        color: white;
        border-color: #0a192f;
    }
    
    /* WordPress Pagination Customization */
    .fw-blog-wrapper .page-numbers { display: inline-flex; align-items: center; padding: 0.5rem 1rem; border: 1px solid #d1d5db; background: white; color: #374151; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: background-color 0.2s; }
    .fw-blog-wrapper .page-numbers.current { background: #f0f9ff; border-color: #0ea5e9; color: #0284c7; z-index: 10; }
    .fw-blog-wrapper .page-numbers:hover:not(.current) { background: #f9fafb; }
    .fw-blog-wrapper ul.page-numbers { display: flex; padding-left: 0; list-style: none; border-radius: 0.375rem; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
    .fw-blog-wrapper ul.page-numbers li:first-child .page-numbers { border-top-left-radius: 0.375rem; border-bottom-left-radius: 0.375rem; }
    .fw-blog-wrapper ul.page-numbers li:last-child .page-numbers { border-top-right-radius: 0.375rem; border-bottom-right-radius: 0.375rem; }
</style>

<div class="fw-blog-wrapper py-12 md:py-16 text-gray-800">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Enhanced SEO-friendly Page Header -->
        <div class="relative mb-16 pt-6 pb-12 border-b border-gray-200">
            <!-- Decorative background element -->
            <div class="absolute inset-0 bg-gradient-to-b from-blue-50/40 to-transparent -z-10 rounded-3xl"></div>
            
            <div class="max-w-3xl mx-auto text-center">
                <!-- Top Badge -->
                <span class="inline-block py-1.5 px-4 rounded-full bg-white border border-blue-100 shadow-sm text-blue-600 text-xs font-bold tracking-widest uppercase mb-5">
                    FinanceWise Journal
                </span>
                
                <!-- SEO Optimized H1 Title -->
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 tracking-tight" style="color: #0a192f;">
                    Financial Insights & <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Investment Strategies</span>
                </h1>
                
                <!-- SEO Optimized Description -->
                <p class="text-lg md:text-xl text-gray-600 leading-relaxed">
                    Master your wealth with expert analysis. Discover actionable tips on <strong class="font-medium text-gray-900">personal finance, stock market trends, and real estate investing</strong> to accelerate your journey to financial freedom.
                </p>
            </div>
        </div>

        <!-- Dynamic Category Filters -->
        <?php 
        // Fetch all non-empty categories
        $categories = get_categories( array(
            'orderby' => 'name',
            'order'   => 'ASC',
            'exclude' => 1 // Excludes the default 'Uncategorized' if its ID is 1
        ) ); 
        ?>
        <div class="mb-10 border-b border-gray-200 pb-4">
            <div class="flex space-x-2 overflow-x-auto pb-2" id="category-filters">
                <button data-filter="all" class="category-btn active px-5 py-2 rounded-full border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 whitespace-nowrap cursor-pointer">
                    All Updates
                </button>
                <?php foreach( $categories as $category ) : ?>
                    <button data-filter="<?php echo esc_attr( $category->slug ); ?>" class="category-btn px-5 py-2 rounded-full border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 whitespace-nowrap cursor-pointer">
                        <?php echo esc_html( $category->name ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Blog Post Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="post-grid">
            <?php
            // WP_Query to fetch posts
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 12, // Number of posts per page
                'paged'          => $paged
            );
            $blog_query = new WP_Query( $args );

            if ( $blog_query->have_posts() ) :
                while ( $blog_query->have_posts() ) : $blog_query->the_post();
                    
                    // Retrieve specific category for the post
                    $post_categories = get_the_category();
                    $cat_slug = ! empty( $post_categories ) ? $post_categories[0]->slug : '';
                    $cat_name = ! empty( $post_categories ) ? $post_categories[0]->name : 'Blog';

                    // Fallback image if thumbnail doesn't exist
                    $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
                    if ( ! $thumb_url ) {
                        $thumb_url = 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?auto=format&fit=crop&q=80&w=800'; // Default placeholder
                    }
            ?>
            
            <article class="post-item bg-white rounded-2xl overflow-hidden post-card flex flex-col h-full" data-category="<?php echo esc_attr( $cat_slug ); ?>">
                <a href="<?php the_permalink(); ?>" class="block relative aspect-[16/9] overflow-hidden group">
                    <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-navy text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                        <?php echo esc_html( $cat_name ); ?>
                    </div>
                </a>
                
                <div class="p-6 flex flex-col flex-grow">
                    <div class="text-xs text-gray-500 mb-2 flex items-center">
                        <!-- Calendar SVG Icon -->
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <?php echo get_the_date( 'M j, Y' ); ?>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="block mb-3" style="text-decoration: none;">
                        <h2 class="text-xl font-bold text-gray-900 leading-tight transition-colors" style="margin: 0; padding-bottom: 0.5rem;">
                            <?php the_title(); ?>
                        </h2>
                    </a>
                    
                    <div class="text-gray-600 text-sm mb-4 flex-grow line-clamp-3 overflow-hidden">
                        <?php echo wp_trim_words( get_the_excerpt(), 22, '...' ); ?>
                    </div>
                    
                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 24, '', 'Author', array( 'class' => 'rounded-full mr-2' ) ); ?>
                            <span class="text-xs font-medium text-gray-700"><?php the_author(); ?></span>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="text-sm font-semibold flex items-center" style="color: #0a192f; text-decoration: none;">
                            Read More
                            <!-- Arrow Right SVG Icon -->
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </article>

            <?php
                endwhile;
            else :
            ?>
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12">
                    <h2 class="text-2xl font-bold text-gray-900">No articles found</h2>
                    <p class="text-gray-500 mt-2">Please check back later for new content.</p>
                </div>
            <?php
            endif;
            ?>
        </div>

        <!-- Custom Pagination -->
        <div class="mt-16 flex justify-center">
            <?php
            // Displays default WordPress pagination but styled with CSS above
            echo paginate_links( array(
                'total'     => $blog_query->max_num_pages,
                'current'   => $paged,
                'prev_text' => '&larr; Previous',
                'next_text' => 'Next &rarr;',
                'type'      => 'list',
                'mid_size'  => 2,
            ) );
            wp_reset_postdata(); // Reset query
            ?>
        </div>

    </main>
</div>

<!-- JavaScript for Frontend Category Filtering -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.category-btn');
        const postItems = document.querySelectorAll('.post-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                btn.classList.add('active');
                
                const filterValue = btn.getAttribute('data-filter');

                // Filter the posts
                postItems.forEach(item => {
                    if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>

<?php get_footer(); ?>