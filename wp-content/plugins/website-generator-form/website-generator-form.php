<?php
/**
 * Plugin Name: Website Generator Form
 * Description: Form input client untuk kebutuhan pembuatan website.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
  exit;
}

function wg_register_submission_cpt() {
  register_post_type('wg_submission', array(
    'labels' => array(
      'name' => 'Website Requests',
      'singular_name' => 'Website Request',
      'add_new_item' => 'Add Website Request',
      'edit_item' => 'Edit Website Request',
    ),
    'public' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_icon' => 'dashicons-feedback',
    'supports' => array('title'),
  ));
}
add_action('init', 'wg_register_submission_cpt');

function wg_form_shortcode() {
  $submitted = isset($_GET['wg_submitted']) && $_GET['wg_submitted'] === '1';

  ob_start();
  ?>
  <div class="wg-wrapper" data-wg-submitted="<?php echo $submitted ? '1' : '0'; ?>">
    <form id="wg-form" class="wg-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="wg_submit" />
      <?php wp_nonce_field('wg_submit_form', 'wg_nonce'); ?>
      <input type="hidden" name="wg_payload" id="wg-payload" value="" />

      <section class="wg-card">
        <h2>Profil Usaha</h2>
        <div class="wg-grid-2">
          <div class="wg-field">
            <label for="wg-nama-usaha">Nama Usaha</label>
            <input id="wg-nama-usaha" name="nama_usaha" placeholder="PT. Aku Bisa" required />
          </div>
          <div class="wg-field">
            <label for="wg-bidang-usaha">Bidang Usaha</label>
            <input id="wg-bidang-usaha" name="bidang_usaha" placeholder="Tour & Travel" required />
          </div>
        </div>

        <div class="wg-grid-2">
          <div class="wg-field">
            <label for="wg-nama-website">Nama Website</label>
            <input id="wg-nama-website" name="nama_website" placeholder="akubisa.com" required />
          </div>
          <div class="wg-field">
            <label for="wg-no-hp">No HP</label>
            <input id="wg-no-hp" name="no_hp" placeholder="089238273823" inputmode="tel" required />
          </div>
        </div>

        <div class="wg-grid-2">
          <div class="wg-field">
            <label for="wg-email">Email</label>
            <input id="wg-email" name="email" type="email" placeholder="akubisa@gmail.com" required />
          </div>
          <div class="wg-field">
            <label for="wg-logo">Logo (Upload)</label>
            <div class="wg-upload">
              <input id="wg-logo" name="logo" type="file" accept="image/*" />
              <div class="wg-preview-grid" id="wg-logo-preview"></div>
            </div>
          </div>
        </div>

        <div class="wg-field">
          <label for="wg-alamat-usaha">Alamat Usaha</label>
          <textarea
            id="wg-alamat-usaha"
            name="alamat_usaha"
            placeholder="Bali, Indonesia (lengkap supaya bisa dijadikan maps)"
            required
          ></textarea>
        </div>
      </section>

      <section class="wg-card">
        <h2>Layanan & Paket</h2>
        <div class="wg-field">
          <label for="wg-layanan-input">Nama Layanan</label>
          <div class="wg-service-input">
            <input
              id="wg-layanan-input"
              name="layanan_input"
              placeholder="Contoh: Rental Mobil, Paket Wisata"
            />
            <button class="wg-btn wg-add-service" type="button">Tambah Layanan</button>
          </div>
          <p class="wg-helper">
            Setiap layanan berisi beberapa paket. Tambahkan paket sebanyak yang dibutuhkan.
          </p>
        </div>
        <div class="wg-service-sections" id="wg-layanan-sections" aria-live="polite"></div>
        <button class="wg-btn wg-add-service" type="button">Tambah Layanan</button>
        <p class="wg-helper">
          Pisahkan setiap layanan dalam section berbeda. Contoh layanan: Rental Mobil, Paket Wisata,
          Airport Transfer, Trekking, Umroh. Jangan gabungkan layanan berbeda dalam satu paket.
        </p>
      </section>

      <section class="wg-card">
        <h2>Gallery</h2>
        <div class="wg-field">
          <label for="wg-gallery-upload">Upload Foto Gallery</label>
          <div class="wg-upload">
            <input id="wg-gallery-upload" name="gallery_upload" type="file" accept="image/*" multiple />
            <div class="wg-preview-grid" id="wg-gallery-preview"></div>
          </div>
          <p class="wg-helper">Upload beberapa foto terbaik yang mewakili usaha dan layanan.</p>
        </div>
      </section>

      <section class="wg-card">
        <h2>Testimoni</h2>
        <p class="wg-helper">Bisa upload hasil screenshot chat/ulasan, atau isi teks saja.</p>
        <div class="wg-field">
          <label for="wg-testimoni-text">Testimoni (Input Text)</label>
          <textarea
            id="wg-testimoni-text"
            name="testimoni_text"
            placeholder="Contoh: Pelayanan cepat, mobil bersih, driver ramah."
          ></textarea>
        </div>
        <div class="wg-field">
          <label for="wg-testimoni-upload">Upload Screenshot Testimoni</label>
          <div class="wg-upload">
            <input
              id="wg-testimoni-upload"
              name="testimoni_upload"
              type="file"
              accept="image/*"
              multiple
            />
            <div class="wg-preview-grid" id="wg-testimoni-preview"></div>
          </div>
        </div>
      </section>

      <section class="wg-card wg-submit-bar">
        <button class="wg-btn" type="submit">Submit Form Website</button>
      </section>
    </form>

    <div class="wg-modal" id="wg-modal">
      <div class="wg-modal-content">
        <h3>Terima kasih sudah input data!</h3>
        <p>
          Mohon ditunggu, tim kami akan menghubungi Anda untuk memberikan hasil preview awal
          websitenya.
        </p>
        <button class="wg-btn wg-btn-secondary" type="button" id="wg-modal-close">Tutup</button>
      </div>
    </div>
  </div>

  <style>
    @import url("https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;500;600&family=Merriweather:wght@400;700&display=swap");

    .wg-wrapper {
      font-family: "Source Sans 3", "Segoe UI", sans-serif;
      color: #1b1c1d;
      display: grid;
      gap: 24px;
    }

    .wg-form {
      display: grid;
      gap: 24px;
    }

    .wg-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 24px;
      border: 1px solid rgba(27, 28, 29, 0.08);
      box-shadow: 0 18px 40px rgba(27, 28, 29, 0.08);
    }

    .wg-card h2 {
      font-family: "Merriweather", "Georgia", serif;
      margin: 0 0 12px;
      font-size: 1.4rem;
    }

    .wg-helper {
      margin: 6px 0 0;
      font-size: 0.9rem;
      color: rgba(27, 28, 29, 0.65);
    }

    .wg-card h2 + .wg-helper {
      margin-top: -4px;
    }

    .wg-grid-2 {
      display: grid;
      gap: 16px;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    .wg-field {
      display: grid;
      gap: 8px;
    }

    .wg-field label {
      font-weight: 600;
      font-size: 0.95rem;
    }

    .wg-field input,
    .wg-field textarea {
      width: 100%;
      padding: 12px 14px;
      border-radius: 12px;
      border: 1px solid rgba(27, 28, 29, 0.12);
      font-family: inherit;
      font-size: 0.98rem;
      background: #fffdf9;
    }

    .wg-field textarea {
      min-height: 90px;
      resize: vertical;
    }

    .wg-service-input {
      display: grid;
      gap: 10px;
      grid-template-columns: minmax(0, 1fr) auto;
      align-items: center;
    }

    .wg-btn {
      border: none;
      padding: 11px 18px;
      border-radius: 12px;
      background: linear-gradient(135deg, #ffb44c, #ff6f59);
      color: #1b1c1d;
      font-weight: 600;
      cursor: pointer;
      font-family: "Source Sans 3", "Segoe UI", sans-serif;
    }

    .wg-btn-secondary {
      background: linear-gradient(135deg, #4aa79f, #2f6f73);
      color: #ffffff;
    }

    .wg-submit-bar {
      display: flex;
      justify-content: center;
    }

    .wg-upload {
      border: 1px dashed rgba(27, 28, 29, 0.3);
      border-radius: 14px;
      padding: 14px;
      background: rgba(255, 244, 223, 0.7);
    }

    .wg-preview-grid {
      display: grid;
      gap: 12px;
      grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      margin-top: 12px;
    }

    .wg-preview-item {
      position: relative;
    }

    .wg-preview-grid img {
      width: 100%;
      aspect-ratio: 4 / 3;
      object-fit: cover;
      border-radius: 12px;
      border: 1px solid rgba(27, 28, 29, 0.1);
      background: #ffffff;
    }

    .wg-preview-remove {
      position: absolute;
      top: 6px;
      right: 6px;
      border: none;
      border-radius: 999px;
      width: 26px;
      height: 26px;
      display: grid;
      place-items: center;
      background: rgba(27, 28, 29, 0.8);
      color: #ffffff;
      cursor: pointer;
      font-size: 0.85rem;
    }

    .wg-service-sections {
      display: grid;
      gap: 18px;
    }

    .wg-service-section {
      border-radius: 16px;
      border: 1px solid rgba(27, 28, 29, 0.12);
      padding: 18px;
      background: rgba(255, 255, 255, 0.85);
      display: grid;
      gap: 16px;
    }

    .wg-service-header,
    .wg-package-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }

    .wg-service-title,
    .wg-package-title {
      font-weight: 600;
    }

    .wg-packages {
      display: grid;
      gap: 14px;
    }

    .wg-package-card {
      border-radius: 14px;
      border: 1px solid rgba(27, 28, 29, 0.1);
      padding: 16px;
      background: rgba(74, 167, 159, 0.08);
      display: grid;
      gap: 12px;
    }

    .wg-package-grid {
      display: grid;
      gap: 12px;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    .wg-modal {
      position: fixed;
      inset: 0;
      background: rgba(27, 28, 29, 0.55);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 24px;
      z-index: 9999;
    }

    .wg-modal.is-active {
      display: flex;
    }

    .wg-modal-content {
      background: #ffffff;
      border-radius: 18px;
      padding: 24px;
      max-width: 480px;
      text-align: center;
      box-shadow: 0 18px 40px rgba(27, 28, 29, 0.2);
      display: grid;
      gap: 12px;
    }

    .wg-modal-content h3 {
      margin: 0;
      font-family: "Merriweather", "Georgia", serif;
    }

    @media (max-width: 720px) {
      .wg-service-input {
        grid-template-columns: 1fr;
      }
    }
  </style>

  <script>
    (function () {
      const form = document.getElementById("wg-form");
      const addServiceButtons = form.querySelectorAll(".wg-add-service");
      const serviceInput = document.getElementById("wg-layanan-input");
      const sections = document.getElementById("wg-layanan-sections");
      const logoInput = document.getElementById("wg-logo");
      const logoPreview = document.getElementById("wg-logo-preview");
      const galleryInput = document.getElementById("wg-gallery-upload");
      const galleryPreview = document.getElementById("wg-gallery-preview");
      const testimoniInput = document.getElementById("wg-testimoni-upload");
      const testimoniPreview = document.getElementById("wg-testimoni-preview");
      const payloadInput = document.getElementById("wg-payload");
      const modal = document.getElementById("wg-modal");
      const modalClose = document.getElementById("wg-modal-close");
      const galleryFiles = [];
      const testimoniFiles = [];
      let serviceCount = 0;

      const createField = (labelText, id, type = "text", placeholder = "", extra = "") => `
        <div class="wg-field">
          <label for="${id}">${labelText}</label>
          ${
            type === "textarea"
              ? `<textarea id="${id}" data-field="${id}" placeholder="${placeholder}"></textarea>`
              : `<input id="${id}" data-field="${id}" type="${type}" placeholder="${placeholder}" ${extra} />`
          }
        </div>
      `;

      const addService = (value) => {
        const trimmed = value.trim();
        if (!trimmed) return;

        serviceCount += 1;
        const serviceId = `wg-service-${serviceCount}`;
        const section = document.createElement("section");
        section.className = "wg-service-section";
        section.dataset.serviceId = serviceId;
        section.dataset.serviceName = trimmed;

        const packages = document.createElement("div");
        packages.className = "wg-packages";

        let packageCount = 0;

        const addPackage = () => {
          packageCount += 1;
          const packageId = `${serviceId}-package-${packageCount}`;
          const card = document.createElement("div");
          card.className = "wg-package-card";
          card.dataset.packageId = packageId;
          card.innerHTML = `
            <div class="wg-package-header">
              <span class="wg-package-title">${trimmed} - Paket / Produk ${packageCount}</span>
              <button class="wg-btn wg-btn-secondary" type="button" data-remove="${packageId}">
                Hapus Paket
              </button>
            </div>
            <div class="wg-package-grid">
              ${createField("Nama Paket", `${packageId}-nama`, "text", "Contoh: Sewa Mobil Lepas Kunci")}
              ${createField("Harga", `${packageId}-harga`, "text", "Contoh: 350.000")}
              ${createField("Gambar Paket", `${packageId}-gambar`, "file", "", 'accept="image/*" name="package_images[]"')}
            </div>
            <div class="wg-package-grid">
              ${createField("Include", `${packageId}-include`, "textarea", "Contoh: Driver, BBM, Air Mineral")}
              ${createField("Exclude", `${packageId}-exclude`, "textarea", "Contoh: Tiket masuk, makan siang")}
            </div>
            ${createField(
              "Deskripsi Singkat",
              `${packageId}-deskripsi`,
              "textarea",
              "Tulis ringkasan paket yang menarik dan jelas."
            )}
          `;

          card.querySelector(`[data-remove="${packageId}"]`).addEventListener("click", () => {
            card.remove();
          });

          packages.appendChild(card);
        };

        section.innerHTML = `
          <div class="wg-service-header">
            <span class="wg-service-title">Layanan ${trimmed}</span>
            <button class="wg-btn wg-btn-secondary" type="button" data-remove-service="${serviceId}">
              Hapus Layanan
            </button>
          </div>
          <button class="wg-btn wg-btn-secondary" type="button" data-add-package="${serviceId}">
            Tambah Produk / Paket - ${trimmed}
          </button>
        `;

        section.appendChild(packages);
        section.insertAdjacentHTML(
          "beforeend",
          `<button class="wg-btn wg-btn-secondary" type="button" data-add-package="${serviceId}">
            Tambah Produk / Paket - ${trimmed}
          </button>`
        );

        section.querySelector(`[data-remove-service="${serviceId}"]`).addEventListener("click", () => {
          section.remove();
        });

        section.querySelectorAll(`[data-add-package="${serviceId}"]`).forEach((button) => {
          button.addEventListener("click", addPackage);
        });

        sections.appendChild(section);
        addPackage();
      };

      const renderFileList = (store, container) => {
        container.innerHTML = "";
        store.forEach((file, index) => {
          const url = URL.createObjectURL(file);
          const item = document.createElement("div");
          item.className = "wg-preview-item";
          const img = document.createElement("img");
          img.src = url;
          img.alt = file.name;
          img.onload = () => URL.revokeObjectURL(url);
          const remove = document.createElement("button");
          remove.type = "button";
          remove.className = "wg-preview-remove";
          remove.textContent = "×";
          remove.setAttribute("aria-label", `Hapus ${file.name}`);
          remove.addEventListener("click", () => {
            store.splice(index, 1);
            renderFileList(store, container);
          });
          item.appendChild(img);
          item.appendChild(remove);
          container.appendChild(item);
        });
      };

      const fileKey = (file) => `${file.name}-${file.size}-${file.lastModified}`;

      const addFiles = (files, store) => {
        Array.from(files).forEach((file) => {
          if (!store.some((item) => fileKey(item) === fileKey(file))) {
            store.push(file);
          }
        });
      };

      const renderSinglePreview = (files, container) => {
        container.innerHTML = "";
        if (!files || !files[0]) return;
        const url = URL.createObjectURL(files[0]);
        const item = document.createElement("div");
        item.className = "wg-preview-item";
        const img = document.createElement("img");
        img.src = url;
        img.alt = files[0].name;
        img.onload = () => URL.revokeObjectURL(url);
        item.appendChild(img);
        container.appendChild(item);
      };

      addServiceButtons.forEach((button) => {
        button.addEventListener("click", () => {
          addService(serviceInput.value);
          serviceInput.value = "";
          serviceInput.focus();
        });
      });

      serviceInput.addEventListener("keydown", (event) => {
        if (event.key === "Enter") {
          event.preventDefault();
          addService(serviceInput.value);
          serviceInput.value = "";
        }
      });

      logoInput.addEventListener("change", (event) => {
        renderSinglePreview(event.target.files, logoPreview);
      });

      galleryInput.addEventListener("change", (event) => {
        addFiles(event.target.files, galleryFiles);
        renderFileList(galleryFiles, galleryPreview);
        event.target.value = "";
      });

      testimoniInput.addEventListener("change", (event) => {
        addFiles(event.target.files, testimoniFiles);
        renderFileList(testimoniFiles, testimoniPreview);
        event.target.value = "";
      });

      const syncFiles = (inputEl, store) => {
        const dataTransfer = new DataTransfer();
        store.forEach((file) => dataTransfer.items.add(file));
        inputEl.files = dataTransfer.files;
      };

      form.addEventListener("submit", () => {
        syncFiles(galleryInput, galleryFiles);
        syncFiles(testimoniInput, testimoniFiles);

        const services = Array.from(document.querySelectorAll(".wg-service-section")).map((section) => {
          const serviceName = section.dataset.serviceName || "";
          const packages = Array.from(section.querySelectorAll(".wg-package-card")).map((card) => {
            const getValue = (suffix) => {
              const input = card.querySelector(`[id$="${suffix}"]`);
              return input ? input.value.trim() : "";
            };
            const fileInput = card.querySelector('input[type="file"][name="package_images[]"]');
            const hasFile = fileInput && fileInput.files && fileInput.files.length > 0;
            return {
              name: getValue("-nama"),
              price: getValue("-harga"),
              include: getValue("-include"),
              exclude: getValue("-exclude"),
              description: getValue("-deskripsi"),
              image_index: hasFile ? 0 : null,
            };
          });
          return { name: serviceName, packages };
        });

        let imageIndex = 0;
        services.forEach((service) => {
          service.packages.forEach((pkg) => {
            if (pkg.image_index !== null) {
              pkg.image_index = imageIndex;
              imageIndex += 1;
            }
          });
        });

        payloadInput.value = JSON.stringify({ services });
      });

      if (modal && modalClose) {
        modalClose.addEventListener("click", () => {
          modal.classList.remove("is-active");
          const url = new URL(window.location.href);
          url.searchParams.delete("wg_submitted");
          window.history.replaceState({}, document.title, url.toString());
        });
      }

      if (modal && document.querySelector(".wg-wrapper").dataset.wgSubmitted === "1") {
        modal.classList.add("is-active");
      }
    })();
  </script>
  <?php
  return ob_get_clean();
}
add_shortcode('website_generator_form', 'wg_form_shortcode');

function wg_handle_single_upload($field, $post_id) {
  if (empty($_FILES[$field]['name'])) {
    return '';
  }

  require_once ABSPATH . 'wp-admin/includes/file.php';
  require_once ABSPATH . 'wp-admin/includes/media.php';
  require_once ABSPATH . 'wp-admin/includes/image.php';

  $attachment_id = media_handle_upload($field, $post_id);
  if (is_wp_error($attachment_id)) {
    return '';
  }
  return wp_get_attachment_url($attachment_id);
}

function wg_handle_multi_upload($field, $post_id) {
  $urls = array();
  if (empty($_FILES[$field]['name']) || !is_array($_FILES[$field]['name'])) {
    return $urls;
  }

  require_once ABSPATH . 'wp-admin/includes/file.php';
  require_once ABSPATH . 'wp-admin/includes/media.php';
  require_once ABSPATH . 'wp-admin/includes/image.php';

  $files = $_FILES[$field];
  foreach ($files['name'] as $index => $name) {
    if (empty($name)) {
      continue;
    }

    $_FILES['wg_temp'] = array(
      'name' => $files['name'][$index],
      'type' => $files['type'][$index],
      'tmp_name' => $files['tmp_name'][$index],
      'error' => $files['error'][$index],
      'size' => $files['size'][$index],
    );

    $attachment_id = media_handle_upload('wg_temp', $post_id);
    if (!is_wp_error($attachment_id)) {
      $urls[] = wp_get_attachment_url($attachment_id);
    }
  }

  unset($_FILES['wg_temp']);
  return $urls;
}

function wg_attach_package_images($payload, $package_urls) {
  if (empty($payload['services']) || !is_array($payload['services'])) {
    return $payload;
  }

  foreach ($payload['services'] as $service_index => $service) {
    if (empty($service['packages']) || !is_array($service['packages'])) {
      continue;
    }

    foreach ($service['packages'] as $package_index => $package) {
      if (!isset($package['image_index'])) {
        continue;
      }
      $image_index = $package['image_index'];
      if ($image_index !== null && isset($package_urls[$image_index])) {
        $payload['services'][$service_index]['packages'][$package_index]['image_url'] = $package_urls[$image_index];
      }
    }
  }

  return $payload;
}

function wg_handle_submit() {
  if (!isset($_POST['wg_nonce']) || !wp_verify_nonce($_POST['wg_nonce'], 'wg_submit_form')) {
    wp_die('Invalid submission.');
  }

  $payload_raw = isset($_POST['wg_payload']) ? wp_unslash($_POST['wg_payload']) : '';
  $payload = json_decode($payload_raw, true);
  if (!is_array($payload)) {
    $payload = array();
  }

  $post_title = isset($_POST['nama_usaha']) ? sanitize_text_field($_POST['nama_usaha']) : 'Website Request';
  $post_id = wp_insert_post(array(
    'post_type' => 'wg_submission',
    'post_status' => 'publish',
    'post_title' => $post_title,
  ));

  if (is_wp_error($post_id)) {
    wp_die('Failed to save submission.');
  }

  $logo_url = wg_handle_single_upload('logo', $post_id);
  $gallery_urls = wg_handle_multi_upload('gallery_upload', $post_id);
  $testimoni_urls = wg_handle_multi_upload('testimoni_upload', $post_id);
  $package_urls = wg_handle_multi_upload('package_images', $post_id);

  $payload['business'] = array(
    'nama_usaha' => isset($_POST['nama_usaha']) ? sanitize_text_field($_POST['nama_usaha']) : '',
    'bidang_usaha' => isset($_POST['bidang_usaha']) ? sanitize_text_field($_POST['bidang_usaha']) : '',
    'nama_website' => isset($_POST['nama_website']) ? sanitize_text_field($_POST['nama_website']) : '',
    'alamat_usaha' => isset($_POST['alamat_usaha']) ? sanitize_textarea_field($_POST['alamat_usaha']) : '',
    'no_hp' => isset($_POST['no_hp']) ? sanitize_text_field($_POST['no_hp']) : '',
    'email' => isset($_POST['email']) ? sanitize_email($_POST['email']) : '',
    'logo_url' => $logo_url,
  );

  $payload['gallery'] = $gallery_urls;
  $payload['testimoni'] = array(
    'text' => isset($_POST['testimoni_text']) ? sanitize_textarea_field($_POST['testimoni_text']) : '',
    'screenshots' => $testimoni_urls,
  );

  $payload = wg_attach_package_images($payload, $package_urls);

  update_post_meta($post_id, 'wg_payload', wp_json_encode($payload));
  update_post_meta($post_id, 'wg_logo_url', $logo_url);
  update_post_meta($post_id, 'wg_gallery_urls', $gallery_urls);
  update_post_meta($post_id, 'wg_testimoni_urls', $testimoni_urls);

  $redirect = wp_get_referer();
  if (!$redirect) {
    $redirect = home_url('/');
  }
  $redirect = add_query_arg('wg_submitted', '1', $redirect);
  wp_safe_redirect($redirect);
  exit;
}
add_action('admin_post_nopriv_wg_submit', 'wg_handle_submit');
add_action('admin_post_wg_submit', 'wg_handle_submit');
