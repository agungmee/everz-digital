<?php
/**
 * Plugin Name: Raun Sumatra Elementor Packages Gallery CMS
 * Description: CRUD paket dan gallery bilingual (EN/ID) untuk Elementor via shortcode.
 * Version: 1.0.0
 * Author: Raun Sumatra
 */

if (!defined('ABSPATH')) {
    exit;
}

final class RS_Elementor_Packages_Gallery_CMS
{
    private const PAGE_SLUG = 'rs-pg-cms';
    private const NONCE_ACTION = 'rs_pg_cms_action';

    private const OPTION_PACKAGES = 'rs_pg_packages_v1';
    private const OPTION_GALLERY = 'rs_pg_gallery_v1';
    private const OPTION_SECTIONS = 'rs_pg_sections_v1';

    public static function init(): void
    {
        register_activation_hook(__FILE__, [self::class, 'activate']);
        add_action('admin_menu', [self::class, 'register_admin_menu']);
        add_action('admin_init', [self::class, 'handle_admin_post']);

        add_shortcode('rs_paket_section', [self::class, 'shortcode_packages']);
        add_shortcode('rs_gallery_section', [self::class, 'shortcode_gallery']);
        add_shortcode('rs_paket_gallery', [self::class, 'shortcode_both']);
    }

    public static function activate(): void
    {
        if (!is_array(get_option(self::OPTION_PACKAGES))) {
            update_option(self::OPTION_PACKAGES, self::default_packages());
        }
        if (!is_array(get_option(self::OPTION_GALLERY))) {
            update_option(self::OPTION_GALLERY, self::default_gallery());
        }
        if (!is_array(get_option(self::OPTION_SECTIONS))) {
            update_option(self::OPTION_SECTIONS, self::default_sections());
        }
    }

    private static function default_sections(): array
    {
        return [
            'package' => [
                'chip_en' => 'Tour Packages',
                'chip_id' => 'Paket Wisata',
                'title_en' => 'Tour Packages',
                'title_id' => 'Paket Wisata',
                'desc_en' => 'Choose the best package and review the itinerary before booking. All trips are arranged on a private tour basis with local support.',
                'desc_id' => 'Pilih paket terbaik dan pelajari itinerary sebelum booking. Semua perjalanan disusun private tour dengan dukungan tim lokal.',
            ],
            'gallery' => [
                'chip_en' => 'Gallery',
                'chip_id' => 'Galeri',
                'title_en' => 'Scenes from our favorite routes.',
                'title_id' => 'Momen dari rute favorit kami.',
                'desc_en' => 'A quick look at the landscapes, culture, and coastline our guests enjoy across Sumatra.',
                'desc_id' => 'Sekilas lanskap, budaya, dan pesisir yang dinikmati tamu kami di berbagai destinasi Sumatra.',
            ],
        ];
    }

    private static function default_packages(): array
    {
        return [
            [
                'image' => 'http://raunsumatra.com/wp-content/uploads/2026/02/padang-sumatra-indonesia-travel-photo-20240703182607902-main-image.jpg',
                'badge_en' => 'Private Tour',
                'badge_id' => 'Private Tour',
                'name_en' => '5D 4N Explore Alahan Panjang - Bukittinggi - Padang',
                'name_id' => '5H 4M Jelajah Alahan Panjang - Bukittinggi - Padang',
                'sub_en' => '1 nite Alahan Panjang / 2 nites Bukittinggi / 1 nite Padang. Valid until 20 December 2026.',
                'sub_id' => '1 malam Alahan Panjang / 2 malam Bukittinggi / 1 malam Padang. Berlaku hingga 20 Desember 2026.',
                'price_en' => 'From Rp. 4.140.000 / pax',
                'price_id' => 'Mulai Rp. 4.140.000 / pax',
                'btn_detail_en' => 'Tour Details',
                'btn_detail_id' => 'Detail Tur',
                'btn_book_en' => 'Booking Now',
                'btn_book_id' => 'Booking Sekarang',
                'detail_url' => '#',
                'book_url' => 'https://wa.me/628116602898',
            ],
            [
                'image' => 'http://raunsumatra.com/wp-content/uploads/2026/02/jam-gadang_1999979717.jpg',
                'badge_en' => 'Private Tour',
                'badge_id' => 'Private Tour',
                'name_en' => '4D 3N Explore Alahan Panjang - Bukittinggi',
                'name_id' => '4H 3M Jelajah Alahan Panjang - Bukittinggi',
                'sub_en' => '1 nite Alahan / 1 nite Bukittinggi / 1 nite Padang. Valid until 20 December 2026.',
                'sub_id' => '1 malam Alahan / 1 malam Bukittinggi / 1 malam Padang. Berlaku hingga 20 Desember 2026.',
                'price_en' => 'From Rp. 3.375.000 / pax',
                'price_id' => 'Mulai Rp. 3.375.000 / pax',
                'btn_detail_en' => 'Tour Details',
                'btn_detail_id' => 'Detail Tur',
                'btn_book_en' => 'Booking Now',
                'btn_book_id' => 'Booking Sekarang',
                'detail_url' => '#',
                'book_url' => 'https://wa.me/628116602898',
            ],
            [
                'image' => 'https://astradigitaldigiroomuat.blob.core.windows.net/storage-uat-001/tempat-wisata-di-padang.jpg',
                'badge_en' => 'Private Tour',
                'badge_id' => 'Private Tour',
                'name_en' => '4D 3N Explore Padang - Bukittinggi',
                'name_id' => '4H 3M Jelajah Padang - Bukittinggi',
                'sub_en' => '2 nites Bukittinggi / 1 nite Padang. Valid until 20 December 2026.',
                'sub_id' => '2 malam Bukittinggi / 1 malam Padang. Berlaku hingga 20 Desember 2026.',
                'price_en' => 'From Rp. 3.015.000 / pax',
                'price_id' => 'Mulai Rp. 3.015.000 / pax',
                'btn_detail_en' => 'Tour Details',
                'btn_detail_id' => 'Detail Tur',
                'btn_book_en' => 'Booking Now',
                'btn_book_id' => 'Booking Sekarang',
                'detail_url' => '#',
                'book_url' => 'https://wa.me/628116602898',
            ],
            [
                'image' => 'http://raunsumatra.com/wp-content/uploads/2026/02/db9f488ba033c63667e6e056ad55e5c0.jpg',
                'badge_en' => 'Private Tour',
                'badge_id' => 'Private Tour',
                'name_en' => '5D 4N Explore Padang - Bukittinggi',
                'name_id' => '5H 4M Jelajah Padang - Bukittinggi',
                'sub_en' => '3 nites Bukittinggi / 1 nite Padang. Valid until 20 December 2026.',
                'sub_id' => '3 malam Bukittinggi / 1 malam Padang. Berlaku hingga 20 Desember 2026.',
                'price_en' => 'From Rp. 3.895.000 / pax',
                'price_id' => 'Mulai Rp. 3.895.000 / pax',
                'btn_detail_en' => 'Tour Details',
                'btn_detail_id' => 'Detail Tur',
                'btn_book_en' => 'Booking Now',
                'btn_book_id' => 'Booking Sekarang',
                'detail_url' => '#',
                'book_url' => 'https://wa.me/628116602898',
            ],
        ];
    }

