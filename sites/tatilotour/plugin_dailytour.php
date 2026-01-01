<?php
/**
 * Plugin Name: VTM Daily Tour Section
 * Description: Outputs the Daily Tour section with i18n, accordion, and WhatsApp modals.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
  exit;
}

function vtm_daily_tour_register_cpt() {
  $labels = array(
    'name' => 'Daily Tour Packages',
    'singular_name' => 'Daily Tour Package',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Daily Tour Package',
    'edit_item' => 'Edit Daily Tour Package',
    'new_item' => 'New Daily Tour Package',
    'view_item' => 'View Daily Tour Package',
    'search_items' => 'Search Daily Tour Packages',
    'not_found' => 'No daily tour packages found',
    'not_found_in_trash' => 'No daily tour packages found in Trash',
    'all_items' => 'Daily Tour Packages',
    'menu_name' => 'Daily Tour Packages',
  );

  register_post_type('vtm_daily_package', array(
    'labels' => $labels,
    'public' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_icon' => 'dashicons-palmtree',
    'supports' => array('title', 'thumbnail', 'page-attributes'),
  ));
}
add_action('init', 'vtm_daily_tour_register_cpt');

function vtm_daily_tour_add_metaboxes() {
  add_meta_box(
    'vtm_daily_tour_meta',
    'Daily Tour Package Details (TR / EN / ID)',
    'vtm_daily_tour_render_metabox',
    'vtm_daily_package',
    'normal',
    'default'
  );
}
add_action('add_meta_boxes', 'vtm_daily_tour_add_metaboxes');

function vtm_daily_tour_render_metabox($post) {
  wp_nonce_field('vtm_daily_tour_meta_save', 'vtm_daily_tour_meta_nonce');

  $langs = array(
    'tr' => 'Turkish (TR)',
    'en' => 'English (EN)',
    'id' => 'Indonesian (ID)',
  );

  $fields = array(
    'pack_title' => array('label' => 'Package Title', 'type' => 'text'),
    'price' => array('label' => 'Price Label', 'type' => 'text'),
    'desc' => array('label' => 'Short Description', 'type' => 'textarea'),
    'included' => array('label' => 'Included', 'type' => 'rich'),
    'excluded' => array('label' => 'Not Included', 'type' => 'rich'),
    'terms' => array('label' => 'Terms', 'type' => 'rich'),
    'program' => array('label' => 'Program', 'type' => 'rich'),
  );

  echo '<div style="display:grid;gap:18px;">';
  foreach ($langs as $lang => $lang_label) {
    echo '<div style="border:1px solid #e5e7eb;border-radius:10px;padding:12px;">';
    echo '<h4 style="margin:0 0 10px;font-size:14px;">' . esc_html($lang_label) . '</h4>';

    foreach ($fields as $key => $field) {
      $meta_key = 'vtm_daily_' . $key . '_' . $lang;
      $value = get_post_meta($post->ID, $meta_key, true);
      $label = $field['label'];

      echo '<label style="display:block;margin-bottom:6px;font-weight:600;">' . esc_html($label) . '</label>';
      if ($field['type'] === 'text') {
        echo '<input type="text" name="' . esc_attr($meta_key) . '" value="' . esc_attr($value) . '" style="width:100%;margin-bottom:12px;" />';
      } elseif ($field['type'] === 'rich') {
        wp_editor($value, $meta_key, array(
          'textarea_name' => $meta_key,
          'textarea_rows' => 6,
          'teeny' => true,
          'media_buttons' => false,
          'quicktags' => true,
        ));
        echo '<div style="margin-bottom:12px;"></div>';
      } else {
        echo '<textarea name="' . esc_attr($meta_key) . '" rows="4" style="width:100%;margin-bottom:12px;">' . esc_textarea($value) . '</textarea>';
      }
    }

    echo '<p style="margin:0;font-size:12px;color:#6b7280;">You can use the visual editor to add bullet lists.</p>';
    echo '</div>';
  }
  echo '</div>';
}

function vtm_daily_tour_save_meta($post_id) {
  if (!isset($_POST['vtm_daily_tour_meta_nonce']) || !wp_verify_nonce($_POST['vtm_daily_tour_meta_nonce'], 'vtm_daily_tour_meta_save')) {
    return;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  $langs = array('tr', 'en', 'id');
  $text_fields = array('pack_title', 'price');
  $textarea_fields = array('desc');
  $html_fields = array('included', 'excluded', 'terms', 'program');

  foreach ($langs as $lang) {
    foreach ($text_fields as $key) {
      $meta_key = 'vtm_daily_' . $key . '_' . $lang;
      if (isset($_POST[$meta_key])) {
        update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$meta_key]));
      }
    }
    foreach ($textarea_fields as $key) {
      $meta_key = 'vtm_daily_' . $key . '_' . $lang;
      if (isset($_POST[$meta_key])) {
        update_post_meta($post_id, $meta_key, sanitize_textarea_field($_POST[$meta_key]));
      }
    }
    foreach ($html_fields as $key) {
      $meta_key = 'vtm_daily_' . $key . '_' . $lang;
      if (isset($_POST[$meta_key])) {
        update_post_meta($post_id, $meta_key, wp_kses_post($_POST[$meta_key]));
      }
    }
  }
}
add_action('save_post_vtm_daily_package', 'vtm_daily_tour_save_meta');

function vtm_daily_tour_migrate_default_package() {
  if (!is_admin()) {
    return;
  }
  if (get_option('vtm_daily_tour_migrated')) {
    return;
  }

  $existing = get_posts(array(
    'post_type' => 'vtm_daily_package',
    'post_status' => 'any',
    'numberposts' => 1,
  ));
  if (!empty($existing)) {
    update_option('vtm_daily_tour_migrated', 1);
    return;
  }

  $post_id = wp_insert_post(array(
    'post_type' => 'vtm_daily_package',
    'post_status' => 'publish',
    'post_title' => 'Ubud Daily Tour',
    'menu_order' => 0,
  ));
  if (is_wp_error($post_id) || !$post_id) {
    return;
  }

  $data = array(
    'tr' => array(
      'pack_title' => 'Ubud Bölgesi Günlük Tur',
      'price' => 'Günlük Tur • Ubud',
      'desc' => 'Ubud’un doğası, kültürü ve ikonik noktalarını tek günde gezebileceğiniz dolu dolu rota.',
      'included' => '<ul><li>Araç + şoför + yakıt</li><li>Öğle yemeği</li><li>Yolculuk boyunca içme suyu</li><li>Otopark ücretleri</li></ul>',
      'excluded' => '<ul><li>Kişisel harcamalar</li><li>Turistik yerlere giriş biletleri (tapınaklar, plaj kulüpleri ve benzeri yerler)</li></ul>',
      'terms' => '<ul><li><strong>08.00</strong> konaklama yerinden alınış (pick up)</li><li><strong>16.00</strong> öğle yemeği</li><li><strong>19.00</strong> otele dönüş</li><li>Serbest zaman</li><li>19.00’dan sonra tur süresini uzatmak isteyen misafirler için saat başı <strong>8 Euro</strong> ek ücret uygulanır.</li></ul>',
      'program' => '<div class="vtm-daily-prog-head"><div><strong>Ubud Bölgesi – Gezilecek Yerler</strong></div><div style="font-size:12px;color:#6b7280;">Rota isteğe göre esnetilebilir.</div></div><ul><li><strong>Tegalalang Pirinç Terasları</strong> – Ünlü pirinç tarlası manzaraları</li><li><strong>Ubud Maymun Ormanı (Monkey Forest)</strong> – Doğal ortamda yaşayan maymunlar</li><li><strong>Ubud Sarayı (Ubud Palace)</strong> – Geleneksel Bali mimarisi</li><li><strong>Ubud Sanat Pazarı (Ubud Art Market)</strong> – El sanatları ve hediyelik eşyalar</li><li><strong>Tirta Empul Tapınağı</strong> – Kutsal su tapınağı</li><li><strong>Goa Gajah (Fil Mağarası)</strong> – Tarihi ve mistik mağara</li><li><strong>Campuhan Ridge Walk</strong> – Doğa yürüyüş parkuru</li><li><strong>Tegenungan Şelalesi</strong> – Popüler ve kolay ulaşılabilir şelale</li><li><strong>Bali Zoo</strong> – Aileler için uygun hayvanat bahçesi deneyimi</li><li><strong>UC Silver & Altın Müzesi (UC Silver Museum)</strong> – Bali’nin gümüş ve mücevher sanatını keşfetme fırsatı</li><li><strong>Swing Heaven</strong> – Doğa manzaralı salıncak deneyimi</li><li><strong>ATV Ubud</strong> – Orman, nehir ve çamur parkurlarında ATV macerası</li><li><strong>Bali Salıncakları (Bali Swing)</strong> – Doğa manzaralı fotoğraf noktaları</li><li><strong>Kahve Plantasyonları</strong> – Luwak kahvesi tadımı ve bahçeler</li></ul>',
    ),
    'en' => array(
      'pack_title' => 'Ubud Area Daily Tour',
      'price' => 'Daily Tour • Ubud',
      'desc' => 'A packed one-day route covering Ubud’s nature, culture, and iconic highlights.',
      'included' => '<ul><li>Car + driver + fuel</li><li>Lunch</li><li>Drinking water during the trip</li><li>Parking fees</li></ul>',
      'excluded' => '<ul><li>Personal expenses</li><li>Entrance tickets to attractions (temples, beach clubs, etc.)</li></ul>',
      'terms' => '<ul><li><strong>08:00</strong> pick-up from your accommodation</li><li><strong>16:00</strong> lunch</li><li><strong>19:00</strong> return to hotel</li><li>Free time</li><li>After 19:00, an extra <strong>€8 per hour</strong> applies for extending the tour duration.</li></ul>',
      'program' => '<div class="vtm-daily-prog-head"><div><strong>Ubud Area – Places to Visit</strong></div><div style="font-size:12px;color:#6b7280;">Route can be adjusted based on your preference.</div></div><ul><li><strong>Tegalalang Rice Terraces</strong> – Iconic rice field views</li><li><strong>Ubud Monkey Forest</strong> – Monkeys in a natural habitat</li><li><strong>Ubud Palace</strong> – Traditional Balinese architecture</li><li><strong>Ubud Art Market</strong> – Handicrafts and souvenirs</li><li><strong>Tirta Empul Temple</strong> – Holy water temple</li><li><strong>Goa Gajah (Elephant Cave)</strong> – Historic and mystical cave</li><li><strong>Campuhan Ridge Walk</strong> – Scenic nature trail</li><li><strong>Tegenungan Waterfall</strong> – Popular, easy-to-reach waterfall</li><li><strong>Bali Zoo</strong> – Family-friendly zoo experience</li><li><strong>UC Silver Museum</strong> – Discover Bali’s silver & jewelry craft</li><li><strong>Swing Heaven</strong> – Jungle swing experience</li><li><strong>ATV Ubud</strong> – ATV adventure through forest, river & mud tracks</li><li><strong>Bali Swing</strong> – Photo spots with nature views</li><li><strong>Coffee Plantations</strong> – Luwak coffee tasting & gardens</li></ul>',
    ),
    'id' => array(
      'pack_title' => 'Daily Tour Area Ubud',
      'price' => 'Daily Tour • Ubud',
      'desc' => 'Rute seharian yang padat: alam, budaya, dan spot ikonik Ubud dalam satu hari.',
      'included' => '<ul><li>Mobil + driver + bensin</li><li>Makan siang</li><li>Air minum selama perjalanan</li><li>Biaya parkir</li></ul>',
      'excluded' => '<ul><li>Pengeluaran pribadi</li><li>Tiket masuk tempat wisata (pura, beach club, dan sejenisnya)</li></ul>',
      'terms' => '<ul><li><strong>08.00</strong> penjemputan di tempat menginap (pick up)</li><li><strong>16.00</strong> makan siang</li><li><strong>19.00</strong> kembali ke hotel</li><li>Waktu bebas</li><li>Setelah 19.00, perpanjangan durasi tour dikenakan biaya tambahan <strong>€8/jam</strong>.</li></ul>',
      'program' => '<div class="vtm-daily-prog-head"><div><strong>Area Ubud – Tempat yang Bisa Dikunjungi</strong></div><div style="font-size:12px;color:#6b7280;">Rute bisa disesuaikan sesuai preferensi.</div></div><ul><li><strong>Tegalalang Rice Terraces</strong> – Pemandangan sawah terasering ikonik</li><li><strong>Ubud Monkey Forest</strong> – Melihat monyet di habitat alami</li><li><strong>Ubud Palace</strong> – Arsitektur tradisional Bali</li><li><strong>Ubud Art Market</strong> – Kerajinan & oleh-oleh</li><li><strong>Tirta Empul Temple</strong> – Pura mata air suci</li><li><strong>Goa Gajah (Elephant Cave)</strong> – Goa bersejarah dan mistis</li><li><strong>Campuhan Ridge Walk</strong> – Jalur trekking alam</li><li><strong>Tegenungan Waterfall</strong> – Air terjun populer & mudah diakses</li><li><strong>Bali Zoo</strong> – Cocok untuk keluarga</li><li><strong>UC Silver Museum</strong> – Mengenal seni perak & perhiasan Bali</li><li><strong>Swing Heaven</strong> – Ayunan dengan view alam</li><li><strong>ATV Ubud</strong> – Petualangan ATV (hutan, sungai, jalur lumpur)</li><li><strong>Bali Swing</strong> – Spot foto dengan view alam</li><li><strong>Coffee Plantations</strong> – Cicip kopi luwak & kebun</li></ul>',
    ),
  );

  foreach ($data as $lang => $fields) {
    foreach ($fields as $key => $value) {
      $meta_key = 'vtm_daily_' . $key . '_' . $lang;
      update_post_meta($post_id, $meta_key, $value);
    }
  }

  update_option('vtm_daily_tour_migrated', 1);
}
add_action('admin_init', 'vtm_daily_tour_migrate_default_package');

function vtm_daily_tour_enqueue_assets() {
  if (is_admin()) {
    return;
  }

  $css = <<<'CSS'
.vtm-daily-full{width:100vw;margin-left:calc(50% - 50vw);margin-right:calc(50% - 50vw);background:#fff;}
.vtm-daily-wrap{max-width:1200px;margin:0 auto;padding:44px 5vw 52px;font-family:"Poppins",system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;}

.vtm-daily-eyebrow{text-align:center;font-size:12px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#e12726;margin-bottom:6px;}
.vtm-daily-title{text-align:center;font-size:26px;font-weight:900;color:#111827;margin:0 0 8px;line-height:1.15;}
.vtm-daily-subtitle{text-align:center;font-size:14px;color:#6b7280;max-width:740px;margin:0 auto 22px;line-height:1.65;}

.vtm-daily-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;align-items:stretch;}
@media (max-width:1100px){.vtm-daily-grid{grid-template-columns:repeat(2,minmax(0,1fr));}}
@media (max-width:900px){.vtm-daily-wrap{padding:38px 20px 46px;}.vtm-daily-title{font-size:22px;}.vtm-daily-grid{grid-template-columns:1fr;}}
@media (max-width:640px){.vtm-daily-wrap{padding:34px 16px 42px;}.vtm-daily-subtitle{font-size:13.5px;margin-bottom:18px;}}

.vtm-daily-card{
  background:#fff;border-radius:18px;overflow:hidden;border:1px solid #e5e7eb;
  box-shadow:0 10px 26px rgba(15,23,42,.08);
  display:flex;flex-direction:column;height:100%;
  transform:translateY(0);
  transition:transform .22s ease, box-shadow .22s ease;
  will-change: transform, opacity;
}
.vtm-daily-card:hover{transform:translateY(-3px);box-shadow:0 16px 40px rgba(15,23,42,.14);}

/* Scroll reveal (bolak-balik) */
.vtm-daily-card.vtm-reveal{opacity:0;transform:translateY(14px);transition:opacity .55s cubic-bezier(.2,.8,.2,1), transform .55s cubic-bezier(.2,.8,.2,1), box-shadow .22s ease;}
.vtm-daily-card.vtm-reveal.is-inview{opacity:1;transform:translateY(0);}

