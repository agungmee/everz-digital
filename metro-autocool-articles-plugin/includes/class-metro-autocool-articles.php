<?php
/**
 * Main Plugin Class
 */

class Metro_Autocool_Articles {
    
    public function run() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_shortcode('metro_articles', array($this, 'render_articles_shortcode'));
        
        // Register Elementor widget jika Elementor aktif
        if (did_action('elementor/loaded')) {
            add_action('elementor/widgets/register', array($this, 'register_elementor_widget'));
        }
    }
    
    /**
     * Enqueue CSS untuk plugin
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'metro-autocool-articles-style',
            METRO_AUTOCOOL_ARTICLES_URL . 'assets/css/style.css',
            array(),
            METRO_AUTOCOOL_ARTICLES_VERSION
        );
    }
    
    /**
     * Shortcode untuk menampilkan artikel
     * [metro_articles title="Artikel Terbaru" limit="3" columns="3"]
     */
    public function render_articles_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => 'Artikel',
            'limit' => 3,
            'columns' => 3,
            'category' => '',
        ), $atts);
        
        return $this->get_articles_html($atts);
    }
    
    /**
     * Generate HTML untuk artikel
     */
    private function get_articles_html($args) {
        $args = wp_parse_args($args, array(
            'title' => 'Artikel',
            'limit' => 3,
            'columns' => 3,
            'category' => '',
        ));
        
        // Query artikel dari WordPress
        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => intval($args['limit']),
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish',
        );
        
        // Add category filter jika diperlukan
        if (!empty($args['category'])) {
            $query_args['category_name'] = $args['category'];
        }
        
        $query = new WP_Query($query_args);
        
        ob_start();
        
        if ($query->have_posts()) {
            echo '<section class="mc-articles" aria-label="' . esc_attr($args['title']) . '">';
            echo '<div class="mc-articles-inner">';
            
            // Title
            echo '<h2 class="mc-articles-head">';
            $title_parts = explode(' ', $args['title']);
            $last_word = array_pop($title_parts);
            if (!empty($title_parts)) {
                echo '<span>' . esc_html(implode(' ', $title_parts)) . '</span> ';
            }
            echo esc_html($last_word);
            echo '</h2>';
            
            // Grid
            echo '<div class="mc-articles-grid mc-articles-grid-' . intval($args['columns']) . '">';
            
            while ($query->have_posts()) {
                $query->the_post();
                echo $this->render_article_card();
            }
            
            echo '</div>';
            echo '</div>';
            echo '</section>';
        }
        
        wp_reset_postdata();
        
        return ob_get_clean();
    }
    
    /**
     * Render single article card
     */
    private function render_article_card() {
        $post_id = get_the_ID();
        $featured_image = get_the_post_thumbnail_url($post_id, 'large');
        
        if (!$featured_image) {
            $featured_image = 'https://via.placeholder.com/400x210?text=' . urlencode(get_the_title());
        }
        
        $author_name = get_the_author();
        $publish_date = get_the_date('d F Y');
        $reading_time = $this->estimate_reading_time($post_id);
        
        $html = '<article class="mc-article-card">';
        $html .= '<img src="' . esc_url($featured_image) . '" alt="' . esc_attr(get_the_title()) . '">';
        $html .= '<div class="mc-article-body">';
        $html .= '<p class="mc-article-meta">Oleh ' . esc_html($author_name) . ' • ' . esc_html($publish_date) . ' • ' . esc_html($reading_time) . ' Menit Baca</p>';
        $html .= '<h3 class="mc-article-title">' . esc_html(get_the_title()) . '</h3>';
        $html .= '<p class="mc-article-excerpt">' . esc_html($this->get_excerpt()) . '</p>';
        $html .= '<a class="mc-article-link" href="' . esc_url(get_permalink()) . '">Baca Selengkapnya</a>';
        $html .= '</div>';
        $html .= '</article>';
        
        return $html;
    }
    
    /**
     * Get custom excerpt
     */
    private function get_excerpt() {
        $excerpt = get_the_excerpt();
        if (empty($excerpt)) {
            $content = get_the_content();
            $excerpt = wp_trim_words(strip_tags($content), 20);
        }
        return $excerpt;
    }
    
    /**
     * Estimate reading time
     */
    private function estimate_reading_time($post_id) {
        $content = get_post_field('post_content', $post_id);
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // 200 words per minute
        return max(1, $reading_time);
    }
    
    /**
     * Register Elementor Widget
     */
    public function register_elementor_widget($widgets_manager) {
        require_once METRO_AUTOCOOL_ARTICLES_PATH . 'includes/class-metro-articles-widget.php';
        $widgets_manager->register(new Metro_Articles_Elementor_Widget());
    }
}
