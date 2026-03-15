const tabs = [
  {
    id: "konten-iklan",
    label: "30 Konten Iklan Siap Pakai",
    price: "Rp 97.000",
    summary: "30 paket prompt gambar + ad copy yang tinggal copy."
  },
  {
    id: "script-wa",
    label: "11 Script WA",
    price: "Rp 77.000",
    summary: "11 chat template siap kirim untuk follow up dan closing."
  },
  {
    id: "checklist",
    label: "Checklist Penting",
    price: "Rp 150.000",
    summary: "Checklist operasional yang sudah disusun langsung untuk tim."
  }
];

function getTabLabelParts(label) {
  const match = label.match(/^(\d+)\s+(.+)$/);
  if (!match) {
    return { count: "", title: label };
  }

  return {
    count: match[1],
    title: match[2]
  };
}

const nicheMap = {
  "jasa-pasang-cctv": {
    title: "Bonus Jasa Pasang CCTV",
    short: "CCTV",
    intro: "Semua bonus di halaman ini dibuat untuk langsung dipakai jualan jasa pasang CCTV. Tinggal copy, ganti detail brand, nomor WA, atau area layanan bila perlu.",
    audience: "pemilik rumah, toko, gudang, kantor, dan kos yang butuh rasa aman",
    value: "solusi CCTV yang rapi, cepat dipasang, dan jelas rekomendasinya",
    offer: "survey cepat, rekomendasi titik kamera, dan pemasangan rapi",
    cta: "Chat sekarang untuk minta rekomendasi paket CCTV yang paling cocok untuk lokasi Anda.",
    benefits: [
      "Titik kamera kami bantu sesuaikan dengan area paling rawan",
      "Instalasi rapi, aman, dan enak dilihat",
      "Bisa pantau dari HP dengan lebih tenang"
    ],
    problemBullets: [
      "Area rumah atau usaha masih banyak blind spot",
      "Takut kejadian penting tidak terekam saat dibutuhkan",
      "Bingung pilih paket yang sesuai kebutuhan"
    ],
    socialProof: "Sudah banyak pemilik rumah dan usaha di [Kota] yang mempercayakan pemasangan CCTV mereka ke kami.",
    ctaClose: "Sekarang giliran Anda. Minta rekomendasinya sekarang juga. 👇",
    themes: [
      { hook: "Rumah kosong sering bikin was-was karena tidak ada pantauan real time.", hookShort: "Punya Rumah yang Sering Kosong? Takut Tidak Bisa Pantau?", hope: "Pantau rumah langsung dari HP kapan pun dibutuhkan.", visual: "keluarga melihat CCTV dari ponsel dengan ekspresi lega" },
      { hook: "Toko rawan kehilangan karena titik kamera sebelumnya salah pasang.", hookShort: "Punya Toko? Takut Ada Titik Rawan yang Lolos dari Pantauan?", hope: "Pasang kamera di titik penting agar pengawasan lebih terasa.", visual: "interior toko dengan overlay titik kamera penting" },
      { hook: "Gudang besar sulit diawasi kalau hanya mengandalkan patroli manual.", hookShort: "Punya Gudang Luas? Takut Pengawasannya Banyak Celah?", hope: "Awasi area besar lebih praktis tanpa harus keliling terus.", visual: "area gudang luas dengan beberapa angle pengawasan" },
      { hook: "Banyak klien takut beli CCTV tapi hasil pasangnya berantakan.", hookShort: "Mau Pasang CCTV Tapi Takut Hasilnya Berantakan?", hope: "Dapatkan instalasi rapi yang enak dilihat dan aman dipakai.", visual: "teknisi merapikan instalasi kamera di plafon bersih" },
      { hook: "Kos atau kantor butuh keamanan tambahan tanpa bikin proses ribet.", hookShort: "Punya Kos atau Kantor? Mau Tambah Keamanan Tanpa Ribet?", hope: "Mulai dari konsultasi sampai pemasangan dibuat lebih ringkas.", visual: "pemilik properti berdiskusi santai dengan teknisi CCTV" },
      { hook: "Calon klien bingung pilih paket karena merek dan speknya terlalu banyak.", hookShort: "Mau Pasang CCTV Tapi Bingung Pilih Paketnya?", hope: "Kami bantu pilih paket sesuai kebutuhan, bukan asal jual kamera.", visual: "teknisi menunjukkan pilihan monitor dan kamera dengan jelas" }
    ],
    waContext: "kebutuhan lokasi pasang, jumlah titik kamera, dan kekhawatiran soal keamanan atau harga",
    checklistContext: "lead CCTV cepat ditangani dan materi iklan tetap relevan dengan kebutuhan keamanan"
  },
  "wedding-organizer": {
    title: "Bonus Wedding Organizer",
    short: "WO",
    intro: "Semua materi di halaman ini dibuat untuk vendor wedding organizer. Fokusnya bukan teori, tetapi konten dan script yang bisa langsung dipakai promosi dan follow up calon pengantin.",
    audience: "calon pengantin dan keluarga yang ingin acara rapi tanpa drama",
    value: "hari pernikahan yang tenang, terarah, dan terkoordinasi",
    offer: "konsultasi konsep, rundown rapi, dan pendampingan sampai hari H",
    cta: "Chat sekarang untuk cek tanggal, konsep acara, dan paket WO yang paling pas.",
    benefits: [
      "Semua vendor kami bantu koordinasikan dari awal",
      "Rundown hari H dibuat lebih rapi dan terarah",
      "Kamu bisa fokus menikmati momen, bukan panik sendiri"
    ],
    problemBullets: [
      "Vendor susah dihubungi saat dibutuhkan",
      "Detail acara masih berantakan dan belum fix",
      "Tanggal makin dekat tapi persiapan belum tenang"
    ],
    socialProof: "Sudah banyak pasangan di [Kota] yang mempercayakan hari istimewa mereka ke kami.",
    ctaClose: "Sekarang giliran kamu. 👇",
    themes: [
      { hook: "Banyak calon pengantin stres karena takut hari H berantakan.", hookShort: "Mau Nikah Tapi Takut Ribet?", hope: "Kami urus semuanya, kamu tinggal hadir dan bahagia.", visual: "pasangan pengantin tersenyum tenang saat tim WO mengatur rundown" },
      { hook: "Keluarga sering kewalahan mengurus vendor satu per satu.", hookShort: "Mau Nikah Tapi Takut Keluarga Kewalahan Urus Vendor?", hope: "Semua vendor, timeline, dan detail acara kami bantu koordinasikan.", visual: "tim WO briefing vendor dan keluarga di venue" },
      { hook: "Calon pengantin takut konsep bagus di kepala tapi gagal di eksekusi.", hookShort: "Mau Nikah Dengan Konsep Cantik Tapi Takut Hasilnya Zonk?", hope: "Kami bantu wujudkan detail acara agar hasilnya seindah yang dibayangkan.", visual: "moodboard wedding berubah menjadi setup venue elegan" },
      { hook: "Hari penting tidak boleh kacau hanya karena rundown tidak jelas.", hookShort: "Mau Nikah Tapi Takut Hari H Berantakan?", hope: "Dengan koordinasi yang rapi, semua momen penting tetap berjalan indah.", visual: "clipboard rundown, headset coordinator, suasana venue tertata" },
      { hook: "Banyak pasangan bingung mulai persiapan dari mana.", hookShort: "Mau Nikah Tapi Bingung Mulai Dari Mana?", hope: "Kami bantu susun persiapan dari awal supaya kamu tidak jalan sendirian.", visual: "konsultasi hangat antara pasangan dan planner dengan laptop terbuka" },
      { hook: "Takut vendor tidak sinkron saat hari acara berlangsung.", hookShort: "Mau Nikah Tapi Takut Vendor Tidak Sinkron?", hope: "Serahkan koordinasi ke tim WO agar acara tetap tenang dan terkendali.", visual: "tim WO memberi arahan cepat ke MC, dekorasi, dan dokumentasi" }
    ],
    waContext: "tanggal acara, konsep, venue, jumlah tamu, dan budget yang masih dipertimbangkan",
    checklistContext: "lead WO tetap hangat, jadwal follow up rapi, dan angle promosi selalu menyentuh keresahan calon pengantin"
  },
  "service-ac": {
    title: "Bonus Service AC",
    short: "Service AC",
    intro: "Halaman ini berisi bonus siap pakai untuk promosi jasa service AC. Tiap konten dibuat agar owner atau admin tinggal copy lalu publish atau kirim ke prospek.",
    audience: "pemilik rumah, kos, kantor, dan tempat usaha yang AC-nya bermasalah",
    value: "AC kembali dingin, nyaman dipakai, dan proses servis lebih jelas",
    offer: "diagnosa cepat, teknisi rapi, dan penanganan sesuai masalah unit",
    cta: "Chat sekarang dan booking jadwal service AC sebelum slot hari ini penuh.",
    benefits: [
      "Teknisi datang dengan penanganan yang lebih jelas",
      "Pengerjaan rapi tanpa bikin rumah atau ruangan tambah repot",
      "AC kembali nyaman dipakai lebih cepat"
    ],
    problemBullets: [
      "AC tidak dingin tapi terus dipaksa nyala",
      "Air menetes, bunyi aneh, atau performa makin turun",
      "Takut biaya service makin besar kalau terus ditunda"
    ],
    socialProof: "Sudah banyak rumah, kos, dan tempat usaha di [Kota] yang mempercayakan service AC mereka ke kami.",
    ctaClose: "Kalau AC Anda sudah mulai bermasalah, jangan tunggu makin parah. 👇",
    themes: [
      { hook: "AC tidak dingin bikin rumah atau ruangan kerja jadi tidak nyaman.", hookShort: "Punya AC Tapi Sudah Tidak Dingin?", hope: "Biar ruangan kembali adem tanpa ribet dan tanpa nunggu lama.", visual: "keluarga merasa gerah lalu berubah lega setelah AC kembali dingin" },
      { hook: "Banyak orang menunda servis sampai AC makin parah dan biaya naik.", hookShort: "Punya AC Bermasalah Tapi Masih Ditunda Servisnya?", hope: "Service lebih cepat sering kali jauh lebih hemat daripada menunggu rusak total.", visual: "teknisi membersihkan unit indoor dengan hasil kinclong" },
      { hook: "AC bocor sering bikin panik karena takut merusak interior rumah.", hookShort: "Punya AC Bocor? Takut Rumah Jadi Ikut Rusak?", hope: "Kami bantu tangani cepat sebelum kerusakan merembet ke mana-mana.", visual: "unit AC bocor lalu teknisi memperbaiki dengan alat lengkap" },
      { hook: "Pemilik kos atau kantor butuh teknisi yang datang tepat waktu.", hookShort: "Punya Kos atau Kantor? Butuh Teknisi AC yang Tepat Waktu?", hope: "Respon cepat dan jadwal jelas bikin masalah cepat selesai.", visual: "teknisi service AC datang dengan seragam rapi dan alat siap kerja" },
      { hook: "Banyak pelanggan trauma dengan teknisi yang asal bongkar lalu hilang.", hookShort: "Mau Service AC Tapi Takut Teknisi Tidak Jelas?", hope: "Kami kerja rapi, komunikatif, dan jelas penanganannya dari awal.", visual: "teknisi menjelaskan kondisi unit ke pelanggan dengan sopan" },
      { hook: "Listrik boros sering muncul karena AC kotor atau setting tidak optimal.", hookShort: "Punya AC Tapi Tagihan Listrik Ikut Naik?", hope: "AC yang dirawat dengan benar bisa lebih nyaman dan lebih efisien.", visual: "perbandingan tagihan listrik dan unit AC yang diservis" }
    ],
    waContext: "keluhan AC tidak dingin, bocor, bunyi, jadwal kunjungan, dan estimasi harga",
    checklistContext: "permintaan service cepat direspon dan materi iklan tetap kuat di musim panas atau saat demand naik"
  },
  "jasa-renovasi": {
    title: "Bonus Jasa Renovasi",
    short: "Renovasi",
    intro: "Bonus ini dibuat untuk pengusaha jasa renovasi yang butuh bahan iklan dan follow up siap pakai. Setiap item diarahkan untuk membantu closing tanpa harus mulai dari nol.",
    audience: "pemilik rumah, ruko, kantor, dan bangunan lama yang ingin renovasi lebih aman",
    value: "renovasi lebih terkontrol, jelas progresnya, dan hasilnya sesuai kebutuhan",
    offer: "survey lokasi, estimasi kerja, dan pengawasan progres yang jelas",
    cta: "Chat sekarang untuk konsultasi renovasi dan cek estimasi awal proyek Anda.",
    benefits: [
      "Progress kerja lebih jelas dan mudah dipantau",
      "Estimasi awal dibuat lebih rapi sebelum jalan",
      "Koordinasi proyek lebih enak dan tidak bikin capek sendiri"
    ],
    problemBullets: [
      "Takut proyek molor dan mengganggu aktivitas",
      "Cemas biaya membengkak di tengah jalan",
      "Khawatir hasil akhirnya tidak sesuai harapan"
    ],
    socialProof: "Sudah banyak pemilik rumah dan usaha di [Kota] yang mempercayakan proyek renovasi mereka ke kami.",
    ctaClose: "Kalau Anda mau renovasi lebih tenang dan lebih jelas arahnya, mulai dari sini. 👇",
    themes: [
      { hook: "Banyak orang takut renovasi karena sering dengar cerita proyek molor.", hookShort: "Mau Renovasi Tapi Takut Proyeknya Molor?", hope: "Kami bantu jalankan proyek dengan progres yang lebih jelas dan terkontrol.", visual: "owner rumah melihat progres renovasi dengan wajah lega" },
      { hook: "Biaya renovasi yang mendadak naik sering bikin calon klien mundur.", hookShort: "Mau Renovasi Tapi Takut Biayanya Membengkak?", hope: "Mulai dari estimasi yang lebih jelas agar keputusan terasa lebih aman.", visual: "diskusi anggaran renovasi dengan gambar kerja dan kalkulasi rapi" },
      { hook: "Rumah lama butuh diperbaiki tapi pemilik bingung harus mulai dari mana.", hookShort: "Punya Rumah yang Mau Direnovasi Tapi Bingung Mulainya?", hope: "Kami bantu arahkan langkah awal sampai hasil akhir lebih tertata.", visual: "sebelum dan sesudah area rumah yang direnovasi" },
      { hook: "Klien takut hasil akhir tidak sesuai ekspektasi setelah keluar biaya besar.", hookShort: "Sudah Siap Renovasi Tapi Takut Hasilnya Tidak Sesuai?", hope: "Kami jaga detail kerja dan komunikasi agar hasil lebih sesuai harapan.", visual: "tim renovasi menunjukkan material dan progress ke owner" },
      { hook: "Renovasi toko atau kantor tidak boleh terlalu mengganggu operasional.", hookShort: "Punya Toko atau Kantor yang Mau Direnovasi?", hope: "Pekerjaan yang lebih tertata membantu operasional tetap lebih terkendali.", visual: "renovasi ruang usaha yang tetap terlihat tertib" },
      { hook: "Pemilik rumah sering capek mengurus tukang yang sulit diarahkan.", hookShort: "Mau Renovasi Tapi Capek Urus Tukang Sendiri?", hope: "Serahkan ke tim yang lebih rapi, terarah, dan enak diajak koordinasi.", visual: "mandor dan tim kerja dengan alat dan area yang rapi" }
    ],
    waContext: "luas area, jenis pekerjaan, target hasil, timeline, dan kekhawatiran biaya",
    checklistContext: "lead renovasi masuk lebih tersaring dan tim tidak kehilangan momentum follow up"
  },
  "agen-property": {
    title: "Bonus Agen Property",
    short: "Property",
    intro: "Semua materi di halaman ini difokuskan untuk agen property. Isinya sudah berupa konten siap-copy yang membantu Anda menarik prospek, menghangatkan minat, dan mengarahkan survey.",
    audience: "pembeli rumah, investor, dan pencari hunian yang masih membandingkan banyak pilihan",
    value: "prospek lebih yakin memilih properti yang tepat dan berani ambil langkah lanjut",
    offer: "kurasi listing, penjelasan lokasi, dan arahan next step yang jelas",
    cta: "Chat sekarang untuk minta daftar properti yang paling sesuai kebutuhan Anda.",
    benefits: [
      "Kami bantu shortlist properti yang lebih relevan",
      "Survey jadi lebih terarah dan tidak buang waktu",
      "Keputusan beli terasa lebih yakin dan lebih aman"
    ],
    problemBullets: [
      "Terlalu banyak listing tapi belum tahu mana yang paling cocok",
      "Takut salah pilih karena keputusan beli nilainya besar",
      "Capek survey atau scroll listing tanpa arah yang jelas"
    ],
    socialProof: "Sudah banyak pencari rumah dan investor di [Kota] yang dibantu menemukan properti yang lebih tepat bersama kami.",
    ctaClose: "Kalau Anda ingin pilihan yang lebih tepat tanpa muter-muter, mulai dari sini. 👇",
    themes: [
      { hook: "Banyak calon pembeli bingung karena semua listing terlihat menarik tapi tidak jelas mana yang paling pas.", hookShort: "Sedang Cari Rumah Tapi Bingung Pilih yang Benar-Benar Cocok?", hope: "Kami bantu saring pilihan agar Anda tidak buang waktu lihat yang tidak relevan.", visual: "agen property menunjukkan beberapa pilihan properti di tablet" },
      { hook: "Prospek takut salah beli karena keputusan properti menyangkut uang besar.", hookShort: "Mau Beli Properti Tapi Takut Salah Ambil?", hope: "Dapatkan arahan yang lebih jelas sebelum ambil keputusan besar.", visual: "pasangan meninjau rumah sambil didampingi agen profesional" },
      { hook: "Rumah yang terlihat bagus di iklan sering berbeda saat survey.", hookShort: "Sedang Cari Rumah Tapi Takut Iklannya Tidak Sesuai Aslinya?", hope: "Kami bantu dampingi survey dengan informasi yang lebih transparan.", visual: "agen membuka pintu rumah siap huni dengan suasana terang" },
      { hook: "Banyak prospek ingin rumah ideal tapi waktunya habis untuk membandingkan listing.", hookShort: "Sedang Cari Rumah Tapi Capek Bandingkan Listing Terus?", hope: "Kami bantu rapikan proses pencarian agar pilihan lebih cepat mengerucut.", visual: "kolase perumahan, akses jalan, dan fasilitas sekitar" },
      { hook: "Investor butuh properti yang potensial, bukan sekadar yang murah.", hookShort: "Cari Properti untuk Investasi Tapi Mau yang Lebih Potensial?", hope: "Kami bantu arahkan listing sesuai tujuan beli dan potensi lokasinya.", visual: "grafik potensi lokasi dan foto properti modern" },
      { hook: "Calon pembeli sering menunda survey karena masih ragu apakah unitnya cocok.", hookShort: "Sudah Ingin Survey Tapi Masih Ragu Unitnya Cocok?", hope: "Mulai dari shortlist yang lebih tepat agar survey terasa lebih mantap.", visual: "agen menyambut prospek saat survey rumah contoh" }
    ],
    waContext: "lokasi incaran, budget, tujuan beli, tipe unit, dan kesiapan survey",
    checklistContext: "pipeline prospek property tetap bergerak dan tidak hilang di tahap follow up"
  }
};

