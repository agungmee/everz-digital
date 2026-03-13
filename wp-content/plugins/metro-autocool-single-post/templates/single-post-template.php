<?php
/**
 * Single Post Template
 */

// Exit jika file diakses langsung
if (!defined('ABSPATH')) {
    exit;
}

// Get post data
$post_id = get_the_ID();
$author_name = get_the_author();
$publish_date = get_the_date('d F Y');
$featured_image_url = get_the_post_thumbnail_url($post_id, 'large');

// Calculate reading time
$content = get_the_content();
$word_count = str_word_count(strip_tags($content));
$reading_time = ceil($word_count / 200); // 200 words per minute

?>
<!DOCTYPE html>
<html lang="<?php language_attributes(); ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title(); ?></title>
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php the_title(); ?>">
    <meta property="og:description" content="<?php echo wp_trim_words(strip_tags(get_the_excerpt() ?: get_the_content()), 20); ?>">
    <?php if ($featured_image_url) { ?>
        <meta property="og:image" content="<?php echo esc_attr($featured_image_url); ?>">
    <?php } ?>
    <meta property="og:url" content="<?php the_permalink(); ?>">
    <meta property="og:type" content="article">
    <meta property="og:locale" content="id_ID">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php the_title(); ?>">
    
    <?php wp_head(); ?>
    <script>
        document.body.classList.add('mc-single-post');
    </script>
</head>
<body <?php body_class('mc-single-post'); ?>>
    <?php wp_body_open(); ?>

    <!-- Navbar -->
    <nav class="mc-navbar" aria-label="Main Navigation">
        <div class="mc-navbar-inner">
            <a class="mc-navbar-brand" href="<?php echo home_url(); ?>">
                <img class="mc-navbar-logo" src="https://everz-digital.site/wp-content/uploads/2026/02/task_01kh89sfy2fwt8ckgeez9sxdpf_1770879018_img_0.webp" alt="Logo Metro Autocool">
                <span>Metro</span> Autocool
            </a>
        </div>
    </nav>

    <div class="mc-single-post-wrapper">
        <!-- Hero Section -->
        <div class="mc-post-hero">
            <div class="mc-post-hero-inner">
                <h1 class="mc-post-title"><?php the_title(); ?></h1>
                <div class="mc-post-meta">
                    <span class="mc-post-meta-item">
                        <i class="fa-solid fa-user" aria-hidden="true"></i>
                        Oleh <?php echo esc_html($author_name); ?>
                    </span>
                    <span class="mc-post-meta-item">
                        <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                        <?php echo esc_html($publish_date); ?>
                    </span>
                    <span class="mc-post-meta-item">
                        <i class="fa-regular fa-clock" aria-hidden="true"></i>
                        <?php echo max(1, $reading_time); ?> Menit Baca
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <article class="mc-post-container">
            <?php if ($featured_image_url) { ?>
                <img class="mc-post-featured" src="<?php echo esc_attr($featured_image_url); ?>" alt="<?php the_title(); ?>">
            <?php } ?>

            <div class="mc-post-content">
                <?php the_content(); ?>
            </div>
        </article>

        <!-- Related Posts -->
        <?php
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
            'post__not_in' => array($post_id),
            'post_status' => 'publish',
        );

        $related_query = new WP_Query($args);

        if ($related_query->have_posts()) {
        ?>
            <section class="mc-post-related">
                <div class="mc-post-related-inner">
                    <h2 class="mc-post-related-title">Artikel <span>Lainnya</span></h2>
                    <div class="mc-post-related-grid">
                        <?php
                        while ($related_query->have_posts()) {
                            $related_query->the_post();
                            $related_featured = get_the_post_thumbnail_url(get_the_ID(), 'large');
                            if (!$related_featured) {
                                $related_featured = 'https://via.placeholder.com/400x210?text=' . urlencode(get_the_title());
                            }
                        ?>
                            <article class="mc-post-related-card">
                                <img src="<?php echo esc_attr($related_featured); ?>" alt="<?php the_title(); ?>">
                                <div class="mc-post-related-body">
                                    <h3><?php the_title(); ?></h3>
                                    <p><?php echo wp_trim_words(strip_tags(get_the_excerpt() ?: get_the_content()), 15); ?></p>
                                    <a class="mc-post-related-link" href="<?php the_permalink(); ?>">Baca Selengkapnya</a>
                                </div>
                            </article>
                        <?php
                        }
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </section>
        <?php
        }
        ?>
    </div>

    <!-- Sticky WA Button -->
    <a class="mc-sticky-wa" href="https://wa.me/62852585862620?text=Halo%20Metro%20Autocool%20tentang%20artikel%20<?php the_title(); ?>" target="_blank" rel="noopener" aria-label="Hubungi Metro Autocool via WhatsApp">
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
        <span>Hubungi Kami</span>
    </a>

    <!-- Footer -->
    <footer class="mc-footer" aria-label="Footer Metro Autocool">
        <div class="mc-footer-inner">
            <img class="mc-footer-logo" src="https://everz-digital.site/wp-content/uploads/2026/02/task_01kh89sfy2fwt8ckgeez9sxdpf_1770879018_img_0.webp" alt="Logo Metro Autocool">
            <p class="mc-footer-name"><span>Metro</span> Autocool</p>
            <p class="mc-footer-address">Sutorejo Utara 5 No 12 Surabaya</p>
            <p class="mc-footer-copy">Copyright &copy; <?php echo date('Y'); ?> Metro Autocool. All Rights Reserved.</p>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
