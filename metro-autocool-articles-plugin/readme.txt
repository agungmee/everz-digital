=== Metro Autocool Articles Plugin ===
Contributors: Metro Autocool
Requires at least: 5.0
Requires PHP: 7.4
Tested up to: 6.4
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.txt

== Description ==

Plugin untuk menampilkan artikel Metro Autocool dengan tampilan yang elegan dan responsive. Plugin ini menyediakan shortcode dan Elementor widget yang dapat digunakan untuk menampilkan artikel dengan styling yang sama persis seperti di website asli.

Fitur:
- Shortcode: [metro_articles]
- Elementor Widget: Metro Autocool Articles
- Fully responsive design
- Customizable columns (1-4)
- Category filtering
- Reading time estimation
- Smooth hover effects

== Installation ==

1. Upload folder `metro-autocool-articles-plugin` ke direktori `/wp-content/plugins/`
2. Activate plugin melalui menu 'Plugins' di WordPress admin panel
3. Gunakan shortcode [metro_articles] di halaman/post atau gunakan Elementor widget

== Usage ==

=== Shortcode ===

Basic usage:
[metro_articles]

Advanced usage dengan parameter:
[metro_articles title="Artikel Terbaru" limit="3" columns="3" category="blog"]

Parameter:
- title: Judul section (default: "Artikel")
- limit: Jumlah artikel yang ditampilkan (default: 3, max: 12)
- columns: Jumlah kolom (1, 2, 3, atau 4) (default: 3)
- category: Slug kategori untuk filter (default: "" untuk semua)

=== Elementor Widget ===

1. Edit halaman dengan Elementor
2. Cari widget "Metro Autocool Articles"
3. Drag widget ke halaman
4. Customize pengaturan sesuai kebutuhan:
   - Judul
   - Jumlah Artikel
   - Jumlah Kolom
   - Kategori
   - Warna Judul
   - Warna Aksen

== Features ==

✓ Full WordPress integration
✓ Elementor widget support
✓ Responsive design
✓ Auto reading time calculation
✓ Featured image support
✓ Category filtering
✓ Customizable styling
✓ Smooth animations
✓ Mobile optimized

== CSS Classes ==

Untuk custom styling, Anda bisa menggunakan class berikut:

- `.mc-articles` - Main container
- `.mc-articles-head` - Title
- `.mc-articles-grid` - Grid container
- `.mc-article-card` - Article card
- `.mc-article-body` - Card content
- `.mc-article-meta` - Article meta info
- `.mc-article-title` - Article title
- `.mc-article-excerpt` - Article excerpt
- `.mc-article-link` - Read more link

== Requirements ==

- WordPress 5.0+
- PHP 7.4+
- Elementor (opsional, untuk menggunakan widget)

== Changelog ==

= 1.0.0 =
* Initial release
* Shortcode support
* Elementor widget support
* Responsive design

== Support ==

Untuk pertanyaan atau laporan bug, silakan hubungi support di https://acmobilsurabaya.com
