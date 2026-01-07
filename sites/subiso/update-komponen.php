<?php
/**
 * Plugin Name: Subiso Komponen Manager
 * Description: Kelola data komponen untuk ditampilkan via shortcode Elementor.
 * Version: 1.0.0
 * Author: Subiso
 */

if (!defined('ABSPATH')) {
    exit;
}

const SUBISO_KOMPONEN_OPTION = 'subiso_komponen_data';

function subiso_komponen_default_data() {
    return [
        'title' => 'Paket Servis Komponen',
        'subtitle' => 'Layanan servis dan perawatan komponen mesin offset dengan harga transparan.',
        'items' => [
            [
                'title' => 'Papan SEK',
                'image' => 'https://everz-digital.site/wp-content/uploads/2026/01/SEK.jpg',
                'image_id' => '',
                'alt' => 'Papan SEK',
                'price' => 'Mulai Rp 8.000.000',
                'bullets' => [
                    'Perbaikan modul papan',
                    'Kalibrasi & pengujian',
                    'Mesin Heidelberg',
                ],
            ],
            [
                'title' => 'Papan POLAR',
                'image' => 'https://everz-digital.site/wp-content/uploads/2026/01/POLAR.jpg',
                'image_id' => '',
                'alt' => 'Papan POLAR',
                'price' => 'Mulai Rp 8.000.000',
                'bullets' => [
                    'Servis sistem kontrol',
                    'Sensor & wiring',
                    'Mesin offset',
                ],
            ],
            [
                'title' => 'RGP 2',
                'image' => 'https://everz-digital.site/wp-content/uploads/2026/01/RGP-2.jpg',
                'image_id' => '',
                'alt' => 'RGP 2',
                'price' => 'Mulai Rp 8.000.000',
                'bullets' => [
                    'Perbaikan papan',
                    'Pembersihan & penyetelan',
                    'Siap uji cetak',
                ],
            ],
            [
                'title' => 'SVT',
                'image' => 'https://everz-digital.site/wp-content/uploads/2026/01/SVT.jpg',
                'image_id' => '',
                'alt' => 'SVT',
                'price' => 'Mulai Rp 8.000.000',
                'bullets' => [
                    'Pengecekan motor & sensor',
                    'Kalibrasi presisi',
                    'Uji operasional',
                ],
            ],
            [
                'title' => 'Sensor Lay Depan',
                'image' => 'https://everz-digital.site/wp-content/uploads/2026/01/Sensor-front-lay.jpg',
                'image_id' => '',
                'alt' => 'Sensor Lay Depan',
                'price' => 'Mulai Rp 8.000.000',
                'bullets' => [
                    'Penyelarasan sensor',
                    'Kalibrasi presisi',
                    'Mesin offset',
                ],
            ],
            [
                'title' => 'Sensor Lay Samping',
                'image' => 'https://everz-digital.site/wp-content/uploads/2026/01/Sensor-Side-Lay.jpg',
                'image_id' => '',
                'alt' => 'Sensor Lay Samping',
                'price' => 'Mulai Rp 8.000.000',
                'bullets' => [
                    'Deteksi sensor',
                    'Stabilitas pemasukan',
                    'Pengujian mesin',
                ],
            ],
            [
                'title' => 'Servis Kompresor',
                'image' => 'https://everz-digital.site/wp-content/uploads/2026/01/Servis-kompressor.jpg',
                'image_id' => '',
                'alt' => 'Servis Kompresor',
                'price' => 'Mulai Rp 8.000.000',
                'bullets' => [
                    'Pengecekan tekanan',
                    'Servis & penyetelan',
                    'Sistem udara',
                ],
            ],
            [
                'title' => 'Motor Roll Air',
                'image' => 'https://everz-digital.site/wp-content/uploads/2026/01/roll-air.jpg',
                'image_id' => '',
                'alt' => 'Motor Roll Air',
                'price' => 'Mulai Rp 8.000.000',
                'bullets' => [
                    'Perawatan roll',
                    'Stabilitas kertas',
                    'Mesin offset',
                ],
            ],
            [
                'title' => 'Motor Roll Air',
                'image' => 'https://everz-digital.site/wp-content/uploads/2026/01/motor-air.jpg',
                'image_id' => '',
                'alt' => 'Motor Roll Air',
                'price' => 'Mulai Rp 8.000.000',
                'bullets' => [
                    'Pengecekan sistem motor',
                    'Penyetelan performa',
                    'Uji operasional',
                ],
            ],
        ],
    ];
}

function subiso_komponen_get_data() {
    $data = get_option(SUBISO_KOMPONEN_OPTION);
    if (!$data || !is_array($data)) {
        $data = subiso_komponen_default_data();
        update_option(SUBISO_KOMPONEN_OPTION, $data);
    }
    return $data;
}