    private static function default_gallery(): array
    {
        return [
            [
                'image' => 'http://raunsumatra.com/wp-content/uploads/2026/02/padang-sumatra-indonesia-travel-photo-20240703182607902-main-image.jpg',
                'alt_en' => 'Padang Sumatra landscape',
                'alt_id' => 'Pemandangan Padang Sumatra',
            ],
            [
                'image' => 'http://raunsumatra.com/wp-content/uploads/2026/02/iyehioifp1zy1ve.jpeg',
                'alt_en' => 'Sumatra travel destination view',
                'alt_id' => 'Pemandangan destinasi wisata Sumatra',
            ],
            [
                'image' => 'http://raunsumatra.com/wp-content/uploads/2026/02/jam-gadang_1999979717.jpg',
                'alt_en' => 'Jam Gadang Bukittinggi',
                'alt_id' => 'Jam Gadang Bukittinggi',
            ],
            [
                'image' => 'http://raunsumatra.com/wp-content/uploads/2026/02/5e.jpg',
                'alt_en' => 'Minangkabau village',
                'alt_id' => 'Perkampungan Minangkabau',
            ],
            [
                'image' => 'http://raunsumatra.com/wp-content/uploads/2026/02/sabang-mobile.webp',
                'alt_en' => 'Sabang coastline',
                'alt_id' => 'Pesisir Sabang',
            ],
            [
                'image' => 'http://raunsumatra.com/wp-content/uploads/2026/02/db9f488ba033c63667e6e056ad55e5c0.jpg',
                'alt_en' => 'Pandai Sikek village',
                'alt_id' => 'Desa Pandai Sikek',
            ],
        ];
    }

    public static function register_admin_menu(): void
    {
        add_menu_page(
            'Raun Paket Gallery CMS',
            'Raun Paket/Gallery',
            'manage_options',
            self::PAGE_SLUG,
            [self::class, 'render_admin_page'],
            'dashicons-images-alt2',
            59
        );
    }

