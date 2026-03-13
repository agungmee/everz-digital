<?php
/**
 * Plugin Name: Raun Sumatra Elementor Shortcode CMS
 * Description: Shortcode Elementor + CRUD section untuk index.html dan wisata-sumbar.html.
 * Version: 1.0.0
 * Author: Raun Sumatra
 */

if (!defined('ABSPATH')) {
    exit;
}

final class RS_Elementor_Shortcode_CMS
{
    private const OPTION_BASE_PATH = 'rsesc_base_path';
    private const OPTION_SEEDED = 'rsesc_seeded_v1';
    private const OPTION_SIMPLE_SETTINGS = 'rsesc_simple_settings_v1';
    private const NONCE_ACTION = 'rsesc_admin_action';
    private const PAGE_SLUG = 'rsesc-manager';
    private static array $collectedHeadAssets = [];
    private static array $collectedFooterScripts = [];
    private static bool $assetHooksRegistered = false;

    public static function init(): void
    {
        register_activation_hook(__FILE__, [self::class, 'activate']);
        add_action('admin_menu', [self::class, 'register_admin_menu']);
        add_action('admin_init', [self::class, 'handle_admin_post']);
        add_action('init', [self::class, 'maybe_seed_defaults']);
        add_shortcode('rs_page', [self::class, 'shortcode_page']);
        add_shortcode('rs_section', [self::class, 'shortcode_section']);
        add_shortcode('raunsumatra_index', [self::class, 'shortcode_index']);
        add_shortcode('raunsumatra_wisata_sumbar', [self::class, 'shortcode_wisata_sumbar']);
    }

    private static function ensure_asset_hooks(): void
    {
        if (self::$assetHooksRegistered) {
            return;
        }
        add_action('wp_head', [self::class, 'render_collected_head_assets'], 99);
        // Fallback: shortcodes are usually rendered after wp_head, so print pending
        // head assets again in footer when needed.
        add_action('wp_footer', [self::class, 'render_collected_head_assets'], 1);
        add_action('wp_footer', [self::class, 'render_collected_footer_scripts'], 99);
        self::$assetHooksRegistered = true;
    }

    public static function render_collected_head_assets(): void
    {
        if (empty(self::$collectedHeadAssets)) {
            return;
        }
        foreach (self::$collectedHeadAssets as $asset) {
            echo $asset . "\n";
        }
        self::$collectedHeadAssets = [];
    }

    public static function render_collected_footer_scripts(): void
    {
        if (empty(self::$collectedFooterScripts)) {
            return;
        }
        foreach (self::$collectedFooterScripts as $script) {
            echo $script . "\n";
        }
        self::$collectedFooterScripts = [];
    }

    private static function collect_assets_from_html(string $html): void
    {
        self::ensure_asset_hooks();

        if (preg_match_all('/<link\b[^>]*(?:rel=["\'](?:stylesheet|preconnect)["\'])[^>]*>/i', $html, $m)) {
            foreach ($m[0] as $tag) {
                $key = md5($tag);
                self::$collectedHeadAssets[$key] = $tag;
            }
        }

        if (preg_match_all('/<style\b[^>]*>[\s\S]*?<\/style>/i', $html, $m)) {
            foreach ($m[0] as $tag) {
                $key = md5($tag);
                self::$collectedHeadAssets[$key] = $tag;
            }
        }

        if (preg_match_all('/<script\b[^>]*>[\s\S]*?<\/script>/i', $html, $m)) {
            foreach ($m[0] as $tag) {
                $key = md5($tag);
                self::$collectedFooterScripts[$key] = $tag;
            }
        }
    }

    private static function collect_footer_script(string $scriptTag): void
    {
        self::ensure_asset_hooks();
        $key = md5($scriptTag);
        self::$collectedFooterScripts[$key] = $scriptTag;
    }

    private static function strip_embedded_assets(string $html): string
    {
        $clean = preg_replace('/<link\b[^>]*(?:rel=["\'](?:stylesheet|preconnect)["\'])[^>]*>/i', '', $html);
        $clean = is_string($clean) ? $clean : $html;
        $clean = preg_replace('/<style\b[^>]*>[\s\S]*?<\/style>/i', '', $clean);
        $clean = is_string($clean) ? $clean : $html;
        $clean = preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>/i', '', $clean);
        return is_string($clean) ? $clean : $html;
    }

    public static function activate(): void
    {
        self::seed_defaults(true);
    }

    public static function register_admin_menu(): void
    {
        add_menu_page(
            'Raun Sumatra CMS',
            'Raun Sumatra CMS',
            'manage_options',
            self::PAGE_SLUG,
            [self::class, 'render_admin_page'],
            'dashicons-edit-page',
            58
        );
    }

    private static function default_base_path(): string
    {
        $upload = wp_upload_dir();
        return trailingslashit($upload['basedir']) . 'rsesc-pages';
    }

    private static function bundled_path(): string
    {
        return trailingslashit(plugin_dir_path(__FILE__)) . 'templates';
    }

    private static function get_base_path(): string
    {
        $saved = get_option(self::OPTION_BASE_PATH, '');
        if (is_string($saved) && trim($saved) !== '') {
            return rtrim(trim($saved), '/\\');
        }

        return self::default_base_path();
    }

    private static function get_files(): array
    {
        $base = self::get_base_path();
        return [
            'index' => [
                'label' => 'index.html',
                'path' => $base . '/index.html',
            ],
            'wisata-sumbar' => [
                'label' => 'wisata-sumbar.html',
                'path' => $base . '/wisata-sumbar.html',
            ],
        ];
    }

    private static function has_required_files(string $base): bool
    {
        $a = rtrim($base, '/\\') . '/index.html';
        $b = rtrim($base, '/\\') . '/wisata-sumbar.html';
        return is_file($a) && is_readable($a) && is_file($b) && is_readable($b);
    }

    private static function active_base_path(): string
    {
        $current = self::get_base_path();
        if (self::has_required_files($current)) {
            return $current;
        }

        $bundled = self::bundled_path();
        if (self::has_required_files($bundled)) {
            return $bundled;
        }

        return $current;
    }

    private static function sanitize_file_key(string $key): string
    {
        $files = self::get_files_for_base(self::active_base_path());
        return isset($files[$key]) ? $key : 'index';
    }

    private static function get_files_for_base(string $base): array
    {
        return [
            'index' => [
                'label' => 'index.html',
                'path' => rtrim($base, '/\\') . '/index.html',
            ],
            'wisata-sumbar' => [
                'label' => 'wisata-sumbar.html',
                'path' => rtrim($base, '/\\') . '/wisata-sumbar.html',
            ],
        ];
    }

    private static function read_file(string $path): string
    {
        $content = @file_get_contents($path);
        if ($content === false) {
            throw new RuntimeException('Gagal membaca file: ' . $path);
        }

        return $content;
    }

    private static function write_file(string $path, string $content): void
    {
        $ok = @file_put_contents($path, $content);
        if ($ok === false) {
            throw new RuntimeException('Gagal menulis file: ' . $path);
        }
    }

    private static function get_simple_settings(): array
    {
        $raw = get_option(self::OPTION_SIMPLE_SETTINGS, []);
        return is_array($raw) ? $raw : [];
    }

    private static function get_simple_value(array $settings, string $fileKey, string $lang, string $fieldId): string
    {
        $value = $settings[$fileKey][$lang][$fieldId] ?? '';
        return is_string($value) ? $value : '';
    }

    private static function get_simple_field_value(array $settings, string $fileKey, string $lang, array $field): string
    {
        $id = (string) ($field['id'] ?? '');
        if ($id === '') {
            return '';
        }
        $saved = self::get_simple_value($settings, $fileKey, $lang, $id);
        if ($saved !== '') {
            return $saved;
        }
        $default = $field['default'] ?? '';
        return is_string($default) ? $default : '';
    }

