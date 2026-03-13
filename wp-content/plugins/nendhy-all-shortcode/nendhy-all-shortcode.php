<?php
/**
 * Plugin Name: Nendhy All Shortcode Manager
 * Description: Render landing page Nendhy dari template all.html via shortcode + CRUD Tour Activity, Additional Services, dan Gallery.
 * Version: 1.0.0
 * Author: Codex
 */

if (!defined('ABSPATH')) {
    exit;
}

class Nendhy_All_Shortcode_Manager {
    const OPTION_TOUR = 'nendhy_tour_activity_items';
    const OPTION_SERVICES = 'nendhy_additional_service_items';
    const OPTION_GALLERY = 'nendhy_gallery_items';

    private $template_parts = null;

    public function __construct() {
        add_shortcode('nendhy_all', array($this, 'render_shortcode'));

        add_action('admin_menu', array($this, 'register_admin_menu'));
        add_action('admin_init', array($this, 'handle_admin_submit'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    public function register_admin_menu() {
        add_menu_page(
            'Nendhy Landing',
            'Nendhy Landing',
            'manage_options',
            'nendhy-all-shortcode',
            array($this, 'render_admin_home'),
            'dashicons-admin-site-alt3',
            57
        );

        add_submenu_page(
            'nendhy-all-shortcode',
            'Tour Activity',
            'Tour Activity',
            'manage_options',
            'nendhy-tour-activity',
            array($this, 'render_admin_tour')
        );

        add_submenu_page(
            'nendhy-all-shortcode',
            'Additional Services',
            'Additional Services',
            'manage_options',
            'nendhy-additional-services',
            array($this, 'render_admin_services')
        );

        add_submenu_page(
            'nendhy-all-shortcode',
            'Gallery',
            'Gallery',
            'manage_options',
            'nendhy-gallery',
            array($this, 'render_admin_gallery')
        );
    }

    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'nendhy-') === false) {
            return;
        }
        wp_enqueue_media();
    }

    public function handle_admin_submit() {
        if (!isset($_POST['nendhy_nonce'])) {
            return;
        }
        if (!current_user_can('manage_options')) {
            return;
        }
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nendhy_nonce'])), 'nendhy_save_data')) {
            return;
        }

        $type = isset($_POST['nendhy_section_type']) ? sanitize_key(wp_unslash($_POST['nendhy_section_type'])) : '';
        if ($type === 'tour') {
            $rows = $this->sanitize_tour_rows($_POST);
            update_option(self::OPTION_TOUR, $rows);
            $this->redirect_updated('nendhy-tour-activity');
        }

        if ($type === 'services') {
            $rows = $this->sanitize_service_rows($_POST);
            update_option(self::OPTION_SERVICES, $rows);
            $this->redirect_updated('nendhy-additional-services');
        }