function subiso_komponen_shortcode() {
    $data = subiso_komponen_get_data();
    $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];

    ob_start();
    ?>
    <section class="service-component-section">
      <div class="container">
        <div class="section-header">
          <h2><?php echo esc_html($data['title'] ?? ''); ?></h2>
          <p><?php echo esc_html($data['subtitle'] ?? ''); ?></p>
        </div>

        <div class="component-grid">
          <?php foreach ($items as $item) : ?>
            <?php
            $image_url = isset($item['image']) ? $item['image'] : '';
            $image_id = isset($item['image_id']) ? (int) $item['image_id'] : 0;
            if ($image_id) {
                $attachment_url = wp_get_attachment_image_url($image_id, 'full');
                if ($attachment_url) {
                    $image_url = $attachment_url;
                }
            }
            ?>
            <div class="component-card reveal-item">
              <div class="card-image">
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($item['alt'] ?? $item['title'] ?? ''); ?>">
                <span class="price-badge"><?php echo esc_html($item['price'] ?? ''); ?></span>
              </div>
              <div class="card-body">
                <h4><?php echo esc_html($item['title'] ?? ''); ?></h4>
                <ul>
                  <?php
                  $bullets = isset($item['bullets']) && is_array($item['bullets']) ? $item['bullets'] : [];
                  foreach ($bullets as $bullet) :
                  ?>
                    <li><?php echo esc_html($bullet); ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('subiso_komponen', 'subiso_komponen_shortcode');

function subiso_komponen_register_menu() {
    $hook = add_menu_page(
        'Subiso Komponen',
        'Subiso Komponen',
        'manage_options',
        'subiso-komponen',
        'subiso_komponen_admin_page',
        'dashicons-screenoptions',
        58
    );
    $GLOBALS['subiso_komponen_admin_hook'] = $hook;
}
add_action('admin_menu', 'subiso_komponen_register_menu');

function subiso_komponen_admin_assets($hook) {
    $target = isset($GLOBALS['subiso_komponen_admin_hook']) ? $GLOBALS['subiso_komponen_admin_hook'] : '';
    if ($hook !== $target) {
        return;
    }
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'subiso_komponen_admin_assets');