    private static function simple_schema(string $fileKey): array
    {
        $menuDefaults = [
            'en' => ['Home', 'Packages', 'West Sumatra Travel', 'Destinations', 'About', 'Contact'],
            'id' => ['Beranda', 'Paket', 'Wisata Sumbar', 'Destinasi', 'Tentang', 'Kontak'],
        ];
        $menuFields = static function (string $lang) use ($menuDefaults): array {
            $items = [];
            for ($i = 0; $i < 6; $i++) {
                $idx = $i + 1;
                $items[] = [
                    'id' => 'menu_' . $idx,
                    'label' => 'Menu ' . $idx,
                    'section' => 'Navbar',
                    'mode' => 'text',
                    'default' => $menuDefaults[$lang][$i] ?? '',
                    'targets' => [
                        ['selector' => '.rs-menu a', 'index' => $i],
                        ['selector' => '.rs-mobile > a', 'index' => $i],
                    ],
                ];
            }
            return $items;
        };

        if ($fileKey === 'wisata-sumbar') {
            return [
                'shared' => [
                    ['id' => 'navbar_logo', 'label' => 'Logo Navbar URL', 'section' => 'Navbar', 'type' => 'url', 'selector' => '.rs-brand img', 'mode' => 'attr', 'attr' => 'src', 'default' => 'https://everz-digital.site/wp-content/uploads/2026/01/logo-RAUN-SUMATRA-768x132-removebg-preview.png'],
                    ['id' => 'hero_card_image', 'label' => 'Hero Card Image URL', 'section' => 'Hero', 'type' => 'url', 'selector' => '.sb-hero-card img', 'mode' => 'attr', 'attr' => 'src'],
                ],
                'en' => array_merge($menuFields('en'), [
                    ['id' => 'hero_chip', 'label' => 'Hero Chip', 'section' => 'Hero', 'selector' => '.sb-hero .sb-chip', 'mode' => 'text', 'default' => 'West Sumatra Travel List'],
                    ['id' => 'hero_title', 'label' => 'Hero Title', 'section' => 'Hero', 'selector' => '.sb-hero h1', 'mode' => 'text', 'default' => 'Culture, nature, and culinary highlights across Minang land.'],
                    ['id' => 'hero_desc', 'label' => 'Hero Description', 'section' => 'Hero', 'selector' => '.sb-hero > .sb-container > div:first-child p', 'mode' => 'text'],
                    ['id' => 'banner_cta', 'label' => 'Banner CTA', 'section' => 'Hero', 'selector' => '.sb-banner a', 'mode' => 'text', 'default' => 'See Tour Packages'],
                ]),
                'id' => array_merge($menuFields('id'), [
                    ['id' => 'hero_chip', 'label' => 'Hero Chip', 'section' => 'Hero', 'selector' => '.sb-hero .sb-chip', 'mode' => 'text', 'default' => 'Daftar Wisata Sumbar'],
                    ['id' => 'hero_title', 'label' => 'Hero Title', 'section' => 'Hero', 'selector' => '.sb-hero h1', 'mode' => 'text', 'default' => 'Budaya, alam, dan kuliner unggulan di Ranah Minang.'],
                    ['id' => 'hero_desc', 'label' => 'Hero Description', 'section' => 'Hero', 'selector' => '.sb-hero > .sb-container > div:first-child p', 'mode' => 'text'],
                    ['id' => 'banner_cta', 'label' => 'Banner CTA', 'section' => 'Hero', 'selector' => '.sb-banner a', 'mode' => 'text', 'default' => 'Lihat Paket Wisata'],
                ]),
            ];
        }

        return [
            'shared' => [
                ['id' => 'navbar_logo', 'label' => 'Logo Navbar URL', 'section' => 'Navbar', 'type' => 'url', 'selector' => '.rs-brand img', 'mode' => 'attr', 'attr' => 'src', 'default' => 'https://everz-digital.site/wp-content/uploads/2026/01/logo-RAUN-SUMATRA-768x132-removebg-preview.png'],
                ['id' => 'hero_image_1', 'label' => 'Hero Image 1 URL', 'section' => 'Hero', 'type' => 'url', 'selector' => '.rs-hero-bg-layer', 'index' => 0, 'mode' => 'bg_image', 'default' => 'http://raunsumatra.com/wp-content/uploads/2026/02/padang-sumatra-indonesia-travel-photo-20240703182607902-main-image.jpg'],
                ['id' => 'hero_image_2', 'label' => 'Hero Image 2 URL', 'section' => 'Hero', 'type' => 'url', 'selector' => '.rs-hero-bg-layer', 'index' => 1, 'mode' => 'bg_image', 'default' => 'http://raunsumatra.com/wp-content/uploads/2026/02/iyehioifp1zy1ve.jpeg'],
                ['id' => 'package_image_1', 'label' => 'Paket 1 Image URL', 'section' => 'Paket', 'type' => 'url', 'selector' => '.rs-package-media img', 'index' => 0, 'mode' => 'attr', 'attr' => 'src', 'default' => 'http://raunsumatra.com/wp-content/uploads/2026/02/padang-sumatra-indonesia-travel-photo-20240703182607902-main-image.jpg'],
                ['id' => 'package_image_2', 'label' => 'Paket 2 Image URL', 'section' => 'Paket', 'type' => 'url', 'selector' => '.rs-package-media img', 'index' => 1, 'mode' => 'attr', 'attr' => 'src', 'default' => 'http://raunsumatra.com/wp-content/uploads/2026/02/jam-gadang_1999979717.jpg'],
                ['id' => 'package_image_3', 'label' => 'Paket 3 Image URL', 'section' => 'Paket', 'type' => 'url', 'selector' => '.rs-package-media img', 'index' => 2, 'mode' => 'attr', 'attr' => 'src', 'default' => 'https://astradigitaldigiroomuat.blob.core.windows.net/storage-uat-001/tempat-wisata-di-padang.jpg'],
                ['id' => 'package_image_4', 'label' => 'Paket 4 Image URL', 'section' => 'Paket', 'type' => 'url', 'selector' => '.rs-package-media img', 'index' => 3, 'mode' => 'attr', 'attr' => 'src', 'default' => 'http://raunsumatra.com/wp-content/uploads/2026/02/db9f488ba033c63667e6e056ad55e5c0.jpg'],
            ],
            'en' => array_merge($menuFields('en'), [
                ['id' => 'package_title', 'label' => 'Judul Section Paket', 'section' => 'Paket', 'selector' => '.rs-package-title', 'mode' => 'text', 'default' => 'Tour Packages'],
                ['id' => 'package_desc', 'label' => 'Deskripsi Paket', 'section' => 'Paket', 'selector' => '.rs-package-desc', 'mode' => 'text'],
                ['id' => 'package_name_1', 'label' => 'Nama Paket 1', 'section' => 'Paket', 'selector' => '.rs-package-name', 'index' => 0, 'mode' => 'text', 'default' => '5D 4N Explore Alahan Panjang – Bukittinggi – Padang'],
                ['id' => 'package_name_2', 'label' => 'Nama Paket 2', 'section' => 'Paket', 'selector' => '.rs-package-name', 'index' => 1, 'mode' => 'text', 'default' => '4D 3N Explore Alahan Panjang - Bukittinggi'],
                ['id' => 'package_name_3', 'label' => 'Nama Paket 3', 'section' => 'Paket', 'selector' => '.rs-package-name', 'index' => 2, 'mode' => 'text', 'default' => '4D 3N Explore Padang - Bukittinggi'],
                ['id' => 'package_name_4', 'label' => 'Nama Paket 4', 'section' => 'Paket', 'selector' => '.rs-package-name', 'index' => 3, 'mode' => 'text', 'default' => '5D 4N Explore Padang - Bukittinggi'],
                ['id' => 'package_sub_1', 'label' => 'Sub Paket 1', 'section' => 'Paket', 'selector' => '.rs-package-sub', 'index' => 0, 'mode' => 'text'],
                ['id' => 'package_sub_2', 'label' => 'Sub Paket 2', 'section' => 'Paket', 'selector' => '.rs-package-sub', 'index' => 1, 'mode' => 'text'],
                ['id' => 'package_sub_3', 'label' => 'Sub Paket 3', 'section' => 'Paket', 'selector' => '.rs-package-sub', 'index' => 2, 'mode' => 'text'],
                ['id' => 'package_sub_4', 'label' => 'Sub Paket 4', 'section' => 'Paket', 'selector' => '.rs-package-sub', 'index' => 3, 'mode' => 'text'],
                ['id' => 'package_price_1', 'label' => 'Harga Paket 1', 'section' => 'Paket', 'selector' => '.rs-package-price', 'index' => 0, 'mode' => 'text', 'default' => 'From Rp. 4.140.000 / pax'],
                ['id' => 'package_price_2', 'label' => 'Harga Paket 2', 'section' => 'Paket', 'selector' => '.rs-package-price', 'index' => 1, 'mode' => 'text', 'default' => 'From Rp. 3.375.000 / pax'],
                ['id' => 'package_price_3', 'label' => 'Harga Paket 3', 'section' => 'Paket', 'selector' => '.rs-package-price', 'index' => 2, 'mode' => 'text', 'default' => 'From Rp. 3.015.000 / pax'],
                ['id' => 'package_price_4', 'label' => 'Harga Paket 4', 'section' => 'Paket', 'selector' => '.rs-package-price', 'index' => 3, 'mode' => 'text', 'default' => 'From Rp. 3.895.000 / pax'],
                ['id' => 'contact_title', 'label' => 'Judul Contact', 'section' => 'Contact', 'selector' => '.rs-contact-title', 'mode' => 'text', 'default' => 'Contact Raun Sumatra'],
                ['id' => 'contact_desc', 'label' => 'Deskripsi Contact', 'section' => 'Contact', 'selector' => '.rs-contact-desc', 'mode' => 'text'],
            ]),
            'id' => array_merge($menuFields('id'), [
                ['id' => 'package_title', 'label' => 'Judul Section Paket', 'section' => 'Paket', 'selector' => '.rs-package-title', 'mode' => 'text', 'default' => 'Paket Wisata'],
                ['id' => 'package_desc', 'label' => 'Deskripsi Paket', 'section' => 'Paket', 'selector' => '.rs-package-desc', 'mode' => 'text'],
                ['id' => 'package_name_1', 'label' => 'Nama Paket 1', 'section' => 'Paket', 'selector' => '.rs-package-name', 'index' => 0, 'mode' => 'text', 'default' => '5H 4M Jelajah Alahan Panjang – Bukittinggi – Padang'],
                ['id' => 'package_name_2', 'label' => 'Nama Paket 2', 'section' => 'Paket', 'selector' => '.rs-package-name', 'index' => 1, 'mode' => 'text', 'default' => '4H 3M Jelajah Alahan Panjang - Bukittinggi'],
                ['id' => 'package_name_3', 'label' => 'Nama Paket 3', 'section' => 'Paket', 'selector' => '.rs-package-name', 'index' => 2, 'mode' => 'text', 'default' => '4H 3M Jelajah Padang - Bukittinggi'],
                ['id' => 'package_name_4', 'label' => 'Nama Paket 4', 'section' => 'Paket', 'selector' => '.rs-package-name', 'index' => 3, 'mode' => 'text', 'default' => '5H 4M Jelajah Padang - Bukittinggi'],
                ['id' => 'package_sub_1', 'label' => 'Sub Paket 1', 'section' => 'Paket', 'selector' => '.rs-package-sub', 'index' => 0, 'mode' => 'text'],
                ['id' => 'package_sub_2', 'label' => 'Sub Paket 2', 'section' => 'Paket', 'selector' => '.rs-package-sub', 'index' => 1, 'mode' => 'text'],
                ['id' => 'package_sub_3', 'label' => 'Sub Paket 3', 'section' => 'Paket', 'selector' => '.rs-package-sub', 'index' => 2, 'mode' => 'text'],
                ['id' => 'package_sub_4', 'label' => 'Sub Paket 4', 'section' => 'Paket', 'selector' => '.rs-package-sub', 'index' => 3, 'mode' => 'text'],
                ['id' => 'package_price_1', 'label' => 'Harga Paket 1', 'section' => 'Paket', 'selector' => '.rs-package-price', 'index' => 0, 'mode' => 'text', 'default' => 'Mulai Rp. 4.140.000 / pax'],
                ['id' => 'package_price_2', 'label' => 'Harga Paket 2', 'section' => 'Paket', 'selector' => '.rs-package-price', 'index' => 1, 'mode' => 'text', 'default' => 'Mulai Rp. 3.375.000 / pax'],
                ['id' => 'package_price_3', 'label' => 'Harga Paket 3', 'section' => 'Paket', 'selector' => '.rs-package-price', 'index' => 2, 'mode' => 'text', 'default' => 'Mulai Rp. 3.015.000 / pax'],
                ['id' => 'package_price_4', 'label' => 'Harga Paket 4', 'section' => 'Paket', 'selector' => '.rs-package-price', 'index' => 3, 'mode' => 'text', 'default' => 'Mulai Rp. 3.895.000 / pax'],
                ['id' => 'contact_title', 'label' => 'Judul Contact', 'section' => 'Contact', 'selector' => '.rs-contact-title', 'mode' => 'text', 'default' => 'Hubungi Raun Sumatra'],
                ['id' => 'contact_desc', 'label' => 'Deskripsi Contact', 'section' => 'Contact', 'selector' => '.rs-contact-desc', 'mode' => 'text'],
            ]),
        ];
    }