        if ($type === 'gallery') {
            $rows = $this->sanitize_gallery_rows($_POST);
            update_option(self::OPTION_GALLERY, $rows);
            $this->redirect_updated('nendhy-gallery');
        }
    }

    private function redirect_updated($slug) {
        $url = add_query_arg(
            array(
                'page' => $slug,
                'updated' => '1',
            ),
            admin_url('admin.php')
        );
        wp_safe_redirect($url);
        exit;
    }

    private function sanitize_tour_rows($post) {
        $titles = isset($post['title']) && is_array($post['title']) ? wp_unslash($post['title']) : array();
        $descs = isset($post['desc']) && is_array($post['desc']) ? wp_unslash($post['desc']) : array();
        $lists = isset($post['list']) && is_array($post['list']) ? wp_unslash($post['list']) : array();
        $book_urls = isset($post['book_url']) && is_array($post['book_url']) ? wp_unslash($post['book_url']) : array();
        $image_urls = isset($post['image_url']) && is_array($post['image_url']) ? wp_unslash($post['image_url']) : array();
        $image_alts = isset($post['image_alt']) && is_array($post['image_alt']) ? wp_unslash($post['image_alt']) : array();
        $slider_images = isset($post['slider_images']) && is_array($post['slider_images']) ? wp_unslash($post['slider_images']) : array();

        $count = max(count($titles), count($descs), count($lists), count($book_urls), count($image_urls), count($slider_images));
        $rows = array();

        for ($i = 0; $i < $count; $i++) {
            $title = isset($titles[$i]) ? sanitize_text_field($titles[$i]) : '';
            $desc = isset($descs[$i]) ? sanitize_textarea_field($descs[$i]) : '';
            $book_url = isset($book_urls[$i]) ? esc_url_raw(trim((string) $book_urls[$i])) : '';
            $image_url = isset($image_urls[$i]) ? esc_url_raw(trim((string) $image_urls[$i])) : '';
            $image_alt = isset($image_alts[$i]) ? sanitize_text_field($image_alts[$i]) : '';
            $list_raw = isset($lists[$i]) ? (string) $lists[$i] : '';
            $slider_raw = isset($slider_images[$i]) ? (string) $slider_images[$i] : '';

            $list_items = array();
            foreach (preg_split('/\r\n|\r|\n/', $list_raw) as $line) {
                $line = trim($line);
                if ($line === '') {
                    continue;
                }
                $list_items[] = wp_kses($line, array('b' => array(), 'strong' => array(), 'i' => array(), 'em' => array(), 'br' => array()));
            }

            $slider_list = array();
            foreach (preg_split('/\r\n|\r|\n/', $slider_raw) as $line) {
                $line = esc_url_raw(trim($line));
                if ($line !== '') {
                    $slider_list[] = $line;
                }
            }

            if ($title === '' || $book_url === '' || (empty($slider_list) && $image_url === '')) {
                continue;
            }

            $rows[] = array(
                'title' => $title,
                'desc' => $desc,
                'list' => $list_items,
                'book_url' => $book_url,
                'image_url' => $image_url,
                'image_alt' => $image_alt,
                'slider_images' => $slider_list,
            );
        }

        return $rows;
    }

    private function sanitize_service_rows($post) {
        $titles = isset($post['title']) && is_array($post['title']) ? wp_unslash($post['title']) : array();
        $descs = isset($post['desc']) && is_array($post['desc']) ? wp_unslash($post['desc']) : array();
        $info_urls = isset($post['info_url']) && is_array($post['info_url']) ? wp_unslash($post['info_url']) : array();
        $image_urls = isset($post['image_url']) && is_array($post['image_url']) ? wp_unslash($post['image_url']) : array();
        $image_alts = isset($post['image_alt']) && is_array($post['image_alt']) ? wp_unslash($post['image_alt']) : array();
        $price_list_flags = isset($post['show_price_list']) && is_array($post['show_price_list']) ? wp_unslash($post['show_price_list']) : array();

        $count = max(count($titles), count($descs), count($info_urls), count($image_urls));
        $rows = array();

        for ($i = 0; $i < $count; $i++) {
            $title = isset($titles[$i]) ? sanitize_text_field($titles[$i]) : '';
            $desc = isset($descs[$i]) ? sanitize_textarea_field($descs[$i]) : '';
            $info_url = isset($info_urls[$i]) ? esc_url_raw(trim((string) $info_urls[$i])) : '';
            $image_url = isset($image_urls[$i]) ? esc_url_raw(trim((string) $image_urls[$i])) : '';
            $image_alt = isset($image_alts[$i]) ? sanitize_text_field($image_alts[$i]) : '';
            $show_price_list = isset($price_list_flags[$i]) ? (int) $price_list_flags[$i] === 1 : false;

            if ($title === '' || $info_url === '' || $image_url === '') {
                continue;
            }

            $rows[] = array(
                'title' => $title,
                'desc' => $desc,
                'info_url' => $info_url,
                'image_url' => $image_url,
                'image_alt' => $image_alt,
                'show_price_list' => $show_price_list,
            );
        }

        return $rows;
    }

    private function sanitize_gallery_rows($post) {
        $image_urls = isset($post['image_url']) && is_array($post['image_url']) ? wp_unslash($post['image_url']) : array();
        $image_alts = isset($post['image_alt']) && is_array($post['image_alt']) ? wp_unslash($post['image_alt']) : array();

        $count = max(count($image_urls), count($image_alts));
        $rows = array();

        for ($i = 0; $i < $count; $i++) {
            $image_url = isset($image_urls[$i]) ? esc_url_raw(trim((string) $image_urls[$i])) : '';
            $image_alt = isset($image_alts[$i]) ? sanitize_text_field($image_alts[$i]) : '';

            if ($image_url === '') {
                continue;
            }

            $rows[] = array(
                'image_url' => $image_url,
                'image_alt' => $image_alt,
            );
        }

        return $rows;
    }

    public function render_shortcode() {
        $parts = $this->get_template_parts();
        if (empty($parts['css']) || empty($parts['body']) || empty($parts['script'])) {
            return '<p>Template Nendhy tidak ditemukan.</p>';
        }

        $body = $parts['body'];
        $body = preg_replace(
            '~<section class="aha-section aha-tour-activity" id="packages">.*?</section>~s',
            $this->render_tour_section(),
            $body,
            1
        );
        $body = preg_replace(
            '~<section class="aha-section aha-services" id="additional-services">.*?</section>~s',
            $this->render_services_section(),
            $body,
            1
        );
        $body = preg_replace(
            '~<section class="aha-section aha-gallery" id="gallery">.*?</section>~s',
            $this->render_gallery_section(),
            $body,
            1
        );

        $script = preg_replace('~// Load sections when DOM is ready[\s\S]*$~', '', $parts['script']);
        $script .= "\nif (!window.__nendhyAllInitialized && typeof initializeScripts === 'function') { window.__nendhyAllInitialized = true; initializeScripts(); }\n";

        ob_start();
        ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
        <style><?php echo $parts['css']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
        <?php echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <script><?php echo $script; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></script>
        <?php
        return ob_get_clean();
    }

    private function get_tour_items() {
        $saved = get_option(self::OPTION_TOUR, array());
        if (!empty($saved) && is_array($saved)) {
            return $saved;
        }

        $defaults = $this->parse_tour_defaults_from_template();
        if (!empty($defaults)) {
            update_option(self::OPTION_TOUR, $defaults);
            return $defaults;
        }

        return array();
    }

    private function get_service_items() {
        $saved = get_option(self::OPTION_SERVICES, array());
        if (!empty($saved) && is_array($saved)) {
            return $saved;
        }

        $defaults = $this->parse_service_defaults_from_template();
        if (!empty($defaults)) {
            update_option(self::OPTION_SERVICES, $defaults);
            return $defaults;
        }

        return array();
    }

    private function get_gallery_items() {
        $saved = get_option(self::OPTION_GALLERY, array());
        if (!empty($saved) && is_array($saved)) {
            return $saved;
        }

        $defaults = $this->parse_gallery_defaults_from_template();
        if (!empty($defaults)) {
            update_option(self::OPTION_GALLERY, $defaults);
            return $defaults;
        }

        return array();
    }

    private function render_tour_section() {
        $items = $this->get_tour_items();
        ob_start();
        ?>
  <section class="aha-section aha-tour-activity" id="packages">
  <h2 class="aha-section-title">Tour Activities</h2>
  <p class="aha-section-sub">Popular activity options for your trip in Lombok and nearby areas.</p>

  <div class="tour-activity-carousel-wrapper">
    <div class="tour-activity-carousel" id="tourActivityCarousel">
      <div class="tour-activity-track">
<?php foreach ($items as $item) : ?>
        <article class="tour-activity-card">
          <?php if (!empty($item['slider_images']) && count($item['slider_images']) > 1) : ?>
          <div class="tour-activity-media" data-autoslide="true" data-interval="3200">
            <?php foreach ($item['slider_images'] as $idx => $slider_img) : ?>
            <img <?php echo $idx === 0 ? 'class="is-active"' : ''; ?> src="<?php echo esc_url($slider_img); ?>" alt="<?php echo esc_attr($item['title'] . ' ' . ($idx + 1)); ?>" loading="lazy">
            <?php endforeach; ?>
          </div>
          <?php else : ?>
          <img src="<?php echo esc_url($item['image_url']); ?>" alt="<?php echo esc_attr($item['image_alt'] ? $item['image_alt'] : $item['title']); ?>" loading="lazy">
          <?php endif; ?>
          <h3><?php echo esc_html($item['title']); ?></h3>
          <p class="tour-activity-desc"><?php echo esc_html($item['desc']); ?></p>
          <ul class="tour-activity-list">
            <?php foreach ((array) $item['list'] as $line) : ?>
            <li><?php echo wp_kses_post($line); ?></li>
            <?php endforeach; ?>
          </ul>
          <a class="tour-activity-book" href="<?php echo esc_url($item['book_url']); ?>" target="_blank" rel="noopener">Book This Tour</a>
        </article>
<?php endforeach; ?>
      </div>
      <button class="tour-activity-nav tour-activity-prev" aria-label="Previous">‹</button>
      <button class="tour-activity-nav tour-activity-next" aria-label="Next">›</button>
    </div>
  </div>
</section>
        <?php
        return ob_get_clean();
    }

    private function render_services_section() {
        $items = $this->get_service_items();
        ob_start();
        ?>
  <section class="aha-section aha-services" id="additional-services">
  <h2 class="aha-section-title">Additional Services</h2>
  <p class="aha-section-sub">Extra services to make your trip easier and more comfortable.</p>

  <div class="aha-services-carousel-wrapper">
    <div class="aha-services-carousel" id="servicesCarousel">
      <div class="aha-services-track">
<?php foreach ($items as $item) : ?>
        <article class="aha-service-card">
          <img src="<?php echo esc_url($item['image_url']); ?>" alt="<?php echo esc_attr($item['image_alt'] ? $item['image_alt'] : $item['title']); ?>" loading="lazy">
          <h3><?php echo esc_html($item['title']); ?></h3>
          <p class="aha-service-desc"><?php echo esc_html($item['desc']); ?></p>
          <?php if (!empty($item['show_price_list'])) : ?>
          <div class="aha-service-actions">
            <a class="aha-service-book" href="<?php echo esc_url($item['info_url']); ?>" target="_blank" rel="noopener">More Info</a>
            <button class="aha-service-alt aha-price-list-btn" type="button">Price List</button>
          </div>
          <?php else : ?>
          <a class="aha-service-book" href="<?php echo esc_url($item['info_url']); ?>" target="_blank" rel="noopener">More Info</a>
          <?php endif; ?>
        </article>
<?php endforeach; ?>
      </div>
      <button class="aha-services-nav aha-services-prev" aria-label="Previous">‹</button>
      <button class="aha-services-nav aha-services-next" aria-label="Next">›</button>
    </div>
  </div>
</section>
        <?php
        return ob_get_clean();
    }

    private function render_gallery_section() {
        $items = $this->get_gallery_items();
        ob_start();
        ?>
  <section class="aha-section aha-gallery" id="gallery">
  <h2 class="aha-section-title">Trip Gallery</h2>
  <p class="aha-section-sub">Moments from our tours, transfers, and activities around Lombok.</p>

  <div class="aha-gallery-carousel" id="ahaGalleryCarousel">
    <div class="aha-gallery-track">
<?php foreach ($items as $index => $item) : ?>
      <article class="aha-gallery-item"><img src="<?php echo esc_url($item['image_url']); ?>" alt="<?php echo esc_attr($item['image_alt'] ? $item['image_alt'] : ('Trip gallery photo ' . ($index + 1))); ?>" loading="lazy"></article>
<?php endforeach; ?>
    </div>
  </div>
</section>
        <?php
        return ob_get_clean();
    }

    private function get_template_parts() {
        if (is_array($this->template_parts)) {
            return $this->template_parts;
        }

        $file = plugin_dir_path(__FILE__) . 'templates/all.html';
        if (!file_exists($file)) {
            return array('css' => '', 'body' => '', 'script' => '');
        }

        $all = file_get_contents($file);
        if ($all === false) {
            return array('css' => '', 'body' => '', 'script' => '');
        }

        $css = '';
        $body = '';
        $script = '';

        if (preg_match('~<style>([\s\S]*?)</style>~', $all, $m)) {
            $css = trim($m[1]);
        }
        if (preg_match('~<body>([\s\S]*?)<script>~', $all, $m)) {
            $body = trim($m[1]);
        }
        if (preg_match('~<script>([\s\S]*?)</script>~', $all, $m)) {
            $script = trim($m[1]);
        }

        $this->template_parts = array(
            'css' => $css,
            'body' => $body,
            'script' => $script,
        );

        return $this->template_parts;
    }

    private function parse_tour_defaults_from_template() {
        $parts = $this->get_template_parts();
        $body = $parts['body'];
        if ($body === '') {
            return array();
        }

        if (!preg_match('~<section class="aha-section aha-tour-activity" id="packages">([\s\S]*?)</section>~', $body, $section_match)) {
            return array();
        }

        $section = $section_match[0];
        if (!preg_match_all('~<article class="tour-activity-card">([\s\S]*?)</article>~', $section, $cards)) {
            return array();
        }

        $rows = array();
        foreach ($cards[1] as $card) {
            $title = '';
            $desc = '';
            $book_url = '';
            $image_url = '';
            $image_alt = '';
            $list = array();
            $slider_images = array();

            if (preg_match('~<h3>([\s\S]*?)</h3>~', $card, $m)) {
                $title = wp_strip_all_tags($m[1]);
            }
            if (preg_match('~<p class="tour-activity-desc">([\s\S]*?)</p>~', $card, $m)) {
                $desc = html_entity_decode(trim(wp_strip_all_tags($m[1])), ENT_QUOTES, 'UTF-8');
            }
            if (preg_match('~<a class="tour-activity-book" href="([^"]+)"~', $card, $m)) {
                $book_url = esc_url_raw($m[1]);
            }

            if (preg_match('~<div class="tour-activity-media"[^>]*>([\s\S]*?)</div>~', $card, $m)) {
                if (preg_match_all('~<img[^>]+src="([^"]+)"~', $m[1], $imgs)) {
                    foreach ($imgs[1] as $img) {
                        $img = esc_url_raw($img);
                        if ($img !== '') {
                            $slider_images[] = $img;
                        }
                    }
                }
                if (!empty($slider_images)) {
                    $image_url = $slider_images[0];
                }
            } elseif (preg_match('~<img[^>]+src="([^"]+)"[^>]*alt="([^"]*)"~', $card, $m)) {
                $image_url = esc_url_raw($m[1]);
                $image_alt = sanitize_text_field($m[2]);
            }

            if (preg_match_all('~<li>([\s\S]*?)</li>~', $card, $list_match)) {
                foreach ($list_match[1] as $line) {
                    $list[] = trim($line);
                }
            }

            if ($title === '' || $book_url === '' || $image_url === '') {
                continue;
            }

            $rows[] = array(
                'title' => sanitize_text_field($title),
                'desc' => sanitize_textarea_field($desc),
                'list' => $list,
                'book_url' => $book_url,
                'image_url' => $image_url,
                'image_alt' => $image_alt,
                'slider_images' => $slider_images,
            );
        }

        return $rows;
    }

    private function parse_service_defaults_from_template() {
        $parts = $this->get_template_parts();
        $body = $parts['body'];
        if ($body === '') {
            return array();
        }

        if (!preg_match('~<section class="aha-section aha-services" id="additional-services">([\s\S]*?)</section>~', $body, $section_match)) {
            return array();
        }

        $section = $section_match[0];
        if (!preg_match_all('~<article class="aha-service-card">([\s\S]*?)</article>~', $section, $cards)) {
            return array();
        }

        $rows = array();
        foreach ($cards[1] as $card) {
            $title = '';
            $desc = '';
            $info_url = '';
            $image_url = '';
            $image_alt = '';

            if (preg_match('~<img[^>]+src="([^"]+)"[^>]*alt="([^"]*)"~', $card, $m)) {
                $image_url = esc_url_raw($m[1]);
                $image_alt = sanitize_text_field($m[2]);
            }
            if (preg_match('~<h3>([\s\S]*?)</h3>~', $card, $m)) {
                $title = sanitize_text_field(wp_strip_all_tags($m[1]));
            }
            if (preg_match('~<p class="aha-service-desc">([\s\S]*?)</p>~', $card, $m)) {
                $desc = sanitize_textarea_field(html_entity_decode(trim(wp_strip_all_tags($m[1])), ENT_QUOTES, 'UTF-8'));
            }
            if (preg_match('~<a class="aha-service-book" href="([^"]+)"~', $card, $m)) {
                $info_url = esc_url_raw($m[1]);
            }

            if ($title === '' || $info_url === '' || $image_url === '') {
                continue;
            }

            $rows[] = array(
                'title' => $title,
                'desc' => $desc,
                'info_url' => $info_url,
                'image_url' => $image_url,
                'image_alt' => $image_alt,
                'show_price_list' => strpos($card, 'aha-price-list-btn') !== false,
            );
        }

        return $rows;
    }

    private function parse_gallery_defaults_from_template() {
        $parts = $this->get_template_parts();
        $body = $parts['body'];
        if ($body === '') {
            return array();
        }

        if (!preg_match('~<section class="aha-section aha-gallery" id="gallery">([\s\S]*?)</section>~', $body, $section_match)) {
            return array();
        }

        $section = $section_match[0];
        if (!preg_match_all('~<article class="aha-gallery-item"><img src="([^"]+)" alt="([^"]*)"~', $section, $matches, PREG_SET_ORDER)) {
            return array();
        }

        $rows = array();
        foreach ($matches as $m) {
            $image_url = esc_url_raw($m[1]);
            if ($image_url === '') {
                continue;
            }
            $rows[] = array(
                'image_url' => $image_url,
                'image_alt' => sanitize_text_field($m[2]),
            );
        }

        return $rows;
    }

    public function render_admin_home() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
          <h1>Nendhy All Shortcode Manager</h1>
          <p>Pakai shortcode berikut untuk render landing page penuh:</p>
          <p><code>[nendhy_all]</code></p>
          <p>Kelola konten dinamis di menu:</p>
          <ul>
            <li><a href="<?php echo esc_url(admin_url('admin.php?page=nendhy-tour-activity')); ?>">Tour Activity</a></li>
            <li><a href="<?php echo esc_url(admin_url('admin.php?page=nendhy-additional-services')); ?>">Additional Services</a></li>
            <li><a href="<?php echo esc_url(admin_url('admin.php?page=nendhy-gallery')); ?>">Gallery</a></li>
          </ul>
        </div>
        <?php
    }

    public function render_admin_tour() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rows = $this->get_tour_items();
        ?>
        <div class="wrap">
          <h1>Tour Activity</h1>
          <?php if (isset($_GET['updated']) && $_GET['updated'] === '1') : ?>
            <div class="notice notice-success is-dismissible"><p>Tour activity berhasil diupdate.</p></div>
          <?php endif; ?>
          <form method="post">
            <?php wp_nonce_field('nendhy_save_data', 'nendhy_nonce'); ?>
            <input type="hidden" name="nendhy_section_type" value="tour">
            <table class="widefat striped" id="nendhy-tour-table">
              <thead>
                <tr>
                  <th style="width:90px;">Preview</th>
                  <th>Title</th>
                  <th>Description</th>
                  <th>List (1 line = 1 item)</th>
                  <th>Book URL</th>
                  <th>Image URL</th>
                  <th>Slider Images URL (opsional, 1 line = 1 image)</th>
                  <th style="width:120px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $row) : ?>
                <tr>
                  <td><img src="<?php echo esc_url($row['image_url']); ?>" style="width:72px;height:54px;object-fit:cover;border:1px solid #ddd;border-radius:6px;"></td>
                  <td><input type="text" name="title[]" value="<?php echo esc_attr($row['title']); ?>" style="width:100%;"></td>
                  <td><textarea name="desc[]" rows="3" style="width:100%;"><?php echo esc_textarea($row['desc']); ?></textarea></td>
                  <td><textarea name="list[]" rows="4" style="width:100%;"><?php echo esc_textarea(implode("\n", (array) $row['list'])); ?></textarea></td>
                  <td><input type="url" name="book_url[]" value="<?php echo esc_attr($row['book_url']); ?>" style="width:100%;"></td>
                  <td>
                    <input type="url" name="image_url[]" value="<?php echo esc_attr($row['image_url']); ?>" style="width:100%;" class="nendhy-image-url">
                    <input type="text" name="image_alt[]" value="<?php echo esc_attr($row['image_alt']); ?>" style="width:100%;margin-top:6px;" placeholder="Image alt">
                    <button type="button" class="button nendhy-upload" style="margin-top:6px;">Upload</button>
                  </td>
                  <td><textarea name="slider_images[]" rows="4" style="width:100%;" placeholder="https://...\nhttps://..."><?php echo esc_textarea(implode("\n", (array) $row['slider_images'])); ?></textarea></td>
                  <td><button type="button" class="button-link-delete nendhy-remove-row">Hapus</button></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <p><button type="button" class="button" id="nendhy-add-tour">+ Add Tour Item</button></p>
            <p><button type="submit" class="button button-primary">Simpan</button></p>
          </form>
        </div>

        <template id="nendhy-tour-row-template">
          <tr>
            <td><img src="" style="width:72px;height:54px;object-fit:cover;border:1px solid #ddd;border-radius:6px;display:none;"></td>
            <td><input type="text" name="title[]" value="" style="width:100%;"></td>
            <td><textarea name="desc[]" rows="3" style="width:100%;"></textarea></td>
            <td><textarea name="list[]" rows="4" style="width:100%;"></textarea></td>
            <td><input type="url" name="book_url[]" value="" style="width:100%;"></td>
            <td>
              <input type="url" name="image_url[]" value="" style="width:100%;" class="nendhy-image-url">
              <input type="text" name="image_alt[]" value="" style="width:100%;margin-top:6px;" placeholder="Image alt">
              <button type="button" class="button nendhy-upload" style="margin-top:6px;">Upload</button>
            </td>
            <td><textarea name="slider_images[]" rows="4" style="width:100%;" placeholder="https://...\nhttps://..."></textarea></td>
            <td><button type="button" class="button-link-delete nendhy-remove-row">Hapus</button></td>
          </tr>
        </template>

        <script>
          (function(){
            const table = document.querySelector('#nendhy-tour-table tbody');
            const addBtn = document.getElementById('nendhy-add-tour');
            const tpl = document.getElementById('nendhy-tour-row-template');
            if (!table || !addBtn || !tpl) return;

            function bindRow(row){
              const removeBtn = row.querySelector('.nendhy-remove-row');
              const uploadBtn = row.querySelector('.nendhy-upload');
              const urlInput = row.querySelector('.nendhy-image-url');
              const preview = row.querySelector('img');

              if (removeBtn) {
                removeBtn.addEventListener('click', function(){ row.remove(); });
              }

              if (urlInput) {
                urlInput.addEventListener('input', function(){
                  const v = this.value.trim();
                  if (preview && v) {
                    preview.src = v;
                    preview.style.display = '';
                  } else if (preview) {
                    preview.removeAttribute('src');
                    preview.style.display = 'none';
                  }
                });
              }

              if (uploadBtn) {
                uploadBtn.addEventListener('click', function(){
                  const frame = wp.media({ title: 'Select image', button: { text: 'Use image' }, multiple: false });
                  frame.on('select', function(){
                    const a = frame.state().get('selection').first().toJSON();
                    if (!a || !a.url) return;
                    if (urlInput) urlInput.value = a.url;
                    if (preview) {
                      preview.src = a.url;
                      preview.style.display = '';
                    }
                  });
                  frame.open();
                });
              }
            }

            addBtn.addEventListener('click', function(){
              const row = tpl.content.firstElementChild.cloneNode(true);
              table.appendChild(row);
              bindRow(row);
            });

            Array.from(table.querySelectorAll('tr')).forEach(bindRow);
          })();
        </script>
        <?php
    }

    public function render_admin_services() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rows = $this->get_service_items();
        ?>
        <div class="wrap">
          <h1>Additional Services</h1>
          <?php if (isset($_GET['updated']) && $_GET['updated'] === '1') : ?>
            <div class="notice notice-success is-dismissible"><p>Additional services berhasil diupdate.</p></div>
          <?php endif; ?>
          <form method="post">
            <?php wp_nonce_field('nendhy_save_data', 'nendhy_nonce'); ?>
            <input type="hidden" name="nendhy_section_type" value="services">
            <table class="widefat striped" id="nendhy-service-table">
              <thead>
                <tr>
                  <th style="width:90px;">Preview</th>
                  <th>Title</th>
                  <th>Description</th>
                  <th>More Info URL</th>
                  <th>Image URL</th>
                  <th>Price List Button</th>
                  <th style="width:120px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $i => $row) : ?>
                <tr>
                  <td><img src="<?php echo esc_url($row['image_url']); ?>" style="width:72px;height:54px;object-fit:cover;border:1px solid #ddd;border-radius:6px;"></td>
                  <td><input type="text" name="title[]" value="<?php echo esc_attr($row['title']); ?>" style="width:100%;"></td>
                  <td><textarea name="desc[]" rows="3" style="width:100%;"><?php echo esc_textarea($row['desc']); ?></textarea></td>
                  <td><input type="url" name="info_url[]" value="<?php echo esc_attr($row['info_url']); ?>" style="width:100%;"></td>
                  <td>
                    <input type="url" name="image_url[]" value="<?php echo esc_attr($row['image_url']); ?>" style="width:100%;" class="nendhy-image-url">
                    <input type="text" name="image_alt[]" value="<?php echo esc_attr($row['image_alt']); ?>" style="width:100%;margin-top:6px;" placeholder="Image alt">
                    <button type="button" class="button nendhy-upload" style="margin-top:6px;">Upload</button>
                  </td>
                  <td style="text-align:center;">
                    <label>
                      <input type="checkbox" name="show_price_list[<?php echo esc_attr((string) $i); ?>]" value="1" <?php checked(!empty($row['show_price_list'])); ?>> Show
                    </label>
                  </td>
                  <td><button type="button" class="button-link-delete nendhy-remove-row">Hapus</button></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <p><button type="button" class="button" id="nendhy-add-service">+ Add Service Item</button></p>
            <p><button type="submit" class="button button-primary">Simpan</button></p>
          </form>
        </div>

        <template id="nendhy-service-row-template">
          <tr>
            <td><img src="" style="width:72px;height:54px;object-fit:cover;border:1px solid #ddd;border-radius:6px;display:none;"></td>
            <td><input type="text" name="title[]" value="" style="width:100%;"></td>
            <td><textarea name="desc[]" rows="3" style="width:100%;"></textarea></td>
            <td><input type="url" name="info_url[]" value="" style="width:100%;"></td>
            <td>
              <input type="url" name="image_url[]" value="" style="width:100%;" class="nendhy-image-url">
              <input type="text" name="image_alt[]" value="" style="width:100%;margin-top:6px;" placeholder="Image alt">
              <button type="button" class="button nendhy-upload" style="margin-top:6px;">Upload</button>
            </td>
            <td style="text-align:center;"><label><input type="checkbox" data-price-list="1"> Show</label></td>
            <td><button type="button" class="button-link-delete nendhy-remove-row">Hapus</button></td>
          </tr>
        </template>

        <script>
          (function(){
            const table = document.querySelector('#nendhy-service-table tbody');
            const addBtn = document.getElementById('nendhy-add-service');
            const tpl = document.getElementById('nendhy-service-row-template');
            if (!table || !addBtn || !tpl) return;

            function reindexCheckboxes(){
              Array.from(table.querySelectorAll('tr')).forEach((row, idx) => {
                const cb = row.querySelector('input[data-price-list="1"], input[type="checkbox"][name^="show_price_list"]');
                if (cb) {
                  cb.setAttribute('name', `show_price_list[${idx}]`);
                  cb.setAttribute('data-price-list', '1');
                }
              });
            }

            function bindRow(row){
              const removeBtn = row.querySelector('.nendhy-remove-row');
              const uploadBtn = row.querySelector('.nendhy-upload');
              const urlInput = row.querySelector('.nendhy-image-url');
              const preview = row.querySelector('img');

              if (removeBtn) {
                removeBtn.addEventListener('click', function(){
                  row.remove();
                  reindexCheckboxes();
                });
              }

              if (urlInput) {
                urlInput.addEventListener('input', function(){
                  const v = this.value.trim();
                  if (preview && v) {
                    preview.src = v;
                    preview.style.display = '';
                  } else if (preview) {
                    preview.removeAttribute('src');
                    preview.style.display = 'none';
                  }
                });
              }

              if (uploadBtn) {
                uploadBtn.addEventListener('click', function(){
                  const frame = wp.media({ title: 'Select image', button: { text: 'Use image' }, multiple: false });
                  frame.on('select', function(){
                    const a = frame.state().get('selection').first().toJSON();
                    if (!a || !a.url) return;
                    if (urlInput) urlInput.value = a.url;
                    if (preview) {
                      preview.src = a.url;
                      preview.style.display = '';
                    }
                  });
                  frame.open();
                });
              }
            }

            addBtn.addEventListener('click', function(){
              const row = tpl.content.firstElementChild.cloneNode(true);
              table.appendChild(row);
              bindRow(row);
              reindexCheckboxes();
            });

            Array.from(table.querySelectorAll('tr')).forEach(bindRow);
            reindexCheckboxes();
          })();
        </script>
        <?php
    }

    public function render_admin_gallery() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rows = $this->get_gallery_items();
        ?>
        <div class="wrap">
          <h1>Gallery</h1>
          <?php if (isset($_GET['updated']) && $_GET['updated'] === '1') : ?>
            <div class="notice notice-success is-dismissible"><p>Gallery berhasil diupdate.</p></div>
          <?php endif; ?>
          <form method="post">
            <?php wp_nonce_field('nendhy_save_data', 'nendhy_nonce'); ?>
            <input type="hidden" name="nendhy_section_type" value="gallery">
            <table class="widefat striped" id="nendhy-gallery-table">
              <thead>
                <tr>
                  <th style="width:90px;">Preview</th>
                  <th>Image URL</th>
                  <th>Image Alt</th>
                  <th style="width:120px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $row) : ?>
                <tr>
                  <td><img src="<?php echo esc_url($row['image_url']); ?>" style="width:72px;height:54px;object-fit:cover;border:1px solid #ddd;border-radius:6px;"></td>
                  <td>
                    <input type="url" name="image_url[]" value="<?php echo esc_attr($row['image_url']); ?>" style="width:100%;" class="nendhy-image-url">
                    <button type="button" class="button nendhy-upload" style="margin-top:6px;">Upload</button>
                  </td>
                  <td><input type="text" name="image_alt[]" value="<?php echo esc_attr($row['image_alt']); ?>" style="width:100%;"></td>
                  <td><button type="button" class="button-link-delete nendhy-remove-row">Hapus</button></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <p><button type="button" class="button" id="nendhy-add-gallery">+ Add Gallery Item</button></p>
            <p><button type="submit" class="button button-primary">Simpan</button></p>
          </form>
        </div>

        <template id="nendhy-gallery-row-template">
          <tr>
            <td><img src="" style="width:72px;height:54px;object-fit:cover;border:1px solid #ddd;border-radius:6px;display:none;"></td>
            <td>
              <input type="url" name="image_url[]" value="" style="width:100%;" class="nendhy-image-url">
              <button type="button" class="button nendhy-upload" style="margin-top:6px;">Upload</button>
            </td>
            <td><input type="text" name="image_alt[]" value="" style="width:100%;"></td>
            <td><button type="button" class="button-link-delete nendhy-remove-row">Hapus</button></td>
          </tr>
        </template>

        <script>
          (function(){
            const table = document.querySelector('#nendhy-gallery-table tbody');
            const addBtn = document.getElementById('nendhy-add-gallery');
            const tpl = document.getElementById('nendhy-gallery-row-template');
            if (!table || !addBtn || !tpl) return;

            function bindRow(row){
              const removeBtn = row.querySelector('.nendhy-remove-row');
              const uploadBtn = row.querySelector('.nendhy-upload');
              const urlInput = row.querySelector('.nendhy-image-url');
              const preview = row.querySelector('img');

              if (removeBtn) {
                removeBtn.addEventListener('click', function(){ row.remove(); });
              }

              if (urlInput) {
                urlInput.addEventListener('input', function(){
                  const v = this.value.trim();
                  if (preview && v) {
                    preview.src = v;
                    preview.style.display = '';
                  } else if (preview) {
                    preview.removeAttribute('src');
                    preview.style.display = 'none';
                  }
                });
              }

              if (uploadBtn) {
                uploadBtn.addEventListener('click', function(){
                  const frame = wp.media({ title: 'Select image', button: { text: 'Use image' }, multiple: false });
                  frame.on('select', function(){
                    const a = frame.state().get('selection').first().toJSON();
                    if (!a || !a.url) return;
                    if (urlInput) urlInput.value = a.url;
                    if (preview) {
                      preview.src = a.url;
                      preview.style.display = '';
                    }
                  });
                  frame.open();
                });
              }
            }

            addBtn.addEventListener('click', function(){
              const row = tpl.content.firstElementChild.cloneNode(true);
              table.appendChild(row);
              bindRow(row);
            });

            Array.from(table.querySelectorAll('tr')).forEach(bindRow);
          })();
        </script>
        <?php
    }
}

new Nendhy_All_Shortcode_Manager();
