document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const packageId = params.get("id");

    if (!packageId || !packagesData[packageId]) {
        document.getElementById("package-detail-container").innerHTML = `
            <div class="container text-center" style="padding: 100px 0;">
                <h2>Paket Tidak Ditemukan</h2>
                <p>Maaf, kami tidak dapat menemukan paket yang Anda cari.</p>
                <a href="index.html#paket-wisata" class="btn btn-primary">Kembali ke Paket</a>
            </div>
        `;
        return;
    }

    const data = packagesData[packageId];
    renderPackage(data);
});

function renderPackage(data) {
    const container = document.getElementById("package-detail-container");

    let starsHtml = "";
    for (let i = 0; i < data.stars; i++) {
        starsHtml += '<i class="fas fa-star" style="color: var(--accent); margin: 0 2px;"></i>';
    }

    let galleryHtml = "";
    data.gallery.forEach(img => {
        galleryHtml += `<img src="${img}" alt="${data.title}" style="width: 24%; border-radius: 8px; aspect-ratio: 4/3; object-fit: cover;">`;
    });

    let facilitiesHtml = data.facilities.map(item => `<li>✓ ${item}</li>`).join("");
    let termsHtml = data.terms.map(item => `<li>• ${item}</li>`).join("");

    const formatText = (text) => {
        if (!text) return "";
        return text
            .replace(/\n/g, '<br>')
            .replace(/>>>tourflores<<</g, `<a href="https://wa.me/6287718031430?text=Halo%20Althaf%20Ocean%20Tour,%20saya%20tertarik%20dengan%20paket%20${encodeURIComponent(data.title)}" target="_blank" style="color: #4169E1; font-weight: 700; text-decoration: underline;">KLIK DI SINI (WhatsApp)</a>`);
    };

    container.innerHTML = `
        <div class="container">
            <!-- Header Section -->
            <div class="package-header text-center" style="margin-bottom: 4rem;">
                <h1 style="color: #4A69BD; font-family: var(--font-body); font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase;">
                    ${data.title}
                </h1>
                <h2 style="color: #4A69BD; font-family: var(--font-body); font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase;">
                    ${data.subtitle}
                </h2>
                <div style="font-size: 1.2rem; margin-bottom: 2rem;">
                    ${starsHtml}
                </div>
                <div style="max-width: 1000px; margin: 0 auto; line-height: 1.8; color: var(--text-dark); text-align: justify;">
                    ${formatText(data.description)}
                </div>
                <p style="margin-top: 1.5rem; font-weight: 500;">Kami hadirkan pilihan tur terbaik untuk Anda</p>
            </div>

            <hr style="border: 0; border-top: 1px solid #ddd; margin: 3rem 0;">

            <!-- Program Section -->
            <div class="package-program" style="margin-bottom: 4rem;">
                <h3 style="color: #55E6C1; text-align: center; font-weight: 700; margin-bottom: 2rem; text-transform: uppercase;">
                    ${data.programTitle}
                </h3>
                <h4 style="font-weight: 700; margin-bottom: 1.5rem;">${data.programSubtitle}</h4>
                <div style="line-height: 1.7; color: #333; margin-bottom: 2rem;">
                    ${formatText(data.itinerary)}
                </div>
                <div class="program-gallery" style="display: flex; gap: 1%; flex-wrap: wrap; justify-content: center; margin-bottom: 3rem;">
                    ${galleryHtml}
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #ddd; margin: 3rem 0;">

            <!-- Features Section -->
            <div class="package-features" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-bottom: 4rem;">
                <div>
                    <h4 style="font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase;">FASILITAS :</h4>
                    <ul style="list-style: none; padding: 0; line-height: 2;">
                        ${facilitiesHtml}
                    </ul>
                </div>
                <div>
                    <h4 style="font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase;">Syarat dan Ketentuan :</h4>
                    <ul style="list-style: none; padding: 0; line-height: 2;">
                        ${termsHtml}
                    </ul>
                </div>
            </div>

            <hr style="border: 0; border-top: 2px solid #333; margin: 3rem 0;">

            <!-- Pricing Section -->
            <div class="package-pricing text-center" style="margin-bottom: 4rem;">
                <h3 style="color: #4A69BD; font-weight: 700; margin-bottom: 2rem; text-transform: uppercase;">
                    HARGA BERDASARKAN JUMLAH PESERTA
                </h3>
                
                <div class="price-tabs-container" style="border: 1px solid #ddd; border-radius: 4px; overflow: hidden; text-align: left;">
                    <div class="price-tabs" style="display: flex; background: #fff; border-bottom: 1px solid #ddd;">
                        <button class="price-tab active" onclick="switchTab('open-trip')" style="padding: 15px 30px; border: none; background: #fff; cursor: pointer; font-weight: 600; color: #4A69BD; border-right: 1px solid #ddd; border-top: 3px solid #4A69BD;">
                            ${data.pricing.openTrip.label}
                        </button>
                        <button class="price-tab" onclick="switchTab('private')" style="padding: 15px 30px; border: none; background: #fff; cursor: pointer; font-weight: 600; color: #333; border-right: 1px solid #ddd;">
                            ${data.pricing.private.label}
                        </button>
                    </div>
                    <div id="price-content" style="padding: 2.5rem;">
                        <!-- Tab Content -->
                        <div id="open-trip-content">
                            <p style="font-size: 1.1rem; margin-bottom: 1rem;">
                                <strong>${data.pricing.openTrip.price || ""}</strong>
                            </p>
                            <div style="line-height: 1.8; color: #333;">
                                ${formatText(data.pricing.openTrip.details)}
                            </div>
                        </div>
                        <div id="private-content" style="display: none;">
                            <div style="line-height: 1.8; color: #333;">
                                ${formatText(data.pricing.private.details)}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="promo-banner" style="background: yellow; color: #000; padding: 15px; font-weight: 900; font-size: 1.2rem; margin-top: 2rem; text-transform: uppercase; border-radius: 4px;">
                    POTONGAN HARGA HINGGA 20% UNTUK PEMESANAN EARLY BIRD. HUBUNGI CS KAMI SEKARANG
                </div>
            </div>
            
            <!-- Suggested Items Section -->
            <div class="suggested-items" style="margin-bottom: 4rem; text-align: left;">
                <h3 style="color: #333; font-weight: 700; margin-bottom: 2rem; text-transform: uppercase; border-bottom: 2px solid #eee; padding-bottom: 1rem;">
                    BARANG YANG DISARANKAN UNTUK DIBAWA
                </h3>
                
                <div style="margin-bottom: 1.5rem;">
                    <h4 style="font-weight: 700; margin-bottom: 0.5rem; color: #333;">Tas Ransel Kecil</h4>
                    <p style="color: #555; line-height: 1.6;">Ransel kecil adalah barang yang cukup penting untuk dibawa, terutama saat mendaki (trekking), memudahkan Anda membawa air mineral botol, kamera, dan keperluan penting lainnya.</p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h4 style="font-weight: 700; margin-bottom: 0.5rem; color: #333;">Obat-obatan</h4>
                    <p style="color: #555; line-height: 1.6;">Saat berlayar, kita akan jauh dari daratan yang menyediakan banyak kebutuhan mendesak. Pastikan Anda menyiapkan obat-obatan pribadi yang biasa Anda gunakan sebelumnya.</p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h4 style="font-weight: 700; margin-bottom: 0.5rem; color: #333;">Perlengkapan Mandi</h4>
                    <p style="color: #555; line-height: 1.6;">Jika Anda selektif tentang merek perlengkapan mandi, membawa sendiri adalah keharusan. Bawa semua keperluan mulai dari sabun hingga sampo.</p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h4 style="font-weight: 700; margin-bottom: 0.5rem; color: #333;">Pelindung Diri</h4>
                    <p style="color: #555; line-height: 1.6;">Labuan Bajo dan Kepulauan Komodo sangat panas dan terik, terutama selama bulan-bulan tertentu. Disarankan agar Anda membawa tabir surya (sunblock), kacamata hitam, dan topi.</p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h4 style="font-weight: 700; margin-bottom: 0.5rem; color: #333;">Uang Tunai</h4>
                    <p style="color: #555; line-height: 1.6;">Karena tidak ada ATM di pulau, Anda perlu menyiapkan uang tunai untuk membeli camilan, minum air kelapa muda, membeli suvenir, dan terutama untuk membayar biaya masuk Taman Nasional Komodo.</p>
                </div>
            </div>
            
            <!-- CTA Section -->
            <div class="text-center" style="margin-bottom: 5rem;">
                <a href="https://wa.me/6287718031430?text=Halo%20Althaf%20Ocean%20Tour,%20saya%20ingin%20tanya%20paket%20${data.title}" class="btn btn-accent" style="padding: 15px 50px; font-size: 1rem;">PESAN SEKARANG</a>
            </div>
        </div>
    `;


    // Global switch function for tabs
    window.switchTab = function(type) {
        const openTab = document.querySelector(".price-tab:nth-child(1)");
        const privateTab = document.querySelector(".price-tab:nth-child(2)");
        const openContent = document.getElementById("open-trip-content");
        const privateContent = document.getElementById("private-content");

        if (type === 'open-trip') {
            openTab.classList.add("active");
            openTab.style.borderTop = "3px solid #4A69BD";
            openTab.style.color = "#4A69BD";
            
            privateTab.classList.remove("active");
            privateTab.style.borderTop = "none";
            privateTab.style.color = "#333";
            
            openContent.style.display = "block";
            privateContent.style.display = "none";
        } else {
            privateTab.classList.add("active");
            privateTab.style.borderTop = "3px solid #4A69BD";
            privateTab.style.color = "#4A69BD";
            
            openTab.classList.remove("active");
            openTab.style.borderTop = "none";
            openTab.style.color = "#333";
            
            openContent.style.display = "none";
            privateContent.style.display = "block";
        }
    };
}
