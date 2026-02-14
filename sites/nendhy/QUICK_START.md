# 🎯 QUICK START GUIDE - Nendhy Project Structure

## Visualisasi Alur Kerja

```
┌─────────────────────────────────────────────────────┐
│              index.html (dibuka user)               │
│  - CSS semua ada disini                             │
│  - Placeholder divs untuk setiap section            │
│  - <script src="sections-loader.js"></script>       │
└────────────────────┬────────────────────────────────┘
                     │
                     │ Otomatis load saat page open
                     ↓
┌─────────────────────────────────────────────────────┐
│          sections-loader.js (main orchestrator)     │
│  ┌─────────────────────────────────────────────┐   │
│  │ function loadSections():                    │   │
│  │  - Fetch sections/hero.html                 │   │
│  │  - Fetch sections/about.html                │   │
│  │  - Fetch sections/why-choose.html           │   │
│  │  - Fetch sections/motorbike.html            │   │
│  │  - Fetch sections/contact.html              │   │
│  │  - Fetch sections/testimonials.html         │   │
│  │  - Fetch sections/footer.html               │   │
│  │  - Fetch sections/modals.html               │   │
│  └─────────────────────────────────────────────┘   │
│  ┌─────────────────────────────────────────────┐   │
│  │ function initializeScripts():                │   │
│  │  - Run semua JavaScript functionality       │   │
│  │  - Slider, modal, nav, carousel, etc        │   │
│  └─────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────┘
                     │
                     │ Replace placeholder dengan content
                     ↓
┌──────────────────────────────────────────────────────────────┐
│                   Halaman Render Sempurna                    │
│ <div id="hero-placeholder"> → HTML dari hero.html            │
│ <div id="about-placeholder"> → HTML dari about.html          │
│ <div id="why-placeholder"> → HTML dari why-choose.html       │
│ ... dan seterusnya ...                                       │
└──────────────────────────────────────────────────────────────┘
```

---

## 📂 File Structure Detail

### **Root Level (index.html)**
Berisi:
- ✅ All CSS (dari original index.html)
- ✅ Placeholder divs untuk setiap section
- ✅ Link ke sections-loader.js

### **Sections Folder**
Setiap file hanya berisi HTML content, TANPA CSS atau script
- `hero.html` - Header, navbar, hero intro
- `about.html` - About section
- `why-choose.html` - 4 kartu why choose
- `motorbike.html` - Motorbike carousel
- `contact.html` - Contact form & map
- `testimonials.html` - Customer reviews carousel
- `footer.html` - Footer
- `modals.html` - Booking modals (package & transfer)

### **sections-loader.js**
Orchestrator utama yang:
- Fetch semua section files
- Replace placeholders
- Run semua functionality JavaScript

---

## 💻 Cara Edit

### **Scenario 1: Edit About Section**
```
1. Open: sections/about.html
2. Edit HTML
3. Save
4. Refresh browser → Perubahan langsung terlihat
```

### **Scenario 2: Update Motorbike Harga**
```
1. Open: sections/motorbike.html
2. Find <li><span>One Day</span><b>Rp XXX</b></li>
3. Update harga
4. Save
5. Refresh → Harga updated
```

### **Scenario 3: Tambah Testimoni Baru**
```
1. Open: sections/testimonials.html
2. Copy-paste satu <article class="aha-testimonial-card">
3. Edit nama & review
4. Save
5. Refresh → Testimoni baru ada (auto carousel update)
```

### **Scenario 4: Tambah Section Baru (misal Pricing)**
```
1. Create: sections/pricing.html
   └─ Paste HTML content section

2. Edit: sections-loader.js
   └─ Add ke array: { id: 'pricing-placeholder', file: 'sections/pricing.html' }

3. Edit: index.html
   └─ Add placeholder: <div id="pricing-placeholder"></div>

4. Refresh browser → Pricing section appear!
```

---

## 🔧 JavaScript Functionality

Semua functionality ada di **sections-loader.js** dalam fungsi **initializeScripts()**:

✅ Hero slider automation  
✅ About slider animation  
✅ Navbar scroll effect  
✅ Bike carousel infinite loop  
✅ Testimonial carousel auto-scroll  
✅ Mobile menu toggle  
✅ Modal forms (package booking, airport transfer)  

Setiap functionality otomatis berjalan setelah sections loaded.

---

## 📊 Before vs After

| Aspek | Sebelum | Sesudah |
|-------|---------|--------|
| Total lines | 2,962 | Split: 1,524 + 360 + sections |
| Edit file | 1 besar | Max 150 lines per section |
| Find section | Scroll panjang | Direct open file |
| Add section | Risky merge | Safe, file baru |
| Team work | Merge conflict | No conflict |
| Debug | Sulit | Mudah isolate |

---

## 🚀 Tips Maintenance

1. **Always backup** sections sebelum edit besar
2. **Test mobile** setelah update hero atau sections
3. **Check console** (F12) untuk error saat load
4. **Don't edit** sections-loader.js kecuali ada alasan
5. **Update README.md** jika struktur berubah
6. **Comment code** jika ada JavaScript tambahan

---

## ✨ Kesimpulan

Project nendhy sekarang:
- **Clean** - Struktur jelas dan organized
- **Scalable** - Mudah tambah section baru
- **Maintainable** - Edit hanya bagian yang perlu
- **Collaborative** - Tim bisa work on different sections
- **Professional** - Ready for production & team collaboration

Happy coding! 🎉