const nichePromptProfiles = {
  CCTV: {
    ageRange: "ages 28-45",
    sceneSubjects: [
      "An Indonesian couple checking home security concerns with tense expressions",
      "An Indonesian shop owner looking worried while reviewing security camera footage on a monitor",
      "An Indonesian warehouse supervisor standing in a large storage area with a concerned face",
      "An Indonesian boarding house owner discussing security problems with a technician",
      "An Indonesian office manager pointing at blind spots in a modern workspace",
      "An Indonesian homeowner holding a smartphone and feeling anxious about an unmonitored property"
    ],
    sceneSettings: [
      "Modern Indonesian living room with subtle signs of concern, warm practical lighting, realistic home details",
      "Convenience store or minimarket interior in Indonesia with shelves, cashier area, and visible blind spots",
      "Industrial warehouse interior with boxes, high ceiling, and dim corners that feel hard to monitor",
      "Modern boarding house corridor or office lobby in Indonesia, clean but vulnerable atmosphere",
      "Security consultation setup with CCTV catalog sheets, monitor screens, and installation sketches on a table"
    ],
    detailElements: [
      "Include visible security concerns, realistic facial expressions, and a strong sense of urgency",
      "Add practical props such as CCTV camera boxes, installation notes, or phone screens with monitoring apps",
      "Keep the scene believable, premium, and relevant for Indonesian service ads",
      "Show emotional contrast between worry before service and calm confidence after the solution is presented",
      "Make the composition commercial, clean, and easy to scan in a Meta Ads feed",
      "Use Indonesian faces, realistic clothing, and natural body language"
    ],
    buttonColors: ["deep blue", "emerald green", "dark red", "amber orange", "charcoal black"]
  },
  WO: {
    ageRange: "ages 23-30",
    sceneSubjects: [
      "A stressed young Indonesian couple sitting together while planning their wedding",
      "A tired Indonesian future bride working alone late at night with a worried expression",
      "An Indonesian couple discussing wedding chaos with visible emotional pressure",
      "An Indonesian wedding organizer calmly briefing a team while the couple looks relieved",
      "A future bride and groom reviewing vendor options with anxious faces",
      "An Indonesian family helping with wedding preparation but looking overwhelmed"
    ],
    sceneSettings: [
      "Modern Indonesian living room with invitation drafts, sticky notes, budget sheets, and a laptop on a cluttered table",
      "Cozy Indonesian bedroom or study with dim table lamp lighting, wedding checklist printouts, notebooks, and vendor comparison spreadsheets",
      "Elegant wedding venue in Indonesia during preparation time, with floral arrangements, chairs, and staff in motion",
      "Wedding consultation desk with moodboards, fabric swatches, venue references, and detailed planning notes",
      "Split-screen wedding concept showing chaos on one side and a perfect organized celebration on the other side"
    ],
    detailElements: [
      "Make the emotional pressure obvious but tasteful, suitable for premium wedding ads",
      "Use cinematic warm lighting, editorial photo realism, and polished composition",
      "Show Indonesian faces, expressive body language, and authentic wedding planning details",
      "Include props like invitation samples, rundown sheets, vendor lists, and budget spreadsheets",
      "Keep the layout modern, premium, and ad-ready with strong emotional storytelling",
      "Create a visual contrast between stress before hiring help and calm after professional support"
    ],
    buttonColors: ["deep rose", "warm gold", "deep teal", "soft amber", "dusty pink"]
  },
  "Service AC": {
    ageRange: "ages 26-45",
    sceneSubjects: [
      "An Indonesian homeowner sweating lightly in a warm room while looking frustrated at a wall AC unit",
      "An Indonesian technician in neat uniform inspecting an AC unit with professional focus",
      "An Indonesian family in a living room feeling uncomfortable because the AC is not cooling",
      "An Indonesian office worker pointing at a leaking AC above a workstation",
      "An Indonesian boarding house owner discussing AC problems with a technician",
      "An Indonesian customer looking relieved after an AC service technician finishes the job cleanly"
    ],
    sceneSettings: [
      "Modern Indonesian home interior with wall-mounted AC, realistic furniture, and warm daylight",
      "Compact office room in Indonesia with computer desks, work chairs, and an AC unit above",
      "Technician service scene with tools, protective cover, and partially opened AC unit in realistic detail",
      "Small business or boarding house room with signs of discomfort caused by poor cooling",
      "Before-and-after cooling scene with visual contrast between hot discomfort and cool relief"
    ],
    detailElements: [
      "Show humidity, heat discomfort, or leaking water in a realistic but clean commercial way",
      "Use photo-real editorial style with crisp textures, cinematic lighting, and believable Indonesian interiors",
      "Include service tools, pressure gauges, cleaning equipment, or protective covers where relevant",
      "Make the outcome feel practical, fast, and trustworthy rather than overly dramatic",
      "Highlight clean workmanship and professional technician appearance",
      "Keep the layout conversion-focused and easy to read for service ads"
    ],
    buttonColors: ["deep green", "electric blue", "bright orange", "red orange", "dark cyan"]
  },
  Renovasi: {
    ageRange: "ages 30-50",
    sceneSubjects: [
      "An Indonesian homeowner standing in a half-renovated room with a worried expression",
      "An Indonesian couple reviewing renovation plans and budget notes on a table",
      "An Indonesian contractor or site supervisor explaining progress to a client",
      "An Indonesian business owner looking concerned inside a space under renovation",
      "An Indonesian family imagining a better home while facing a messy outdated interior",
      "An Indonesian client feeling relieved while inspecting neat renovation progress"
    ],
    sceneSettings: [
      "Partially renovated Indonesian home interior with tools, materials, dusty corners, and exposed surfaces",
      "Renovation planning desk with floor plans, calculators, material samples, and cost notes",
      "Before-and-after room transformation with one side unfinished and the other side bright and polished",
      "Modern Indonesian shop or office space being renovated in an orderly professional manner",
      "Client consultation on-site with building materials, measuring tools, and progress references"
    ],
    detailElements: [
      "Show a strong sense of transformation, control, and visible progress",
      "Use realistic Indonesian construction details, clean uniforms, and believable tools",
      "Make the mess feel authentic but keep the overall composition premium and commercial",
      "Highlight planning clarity, budget pressure, and the promise of a better finished result",
      "Use cinematic realism with warm daylight or controlled indoor lighting",
      "Create trust through tidy work habits and professional coordination"
    ],
    buttonColors: ["burnt orange", "deep navy", "forest green", "dark maroon", "mustard gold"]
  },
  Property: {
    ageRange: "ages 27-45",
    sceneSubjects: [
      "An Indonesian couple comparing multiple property options with serious expressions",
      "An Indonesian property agent professionally guiding a client during a house viewing",
      "An Indonesian buyer standing in front of a modern house while thinking carefully",
      "An Indonesian investor reviewing property brochures and location maps on a desk",
      "A young Indonesian family imagining their future home while touring a clean property",
      "An Indonesian prospect feeling confused by too many listings on a laptop and smartphone"
    ],
    sceneSettings: [
      "Modern Indonesian house exterior with clean facade, driveway, and natural daylight",
      "Property viewing scene inside a bright living room with agent and client interaction",
      "Home search desk with brochures, maps, mortgage notes, and a laptop showing listings",
      "Split-screen property visual comparing confusion versus confident decision making",
      "Residential area or show unit environment in Indonesia with premium yet realistic atmosphere"
    ],
    detailElements: [
      "Show trust, aspiration, and decision-making pressure in a realistic way",
      "Keep the scene premium and commercial, suitable for property lead generation ads",
      "Include brochures, floor plans, keys, location maps, or survey notes where relevant",
      "Use Indonesian faces, natural poses, and a polished but believable environment",
      "Make the property look desirable without feeling too generic or stock-like",
      "Balance emotional appeal with practical buying context"
    ],
    buttonColors: ["deep emerald", "royal blue", "warm gold", "brick red", "dark teal"]
  }
};