/* Image: keep original ratio (no crop) */
.vtm-daily-image{width:100%;background:#f3f4f6;padding:8px;}
.vtm-daily-image img{
  width:100% !important;
  height:auto !important;
  object-fit:contain !important;
  display:block !important;
  border-radius:14px;
  background:#fff;
  transition:transform .35s ease;
}
.vtm-daily-card:hover .vtm-daily-image img{transform:scale(1.01);}

.vtm-daily-content{padding:14px;display:flex;flex-direction:column;gap:8px;flex:1;}
.vtm-daily-head{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;}
.vtm-daily-card-title{font-size:16px;font-weight:900;color:#111827;margin:0;line-height:1.25;}
.vtm-daily-price{
  font-size:12px;font-weight:900;color:#e12726;margin:0;
  padding:6px 10px;border-radius:999px;background:#fff1f2;border:1px solid #ffe4e6;
  white-space:nowrap;align-self:flex-start;
}
.vtm-daily-card-desc{font-size:13px;color:#4b5563;line-height:1.6;margin:0;}

/* Accordion */
.vtm-daily-acc{border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;background:#fff;}
.vtm-daily-acc + .vtm-daily-acc{margin-top:8px;}
.vtm-daily-acc-head{
  width:100%;cursor:pointer;padding:10px 12px;
  display:flex;align-items:center;justify-content:space-between;gap:10px;
  font-weight:900;font-size:13px;color:#111827;
  background:#f9fafb;border:0;
  transition:background .18s ease;
}
.vtm-daily-acc-head:hover{background:#f3f4f6;}
.vtm-daily-acc-ico{
  display:inline-flex;align-items:center;justify-content:center;
  width:26px;height:26px;border-radius:999px;
  background:#ffffff;border:1px solid #e5e7eb;
  font-size:14px;line-height:1;opacity:.9;
  transform:rotate(0deg);
  transition:transform .22s ease, box-shadow .22s ease;
}
.vtm-daily-acc.is-open .vtm-daily-acc-ico{transform:rotate(180deg);box-shadow:0 10px 22px rgba(15,23,42,.10);}
.vtm-daily-acc-body{height:0;overflow:hidden;transition:height .26s cubic-bezier(.2,.8,.2,1);background:#fff;}
.vtm-daily-acc-inner{padding:10px 12px 12px;}
.vtm-daily-acc-inner ul{margin:0;padding-left:18px;display:grid;gap:6px;font-size:13px;color:#374151;line-height:1.55;}
.vtm-daily-acc-inner p{margin:0;font-size:13px;color:#374151;line-height:1.65;}

/* Buttons */
.vtm-daily-actions{margin-top:auto;display:grid;grid-template-columns:1fr 1fr;gap:10px;padding-top:8px;}
.vtm-daily-btn,.vtm-daily-btn-secondary{
  width:100%;
  padding:11px 12px;
  border-radius:12px;
  font-size:13.5px;
  font-weight:900;
  letter-spacing:.01em;
  cursor:pointer;
  transition:transform .2s ease, box-shadow .2s ease, background .2s ease, border-color .2s ease;
  white-space:nowrap;
}
.vtm-daily-btn{border:none;background:#e12726;color:#fff;}
.vtm-daily-btn:hover{background:#b71f1e;transform:translateY(-1px);}
.vtm-daily-btn-secondary{border:1px solid #e5e7eb;background:#fff;color:#111827;}
.vtm-daily-btn-secondary:hover{transform:translateY(-1px);box-shadow:0 12px 26px rgba(15,23,42,.10);border-color:#d1d5db;}

.vtm-daily-note{text-align:center;font-size:12px;color:#9ca3af;margin-top:12px;line-height:1.55;}

/* Modal */
.vtm-daily-modal{
  position:fixed; inset:0; display:none;
  align-items:center; justify-content:center;
  padding:18px; z-index:2147483647;
  background:transparent;
}
.vtm-daily-modal[aria-hidden="false"]{display:flex;}
.vtm-daily-modal::before{content:"";position:fixed; inset:0;background:rgba(17,24,39,.72);z-index:0;}
.vtm-daily-modal-panel{
  position:relative;z-index:1;
  width:min(720px,100%);
  background:#fff;border-radius:18px;
  overflow:hidden;
  box-shadow:0 30px 80px rgba(0,0,0,.35);
  max-height:calc(100vh - 36px);
  display:flex;flex-direction:column;
}
.vtm-daily-modal-head{
  display:flex;align-items:center;justify-content:space-between;
  padding:12px 14px;border-bottom:1px solid #e5e7eb;gap:10px;
}
.vtm-daily-modal-title{margin:0;font-size:13px;font-weight:900;letter-spacing:.02em;color:#111827;}
.vtm-daily-modal-close{
  width:38px;height:38px;border-radius:12px;border:1px solid #e5e7eb;background:#fff;
  cursor:pointer;font-size:18px;font-weight:900;
  display:inline-flex;align-items:center;justify-content:center;
}
.vtm-daily-modal-body{padding:14px;overflow:auto;-webkit-overflow-scrolling:touch;overscroll-behavior:contain;}
.vtm-daily-detail{display:grid;gap:10px;font-size:13.5px;color:#374151;line-height:1.75;}
.vtm-daily-detail h4{margin:10px 0 6px;font-size:14px;font-weight:900;color:#111827;}
.vtm-daily-prog-head{background:#f9fafb;border:1px solid #e5e7eb;border-radius:14px;padding:12px;display:grid;gap:6px;}
.vtm-daily-detail ul{margin:0;padding-left:18px;display:grid;gap:8px;}
.vtm-wa-chooser{display:grid;gap:10px;}
.vtm-wa-sub{font-size:13px;color:#6b7280;}
.vtm-wa-btn{
  width:100%;
  display:flex;align-items:center;justify-content:space-between;gap:12px;
  padding:12px 12px;border-radius:14px;
  border:1px solid #e5e7eb;background:#fff;
  cursor:pointer;font-weight:900;font-size:13px;color:#111827;
  transition:transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}
.vtm-wa-btn:hover{transform:translateY(-1px);box-shadow:0 12px 26px rgba(15,23,42,.10);border-color:#d1d5db;}
CSS;

  wp_register_style('vtm-daily-tour', false);
  wp_enqueue_style('vtm-daily-tour');
  wp_add_inline_style('vtm-daily-tour', $css);

  $js = <<<'JS'
(function(){
  const WA_TR="905396919570";
  const WA_ID="6281997438238";
  const DEFAULT_LANG="tr";

  const dictDaily = {
    tr:{
      "daily.eyebrow":"Günlük Turlar",
      "daily.title":"GÜNLÜK TURLAR",
      "daily.subtitle":"Bali’yi tek günde keşfet! Konforlu araç, profesyonel şoför ve esnek rota ile günün tadını çıkar.",
      "daily.note":"Rezervasyon WhatsApp üzerinden alınır. Tatilo Tour • Bali",

      "btn.book":"Şimdi Rezervasyon",
      "btn.detail":"Tur Programı",
      "inc.title":"Dahil olanlar",
      "exc.title":"Dahil olmayanlar",
      "daily.termsTitle":"Tur Koşulları",

      "modal.programTitle":"Tur Programı",
      "modal.bookingTitle":"Rezervasyon",
      "modal.chooseWa":"WhatsApp numarası seçin."
    },
    en:{
      "daily.eyebrow":"Daily Tour",
      "daily.title":"DAILY TOURS",
      "daily.subtitle":"Explore Bali in a day—comfortable car, professional driver, and a flexible route tailored to you.",
      "daily.note":"Reservations via WhatsApp. Tatilo Tour • Bali",

      "btn.book":"Book Now",
      "btn.detail":"Tour Program",
      "inc.title":"Included",
      "exc.title":"Not included",
      "daily.termsTitle":"Terms & Schedule",

      "modal.programTitle":"Tour Program",
      "modal.bookingTitle":"Booking",
      "modal.chooseWa":"Choose a WhatsApp number."
    },
    id:{
      "daily.eyebrow":"Daily Tour",
      "daily.title":"DAILY TOUR",
      "daily.subtitle":"Keliling Bali dalam sehari—mobil nyaman, driver profesional, dan rute fleksibel sesuai maumu.",
      "daily.note":"Reservasi via WhatsApp. Tatilo Tour • Bali",

      "btn.book":"Booking Sekarang",
      "btn.detail":"Program Tour",
      "inc.title":"Termasuk",
      "exc.title":"Tidak termasuk",
      "daily.termsTitle":"Ketentuan",

      "modal.programTitle":"Program Tour",
      "modal.bookingTitle":"Booking",
      "modal.chooseWa":"Pilih nomor WhatsApp."
    }
  };

  function normalizeLang(raw){
    const x = (raw || "").toLowerCase();
    if(x.startsWith("tr")) return "tr";
    if(x.startsWith("en")) return "en";
    if(x.startsWith("id")) return "id";
    return DEFAULT_LANG;
  }

  function getLang(){
    const attr = document.documentElement.getAttribute("data-lang");
    return normalizeLang(attr || DEFAULT_LANG);
  }

  function getI18nValue(el, attr, lang){
    if(!el) return "";
    const raw = el.getAttribute(attr);
    if(!raw) return "";
    const cache = el.__vtmI18n || (el.__vtmI18n = {});
    if(!cache[attr]){
      try{ cache[attr] = JSON.parse(raw); }catch{ cache[attr] = {}; }
    }
    const map = cache[attr] || {};
    return map[lang] || map[DEFAULT_LANG] || Object.values(map)[0] || "";
  }

  function getCardTitle(card, lang){
    const el = card?.querySelector(".vtm-daily-card-title");
    return getI18nValue(el, "data-vtm-i18n", lang);
  }

  function getCardProgram(card, lang){
    return getI18nValue(card, "data-vtm-program", lang);
  }

  function applyDailyLang(){
    const lang = dictDaily[getLang()] ? getLang() : DEFAULT_LANG;

    document.querySelectorAll("#daily-tour [data-i18n], .vtm-daily-modal [data-i18n]").forEach(el=>{
      const key = el.getAttribute("data-i18n");
      const val = dictDaily[lang] && dictDaily[lang][key];
      if(typeof val === "string") el.textContent = val;
    });

    document.querySelectorAll("[data-vtm-i18n]").forEach(el=>{
      const val = getI18nValue(el, "data-vtm-i18n", lang);
      if(typeof val === "string") el.textContent = val;
    });

    document.querySelectorAll("[data-vtm-i18n-html]").forEach(el=>{
      const val = getI18nValue(el, "data-vtm-i18n-html", lang);
      if(typeof val === "string") el.innerHTML = val;
    });

    const mProg = document.querySelector("#vtmDailyDetailTitle[data-i18n='modal.programTitle']");
    if(mProg) mProg.textContent = dictDaily[lang]["modal.programTitle"];

    const mBook = document.querySelector("#vtmDailyBookTitle[data-i18n='modal.bookingTitle']");
    if(mBook) mBook.textContent = dictDaily[lang]["modal.bookingTitle"];

    const mChoose = document.querySelector("#vtmDailyBookSub[data-i18n='modal.chooseWa']");
    if(mChoose) mChoose.textContent = dictDaily[lang]["modal.chooseWa"];
  }

  const cards = Array.from(document.querySelectorAll("#daily-tour .vtm-daily-card.vtm-reveal"));
  if("IntersectionObserver" in window && cards.length){
    const io = new IntersectionObserver((entries)=>{
      entries.forEach(ent=>{
        if(ent.isIntersecting) ent.target.classList.add("is-inview");
        else ent.target.classList.remove("is-inview");
      });
    }, { threshold: 0.18 });
    cards.forEach(c=>io.observe(c));
  } else {
    cards.forEach(c=>c.classList.add("is-inview"));
  }

  function setAccHeight(acc, open){
    const body = acc.querySelector(".vtm-daily-acc-body");
    if(!body) return;

    if(open){
      acc.classList.add("is-open");
      body.style.height="auto";
      const full=body.scrollHeight;
      body.style.height="0px";
      body.offsetHeight;
      body.style.height=full+"px";
      const done=()=>{body.style.height="auto";body.removeEventListener("transitionend",done);};
      body.addEventListener("transitionend",done);
    }else{
      const current=body.scrollHeight;
      body.style.height=current+"px";
      body.offsetHeight;
      body.style.height="0px";
      acc.classList.remove("is-open");
    }
  }

  document.addEventListener("click",(e)=>{
    const head = e.target.closest("#daily-tour .vtm-daily-acc-head");
    if(!head) return;

    const card = head.closest(".vtm-daily-card");
    const acc  = head.closest(".vtm-daily-acc");
    if(!acc) return;

    const isOpen = acc.classList.contains("is-open");
    if(!isOpen && card){
      card.querySelectorAll(".vtm-daily-acc.is-open").forEach(other=>{
        if(other!==acc) setAccHeight(other,false);
      });
    }
    setAccHeight(acc, !isOpen);
  });

  const detailModal=document.getElementById("vtmDailyDetailModal");
  const detailTitle=document.getElementById("vtmDailyDetailTitle");
  const detailBody=document.getElementById("vtmDailyDetailBody");

  const bookModal=document.getElementById("vtmDailyBookModal");
  const bookTitle=document.getElementById("vtmDailyBookTitle");
  const bookSub=document.getElementById("vtmDailyBookSub");
  let activeCard=null;

  function openModal(modalEl){
    if(!modalEl) return;
    document.querySelectorAll(".vtm-daily-modal").forEach(m=>m.setAttribute("aria-hidden","true"));
    modalEl.setAttribute("aria-hidden","false");
    document.body.style.overflow="hidden";
    modalEl.querySelector("[data-dt-close]")?.focus();
  }
  function closeModal(modalEl){
    if(!modalEl) return;
    modalEl.setAttribute("aria-hidden","true");
    const stillOpen=document.querySelector('.vtm-daily-modal[aria-hidden="false"]');
    if(!stillOpen) document.body.style.overflow="";
  }

  document.addEventListener("click",(e)=>{
    const closeBtn=e.target.closest("[data-dt-close]");
    if(closeBtn){ closeModal(closeBtn.closest(".vtm-daily-modal")); return; }
    const modalWrap=e.target.closest(".vtm-daily-modal");
    if(modalWrap && !e.target.closest(".vtm-daily-modal-panel")){
      closeModal(modalWrap); return;
    }
  });

  document.addEventListener("keydown",(e)=>{
    if(e.key!=="Escape") return;
    const opened=Array.from(document.querySelectorAll('.vtm-daily-modal[aria-hidden="false"]'));
    const top=opened[opened.length-1];
    if(top){ e.preventDefault(); closeModal(top); }
  });

  function messageByLang(pkg){
    const lang=getLang();
    const map={
      tr:`Merhaba Tatilo Tour, ${pkg} için rezervasyon yapmak istiyorum. Uygunluk ve detayları paylaşabilir misiniz?`,
      en:`Hi Tatilo Tour, I’d like to book: ${pkg}. Could you share availability and details?`,
      id:`Halo Tatilo Tour, saya ingin booking: ${pkg}. Mohon info ketersediaan & detailnya ya.`
    };
    return map[lang]||map.tr;
  }

  document.addEventListener("click",(e)=>{
    const btn=e.target.closest("[data-dt-book]");
    if(!btn) return;

    applyDailyLang();
    const lang=getLang();
    const card=btn.closest(".vtm-daily-card");
    activeCard=card;
    const pkg=getCardTitle(card, lang);

    if(bookTitle){
      const bookText=dictDaily[lang]["btn.book"];
      bookTitle.textContent=bookText+" • "+pkg;
    }
    if(bookSub){
      bookSub.textContent=dictDaily[lang]["modal.chooseWa"];
    }
    openModal(bookModal);
  });

  bookModal?.addEventListener("click",(e)=>{
    const pick=e.target.closest("[data-dt-wa]");
    if(!pick) return;

    const channel=pick.getAttribute("data-dt-wa");
    const number=(channel==="id")?WA_ID:WA_TR;
    const lang=getLang();
    const pkg=activeCard ? getCardTitle(activeCard, lang) : "";
    const msg=messageByLang(pkg);
    window.open("https://wa.me/"+number+"?text="+encodeURIComponent(msg),"_blank");
    closeModal(bookModal);
  });

  document.addEventListener("click",(e)=>{
    const btn=e.target.closest("[data-dt-detail-open]");
    if(!btn) return;

    applyDailyLang();
    const lang=getLang();
    const base=dictDaily[lang]["modal.programTitle"];
    const card=btn.closest(".vtm-daily-card");
    const pkg=getCardTitle(card, lang);
    if(detailTitle) detailTitle.textContent = base + " • " + pkg;
    if(detailBody) detailBody.innerHTML = getCardProgram(card, lang);
    openModal(detailModal);
  });

  function watchLangChange(){
    const root = document.documentElement;
    let last = root.getAttribute("data-lang") || DEFAULT_LANG;

    const mo = new MutationObserver(()=>{
      const now = root.getAttribute("data-lang") || DEFAULT_LANG;
      if(now === last) return;
      last = now;
      applyDailyLang();
    });
    mo.observe(root, { attributes:true, attributeFilter:["data-lang"] });
  }

  document.addEventListener("click",(e)=>{
    const langBtn=e.target.closest("[data-lang]");
    if(!langBtn) return;
    setTimeout(applyDailyLang, 0);
  });
  const sel=document.getElementById("vtmLangSelect");
  if(sel) sel.addEventListener("change", ()=>setTimeout(applyDailyLang,0));

  applyDailyLang();
  watchLangChange();
})();
JS;

  wp_register_script('vtm-daily-tour', false, array(), null, true);
  wp_enqueue_script('vtm-daily-tour');
  wp_add_inline_script('vtm-daily-tour', $js);
}

function vtm_daily_tour_shortcode() {
  $default_lang = 'tr';
  $langs = array('tr', 'en', 'id');
  $packages = new WP_Query(array(
    'post_type' => 'vtm_daily_package',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
  ));

  $get_map = function($post_id, $key, $langs) {
    $map = array();
    foreach ($langs as $lang) {
      $meta_key = 'vtm_daily_' . $key . '_' . $lang;
      $map[$lang] = get_post_meta($post_id, $meta_key, true);
    }
    return $map;
  };
  $pick_default = function($map, $default_lang) {
    if (!empty($map[$default_lang])) {
      return $map[$default_lang];
    }
    foreach ($map as $val) {
      if (!empty($val)) {
        return $val;
      }
    }
    return '';
  };
  $json_attr = function($map) {
    return esc_attr(wp_json_encode($map));
  };

  ob_start();
  ?>
<section class="vtm-daily-full" id="daily-tour">
  <div class="vtm-daily-wrap">
    <div class="vtm-daily-eyebrow" data-i18n="daily.eyebrow">Günlük Turlar</div>
    <h2 class="vtm-daily-title" data-i18n="daily.title">GÜNLÜK TURLAR</h2>
    <p class="vtm-daily-subtitle" data-i18n="daily.subtitle">
      Bali’yi tek günde keşfet! Konforlu araç, profesyonel şoför ve esnek rota ile günün tadını çıkar.
    </p>

    <div class="vtm-daily-grid">
      <?php if ($packages->have_posts()) : ?>
        <?php while ($packages->have_posts()) : $packages->the_post(); ?>
          <?php
            $post_id = get_the_ID();
            $title_map = $get_map($post_id, 'pack_title', $langs);
            $price_map = $get_map($post_id, 'price', $langs);
            $desc_map = $get_map($post_id, 'desc', $langs);
            $included_map = $get_map($post_id, 'included', $langs);
            $excluded_map = $get_map($post_id, 'excluded', $langs);
            $terms_map = $get_map($post_id, 'terms', $langs);
            $program_map = $get_map($post_id, 'program', $langs);

            $title_default = $pick_default($title_map, $default_lang);
            $price_default = $pick_default($price_map, $default_lang);
            $desc_default = $pick_default($desc_map, $default_lang);
            $included_default = $pick_default($included_map, $default_lang);
            $excluded_default = $pick_default($excluded_map, $default_lang);
            $terms_default = $pick_default($terms_map, $default_lang);
            $image_url = get_the_post_thumbnail_url($post_id, 'large');
          ?>
      <article class="vtm-daily-card vtm-reveal" data-vtm-program="<?php echo $json_attr($program_map); ?>">
        <div class="vtm-daily-image">
          <?php if ($image_url) : ?>
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title_default); ?>" loading="lazy" decoding="async"/>
          <?php endif; ?>
        </div>

        <div class="vtm-daily-content">
          <div class="vtm-daily-head">
            <h3 class="vtm-daily-card-title" data-vtm-i18n="<?php echo $json_attr($title_map); ?>"><?php echo esc_html($title_default); ?></h3>
            <div class="vtm-daily-price" data-vtm-i18n="<?php echo $json_attr($price_map); ?>"><?php echo esc_html($price_default); ?></div>
          </div>

          <p class="vtm-daily-card-desc" data-vtm-i18n="<?php echo $json_attr($desc_map); ?>"><?php echo esc_html($desc_default); ?></p>

          <div class="vtm-daily-acc" data-dt-acc>
            <button class="vtm-daily-acc-head" type="button">
              <span data-i18n="inc.title">Dahil olanlar</span>
              <span class="vtm-daily-acc-ico" aria-hidden="true">▾</span>
            </button>
            <div class="vtm-daily-acc-body">
              <div class="vtm-daily-acc-inner" data-vtm-i18n-html="<?php echo $json_attr($included_map); ?>"><?php echo wp_kses_post($included_default); ?></div>
            </div>
          </div>

          <div class="vtm-daily-acc" data-dt-acc>
            <button class="vtm-daily-acc-head" type="button">
              <span data-i18n="exc.title">Dahil olmayanlar</span>
              <span class="vtm-daily-acc-ico" aria-hidden="true">▾</span>
            </button>
            <div class="vtm-daily-acc-body">
              <div class="vtm-daily-acc-inner" data-vtm-i18n-html="<?php echo $json_attr($excluded_map); ?>"><?php echo wp_kses_post($excluded_default); ?></div>
            </div>
          </div>

          <div class="vtm-daily-acc" data-dt-acc>
            <button class="vtm-daily-acc-head" type="button">
              <span data-i18n="daily.termsTitle">Tur Koşulları</span>
              <span class="vtm-daily-acc-ico" aria-hidden="true">▾</span>
            </button>
            <div class="vtm-daily-acc-body">
              <div class="vtm-daily-acc-inner" data-vtm-i18n-html="<?php echo $json_attr($terms_map); ?>"><?php echo wp_kses_post($terms_default); ?></div>
            </div>
          </div>

          <div class="vtm-daily-actions">
            <button class="vtm-daily-btn" type="button" data-dt-book data-i18n="btn.book">Şimdi Rezervasyon</button>
            <button class="vtm-daily-btn-secondary" type="button" data-dt-detail-open data-i18n="btn.detail">Tur Programı</button>
          </div>
        </div>
      </article>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
      <?php endif; ?>
    </div>

    <div class="vtm-daily-note" data-i18n="daily.note">Rezervasyon WhatsApp üzerinden alınır. Tatilo Tour • Bali</div>
  </div>
</section>

<div class="vtm-daily-modal" id="vtmDailyDetailModal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Daily Tour Program">
  <div class="vtm-daily-modal-panel" role="document">
    <div class="vtm-daily-modal-head">
      <h3 class="vtm-daily-modal-title" id="vtmDailyDetailTitle" data-i18n="modal.programTitle">Tur Programı</h3>
      <button class="vtm-daily-modal-close" type="button" data-dt-close aria-label="Close">✕</button>
    </div>
    <div class="vtm-daily-modal-body">
      <div class="vtm-daily-detail" id="vtmDailyDetailBody"></div>
    </div>
  </div>
</div>

<div class="vtm-daily-modal" id="vtmDailyBookModal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Daily Booking">
  <div class="vtm-daily-modal-panel" role="document">
    <div class="vtm-daily-modal-head">
      <h3 class="vtm-daily-modal-title" id="vtmDailyBookTitle" data-i18n="modal.bookingTitle">Booking</h3>
      <button class="vtm-daily-modal-close" type="button" data-dt-close aria-label="Close">✕</button>
    </div>
    <div class="vtm-daily-modal-body">
      <div class="vtm-wa-chooser">
        <div class="vtm-wa-sub" id="vtmDailyBookSub" data-i18n="modal.chooseWa">WhatsApp numarası seçin.</div>
        <button class="vtm-wa-btn" type="button" data-dt-wa="tr">
          <span>WhatsApp TR</span><span>+90 539 691 9570</span>
        </button>
        <button class="vtm-wa-btn" type="button" data-dt-wa="id">
          <span>WhatsApp ID</span><span>+62 819 9743 8238</span>
        </button>
      </div>
    </div>
  </div>
</div>
<?php
  return ob_get_clean();
}

add_shortcode('vtm_daily_tour', 'vtm_daily_tour_shortcode');
add_action('wp_enqueue_scripts', 'vtm_daily_tour_enqueue_assets');
