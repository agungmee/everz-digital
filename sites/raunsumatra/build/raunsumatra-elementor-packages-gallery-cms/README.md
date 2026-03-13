# Raun Sumatra Elementor Packages & Gallery CMS

Plugin WordPress khusus untuk **CRUD Paket + Gallery** dengan dukungan **bilingual EN/ID** dan output ke Elementor via shortcode.

## File plugin
- `sites/raunsumatra/raunsumatra-elementor-packages-gallery-cms.php`

## Fitur
- CRUD Paket:
  - tambah, edit, hapus, urutkan item
  - field bilingual: nama, subtitle, harga, tombol detail, tombol booking
  - URL detail & URL booking per item
- CRUD Gallery:
  - tambah, edit, hapus, urutkan item
  - URL gambar + alt EN/ID
- Section copy bilingual:
  - chip/title/description untuk section Paket
  - chip/title/description untuk section Gallery
- Integrasi Elementor via shortcode

## Shortcode Elementor
- Paket saja:
  - `[rs_paket_section]`
- Gallery saja:
  - `[rs_gallery_section]`
- Paket + Gallery:
  - `[rs_paket_gallery]`

## Cara pakai
1. Copy file plugin ke WordPress:
   - `wp-content/plugins/raunsumatra-elementor-packages-gallery-cms/raunsumatra-elementor-packages-gallery-cms.php`
2. Aktivasi plugin di admin WordPress.
3. Buka menu admin:
   - `Raun Paket/Gallery`
4. Edit data Paket dan Gallery.
5. Di halaman Elementor, pakai widget Shortcode lalu isi salah satu shortcode di atas.

## Catatan bahasa
- Frontend mengikuti bahasa aktif dari:
  - `window.__rsLang` atau
  - `localStorage.rsLang`
- Jika tidak ada, fallback otomatis ke EN.