const adAngleBlueprints = [
  { label: "Fear Angle", style: "dramatic editorial realism, cinematic contrast, emotional tension", headlineLead: "Jangan Tunggu Sampai Terjadi", bridge: "Masalah kecil yang ditunda sering berubah jadi kerugian besar.", ctaLead: "Amankan sekarang", proof: "Tambahkan elemen rasa was-was lalu ubah menjadi rasa aman.", composition: "single scene with emotional focus", overlayPlacement: "bottom half", lighting: "cinematic warm lighting", panelTone: "clean white", buttonTone: "deep red" },
  { label: "Urgency Angle", style: "fast paced commercial look, high clarity, sharp focal point", headlineLead: "Jangan Tunggu Terlalu Lama", bridge: "Kebutuhan seperti ini biasanya makin mendesak saat dibiarkan.", ctaLead: "Booking hari ini", proof: "Masukkan kesan slot terbatas dan respon cepat.", composition: "single scene with strong foreground subject", overlayPlacement: "bottom right", lighting: "crisp daylight with commercial polish", panelTone: "soft cream", buttonTone: "bright orange" },
  { label: "Before After", style: "split-screen transformation layout, polished commercial realism", headlineLead: "Bedanya Terasa Setelah Ditangani", bridge: "Tampilkan perubahan kondisi sebelum dan sesudah memakai layanan.", ctaLead: "Lihat perubahan", proof: "Perjelas kontras hasil lama vs hasil setelah ditangani.", composition: "split-screen with strong center divider", overlayPlacement: "bottom full width", lighting: "contrast between cooler before side and warmer after side", panelTone: "clean white", buttonTone: "deep teal" },
  { label: "Social Proof", style: "editorial realism with testimonial feel and premium trust", headlineLead: "Sudah Banyak yang Merasakan Bedanya", bridge: "Orang lebih yakin saat melihat hasil yang sudah dirasakan pelanggan lain.", ctaLead: "Minta contoh hasil", proof: "Masukkan bintang, testimoni singkat, atau bukti hasil kerja.", composition: "subject plus supporting proof elements", overlayPlacement: "right side", lighting: "warm confident lighting", panelTone: "white or light beige", buttonTone: "emerald green" },
  { label: "Anti Ribet", style: "clean modern ad layout, friendly, conversion-focused", headlineLead: "Tidak Perlu Ribet Urus Sendiri", bridge: "Prospek sering menunda karena membayangkan prosesnya ribet.", ctaLead: "Konsultasi cepat", proof: "Tonjolkan proses singkat, dibimbing, dan praktis.", composition: "single scene with clear subject and negative space", overlayPlacement: "bottom left", lighting: "soft natural daylight", panelTone: "cream white", buttonTone: "warm gold" },
  { label: "Harga Aman", style: "premium clean look, neat composition, persuasive clarity", headlineLead: "Takut Mahal Karena Tidak Jelas?", bridge: "Kekhawatiran harga biasanya muncul saat proses dan scope tidak diterangkan dengan baik.", ctaLead: "Minta estimasi", proof: "Masukkan nuansa harga transparan dan langkah kerja yang jelas.", composition: "desk scene with planning details", overlayPlacement: "bottom half", lighting: "soft cinematic desk lighting", panelTone: "clean white", buttonTone: "dusty blue" },
  { label: "Comparison", style: "comparison ad, highly readable, strong visual contrast", headlineLead: "Jangan Sampai Salah Pilih", bridge: "Banyak calon pelanggan bingung karena semua vendor terlihat mirip.", ctaLead: "Bandingkan dulu", proof: "Beri kesan ada perbedaan nyata antara layanan rapi dan layanan asal jadi.", composition: "side-by-side comparison", overlayPlacement: "bottom full width", lighting: "neutral commercial lighting with visual separation", panelTone: "white", buttonTone: "navy blue" },
  { label: "Checklist Problem", style: "infographic-inspired realism, sharp ad composition", headlineLead: "Kalau Sudah Begini, Saatnya Ambil Tindakan", bridge: "Format checklist cepat menarik orang yang sedang mengalami masalah spesifik.", ctaLead: "Cek kondisi sekarang", proof: "Gunakan 3 poin masalah yang mudah diidentifikasi.", composition: "single scene with structured overlay space", overlayPlacement: "right side", lighting: "bright indoor lighting", panelTone: "soft ivory", buttonTone: "red orange" },
  { label: "Result Driven", style: "aspirational commercial realism, polished and optimistic", headlineLead: "Yang Dicari Bukan Sekadar Proses", bridge: "Orang membeli hasil akhirnya, bukan teknisnya saja.", ctaLead: "Wujudkan hasilnya", proof: "Tegaskan hasil akhir yang paling diinginkan target market.", composition: "result-focused hero shot", overlayPlacement: "bottom center", lighting: "warm golden hour style", panelTone: "white", buttonTone: "forest green" },
  { label: "Soft Closing", style: "friendly editorial realism, approachable and calming", headlineLead: "Mulai Dari Langkah Kecil Dulu", bridge: "Banyak prospek belum siap deal, tapi siap diajak konsultasi ringan.", ctaLead: "Tanya dulu", proof: "Bangun rasa aman tanpa tekanan.", composition: "consultation scene with approachable expressions", overlayPlacement: "bottom right", lighting: "soft warm indoor lighting", panelTone: "cream", buttonTone: "dusty pink" },
  { label: "Promo Angle", style: "bold ad creative, high-conversion commercial style", headlineLead: "Saatnya Ambil Kesempatan Ini", bridge: "Angle promo cocok untuk mendorong keputusan yang tertunda.", ctaLead: "Ambil penawaran", proof: "Tambahkan kesan penawaran khusus yang tidak selalu ada.", composition: "single scene with promotional emphasis", overlayPlacement: "bottom half", lighting: "bright energetic lighting", panelTone: "clean white", buttonTone: "bright red" },
  { label: "Pain Point Rumah", style: "home-life realism, emotional, relatable", headlineLead: "Masalah Ini Sering Dianggap Sepele", bridge: "Masalah rumah atau keluarga cepat memancing atensi karena dekat dengan keseharian.", ctaLead: "Atasi sekarang", proof: "Gunakan suasana rumah tangga yang relevan.", composition: "family or home-centered scene", overlayPlacement: "bottom left", lighting: "warm family-home lighting", panelTone: "soft cream", buttonTone: "amber gold" },
  { label: "Pain Point Bisnis", style: "business-focused realism, professional, high urgency", headlineLead: "Kalau Bisnis Terganggu, Ruginya Lebih Besar", bridge: "Pemilik usaha bereaksi cepat saat melihat potensi kerugian operasional.", ctaLead: "Amankan operasional", proof: "Masukkan konteks toko, kantor, gudang, atau tempat usaha.", composition: "business environment scene", overlayPlacement: "right side", lighting: "neutral office lighting with contrast", panelTone: "white", buttonTone: "dark green" },
  { label: "Trust Builder", style: "premium trust-building ad, clean, elegant, professional", headlineLead: "Pilih Vendor yang Komunikasinya Jelas", bridge: "Kepercayaan sering terbentuk dari cara vendor menjelaskan proses dan hasil.", ctaLead: "Lihat cara kerja", proof: "Tampilkan kesan profesional, detail, dan dapat dipercaya.", composition: "service explanation or consultation scene", overlayPlacement: "bottom right", lighting: "controlled soft lighting", panelTone: "light beige", buttonTone: "royal blue" },
  { label: "Fast Response", style: "mobile-first, urgent, responsive service ad", headlineLead: "Butuh Respon Cepat?", bridge: "Sebagian besar prospek hanya ingin ditangani cepat tanpa muter-muter.", ctaLead: "Hubungi sekarang", proof: "Tekankan fast response dan penanganan langsung.", composition: "subject interacting with phone or quick action", overlayPlacement: "bottom half", lighting: "crisp daylight", panelTone: "white", buttonTone: "deep green" },
  { label: "Problem Escalation", style: "fear-driven realism, strong problem visibility", headlineLead: "Kalau Dibiarkan, Bisa Makin Parah", bridge: "Angle ini cocok untuk memecah penundaan dan mendorong aksi cepat.", ctaLead: "Cegah lebih jauh", proof: "Visual harus menunjukkan risiko bila masalah diabaikan.", composition: "problem-heavy single scene", overlayPlacement: "bottom full width", lighting: "moody cinematic lighting", panelTone: "white", buttonTone: "dark maroon" },
  { label: "Portofolio Angle", style: "portfolio showcase, premium editorial realism", headlineLead: "Lihat Standar Hasil yang Bisa Didapat", bridge: "Sebagian prospek baru yakin setelah melihat contoh hasil nyata.", ctaLead: "Minta portofolio", proof: "Tampilkan hasil kerja yang bersih dan meyakinkan.", composition: "showcase style with polished end result", overlayPlacement: "right side", lighting: "premium bright lighting", panelTone: "soft white", buttonTone: "deep teal" },
  { label: "Educational Angle", style: "educational ad layout, authority driven, clean realism", headlineLead: "Banyak Orang Baru Sadar Setelah Terjadi", bridge: "Iklan edukatif bagus untuk prospek yang belum sadar urgensi masalahnya.", ctaLead: "Pelajari lalu ambil langkah", proof: "Masukkan fakta singkat atau insight sederhana.", composition: "subject plus educational visual cues", overlayPlacement: "bottom left", lighting: "soft neutral lighting", panelTone: "ivory white", buttonTone: "steel blue" },
  { label: "Myth Busting", style: "bold anti-myth ad, scroll-stopping, crisp", headlineLead: "Tidak Semua yang Murah Itu Aman", bridge: "Membantah asumsi umum bisa memancing perhatian yang kuat.", ctaLead: "Pilih yang tepat", proof: "Bongkar miskonsepsi yang sering merugikan prospek.", composition: "single scene with strong message hierarchy", overlayPlacement: "bottom half", lighting: "high-contrast commercial lighting", panelTone: "clean white", buttonTone: "black" },
  { label: "Decision Helper", style: "smart consultative ad, premium clarity", headlineLead: "Masih Bingung Memilih?", bridge: "Prospek yang bingung butuh diarahkan, bukan ditekan.", ctaLead: "Minta arahan", proof: "Tampilkan bahwa Anda membantu memilih sesuai kebutuhan.", composition: "guided choice consultation scene", overlayPlacement: "right side", lighting: "warm consultation lighting", panelTone: "light cream", buttonTone: "deep blue" },
  { label: "Seasonal Angle", style: "timely, situational, ad-ready realism", headlineLead: "Momen Ini Bikin Kebutuhan Makin Terasa", bridge: "Angle musiman membuat iklan terasa lebih dekat dengan kondisi sekarang.", ctaLead: "Siapkan dari sekarang", proof: "Sesuaikan visual dengan momen sibuk, panas, ramai, atau jadwal padat.", composition: "moment-based lifestyle scene", overlayPlacement: "bottom center", lighting: "seasonal atmospheric lighting", panelTone: "white", buttonTone: "orange amber" },
  { label: "Emotional Relief", style: "emotional realism, human-centered, warm", headlineLead: "Rasanya Lega Kalau Sudah Ada yang Pegang", bridge: "Beberapa prospek lebih tersentuh oleh rasa tenang dibanding detail teknis.", ctaLead: "Biar lebih tenang", proof: "Tonjolkan ekspresi lega setelah masalah teratasi.", composition: "relief-focused emotional scene", overlayPlacement: "bottom half", lighting: "soft golden lighting", panelTone: "cream white", buttonTone: "sage green" },
  { label: "CTA Berat", style: "hard sell commercial style, sharp and direct", headlineLead: "Kalau Memang Butuh, Langsung Ambil Langkah", bridge: "Untuk market yang sudah sadar kebutuhan, CTA tegas lebih efektif.", ctaLead: "Chat dan amankan", proof: "Gunakan bahasa langsung dan kuat.", composition: "high-intent hero image", overlayPlacement: "bottom full width", lighting: "strong commercial lighting", panelTone: "clean white", buttonTone: "deep red" },
  { label: "Simple Process", style: "step-by-step clean commercial realism", headlineLead: "Prosesnya Sesederhana Ini", bridge: "Banyak orang menunda karena tidak paham langkah kerjanya.", ctaLead: "Mulai dari langkah pertama", proof: "Visualkan 3 langkah proses yang terasa mudah.", composition: "service process scene with clean staging", overlayPlacement: "right side", lighting: "clear daylight", panelTone: "soft ivory", buttonTone: "deep teal" },
  { label: "Objection Killer", style: "objection handling ad, sharp hierarchy, realistic", headlineLead: "Masih Takut Soal Harga, Ribet, atau Hasil?", bridge: "Angle ini cocok untuk mematahkan hambatan utama sebelum prospek bertanya.", ctaLead: "Tanyakan dulu", proof: "Masukkan 3 objection paling umum lalu bantah secara singkat.", composition: "single scene plus structured message overlay", overlayPlacement: "bottom right", lighting: "bright commercial lighting", panelTone: "white", buttonTone: "dark green" },
  { label: "Premium Positioning", style: "luxury-inspired minimal realism, premium composition", headlineLead: "Untuk yang Tidak Mau Asal Jadi", bridge: "Sebagian pasar tidak mencari yang termurah, tapi yang paling aman dipilih.", ctaLead: "Lihat standar kami", proof: "Bangun kesan eksklusif dan detail.", composition: "clean premium hero shot", overlayPlacement: "bottom left", lighting: "elegant warm lighting", panelTone: "soft beige", buttonTone: "warm gold" },
  { label: "Budget Fit", style: "practical premium realism, honest and calming", headlineLead: "Tetap Bisa Jalan Meski Budget Dijaga", bridge: "Calon pelanggan dengan budget ketat tetap ingin solusi yang aman.", ctaLead: "Cari opsi yang pas", proof: "Tampilkan fleksibilitas tanpa terlihat murahan.", composition: "budget planning or practical choice scene", overlayPlacement: "bottom half", lighting: "soft desk lighting", panelTone: "white", buttonTone: "slate blue" },
  { label: "Question Hook", style: "question-led scroll stopper, cinematic realism", headlineLead: "Masih Mau Menunda Kalau Sudah Begini?", bridge: "Pertanyaan yang tepat memancing prospek berhenti scroll dan berpikir.", ctaLead: "Jawab dengan aksi", proof: "Fokus pada satu pertanyaan besar yang menekan rasa penasaran.", composition: "single scene with strong emotional pause", overlayPlacement: "right side", lighting: "moody warm lighting", panelTone: "clean white", buttonTone: "deep orange" },
  { label: "Local Service", style: "local trust ad, grounded realism, approachable", headlineLead: "Cari yang Dekat, Cepat, dan Jelas?", bridge: "Banyak prospek lebih nyaman jika merasa layanannya mudah dijangkau.", ctaLead: "Cek area layanan", proof: "Masukkan nuansa area lokal dan kedekatan layanan.", composition: "local area service scene", overlayPlacement: "bottom center", lighting: "natural daylight", panelTone: "white", buttonTone: "dark teal" },
  { label: "Outcome Guarantee Feel", style: "safe, controlled, confidence-building realism", headlineLead: "Yang Dicari Itu Kepastian Rasa Aman", bridge: "Banyak orang membeli rasa tenang setelah keputusan dibuat.", ctaLead: "Dapatkan kepastiannya", proof: "Bangun kesan hasil yang terkendali dan tidak bikin was-was.", composition: "confident after-solution hero scene", overlayPlacement: "bottom half", lighting: "warm polished cinematic lighting", panelTone: "soft cream", buttonTone: "deep green" }
];

function buildAdEntries(niche) {
  const profile = nichePromptProfiles[niche.short];

  return adAngleBlueprints.map((angle, index) => {
    const theme = niche.themes[index % niche.themes.length];
    const subject = profile.sceneSubjects[index % profile.sceneSubjects.length];
    const setting = profile.sceneSettings[(index * 2) % profile.sceneSettings.length];
    const detail = profile.detailElements[(index + 2) % profile.detailElements.length];
    const buttonColor = profile.buttonColors[index % profile.buttonColors.length];
    const hookText = buildHookText(theme, angle);
    const subtext = buildSubtext(theme, angle, niche);
    const ctaText = buildCtaText(angle, niche);
    const proofText = angle.proof.replace(/\.+$/, "");
    const prompt = [
      `${subject}, showing a situation related to ${niche.short} services. ${setting}.`,
      `${detail}.`,
      `Main emotional context: "${theme.hook}"`,
      `Visual direction: ${theme.visual}.`,
      `Use ${angle.composition}. Style: ${angle.style}. Lighting: ${angle.lighting}.`,
      `Overlay panel on the ${angle.overlayPlacement}: ${angle.panelTone} background with bold black Indonesian text, clean modern layout:`,
      `- HOOK / HEADLINE (large, bold): "${hookText}"`,
      `- SUBTEXT (medium, softer): "${subtext}"`,
      `- CTA BUTTON (pill shape, ${buttonColor}): "${ctaText}"`,
      `Make the offer feel clear: "${niche.offer}".`,
      `Add proof cue: ${proofText}.`,
      `Square 1:1 ratio. Editorial photo realism. High resolution. Cinematic commercial ad quality. Clean modern layout. Indonesian subjects, ${profile.ageRange}. No watermarks.`
    ].join("\n");

    const headline = buildHeadline(theme, angle, niche);
    const primaryText = buildPrimaryText(theme, angle, niche, index);
    const description = buildDescription(theme, angle, niche);

    return {
      title: `Konten Iklan ${index + 1}`,
      subtitle: `${angle.label} untuk ${niche.short}`,
      prompt,
      primaryText,
      headline,
      description
    };
  });
}

function buildPrimaryText(theme, angle, niche, index) {
  const bulletA = niche.problemBullets[index % niche.problemBullets.length];
  const bulletB = niche.problemBullets[(index + 1) % niche.problemBullets.length];
  const bulletC = niche.problemBullets[(index + 2) % niche.problemBullets.length];
  const benefitA = niche.benefits[index % niche.benefits.length];
  const benefitB = niche.benefits[(index + 1) % niche.benefits.length];
  const benefitC = niche.benefits[(index + 2) % niche.benefits.length];
  const openers = getPrimaryOpeners(niche.short, theme, angle);
  const realityLead = getRealityLead(niche.short, angle);
  const transitions = getTransitionCopy(niche.short, angle);
  const close = niche.ctaClose || "Kalau merasa ini relate, lanjut chat sekarang. 👇";
  const socialProof = getSocialProofCopy(niche, angle);

  return [
    openers,
    "",
    realityLead,
    `• ${ensureSentence(bulletA)}`,
    `• ${ensureSentence(bulletB)}`,
    `• ${ensureSentence(bulletC)}`,
    "",
    ensureSentence(transitions),
    "",
    `✅ ${ensureSentence(benefitA)}`,
    `✅ ${ensureSentence(benefitB)}`,
    `✅ ${ensureSentence(benefitC)}`,
    "",
    ensureSentence(socialProof),
    "",
    ensureSentence(close),
    buildFinalCtaSentence(angle, niche),
    "https://wa.me/[nomor anda]"
  ].join("\n");
}