    private static function sanitize_url_or_relative(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }
        if (preg_match('/^(#|\/|\?)/', $value)) {
            return sanitize_text_field($value);
        }
        return esc_url_raw($value);
    }

    private static function sanitize_package_item(array $item): array
    {
        return [
            'image' => self::sanitize_url_or_relative((string) ($item['image'] ?? '')),
            'badge_en' => sanitize_text_field((string) ($item['badge_en'] ?? '')),
            'badge_id' => sanitize_text_field((string) ($item['badge_id'] ?? '')),
            'name_en' => sanitize_text_field((string) ($item['name_en'] ?? '')),
            'name_id' => sanitize_text_field((string) ($item['name_id'] ?? '')),
            'sub_en' => sanitize_text_field((string) ($item['sub_en'] ?? '')),
            'sub_id' => sanitize_text_field((string) ($item['sub_id'] ?? '')),
            'price_en' => sanitize_text_field((string) ($item['price_en'] ?? '')),
            'price_id' => sanitize_text_field((string) ($item['price_id'] ?? '')),
            'btn_detail_en' => sanitize_text_field((string) ($item['btn_detail_en'] ?? '')),
            'btn_detail_id' => sanitize_text_field((string) ($item['btn_detail_id'] ?? '')),
            'btn_book_en' => sanitize_text_field((string) ($item['btn_book_en'] ?? '')),
            'btn_book_id' => sanitize_text_field((string) ($item['btn_book_id'] ?? '')),
            'detail_url' => self::sanitize_url_or_relative((string) ($item['detail_url'] ?? '#')),
            'book_url' => self::sanitize_url_or_relative((string) ($item['book_url'] ?? 'https://wa.me/628116602898')),
        ];
    }

    private static function sanitize_gallery_item(array $item): array
    {
        return [
            'image' => self::sanitize_url_or_relative((string) ($item['image'] ?? '')),
            'alt_en' => sanitize_text_field((string) ($item['alt_en'] ?? '')),
            'alt_id' => sanitize_text_field((string) ($item['alt_id'] ?? '')),
        ];
    }

    private static function get_packages(): array
    {
        $data = get_option(self::OPTION_PACKAGES, []);
        if (!is_array($data)) {
            return self::default_packages();
        }
        $clean = [];
        foreach ($data as $item) {
            if (!is_array($item)) {
                continue;
            }
            $san = self::sanitize_package_item($item);
            if ($san['name_en'] === '' && $san['name_id'] === '') {
                continue;
            }
            $clean[] = $san;
        }
        return $clean;
    }

    private static function get_gallery(): array
    {
        $data = get_option(self::OPTION_GALLERY, []);
        if (!is_array($data)) {
            return self::default_gallery();
        }
        $clean = [];
        foreach ($data as $item) {
            if (!is_array($item)) {
                continue;
            }
            $san = self::sanitize_gallery_item($item);
            if ($san['image'] === '') {
                continue;
            }
            $clean[] = $san;
        }
        return $clean;
    }

    private static function get_sections(): array
    {
        $saved = get_option(self::OPTION_SECTIONS, []);
        $default = self::default_sections();
        if (!is_array($saved)) {
            return $default;
        }

        foreach (['package', 'gallery'] as $block) {
            if (!isset($saved[$block]) || !is_array($saved[$block])) {
                $saved[$block] = $default[$block];
                continue;
            }
            foreach ($default[$block] as $key => $fallback) {
                if (!isset($saved[$block][$key])) {
                    $saved[$block][$key] = $fallback;
                }
                $saved[$block][$key] = sanitize_text_field((string) $saved[$block][$key]);
            }
        }

        return $saved;
    }

    public static function handle_admin_post(): void
    {
        if (!is_admin() || !current_user_can('manage_options')) {
            return;
        }
        if (!isset($_POST['rs_pg_action'])) {
            return;
        }

        check_admin_referer(self::NONCE_ACTION, 'rs_pg_nonce');

        $action = sanitize_text_field((string) $_POST['rs_pg_action']);

        try {
            if ($action === 'save_sections') {
                $input = isset($_POST['sections']) && is_array($_POST['sections']) ? $_POST['sections'] : [];
                $default = self::default_sections();
                $clean = $default;

                foreach (['package', 'gallery'] as $block) {
                    foreach ($default[$block] as $key => $fallback) {
                        $clean[$block][$key] = sanitize_text_field((string) ($input[$block][$key] ?? $fallback));
                    }
                }

                update_option(self::OPTION_SECTIONS, $clean);
                self::redirect_admin('Judul/teks section berhasil disimpan.');
            }

            if ($action === 'save_packages') {
                $rows = isset($_POST['packages']) && is_array($_POST['packages']) ? $_POST['packages'] : [];
                $clean = [];
                foreach ($rows as $row) {
                    if (!is_array($row)) {
                        continue;
                    }
                    $item = self::sanitize_package_item($row);
                    if ($item['name_en'] === '' && $item['name_id'] === '') {
                        continue;
                    }
                    $clean[] = $item;
                }
                update_option(self::OPTION_PACKAGES, $clean);
                self::redirect_admin('Data paket berhasil disimpan.');
            }

            if ($action === 'update_package') {
                $index = isset($_POST['index']) ? (int) $_POST['index'] : -1;
                $row = isset($_POST['package']) && is_array($_POST['package']) ? $_POST['package'] : [];
                $item = self::sanitize_package_item($row);
                $data = self::get_packages();
                if (!isset($data[$index])) {
                    throw new RuntimeException('Item paket tidak ditemukan.');
                }
                $data[$index] = $item;
                update_option(self::OPTION_PACKAGES, array_values($data));
                self::redirect_admin('Paket berhasil diupdate.');
            }

            if ($action === 'add_package') {
                $row = isset($_POST['package_new']) && is_array($_POST['package_new']) ? $_POST['package_new'] : [];
                if ($row) {
                    $item = self::sanitize_package_item($row);
                    if ($item['name_en'] === '' && $item['name_id'] === '') {
                        throw new RuntimeException('Nama paket EN/ID tidak boleh kosong.');
                    }
                    $data = self::get_packages();
                    $data[] = $item;
                    update_option(self::OPTION_PACKAGES, array_values($data));
                    self::redirect_admin('Item paket baru ditambahkan.');
                }

                $data = self::get_packages();
                $data[] = [
                    'image' => '',
                    'badge_en' => 'Private Tour',
                    'badge_id' => 'Private Tour',
                    'name_en' => 'New Package',
                    'name_id' => 'Paket Baru',
                    'sub_en' => '',
                    'sub_id' => '',
                    'price_en' => '',
                    'price_id' => '',
                    'btn_detail_en' => 'Tour Details',
                    'btn_detail_id' => 'Detail Tur',
                    'btn_book_en' => 'Booking Now',
                    'btn_book_id' => 'Booking Sekarang',
                    'detail_url' => '#',
                    'book_url' => 'https://wa.me/628116602898',
                ];
                update_option(self::OPTION_PACKAGES, $data);
                self::redirect_admin('Item paket baru ditambahkan.');
            }

            if ($action === 'delete_package') {
                $index = isset($_POST['index']) ? (int) $_POST['index'] : -1;
                $data = self::get_packages();
                if (isset($data[$index])) {
                    array_splice($data, $index, 1);
                }
                update_option(self::OPTION_PACKAGES, array_values($data));
                self::redirect_admin('Item paket dihapus.');
            }

            if ($action === 'move_package') {
                $index = isset($_POST['index']) ? (int) $_POST['index'] : -1;
                $direction = sanitize_text_field((string) ($_POST['direction'] ?? 'up'));
                $target = $direction === 'down' ? $index + 1 : $index - 1;
                $data = self::get_packages();
                if (isset($data[$index], $data[$target])) {
                    $tmp = $data[$index];
                    $data[$index] = $data[$target];
                    $data[$target] = $tmp;
                }
                update_option(self::OPTION_PACKAGES, array_values($data));
                self::redirect_admin('Urutan paket diperbarui.');
            }

            if ($action === 'save_gallery') {
                $rows = isset($_POST['gallery']) && is_array($_POST['gallery']) ? $_POST['gallery'] : [];
                $clean = [];
                foreach ($rows as $row) {
                    if (!is_array($row)) {
                        continue;
                    }
                    $item = self::sanitize_gallery_item($row);
                    if ($item['image'] === '') {
                        continue;
                    }
                    $clean[] = $item;
                }
                update_option(self::OPTION_GALLERY, $clean);
                self::redirect_admin('Data gallery berhasil disimpan.');
            }

            if ($action === 'update_gallery') {
                $index = isset($_POST['index']) ? (int) $_POST['index'] : -1;
                $row = isset($_POST['gallery_item']) && is_array($_POST['gallery_item']) ? $_POST['gallery_item'] : [];
                $item = self::sanitize_gallery_item($row);
                $data = self::get_gallery();
                if (!isset($data[$index])) {
                    throw new RuntimeException('Item gallery tidak ditemukan.');
                }
                $data[$index] = $item;
                update_option(self::OPTION_GALLERY, array_values($data));
                self::redirect_admin('Gallery berhasil diupdate.');
            }

            if ($action === 'add_gallery') {
                $row = isset($_POST['gallery_new']) && is_array($_POST['gallery_new']) ? $_POST['gallery_new'] : [];
                if ($row) {
                    $item = self::sanitize_gallery_item($row);
                    if ($item['image'] === '') {
                        throw new RuntimeException('Image URL gallery tidak boleh kosong.');
                    }
                    $data = self::get_gallery();
                    $data[] = $item;
                    update_option(self::OPTION_GALLERY, array_values($data));
                    self::redirect_admin('Item gallery baru ditambahkan.');
                }

                $data = self::get_gallery();
                $data[] = [
                    'image' => '',
                    'alt_en' => 'New gallery image',
                    'alt_id' => 'Gambar galeri baru',
                ];
                update_option(self::OPTION_GALLERY, $data);
                self::redirect_admin('Item gallery baru ditambahkan.');
            }

            if ($action === 'delete_gallery') {
                $index = isset($_POST['index']) ? (int) $_POST['index'] : -1;
                $data = self::get_gallery();
                if (isset($data[$index])) {
                    array_splice($data, $index, 1);
                }
                update_option(self::OPTION_GALLERY, array_values($data));
                self::redirect_admin('Item gallery dihapus.');
            }

            if ($action === 'move_gallery') {
                $index = isset($_POST['index']) ? (int) $_POST['index'] : -1;
                $direction = sanitize_text_field((string) ($_POST['direction'] ?? 'up'));
                $target = $direction === 'down' ? $index + 1 : $index - 1;
                $data = self::get_gallery();
                if (isset($data[$index], $data[$target])) {
                    $tmp = $data[$index];
                    $data[$index] = $data[$target];
                    $data[$target] = $tmp;
                }
                update_option(self::OPTION_GALLERY, array_values($data));
                self::redirect_admin('Urutan gallery diperbarui.');
            }

            if ($action === 'reset_defaults') {
                update_option(self::OPTION_PACKAGES, self::default_packages());
                update_option(self::OPTION_GALLERY, self::default_gallery());
                update_option(self::OPTION_SECTIONS, self::default_sections());
                self::redirect_admin('Data dikembalikan ke default.');
            }

            self::redirect_admin('Aksi tidak dikenali.', true);
        } catch (Throwable $e) {
            self::redirect_admin($e->getMessage(), true);
        }
    }

    private static function redirect_admin(string $message, bool $isError = false): void
    {
        $url = add_query_arg(
            [
                'page' => self::PAGE_SLUG,
                'msg' => rawurlencode($message),
                'type' => $isError ? 'error' : 'success',
            ],
            admin_url('admin.php')
        );
        wp_safe_redirect($url);
        exit;
    }

    public static function render_admin_page(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $sections = self::get_sections();
        $packages = self::get_packages();
        $gallery = self::get_gallery();

        $msg = isset($_GET['msg']) ? rawurldecode((string) $_GET['msg']) : '';
        $type = isset($_GET['type']) && $_GET['type'] === 'error' ? 'error' : 'success';

        ?>
        <div class="wrap">
            <h1>Raun Sumatra Paket & Gallery CMS</h1>
            <p>Editor dibuat model visual card agar lebih nyaman: preview gambar + tombol Update/Delete per item.</p>
            <p><strong>Shortcode:</strong> <code>[rs_paket_section]</code>, <code>[rs_gallery_section]</code>, <code>[rs_paket_gallery]</code>.</p>

            <style>
                .rs-admin-grid{display:grid;gap:16px;max-width:1240px;}
                .rs-admin-box{background:#fff;border:1px solid #dcdcde;border-radius:12px;padding:16px;}
                .rs-admin-card{border:1px solid rgba(15,31,23,.12);border-radius:16px;padding:14px;background:#fff;margin-bottom:14px;box-shadow:0 8px 18px rgba(15,31,23,.08);}
                .rs-admin-two{display:grid;grid-template-columns:340px 1fr;gap:14px;align-items:start;}
                .rs-admin-preview{border-radius:14px;overflow:hidden;border:1px solid rgba(15,31,23,.12);background:#f4efe7;}
                .rs-admin-preview img{width:100%;height:220px;object-fit:cover;display:block;}
                .rs-admin-label{display:block;font-weight:600;margin:0 0 4px;}
                .rs-admin-fields{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
                .rs-admin-actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px;}
                .rs-admin-chip{display:inline-flex;padding:6px 10px;border-radius:999px;background:rgba(31,109,77,.12);color:#1f6d4d;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;}
                .rs-admin-title{font-family:Georgia,serif;font-size:28px;margin:4px 0 8px;}
                .rs-admin-sub{color:#2a3a31;font-size:13px;line-height:1.5;margin:0 0 8px;}
                .rs-admin-price{color:#c55b3c;font-weight:700;margin-bottom:10px;}
                .rs-admin-btn{display:inline-flex;padding:7px 12px;border-radius:999px;border:1px solid rgba(15,31,23,.18);font-size:12px;font-weight:700;background:#fff;}
                .rs-admin-btn.primary{background:#1f6d4d;color:#fff;border-color:#1f6d4d;}
                @media(max-width:920px){.rs-admin-two{grid-template-columns:1fr;}.rs-admin-fields{grid-template-columns:1fr;}}
            </style>

            <?php if ($msg !== ''): ?>
                <div class="notice notice-<?php echo esc_attr($type === 'error' ? 'error' : 'success'); ?> is-dismissible"><p><?php echo esc_html($msg); ?></p></div>
            <?php endif; ?>

            <form method="post" style="margin-bottom:16px;">
                <?php wp_nonce_field(self::NONCE_ACTION, 'rs_pg_nonce'); ?>
                <input type="hidden" name="rs_pg_action" value="reset_defaults">
                <button type="submit" class="button">Reset ke Default</button>
            </form>

            <div class="rs-admin-grid">
                <form method="post" class="rs-admin-box">
                    <?php wp_nonce_field(self::NONCE_ACTION, 'rs_pg_nonce'); ?>
                    <input type="hidden" name="rs_pg_action" value="save_sections">
                    <h2 style="margin-top:0;">Section Copy (EN/ID)</h2>
                    <table class="widefat striped">
                        <thead><tr><th>Field</th><th>English (EN)</th><th>Indonesia (ID)</th></tr></thead>
                        <tbody>
                            <tr><td><strong>Paket Chip</strong></td><td><input type="text" name="sections[package][chip_en]" value="<?php echo esc_attr($sections['package']['chip_en']); ?>" style="width:100%;"></td><td><input type="text" name="sections[package][chip_id]" value="<?php echo esc_attr($sections['package']['chip_id']); ?>" style="width:100%;"></td></tr>
                            <tr><td><strong>Paket Title</strong></td><td><input type="text" name="sections[package][title_en]" value="<?php echo esc_attr($sections['package']['title_en']); ?>" style="width:100%;"></td><td><input type="text" name="sections[package][title_id]" value="<?php echo esc_attr($sections['package']['title_id']); ?>" style="width:100%;"></td></tr>
                            <tr><td><strong>Paket Description</strong></td><td><textarea name="sections[package][desc_en]" rows="3" style="width:100%;"><?php echo esc_textarea($sections['package']['desc_en']); ?></textarea></td><td><textarea name="sections[package][desc_id]" rows="3" style="width:100%;"><?php echo esc_textarea($sections['package']['desc_id']); ?></textarea></td></tr>
                            <tr><td><strong>Gallery Chip</strong></td><td><input type="text" name="sections[gallery][chip_en]" value="<?php echo esc_attr($sections['gallery']['chip_en']); ?>" style="width:100%;"></td><td><input type="text" name="sections[gallery][chip_id]" value="<?php echo esc_attr($sections['gallery']['chip_id']); ?>" style="width:100%;"></td></tr>
                            <tr><td><strong>Gallery Title</strong></td><td><input type="text" name="sections[gallery][title_en]" value="<?php echo esc_attr($sections['gallery']['title_en']); ?>" style="width:100%;"></td><td><input type="text" name="sections[gallery][title_id]" value="<?php echo esc_attr($sections['gallery']['title_id']); ?>" style="width:100%;"></td></tr>
                            <tr><td><strong>Gallery Description</strong></td><td><textarea name="sections[gallery][desc_en]" rows="3" style="width:100%;"><?php echo esc_textarea($sections['gallery']['desc_en']); ?></textarea></td><td><textarea name="sections[gallery][desc_id]" rows="3" style="width:100%;"><?php echo esc_textarea($sections['gallery']['desc_id']); ?></textarea></td></tr>
                        </tbody>
                    </table>
                    <p><button type="submit" class="button button-primary">Simpan Section Copy</button></p>
                </form>

                <div class="rs-admin-box">
                    <h2 style="margin-top:0;">Paket (Preview + Edit)</h2>
                    <?php foreach ($packages as $i => $item): ?>
                        <div class="rs-admin-card">
                            <form method="post">
                                <?php wp_nonce_field(self::NONCE_ACTION, 'rs_pg_nonce'); ?>
                                <input type="hidden" name="rs_pg_action" value="update_package">
                                <input type="hidden" name="index" value="<?php echo esc_attr((string) $i); ?>">
                                <div class="rs-admin-two">
                                    <div>
                                        <div class="rs-admin-preview">
                                            <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['name_en']); ?>">
                                        </div>
                                        <div style="padding:10px 2px 0;">
                                            <span class="rs-admin-chip"><?php echo esc_html($item['badge_en']); ?></span>
                                            <div class="rs-admin-title"><?php echo esc_html($item['name_en']); ?></div>
                                            <p class="rs-admin-sub"><?php echo esc_html($item['sub_en']); ?></p>
                                            <div class="rs-admin-price"><?php echo esc_html($item['price_en']); ?></div>
                                            <span class="rs-admin-btn"><?php echo esc_html($item['btn_detail_en']); ?></span>
                                            <span class="rs-admin-btn primary"><?php echo esc_html($item['btn_book_en']); ?></span>
                                        </div>
                                    </div>
                                    <div class="rs-admin-fields">
                                        <p><label class="rs-admin-label">Image URL</label><input type="text" name="package[image]" value="<?php echo esc_attr($item['image']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Detail URL</label><input type="text" name="package[detail_url]" value="<?php echo esc_attr($item['detail_url']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Booking URL</label><input type="text" name="package[book_url]" value="<?php echo esc_attr($item['book_url']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Badge EN</label><input type="text" name="package[badge_en]" value="<?php echo esc_attr($item['badge_en']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Badge ID</label><input type="text" name="package[badge_id]" value="<?php echo esc_attr($item['badge_id']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Name EN</label><input type="text" name="package[name_en]" value="<?php echo esc_attr($item['name_en']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Name ID</label><input type="text" name="package[name_id]" value="<?php echo esc_attr($item['name_id']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Subtitle EN</label><textarea name="package[sub_en]" rows="3" style="width:100%;"><?php echo esc_textarea($item['sub_en']); ?></textarea></p>
                                        <p><label class="rs-admin-label">Subtitle ID</label><textarea name="package[sub_id]" rows="3" style="width:100%;"><?php echo esc_textarea($item['sub_id']); ?></textarea></p>
                                        <p><label class="rs-admin-label">Price EN</label><input type="text" name="package[price_en]" value="<?php echo esc_attr($item['price_en']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Price ID</label><input type="text" name="package[price_id]" value="<?php echo esc_attr($item['price_id']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Detail Button EN</label><input type="text" name="package[btn_detail_en]" value="<?php echo esc_attr($item['btn_detail_en']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Detail Button ID</label><input type="text" name="package[btn_detail_id]" value="<?php echo esc_attr($item['btn_detail_id']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Book Button EN</label><input type="text" name="package[btn_book_en]" value="<?php echo esc_attr($item['btn_book_en']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Book Button ID</label><input type="text" name="package[btn_book_id]" value="<?php echo esc_attr($item['btn_book_id']); ?>" style="width:100%;"></p>
                                    </div>
                                </div>
                                <div class="rs-admin-actions">
                                    <button type="submit" class="button button-primary">Update Paket</button>
                                </div>
                            </form>
                            <div class="rs-admin-actions">
                                <form method="post"><?php wp_nonce_field(self::NONCE_ACTION, 'rs_pg_nonce'); ?><input type="hidden" name="rs_pg_action" value="move_package"><input type="hidden" name="index" value="<?php echo esc_attr((string) $i); ?>"><input type="hidden" name="direction" value="up"><button type="submit" class="button" <?php disabled($i === 0); ?>>Naik</button></form>
                                <form method="post"><?php wp_nonce_field(self::NONCE_ACTION, 'rs_pg_nonce'); ?><input type="hidden" name="rs_pg_action" value="move_package"><input type="hidden" name="index" value="<?php echo esc_attr((string) $i); ?>"><input type="hidden" name="direction" value="down"><button type="submit" class="button" <?php disabled($i === count($packages) - 1); ?>>Turun</button></form>
                                <form method="post" onsubmit="return confirm('Hapus item paket ini?');"><?php wp_nonce_field(self::NONCE_ACTION, 'rs_pg_nonce'); ?><input type="hidden" name="rs_pg_action" value="delete_package"><input type="hidden" name="index" value="<?php echo esc_attr((string) $i); ?>"><button type="submit" class="button button-link-delete">Delete</button></form>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="rs-admin-card" style="border-style:dashed;">
                        <h3 style="margin-top:0;">Tambah Paket Baru</h3>
                        <form method="post">
                            <?php wp_nonce_field(self::NONCE_ACTION, 'rs_pg_nonce'); ?>
                            <input type="hidden" name="rs_pg_action" value="add_package">
                            <div class="rs-admin-fields">
                                <p><label class="rs-admin-label">Image URL</label><input type="text" name="package_new[image]" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Detail URL</label><input type="text" name="package_new[detail_url]" value="#" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Booking URL</label><input type="text" name="package_new[book_url]" value="https://wa.me/628116602898" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Badge EN</label><input type="text" name="package_new[badge_en]" value="Private Tour" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Badge ID</label><input type="text" name="package_new[badge_id]" value="Private Tour" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Name EN</label><input type="text" name="package_new[name_en]" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Name ID</label><input type="text" name="package_new[name_id]" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Subtitle EN</label><textarea name="package_new[sub_en]" rows="3" style="width:100%;"></textarea></p>
                                <p><label class="rs-admin-label">Subtitle ID</label><textarea name="package_new[sub_id]" rows="3" style="width:100%;"></textarea></p>
                                <p><label class="rs-admin-label">Price EN</label><input type="text" name="package_new[price_en]" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Price ID</label><input type="text" name="package_new[price_id]" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Detail Button EN</label><input type="text" name="package_new[btn_detail_en]" value="Tour Details" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Detail Button ID</label><input type="text" name="package_new[btn_detail_id]" value="Detail Tur" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Book Button EN</label><input type="text" name="package_new[btn_book_en]" value="Booking Now" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Book Button ID</label><input type="text" name="package_new[btn_book_id]" value="Booking Sekarang" style="width:100%;"></p>
                            </div>
                            <p><button type="submit" class="button button-primary">Tambah Paket</button></p>
                        </form>
                    </div>
                </div>

                <div class="rs-admin-box">
                    <h2 style="margin-top:0;">Gallery (Preview + Edit)</h2>
                    <?php foreach ($gallery as $i => $item): ?>
                        <div class="rs-admin-card">
                            <form method="post">
                                <?php wp_nonce_field(self::NONCE_ACTION, 'rs_pg_nonce'); ?>
                                <input type="hidden" name="rs_pg_action" value="update_gallery">
                                <input type="hidden" name="index" value="<?php echo esc_attr((string) $i); ?>">
                                <div class="rs-admin-two">
                                    <div>
                                        <div class="rs-admin-preview"><img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['alt_en']); ?>"></div>
                                    </div>
                                    <div class="rs-admin-fields">
                                        <p><label class="rs-admin-label">Image URL</label><input type="text" name="gallery_item[image]" value="<?php echo esc_attr($item['image']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Alt EN</label><input type="text" name="gallery_item[alt_en]" value="<?php echo esc_attr($item['alt_en']); ?>" style="width:100%;"></p>
                                        <p><label class="rs-admin-label">Alt ID</label><input type="text" name="gallery_item[alt_id]" value="<?php echo esc_attr($item['alt_id']); ?>" style="width:100%;"></p>
                                    </div>
                                </div>
                                <div class="rs-admin-actions"><button type="submit" class="button button-primary">Update Gallery</button></div>
                            </form>
                            <div class="rs-admin-actions">
                                <form method="post"><?php wp_nonce_field(self::NONCE_ACTION, 'rs_pg_nonce'); ?><input type="hidden" name="rs_pg_action" value="move_gallery"><input type="hidden" name="index" value="<?php echo esc_attr((string) $i); ?>"><input type="hidden" name="direction" value="up"><button type="submit" class="button" <?php disabled($i === 0); ?>>Naik</button></form>
                                <form method="post"><?php wp_nonce_field(self::NONCE_ACTION, 'rs_pg_nonce'); ?><input type="hidden" name="rs_pg_action" value="move_gallery"><input type="hidden" name="index" value="<?php echo esc_attr((string) $i); ?>"><input type="hidden" name="direction" value="down"><button type="submit" class="button" <?php disabled($i === count($gallery) - 1); ?>>Turun</button></form>
                                <form method="post" onsubmit="return confirm('Hapus item gallery ini?');"><?php wp_nonce_field(self::NONCE_ACTION, 'rs_pg_nonce'); ?><input type="hidden" name="rs_pg_action" value="delete_gallery"><input type="hidden" name="index" value="<?php echo esc_attr((string) $i); ?>"><button type="submit" class="button button-link-delete">Delete</button></form>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="rs-admin-card" style="border-style:dashed;">
                        <h3 style="margin-top:0;">Tambah Gallery Baru</h3>
                        <form method="post">
                            <?php wp_nonce_field(self::NONCE_ACTION, 'rs_pg_nonce'); ?>
                            <input type="hidden" name="rs_pg_action" value="add_gallery">
                            <div class="rs-admin-fields">
                                <p><label class="rs-admin-label">Image URL</label><input type="text" name="gallery_new[image]" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Alt EN</label><input type="text" name="gallery_new[alt_en]" value="New gallery image" style="width:100%;"></p>
                                <p><label class="rs-admin-label">Alt ID</label><input type="text" name="gallery_new[alt_id]" value="Gambar galeri baru" style="width:100%;"></p>
                            </div>
                            <p><button type="submit" class="button button-primary">Tambah Gallery</button></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private static function render_styles_once(): string
    {
        static $printed = false;
        if ($printed) {
            return '';
        }
        $printed = true;

        return '<style>
        .rs-cms-section{width:100vw!important;margin-left:calc(50% - 50vw)!important;margin-right:calc(50% - 50vw)!important;padding:90px 0;font-family:"Sora",sans-serif;color:#0f1f17;background:#fff;}
        .rs-cms-container{max-width:1200px;margin:0 auto;padding:0 18px;}
        .rs-cms-head{display:grid;grid-template-columns:1.05fr .95fr;gap:32px;align-items:end;margin-bottom:26px;text-align:center;justify-items:center;}
        .rs-cms-chip{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;background:rgba(31,109,77,.12);color:#1f6d4d;font-weight:700;font-size:12px;letter-spacing:.12em;text-transform:uppercase;margin-bottom:14px;}
        .rs-cms-title{font-family:"DM Serif Display",serif;font-weight:400;font-size:clamp(30px,3.4vw,46px);line-height:1.12;margin:0 0 10px;}
        .rs-cms-desc{margin:0;color:#2a3a31;line-height:1.8;font-size:16px;max-width:640px;}
        .rs-package-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;justify-items:center;}
        .rs-package-card{display:flex;flex-direction:column;border-radius:22px;overflow:hidden;background:#fff;border:1px solid rgba(15,31,23,.08);box-shadow:0 20px 44px rgba(15,31,23,.12);text-align:center;width:100%;}
        .rs-package-media{position:relative;min-height:200px;aspect-ratio:16/9;}
        .rs-package-media img{width:100%;height:100%;object-fit:cover;display:block;}
        .rs-package-badge{position:absolute;top:16px;left:16px;padding:8px 12px;border-radius:999px;background:rgba(255,255,255,.92);border:1px solid rgba(15,31,23,.12);font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;}
        .rs-package-body{padding:18px;display:flex;flex-direction:column;gap:10px;flex:1;}
        .rs-package-name{margin:0;font-size:18px;font-weight:700;color:#0f1f17;}
        .rs-package-sub{margin:0;font-size:13px;color:#2a3a31;line-height:1.6;}
        .rs-package-price{font-size:16px;font-weight:700;color:#c55b3c;}
        .rs-package-actions{margin-top:auto;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;}
        .rs-package-btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 14px;border-radius:999px;font-size:12px;font-weight:700;text-decoration:none;letter-spacing:.04em;border:1px solid transparent;}
        .rs-package-btn.primary{background:#1f6d4d;color:#fff;}
        .rs-package-btn.ghost{background:#fff;color:#0f1f17;border-color:rgba(15,31,23,.16);}
        .rs-gallery-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;justify-items:center;}
        .rs-gallery-card{position:relative;border-radius:22px;overflow:hidden;background:#f4efe7;border:1px solid rgba(15,31,23,.08);box-shadow:0 18px 40px rgba(15,31,23,.12);width:100%;}
        .rs-gallery-card img{width:100%;height:100%;display:block;object-fit:cover;aspect-ratio:4/3;}
        @media(max-width:980px){.rs-cms-head{grid-template-columns:1fr;}.rs-package-grid,.rs-gallery-grid{grid-template-columns:repeat(2,minmax(0,1fr));}}
        @media(max-width:640px){.rs-cms-section{padding:30px 0!important;}.rs-package-grid,.rs-gallery-grid{grid-template-columns:1fr;}}
        </style>';
    }

    private static function render_lang_script_once(): string
    {
        static $printed = false;
        if ($printed) {
            return '';
        }
        $printed = true;

        return '<script>(function(){function lang(){return window.__rsLang||localStorage.getItem("rsLang")||((navigator.language||"").toLowerCase().indexOf("id")===0?"id":"en");}function run(){var l=lang();document.querySelectorAll("[data-rs-t-en]").forEach(function(el){var txt=l==="id"?el.getAttribute("data-rs-t-id"):el.getAttribute("data-rs-t-en");if(typeof txt==="string"){el.textContent=txt;}});document.querySelectorAll("[data-rs-alt-en]").forEach(function(el){var alt=l==="id"?el.getAttribute("data-rs-alt-id"):el.getAttribute("data-rs-alt-en");if(typeof alt==="string"){el.setAttribute("alt",alt);}});}if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",run);}else{run();}window.addEventListener("load",run);document.querySelectorAll("[data-rs-lang]").forEach(function(btn){btn.addEventListener("click",function(){setTimeout(run,0);});});})();</script>';
    }

    public static function shortcode_packages(array $atts = []): string
    {
        $sections = self::get_sections();
        $packages = self::get_packages();
        if (!$packages) {
            return '';
        }

        ob_start();
        echo self::render_styles_once();
        ?>
        <section class="rs-cms-section rs-package" id="paket" aria-label="Tour Packages">
            <div class="rs-cms-container">
                <div class="rs-cms-head">
                    <div>
                        <div class="rs-cms-chip" data-rs-t-en="<?php echo esc_attr($sections['package']['chip_en']); ?>" data-rs-t-id="<?php echo esc_attr($sections['package']['chip_id']); ?>"><?php echo esc_html($sections['package']['chip_en']); ?></div>
                        <h2 class="rs-cms-title" data-rs-t-en="<?php echo esc_attr($sections['package']['title_en']); ?>" data-rs-t-id="<?php echo esc_attr($sections['package']['title_id']); ?>"><?php echo esc_html($sections['package']['title_en']); ?></h2>
                    </div>
                    <p class="rs-cms-desc" data-rs-t-en="<?php echo esc_attr($sections['package']['desc_en']); ?>" data-rs-t-id="<?php echo esc_attr($sections['package']['desc_id']); ?>"><?php echo esc_html($sections['package']['desc_en']); ?></p>
                </div>

                <div class="rs-package-grid">
                    <?php foreach ($packages as $item): ?>
                        <article class="rs-package-card">
                            <div class="rs-package-media">
                                <span class="rs-package-badge" data-rs-t-en="<?php echo esc_attr($item['badge_en']); ?>" data-rs-t-id="<?php echo esc_attr($item['badge_id']); ?>"><?php echo esc_html($item['badge_en']); ?></span>
                                <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['name_en']); ?>">
                            </div>
                            <div class="rs-package-body">
                                <h3 class="rs-package-name" data-rs-t-en="<?php echo esc_attr($item['name_en']); ?>" data-rs-t-id="<?php echo esc_attr($item['name_id']); ?>"><?php echo esc_html($item['name_en']); ?></h3>
                                <p class="rs-package-sub" data-rs-t-en="<?php echo esc_attr($item['sub_en']); ?>" data-rs-t-id="<?php echo esc_attr($item['sub_id']); ?>"><?php echo esc_html($item['sub_en']); ?></p>
                                <div class="rs-package-price" data-rs-t-en="<?php echo esc_attr($item['price_en']); ?>" data-rs-t-id="<?php echo esc_attr($item['price_id']); ?>"><?php echo esc_html($item['price_en']); ?></div>
                                <div class="rs-package-actions">
                                    <a class="rs-package-btn ghost" href="<?php echo esc_url($item['detail_url']); ?>" data-rs-t-en="<?php echo esc_attr($item['btn_detail_en']); ?>" data-rs-t-id="<?php echo esc_attr($item['btn_detail_id']); ?>"><?php echo esc_html($item['btn_detail_en']); ?></a>
                                    <a class="rs-package-btn primary" href="<?php echo esc_url($item['book_url']); ?>" target="_blank" rel="noopener noreferrer" data-rs-t-en="<?php echo esc_attr($item['btn_book_en']); ?>" data-rs-t-id="<?php echo esc_attr($item['btn_book_id']); ?>"><?php echo esc_html($item['btn_book_en']); ?></a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php

        echo self::render_lang_script_once();
        return (string) ob_get_clean();
    }

    public static function shortcode_gallery(array $atts = []): string
    {
        $sections = self::get_sections();
        $gallery = self::get_gallery();
        if (!$gallery) {
            return '';
        }

        ob_start();
        echo self::render_styles_once();
        ?>
        <section class="rs-cms-section rs-gallery" id="gallery" aria-label="Gallery">
            <div class="rs-cms-container">
                <div class="rs-cms-head">
                    <div>
                        <div class="rs-cms-chip" data-rs-t-en="<?php echo esc_attr($sections['gallery']['chip_en']); ?>" data-rs-t-id="<?php echo esc_attr($sections['gallery']['chip_id']); ?>"><?php echo esc_html($sections['gallery']['chip_en']); ?></div>
                        <h2 class="rs-cms-title" data-rs-t-en="<?php echo esc_attr($sections['gallery']['title_en']); ?>" data-rs-t-id="<?php echo esc_attr($sections['gallery']['title_id']); ?>"><?php echo esc_html($sections['gallery']['title_en']); ?></h2>
                    </div>
                    <p class="rs-cms-desc" data-rs-t-en="<?php echo esc_attr($sections['gallery']['desc_en']); ?>" data-rs-t-id="<?php echo esc_attr($sections['gallery']['desc_id']); ?>"><?php echo esc_html($sections['gallery']['desc_en']); ?></p>
                </div>

                <div class="rs-gallery-grid">
                    <?php foreach ($gallery as $item): ?>
                        <div class="rs-gallery-card">
                            <img
                                src="<?php echo esc_url($item['image']); ?>"
                                alt="<?php echo esc_attr($item['alt_en']); ?>"
                                data-rs-alt-en="<?php echo esc_attr($item['alt_en']); ?>"
                                data-rs-alt-id="<?php echo esc_attr($item['alt_id']); ?>"
                            >
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php

        echo self::render_lang_script_once();
        return (string) ob_get_clean();
    }

    public static function shortcode_both(array $atts = []): string
    {
        return self::shortcode_packages($atts) . self::shortcode_gallery($atts);
    }
}

RS_Elementor_Packages_Gallery_CMS::init();