    private static function sanitize_simple_post_value(string $value, string $type): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }
        if ($type === 'url') {
            if (preg_match('/^(#|\/|\?)/', $value)) {
                return sanitize_text_field($value);
            }
            return esc_url_raw($value);
        }
        return sanitize_textarea_field($value);
    }

    private static function simple_operations_for_file(string $fileKey): array
    {
        $settings = self::get_simple_settings();
        $schema = self::simple_schema($fileKey);
        $ops = [];

        foreach (['shared', 'en', 'id'] as $lang) {
            $fields = $schema[$lang] ?? [];
            foreach ($fields as $field) {
                $id = (string) ($field['id'] ?? '');
                if ($id === '') {
                    continue;
                }
                $value = self::get_simple_field_value($settings, $fileKey, $lang, $field);
                if ($value === '') {
                    continue;
                }
                $targets = [];
                if (isset($field['targets']) && is_array($field['targets'])) {
                    $targets = $field['targets'];
                } elseif (!empty($field['selector'])) {
                    $single = ['selector' => (string) $field['selector']];
                    if (isset($field['index'])) {
                        $single['index'] = (int) $field['index'];
                    }
                    $targets[] = $single;
                }

                foreach ($targets as $target) {
                    $selector = (string) ($target['selector'] ?? '');
                    if ($selector === '') {
                        continue;
                    }
                    $op = [
                        'lang' => $lang === 'shared' ? 'all' : $lang,
                        'selector' => $selector,
                        'mode' => (string) ($field['mode'] ?? 'text'),
                        'value' => $value,
                    ];
                    if (isset($target['index'])) {
                        $op['index'] = (int) $target['index'];
                    }
                    if (isset($field['attr'])) {
                        $op['attr'] = (string) $field['attr'];
                    }
                    $ops[] = $op;
                }
            }
        }

        return $ops;
    }

    private static function simple_override_script(string $fileKey): string
    {
        $ops = self::simple_operations_for_file($fileKey);
        if (!$ops) {
            return '';
        }

        $json = wp_json_encode($ops);
        if (!is_string($json) || $json === '') {
            return '';
        }

        return '<script>(function(){const ops=' . $json . ';function apply(lang){ops.forEach(function(op){if(op.lang!=="all"&&op.lang!==lang){return;}const nodes=document.querySelectorAll(op.selector);const index=typeof op.index==="number"?op.index:0;const el=nodes[index];if(!el){return;}if(op.mode==="attr"&&op.attr){el.setAttribute(op.attr,op.value);return;}if(op.mode==="bg_image"){el.style.backgroundImage="url(\'"+op.value+"\')";return;}el.textContent=op.value;});}function run(){const lang=window.__rsLang||localStorage.getItem("rsLang")||"en";apply(lang);}if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",run);}else{run();}window.addEventListener("load",run);document.querySelectorAll("[data-rs-lang]").forEach(function(btn){btn.addEventListener("click",function(){setTimeout(run,0);});});})();</script>';
    }

    private static function backup_file(string $filename, string $content): string
    {
        $upload = wp_upload_dir();
        $dir = trailingslashit($upload['basedir']) . 'rs-section-backups';
        if (!is_dir($dir) && !wp_mkdir_p($dir)) {
            throw new RuntimeException('Gagal membuat folder backup: ' . $dir);
        }

        $safe = preg_replace('/[^a-zA-Z0-9._-]/', '-', $filename);
        $name = $safe . '.' . gmdate('Ymd-His') . '.bak';
        $path = trailingslashit($dir) . $name;
        self::write_file($path, $content);

        return $name;
    }

    private static function section_id(string $label, int $occurrence): string
    {
        $slug = sanitize_title($label);
        if ($slug === '') {
            $slug = 'section';
        }
        return $slug . '-' . $occurrence;
    }

    private static function detect_sections(string $html): array
    {
        $sections = [];
        $labelCount = [];

        if (preg_match_all('/<!--\s*Start\s+(.+?)\s*-->(.*?)<!--\s*End\s+(.+?)\s*-->/is', $html, $m, PREG_OFFSET_CAPTURE)) {
            foreach ($m[0] as $i => $full) {
                $block = $full[0];
                $start = (int) $full[1];
                $end = $start + strlen($block);
                $label = trim((string) $m[1][$i][0]);
                if ($label === '') {
                    $label = 'Section ' . ($i + 1);
                }
                $labelCount[$label] = ($labelCount[$label] ?? 0) + 1;
                $sid = self::section_id($label, (int) $labelCount[$label]);
                $sections[] = [
                    'id' => $sid,
                    'label' => $label,
                    'start' => $start,
                    'end' => $end,
                    'html' => $block,
                ];
            }
            return $sections;
        }

        if (preg_match_all('/<(header|section|footer)\b([^>]*)>.*?<\/\1>/is', $html, $m, PREG_OFFSET_CAPTURE)) {
            foreach ($m[0] as $i => $full) {
                $block = $full[0];
                $start = (int) $full[1];
                $end = $start + strlen($block);
                $tag = strtoupper((string) $m[1][$i][0]);
                $attrs = (string) $m[2][$i][0];
                $label = $tag . ' block ' . ($i + 1);
                if (preg_match('/\bid\s*=\s*["\']([^"\']+)["\']/i', $attrs, $x)) {
                    $label = $tag . ' #' . trim((string) $x[1]);
                }
                $labelCount[$label] = ($labelCount[$label] ?? 0) + 1;
                $sid = self::section_id($label, (int) $labelCount[$label]);
                $sections[] = [
                    'id' => $sid,
                    'label' => $label,
                    'start' => $start,
                    'end' => $end,
                    'html' => $block,
                ];
            }
        }

        return $sections;
    }

    private static function replace_range(string $html, int $start, int $end, string $replacement): string
    {
        return substr($html, 0, $start) . $replacement . substr($html, $end);
    }

    private static function parse_section_dom(string $sectionHtml): array
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $wrapped = '<!doctype html><html><body><div id="rsesc-root">' . $sectionHtml . '</div></body></html>';
        $dom->loadHTML('<?xml encoding="UTF-8">' . $wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        $root = $dom->getElementById('rsesc-root');
        if (!$root) {
            throw new RuntimeException('Gagal parse section HTML.');
        }
        return [$dom, $root];
    }

    private static function dom_inner_html(DOMNode $node): string
    {
        $html = '';
        foreach ($node->childNodes as $child) {
            $html .= $node->ownerDocument->saveHTML($child);
        }
        return $html;
    }

    private static function is_hidden_text_parent(?DOMNode $parent): bool
    {
        if (!$parent instanceof DOMElement) {
            return true;
        }
        $tag = strtolower($parent->tagName);
        return in_array($tag, ['script', 'style', 'noscript', 'textarea'], true);
    }

    private static function extract_visual_fields(string $sectionHtml): array
    {
        [$dom, $root] = self::parse_section_dom($sectionHtml);

        $textItems = [];
        $textNodes = [];
        $textIndex = 0;

        $walker = function (DOMNode $node) use (&$walker, &$textItems, &$textNodes, &$textIndex): void {
            if ($node instanceof DOMText) {
                $raw = $node->nodeValue ?? '';
                $text = trim(preg_replace('/\s+/', ' ', $raw));
                if ($text !== '' && !self::is_hidden_text_parent($node->parentNode)) {
                    $id = $textIndex++;
                    $parent = $node->parentNode instanceof DOMElement ? strtolower($node->parentNode->tagName) : 'text';
                    $textNodes[$id] = $node;
                    $textItems[] = [
                        'id' => $id,
                        'tag' => $parent,
                        'value' => $text,
                    ];
                }
                return;
            }

            if ($node instanceof DOMElement) {
                foreach ($node->childNodes as $child) {
                    $walker($child);
                }
            }
        };
        $walker($root);

        $imageItems = [];
        $imageNodes = [];
        $images = $root->getElementsByTagName('img');
        $imageIndex = 0;
        foreach ($images as $img) {
            $id = $imageIndex++;
            $imageNodes[$id] = $img;
            $imageItems[] = [
                'id' => $id,
                'src' => (string) $img->getAttribute('src'),
                'alt' => (string) $img->getAttribute('alt'),
            ];
        }

        $attrItems = [];
        $attrNodes = [];
        $attrIndex = 0;
        $attrWhitelist = [
            'href',
            'src',
            'alt',
            'title',
            'aria-label',
            'placeholder',
            'value',
            'content',
            'action',
            'data-rs-modal',
            'data-rs-package',
            'data-rs-lang',
        ];
        $all = $root->getElementsByTagName('*');
        foreach ($all as $el) {
            if (!$el instanceof DOMElement || !$el->hasAttributes()) {
                continue;
            }
            foreach ($el->attributes as $attr) {
                $name = strtolower((string) $attr->name);
                if (!in_array($name, $attrWhitelist, true) && strpos($name, 'data-') !== 0) {
                    continue;
                }
                $id = $attrIndex++;
                $attrNodes[$id] = [$el, $name];
                $attrItems[] = [
                    'id' => $id,
                    'tag' => strtolower($el->tagName),
                    'name' => $name,
                    'value' => (string) $attr->value,
                ];
            }
        }

        return [
            'text_items' => $textItems,
            'image_items' => $imageItems,
            'attr_items' => $attrItems,
            'text_nodes' => $textNodes,
            'image_nodes' => $imageNodes,
            'attr_nodes' => $attrNodes,
            'dom' => $dom,
            'root' => $root,
        ];
    }

    private static function handle_single_image_upload(array $file): string
    {
        if (!function_exists('wp_handle_upload')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        $upload = wp_handle_upload($file, ['test_form' => false]);
        if (!is_array($upload) || isset($upload['error'])) {
            $err = is_array($upload) && isset($upload['error']) ? $upload['error'] : 'Upload gagal';
            throw new RuntimeException('Upload gambar gagal: ' . $err);
        }
        return (string) $upload['url'];
    }

    private static function apply_visual_updates(string $sectionHtml, array $post, array $files): string
    {
        $parsed = self::extract_visual_fields($sectionHtml);
        /** @var DOMDocument $dom */
        $dom = $parsed['dom'];
        /** @var DOMElement $root */
        $root = $parsed['root'];
        $textNodes = $parsed['text_nodes'];
        $imageNodes = $parsed['image_nodes'];
        $attrNodes = $parsed['attr_nodes'];

        $textValues = isset($post['text_value']) && is_array($post['text_value']) ? $post['text_value'] : [];
        foreach ($textValues as $idx => $value) {
            $id = (int) $idx;
            if (!isset($textNodes[$id])) {
                continue;
            }
            $node = $textNodes[$id];
            $node->nodeValue = sanitize_textarea_field((string) $value);
        }

        $imageModes = isset($post['image_mode']) && is_array($post['image_mode']) ? $post['image_mode'] : [];
        $imageUrls = isset($post['image_url']) && is_array($post['image_url']) ? $post['image_url'] : [];
        $imageAlts = isset($post['image_alt']) && is_array($post['image_alt']) ? $post['image_alt'] : [];

        foreach ($imageModes as $idx => $mode) {
            $id = (int) $idx;
            if (!isset($imageNodes[$id])) {
                continue;
            }
            $img = $imageNodes[$id];
            $mode = sanitize_text_field((string) $mode);
            $alt = isset($imageAlts[$idx]) ? sanitize_text_field((string) $imageAlts[$idx]) : '';

            if ($mode === 'remove') {
                $img->parentNode?->removeChild($img);
                continue;
            }

            if ($mode === 'url') {
                $newUrl = isset($imageUrls[$idx]) ? esc_url_raw((string) $imageUrls[$idx]) : '';
                if ($newUrl !== '') {
                    $img->setAttribute('src', $newUrl);
                }
            }

            if ($mode === 'upload') {
                $fileKey = 'image_file_' . $id;
                if (isset($files[$fileKey]) && isset($files[$fileKey]['error']) && (int) $files[$fileKey]['error'] === 0) {
                    $newUrl = self::handle_single_image_upload($files[$fileKey]);
                    $img->setAttribute('src', $newUrl);
                }
            }

            $img->setAttribute('alt', $alt);
        }

        $attrValues = isset($post['attr_value']) && is_array($post['attr_value']) ? $post['attr_value'] : [];
        foreach ($attrValues as $idx => $value) {
            $id = (int) $idx;
            if (!isset($attrNodes[$id]) || !is_array($attrNodes[$id])) {
                continue;
            }
            [$el, $name] = $attrNodes[$id];
            if (!$el instanceof DOMElement) {
                continue;
            }
            $raw = (string) $value;
            $trimmed = trim($raw);
            if ($trimmed === '') {
                $el->removeAttribute($name);
                continue;
            }
            if (in_array($name, ['href', 'src', 'action'], true)) {
                if (preg_match('/^(#|\/|\?)/', $trimmed)) {
                    $safe = sanitize_text_field($trimmed);
                } else {
                    $safe = esc_url_raw($trimmed);
                }
                if ($safe === '') {
                    continue;
                }
                $el->setAttribute($name, $safe);
                continue;
            }
            $el->setAttribute($name, sanitize_text_field($trimmed));
        }

        $addMode = isset($post['add_image_mode']) ? sanitize_text_field((string) $post['add_image_mode']) : '';
        $addAlt = isset($post['add_image_alt']) ? sanitize_text_field((string) $post['add_image_alt']) : '';
        $newImageUrl = '';
        if ($addMode === 'url') {
            $newImageUrl = isset($post['add_image_url']) ? esc_url_raw((string) $post['add_image_url']) : '';
        } elseif ($addMode === 'upload') {
            if (isset($files['add_image_file']) && isset($files['add_image_file']['error']) && (int) $files['add_image_file']['error'] === 0) {
                $newImageUrl = self::handle_single_image_upload($files['add_image_file']);
            }
        }
        if ($newImageUrl !== '') {
            $newImg = $dom->createElement('img');
            $newImg->setAttribute('src', $newImageUrl);
            $newImg->setAttribute('alt', $addAlt);
            $root->appendChild($newImg);
        }

        return self::dom_inner_html($root);
    }

    private static function find_matching_brace(string $text, int $openPos): int
    {
        $len = strlen($text);
        if ($openPos < 0 || $openPos >= $len || $text[$openPos] !== '{') {
            return -1;
        }

        $depth = 0;
        $quote = '';
        $escape = false;

        for ($i = $openPos; $i < $len; $i++) {
            $ch = $text[$i];

            if ($quote !== '') {
                if ($escape) {
                    $escape = false;
                    continue;
                }
                if ($ch === '\\') {
                    $escape = true;
                    continue;
                }
                if ($ch === $quote) {
                    $quote = '';
                }
                continue;
            }

            if ($ch === '"' || $ch === "'" || $ch === '`') {
                $quote = $ch;
                continue;
            }

            if ($ch === '{') {
                $depth++;
                continue;
            }
            if ($ch === '}') {
                $depth--;
                if ($depth === 0) {
                    return $i;
                }
            }
        }

        return -1;
    }

    private static function extract_js_translations(string $sectionHtml): ?array
    {
        $needle = 'const translations';
        $constPos = strpos($sectionHtml, $needle);
        if ($constPos === false) {
            return null;
        }

        $eqPos = strpos($sectionHtml, '=', $constPos);
        if ($eqPos === false) {
            return null;
        }

        $objStart = strpos($sectionHtml, '{', $eqPos);
        if ($objStart === false) {
            return null;
        }

        $objEnd = self::find_matching_brace($sectionHtml, (int) $objStart);
        if ($objEnd < 0) {
            return null;
        }

        $objectLiteral = substr($sectionHtml, (int) $objStart, (int) ($objEnd - $objStart + 1));
        $enRange = self::find_top_level_lang_range($objectLiteral, 'en');
        $idRange = self::find_top_level_lang_range($objectLiteral, 'id');

        if (!$enRange || !$idRange) {
            return null;
        }

        return [
            'section_start' => (int) $objStart,
            'section_end' => (int) $objEnd,
            'object' => $objectLiteral,
            'en' => [
                'start' => (int) $enRange['start'],
                'end' => (int) $enRange['end'],
                'value' => substr($objectLiteral, (int) $enRange['start'], (int) ($enRange['end'] - $enRange['start'] + 1)),
            ],
            'id' => [
                'start' => (int) $idRange['start'],
                'end' => (int) $idRange['end'],
                'value' => substr($objectLiteral, (int) $idRange['start'], (int) ($idRange['end'] - $idRange['start'] + 1)),
            ],
        ];
    }

    private static function find_top_level_lang_range(string $objectLiteral, string $lang): ?array
    {
        $len = strlen($objectLiteral);
        if ($len < 2 || $objectLiteral[0] !== '{') {
            return null;
        }

        $depth = 0;
        $quote = '';
        $escape = false;
        $i = 0;

        while ($i < $len) {
            $ch = $objectLiteral[$i];

            if ($quote !== '') {
                if ($escape) {
                    $escape = false;
                    $i++;
                    continue;
                }
                if ($ch === '\\') {
                    $escape = true;
                    $i++;
                    continue;
                }
                if ($ch === $quote) {
                    $quote = '';
                }
                $i++;
                continue;
            }

            if ($ch === '"' || $ch === "'" || $ch === '`') {
                $quote = $ch;
                $i++;
                continue;
            }

            if ($ch === '{' || $ch === '[') {
                $depth++;
                $i++;
                continue;
            }
            if ($ch === '}' || $ch === ']') {
                $depth--;
                $i++;
                continue;
            }

            if ($depth !== 1) {
                $i++;
                continue;
            }

            if (ctype_space($ch) || $ch === ',') {
                $i++;
                continue;
            }

            $key = '';
            $keyStart = $i;

            if ($ch === "'" || $ch === '"') {
                $q = $ch;
                $i++;
                $start = $i;
                while ($i < $len) {
                    if ($objectLiteral[$i] === '\\') {
                        $i += 2;
                        continue;
                    }
                    if ($objectLiteral[$i] === $q) {
                        break;
                    }
                    $i++;
                }
                if ($i >= $len) {
                    return null;
                }
                $key = substr($objectLiteral, $start, $i - $start);
                $i++;
            } elseif (preg_match('/[A-Za-z_$]/', $ch) === 1) {
                $start = $i;
                $i++;
                while ($i < $len && preg_match('/[A-Za-z0-9_$-]/', $objectLiteral[$i]) === 1) {
                    $i++;
                }
                $key = substr($objectLiteral, $start, $i - $start);
            } else {
                $i++;
                continue;
            }

            while ($i < $len && ctype_space($objectLiteral[$i])) {
                $i++;
            }
            if ($i >= $len || $objectLiteral[$i] !== ':') {
                continue;
            }
            $i++;
            while ($i < $len && ctype_space($objectLiteral[$i])) {
                $i++;
            }

            if ($key !== $lang || $i >= $len || $objectLiteral[$i] !== '{') {
                continue;
            }

            $startObj = $i;
            $endObj = self::find_matching_brace($objectLiteral, $startObj);
            if ($endObj < 0) {
                return null;
            }

            return [
                'key_start' => $keyStart,
                'start' => $startObj,
                'end' => $endObj,
            ];
        }

        return null;
    }

    private static function apply_translation_updates_to_section(string $sectionHtml, string $newEn, string $newId): string
    {
        $parsed = self::extract_js_translations($sectionHtml);
        if (!$parsed) {
            throw new RuntimeException('Objek translations tidak ditemukan pada section ini.');
        }

        $newEn = trim($newEn);
        $newId = trim($newId);
        if ($newEn === '' || $newId === '') {
            throw new RuntimeException('Blok translations en/id tidak boleh kosong.');
        }
        if (!preg_match('/^\{[\s\S]*\}$/', $newEn) || !preg_match('/^\{[\s\S]*\}$/', $newId)) {
            throw new RuntimeException('Format translations en/id harus berupa object literal, contoh: { ... }');
        }

        $object = (string) $parsed['object'];
        $replacements = [
            ['start' => (int) $parsed['en']['start'], 'end' => (int) $parsed['en']['end'], 'value' => $newEn],
            ['start' => (int) $parsed['id']['start'], 'end' => (int) $parsed['id']['end'], 'value' => $newId],
        ];

        usort($replacements, static function (array $a, array $b): int {
            return $b['start'] <=> $a['start'];
        });

        foreach ($replacements as $r) {
            $object = self::replace_range($object, (int) $r['start'], (int) $r['end'] + 1, (string) $r['value']);
        }

        return self::replace_range(
            $sectionHtml,
            (int) $parsed['section_start'],
            (int) $parsed['section_end'] + 1,
            $object
        );
    }

    public static function maybe_seed_defaults(): void
    {
        if (get_option(self::OPTION_SEEDED) === '1') {
            return;
        }
        self::seed_defaults(false);
    }

    private static function seed_defaults(bool $force = false): void
    {
        $targetBase = self::default_base_path();
        $bundled = self::bundled_path();

        if (!$force && self::has_required_files($targetBase)) {
            update_option(self::OPTION_BASE_PATH, $targetBase);
            update_option(self::OPTION_SEEDED, '1');
            return;
        }

        if (!self::has_required_files($bundled)) {
            return;
        }

        if (!is_dir($targetBase) && !wp_mkdir_p($targetBase)) {
            return;
        }

        $srcIndex = rtrim($bundled, '/\\') . '/index.html';
        $srcWisata = rtrim($bundled, '/\\') . '/wisata-sumbar.html';
        $dstIndex = rtrim($targetBase, '/\\') . '/index.html';
        $dstWisata = rtrim($targetBase, '/\\') . '/wisata-sumbar.html';

        if ($force || !is_file($dstIndex)) {
            @copy($srcIndex, $dstIndex);
        }
        if ($force || !is_file($dstWisata)) {
            @copy($srcWisata, $dstWisata);
        }

        if (self::has_required_files($targetBase)) {
            update_option(self::OPTION_BASE_PATH, $targetBase);
            update_option(self::OPTION_SEEDED, '1');
        }
    }

    public static function handle_admin_post(): void
    {
        if (!is_admin() || !current_user_can('manage_options')) {
            return;
        }
        if (!isset($_POST['rsesc_action'])) {
            return;
        }
        check_admin_referer(self::NONCE_ACTION, 'rsesc_nonce');

        $base = self::active_base_path();
        $files = self::get_files_for_base($base);
        $fileKey = isset($_POST['file_key']) ? self::sanitize_file_key((string) $_POST['file_key']) : 'index';
        $path = $files[$fileKey]['path'];
        $filename = $files[$fileKey]['label'];
        $action = sanitize_text_field((string) $_POST['rsesc_action']);

        try {
            if ($action === 'save_simple') {
                $schema = self::simple_schema($fileKey);
                $settings = self::get_simple_settings();

                foreach (['shared', 'en', 'id'] as $lang) {
                    $fields = $schema[$lang] ?? [];
                    foreach ($fields as $field) {
                        $id = (string) ($field['id'] ?? '');
                        if ($id === '') {
                            continue;
                        }
                        $type = (string) ($field['type'] ?? 'text');
                        $raw = isset($_POST['simple'][$lang][$id]) ? (string) $_POST['simple'][$lang][$id] : '';
                        $value = self::sanitize_simple_post_value($raw, $type);
                        if (!isset($settings[$fileKey]) || !is_array($settings[$fileKey])) {
                            $settings[$fileKey] = [];
                        }
                        if (!isset($settings[$fileKey][$lang]) || !is_array($settings[$fileKey][$lang])) {
                            $settings[$fileKey][$lang] = [];
                        }
                        $settings[$fileKey][$lang][$id] = $value;
                    }
                }

                update_option(self::OPTION_SIMPLE_SETTINGS, $settings);
                self::redirect_admin($fileKey, 'Simple CMS berhasil disimpan.');
            }

            if ($action === 'reset_simple') {
                $settings = self::get_simple_settings();
                if (isset($settings[$fileKey])) {
                    unset($settings[$fileKey]);
                    update_option(self::OPTION_SIMPLE_SETTINGS, $settings);
                }
                self::redirect_admin($fileKey, 'Custom override direset. Kembali ke default data existing.');
            }

            if ($action === 'save_path') {
                $basePath = isset($_POST['base_path']) ? trim((string) $_POST['base_path']) : '';
                if ($basePath === '') {
                    throw new RuntimeException('Base path tidak boleh kosong.');
                }
                update_option(self::OPTION_BASE_PATH, rtrim($basePath, '/\\'));
                self::redirect_admin($fileKey, 'Path berhasil disimpan.');
            }

            if ($action === 'load_defaults') {
                self::seed_defaults(true);
                self::redirect_admin($fileKey, 'Template default berhasil dimuat.');
            }

            $html = self::read_file($path);
            $sections = self::detect_sections($html);

            if ($action === 'update') {
                $sectionId = sanitize_text_field((string) ($_POST['section_id'] ?? ''));
                $newHtml = (string) ($_POST['section_html'] ?? '');

                $target = null;
                foreach ($sections as $section) {
                    if ($section['id'] === $sectionId) {
                        $target = $section;
                        break;
                    }
                }
                if (!$target) {
                    throw new RuntimeException('Section tidak ditemukan.');
                }

                $backup = self::backup_file($filename, $html);
                $updated = self::replace_range($html, $target['start'], $target['end'], $newHtml);
                self::write_file($path, $updated);
                self::redirect_admin($fileKey, 'Section diupdate. Backup: ' . $backup);
            }

            if ($action === 'delete') {
                $sectionId = sanitize_text_field((string) ($_POST['section_id'] ?? ''));
                $target = null;
                foreach ($sections as $section) {
                    if ($section['id'] === $sectionId) {
                        $target = $section;
                        break;
                    }
                }
                if (!$target) {
                    throw new RuntimeException('Section tidak ditemukan.');
                }

                $backup = self::backup_file($filename, $html);
                $updated = self::replace_range($html, $target['start'], $target['end'], '');
                self::write_file($path, $updated);
                self::redirect_admin($fileKey, 'Section dihapus. Backup: ' . $backup);
            }

            if ($action === 'create') {
                $newLabel = trim((string) ($_POST['new_label'] ?? 'New Section'));
                $newContent = (string) ($_POST['new_content'] ?? '');
                $beforeId = sanitize_text_field((string) ($_POST['insert_before'] ?? ''));
                $withComments = isset($_POST['wrap_comments']) && $_POST['wrap_comments'] === '1';

                if (trim($newContent) === '') {
                    throw new RuntimeException('Konten section baru tidak boleh kosong.');
                }

                $block = $newContent;
                if ($withComments) {
                    $block = "<!-- Start {$newLabel} -->\n" . $newContent . "\n<!-- End {$newLabel} -->";
                }

                $insertPos = null;
                if ($beforeId !== '') {
                    foreach ($sections as $section) {
                        if ($section['id'] === $beforeId) {
                            $insertPos = (int) $section['start'];
                            break;
                        }
                    }
                }
                if ($insertPos === null) {
                    $bodyEnd = strripos($html, '</body>');
                    $insertPos = $bodyEnd === false ? strlen($html) : $bodyEnd;
                }

                $backup = self::backup_file($filename, $html);
                $updated = substr($html, 0, $insertPos) . "\n" . $block . "\n" . substr($html, $insertPos);
                self::write_file($path, $updated);
                self::redirect_admin($fileKey, 'Section ditambahkan. Backup: ' . $backup);
            }

            if ($action === 'update_visual') {
                $sectionId = sanitize_text_field((string) ($_POST['section_id'] ?? ''));
                $target = null;
                foreach ($sections as $section) {
                    if ($section['id'] === $sectionId) {
                        $target = $section;
                        break;
                    }
                }
                if (!$target) {
                    throw new RuntimeException('Section tidak ditemukan.');
                }

                $newSectionHtml = self::apply_visual_updates($target['html'], $_POST, $_FILES);
                $backup = self::backup_file($filename, $html);
                $updated = self::replace_range($html, $target['start'], $target['end'], $newSectionHtml);
                self::write_file($path, $updated);
                self::redirect_admin($fileKey, 'Visual update berhasil. Backup: ' . $backup);
            }

            if ($action === 'update_translations') {
                $sectionId = sanitize_text_field((string) ($_POST['section_id'] ?? ''));
                $target = null;
                foreach ($sections as $section) {
                    if ($section['id'] === $sectionId) {
                        $target = $section;
                        break;
                    }
                }
                if (!$target) {
                    throw new RuntimeException('Section tidak ditemukan.');
                }

                $newEn = (string) ($_POST['translation_en'] ?? '');
                $newId = (string) ($_POST['translation_id'] ?? '');
                $newSectionHtml = self::apply_translation_updates_to_section($target['html'], $newEn, $newId);
                $backup = self::backup_file($filename, $html);
                $updated = self::replace_range($html, $target['start'], $target['end'], $newSectionHtml);
                self::write_file($path, $updated);
                self::redirect_admin($fileKey, 'Bilingual translations berhasil diupdate. Backup: ' . $backup);
            }
        } catch (Throwable $e) {
            self::redirect_admin($fileKey, $e->getMessage(), true);
        }
    }

    private static function redirect_admin(string $fileKey, string $message, bool $isError = false): void
    {
        $url = add_query_arg(
            [
                'page' => self::PAGE_SLUG,
                'file' => $fileKey,
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

        $base = self::active_base_path();
        $files = self::get_files_for_base($base);
        $fileKey = isset($_GET['file']) ? self::sanitize_file_key((string) $_GET['file']) : 'index';
        $settings = self::get_simple_settings();
        $schema = self::simple_schema($fileKey);
        $path = $files[$fileKey]['path'];
        $filename = $files[$fileKey]['label'];
        $html = '';
        $sectionsList = [];
        $readError = '';

        try {
            $html = self::read_file($path);
            $sectionsList = self::detect_sections($html);
        } catch (Throwable $e) {
            $readError = $e->getMessage();
        }

        $activeSectionId = isset($_GET['section']) ? sanitize_text_field((string) $_GET['section']) : '';
        if ($activeSectionId === '' && !empty($sectionsList)) {
            $activeSectionId = (string) $sectionsList[0]['id'];
        }
        $activeSection = null;
        foreach ($sectionsList as $section) {
            if ((string) $section['id'] === $activeSectionId) {
                $activeSection = $section;
                break;
            }
        }

        $visualData = null;
        $visualError = '';
        $translationData = null;
        if ($activeSection) {
            try {
                $visualData = self::extract_visual_fields((string) $activeSection['html']);
            } catch (Throwable $e) {
                $visualError = $e->getMessage();
            }
            $translationData = self::extract_js_translations((string) $activeSection['html']);
        }

        $msg = isset($_GET['msg']) ? rawurldecode((string) $_GET['msg']) : '';
        $type = isset($_GET['type']) && $_GET['type'] === 'error' ? 'error' : 'success';
        ?>
        <div class="wrap">
            <h1>Raun Sumatra Full Section CMS</h1>
            <p>Shortcode Elementor yang dipakai: <code>[raunsumatra_index]</code> untuk halaman utama, <code>[raunsumatra_wisata_sumbar]</code> untuk halaman wisata-sumbar.</p>
            <p>Editor ini mencakup semua section per halaman, termasuk upload gambar langsung dari file.</p>

            <style>
                .rsesc-grid{display:grid;grid-template-columns:280px 1fr;gap:16px;max-width:1400px;}
                .rsesc-panel{background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:12px;}
                .rsesc-list{display:grid;gap:8px;}
                .rsesc-item{display:block;padding:10px 12px;border:1px solid #dcdcde;border-radius:8px;text-decoration:none;color:#1d2327;background:#fff;}
                .rsesc-item.active{background:#e7f3ff;border-color:#2271b1;color:#135e96;font-weight:600;}
                .rsesc-section-title{margin:0 0 10px;}
                .rsesc-field-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;}
                .rsesc-card{border:1px solid #dcdcde;border-radius:10px;padding:12px;margin-bottom:12px;background:#fff;}
                .rsesc-image-preview{width:100%;max-height:180px;object-fit:cover;border-radius:8px;border:1px solid #dcdcde;background:#f6f7f7;}
                .rsesc-help{font-size:12px;color:#50575e;}
                @media(max-width:1000px){.rsesc-grid{grid-template-columns:1fr;}.rsesc-field-grid{grid-template-columns:1fr;}}
            </style>

            <?php if ($msg !== ''): ?>
                <div class="notice notice-<?php echo esc_attr($type === 'error' ? 'error' : 'success'); ?> is-dismissible">
                    <p><?php echo esc_html($msg); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($readError !== ''): ?>
                <div class="notice notice-error"><p><?php echo esc_html($readError); ?></p></div>
            <?php endif; ?>

            <form method="post" style="max-width:900px;margin-bottom:18px;padding:12px;background:#fff;border:1px solid #dcdcde;">
                <?php wp_nonce_field(self::NONCE_ACTION, 'rsesc_nonce'); ?>
                <input type="hidden" name="rsesc_action" value="save_path">
                <input type="hidden" name="file_key" value="<?php echo esc_attr($fileKey); ?>">
                <h2 style="margin-top:0;">Konfigurasi Path</h2>
                <p><label><strong>Base Path Folder HTML</strong></label></p>
                <input type="text" name="base_path" value="<?php echo esc_attr(self::active_base_path()); ?>" style="width:100%;max-width:880px;">
                <p>
                    <button class="button button-primary" type="submit">Simpan Path</button>
                </p>
            </form>

            <form method="post" style="max-width:900px;margin-bottom:18px;">
                <?php wp_nonce_field(self::NONCE_ACTION, 'rsesc_nonce'); ?>
                <input type="hidden" name="rsesc_action" value="load_defaults">
                <input type="hidden" name="file_key" value="<?php echo esc_attr($fileKey); ?>">
                <button class="button" type="submit">Muat Ulang Template Default</button>
            </form>

            <h2 class="nav-tab-wrapper">
                <?php foreach ($files as $k => $f): ?>
                    <a class="nav-tab <?php echo $k === $fileKey ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url(add_query_arg(['page' => self::PAGE_SLUG, 'file' => $k], admin_url('admin.php'))); ?>">
                        <?php echo esc_html($f['label']); ?>
                    </a>
                <?php endforeach; ?>
            </h2>

            <div class="rsesc-grid">
                <div class="rsesc-panel">
                    <h3 class="rsesc-section-title">Daftar Section</h3>
                    <div class="rsesc-list">
                        <?php if (!$sectionsList): ?>
                            <div class="rsesc-help">Belum ada section terdeteksi.</div>
                        <?php else: ?>
                            <?php foreach ($sectionsList as $section): ?>
                                <?php
                                $isActive = (string) $section['id'] === (string) $activeSectionId;
                                $url = add_query_arg(
                                    [
                                        'page' => self::PAGE_SLUG,
                                        'file' => $fileKey,
                                        'section' => (string) $section['id'],
                                    ],
                                    admin_url('admin.php')
                                );
                                ?>
                                <a class="rsesc-item <?php echo $isActive ? 'active' : ''; ?>" href="<?php echo esc_url($url); ?>">
                                    <?php echo esc_html((string) $section['label']); ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <hr>
                    <h4 style="margin:0 0 8px;">Tambah Section Baru</h4>
                    <form method="post">
                        <?php wp_nonce_field(self::NONCE_ACTION, 'rsesc_nonce'); ?>
                        <input type="hidden" name="rsesc_action" value="create">
                        <input type="hidden" name="file_key" value="<?php echo esc_attr($fileKey); ?>">
                        <p style="margin:0 0 8px;">
                            <label><strong>Label</strong></label><br>
                            <input type="text" name="new_label" style="width:100%;" value="New Section">
                        </p>
                        <p style="margin:0 0 8px;">
                            <label><strong>Insert Before</strong></label><br>
                            <select name="insert_before" style="width:100%;">
                                <option value="">Akhir body</option>
                                <?php foreach ($sectionsList as $section): ?>
                                    <option value="<?php echo esc_attr((string) $section['id']); ?>"><?php echo esc_html((string) $section['label']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                        <p style="margin:0 0 8px;">
                            <label><input type="checkbox" name="wrap_comments" value="1" checked> Wrap dengan komentar Start/End</label>
                        </p>
                        <p style="margin:0 0 8px;">
                            <textarea name="new_content" rows="7" style="width:100%;" placeholder="<section>...</section>"></textarea>
                        </p>
                        <button class="button" type="submit">Tambah Section</button>
                    </form>
                </div>

                <div class="rsesc-panel">
                    <?php if (!$activeSection): ?>
                        <p>Pilih section di kiri untuk mulai edit.</p>
                    <?php else: ?>
                        <h3 style="margin-top:0;"><?php echo esc_html((string) $activeSection['label']); ?></h3>
                        <p class="rsesc-help">File: <code><?php echo esc_html($filename); ?></code> | ID: <code><?php echo esc_html((string) $activeSection['id']); ?></code></p>

                        <?php if ($visualError !== ''): ?>
                            <div class="notice notice-error"><p><?php echo esc_html($visualError); ?></p></div>
                        <?php endif; ?>

                        <?php if (is_array($visualData)): ?>
                            <form method="post" enctype="multipart/form-data">
                                <?php wp_nonce_field(self::NONCE_ACTION, 'rsesc_nonce'); ?>
                                <input type="hidden" name="rsesc_action" value="update_visual">
                                <input type="hidden" name="file_key" value="<?php echo esc_attr($fileKey); ?>">
                                <input type="hidden" name="section_id" value="<?php echo esc_attr((string) $activeSection['id']); ?>">

                                <h4>Text Content</h4>
                                <?php foreach (($visualData['text_items'] ?? []) as $item): ?>
                                    <?php $tid = (int) ($item['id'] ?? -1); ?>
                                    <div class="rsesc-card">
                                        <p style="margin:0 0 6px;"><strong><?php echo esc_html(strtoupper((string) ($item['tag'] ?? 'text'))); ?></strong></p>
                                        <textarea name="text_value[<?php echo esc_attr((string) $tid); ?>]" rows="3" style="width:100%;"><?php echo esc_textarea((string) ($item['value'] ?? '')); ?></textarea>
                                    </div>
                                <?php endforeach; ?>

                                <h4>Images</h4>
                                <?php foreach (($visualData['image_items'] ?? []) as $item): ?>
                                    <?php $iid = (int) ($item['id'] ?? -1); ?>
                                    <div class="rsesc-card">
                                        <div class="rsesc-field-grid">
                                            <div>
                                                <img class="rsesc-image-preview" src="<?php echo esc_url((string) ($item['src'] ?? '')); ?>" alt="">
                                            </div>
                                            <div>
                                                <p style="margin:0 0 8px;">
                                                    <label><strong>Mode</strong></label><br>
                                                    <select name="image_mode[<?php echo esc_attr((string) $iid); ?>]" style="width:100%;">
                                                        <option value="keep">Keep</option>
                                                        <option value="url">Ganti URL</option>
                                                        <option value="upload">Upload File</option>
                                                        <option value="remove">Hapus gambar</option>
                                                    </select>
                                                </p>
                                                <p style="margin:0 0 8px;">
                                                    <label><strong>URL Gambar</strong></label><br>
                                                    <input type="text" name="image_url[<?php echo esc_attr((string) $iid); ?>]" value="<?php echo esc_attr((string) ($item['src'] ?? '')); ?>" style="width:100%;">
                                                </p>
                                                <p style="margin:0 0 8px;">
                                                    <label><strong>Upload File</strong></label><br>
                                                    <input type="file" name="image_file_<?php echo esc_attr((string) $iid); ?>" accept="image/*">
                                                </p>
                                                <p style="margin:0;">
                                                    <label><strong>Alt</strong></label><br>
                                                    <input type="text" name="image_alt[<?php echo esc_attr((string) $iid); ?>]" value="<?php echo esc_attr((string) ($item['alt'] ?? '')); ?>" style="width:100%;">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <details class="rsesc-card">
                                    <summary><strong>Attribute Editor (advanced)</strong></summary>
                                    <?php foreach (($visualData['attr_items'] ?? []) as $item): ?>
                                        <?php $aid = (int) ($item['id'] ?? -1); ?>
                                        <p style="margin:10px 0;">
                                            <label><strong><?php echo esc_html((string) ($item['tag'] ?? 'node') . ' [' . (string) ($item['name'] ?? 'attr') . ']'); ?></strong></label><br>
                                            <input type="text" name="attr_value[<?php echo esc_attr((string) $aid); ?>]" value="<?php echo esc_attr((string) ($item['value'] ?? '')); ?>" style="width:100%;">
                                        </p>
                                    <?php endforeach; ?>
                                </details>

                                <details class="rsesc-card">
                                    <summary><strong>Tambah Gambar Baru</strong></summary>
                                    <p style="margin:10px 0;">
                                        <label><strong>Mode</strong></label><br>
                                        <select name="add_image_mode" style="width:100%;">
                                            <option value="">Tidak menambah</option>
                                            <option value="url">Tambah via URL</option>
                                            <option value="upload">Tambah via Upload File</option>
                                        </select>
                                    </p>
                                    <p style="margin:10px 0;">
                                        <label><strong>URL</strong></label><br>
                                        <input type="text" name="add_image_url" style="width:100%;">
                                    </p>
                                    <p style="margin:10px 0;">
                                        <label><strong>Upload File</strong></label><br>
                                        <input type="file" name="add_image_file" accept="image/*">
                                    </p>
                                    <p style="margin:10px 0;">
                                        <label><strong>Alt</strong></label><br>
                                        <input type="text" name="add_image_alt" style="width:100%;">
                                    </p>
                                </details>

                                <p><button class="button button-primary" type="submit">Update Section Visual</button></p>
                            </form>
                        <?php endif; ?>

                        <?php if (is_array($translationData)): ?>
                            <form method="post" class="rsesc-card">
                                <?php wp_nonce_field(self::NONCE_ACTION, 'rsesc_nonce'); ?>
                                <input type="hidden" name="rsesc_action" value="update_translations">
                                <input type="hidden" name="file_key" value="<?php echo esc_attr($fileKey); ?>">
                                <input type="hidden" name="section_id" value="<?php echo esc_attr((string) $activeSection['id']); ?>">
                                <h4 style="margin-top:0;">Bilingual `translations` Object</h4>
                                <p><label><strong>EN object</strong></label><br>
                                    <textarea name="translation_en" rows="10" style="width:100%;font-family:monospace;"><?php echo esc_textarea((string) ($translationData['en']['value'] ?? '{}')); ?></textarea>
                                </p>
                                <p><label><strong>ID object</strong></label><br>
                                    <textarea name="translation_id" rows="10" style="width:100%;font-family:monospace;"><?php echo esc_textarea((string) ($translationData['id']['value'] ?? '{}')); ?></textarea>
                                </p>
                                <p><button class="button" type="submit">Update Translations EN/ID</button></p>
                            </form>
                        <?php endif; ?>

                        <form method="post" class="rsesc-card">
                            <?php wp_nonce_field(self::NONCE_ACTION, 'rsesc_nonce'); ?>
                            <input type="hidden" name="rsesc_action" value="update">
                            <input type="hidden" name="file_key" value="<?php echo esc_attr($fileKey); ?>">
                            <input type="hidden" name="section_id" value="<?php echo esc_attr((string) $activeSection['id']); ?>">
                            <h4 style="margin-top:0;">Raw HTML Editor</h4>
                            <textarea name="section_html" rows="14" style="width:100%;font-family:monospace;"><?php echo esc_textarea((string) $activeSection['html']); ?></textarea>
                            <p><button class="button" type="submit">Update Raw HTML</button></p>
                        </form>

                        <form method="post" onsubmit="return confirm('Hapus section ini?')">
                            <?php wp_nonce_field(self::NONCE_ACTION, 'rsesc_nonce'); ?>
                            <input type="hidden" name="rsesc_action" value="delete">
                            <input type="hidden" name="file_key" value="<?php echo esc_attr($fileKey); ?>">
                            <input type="hidden" name="section_id" value="<?php echo esc_attr((string) $activeSection['id']); ?>">
                            <button class="button button-link-delete" type="submit">Delete Section</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <hr style="margin:22px 0;">
            <h2>Simple Quick Editor (opsional)</h2>
            <form method="post" style="background:#fff;border:1px solid #dcdcde;padding:14px;max-width:1200px;">
                <?php wp_nonce_field(self::NONCE_ACTION, 'rsesc_nonce'); ?>
                <input type="hidden" name="rsesc_action" value="save_simple">
                <input type="hidden" name="file_key" value="<?php echo esc_attr($fileKey); ?>">
                <?php
                $quickSections = [];
                foreach (['shared', 'en', 'id'] as $langKey) {
                    foreach (($schema[$langKey] ?? []) as $field) {
                        $sectionName = (string) ($field['section'] ?? 'General');
                        if (!isset($quickSections[$sectionName])) {
                            $quickSections[$sectionName] = [];
                        }
                        $quickSections[$sectionName][$langKey][] = $field;
                    }
                }
                ?>
                <?php foreach ($quickSections as $sectionName => $langGroups): ?>
                    <div style="margin-bottom:14px;padding:12px;border:1px solid #dcdcde;border-radius:8px;background:#fff;">
                        <h3 style="margin:0 0 10px;"><?php echo esc_html($sectionName); ?></h3>
                        <?php foreach (['shared' => 'General', 'en' => 'English (EN)', 'id' => 'Indonesia (ID)'] as $langKey => $langLabel): ?>
                            <?php if (!empty($langGroups[$langKey])): ?>
                                <h4 style="margin:10px 0 8px;"><?php echo esc_html($langLabel); ?></h4>
                                <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;">
                                    <?php foreach ($langGroups[$langKey] as $field): ?>
                                        <?php $fid = (string) ($field['id'] ?? ''); ?>
                                        <?php $value = self::get_simple_field_value($settings, $fileKey, $langKey, $field); ?>
                                        <p style="margin:0;">
                                            <label><strong><?php echo esc_html((string) ($field['label'] ?? $fid)); ?></strong></label><br>
                                            <?php if (($field['type'] ?? 'text') === 'textarea'): ?>
                                                <textarea name="simple[<?php echo esc_attr($langKey); ?>][<?php echo esc_attr($fid); ?>]" rows="3" style="width:100%;"><?php echo esc_textarea($value); ?></textarea>
                                            <?php else: ?>
                                                <input type="text" name="simple[<?php echo esc_attr($langKey); ?>][<?php echo esc_attr($fid); ?>]" value="<?php echo esc_attr($value); ?>" style="width:100%;">
                                            <?php endif; ?>
                                        </p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
                <p><button class="button button-primary" type="submit">Simpan Quick Editor</button></p>
            </form>

            <form method="post" style="margin-top:10px;max-width:1200px;">
                <?php wp_nonce_field(self::NONCE_ACTION, 'rsesc_nonce'); ?>
                <input type="hidden" name="rsesc_action" value="reset_simple">
                <input type="hidden" name="file_key" value="<?php echo esc_attr($fileKey); ?>">
                <button class="button button-secondary" type="submit">Reset Quick Editor</button>
            </form>
        </div>
        <?php
    }

    private static function get_html_by_file_key(string $fileKey): string
    {
        $files = self::get_files_for_base(self::active_base_path());
        if (!isset($files[$fileKey])) {
            return '';
        }

        try {
            return self::read_file($files[$fileKey]['path']);
        } catch (Throwable $e) {
            return '';
        }
    }

    private static function body_inner_html(string $html): string
    {
        if (preg_match('/<body[^>]*>(.*)<\/body>/is', $html, $m)) {
            return (string) $m[1];
        }
        return $html;
    }

    public static function shortcode_page(array $atts): string
    {
        $atts = shortcode_atts([
            'file' => 'index',
            'body_only' => '1',
        ], $atts, 'rs_page');

        $fileKey = self::sanitize_file_key((string) $atts['file']);
        $html = self::get_html_by_file_key($fileKey);
        if ($html === '') {
            return '';
        }

        $override = self::simple_override_script($fileKey);
        self::collect_assets_from_html($html);
        if ($override !== '') {
            self::collect_footer_script($override);
        }

        $bodyOnly = self::body_inner_html($html);
        $bodyOnly = self::strip_embedded_assets($bodyOnly);

        if ($atts['body_only'] === '1') {
            return $bodyOnly;
        }
        $cleanFull = self::strip_embedded_assets($html);
        return $cleanFull;
    }

    public static function shortcode_section(array $atts): string
    {
        $atts = shortcode_atts([
            'file' => 'index',
            'id' => '',
        ], $atts, 'rs_section');

        $fileKey = self::sanitize_file_key((string) $atts['file']);
        $id = sanitize_text_field((string) $atts['id']);
        if ($id === '') {
            return '';
        }

        $html = self::get_html_by_file_key($fileKey);
        if ($html === '') {
            return '';
        }

        $sections = self::detect_sections($html);
        foreach ($sections as $section) {
            if ($section['id'] === $id) {
                return $section['html'];
            }
        }

        return '';
    }

    public static function shortcode_index(array $atts = []): string
    {
        return self::shortcode_page([
            'file' => 'index',
            'body_only' => isset($atts['body_only']) ? (string) $atts['body_only'] : '1',
        ]);
    }

    public static function shortcode_wisata_sumbar(array $atts = []): string
    {
        return self::shortcode_page([
            'file' => 'wisata-sumbar',
            'body_only' => isset($atts['body_only']) ? (string) $atts['body_only'] : '1',
        ]);
    }
}

RS_Elementor_Shortcode_CMS::init();