function buildHeadline(theme, angle, niche) {
  const headlineCatalog = {
    WO: {
      "Fear Angle": "Mau Nikah Tanpa Ribet dan Tanpa Drama",
      "Urgency Angle": "Jangan Tunggu Sampai Persiapan Nikah Makin Bikin Stres",
      "Before After": "Dari Ribet Jadi Tenang Saat Pernikahan Diurus Tim yang Tepat",
      "Social Proof": "Banyak Pasangan Pilih Menikah Lebih Tenang Bersama Kami",
      "Anti Ribet": "Urus Pernikahan Tidak Harus Capek Sendiri",
      "Harga Aman": "WO dengan Proses Jelas Bikin Keputusan Lebih Tenang",
      Comparison: "WO yang Tepat Bikin Hari H Terasa Sangat Berbeda",
      "Checklist Problem": "Kalau Sudah Begini, Persiapan Nikah Jangan Ditunda Lagi",
      "Result Driven": "Bukan Cuma Sibuk Persiapan, Tapi Hari H Harus Tetap Indah",
      "Soft Closing": "Mulai Persiapan Nikah dari Konsultasi yang Lebih Tenang",
      "Promo Angle": "Saatnya Siapkan Pernikahan Tanpa Drama dari Sekarang",
      "Pain Point Rumah": "Persiapan Nikah Jangan Sampai Bikin Rumah Ikut Tegang",
      "Pain Point Bisnis": "Untuk Pasangan Sibuk, WO yang Rapi Itu Bukan Lagi Pilihan Tambahan",
      "Trust Builder": "WO dengan Koordinasi Jelas Bikin Pengantin Lebih Tenang",
      "Fast Response": "Butuh WO Fast Response untuk Persiapan yang Tidak Menumpuk",
      "Problem Escalation": "Masalah Persiapan Nikah Bisa Makin Besar Kalau Terus Ditunda",
      "Portofolio Angle": "Lihat Hasil Pernikahan yang Ditangani Lebih Rapi dan Elegan",
      "Educational Angle": "Banyak Pasangan Baru Sadar Pentingnya WO Saat Sudah Terlalu Dekat",
      "Myth Busting": "WO yang Tepat Bukan Soal Murah, Tapi Soal Tenang di Hari H",
      "Decision Helper": "Masih Bingung Pilih WO? Mulai dari Arahan yang Lebih Jelas",
      "Seasonal Angle": "Makin Dekat Tanggalnya, Makin Penting Punya WO yang Siap Pegang",
      "Emotional Relief": "WO yang Tepat Bikin Kamu Bisa Menikah dengan Hati Lebih Ringan",
      "CTA Berat": "Kalau Sudah Mau Nikah, Jangan Tunggu Sampai Persiapan Makin Kacau",
      "Simple Process": "Persiapan Pernikahan Bisa Lebih Simpel Kalau Diurus dengan Benar",
      "Objection Killer": "Takut Ribet, Takut Mahal, Takut Vendor Kacau? Semua Bisa Dibahas",
      "Premium Positioning": "Untuk yang Ingin Pernikahan Rapi dan Tidak Asal Jadi",
      "Budget Fit": "WO yang Bisa Disesuaikan Kebutuhan Tanpa Bikin Makin Pusing",
      "Question Hook": theme.hookShort,
      "Local Service": "Cari WO yang Dekat, Cepat, dan Enak Diajak Koordinasi?",
      "Outcome Guarantee Feel": "Pernikahan yang Indah Dimulai dari Rasa Tenang Saat Persiapan"
    },
    CCTV: {
      "Fear Angle": "Pasang CCTV Biar Rumah dan Usaha Lebih Tenang",
      "Urgency Angle": "Jangan Tunggu Sampai Area Penting Luput dari Pantauan",
      "Before After": "Dari Was-Was Jadi Tenang dengan CCTV yang Dipasang Tepat",
      "Social Proof": "Banyak Pemilik Rumah dan Usaha Pasang CCTV Lebih Tenang Bersama Kami",
      "Anti Ribet": "Pasang CCTV Tidak Harus Ribet Kalau Ditangani Tim yang Tepat",
      "Harga Aman": "Pasang CCTV dengan Penjelasan Jelas Biar Tidak Salah Langkah",
      Comparison: "Beda Titik Pasang, Beda Juga Rasa Aman yang Didapat",
      "Checklist Problem": "Kalau Sudah Ada Blind Spot, Jangan Tunggu Kejadian Dulu",
      "Result Driven": "Yang Dicari Bukan Sekadar Kamera, Tapi Rasa Aman yang Nyata",
      "Soft Closing": "Mulai dari Konsultasi CCTV yang Lebih Jelas dan Ringan",
      "Promo Angle": "Saatnya Rapikan Pengawasan Rumah dan Usaha dari Sekarang",
      "Pain Point Rumah": "Rumah yang Sering Kosong Butuh Pengawasan yang Lebih Jelas",
      "Pain Point Bisnis": "Toko dan Gudang Lebih Aman Saat Titik Rawan Tidak Lagi Terbuka",
      "Trust Builder": "Pilih Jasa Pasang CCTV yang Komunikasinya Jelas dari Awal",
      "Fast Response": "Butuh Jasa Pasang CCTV yang Cepat Tanggap dan Tidak Muter-Muter",
      "Problem Escalation": "Blind Spot Kecil Bisa Jadi Masalah Besar Kalau Terus Dibiarkan",
      "Portofolio Angle": "Lihat Hasil Pemasangan CCTV yang Lebih Rapi dan Meyakinkan",
      "Educational Angle": "Banyak Orang Baru Sadar Pentingnya CCTV Setelah Kejadian Terjadi",
      "Myth Busting": "Pasang CCTV yang Tepat Bukan Soal Murah, Tapi Soal Aman",
      "Decision Helper": "Masih Bingung Pilih Paket CCTV? Kami Bantu Arahkan",
      "Seasonal Angle": "Saat Rumah dan Usaha Lebih Sering Ditinggal, CCTV Jadi Makin Penting",
      "Emotional Relief": "CCTV yang Tepat Bikin Pikiran Jauh Lebih Tenang",
      "CTA Berat": "Kalau Sudah Butuh Pengawasan, Jangan Tunggu Sampai Terlambat",
      "Simple Process": "Pasang CCTV Bisa Lebih Simpel dari yang Anda Bayangkan",
      "Objection Killer": "Takut Salah Paket, Takut Berantakan, Takut Mahal? Semua Bisa Dijelaskan",
      "Premium Positioning": "Untuk yang Ingin Pemasangan Rapi dan Hasil Tidak Asal Jadi",
      "Budget Fit": "CCTV yang Tepat Tetap Bisa Disesuaikan dengan Kebutuhan dan Budget",
      "Question Hook": theme.hookShort,
      "Local Service": "Cari Jasa Pasang CCTV yang Dekat, Cepat, dan Jelas?",
      "Outcome Guarantee Feel": "Rasa Aman Dimulai dari Titik Kamera yang Dipasang dengan Tepat"
    },
    "Service AC": {
      "Fear Angle": "AC Bermasalah Jangan Tunggu Sampai Bikin Ruangan Tidak Nyaman",
      "Urgency Angle": "Jangan Tunggu AC Makin Parah dan Biayanya Ikut Naik",
      "Before After": "Dari Gerah Jadi Adem Lagi Saat AC Ditangani dengan Benar",
      "Social Proof": "Banyak Rumah dan Usaha Kembali Nyaman Setelah Service di Sini",
      "Anti Ribet": "Service AC Tidak Harus Bikin Repot Kalau Ditangani Tim yang Rapi",
      "Harga Aman": "Service AC dengan Penjelasan Jelas Bikin Anda Lebih Tenang",
      Comparison: "Teknisi yang Tepat Bikin Hasil Service AC Terasa Beda",
      "Checklist Problem": "Kalau AC Sudah Bocor atau Tidak Dingin, Jangan Ditunda Lagi",
      "Result Driven": "Yang Dicari Bukan Sekadar Bongkar AC, Tapi Ruangan Nyaman Lagi",
      "Soft Closing": "Mulai dari Tanya Kondisi AC Dulu, Baru Putuskan dengan Tenang",
      "Promo Angle": "Saatnya Bikin AC Nyaman Lagi Sebelum Masalahnya Melebar",
      "Pain Point Rumah": "AC Bermasalah di Rumah Bikin Aktivitas Harian Ikut Tidak Nyaman",
      "Pain Point Bisnis": "AC Bermasalah Bisa Ganggu Tamu, Karyawan, dan Operasional",
      "Trust Builder": "Pilih Jasa Service AC yang Kerjanya Jelas dan Komunikatif",
      "Fast Response": "Butuh Teknisi AC Cepat Tanggap Saat Ruangan Sudah Tidak Nyaman",
      "Problem Escalation": "AC Kecil Masalahnya Hari Ini Bisa Jadi Besar Besok",
      "Portofolio Angle": "Lihat Hasil Service AC yang Lebih Rapi dan Lebih Meyakinkan",
      "Educational Angle": "Banyak Orang Baru Sadar Pentingnya Service Saat AC Sudah Drop Total",
      "Myth Busting": "Service AC yang Tepat Bukan Soal Murah, Tapi Soal Hasil yang Jelas",
      "Decision Helper": "Masih Bingung AC Perlu Di-service atau Tidak? Kami Bantu Cek",
      "Seasonal Angle": "Saat Cuaca Panas, AC Bermasalah Terasa Jauh Lebih Mengganggu",
      "Emotional Relief": "AC yang Kembali Normal Bikin Rumah dan Kerja Lebih Tenang",
      "CTA Berat": "Kalau AC Sudah Bermasalah, Jangan Tunggu Sampai Makin Repot",
      "Simple Process": "Service AC Bisa Dimulai dari Langkah Sederhana yang Jelas",
      "Objection Killer": "Takut Mahal, Takut Kotor, Takut Tidak Beres? Semua Bisa Dibahas",
      "Premium Positioning": "Untuk yang Ingin Service AC Rapi dan Tidak Asal Bongkar",
      "Budget Fit": "Service AC Tetap Bisa Dicari Opsi yang Lebih Pas dan Masuk Akal",
      "Question Hook": theme.hookShort,
      "Local Service": "Cari Jasa Service AC yang Dekat, Cepat, dan Tidak Bikin Bingung?",
      "Outcome Guarantee Feel": "Ruangan Nyaman Lagi Saat AC Ditangani dengan Lebih Tepat"
    },
    Renovasi: {
      "Fear Angle": "Renovasi Lebih Tenang Tanpa Takut Proyek Berantakan",
      "Urgency Angle": "Jangan Mulai Renovasi Tanpa Arah dan Progres yang Jelas",
      "Before After": "Dari Bangunan Lama Jadi Lebih Rapi Saat Renovasi Ditangani Tepat",
      "Social Proof": "Banyak Klien Renovasi Lebih Tenang Bersama Tim Kami",
      "Anti Ribet": "Renovasi Tidak Harus Bikin Pusing Kalau Koordinasinya Jelas",
      "Harga Aman": "Renovasi dengan Estimasi yang Lebih Jelas Bikin Hati Lebih Tenang",
      Comparison: "Beda Cara Kerja, Beda Juga Hasil Renovasi yang Didapat",
      "Checklist Problem": "Kalau Sudah Takut Molor dan Membengkak, Jangan Jalan Tanpa Arahan",
      "Result Driven": "Yang Dicari Bukan Sekadar Bongkar, Tapi Hasil Akhir yang Lebih Rapi",
      "Soft Closing": "Mulai Renovasi dari Konsultasi yang Lebih Jelas dan Terarah",
      "Promo Angle": "Saatnya Rapikan Renovasi Sebelum Proyek Makin Bikin Stres",
      "Pain Point Rumah": "Renovasi Rumah yang Tidak Terkontrol Bisa Bikin Pikiran Penuh",
      "Pain Point Bisnis": "Renovasi Toko atau Kantor Harus Jalan Tanpa Ganggu Operasional",
      "Trust Builder": "Pilih Jasa Renovasi yang Enak Diajak Koordinasi dari Awal",
      "Fast Response": "Butuh Tim Renovasi yang Cepat Tanggap dan Jelas Arah Kerjanya",
      "Problem Escalation": "Masalah Renovasi Bisa Makin Besar Saat Terus Ditunda",
      "Portofolio Angle": "Lihat Hasil Renovasi yang Lebih Rapi dan Lebih Meyakinkan",
      "Educational Angle": "Banyak Orang Baru Sadar Pentingnya Sistem Kerja Renovasi Saat Sudah Molor",
      "Myth Busting": "Renovasi yang Tepat Bukan Soal Murah, Tapi Soal Aman dan Rapi",
      "Decision Helper": "Masih Bingung Mulai Renovasi dari Mana? Kami Bantu Arahkan",
      "Seasonal Angle": "Saat Kebutuhan Bangunan Makin Mendesak, Renovasi Perlu Arahan yang Jelas",
      "Emotional Relief": "Renovasi yang Terkontrol Bikin Pemilik Bangunan Lebih Tenang",
      "CTA Berat": "Kalau Sudah Mau Renovasi, Jangan Tunggu Sampai Masalahnya Melebar",
      "Simple Process": "Renovasi Bisa Jalan Lebih Simpel Kalau Langkahnya Tepat",
      "Objection Killer": "Takut Molor, Takut Bengkak, Takut Zonk? Semua Bisa Dibahas dari Awal",
      "Premium Positioning": "Untuk yang Ingin Renovasi Rapi dan Tidak Asal Jadi",
      "Budget Fit": "Renovasi Tetap Bisa Dicari Opsi yang Lebih Pas Sesuai Budget",
      "Question Hook": theme.hookShort,
      "Local Service": "Cari Jasa Renovasi yang Dekat, Cepat, dan Jelas Arah Kerjanya?",
      "Outcome Guarantee Feel": "Renovasi yang Tepat Bikin Hasil Akhir Lebih Menenangkan"
    },
    Property: {
      "Fear Angle": "Cari Properti Jangan Sampai Salah Langkah",
      "Urgency Angle": "Jangan Habis Waktu Lihat Listing yang Tidak Cocok",
      "Before After": "Dari Bingung Jadi Lebih Yakin Saat Properti Disaring dengan Tepat",
      "Social Proof": "Banyak Pencari Rumah dan Investor Lebih Yakin Setelah Dibantu Kami",
      "Anti Ribet": "Cari Properti Tidak Harus Muter-Muter Tanpa Arah",
      "Harga Aman": "Cari Properti dengan Arahan Jelas Biar Tidak Salah Ambil",
      Comparison: "Banyak Listing Mirip, Tapi Pilihan Tepat Tetap Perlu Arahan",
      "Checklist Problem": "Kalau Sudah Bingung Pilih Listing, Jangan Terus Jalan Sendiri",
      "Result Driven": "Yang Dicari Bukan Sekadar Listing, Tapi Properti yang Lebih Tepat",
      "Soft Closing": "Mulai Cari Properti dari Shortlist yang Lebih Jelas",
      "Promo Angle": "Saatnya Rapikan Pencarian Properti Sebelum Makin Buang Waktu",
      "Pain Point Rumah": "Cari Rumah Tidak Harus Bikin Kepala Makin Penuh",
      "Pain Point Bisnis": "Cari Properti untuk Investasi Perlu Arah yang Lebih Jelas",
      "Trust Builder": "Pilih Agen yang Bantu Arahkan, Bukan Cuma Kirim Listing",
      "Fast Response": "Butuh Agen Property yang Cepat Tanggap dan Enak Diajak Diskusi",
      "Problem Escalation": "Terlalu Lama Bingung Pilih Properti Bisa Bikin Momentum Lewat",
      "Portofolio Angle": "Lihat Properti Pilihan yang Lebih Relevan dengan Kebutuhan Anda",
      "Educational Angle": "Banyak Orang Baru Sadar Pentingnya Shortlist Saat Sudah Habis Waktu Survey",
      "Myth Busting": "Properti yang Tepat Bukan Soal Murah, Tapi Soal Cocok dan Aman",
      "Decision Helper": "Masih Bingung Pilih Rumah atau Investasi? Kami Bantu Arahkan",
      "Seasonal Angle": "Saat Pilihan Makin Banyak, Arahan yang Tepat Jadi Makin Penting",
      "Emotional Relief": "Pencarian Properti Jadi Lebih Ringan Saat Pilihan Sudah Mengerucut",
      "CTA Berat": "Kalau Sudah Serius Cari Properti, Jangan Habis Waktu Tanpa Arah",
      "Simple Process": "Cari Properti Bisa Lebih Simpel Kalau Mulai dari Filter yang Tepat",
      "Objection Killer": "Takut Salah Pilih, Takut Buang Waktu, Takut Tidak Cocok? Semua Bisa Dibahas",
      "Premium Positioning": "Untuk yang Ingin Pilih Properti dengan Lebih Serius dan Lebih Tepat",
      "Budget Fit": "Cari Properti Tetap Bisa Disesuaikan dengan Budget dan Tujuan Beli",
      "Question Hook": theme.hookShort,
      "Local Service": "Cari Agen Property yang Dekat, Cepat, dan Tidak Bikin Bingung?",
      "Outcome Guarantee Feel": "Properti yang Tepat Bikin Keputusan Terasa Lebih Menenangkan"
    }
  };

  return headlineCatalog[niche.short]?.[angle.label] || theme.hookShort;
}

function buildDescription(theme, angle, niche) {
  const descriptionCatalog = {
    WO: {
      "Fear Angle": "Kami urus detail dari awal sampai hari H, supaya kamu bisa fokus menikmati momen. Konsultasi gratis sekarang!",
      "Urgency Angle": "Semakin dekat tanggalnya, semakin penting punya tim WO yang siap pegang semuanya. Chat sekarang!",
      "Before After": "Dari vendor berantakan sampai rundown rapi, semua bisa terasa beda saat ditangani tim yang tepat.",
      "Social Proof": "Sudah banyak pasangan mempercayakan hari istimewa mereka ke kami. Sekarang giliran kamu.",
      default: "Serahkan koordinasi ke tim WO agar acara tetap tenang, rapi, dan terkendali. Konsultasi gratis sekarang!"
    },
    CCTV: {
      "Fear Angle": "Kami bantu pilih paket, titik kamera, sampai pemasangan rapi agar rumah dan usaha lebih tenang.",
      "Urgency Angle": "Jangan tunggu sampai kejadian dulu. Rapikan pengawasan dari sekarang bersama tim yang tepat.",
      "Before After": "Pantauan lebih jelas, blind spot lebih minim, dan rasa aman jadi lebih terasa setelah dipasang dengan benar.",
      "Social Proof": "Banyak rumah dan usaha sudah memasang CCTV bersama kami untuk pengawasan yang lebih tenang.",
      default: "Kami bantu dari konsultasi sampai pemasangan rapi, supaya pengawasan terasa lebih jelas dan lebih aman."
    },
    "Service AC": {
      "Fear Angle": "Kami bantu cek dan tangani masalah AC sebelum ruangan makin tidak nyaman. Booking sekarang!",
      "Urgency Angle": "Semakin lama ditunda, AC bisa makin repot dan biayanya ikut naik. Jadwalkan service sekarang.",
      "Before After": "Dari gerah dan bocor jadi adem dan nyaman lagi saat AC ditangani teknisi yang rapi.",
      "Social Proof": "Sudah banyak rumah, kos, dan usaha mempercayakan service AC mereka ke kami.",
      default: "AC bermasalah? Kami bantu tangani dengan proses yang lebih jelas, rapi, dan tidak bikin tambah repot."
    },
    Renovasi: {
      "Fear Angle": "Kami bantu renovasi berjalan lebih jelas, lebih rapi, dan lebih enak dipantau dari awal.",
      "Urgency Angle": "Kalau renovasi memang mau dimulai, pastikan arahnya jelas sejak awal supaya tidak bikin capek di tengah jalan.",
      "Before After": "Dari bangunan lama yang bikin pusing jadi hasil akhir yang lebih rapi dan lebih enak dilihat.",
      "Social Proof": "Sudah banyak klien mempercayakan renovasi mereka ke kami untuk progres yang lebih terarah.",
      default: "Kami bantu dari survey, estimasi, sampai progres kerja agar renovasi terasa lebih aman dan lebih jelas."
    },
    Property: {
      "Fear Angle": "Kami bantu shortlist properti yang lebih tepat supaya Anda tidak buang waktu dan tidak salah langkah.",
      "Urgency Angle": "Jangan habiskan energi untuk listing yang tidak cocok. Mulai dari pilihan yang lebih terarah.",
      "Before After": "Dari bingung bandingkan banyak listing jadi lebih yakin dengan shortlist yang lebih pas.",
      "Social Proof": "Sudah banyak pencari rumah dan investor dibantu menemukan pilihan yang lebih tepat bersama kami.",
      default: "Kami bantu arahkan pilihan properti yang lebih relevan agar proses cari rumah atau investasi terasa lebih ringan."
    }
  };

  return descriptionCatalog[niche.short]?.[angle.label] || descriptionCatalog[niche.short]?.default || `${theme.hope} ${niche.offer}.`;
}

function buildShortCtaLabel(angle) {
  const map = {
    "Fear Angle": "konsultasi sekarang",
    "Urgency Angle": "booking sekarang",
    "Before After": "lihat solusinya",
    "Social Proof": "minta contohnya",
    "Anti Ribet": "konsultasi gratis",
    "Harga Aman": "minta estimasi",
    Comparison: "bandingkan dulu",
    "Checklist Problem": "cek sekarang",
    "Result Driven": "ambil langkah sekarang",
    "Soft Closing": "tanya dulu",
    "Promo Angle": "ambil sekarang",
    "Pain Point Rumah": "atasi sekarang",
    "Pain Point Bisnis": "amankan sekarang",
    "Trust Builder": "lihat detailnya",
    "Fast Response": "hubungi sekarang",
    "Problem Escalation": "cegah sekarang",
    "Portofolio Angle": "minta portofolio",
    "Educational Angle": "pelajari dulu",
    "Myth Busting": "pilih yang tepat",
    "Decision Helper": "minta arahan",
    "Seasonal Angle": "siapkan sekarang",
    "Emotional Relief": "biar lebih tenang",
    "CTA Berat": "langsung chat",
    "Simple Process": "mulai sekarang",
    "Objection Killer": "tanyakan dulu",
    "Premium Positioning": "lihat standarnya",
    "Budget Fit": "cari opsi pas",
    "Question Hook": "jawab sekarang",
    "Local Service": "cek area layanan",
    "Outcome Guarantee Feel": "dapatkan kepastian"
  };

  return map[angle.label] || "konsultasi sekarang";
}

