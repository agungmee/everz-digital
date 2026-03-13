# Raun Sumatra Section CRUD Plugin

Panel admin lokal untuk CRUD section HTML di:
- `sites/raunsumatra/index.html`
- `sites/raunsumatra/wisata-sumbar.html`

## File plugin
- `sites/raunsumatra/section-crud-plugin.php`

## Jalankan
```bash
php -S 127.0.0.1:8080 -t /home/devsant/website_project/sites/raunsumatra
```
Buka:
`http://127.0.0.1:8080/section-crud-plugin.php`

## Fitur
- Read: daftar section otomatis.
  - `index.html` diparse dari marker `<!-- Start ... --> ... <!-- End ... -->`.
  - `wisata-sumbar.html` fallback parse dari tag `<header>`, `<section>`, `<footer>`.
- Update: edit HTML full block section.
- Create: tambah section baru (bisa auto-wrap marker Start/End).
- Delete: hapus section.
- Backup otomatis tiap aksi ke folder:
  - `sites/raunsumatra/.section-backups/`

## Catatan penting
- Tampilan frontend tetap sama selama struktur/class section yang diedit tidak diubah.
- Plugin ini tidak menyentuh CSS/JS global kecuali jika kamu edit manual lewat textarea.
