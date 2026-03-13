# Metro Autocool Articles Plugin

Plugin WordPress untuk menampilkan artikel Metro Autocool dengan tampilan yang elegan dan responsive.

## 🎯 Fitur Utama

✅ **Shortcode Support** - Gunakan `[metro_articles]` di halaman/post  
✅ **Elementor Widget** - Widget siap pakai untuk Elementor builder  
✅ **Fully Responsive** - Optimal di semua ukuran layar  
✅ **Customizable Columns** - Pilih 1, 2, 3, atau 4 kolom  
✅ **Category Filtering** - Filter artikel berdasarkan kategori  
✅ **Reading Time** - Estimasi waktu baca otomatis  
✅ **Smooth Animations** - Hover effects yang elegan  

## 📦 Instalasi

1. Download/Extract folder `metro-autocool-articles-plugin`
2. Upload ke `/wp-content/plugins/`
3. Activate di WordPress admin panel
4. Mulai gunakan!

## 🚀 Cara Penggunaan

### Via Shortcode

**Basic:**
```
[metro_articles]
```

**Advanced dengan parameter:**
```
[metro_articles title="Artikel Terbaru" limit="6" columns="2" category="tips"]
```

**Parameter Tersedia:**
- `title` - Judul section (default: "Artikel")
- `limit` - Jumlah artikel (default: 3, max: 12)
- `columns` - Jumlah kolom: 1, 2, 3, atau 4 (default: 3)
- `category` - Slug kategori untuk filter (default: "" = semua)

### Via Elementor

1. Edit halaman dengan Elementor
2. Cari widget **"Metro Autocool Articles"** di panel widget
3. Drag ke halaman
4. Customize di panel control:
   - 📝 Judul
   - 📊 Jumlah Artikel
   - 📐 Jumlah Kolom
   - 📂 Kategori
   - 🎨 Warna Judul
   - 🎨 Warna Aksen

## 🎨 Styling & CSS

Plugin menggunakan class CSS yang dapat di-customize:

```css
.mc-articles              /* Main container */
.mc-articles-head         /* Title */
.mc-articles-grid         /* Grid */
.mc-article-card          /* Single article */
.mc-article-body          /* Content area */
.mc-article-title         /* Article title */
.mc-article-excerpt       /* Preview text */
.mc-article-link          /* Read more link */
```

## 💻 Requirements

- WordPress 5.0+
- PHP 7.4+
- Elementor (opsional)

## 📝 Contoh Implementasi

### Halaman Archive Artikel
```
[metro_articles title="Semua Artikel" limit="12" columns="3"]
```

### Sidebar Widget Artikel Terbaru
```
[metro_articles title="Terbaru" limit="5" columns="1"]
```

### Section Artikel Spesifik
```
[metro_articles title="Tips & Trik" limit="4" columns="2" category="tips"]
```

## 🔍 Data yang Ditampilkan

Setiap artikel menampilkan:
- ✓ Featured image
- ✓ Judul artikel
- ✓ Penulis
- ✓ Tanggal publish
- ✓ Estimasi waktu baca
- ✓ Excerpt/preview
- ✓ Link ke artikel lengkap

## 🛠️ Troubleshooting

**Artikel tidak muncul?**
- Pastikan sudah ada post yang publish di WordPress
- Check kategori filter sesuai dengan post category

**Styling tidak sesuai?**
- Clear WordPress cache
- Check browser inspector CSS loading
- Pastikan tidak ada CSS conflict dari theme lain

**Elementor widget tidak muncul?**
- Pastikan Elementor sudah di-install dan activate
- Refresh halaman Elementor editor

## 📞 Support

Untuk bantuan atau laporan bug, hubungi:
📧 Email: metroautocool512@gmail.com
🌐 Website: https://acmobilsurabaya.com

## 📄 License

GPL-2.0+ License - Free untuk personal dan commercial use

---

**Version:** 1.0.0  
**Last Updated:** February 2026  
**Author:** Metro Autocool