function getPrimaryOpeners(nicheShort, theme, angle) {
  const nicheBase = {
    WO: "Seharusnya persiapan pernikahan itu menyenangkan, bukan bikin stres dari jauh-jauh hari. 😔",
    CCTV: "Seharusnya rumah atau usaha terasa aman, bukan bikin was-was setiap kali ditinggal. 😟",
    "Service AC": "Seharusnya AC bikin ruangan nyaman, bukan bikin gerah dan tambah repot. 😮‍💨",
    Renovasi: "Seharusnya renovasi bikin bangunan jadi lebih baik, bukan bikin pikiran penuh tiap hari. 😵",
    Property: "Seharusnya cari properti bikin makin yakin, bukan makin bingung pilih yang tepat. 😵‍💫"
  };

  const nicheSpecific = {
    WO: {
      "Urgency Angle": "Kalau persiapan nikah terus ditunda, biasanya yang muncul bukan tenang, tapi tambahan stres baru. ⏳",
      "Pain Point Bisnis": "Buat pasangan yang sama-sama sibuk kerja, urusan nikah yang tidak terpegang bisa cepat menguras energi. 📉",
      "Decision Helper": "Kalau vendor, konsep, dan budget sama-sama banyak pilihannya, yang dibutuhkan bukan tambah bingung, tapi arahan yang jelas. 🧭"
    },
    CCTV: {
      "Urgency Angle": "Kalau pengawasan masih banyak celah, menunda pasang CCTV biasanya cuma bikin rasa was-was makin panjang. ⏳",
      "Pain Point Bisnis": "Buat toko, kantor, atau gudang, titik rawan yang tidak terpantau bisa cepat berubah jadi kerugian. 📉",
      "Decision Helper": "Kalau paket, spek, dan titik kamera terasa membingungkan, yang dibutuhkan adalah arahan yang tepat. 🧭"
    },
    "Service AC": {
      "Urgency Angle": "Kalau AC sudah mulai bermasalah, menunda service biasanya cuma bikin kerusakannya makin terasa. ⏳",
      "Pain Point Bisnis": "Buat tempat usaha, AC yang bermasalah cepat terasa dampaknya ke kenyamanan pelanggan dan tim. 📉",
      "Decision Helper": "Kalau masih bingung AC Anda perlu service ringan atau penanganan lebih lanjut, kami bantu arahkan. 🧭"
    },
    Renovasi: {
      "Urgency Angle": "Kalau renovasi memang mau jalan, menundanya tanpa arah yang jelas biasanya malah bikin makin berat di belakang. ⏳",
      "Pain Point Bisnis": "Buat toko atau kantor, renovasi yang tidak tertata bisa cepat mengganggu aktivitas harian. 📉",
      "Decision Helper": "Kalau masih bingung harus mulai dari desain, anggaran, atau pekerjaan duluan, kami bantu urutkan. 🧭"
    },
    Property: {
      "Urgency Angle": "Kalau pencarian properti terus ditunda tanpa arah, waktu habis duluan tapi pilihan belum juga jelas. ⏳",
      "Pain Point Bisnis": "Buat yang cari properti untuk investasi, salah arah sedikit saja bisa bikin keputusan terasa berat. 📉",
      "Decision Helper": "Kalau listing terlalu banyak dan semuanya terlihat menarik, kami bantu kerucutkan yang paling relevan. 🧭"
    }
  };

  const angleOpeners = {
    "Fear Angle": nicheBase[nicheShort],
    "Urgency Angle": `Kalau ${theme.hook.toLowerCase().replace(/\.$/, "")}, biasanya tidak enak kalau terus ditunda. ⏳`,
    "Before After": `Bayangkan bedanya saat ${theme.hook.toLowerCase().replace(/\.$/, "")} akhirnya ditangani dengan benar. ✨`,
    "Social Proof": `Banyak orang awalnya datang dengan kekhawatiran yang sama. Setelah dibantu, semuanya terasa jauh lebih ringan. 🙌`,
    "Anti Ribet": `${theme.hookShort.replace(/\?$/, "")} Tidak berarti semuanya harus kamu urus sendiri. 🙂`,
    "Harga Aman": `Banyak orang menunda karena takut biaya membesar atau prosesnya tidak jelas. Itu wajar. 💸`,
    Comparison: `Di luar sana banyak pilihan terlihat mirip. Tapi hasil akhirnya bisa sangat berbeda. ⚖️`,
    "Checklist Problem": `Kalau beberapa tanda ini sudah mulai muncul, biasanya masalahnya tidak bisa dianggap sepele lagi. 📌`,
    "Result Driven": `Orang biasanya tidak cari proses yang ribet. Yang dicari adalah hasil akhir yang bikin lega. ✅`,
    "Soft Closing": `Tidak semua orang langsung siap ambil keputusan hari ini. Tapi kamu bisa mulai dari obrolan yang lebih jelas. 🙂`,
    "Promo Angle": `Kalau memang sedang butuh, ini saat yang pas untuk bergerak lebih cepat. 🔥`,
    "Pain Point Rumah": `Masalah seperti ini paling terasa saat sudah menyangkut rumah, keluarga, dan rasa tenang sehari-hari. 🏠`,
    "Pain Point Bisnis": `Kalau urusan ini sampai ganggu bisnis, efeknya biasanya ikut melebar ke mana-mana. 📉`,
    "Trust Builder": `Di situasi seperti ini, orang biasanya tidak cuma cari jasa. Mereka cari tim yang enak diajak koordinasi. 🤝`,
    "Fast Response": `Kadang yang paling dibutuhkan bukan penjelasan panjang, tapi respon cepat yang langsung membantu. ⚡`,
    "Problem Escalation": `Masalah seperti ini jarang selesai sendiri. Kalau dibiarkan, biasanya justru makin repot. 🚨`,
    "Portofolio Angle": `Tidak semua orang yakin dari kata-kata. Kadang yang paling meyakinkan justru hasil nyatanya. 👀`,
    "Educational Angle": `Banyak orang baru sadar pentingnya ini setelah masalahnya keburu mengganggu. 📚`,
    "Myth Busting": `Banyak yang masih kira solusi paling murah pasti paling aman. Padahal sering kali justru sebaliknya. ❌`,
    "Decision Helper": `Kalau pilihannya terlalu banyak, yang dibutuhkan biasanya bukan tambah listing, tapi bantuan untuk mengerucutkan. 🧭`,
    "Seasonal Angle": `Di momen seperti sekarang, kebutuhan ini biasanya terasa lebih mendesak dari biasanya. 📆`,
    "Emotional Relief": `Kadang yang paling dicari bukan sekadar jasa, tapi rasa lega setelah semuanya ditangani dengan benar. 😌`,
    "CTA Berat": `Kalau masalahnya sudah terasa, biasanya memang tidak ada alasan bagus untuk menunda terus. 👇`,
    "Simple Process": `Banyak orang mengira prosesnya pasti ribet. Padahal kalau diarahkan dengan benar, langkahnya bisa jauh lebih simpel. 🪜`,
    "Objection Killer": `Takut mahal, takut ribet, takut hasilnya tidak jelas. Semua itu wajar sebelum dapat penjelasan yang benar. 🛡️`,
    "Premium Positioning": `Kalau hasil akhir itu penting, tentu tidak mau diserahkan ke yang asal jadi. ✨`,
    "Budget Fit": `Punya budget terbatas bukan berarti harus ambil keputusan yang bikin khawatir belakangan. 💡`,
    "Question Hook": `${theme.hookShort} Kalau iya, mungkin ini saatnya berhenti cuma dipikirkan. ❓`,
    "Local Service": `Banyak orang lebih tenang saat ditangani tim yang dekat, cepat dihubungi, dan jelas langkahnya. 📍`,
    "Outcome Guarantee Feel": `Pada akhirnya, yang dicari itu sederhana: keputusan yang bikin hati jauh lebih tenang. 🧘`
  };

  return nicheSpecific[nicheShort]?.[angle.label] || angleOpeners[angle.label] || nicheBase[nicheShort];
}

function getRealityLead(nicheShort, angle) {
  const map = {
    "Fear Angle": "Tapi kenyataannya?",
    "Urgency Angle": "Dan kalau terus ditunda, biasanya ini yang terjadi:",
    "Before After": "Sebelum ditangani, biasanya kondisinya seperti ini:",
    "Social Proof": "Awalnya mereka juga menghadapi hal yang sama:",
    "Anti Ribet": "Yang bikin capek biasanya karena hal-hal ini:",
    "Harga Aman": "Biasanya kekhawatiran muncul karena:",
    Comparison: "Kalau salah pilih, risikonya bisa seperti ini:",
    "Checklist Problem": "Coba cek, apakah Anda juga mengalami ini:",
    "Result Driven": "Masalahnya, orang sering terjebak di bagian ini:",
    "Soft Closing": "Kalau masih ragu, biasanya penyebabnya ini:",
    "Promo Angle": "Saat kebutuhan lagi terasa, masalah ini biasanya ikut muncul:",
    "Pain Point Rumah": "Yang bikin tidak tenang biasanya ini:",
    "Pain Point Bisnis": "Kalau dibiarkan, dampaknya bisa mulai terasa di sini:",
    "Trust Builder": "Biasanya orang mulai ragu karena:",
    "Fast Response": "Saat butuh cepat, hal yang paling bikin kesal biasanya:",
    "Problem Escalation": "Kalau dibiarkan lebih lama, biasanya arahnya ke sini:",
    "Portofolio Angle": "Sebelum lihat hasil nyata, biasanya orang masih khawatir soal:",
    "Educational Angle": "Yang sering tidak disadari dari awal adalah:",
    "Myth Busting": "Masalahnya, asumsi seperti ini sering bikin orang salah langkah:",
    "Decision Helper": "Kalau belum ada arahan yang tepat, biasanya yang terjadi:",
    "Seasonal Angle": "Di momen seperti ini, masalah yang sering muncul adalah:",
    "Emotional Relief": "Yang bikin pikiran terus kepakai biasanya hal-hal ini:",
    "CTA Berat": "Kalau sudah begini, biasanya orang tidak bisa menunda lagi:",
    "Simple Process": "Biasanya yang dibayangkan ribet itu karena:",
    "Objection Killer": "Keraguan paling sering muncul di titik ini:",
    "Premium Positioning": "Orang yang tidak mau asal jadi biasanya paling khawatir soal:",
    "Budget Fit": "Saat budget dijaga, tantangannya biasanya ini:",
    "Question Hook": "Kalau jawabannya iya, biasanya masalahnya ada di sini:",
    "Local Service": "Yang sering bikin orang cari layanan terdekat adalah:",
    "Outcome Guarantee Feel": "Sebelum rasa tenang itu datang, biasanya orang melewati ini:"
  };

  return map[angle.label] || "Tapi kenyataannya?";
}

function getTransitionCopy(nicheShort, angle) {
  const base = {
    WO: "Kami hadir supaya momen bahagia tidak berubah jadi sumber stres.",
    CCTV: "Kami hadir supaya rasa aman itu benar-benar terasa, bukan sekadar rencana.",
    "Service AC": "Kami hadir supaya masalah AC cepat beres tanpa bikin tambah capek.",
    Renovasi: "Kami hadir supaya proses renovasi terasa lebih jelas dan lebih enak dipantau.",
    Property: "Kami hadir supaya proses memilih properti terasa lebih terarah dan tidak buang waktu."
  };
  const byAngle = {
    "Fear Angle": base[nicheShort],
    "Urgency Angle": "Semakin cepat ditangani, semakin besar peluang semuanya tetap terkendali.",
    "Before After": "Begitu ditangani dengan cara yang benar, hasil akhirnya memang bisa terasa sangat berbeda.",
    "Social Proof": "Itulah kenapa banyak orang akhirnya memilih ditangani oleh tim yang lebih jelas prosesnya.",
    "Anti Ribet": "Kalau ada tim yang pegang dari awal, semuanya biasanya terasa jauh lebih ringan.",
    "Harga Aman": "Makanya penting mulai dari penjelasan yang rapi, bukan cuma angka yang kelihatan murah.",
    Comparison: "Beda cara penanganan, beda juga rasa aman dan hasil akhir yang dirasakan.",
    "Checklist Problem": "Kalau beberapa poin di atas terasa familiar, berarti sudah waktunya ambil langkah.",
    "Result Driven": "Yang paling penting bukan terlihat sibuk, tapi hasil akhirnya benar-benar terasa.",
    "Soft Closing": "Tidak harus langsung ambil keputusan besar; mulai saja dari obrolan yang lebih jelas.",
    "Promo Angle": "Kalau memang lagi butuh, momen seperti ini enak dipakai untuk bergerak lebih cepat.",
    "Pain Point Rumah": "Urusan rumah yang dibiarkan terlalu lama biasanya cuma bikin pikiran makin penuh.",
    "Pain Point Bisnis": "Kalau operasional ikut terganggu, kerugiannya sering datang diam-diam.",
    "Trust Builder": "Karena itu, penting pilih tim yang enak diajak koordinasi dari awal.",
    "Fast Response": "Di situasi seperti ini, respon cepat sering jadi hal yang paling melegakan.",
    "Problem Escalation": "Semakin ditunda, biasanya masalahnya bukan mengecil, tapi malah melebar.",
    "Portofolio Angle": "Saat hasil nyata sudah terlihat, keputusan biasanya jadi jauh lebih mudah diambil.",
    "Educational Angle": "Paham lebih cepat sering kali bisa mencegah repot yang lebih besar di belakang.",
    "Myth Busting": "Jadi jangan cuma lihat harga; lihat juga risiko dan hasil setelahnya.",
    "Decision Helper": "Keputusan besar lebih enak diambil saat pilihannya sudah dibuat lebih jelas.",
    "Seasonal Angle": "Itulah kenapa di momen seperti ini, langkah cepat sering terasa paling masuk akal.",
    "Emotional Relief": "Begitu ditangani dengan benar, yang paling terasa biasanya justru rasa leganya.",
    "CTA Berat": "Kalau kebutuhannya memang sudah jelas, sekarang tinggal ambil langkahnya.",
    "Simple Process": "Kalau alurnya benar, semuanya memang bisa dimulai dari langkah yang sederhana.",
    "Objection Killer": "Semua keraguan itu bisa dibahas dulu sebelum Anda memutuskan.",
    "Premium Positioning": "Kalau hasil akhir penting, cara memilih penanganannya juga harus lebih hati-hati.",
    "Budget Fit": "Yang penting bukan memaksakan, tapi menemukan opsi yang paling pas dan aman.",
    "Question Hook": "Kalau masalahnya memang terasa, mungkin ini saatnya berhenti cuma mikir.",
    "Local Service": "Ditangani tim yang dekat dan responsif biasanya membuat keputusan terasa lebih nyaman.",
    "Outcome Guarantee Feel": "Karena pada akhirnya, semua orang ingin keputusan yang bikin hati tenang."
  };

  return byAngle[angle.label] || base[nicheShort];
}

function getSocialProofCopy(niche, angle) {
  const suffixMap = {
    "Fear Angle": "Mereka memilih langkah lebih cepat sebelum masalahnya makin bikin pikiran penuh.",
    "Urgency Angle": "Banyak yang bergerak lebih cepat supaya masalahnya tidak makin panjang.",
    "Before After": "Mereka merasakan bedanya saat semuanya ditangani lebih rapi.",
    "Social Proof": "Sebagian besar datang dengan kekhawatiran yang sama, lalu pulang dengan rasa lebih tenang.",
    "Anti Ribet": "Mereka memilih dibantu supaya tidak harus mengurus semuanya sendiri.",
    "Harga Aman": "Mereka merasa lebih nyaman setelah tahu prosesnya lebih jelas dari awal.",
    Comparison: "Mereka tidak mau ambil risiko hanya karena salah pilih.",
    "Checklist Problem": "Banyak yang baru sadar perlu bantuan setelah tanda-tandanya mulai jelas.",
    "Result Driven": "Yang mereka cari bukan ribetnya, tapi hasil akhir yang lebih meyakinkan.",
    "Soft Closing": "Mereka memulai dari konsultasi ringan sebelum akhirnya mantap lanjut.",
    "Promo Angle": "Mereka memilih ambil momen saat kebutuhannya memang sedang terasa.",
    "Pain Point Rumah": "Karena urusan rumah memang tidak enak kalau terus dibiarkan.",
    "Pain Point Bisnis": "Karena mereka tahu gangguan kecil bisa berdampak besar ke operasional.",
    "Trust Builder": "Yang membuat mereka lanjut biasanya bukan janji manis, tapi komunikasi yang jelas.",
    "Fast Response": "Sebagian besar memilih lanjut karena respon cepat membuat semuanya lebih ringan.",
    "Problem Escalation": "Mereka bergerak sebelum masalah kecil berubah jadi beban yang lebih besar.",
    "Portofolio Angle": "Setelah lihat hasilnya, keputusan mereka jadi jauh lebih yakin.",
    "Educational Angle": "Mereka memilih bertindak lebih cepat sebelum repotnya bertambah.",
    "Myth Busting": "Mereka sadar solusi tepat jauh lebih penting daripada sekadar murah.",
    "Decision Helper": "Mereka merasa terbantu karena pilihan jadi lebih jelas dan tidak melebar.",
    "Seasonal Angle": "Di momen sibuk seperti ini, mereka memilih langkah yang lebih cepat dan aman.",
    "Emotional Relief": "Yang paling mereka rasakan setelahnya adalah rasa lega.",
    "CTA Berat": "Saat kebutuhan sudah jelas, mereka memilih tidak menundanya lagi.",
    "Simple Process": "Mereka kaget karena ternyata prosesnya bisa sesederhana itu.",
    "Objection Killer": "Setelah dijelaskan dengan terang, keraguan mereka jauh berkurang.",
    "Premium Positioning": "Mereka memilih hasil yang lebih rapi, bukan yang asal jadi.",
    "Budget Fit": "Mereka senang karena tetap bisa menemukan opsi yang terasa aman.",
    "Question Hook": "Mereka memilih lanjut saat sadar masalahnya memang tidak bisa dibiarkan.",
    "Local Service": "Mereka lebih nyaman karena timnya dekat dan mudah dihubungi.",
    "Outcome Guarantee Feel": "Yang mereka cari dari awal memang rasa aman setelah mengambil keputusan."
  };

  return `${niche.socialProof} ${suffixMap[angle.label] || ""}`.trim();
}

