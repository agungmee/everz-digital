# Raun Sumatra Elementor Shortcode CMS

Plugin WordPress untuk:
- CRUD section `index.html` dan `wisata-sumbar.html` dari wp-admin
- Render ke Elementor via shortcode
- Simple CMS editor dibagi per section (Navbar, Hero, Paket, Contact, dll): logo, menu bilingual, paket, harga, deskripsi, gambar utama

## File plugin
- `sites/raunsumatra/raunsumatra-elementor-shortcode-cms.php`
- `sites/raunsumatra/templates/index.html`
- `sites/raunsumatra/templates/wisata-sumbar.html`

## Cara pasang
1. Copy file plugin ke folder:
   - `wp-content/plugins/raunsumatra-elementor-shortcode-cms/raunsumatra-elementor-shortcode-cms.php`
2. Aktifkan plugin di WordPress.
3. Buka menu admin:
   - `Raun Sumatra CMS`
4. Set `Base Path Folder HTML` ke folder yang berisi:
   - `index.html`
   - `wisata-sumbar.html`

Plugin versi ini otomatis seed template default ke:
- `wp-content/uploads/rsesc-pages/`
Jadi saat aktivasi, section langsung terisi.

## Shortcode Elementor
- Full page body:
  - `[rs_page file="index"]`
  - `[rs_page file="wisata-sumbar"]`
- Shortcode praktis:
  - `[raunsumatra_index]`
  - `[raunsumatra_wisata_sumbar]`
- Section tertentu:
  - `[rs_section file="index" id="navbar-section-raun-sumatra-tour-travel-1"]`
  - ID section bisa dilihat di panel admin plugin.

## Catatan
- Setiap aksi create/update/delete otomatis backup ke:
  - `wp-content/uploads/rs-section-backups/`
- Semua field otomatis terisi default dari data existing; tinggal ubah jika perlu.
- Ada reset override untuk kembali ke default bawaan konten saat ini.
- Tampilan akan tetap sama selama HTML/class section tidak diubah struktur visualnya.
