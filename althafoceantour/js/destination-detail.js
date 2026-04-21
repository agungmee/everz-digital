document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const destId = params.get("id");

    if (!destId || !destinationsData[destId]) {
        document.getElementById("destination-detail-container").innerHTML = `
            <div class="container text-center" style="padding: 100px 0;">
                <h2>Destinasi Tidak Ditemukan</h2>
                <p>Maaf, kami tidak dapat menemukan detail destinasi yang Anda cari.</p>
                <a href="index.html#destinations" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        `;
        return;
    }

    const data = destinationsData[destId];
    renderDestination(data);
});

function renderDestination(data) {
    const container = document.getElementById("destination-detail-container");

    // Filter related packages
    const relatedPackages = data.relatedPackageIds.map(id => {
        return { id, ...packagesData[id] };
    });

    let packagesHtml = relatedPackages.map(pkg => `
        <div class="pkg-card" style="display: flex; flex-direction: column;">
            <div class="pkg-img">
                <img src="${pkg.gallery[0]}" alt="${pkg.title}" style="width: 100%; height: 240px; object-fit: cover;">
                <div class="pkg-badge">${pkg.stars} ⭐</div>
            </div>
            <div class="pkg-content" style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <h3 class="pkg-title" style="font-size: 1.1rem; margin-bottom: 0.5rem; min-height: 2.8rem;">${pkg.title}</h3>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem; line-height: 1.4;">${pkg.subtitle}</p>
                </div>
                <a href="package.html?id=${pkg.id}" class="btn btn-primary" style="padding: 10px 20px; font-size: 0.8rem; text-align: center;">Lihat Detail</a>
            </div>
        </div>
    `).join("");

    container.innerHTML = `
        <div class="container">
            <!-- Hero Image Section -->
            <div style="width: 100%; height: 500px; border-radius: 20px; overflow: hidden; margin-bottom: 3rem; box-shadow: var(--shadow-lg);">
                <img src="${data.image}" alt="${data.title}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <!-- Content Section -->
            <div class="text-center" style="max-width: 900px; margin: 0 auto 5rem;">
                <h1 style="color: var(--primary); font-family: var(--font-heading); margin-bottom: 1rem; text-transform: uppercase;">
                    ${data.title}
                </h1>
                <p style="color: var(--accent); font-weight: 600; font-size: 1.1rem; margin-bottom: 2.5rem; letter-spacing: 3px; text-transform: uppercase;">
                    ${data.subtitle}
                </p>
                <div style="line-height: 2; color: var(--text-dark); text-align: justify; font-size: 1.1rem;">
                    ${data.description}
                </div>
            </div>

            <!-- Grid Info -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; margin-bottom: 5rem;">
                <div style="background: var(--bg-light); padding: 2rem; border-radius: 15px; border-left: 5px solid var(--primary);">
                    <h4 style="margin-bottom: 1rem; color: var(--primary);"><i class="fas fa-map-marked-alt"></i> Area & Luas</h4>
                    <p style="line-height: 1.6;">${data.details.area}</p>
                </div>
                <div style="background: var(--bg-light); padding: 2rem; border-radius: 15px; border-left: 5px solid var(--primary);">
                    <h4 style="margin-bottom: 1rem; color: var(--primary);"><i class="fas fa-landmark"></i> Sejarah & Status</h4>
                    <p style="line-height: 1.6;">${data.details.history}</p>
                </div>
                <div style="background: var(--bg-light); padding: 2rem; border-radius: 15px; border-left: 5px solid var(--primary);">
                    <h4 style="margin-bottom: 1rem; color: var(--primary);"><i class="fas fa-compass"></i> Destinasi & Aktivitas</h4>
                    <p style="line-height: 1.6;">${data.details.destination}</p>
                </div>
                <div style="background: var(--primary); color: white; padding: 2rem; border-radius: 15px;">
                    <h4 style="margin-bottom: 1rem; color: var(--accent);"><i class="fas fa-star"></i> Highlight Utama</h4>
                    <p style="line-height: 1.6; font-weight: 600;">${data.details.highlight}</p>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 5rem 0;">

            <!-- Related Packages Section -->
            <div class="related-packages">
                <h2 style="text-align: center; color: var(--primary); margin-bottom: 3rem;">Pilihan Paket Wisata Menuju Ke Sini</h2>
                <div class="packages-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
                    ${packagesHtml}
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center" style="margin: 6rem 0;">
                <p style="font-size: 1.1rem; margin-bottom: 1.5rem;">Ingin mengatur perjalanan kustom ke destinasi ini?</p>
                <a href="https://wa.me/6287718031430?text=Halo%20Althaf%20Ocean%20Tour,%20saya%20tertarik%20dengan%20destinasi%20${encodeURIComponent(data.title)}" class="btn btn-accent" style="padding: 15px 50px;">Konsultasi Gratis SEKARANG</a>
            </div>
        </div>
    `;
}