function buildFinalCtaSentence(angle, niche) {
  const labels = {
    "Fear Angle": "konsultasi gratis",
    "Urgency Angle": "booking lebih cepat",
    "Before After": "lihat opsi terbaik",
    "Social Proof": "minta contoh dan detailnya",
    "Anti Ribet": "mulai dari konsultasi gratis",
    "Harga Aman": "minta estimasi dan penjelasannya",
    Comparison: "bandingkan opsinya",
    "Checklist Problem": "cek kebutuhan Anda",
    "Result Driven": "ambil langkah pertamanya",
    "Soft Closing": "tanya dulu tanpa tekanan",
    "Promo Angle": "amankan momennya",
    "Pain Point Rumah": "atasi sekarang juga",
    "Pain Point Bisnis": "amankan kebutuhan Anda",
    "Trust Builder": "lihat prosesnya dulu",
    "Fast Response": "hubungi kami sekarang",
    "Problem Escalation": "cegah sebelum makin repot",
    "Portofolio Angle": "minta portofolionya",
    "Educational Angle": "konsultasi dulu",
    "Myth Busting": "pilih solusi yang lebih tepat",
    "Decision Helper": "minta arahan yang paling cocok",
    "Seasonal Angle": "siapkan dari sekarang",
    "Emotional Relief": "mulai biar lebih tenang",
    "CTA Berat": "langsung chat",
    "Simple Process": "mulai dari langkah pertama",
    "Objection Killer": "tanyakan semua keraguannya",
    "Premium Positioning": "lihat standar kerjanya",
    "Budget Fit": "cari opsi yang paling pas",
    "Question Hook": "ambil langkahnya",
    "Local Service": "cek area layanan",
    "Outcome Guarantee Feel": "dapatkan kepastiannya"
  };

  return `Chat kami sekarang untuk ${labels[angle.label] || "konsultasi gratis"}.`;
}

function ensureSentence(text) {
  const trimmed = String(text || "").trim();
  if (!trimmed) return "";
  if (/[.!?…]$/.test(trimmed)) return trimmed;
  if (/\p{Extended_Pictographic}$/u.test(trimmed)) return trimmed;
  return `${trimmed}.`;
}

function buildHookText(theme, angle) {
  const hookVariants = {
    "Fear Angle": theme.hookShort,
    "Urgency Angle": `${theme.hookShort.replace(/\?$/, "")} Sebelum Terlambat`,
    "Before After": `Sebelum Kacau, Sesudahnya Bisa Jauh Lebih Tenang`,
    "Social Proof": `${theme.hookShort.replace(/\?$/, "")} Sudah Banyak yang Percaya`,
    "Anti Ribet": `${theme.hookShort.replace(/\?$/, "")} Tidak Harus Seribet Itu`,
    "Harga Aman": `${theme.hookShort.replace(/\?$/, "")} Tanpa Takut Biaya Gelap`,
    Comparison: `Jangan Sampai Salah Pilih Saat ${theme.hookShort.replace(/\?$/, "")}`,
    "Checklist Problem": `Kalau Sudah Begini, Saatnya Jangan Ditunda`,
    "Result Driven": `Yang Dicari Bukan Ribetnya, Tapi Hasil Tenangnya`,
    "Soft Closing": `${theme.hookShort.replace(/\?$/, "")} Mulai Dari Konsultasi Dulu`,
    "Promo Angle": `${theme.hookShort.replace(/\?$/, "")} Saatnya Ambil Penawaran Ini`,
    "Pain Point Rumah": theme.hookShort,
    "Pain Point Bisnis": `${theme.hookShort.replace(/\?$/, "")} Bisa Ganggu Operasional`,
    "Trust Builder": `${theme.hookShort.replace(/\?$/, "")} Pilih yang Komunikasinya Jelas`,
    "Fast Response": `${theme.hookShort.replace(/\?$/, "")} Butuh Respon Cepat?`,
    "Problem Escalation": `${theme.hookShort.replace(/\?$/, "")} Bisa Makin Parah`,
    "Portofolio Angle": `Lihat Hasil Saat ${theme.hookShort.replace(/\?$/, "")}`,
    "Educational Angle": `${theme.hookShort.replace(/\?$/, "")} Banyak Orang Baru Sadar Belakangan`,
    "Myth Busting": `${theme.hookShort.replace(/\?$/, "")} Tidak Semua Solusi Murah Itu Aman`,
    "Decision Helper": `${theme.hookShort.replace(/\?$/, "")} Biar Kami Bantu Arahkan`,
    "Seasonal Angle": `${theme.hookShort.replace(/\?$/, "")} Sekarang Justru Makin Terasa`,
    "Emotional Relief": `${theme.hookShort.replace(/\?$/, "")} Rasanya Lega Kalau Sudah Diurus`,
    "CTA Berat": `${theme.hookShort.replace(/\?$/, "")} Kalau Memang Butuh, Langsung Jalan`,
    "Simple Process": `${theme.hookShort.replace(/\?$/, "")} Prosesnya Bisa Sesederhana Ini`,
    "Objection Killer": `${theme.hookShort.replace(/\?$/, "")} Harga, Ribet, Hasil? Semua Bisa Dijelaskan`,
    "Premium Positioning": `${theme.hookShort.replace(/\?$/, "")} Untuk yang Tidak Mau Asal Jadi`,
    "Budget Fit": `${theme.hookShort.replace(/\?$/, "")} Tetap Bisa Jalan Sesuai Budget`,
    "Question Hook": theme.hookShort,
    "Local Service": `${theme.hookShort.replace(/\?$/, "")} Cari yang Dekat dan Jelas?`,
    "Outcome Guarantee Feel": `${theme.hookShort.replace(/\?$/, "")} Yang Dicari Itu Rasa Tenang`
  };

  return hookVariants[angle.label] || theme.hookShort;
}

function buildSubtext(theme, angle, niche) {
  const subtextVariants = {
    "Fear Angle": `${theme.hope} Serahkan ke tim yang siap bantu dari awal.`,
    "Urgency Angle": `${theme.hope} Semakin cepat ditangani, semakin tenang hasilnya.`,
    "Before After": `${theme.hope} Bedanya langsung terasa saat semuanya ditangani dengan rapi.`,
    "Social Proof": `${theme.hope} Sudah banyak yang lebih tenang setelah pakai layanan ini.`,
    "Anti Ribet": `${theme.hope} Tidak perlu urus semuanya sendirian.`,
    "Harga Aman": `${theme.hope} Mulai dari penjelasan yang jelas dan langkah yang rapi.`,
    Comparison: `${theme.hope} Jangan asal pilih untuk hal sepenting ini.`,
    "Checklist Problem": `${theme.hope} Kalau tandanya sudah muncul, jangan tunggu makin repot.`,
    "Result Driven": `${theme.hope} Fokus kami bukan bikin ribet, tapi bikin hasilnya jadi jelas.`,
    "Soft Closing": `${theme.hope} Mulai dulu dari konsultasi yang ringan.`,
    "Promo Angle": `${theme.hope} Ambil momen saat kebutuhan ini lagi terasa.`,
    "Pain Point Rumah": `${theme.hope} Karena urusan rumah tidak enak kalau terus dibiarkan.`,
    "Pain Point Bisnis": `${theme.hope} Karena gangguan kecil bisa bikin operasional ikut kena.`,
    "Trust Builder": `${theme.hope} Komunikasi yang jelas bikin keputusan terasa lebih aman.`,
    "Fast Response": `${theme.hope} Respon cepat bikin masalah lebih cepat beres.`,
    "Problem Escalation": `${theme.hope} Jangan tunggu sampai masalah kecil jadi besar.`,
    "Portofolio Angle": `${theme.hope} Lihat hasilnya, lalu putuskan dengan lebih yakin.`,
    "Educational Angle": `${theme.hope} Banyak orang baru bertindak saat masalahnya telanjur repot.`,
    "Myth Busting": `${theme.hope} Yang penting bukan sekadar murah, tapi hasilnya aman.`,
    "Decision Helper": `${theme.hope} Biar pilihanmu tidak makin melebar tanpa arah.`,
    "Seasonal Angle": `${theme.hope} Di momen seperti ini, kebutuhan biasanya terasa lebih mendesak.`,
    "Emotional Relief": `${theme.hope} Karena yang dicari bukan cuma prosesnya, tapi rasa tenangnya.`,
    "CTA Berat": `${theme.hope} Kalau memang sudah butuh, sekarang saatnya jalan.`,
    "Simple Process": `${theme.hope} Mulainya cukup dari langkah yang sederhana.`,
    "Objection Killer": `${theme.hope} Harga, proses, dan hasilnya bisa dijelaskan dengan terang.`,
    "Premium Positioning": `${theme.hope} Cocok untuk yang tidak mau hasil asal jadi.`,
    "Budget Fit": `${theme.hope} Tetap bisa diarahkan ke opsi yang lebih pas.`,
    "Question Hook": `${theme.hope} Menunda biasanya cuma bikin pikiran makin penuh.`,
    "Local Service": `${theme.hope} Lebih enak ditangani tim yang cepat dihubungi.`,
    "Outcome Guarantee Feel": `${theme.hope} Karena yang dicari ujungnya adalah rasa aman.`
  };

  return subtextVariants[angle.label] || `${theme.hope} ${niche.offer}.`;
}

function buildCtaText(angle, niche) {
  const base = niche.cta.replace(/^Chat sekarang\s*(dan\s*)?/i, "").replace(/\.$/, "");
  const ctaVariants = {
    "Fear Angle": `Chat Sekarang — ${base}!`,
    "Urgency Angle": `Booking Sekarang — ${base}!`,
    "Before After": `Lihat Hasilnya — ${base}!`,
    "Social Proof": `Minta Contohnya — ${base}!`,
    "Anti Ribet": `Konsultasi Gratis — ${base}!`,
    "Harga Aman": `Minta Estimasi — ${base}!`,
    Comparison: `Bandingkan Dulu — ${base}!`,
    "Checklist Problem": `Cek Sekarang — ${base}!`,
    "Result Driven": `Wujudkan Sekarang — ${base}!`,
    "Soft Closing": `Tanya Dulu — ${base}!`,
    "Promo Angle": `Ambil Sekarang — ${base}!`,
    "Pain Point Rumah": `Atasi Sekarang — ${base}!`,
    "Pain Point Bisnis": `Amankan Sekarang — ${base}!`,
    "Trust Builder": `Lihat Detailnya — ${base}!`,
    "Fast Response": `Hubungi Sekarang — ${base}!`,
    "Problem Escalation": `Cegah Sekarang — ${base}!`,
    "Portofolio Angle": `Lihat Portofolio — ${base}!`,
    "Educational Angle": `Pelajari Dulu — ${base}!`,
    "Myth Busting": `Pilih yang Tepat — ${base}!`,
    "Decision Helper": `Minta Arahan — ${base}!`,
    "Seasonal Angle": `Siapkan Sekarang — ${base}!`,
    "Emotional Relief": `Biar Lebih Tenang — ${base}!`,
    "CTA Berat": `Langsung Chat — ${base}!`,
    "Simple Process": `Mulai Sekarang — ${base}!`,
    "Objection Killer": `Tanyakan Dulu — ${base}!`,
    "Premium Positioning": `Lihat Standarnya — ${base}!`,
    "Budget Fit": `Cari Opsi Pas — ${base}!`,
    "Question Hook": `Jawab Sekarang — ${base}!`,
    "Local Service": `Cek Area — ${base}!`,
    "Outcome Guarantee Feel": `Dapatkan Kepastian — ${base}!`
  };

  return ctaVariants[angle.label] || `Chat Sekarang — ${base}!`;
}

