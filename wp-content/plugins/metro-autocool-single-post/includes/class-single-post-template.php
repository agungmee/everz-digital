<?php
/**
 * Main Plugin Class for Single Post Template
 */

class Metro_Single_Post_Template {
    
    public function run() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_filter('single_template', array($this, 'load_custom_single_template'));
    }
    
    /**
     * Enqueue CSS untuk plugin
     */
    public function enqueue_styles() {
        if (is_singular('post')) {
            wp_enqueue_style(
                'metro-single-post-style',
                METRO_SINGLE_POST_URL . 'assets/css/single-post.css',
                array(),
                METRO_SINGLE_POST_VERSION
            );
            
            // Load Font Awesome
            wp_enqueue_style(
                'font-awesome-6',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
                array(),
                '6.5.2'
            );
            
            // Load Google Fonts
            wp_enqueue_style(
                'google-fonts-barlow',
                'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800&display=swap',
                array(),
                null
            );
        }
    }
    
    /**
     * Load custom single template
     */
    public function load_custom_single_template($template) {
        if (is_singular('post')) {
            $custom_template = METRO_SINGLE_POST_PATH . 'templates/single-post-template.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        return $template;
    }
}