function subiso_komponen_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $data = subiso_komponen_get_data();

    if (isset($_POST['subiso_komponen_save'])) {
        check_admin_referer('subiso_komponen_save_action', 'subiso_komponen_nonce');

        $title = isset($_POST['title']) ? sanitize_text_field(wp_unslash($_POST['title'])) : '';
        $subtitle = isset($_POST['subtitle']) ? sanitize_text_field(wp_unslash($_POST['subtitle'])) : '';

        $items = [];
        if (!empty($_POST['item_title']) && is_array($_POST['item_title'])) {
            $count = count($_POST['item_title']);
            for ($i = 0; $i < $count; $i++) {
                $item_title = sanitize_text_field(wp_unslash($_POST['item_title'][$i] ?? ''));
                $item_image = esc_url_raw(wp_unslash($_POST['item_image'][$i] ?? ''));
                $item_image_id = absint($_POST['item_image_id'][$i] ?? 0);
                $item_alt = sanitize_text_field(wp_unslash($_POST['item_alt'][$i] ?? ''));
                $item_price = sanitize_text_field(wp_unslash($_POST['item_price'][$i] ?? ''));
                $bullets_raw = wp_unslash($_POST['item_bullets'][$i] ?? '');
                $bullets_lines = array_filter(array_map('trim', explode("\n", $bullets_raw)));

                if ($item_title === '' && $item_image === '' && !$item_image_id) {
                    continue;
                }

                $items[] = [
                    'title' => $item_title,
                    'image' => $item_image,
                    'image_id' => $item_image_id,
                    'alt' => $item_alt,
                    'price' => $item_price,
                    'bullets' => $bullets_lines,
                ];
            }
        }

        $data = [
            'title' => $title,
            'subtitle' => $subtitle,
            'items' => $items,
        ];

        update_option(SUBISO_KOMPONEN_OPTION, $data);
        echo '<div class="updated"><p>Data komponen berhasil disimpan.</p></div>';
    }

    $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
    ?>
    <div class="wrap">
      <h1>Subiso Komponen</h1>
      <p>Gunakan shortcode <code>[subiso_komponen]</code> di Elementor.</p>
      <form method="post">
        <?php wp_nonce_field('subiso_komponen_save_action', 'subiso_komponen_nonce'); ?>

        <table class="form-table">
          <tr>
            <th scope="row"><label for="subiso-title">Judul</label></th>
            <td><input type="text" id="subiso-title" name="title" class="regular-text" value="<?php echo esc_attr($data['title'] ?? ''); ?>"></td>
          </tr>
          <tr>
            <th scope="row"><label for="subiso-subtitle">Subjudul</label></th>
            <td><input type="text" id="subiso-subtitle" name="subtitle" class="regular-text" value="<?php echo esc_attr($data['subtitle'] ?? ''); ?>"></td>
          </tr>
        </table>

        <h2>Daftar Komponen</h2>
        <table class="widefat fixed" id="subiso-komponen-table">
          <thead>
            <tr>
              <th>Judul</th>
              <th>Gambar (URL / Upload)</th>
              <th>Alt</th>
              <th>Harga</th>
              <th>Bullet (1 per baris)</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $index => $item) : ?>
              <?php
              $row_image_url = isset($item['image']) ? $item['image'] : '';
              $row_image_id = isset($item['image_id']) ? (int) $item['image_id'] : 0;
              if ($row_image_id) {
                  $attachment_url = wp_get_attachment_image_url($row_image_id, 'thumbnail');
                  if ($attachment_url) {
                      $row_image_url = $attachment_url;
                  }
              }
              ?>
              <tr class="subiso-row">
                <td><input type="text" name="item_title[]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" class="regular-text"></td>
                <td>
                  <input type="hidden" name="item_image_id[]" value="<?php echo esc_attr($row_image_id); ?>" class="subiso-image-id">
                  <input type="text" name="item_image[]" value="<?php echo esc_attr($item['image'] ?? ''); ?>" class="regular-text subiso-image-url" placeholder="https://...">
                  <div style="margin-top:6px;">
                    <button type="button" class="button subiso-upload">Upload</button>
                  </div>
                  <div class="subiso-preview" style="margin-top:8px;">
                    <?php if ($row_image_url) : ?>
                      <img src="<?php echo esc_url($row_image_url); ?>" alt="" style="max-width:120px;height:auto;">
                    <?php endif; ?>
                  </div>
                </td>
                <td><input type="text" name="item_alt[]" value="<?php echo esc_attr($item['alt'] ?? ''); ?>" class="regular-text"></td>
                <td><input type="text" name="item_price[]" value="<?php echo esc_attr($item['price'] ?? ''); ?>" class="regular-text"></td>
                <td>
                  <textarea name="item_bullets[]" rows="4" class="large-text"><?php echo esc_textarea(implode("\n", $item['bullets'] ?? [])); ?></textarea>
                </td>
                <td><button type="button" class="button subiso-remove">Hapus</button></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <p>
          <button type="button" class="button" id="subiso-add-item">Tambah Komponen</button>
        </p>

        <p>
          <input type="submit" name="subiso_komponen_save" class="button button-primary" value="Simpan Data">
        </p>
      </form>
    </div>

    <script>
      (function() {
        const tableBody = document.querySelector('#subiso-komponen-table tbody');
        const addButton = document.getElementById('subiso-add-item');
        let mediaFrame = null;

        if (!tableBody || !addButton) return;

        addButton.addEventListener('click', function() {
          const row = document.createElement('tr');
          row.className = 'subiso-row';
          row.innerHTML =
            '<td><input type="text" name="item_title[]" class="regular-text"></td>' +
            '<td>' +
              '<input type="hidden" name="item_image_id[]" class="subiso-image-id">' +
              '<input type="text" name="item_image[]" class="regular-text subiso-image-url" placeholder="https://...">' +
              '<div style="margin-top:6px;">' +
                '<button type="button" class="button subiso-upload">Upload</button>' +
              '</div>' +
              '<div class="subiso-preview" style="margin-top:8px;"></div>' +
            '</td>' +
            '<td><input type="text" name="item_alt[]" class="regular-text"></td>' +
            '<td><input type="text" name="item_price[]" class="regular-text"></td>' +
            '<td><textarea name="item_bullets[]" rows="4" class="large-text"></textarea></td>' +
            '<td><button type="button" class="button subiso-remove">Hapus</button></td>';
          tableBody.appendChild(row);
        });

        tableBody.addEventListener('click', function(event) {
          if (event.target.classList.contains('subiso-remove')) {
            event.preventDefault();
            const row = event.target.closest('tr');
            if (row) row.remove();
          }

          if (event.target.classList.contains('subiso-upload')) {
            event.preventDefault();
            const row = event.target.closest('tr');
            if (!row || typeof wp === 'undefined' || !wp.media) return;

            if (!mediaFrame) {
              mediaFrame = wp.media({
                title: 'Pilih Gambar',
                button: { text: 'Gunakan gambar' },
                multiple: false
              });
            }

            mediaFrame.off('select');
            mediaFrame.on('select', function() {
              const attachment = mediaFrame.state().get('selection').first().toJSON();
              const urlInput = row.querySelector('.subiso-image-url');
              const idInput = row.querySelector('.subiso-image-id');
              const preview = row.querySelector('.subiso-preview');

              if (urlInput) urlInput.value = attachment.url || '';
              if (idInput) idInput.value = attachment.id || '';
              if (preview) {
                preview.innerHTML = attachment.url
                  ? '<img src="' + attachment.url + '" alt="" style="max-width:120px;height:auto;">'
                  : '';
              }
            });

            mediaFrame.open();
          }
        });

        tableBody.addEventListener('input', function(event) {
          if (!event.target.classList.contains('subiso-image-url')) {
            return;
          }
          const row = event.target.closest('tr');
          const idInput = row ? row.querySelector('.subiso-image-id') : null;
          const preview = row ? row.querySelector('.subiso-preview') : null;
          if (idInput) idInput.value = '';
          if (preview) {
            preview.innerHTML = event.target.value
              ? '<img src="' + event.target.value + '" alt="" style="max-width:120px;height:auto;">'
              : '';
          }
        });
      })();
    </script>
    <?php
}