function buildScriptEntries(niche) {
  if (niche.title === "Bonus Wedding Organizer") {
    return [
      {
        stage: "Nurturing",
        title: "Script 1: Balas Chat Pertama (Tanya-tanya)",
        subtitle: "Saat calon pengantin baru DM/chat pertama kali dari iklan",
        text: `Halo Kakak [Nama], terima kasih sudah chat [Nama WO]. Senang bisa bantu persiapan hari spesial Kakak. 🙂

Biar saya kasih arahan yang paling pas, boleh tanya 3 hal dulu ya:
1. Acara rencananya bulan/tahun berapa?
2. Venue-nya sudah ada atau masih cari?
3. Kira-kira tamunya sekitar berapa orang?

Dari situ saya bisa bantu arahkan paket atau langkah awal yang paling masuk akal, jadi Kakak tidak buang waktu lihat opsi yang tidak relevan.`,
        note: "Jangan langsung kirim harga. Ambil kontrol chat dengan pertanyaan yang menyaring keseriusan lead."
      },
      {
        stage: "Nurturing",
        title: "Script 2: Respon Saat Calon Pengantin Tanya Paket",
        subtitle: "Lead tanya “ada paket apa aja?” atau “harganya berapa?”",
        text: `Siap Kakak, kami ada beberapa opsi paket yang bisa disesuaikan dengan kebutuhan acara. Tapi supaya saya tidak asal lempar harga, saya mau pastikan dulu acara Kakak arahnya seperti apa.

Biasanya yang paling ngaruh ke paket itu:
- tanggal acara
- venue / lokasi
- jumlah tamu
- kebutuhan WO-nya dari full planning atau hari H only

Kalau Kakak kasih gambaran singkatnya, saya bantu pilihkan opsi yang paling cocok. Jadi lebih enak daripada lihat daftar paket tapi malah bingung sendiri.`,
        note: "Tujuannya bukan menahan harga, tapi memposisikan kamu sebagai pihak yang membimbing, bukan sekadar kirim pricelist."
      },
      {
        stage: "Nurturing",
        title: "Script 3: Kirim Portofolio / Hasil Kerja",
        subtitle: "Saat lead minta contoh dekorasi, rundown, atau bukti kerja",
        text: `Boleh Kakak, saya kirim beberapa contoh ya.

Ini portfolio acara yang pernah kami handle:
- konsep intimate wedding
- ballroom wedding
- outdoor / semi-outdoor

Yang biasanya kami jaga bukan cuma visualnya, tapi juga alur hari H supaya vendor, keluarga, dan rundown tetap sinkron.

Kalau Kakak mau, setelah lihat portfolio ini saya bisa bantu arahkan mana style dan flow acara yang paling cocok dengan rencana Kakak sekarang.`,
        note: "Jangan cuma kirim foto. Tarik lagi obrolannya ke konsultasi dan kecocokan konsep."
      },
      {
        stage: "Nurturing",
        title: "Script 4: Bangun Kepercayaan (Social Proof)",
        subtitle: "Untuk lead yang masih ragu atau belum kenal WO kamu",
        text: `Wajar banget Kakak kalau masih hati-hati pilih WO, karena yang dicari bukan cuma vendor, tapi partner yang bisa bikin hari H lebih tenang.

Sejauh ini kami sudah bantu banyak pasangan di [Kota] untuk handle persiapan, koordinasi vendor, sampai jalannya acara di hari H.

Biasanya yang paling mereka syukuri itu satu: mereka bisa fokus jadi pengantin, bukan ikut pusing urus detail teknis di belakang layar.

Kalau Kakak mau, saya bisa kirim juga contoh testimonial dan alur kerja kami biar kebayang cara kami handle acara.`,
        note: "Social proof harus menjawab rasa takut lead: takut salah pilih dan takut hari H berantakan."
      },
      {
        stage: "Nurturing",
        title: "Script 5: Edukasi Nilai WO (Lead Masih Ragu Perlu WO)",
        subtitle: "Saat calon pengantin bilang “masih pikir-pikir” atau “mungkin urus sendiri”",
        text: `Bisa banget Kakak urus sendiri, apalagi kalau detail acaranya masih sederhana.

Tapi biasanya pasangan mulai butuh WO saat mereka sadar ada banyak hal yang harus jalan bareng:
- komunikasi vendor
- timeline dan rundown
- briefing keluarga / bridesmaid / groomsmen
- antisipasi kendala di hari H

Peran WO itu bukan sekadar “ada orang di venue”, tapi memastikan semua detail bergerak rapi supaya Kakak dan keluarga tidak jadi pusat kepanikan saat acara berlangsung.

Makanya banyak pasangan yang awalnya mau urus sendiri, akhirnya tetap ambil WO supaya hari H lebih tenang.`,
        note: "Edukasi value, bukan defensive. Jangan debat. Bantu lead menyadari risiko kalau semua dipegang sendiri."
      },
      {
        stage: "Closing",
        title: "Script 6: Closing Saat Lead Sudah Tahu Harga",
        subtitle: "Lead sudah dapat info paket tapi belum berani commit",
        text: `Kalau lihat kebutuhan acara Kakak sejauh ini, paket ini sebenarnya sudah cukup aman untuk bantu jalannya acara lebih rapi dan tenang.

Tinggal pertanyaannya sekarang: Kakak maunya lanjut diamankan dari sekarang, atau masih ada bagian yang ingin didiskusikan dulu?

Kalau ada yang masih mengganjal, bilang saja ya Kakak. Saya bantu jawab sejelas mungkin supaya Kakak ambil keputusan dengan lebih mantap, bukan karena dipaksa.`,
        note: "Closing yang bagus tidak memojokkan. Fokusnya mempersempit keraguan dan mendorong keputusan."
      },
      {
        stage: "Closing",
        title: "Script 7: Handle Keberatan Harga",
        subtitle: "Saat lead bilang “mahal” atau “over budget”",
        text: `Paham Kakak, pertimbangan budget memang penting.

Yang biasanya perlu dilihat bukan cuma nominal di awal, tapi apa yang Kakak dapat dan beban apa yang jadi hilang dari pundak Kakak dan keluarga.

Karena kalau tanpa koordinasi yang rapi, biaya tidak selalu lebih hemat. Kadang justru muncul stres, miss komunikasi vendor, atau momen penting yang lewat begitu saja.

Kalau Kakak mau, saya bisa bantu carikan opsi yang lebih pas dengan budget sekarang, tanpa mengorbankan bagian yang paling krusial untuk hari H.`,
        note: "Jangan buru-buru diskon. Pertahankan value dulu, baru arahkan ke opsi yang lebih pas."
      },
      {
        stage: "Closing",
        title: "Script 8: Closing Momen Meeting / Survey Venue",
        subtitle: "Mengajak lead lanjut ke ketemu langsung atau video call",
        text: `Supaya Kakak tidak menebak-nebak, langkah paling enak setelah ini sebenarnya meeting singkat atau venue check.

Di situ kita bisa bahas:
- flow acara
- kebutuhan vendor
- titik rawan yang perlu diantisipasi
- paket WO yang paling cocok

Biasanya setelah sesi itu, keputusan jadi jauh lebih jelas karena Kakak tidak cuma lihat harga, tapi benar-benar paham alur eksekusinya.

Kalau berkenan, saya bantu atur jadwal yang nyaman untuk Kakak.`,
        note: "Target script ini bukan closing final, tapi mendorong micro-commitment ke langkah berikutnya."
      },
      {
        stage: "Closing",
        title: "Script 9: Closing Final (Dorong DP)",
        subtitle: "Lead sudah cocok dengan konsep dan harga, tinggal dipush untuk booking",
        text: `Kalau dari diskusi kita sejauh ini sudah cocok, saya sarankan slotnya diamankan dulu ya Kakak.

Karena untuk tanggal bagus, biasanya yang cepat aman itu yang lebih dulu booking dan masuk DP.

Begitu slotnya secured, kami bisa lanjut bantu Kakak masuk ke tahap briefing, penyusunan timeline, dan koordinasi persiapan dengan lebih tenang.

Kalau Kakak setuju, saya kirim detail booking dan proses DP-nya sekarang.`,
        note: "Saat lead sudah setuju konsep dan harga, jangan kembali muter. Arahkan langsung ke tindakan konkret."
      },
      {
        stage: "Follow-up",
        title: "Script 10: Follow-up Lead yang Tiba-tiba Tidak Respons",
        subtitle: "Lead sebelumnya aktif lalu ghosting 2-3 hari",
        text: `Halo Kakak [Nama], izin follow up ya.

Kemarin kita sempat bahas soal kebutuhan WO untuk acara Kakak. Saya cek lagi karena biasanya calon pengantin suka ketarik ke banyak urusan lain dan chat-nya jadi tenggelam.

Kalau memang masih dipertimbangkan, saya siap bantu lanjutkan dari poin terakhir tanpa perlu ulang dari awal.

Kalau mau, balas saja dengan:
"lanjut"
atau
"masih diskusi"

Biar saya tahu harus bantu arahkan ke mana.`,
        note: "Buat follow-up serendah mungkin friksinya. Beri jawaban singkat yang mudah dibalas."
      },
      {
        stage: "Follow-up",
        title: "Script 11: Follow-up Lead Lama (1-2 Minggu Tidak Respons)",
        subtitle: "Pesan terakhir untuk lead yang sudah lama dingin",
        text: `Halo Kakak [Nama], saya izin hubungi lagi terkait rencana acara Kakak yang sempat kita bahas sebelumnya.

Saya follow up sekali lagi karena beberapa pasangan biasanya baru siap lanjut setelah timeline dan budget mereka mulai lebih jelas.

Kalau persiapan acara Kakak masih berjalan dan masih butuh WO yang bantu koordinasi dari sebelum acara sampai hari H, saya siap bantu lanjutkan.

Kalau belum timing-nya, tidak apa-apa juga Kakak. Nanti saat sudah siap, tinggal lanjut dari chat ini ya.`,
        note: "Lead lama jangan dikejar terlalu keras. Posisi terbaiknya: tetap hadir, tetap sopan, tetap mudah dihubungi."
      }
    ];
  }

  return [
    {
      stage: "Nurturing",
      title: "Script 1: Balas Chat Pertama (Tanya-tanya)",
      subtitle: `Saat calon pelanggan baru DM/chat pertama kali soal ${niche.short}`,
      text: `Halo Kakak [Nama], terima kasih sudah hubungi kami soal ${niche.short}. 🙂

Biar saya bantu arahkan yang paling pas, boleh tanya 3 hal singkat dulu ya:
1. Kebutuhan utamanya apa?
2. Lokasi / area pengerjaannya di mana?
3. Kira-kira ingin mulai kapan?

Dari situ saya bisa bantu kasih arahan yang lebih relevan, jadi Kakak tidak buang waktu lihat opsi yang belum tentu cocok.`,
      note: "Jangan langsung lempar harga atau daftar paket. Ambil kontrol chat dengan pertanyaan yang menyaring kebutuhan."
    },
    {
      stage: "Nurturing",
      title: "Script 2: Respon Saat Lead Tanya Paket / Harga",
      subtitle: "Saat lead langsung tanya “paketnya apa?” atau “berapa harganya?”",
      text: `Siap Kakak, kami ada beberapa opsi yang bisa disesuaikan. Tapi supaya saya tidak asal kasih angka, saya mau pastikan dulu kebutuhan Kakak seperti apa.

Biasanya yang paling ngaruh ke rekomendasi itu:
- detail kebutuhan utama
- lokasi / kondisi lapangan
- target hasil yang Kakak mau
- kapan ingin mulai

Kalau Kakak kasih gambaran singkatnya, saya bantu arahkan opsi yang paling masuk akal. Jadi lebih enak daripada lihat harga mentah tapi belum tentu pas.`,
      note: "Tujuannya bukan menahan harga, tapi memposisikan kamu sebagai problem solver, bukan sekadar pengirim pricelist."
    },
    {
      stage: "Nurturing",
      title: "Script 3: Kirim Portofolio / Contoh Hasil",
      subtitle: "Saat lead minta bukti kerja, hasil, atau contoh proyek",
      text: `Boleh Kakak, saya kirim beberapa contoh hasil yang pernah kami kerjakan ya.

Yang biasanya kami jaga bukan cuma hasil akhirnya, tapi juga prosesnya supaya lebih jelas, rapi, dan minim drama saat dijalankan.

Setelah Kakak lihat contohnya, saya bisa bantu arahkan mana pendekatan yang paling cocok dengan kebutuhan Kakak sekarang, supaya tidak sekadar lihat hasil tapi juga tahu alurnya.`,
      note: "Jangan cuma kirim foto atau hasil. Tarik lagi obrolannya ke konsultasi dan kecocokan kebutuhan."
    },
    {
      stage: "Nurturing",
      title: "Script 4: Bangun Kepercayaan (Social Proof)",
      subtitle: "Untuk lead yang masih ragu atau belum terlalu kenal brand kamu",
      text: `Wajar banget Kakak kalau masih hati-hati. Soalnya yang dicari bukan cuma jasa, tapi tim yang enak diajak koordinasi dan hasilnya benar-benar bisa diandalkan.

Sejauh ini kami sudah bantu banyak klien di [Kota] untuk kebutuhan ${niche.short.toLowerCase()} dengan proses yang lebih jelas dan terarah.

Biasanya yang paling mereka apresiasi itu bukan cuma hasil akhirnya, tapi karena mereka merasa dibantu ambil keputusan dengan lebih tenang.

Kalau Kakak mau, saya bisa kirim juga contoh testimonial atau alur kerja kami biar lebih kebayang.`,
      note: "Social proof harus menjawab ketakutan inti lead: takut salah pilih, takut hasil tidak sesuai, dan takut ribet di proses."
    },
    {
      stage: "Nurturing",
      title: "Script 5: Edukasi Value Biar Lead Tidak Meremehkan Solusi",
      subtitle: `Saat lead masih ragu kenapa ${niche.short} ini perlu ditangani dengan benar`,
      text: `Bisa saja Kakak pilih jalan yang paling cepat atau paling murah. Tapi biasanya yang jadi masalah itu muncul belakangan kalau dari awal arahnya kurang tepat.

Yang kami bantu bukan cuma urusan ${niche.short.toLowerCase()}-nya selesai, tapi supaya hasil akhirnya benar-benar mendekati yang Kakak butuhkan: ${niche.value}.

Makanya proses awal seperti konsultasi, pengecekan kebutuhan, dan arahan solusi itu penting. Tujuannya supaya Kakak tidak keluar waktu, tenaga, dan biaya untuk keputusan yang setengah pas.`,
      note: "Edukasi value, bukan ceramah. Buat lead sadar bahwa keputusan yang asal murah sering bikin biaya lanjutan lebih besar."
    },
    {
      stage: "Closing",
      title: "Script 6: Closing Saat Lead Sudah Tahu Harga",
      subtitle: "Lead sudah dapat gambaran biaya tapi belum berani commit",
      text: `Kalau lihat kebutuhan Kakak sejauh ini, opsi yang ini sebenarnya sudah cukup aman untuk bantu hasilnya lebih rapi dan terarah.

Tinggal sekarang saya bantu bereskan bagian yang masih mengganjal saja. Kakak masih perlu diskusi di titik mana?

Kalau ada yang belum klik, bilang saja ya Kakak. Saya bantu jawab sejelas mungkin supaya keputusan Kakak lebih mantap, bukan karena didorong-dorong.`,
      note: "Closing yang bagus bukan maksa deal, tapi mempersempit keraguan dan mengarahkan lead ke keputusan."
    },
    {
      stage: "Closing",
      title: "Script 7: Handle Keberatan Harga",
      subtitle: "Saat lead bilang “mahal”, “over budget”, atau masih banding-bandingkan",
      text: `Paham Kakak, pertimbangan budget memang penting.

Yang biasanya perlu dilihat bukan cuma nominal di awal, tapi hasil apa yang Kakak dapat dan masalah apa yang jadi tidak perlu Kakak tanggung sendiri.

Karena kalau dari awal salah arah, ujungnya sering lebih mahal di waktu, tenaga, revisi, atau hasil yang tidak sesuai.

Kalau Kakak mau, saya bisa bantu carikan opsi yang lebih pas dengan budget sekarang tanpa mengorbankan bagian yang paling penting.`,
      note: "Jangan langsung diskon. Pertahankan value dulu, lalu arahkan ke opsi yang lebih realistis."
    },
    {
      stage: "Closing",
      title: "Script 8: Ajak ke Konsultasi / Survey / Pengecekan",
      subtitle: "Mendorong lead ke langkah kecil berikutnya",
      text: `Supaya Kakak tidak menebak-nebak, langkah paling enak setelah ini sebenarnya konsultasi singkat / survey / pengecekan kebutuhan dulu.

Di situ kita bisa bahas:
- kondisi dan kebutuhan utamanya
- opsi yang paling cocok
- estimasi alur kerja
- langkah berikutnya kalau mau lanjut

Biasanya setelah sesi itu, keputusan jadi jauh lebih jelas karena Kakak tidak cuma lihat harga, tapi benar-benar paham arahnya.

Kalau berkenan, saya bantu atur jadwal yang nyaman untuk Kakak.`,
      note: "Target script ini micro-commitment. Kalau lead belum siap deal, dorong ke langkah yang risikonya kecil."
    },
    {
      stage: "Closing",
      title: "Script 9: Closing Final (Dorong Booking / Jadwal / DP)",
      subtitle: "Saat lead sudah cocok dan tinggal diarahkan ke tindakan konkret",
      text: `Kalau dari diskusi kita sejauh ini sudah cocok, saya sarankan langkah berikutnya diamankan sekarang ya Kakak.

Begitu jadwal / booking / proses awalnya secured, kami bisa lanjut bantu Kakak masuk ke tahap berikutnya dengan lebih tenang dan terarah.

Kalau Kakak setuju, saya kirim detail langkah lanjutnya sekarang supaya tidak muter lagi.`,
      note: "Saat lead sudah cocok, jangan kembali ke obrolan umum. Arahkan langsung ke action."
    },
    {
      stage: "Follow-up",
      title: "Script 10: Follow-up Lead yang Tiba-tiba Tidak Respons",
      subtitle: "Lead tadinya aktif lalu ghosting 2-3 hari",
      text: `Halo Kakak [Nama], izin follow up ya.

Kemarin kita sempat bahas soal ${niche.short}. Saya cek lagi karena biasanya chat suka tenggelam atau Kakak masih sempat bandingkan beberapa opsi.

Kalau masih dibutuhkan, saya siap bantu lanjutkan dari poin terakhir tanpa perlu ulang dari awal.

Kalau mau, balas saja:
"lanjut"
atau
"masih pertimbangkan"

Biar saya tahu harus bantu arahkan ke mana.`,
      note: "Buat follow-up serendah mungkin friksinya. Beri opsi jawaban pendek agar mudah dibalas."
    },
    {
      stage: "Follow-up",
      title: "Script 11: Follow-up Lead Lama (1-2 Minggu Tidak Respons)",
      subtitle: "Pesan terakhir untuk menghangatkan kembali lead yang sudah dingin",
      text: `Halo Kakak [Nama], saya izin hubungi lagi terkait kebutuhan ${niche.short} yang sempat kita bahas sebelumnya.

Saya follow up sekali lagi karena biasanya beberapa orang baru siap lanjut setelah kebutuhan dan pertimbangannya mulai lebih jelas.

Kalau kebutuhan Kakak masih ada, saya siap bantu lanjutkan dari chat sebelumnya dan arahkan ke opsi yang paling masuk akal.

Kalau belum timing-nya, tidak apa-apa juga Kakak. Nanti saat sudah siap, tinggal lanjut dari chat ini ya.`,
      note: "Lead lama jangan dikejar terlalu keras. Posisi terbaiknya: tetap sopan, tetap hadir, tetap mudah dihubungi."
    }
  ];
}

