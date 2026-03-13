<?php
/**
 * Elementor Widget untuk Metro Autocool Articles
 */

class Metro_Articles_Elementor_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'metro_articles_widget';
    }
    
    public function get_title() {
        return 'Metro Autocool Articles';
    }
    
    public function get_icon() {
        return 'eicon-posts-grid';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Pengaturan Artikel',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'title',
            [
                'label' => 'Judul',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Artikel Terbaru',
                'placeholder' => 'Contoh: Artikel Terbaru',
            ]
        );
        
        $this->add_control(
            'limit',
            [
                'label' => 'Jumlah Artikel',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 12,
            ]
        );
        
        $this->add_control(
            'columns',
            [
                'label' => 'Jumlah Kolom',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1 Kolom',
                    '2' => '2 Kolom',
                    '3' => '3 Kolom',
                    '4' => '4 Kolom',
                ],
                'default' => '3',
            ]
        );
        
        $this->add_control(
            'category',
            [
                'label' => 'Kategori',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_categories_options(),
                'default' => '',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => 'Styling',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => 'Warna Judul',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mc-articles-head' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'accent_color',
            [
                'label' => 'Warna Aksen (Kata terakhir judul)',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#dc2626',
                'selectors' => [
                    '{{WRAPPER}} .mc-articles-head span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mc-article-link' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $plugin = new Metro_Autocool_Articles();
        echo $plugin->render_articles_shortcode(array(
            'title' => $settings['title'],
            'limit' => $settings['limit'],
            'columns' => $settings['columns'],
            'category' => $settings['category'],
        ));
    }
    
    /**
     * Get WordPress categories
     */
    private function get_categories_options() {
        $options = ['0' => 'Semua Kategori'];
        
        $categories = get_categories();
        foreach ($categories as $cat) {
            $options[$cat->slug] = $cat->name;
        }
        
        return $options;
    }
}
