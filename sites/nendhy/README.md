# Nendhy Holiday Lombok - Project Structure

## Deskripsi
Project ini telah di-refactor untuk memisahkan setiap section menjadi file HTML terpisah untuk lebih mudah dikelola, dikembangkan, dan dimaintain.

## Struktur Folder

```
nendhy/
├── index.html              # Main file dengan style dan placeholder untuk sections
├── sections-loader.js      # Script untuk load semua sections secara dinamis
└── sections/               # Folder berisi semua section HTML terpisah
    ├── hero.html           # Hero section (header + navbar + intro)
    ├── about.html          # About section
    ├── why-choose.html     # Why Choose section
    ├── motorbike.html      # Motorbike rental section
    ├── contact.html        # Contact section
    ├── testimonials.html   # Customer testimonials section
    ├── footer.html         # Footer
    └── modals.html         # Modal forms (Package booking & Airport transfer)
```

## Cara Kerja

1. **index.html** memuat semua CSS dan struktur dasar halaman
2. Setiap section diganti dengan placeholder `<div id="*-placeholder"></div>`
3. **sections-loader.js** secara otomatis:
   - Mengambil (fetch) setiap file HTML dari folder `sections/`
   - Mengganti placeholder dengan konten asli
   - Menjalankan semua JavaScript functionality setelah sections loaded

## Keuntungan Struktur Baru

✅ **Mudah dikelola** - Setiap section terpisah menjadi file sendiri  
✅ **Mudah diupdate** - Edit hanya bagian yang perlu tanpa khawatir syntax error di bagian lain  
✅ **Reusable** - Section bisa digunakan di halaman lain dengan copy-paste  
✅ **Collaboratif** - Tim bisa work on different sections tanpa conflict  
✅ **Loading dinamis** - Bisa di-conditional load atau lazy load jika diperlukan di masa depan  

## Cara Edit

### Mengedit section tertentu:
1. Buka file section yang ingin diedit di folder `sections/`
   - Contoh: `sections/about.html` untuk bagian About
2. Edit HTML sesuai kebutuhan
3. Simpan file
4. Refresh halaman untuk melihat perubahan

### Menambah section baru:
1. Buat file HTML baru di folder `sections/` (contoh: `pricing.html`)
2. Tulis HTML content section
3. Buka `sections-loader.js` dan tambahkan ke array sections:
   ```javascript
   const sections = [
     // ... existing sections ...
     { id: 'pricing-placeholder', file: 'sections/pricing.html' }
   ];
   ```
4. Di `index.html`, tambahkan placeholder:
   ```html
   <div id="pricing-placeholder"></div>
   ```

### Mengedit JavaScript:
- Semua functionality JavaScript ada di `sections-loader.js`
- Edit function di section `initializeScripts()` sesuai kebutuhan
- Script otomatis berjalan setelah semua sections loaded

## Browser Compatibility

Menggunakan:
- **Fetch API** untuk load HTML (supported di semua modern browsers)
- **ES6 syntax** - Pastikan browser support atau gunakan transpiler jika perlu

## Notes

- Pastikan paths di `sections-loader.js` sesuai dengan struktur folder
- Setiap section file hanya berisi HTML content, CSS sudah ada di `index.html`
- Modals dan scripts sudah terpusat, jadi lebih mudah untuk debug dan maintain