function buildChecklistEntries(niche) {
  const ageExamples = {
    WO: "22-35",
    CCTV: "28-45",
    "Service AC": "25-45",
    Renovasi: "28-50",
    Property: "28-45"
  };

  const hookExamples = {
    WO: "Takut Pernikahanmu Berantakan?",
    CCTV: "Takut Ada Area yang Tidak Terpantau?",
    "Service AC": "AC Tidak Dingin Saat Cuaca Lagi Panas?",
    Renovasi: "Takut Renovasi Molor dan Biaya Membengkak?",
    Property: "Takut Salah Pilih Properti yang Nilainya Besar?"
  };

  const sections = [
    {
      name: "Persiapan Akun & Teknis",
      items: [
        {
          priority: "Wajib",
          text: "WhatsApp Business sudah aktif dan ada nomor yang dimonitor aktif.",
          note: "Gunakan nomor yang sama dengan yang akan jadi tujuan chat dari iklan."
        },
        {
          priority: "Wajib",
          text: "Akun Meta Business Suite sudah dibuat dan terverifikasi.",
          note: "Buka business.facebook.com dan pastikan tidak pakai akun personal biasa."
        },
        {
          priority: "Wajib",
          text: "Halaman Facebook bisnis sudah ada dan terhubung ke Ads Manager.",
          note: "Iklan tidak bisa jalan tanpa Facebook Page yang aktif."
        },
        {
          priority: "Wajib",
          text: "Nomor WhatsApp sudah terhubung ke Meta Business Suite.",
          note: "Masuk ke Settings > WhatsApp > Add WhatsApp Number."
        },
        {
          priority: "Penting",
          text: "Instagram bisnis sudah terhubung ke Facebook Page.",
          note: "Supaya iklan bisa tayang di Instagram sekaligus."
        }
      ]
    },
    {
      name: "Setup Campaign yang Benar",
      items: [
        {
          priority: "Wajib",
          text: "Objective campaign dipilih: Messages atau Engagement > Conversations.",
          note: "Jangan pakai Traffic atau Reach karena tidak fokus menghasilkan chat."
        },
        {
          priority: "Wajib",
          text: "Destination diset ke WhatsApp, bukan Messenger atau Instagram DM.",
          note: "Pilih WhatsApp di bagian Messaging Apps saat setup Ad Set."
        },
        {
          priority: "Wajib",
          text: "Budget harian minimal Rp 20.000-50.000 per Ad Set aktif.",
          note: "Di bawah ini biasanya algoritma tidak punya data cukup untuk belajar."
        },
        {
          priority: "Penting",
          text: "Struktur campaign dibuat sederhana: 1 Campaign > 1-2 Ad Set > 2-3 variasi iklan.",
          note: "Jangan terlalu banyak Ad Set sekaligus karena bikin budget terfragmentasi."
        },
        {
          priority: "Penting",
          text: "Jadwal tayang iklan diset ke jam aktif target, misalnya 07.00-22.00.",
          note: "Hindari bayar tayangan tengah malam yang minim peluang menghasilkan chat."
        }
      ]
    },
    {
      name: "Targeting Audiens",
      items: [
        {
          priority: "Wajib",
          text: "Lokasi targeting sudah spesifik sesuai area jangkauan bisnis.",
          note: "Pilih kota atau radius kilometer, jangan seluruh Indonesia kalau bisnisnya lokal."
        },
        {
          priority: "Wajib",
          text: "Rentang usia disesuaikan dengan profil calon pelanggan niche kamu.",
          note: `Contoh untuk ${niche.short}: ${ageExamples[niche.short] || "25-45"}.`
        },
        {
          priority: "Penting",
          text: "Sudah ada minimal 1 Ad Set dengan targeting broad tanpa interest tambahan.",
          note: "Meta sekarang sering lebih efektif saat diberi ruang belajar yang lebih luas."
        },
        {
          priority: "Penting",
          text: "Bahasa target diset ke Indonesia jika bisnisnya lokal.",
          note: "Hindari tayangan ke audiens luar negeri yang tidak relevan."
        },
        {
          priority: "Bonus",
          text: "Sudah membuat Custom Audience dari database pelanggan lama untuk Lookalike.",
          note: "Upload CSV nomor HP pelanggan lalu buat Lookalike 1-3 persen untuk audiens mirip pelanggan."
        }
      ]
    },
    {
      name: "Materi Iklan (Kreatif)",
      items: [
        {
          priority: "Wajib",
          text: "Gambar iklan berformat 1:1 atau 4:5, bukan landscape.",
          note: "Format square atau portrait biasanya lebih dominan di feed mobile."
        },
        {
          priority: "Wajib",
          text: "Hook di gambar menyentuh masalah spesifik calon pelanggan.",
          note: `Contoh untuk ${niche.short}: "${hookExamples[niche.short] || "Takut Kebutuhanmu Salah Ditangani?"}".`
        },
        {
          priority: "Wajib",
          text: "Teks di gambar terbaca dalam 1 detik: font besar dan kontras tinggi.",
          note: "Ukuran teks utama sebaiknya besar dengan background yang mendukung keterbacaan."
        },
        {
          priority: "Penting",
          text: "Ada minimal 2 variasi gambar berbeda untuk A/B testing.",
          note: "Uji 2 angle berbeda, jangan cuma ganti warna atau posisi elemen kecil."
        },
        {
          priority: "Penting",
          text: "Nama atau logo bisnis terlihat di gambar tapi tidak mengalahkan hook.",
          note: "Taruh kecil di pojok sebagai branding, bukan sebagai judul utama."
        }
      ]
    },
    {
      name: "Ad Copy (Teks Iklan)",
      items: [
        {
          priority: "Wajib",
          text: "Kalimat pertama primary text langsung menyentuh masalah atau rasa takut.",
          note: "Tiga baris pertama menentukan apakah orang lanjut baca atau langsung scroll."
        },
        {
          priority: "Wajib",
          text: "Ada CTA eksplisit di primary text dengan link WA langsung.",
          note: 'Tulis jelas seperti: "Chat kami sekarang -> wa.me/62xxxxx", jangan hanya "Hubungi kami".'
        },
        {
          priority: "Wajib",
          text: "Headline maksimal 7 kata, kuat, dan berisi manfaat atau angka.",
          note: 'Contoh: "Nikah Tenang Tanpa Urus Vendor Sendiri".'
        },
        {
          priority: "Penting",
          text: "Ada bukti sosial di primary text seperti jumlah klien atau area yang sudah ditangani.",
          note: 'Contoh: "Sudah 200+ pelanggan di Surabaya" lebih dipercaya daripada klaim umum.'
        },
        {
          priority: "Penting",
          text: "Description melengkapi headline dengan 1 benefit tambahan.",
          note: "Jangan mengulang headline, tapi tambahkan sudut baru yang memperkuat alasan klik."
        }
      ]
    },
    {
      name: "Setelah Chat Masuk: Siap Closing",
      items: [
        {
          priority: "Wajib",
          text: "Auto-reply WA Business aktif untuk balas otomatis di luar jam kerja.",
          note: "Lead yang tidak dibalas dalam 5 menit sering pindah ke kompetitor."
        },
        {
          priority: "Wajib",
          text: "Template balasan pertama sudah disiapkan di Quick Replies WA Business.",
          note: "Jangan ketik manual tiap kali karena lambat dan bikin jawaban tidak konsisten."
        },
        {
          priority: "Wajib",
          text: "Ada sistem pencatatan lead masuk, minimal Google Sheets sederhana.",
          note: "Catat nama, tanggal, sumber, dan status supaya follow-up tidak mengandalkan ingatan."
        },
        {
          priority: "Penting",
          text: "Jadwal follow-up sudah direncanakan: H+1, H+3, dan H+7 untuk lead yang diam.",
          note: "Banyak closing justru terjadi di follow-up kedua atau ketiga, bukan chat pertama."
        },
        {
          priority: "Bonus",
          text: "Ada penawaran atau alasan spesifik saat follow-up, bukan sekadar sapaan kosong.",
          note: 'Contoh: "Kami ada slot konsultasi gratis minggu ini, Kakak mau saya cekkan?"'
        }
      ]
    }
  ];

  let counter = 0;

  return sections.flatMap((section, sectionIndex) => section.items.map((item) => {
    counter += 1;

    return {
      id: `section-${sectionIndex + 1}-item-${counter}`,
      section: section.name,
      priority: item.priority,
      title: `Checklist ${counter}`,
      subtitle: `Section: ${section.name}`,
      text: item.text,
      note: item.note
    };
  }));
}

function renderLanding() {
  const nicheCards = Object.entries(nicheMap).map(([, niche]) => `
    <article class="niche-card">
      <span class="niche-chip">${niche.short}</span>
      <h3>${niche.title}</h3>
      <p>${niche.intro}</p>
    </article>
  `).join("");

  return `
    <section class="hero">
      <div class="eyebrow">Bonus Supersales</div>
      <h1>Halaman Bonus <span class="accent">Per Niche</span> yang Siap Dipakai</h1>
      <p>
        Pilih niche yang ingin dibuka. Setiap halaman niche berisi tiga bonus utama:
        30 konten iklan siap pakai, 11 script WA, dan checklist penting yang sudah disiapkan untuk operasional harian.
      </p>
      <div class="stats">
        <article class="stat"><strong>5 Halaman</strong><span>Satu halaman fokus untuk satu niche.</span></article>
        <article class="stat"><strong>150 Iklan</strong><span>Total 30 paket konten iklan per niche.</span></article>
        <article class="stat"><strong>55 Script WA</strong><span>Total 11 script WA per niche.</span></article>
        <article class="stat"><strong>Siap Copy</strong><span>Tinggal edit nama brand, area, dan nomor WA.</span></article>
      </div>
    </section>
    <h2 class="section-title">Pilih <span class="accent">Halaman Bonus</span></h2>
    <p class="section-copy">
      Struktur sekarang dipisah per niche supaya setiap halaman langsung fokus ke kebutuhan pengusaha di niche tersebut.
      Tidak ada campuran niche dalam satu tampilan.
    </p>
    <section class="niche-grid">${nicheCards}</section>
  `;
}

function renderPrivateIndex() {
  return `
    <section class="hero">
      <div class="eyebrow">Private Page</div>
      <h1>Halaman ini <span class="accent">tidak dipublikasikan</span></h1>
      <p>
        Akses halaman bonus hanya melalui URL langsung yang sudah dibagikan.
        Tidak ada navigasi publik dari halaman ini.
      </p>
    </section>
  `;
}

function renderAssetItem(tabId, item) {
  if (tabId === "konten-iklan") {
    const combined = [
      item.title,
      item.subtitle,
      "",
      "PROMPT GAMBAR:",
      item.prompt,
      "",
      "PRIMARY TEXT:",
      item.primaryText,
      "",
      "HEADLINE:",
      item.headline,
      "",
      "DESCRIPTION:",
      item.description
    ].join("\n");

    return `
      <article class="asset-item">
        <div class="asset-head">
          <div>
            <h3>${item.title}</h3>
            <p>${item.subtitle}</p>
          </div>
          <button class="copy-button" data-copy="${encodeURIComponent(combined)}">Copy 1 Paket</button>
        </div>
        <div class="asset-body">
          <div class="tag-row">
            <span class="tag">Prompt Gambar</span>
            <span class="tag">Primary Text</span>
            <span class="tag">Headline</span>
            <span class="tag">Description</span>
          </div>
          <div class="text-box">
            <strong>Prompt Gambar 1:1</strong>
            <pre>${item.prompt}</pre>
          </div>
          <div class="copy-box">
            <strong>Ad Copy</strong>
            <div class="copy-grid">
              <div class="copy-field"><span>Primary Text</span><div>${item.primaryText}</div></div>
              <div class="copy-field"><span>Headline</span><div>${item.headline}</div></div>
              <div class="copy-field"><span>Description</span><div>${item.description}</div></div>
            </div>
          </div>
        </div>
      </article>
    `;
  }

  if (tabId === "checklist") {
    return `
      <article class="asset-item checklist-item ${item.done ? "done" : ""}">
        <div class="asset-body">
          <label class="checklist-row">
            <input
              class="checklist-toggle"
              type="checkbox"
              data-checklist-id="${item.id}"
              ${item.done ? "checked" : ""}
            />
            <span class="checklist-copy">
              <span class="checklist-top">
                <span class="checklist-section">${item.section}</span>
                <span class="checklist-priority">${item.priority}</span>
              </span>
              <strong>${item.title}</strong>
              <p>${item.text}</p>
            </span>
          </label>
          ${item.note ? `<div class="check-note"><strong>Catatan</strong><p>${item.note}</p></div>` : ""}
        </div>
      </article>
    `;
  }

  return `
    <article class="asset-item">
      <div class="asset-head">
        <div>
          ${tabId === "script-wa" && item.stage ? `<span class="asset-stage">${item.stage}</span>` : ""}
          <h3>${item.title}</h3>
          <p>${item.subtitle}</p>
        </div>
        <button class="copy-button" data-copy="${encodeURIComponent(item.text)}">Copy</button>
      </div>
      <div class="asset-body">
        <div class="check-box">
          <strong>${tabId === "script-wa" ? "Script Siap Kirim" : "Isi Checklist"}</strong>
          ${tabId === "script-wa" ? `<div class="text-box"><pre>${item.text}</pre></div>${item.note ? `<div class="script-note">${item.note}</div>` : ""}` : `<p>${item.text}</p>${item.note ? `<div class="check-note"><strong>Catatan</strong><p>${item.note}</p></div>` : ""}`}
        </div>
      </div>
    </article>
  `;
}

function createPageApp(root, slug) {
  const niche = nicheMap[slug];
  if (!niche) {
    root.innerHTML = renderLanding();
    return;
  }

  const dataByTab = {
    "konten-iklan": buildAdEntries(niche),
    "script-wa": buildScriptEntries(niche),
    checklist: buildChecklistEntries(niche)
  };

  const summaries = {
    "konten-iklan": [
      { title: "Isi Bonus", body: "30 paket prompt gambar 1:1 lengkap dengan ad copy siap pakai." },
      { title: "Cara Pakai", body: "Copy prompt ke image generator, lalu copy ad copy ke Meta Ads." },
      { title: "Kustomisasi", body: "Cukup ganti nama brand, area layanan, nomor WA, atau detail promo." }
    ],
    "script-wa": [
      { title: "Isi Bonus", body: "11 script WA untuk respon cepat, follow up, objection, dan closing." },
      { title: "Cara Pakai", body: "Tinggal copy lalu sesuaikan nama prospek atau kebutuhan singkatnya." },
      { title: "Fokus", body: "Dibuat sesuai alur chat yang paling sering terjadi di niche ini." }
    ],
    checklist: [
      { title: "Isi Bonus", body: "Checklist penting untuk admin, sales, dan owner setiap hari." },
      { title: "Cara Pakai", body: "Tinggal jalankan item satu per satu sebagai rutinitas kerja." },
      { title: "Fokus", body: "Menjaga lead tidak bocor, follow up rapi, dan iklan tetap sehat." }
    ]
  };

  const descriptions = {
    "konten-iklan": `Semua konten di bawah ini sudah berupa paket siap copy untuk promosi ${niche.short}.`,
    "script-wa": `Semua script di bawah ini dibuat untuk chat seputar ${niche.waContext}.`,
    checklist: `Checklist ini disusun supaya ${niche.checklistContext}.`
  };

  let activeTab = tabs[0].id;
  const checklistStorageKey = `bonus-supersales-checklist:${slug}`;

  function readChecklistProgress() {
    try {
      return JSON.parse(localStorage.getItem(checklistStorageKey) || "{}");
    } catch (error) {
      return {};
    }
  }

  function writeChecklistProgress(progress) {
    try {
      localStorage.setItem(checklistStorageKey, JSON.stringify(progress));
    } catch (error) {
      // Ignore storage errors and keep the UI functional.
    }
  }

  let checklistProgress = readChecklistProgress();

  function render() {
    const tabMeta = tabs.find((tab) => tab.id === activeTab);
    const items = activeTab === "checklist"
      ? dataByTab[activeTab].map((item) => ({ ...item, done: Boolean(checklistProgress[item.id]) }))
      : dataByTab[activeTab];

    const checklistDoneCount = dataByTab.checklist.filter((item) => checklistProgress[item.id]).length;
    const checklistSummary = `Progress ${checklistDoneCount}/${dataByTab.checklist.length} item selesai`;

    root.innerHTML = `
      <section class="hero">
        <div class="eyebrow">${niche.short} Bonus Page</div>
        <h1>${niche.title} <span class="accent">Siap Pakai</span></h1>
        <p>${niche.intro}</p>
        <div class="stats">
          <article class="stat"><strong>30 Konten</strong><span>Prompt gambar + ad copy siap publish.</span></article>
          <article class="stat"><strong>11 Script</strong><span>Balas chat, follow up, dan closing.</span></article>
          <article class="stat"><strong>Checklist</strong><span>Rutinitas penting untuk admin dan owner.</span></article>
          <article class="stat"><strong>Direct Use</strong><span>Bukan panduan, tapi bahan yang tinggal dipakai.</span></article>
        </div>
      </section>
      <section class="page-grid" id="konten">
        <aside class="tabs">
          <h2>Menu Bonus</h2>
          <p>Pilih bonus yang ingin dipakai. Setiap bagian berisi konten siap copy untuk niche ${niche.short}.</p>
          <div class="tab-list">
            ${tabs.map((tab) => {
              const parts = getTabLabelParts(tab.label);
              return `
              <button class="tab-button ${tab.id === activeTab ? "active" : ""}" data-tab="${tab.id}">
                <span class="tab-count">${parts.count || "&bull;"}</span>
                <span class="tab-copy">
                  <span class="tab-label">${parts.title}</span>
                  <span class="tab-meta">${tab.summary}</span>
                </span>
              </button>
            `;
            }).join("")}
          </div>
        </aside>
        <section class="content-card">
          <div class="content-head">
            <div>
              <h2>${tabMeta.label}</h2>
              <p>${descriptions[activeTab]}</p>
            </div>
            <div class="price-badge">
              <strong>${activeTab === "checklist" ? `${checklistDoneCount}/${dataByTab.checklist.length}` : tabMeta.price}</strong>
              <span>${activeTab === "checklist" ? checklistSummary : "Gratis untuk Anda"}</span>
            </div>
          </div>
          <div class="summary-grid">
            ${summaries[activeTab].map((item) => `
              <article class="summary-item">
                <strong>${item.title}</strong>
                <span>${item.body}</span>
              </article>
            `).join("")}
          </div>
          <div class="asset-list">
            ${items.map((item) => renderAssetItem(activeTab, item)).join("")}
          </div>
        </section>
      </section>
    `;

    root.querySelectorAll("[data-tab]").forEach((button) => {
      button.addEventListener("click", () => {
        activeTab = button.dataset.tab;
        render();
      });
    });

    root.querySelectorAll("[data-copy]").forEach((button) => {
      button.addEventListener("click", async () => {
        try {
          await navigator.clipboard.writeText(decodeURIComponent(button.dataset.copy));
          showToast("Teks berhasil disalin.");
        } catch (error) {
          showToast("Clipboard tidak tersedia.");
        }
      });
    });

    root.querySelectorAll("[data-checklist-id]").forEach((input) => {
      input.addEventListener("change", () => {
        checklistProgress = {
          ...checklistProgress,
          [input.dataset.checklistId]: input.checked
        };
        writeChecklistProgress(checklistProgress);
        render();
      });
    });
  }

  render();
}

function showToast(message) {
  let toast = document.querySelector(".toast");
  if (!toast) {
    toast = document.createElement("div");
    toast.className = "toast";
    document.body.appendChild(toast);
  }

  toast.textContent = message;
  toast.classList.add("visible");
  clearTimeout(showToast.timer);
  showToast.timer = window.setTimeout(() => {
    toast.classList.remove("visible");
  }, 1800);
}

document.addEventListener("DOMContentLoaded", () => {
  const root = document.getElementById("app");
  const slug = document.body.dataset.niche || "";
  const privateIndex = document.body.dataset.privateIndex === "true";
  if (!root) return;

  if (privateIndex) {
    root.innerHTML = renderPrivateIndex();
    return;
  }

  if (!slug) {
    root.innerHTML = renderLanding();
    return;
  }

  createPageApp(root, slug);
});
