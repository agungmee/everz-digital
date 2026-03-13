<?php
/**
 * Plugin Name: Bali Wedding Packages Manager
 * Description: Kelola kategori dan paket wedding, lalu render menu + card paket via shortcode.
 * Version: 1.0.0
 * Author: Codex
 */

if (!defined('ABSPATH')) {
    exit;
}

class Bali_Wedding_Packages_Manager {
    const OPTION_CATEGORIES = 'bwp_categories';
    const OPTION_PACKAGES = 'bwp_packages';

    public function __construct() {
        add_action('admin_menu', array($this, 'register_admin_menu'));
        add_action('admin_init', array($this, 'handle_admin_submit'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_shortcode('bali_wedding_packages', array($this, 'render_shortcode'));
    }

    public function register_admin_menu() {
        add_menu_page(
            'Bali Wedding Packages',
            'Wedding Packages',
            'manage_options',
            'bwp-manager',
            array($this, 'render_admin_page'),
            'dashicons-heart',
            60
        );
    }

    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'bwp-manager') === false) {
            return;
        }
        wp_enqueue_media();
    }

    public function handle_admin_submit() {
        if (!current_user_can('manage_options') || !isset($_POST['bwp_action'])) {
            return;
        }

        $action = sanitize_key(wp_unslash($_POST['bwp_action']));

        if ($action === 'save_categories') {
            $this->handle_save_categories();
        }

        if ($action === 'save_packages') {
            $this->handle_save_packages();
        }

        if ($action === 'sync_pages') {
            $this->handle_sync_pages();
        }
    }

    private function handle_save_categories() {
        check_admin_referer('bwp_save_categories', 'bwp_categories_nonce');

        $raw = array(
            'category_name' => isset($_POST['category_name']) ? wp_unslash($_POST['category_name']) : array(),
            'category_slug' => isset($_POST['category_slug']) ? wp_unslash($_POST['category_slug']) : array(),
            'category_desc' => isset($_POST['category_desc']) ? wp_unslash($_POST['category_desc']) : array(),
            'category_page_id' => isset($_POST['category_page_id']) ? wp_unslash($_POST['category_page_id']) : array(),
        );

        $categories = $this->sanitize_categories($raw);
        update_option(self::OPTION_CATEGORIES, $categories);

        $this->redirect_admin(array('updated' => '1', 'saved' => 'categories'));
    }

    private function handle_save_packages() {
        check_admin_referer('bwp_save_packages', 'bwp_packages_nonce');

        $raw = array(
            'package_category' => isset($_POST['package_category']) ? wp_unslash($_POST['package_category']) : array(),
            'package_name' => isset($_POST['package_name']) ? wp_unslash($_POST['package_name']) : array(),
            'package_venue' => isset($_POST['package_venue']) ? wp_unslash($_POST['package_venue']) : array(),
            'package_image_url' => isset($_POST['package_image_url']) ? wp_unslash($_POST['package_image_url']) : array(),
            'package_gallery' => isset($_POST['package_gallery']) ? wp_unslash($_POST['package_gallery']) : array(),
            'price_label_1' => isset($_POST['price_label_1']) ? wp_unslash($_POST['price_label_1']) : array(),
            'price_amount_1' => isset($_POST['price_amount_1']) ? wp_unslash($_POST['price_amount_1']) : array(),
            'price_label_2' => isset($_POST['price_label_2']) ? wp_unslash($_POST['price_label_2']) : array(),
            'price_amount_2' => isset($_POST['price_amount_2']) ? wp_unslash($_POST['price_amount_2']) : array(),
            'package_inclusions' => isset($_POST['package_inclusions']) ? wp_unslash($_POST['package_inclusions']) : array(),
            'package_bonuses' => isset($_POST['package_bonuses']) ? wp_unslash($_POST['package_bonuses']) : array(),
            'package_cta_label' => isset($_POST['package_cta_label']) ? wp_unslash($_POST['package_cta_label']) : array(),
            'package_cta_url' => isset($_POST['package_cta_url']) ? wp_unslash($_POST['package_cta_url']) : array(),
        );

        $categories = $this->get_categories();
        $packages = $this->sanitize_packages($raw, $categories);
        update_option(self::OPTION_PACKAGES, $packages);

        $this->redirect_admin(array('updated' => '1', 'saved' => 'packages'));
    }

    private function handle_sync_pages() {
        check_admin_referer('bwp_sync_pages', 'bwp_sync_nonce');

        $categories = $this->get_categories();
        $updated_count = 0;

        foreach ($categories as $index => $category) {
            $title = sprintf('%s Wedding Packages', $category['name']);
            $content = sprintf('[bali_wedding_packages category="%s"]', $category['slug']);

            $postarr = array(
                'post_title' => $title,
                'post_name' => 'wedding-packages-' . $category['slug'],
                'post_content' => $content,
                'post_status' => 'publish',
                'post_type' => 'page',
            );

            $page_id = isset($category['page_id']) ? absint($category['page_id']) : 0;
            if ($page_id > 0 && get_post($page_id)) {
                $postarr['ID'] = $page_id;
                $result_id = wp_update_post($postarr, true);
            } else {
                $result_id = wp_insert_post($postarr, true);
            }

            if (!is_wp_error($result_id) && $result_id > 0) {
                $categories[$index]['page_id'] = (int) $result_id;
                $updated_count++;
            }
        }

        update_option(self::OPTION_CATEGORIES, $categories);
        $this->redirect_admin(array('updated' => '1', 'saved' => 'sync', 'count' => $updated_count));
    }

    private function sanitize_categories($raw) {
        $names = isset($raw['category_name']) && is_array($raw['category_name']) ? $raw['category_name'] : array();
        $slugs = isset($raw['category_slug']) && is_array($raw['category_slug']) ? $raw['category_slug'] : array();
        $descs = isset($raw['category_desc']) && is_array($raw['category_desc']) ? $raw['category_desc'] : array();
        $page_ids = isset($raw['category_page_id']) && is_array($raw['category_page_id']) ? $raw['category_page_id'] : array();

        $count = max(count($names), count($slugs), count($descs), count($page_ids));
        $rows = array();
        $used_slugs = array();

        for ($i = 0; $i < $count; $i++) {
            $name = isset($names[$i]) ? sanitize_text_field($names[$i]) : '';
            $input_slug = isset($slugs[$i]) ? sanitize_title($slugs[$i]) : '';
            $desc = isset($descs[$i]) ? sanitize_textarea_field($descs[$i]) : '';
            $page_id = isset($page_ids[$i]) ? absint($page_ids[$i]) : 0;

            if ($name === '') {
                continue;
            }

            $slug = $input_slug !== '' ? $input_slug : sanitize_title($name);
            if ($slug === '') {
                continue;
            }

            $slug = $this->make_unique_slug($slug, $used_slugs);
            $used_slugs[] = $slug;

            $rows[] = array(
                'name' => $name,
                'slug' => $slug,
                'description' => $desc,
                'page_id' => $page_id,
            );
        }

        return $rows;
    }

    private function sanitize_packages($raw, $categories) {
        $category_map = array();
        foreach ($categories as $category) {
            $category_map[$category['slug']] = true;
        }

        $cat = isset($raw['package_category']) && is_array($raw['package_category']) ? $raw['package_category'] : array();
        $name = isset($raw['package_name']) && is_array($raw['package_name']) ? $raw['package_name'] : array();
        $venue = isset($raw['package_venue']) && is_array($raw['package_venue']) ? $raw['package_venue'] : array();
        $image = isset($raw['package_image_url']) && is_array($raw['package_image_url']) ? $raw['package_image_url'] : array();
        $gallery = isset($raw['package_gallery']) && is_array($raw['package_gallery']) ? $raw['package_gallery'] : array();
        $label1 = isset($raw['price_label_1']) && is_array($raw['price_label_1']) ? $raw['price_label_1'] : array();
        $amount1 = isset($raw['price_amount_1']) && is_array($raw['price_amount_1']) ? $raw['price_amount_1'] : array();
        $label2 = isset($raw['price_label_2']) && is_array($raw['price_label_2']) ? $raw['price_label_2'] : array();
        $amount2 = isset($raw['price_amount_2']) && is_array($raw['price_amount_2']) ? $raw['price_amount_2'] : array();
        $inclusions = isset($raw['package_inclusions']) && is_array($raw['package_inclusions']) ? $raw['package_inclusions'] : array();
        $bonuses = isset($raw['package_bonuses']) && is_array($raw['package_bonuses']) ? $raw['package_bonuses'] : array();
        $cta_label = isset($raw['package_cta_label']) && is_array($raw['package_cta_label']) ? $raw['package_cta_label'] : array();
        $cta_url = isset($raw['package_cta_url']) && is_array($raw['package_cta_url']) ? $raw['package_cta_url'] : array();

        $count = max(
            count($cat),
            count($name),
            count($venue),
            count($image),
            count($gallery),
            count($label1),
            count($amount1),
            count($label2),
            count($amount2),
            count($inclusions),
            count($bonuses),
            count($cta_label),
            count($cta_url)
        );

        $rows = array();

        for ($i = 0; $i < $count; $i++) {
            $category_slug = isset($cat[$i]) ? sanitize_title($cat[$i]) : '';
            $package_name = isset($name[$i]) ? sanitize_text_field($name[$i]) : '';
            $package_venue = isset($venue[$i]) ? sanitize_text_field($venue[$i]) : '';
            $package_image = isset($image[$i]) ? esc_url_raw(trim((string) $image[$i])) : '';
            $package_gallery_raw = isset($gallery[$i]) ? (string) $gallery[$i] : '';
            $package_inclusions = isset($inclusions[$i]) ? (string) $inclusions[$i] : '';
            $package_bonuses = isset($bonuses[$i]) ? (string) $bonuses[$i] : '';
            $package_cta_label = isset($cta_label[$i]) ? sanitize_text_field($cta_label[$i]) : '';
            $package_cta_url = isset($cta_url[$i]) ? esc_url_raw(trim((string) $cta_url[$i])) : '';

            if ($package_name === '' || $category_slug === '' || !isset($category_map[$category_slug])) {
                continue;
            }

            $prices = array();
            $p1_label = isset($label1[$i]) ? sanitize_text_field($label1[$i]) : '';
            $p1_amount = isset($amount1[$i]) ? sanitize_text_field($amount1[$i]) : '';
            $p2_label = isset($label2[$i]) ? sanitize_text_field($label2[$i]) : '';
            $p2_amount = isset($amount2[$i]) ? sanitize_text_field($amount2[$i]) : '';

            if ($p1_label !== '' || $p1_amount !== '') {
                $prices[] = array('label' => $p1_label, 'amount' => $p1_amount);
            }
            if ($p2_label !== '' || $p2_amount !== '') {
                $prices[] = array('label' => $p2_label, 'amount' => $p2_amount);
            }

            $gallery_urls = array();
            foreach (preg_split('/\r\n|\r|\n/', $package_gallery_raw) as $line) {
                $line = esc_url_raw(trim($line));
                if ($line !== '') {
                    $gallery_urls[] = $line;
                }
            }

            $rows[] = array(
                'category_slug' => $category_slug,
                'name' => $package_name,
                'venue' => $package_venue,
                'image_url' => $package_image,
                'gallery_urls' => $gallery_urls,
                'prices' => $prices,
                'inclusions' => $this->split_lines($package_inclusions),
                'bonuses' => $this->split_lines($package_bonuses),
                'cta_label' => $package_cta_label,
                'cta_url' => $package_cta_url,
            );
        }

        return $rows;
    }

    private function split_lines($text) {
        $rows = array();
        foreach (preg_split('/\r\n|\r|\n/', (string) $text) as $line) {
            $line = sanitize_text_field(trim($line));
            if ($line !== '') {
                $rows[] = $line;
            }
        }
        return $rows;
    }

    private function make_unique_slug($slug, $used_slugs) {
        if (!in_array($slug, $used_slugs, true)) {
            return $slug;
        }

        $suffix = 2;
        while (in_array($slug . '-' . $suffix, $used_slugs, true)) {
            $suffix++;
        }

        return $slug . '-' . $suffix;
    }

    private function redirect_admin($args = array()) {
        $url = add_query_arg(
            array_merge(
                array(
                    'page' => 'bwp-manager',
                ),
                $args
            ),
            admin_url('admin.php')
        );

        wp_safe_redirect($url);
        exit;
    }

    private function get_categories() {
        $saved = get_option(self::OPTION_CATEGORIES, array());
        return is_array($saved) ? $saved : array();
    }

    private function get_packages() {
        $saved = get_option(self::OPTION_PACKAGES, array());
        return is_array($saved) ? $saved : array();
    }

    public function render_admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $categories = $this->get_categories();
        $packages = $this->get_packages();

        if (empty($categories)) {
            $categories = array(
                array(
                    'name' => 'Paket Wedding Terbaik',
                    'slug' => 'paket-wedding-terbaik',
                    'description' => 'Kategori paket unggulan.',
                    'page_id' => 0,
                ),
            );
        }

        if (empty($packages)) {
            $first_slug = isset($categories[0]['slug']) ? $categories[0]['slug'] : '';
            $packages = array(
                array(
                    'category_slug' => $first_slug,
                    'name' => 'Wedding Package 2027',
                    'venue' => 'Mercure Kuta',
                    'image_url' => '',
                    'gallery_urls' => array(),
                    'prices' => array(
                        array('label' => '50 Pax', 'amount' => '121 Juta'),
                        array('label' => '100 Pax', 'amount' => '149 Juta'),
                    ),
                    'inclusions' => array('WO Service', 'Make Up & Hair Do', 'Dekor Ceremony & Resepsi', 'Foto & Video'),
                    'bonuses' => array('Free Pyrotech', 'Free Konten Kreator', 'Free Undangan Online'),
                    'cta_label' => 'Konsultasi Paket',
                    'cta_url' => '',
                ),
            );
        }

        ?>
        <div class="wrap bwp-wrap">
          <h1>Bali Wedding Packages Manager</h1>
          <p>Input kategori dan paket. Lalu tampilkan di halaman dengan shortcode: <code>[bali_wedding_packages]</code></p>

          <?php if (isset($_GET['updated']) && $_GET['updated'] === '1') : ?>
            <div class="notice notice-success is-dismissible">
              <p>
                <?php
                $saved = isset($_GET['saved']) ? sanitize_text_field(wp_unslash($_GET['saved'])) : '';
                if ($saved === 'categories') {
                    echo esc_html('Kategori berhasil disimpan.');
                } elseif ($saved === 'packages') {
                    echo esc_html('Paket berhasil disimpan.');
                } elseif ($saved === 'sync') {
                    $count = isset($_GET['count']) ? absint($_GET['count']) : 0;
                    echo esc_html('Halaman kategori tersinkronisasi: ' . $count . ' halaman.');
                } else {
                    echo esc_html('Data berhasil disimpan.');
                }
                ?>
              </p>
            </div>
          <?php endif; ?>

          <div class="bwp-admin-grid">
            <section class="bwp-panel">
              <h2>Kategori (jadi menu + halaman)</h2>
              <form method="post" action="">
                <?php wp_nonce_field('bwp_save_categories', 'bwp_categories_nonce'); ?>
                <input type="hidden" name="bwp_action" value="save_categories">

                <table class="widefat striped" id="bwp-category-table">
                  <thead>
                    <tr>
                      <th>Nama Kategori</th>
                      <th>Slug</th>
                      <th>Deskripsi Singkat</th>
                      <th>Page ID</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($categories as $row) : ?>
                      <tr>
                        <td><input type="text" name="category_name[]" value="<?php echo esc_attr($row['name']); ?>" class="regular-text" required></td>
                        <td><input type="text" name="category_slug[]" value="<?php echo esc_attr($row['slug']); ?>" class="regular-text"></td>
                        <td><input type="text" name="category_desc[]" value="<?php echo esc_attr($row['description']); ?>" class="regular-text"></td>
                        <td>
                          <input type="hidden" name="category_page_id[]" value="<?php echo isset($row['page_id']) ? absint($row['page_id']) : 0; ?>">
                          <?php echo isset($row['page_id']) && absint($row['page_id']) > 0 ? esc_html((string) absint($row['page_id'])) : '-'; ?>
                        </td>
                        <td><button type="button" class="button-link-delete bwp-remove-row">Hapus</button></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>

                <p class="bwp-row-gap">
                  <button type="button" class="button" id="bwp-add-category">+ Tambah Kategori</button>
                  <button type="submit" class="button button-primary">Simpan Kategori</button>
                </p>
              </form>

              <form method="post" action="" class="bwp-sync-form">
                <?php wp_nonce_field('bwp_sync_pages', 'bwp_sync_nonce'); ?>
                <input type="hidden" name="bwp_action" value="sync_pages">
                <button type="submit" class="button button-secondary">Sync Halaman Kategori Otomatis</button>
                <p class="description">Plugin akan create/update page per kategori dengan shortcode kategori masing-masing.</p>
              </form>
            </section>

            <section class="bwp-panel">
              <h2>Paket (tampil sebagai card)</h2>
              <form method="post" action="" id="bwp-package-form">
                <?php wp_nonce_field('bwp_save_packages', 'bwp_packages_nonce'); ?>
                <input type="hidden" name="bwp_action" value="save_packages">

                <div id="bwp-package-list">
                  <?php foreach ($packages as $pkg) :
                    $price1 = isset($pkg['prices'][0]) ? $pkg['prices'][0] : array('label' => '', 'amount' => '');
                    $price2 = isset($pkg['prices'][1]) ? $pkg['prices'][1] : array('label' => '', 'amount' => '');
                    ?>
                    <div class="bwp-package-item">
                      <div class="bwp-package-head">
                        <strong><?php echo esc_html($pkg['name']); ?></strong>
                        <button type="button" class="button-link-delete bwp-remove-package">Hapus</button>
                      </div>

                      <div class="bwp-grid-2">
                        <p>
                          <label>Kategori</label>
                          <select name="package_category[]" required>
                            <option value="">Pilih kategori</option>
                            <?php foreach ($categories as $category) : ?>
                              <option value="<?php echo esc_attr($category['slug']); ?>" <?php selected($pkg['category_slug'], $category['slug']); ?>>
                                <?php echo esc_html($category['name']); ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </p>
                        <p>
                          <label>Nama Paket</label>
                          <input type="text" name="package_name[]" value="<?php echo esc_attr($pkg['name']); ?>" required>
                        </p>
                      </div>

                      <div class="bwp-grid-2">
                        <p>
                          <label>Venue</label>
                          <input type="text" name="package_venue[]" value="<?php echo esc_attr($pkg['venue']); ?>">
                        </p>
                        <p>
                          <label>URL Gambar Cover</label>
                          <span class="bwp-media-input-wrap">
                            <input type="url" name="package_image_url[]" value="<?php echo esc_attr($pkg['image_url']); ?>" placeholder="https://...">
                            <button type="button" class="button bwp-upload-image">Upload</button>
                          </span>
                        </p>
                      </div>

                      <div class="bwp-grid-2">
                        <p>
                          <label>Harga 1 Label (contoh: 50 Pax)</label>
                          <input type="text" name="price_label_1[]" value="<?php echo esc_attr(isset($price1['label']) ? $price1['label'] : ''); ?>">
                        </p>
                        <p>
                          <label>Harga 1 Nominal (contoh: 121 Juta)</label>
                          <input type="text" name="price_amount_1[]" value="<?php echo esc_attr(isset($price1['amount']) ? $price1['amount'] : ''); ?>">
                        </p>
                      </div>

                      <div class="bwp-grid-2">
                        <p>
                          <label>Harga 2 Label (opsional)</label>
                          <input type="text" name="price_label_2[]" value="<?php echo esc_attr(isset($price2['label']) ? $price2['label'] : ''); ?>">
                        </p>
                        <p>
                          <label>Harga 2 Nominal (opsional)</label>
                          <input type="text" name="price_amount_2[]" value="<?php echo esc_attr(isset($price2['amount']) ? $price2['amount'] : ''); ?>">
                        </p>
                      </div>

                      <div class="bwp-grid-2">
                        <p>
                          <label>Inclusion (1 baris = 1 item)</label>
                          <textarea name="package_inclusions[]" rows="6"><?php echo esc_textarea(implode("\n", isset($pkg['inclusions']) ? $pkg['inclusions'] : array())); ?></textarea>
                        </p>
                        <p>
                          <label>Bonus / Free (1 baris = 1 item)</label>
                          <textarea name="package_bonuses[]" rows="6"><?php echo esc_textarea(implode("\n", isset($pkg['bonuses']) ? $pkg['bonuses'] : array())); ?></textarea>
                        </p>
                      </div>

                      <p>
                        <label>Gallery URL (opsional, 1 baris = 1 URL)</label>
                        <textarea name="package_gallery[]" rows="3" placeholder="https://...&#10;https://..."><?php echo esc_textarea(implode("\n", isset($pkg['gallery_urls']) ? $pkg['gallery_urls'] : array())); ?></textarea>
                      </p>

                      <div class="bwp-grid-2">
                        <p>
                          <label>Teks Tombol CTA</label>
                          <input type="text" name="package_cta_label[]" value="<?php echo esc_attr(isset($pkg['cta_label']) ? $pkg['cta_label'] : 'Konsultasi'); ?>">
                        </p>
                        <p>
                          <label>Link Tombol CTA</label>
                          <input type="url" name="package_cta_url[]" value="<?php echo esc_attr(isset($pkg['cta_url']) ? $pkg['cta_url'] : ''); ?>" placeholder="https://wa.me/...">
                        </p>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>

                <p class="bwp-row-gap">
                  <button type="button" class="button" id="bwp-add-package">+ Tambah Paket</button>
                  <button type="submit" class="button button-primary">Simpan Paket</button>
                </p>
              </form>
            </section>
          </div>
        </div>

        <template id="bwp-category-template">
          <tr>
            <td><input type="text" name="category_name[]" value="" class="regular-text" required></td>
            <td><input type="text" name="category_slug[]" value="" class="regular-text"></td>
            <td><input type="text" name="category_desc[]" value="" class="regular-text"></td>
            <td>
              <input type="hidden" name="category_page_id[]" value="0">
              -
            </td>
            <td><button type="button" class="button-link-delete bwp-remove-row">Hapus</button></td>
          </tr>
        </template>

        <template id="bwp-package-template">
          <div class="bwp-package-item">
            <div class="bwp-package-head">
              <strong>Paket Baru</strong>
              <button type="button" class="button-link-delete bwp-remove-package">Hapus</button>
            </div>

            <div class="bwp-grid-2">
              <p>
                <label>Kategori</label>
                <select name="package_category[]" required>
                  <option value="">Pilih kategori</option>
                  <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo esc_attr($category['slug']); ?>"><?php echo esc_html($category['name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </p>
              <p>
                <label>Nama Paket</label>
                <input type="text" name="package_name[]" value="" required>
              </p>
            </div>

            <div class="bwp-grid-2">
              <p>
                <label>Venue</label>
                <input type="text" name="package_venue[]" value="">
              </p>
              <p>
                <label>URL Gambar Cover</label>
                <span class="bwp-media-input-wrap">
                  <input type="url" name="package_image_url[]" value="" placeholder="https://...">
                  <button type="button" class="button bwp-upload-image">Upload</button>
                </span>
              </p>
            </div>

            <div class="bwp-grid-2">
              <p>
                <label>Harga 1 Label</label>
                <input type="text" name="price_label_1[]" value="">
              </p>
              <p>
                <label>Harga 1 Nominal</label>
                <input type="text" name="price_amount_1[]" value="">
              </p>
            </div>

            <div class="bwp-grid-2">
              <p>
                <label>Harga 2 Label</label>
                <input type="text" name="price_label_2[]" value="">
              </p>
              <p>
                <label>Harga 2 Nominal</label>
                <input type="text" name="price_amount_2[]" value="">
              </p>
            </div>

            <div class="bwp-grid-2">
              <p>
                <label>Inclusion (1 baris = 1 item)</label>
                <textarea name="package_inclusions[]" rows="6"></textarea>
              </p>
              <p>
                <label>Bonus / Free (1 baris = 1 item)</label>
                <textarea name="package_bonuses[]" rows="6"></textarea>
              </p>
            </div>

            <p>
              <label>Gallery URL (opsional, 1 baris = 1 URL)</label>
              <textarea name="package_gallery[]" rows="3" placeholder="https://...&#10;https://..."></textarea>
            </p>

            <div class="bwp-grid-2">
              <p>
                <label>Teks Tombol CTA</label>
                <input type="text" name="package_cta_label[]" value="Konsultasi">
              </p>
              <p>
                <label>Link Tombol CTA</label>
                <input type="url" name="package_cta_url[]" value="" placeholder="https://wa.me/...">
              </p>
            </div>
          </div>
        </template>

        <style>
          .bwp-admin-grid { display:grid; grid-template-columns:1fr; gap:20px; margin-top:16px; }
          .bwp-panel { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:16px; }
          .bwp-panel h2 { margin-top:0; }
          .bwp-row-gap { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
          .bwp-sync-form { margin-top:16px; padding-top:12px; border-top:1px dashed #ddd; }
          .bwp-package-item { border:1px solid #d9d9d9; border-radius:10px; padding:12px; margin-bottom:14px; background:#fcfcfc; }
          .bwp-package-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; }
          .bwp-grid-2 { display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:12px; }
          .bwp-package-item p { margin:0 0 10px; }
          .bwp-package-item label { display:block; margin-bottom:4px; font-weight:600; }
          .bwp-package-item input, .bwp-package-item textarea, .bwp-package-item select { width:100%; }
          .bwp-media-input-wrap { display:grid; grid-template-columns:minmax(0,1fr) auto; gap:8px; }
          @media (max-width: 900px) { .bwp-grid-2 { grid-template-columns: 1fr; } }
        </style>

        <script>
          (function(){
            const categoryTableBody = document.querySelector('#bwp-category-table tbody');
            const addCategoryBtn = document.getElementById('bwp-add-category');
            const categoryTemplate = document.getElementById('bwp-category-template');
            const packageList = document.getElementById('bwp-package-list');
            const addPackageBtn = document.getElementById('bwp-add-package');
            const packageTemplate = document.getElementById('bwp-package-template');

            if (addCategoryBtn && categoryTableBody && categoryTemplate) {
              addCategoryBtn.addEventListener('click', function() {
                const row = categoryTemplate.content.firstElementChild.cloneNode(true);
                categoryTableBody.appendChild(row);
              });

              categoryTableBody.addEventListener('click', function(event){
                if (!event.target.classList.contains('bwp-remove-row')) return;
                const row = event.target.closest('tr');
                if (row) row.remove();
              });
            }

            if (addPackageBtn && packageList && packageTemplate) {
              addPackageBtn.addEventListener('click', function() {
                const item = packageTemplate.content.firstElementChild.cloneNode(true);
                packageList.appendChild(item);
              });

              packageList.addEventListener('click', function(event){
                if (event.target.classList.contains('bwp-remove-package')) {
                  const item = event.target.closest('.bwp-package-item');
                  if (item) item.remove();
                }

                if (event.target.classList.contains('bwp-upload-image')) {
                  const wrapper = event.target.closest('.bwp-media-input-wrap');
                  const input = wrapper ? wrapper.querySelector('input[name="package_image_url[]"]') : null;
                  if (!input || typeof wp === 'undefined' || !wp.media) {
                    return;
                  }

                  const frame = wp.media({
                    title: 'Pilih gambar cover paket',
                    button: { text: 'Gunakan gambar ini' },
                    multiple: false
                  });

                  frame.on('select', function(){
                    const attachment = frame.state().get('selection').first().toJSON();
                    if (attachment && attachment.url) {
                      input.value = attachment.url;
                    }
                  });

                  frame.open();
                }
              });
            }
          })();
        </script>
        <?php
    }

    public function render_shortcode($atts = array()) {
        $atts = shortcode_atts(
            array(
                'category' => '',
            ),
            $atts,
            'bali_wedding_packages'
        );

        $categories = $this->get_categories();
        $packages = $this->get_packages();

        if (empty($categories)) {
            return '<p>Belum ada kategori paket wedding.</p>';
        }

        $category_slug = sanitize_title($atts['category']);
        if ($category_slug === '' && isset($_GET['wedding_category'])) {
            $category_slug = sanitize_title(wp_unslash($_GET['wedding_category']));
        }

        $category_map = array();
        foreach ($categories as $category) {
            $category_map[$category['slug']] = $category;
        }

        $filtered_categories = $categories;
        if ($category_slug !== '' && isset($category_map[$category_slug])) {
            $filtered_categories = array($category_map[$category_slug]);
        }

        ob_start();
        ?>
        <section class="bwp-front-wrap">
          <nav class="bwp-front-menu" aria-label="Kategori Paket Wedding">
            <?php foreach ($categories as $category) :
                $slug = $category['slug'];
                $is_active = ($category_slug !== '' && $category_slug === $slug);
                $page_id = isset($category['page_id']) ? absint($category['page_id']) : 0;
                $link = '';
                if ($page_id > 0 && get_post_status($page_id)) {
                    $link = get_permalink($page_id);
                }
                if (!$link) {
                    $current_url = get_permalink();
                    $link = $current_url ? add_query_arg('wedding_category', $slug, $current_url) : '#';
                }
                ?>
              <a class="bwp-menu-item <?php echo $is_active ? 'is-active' : ''; ?>" href="<?php echo esc_url($link); ?>"><?php echo esc_html($category['name']); ?></a>
            <?php endforeach; ?>
          </nav>

          <?php foreach ($filtered_categories as $category) :
            $cat_slug = $category['slug'];
            $cat_packages = array_values(array_filter($packages, function($item) use ($cat_slug) {
                return isset($item['category_slug']) && $item['category_slug'] === $cat_slug;
            }));
            ?>
            <div class="bwp-category-block" id="category-<?php echo esc_attr($cat_slug); ?>">
              <h2><?php echo esc_html($category['name']); ?></h2>
              <?php if (!empty($category['description'])) : ?>
                <p class="bwp-category-desc"><?php echo esc_html($category['description']); ?></p>
              <?php endif; ?>

              <?php if (empty($cat_packages)) : ?>
                <p class="bwp-empty">Belum ada paket di kategori ini.</p>
              <?php else : ?>
                <div class="bwp-card-grid">
                  <?php foreach ($cat_packages as $pkg) : ?>
                    <article class="bwp-card">
                      <?php
                      $cover = isset($pkg['image_url']) ? $pkg['image_url'] : '';
                      if ($cover === '' && !empty($pkg['gallery_urls'][0])) {
                          $cover = $pkg['gallery_urls'][0];
                      }
                      ?>
                      <?php if ($cover !== '') : ?>
                        <img class="bwp-card-image" src="<?php echo esc_url($cover); ?>" alt="<?php echo esc_attr($pkg['name']); ?>">
                      <?php endif; ?>

                      <div class="bwp-card-body">
                        <h3><?php echo esc_html($pkg['name']); ?></h3>
                        <?php if (!empty($pkg['venue'])) : ?>
                          <p class="bwp-venue"><?php echo esc_html($pkg['venue']); ?></p>
                        <?php endif; ?>

                        <?php if (!empty($pkg['prices'])) : ?>
                          <div class="bwp-prices">
                            <?php foreach ($pkg['prices'] as $price) : ?>
                              <p>
                                <?php if (!empty($price['amount'])) : ?><strong><?php echo esc_html($price['amount']); ?></strong><?php endif; ?>
                                <?php if (!empty($price['label'])) : ?> <span><?php echo esc_html($price['label']); ?></span><?php endif; ?>
                              </p>
                            <?php endforeach; ?>
                          </div>
                        <?php endif; ?>

                        <?php if (!empty($pkg['inclusions'])) : ?>
                          <div class="bwp-list-wrap">
                            <p class="bwp-subtitle">Inclusion</p>
                            <ul>
                              <?php foreach ($pkg['inclusions'] as $item) : ?>
                                <li><?php echo esc_html($item); ?></li>
                              <?php endforeach; ?>
                            </ul>
                          </div>
                        <?php endif; ?>

                        <?php if (!empty($pkg['bonuses'])) : ?>
                          <div class="bwp-list-wrap">
                            <p class="bwp-subtitle">Bonus</p>
                            <ul>
                              <?php foreach ($pkg['bonuses'] as $item) : ?>
                                <li><?php echo esc_html($item); ?></li>
                              <?php endforeach; ?>
                            </ul>
                          </div>
                        <?php endif; ?>

                        <?php if (!empty($pkg['cta_url'])) : ?>
                          <a class="bwp-cta" href="<?php echo esc_url($pkg['cta_url']); ?>" target="_blank" rel="noopener noreferrer">
                            <?php echo !empty($pkg['cta_label']) ? esc_html($pkg['cta_label']) : 'Konsultasi Sekarang'; ?>
                          </a>
                        <?php endif; ?>
                      </div>
                    </article>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </section>

        <style>
          .bwp-front-wrap { font-family: "Source Sans 3", Arial, sans-serif; }
          .bwp-front-menu { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:22px; }
          .bwp-menu-item { text-decoration:none; color:#9e2062; border:1px solid #f2cade; background:#fff5fa; padding:8px 14px; border-radius:999px; font-weight:700; font-size:13px; }
          .bwp-menu-item.is-active, .bwp-menu-item:hover { background:#d83f89; color:#fff; border-color:#d83f89; }
          .bwp-category-block h2 { margin:0 0 6px; font-size:1.8rem; font-family:"Playfair Display", Georgia, serif; color:#2f2f2f; }
          .bwp-category-desc { margin:0 0 18px; color:#666; }
          .bwp-empty { color:#666; }
          .bwp-card-grid { display:grid; gap:18px; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); }
          .bwp-card { border:1px solid #eed9e3; border-radius:14px; overflow:hidden; background:#fff; box-shadow:0 10px 22px rgba(31,22,34,0.07); }
          .bwp-card-image { width:100%; aspect-ratio:4/3; object-fit:cover; display:block; }
          .bwp-card-body { padding:16px; }
          .bwp-card h3 { margin:0 0 6px; font-size:1.25rem; font-family:"Playfair Display", Georgia, serif; }
          .bwp-venue { margin:0 0 8px; color:#555; font-style:italic; }
          .bwp-prices { border-top:1px solid #eee; border-bottom:1px solid #eee; padding:8px 0; margin:10px 0 12px; }
          .bwp-prices p { margin:0 0 6px; }
          .bwp-prices p:last-child { margin-bottom:0; }
          .bwp-prices strong { font-size:1.6rem; line-height:1.1; color:#191919; }
          .bwp-prices span { margin-left:6px; text-transform:uppercase; letter-spacing:0.08em; font-size:12px; color:#6c6c6c; }
          .bwp-subtitle { margin:0 0 6px; font-weight:700; color:#3d3d3d; }
          .bwp-list-wrap ul { margin:0 0 12px 18px; }
          .bwp-list-wrap li { margin-bottom:4px; }
          .bwp-cta { display:inline-block; text-decoration:none; background:#d83f89; color:#fff; padding:9px 14px; border-radius:999px; font-weight:700; }
          .bwp-cta:hover { background:#000; color:#fff; }
        </style>
        <?php

        return ob_get_clean();
    }
}

new Bali_Wedding_Packages_Manager();
