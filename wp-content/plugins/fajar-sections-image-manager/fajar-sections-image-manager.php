<?php
/**
 * Plugin Name: Fajar Sections Image Manager
 * Description: CRUD gambar untuk section "Dokumentasi Kerja Kami" dan "Gallery" (upload media atau URL) + shortcode render.
 * Version: 1.0.0
 * Author: Codex
 */

if (!defined('ABSPATH')) {
    exit;
}

class Fajar_Sections_Image_Manager {
    const OPTION_FEATURED = 'fajar_featured_images';
    const OPTION_GALLERY = 'fajar_gallery_images';

    private static $assets_printed = false;

    public function __construct() {
        add_action('admin_menu', array($this, 'register_admin_menu'));
        add_action('admin_init', array($this, 'handle_form_submit'));
        add_shortcode('fajar_dokumentasi_kerja_kami', array($this, 'shortcode_featured'));
        add_shortcode('fajar_gallery', array($this, 'shortcode_gallery'));
    }

    public function register_admin_menu() {
        add_menu_page(
            'Fajar Sections',
            'Fajar Sections',
            'manage_options',
            'fajar-sections-image-manager',
            array($this, 'render_admin_page'),
            'dashicons-format-gallery',
            58
        );
    }

    public function handle_form_submit() {
        if (!isset($_POST['fajar_sections_nonce'])) {
            return;
        }
        if (!current_user_can('manage_options')) {
            return;
        }
        if (!wp_verify_nonce($_POST['fajar_sections_nonce'], 'fajar_sections_save')) {
            return;
        }

        $tab = isset($_POST['tab']) ? sanitize_key($_POST['tab']) : 'featured';
        $option = ($tab === 'gallery') ? self::OPTION_GALLERY : self::OPTION_FEATURED;

        $ids = isset($_POST['image_id']) && is_array($_POST['image_id']) ? $_POST['image_id'] : array();
        $urls = isset($_POST['image_url']) && is_array($_POST['image_url']) ? $_POST['image_url'] : array();
        $alts = isset($_POST['image_alt']) && is_array($_POST['image_alt']) ? $_POST['image_alt'] : array();

        $rows = array();
        $count = max(count($ids), count($urls), count($alts));

        for ($i = 0; $i < $count; $i++) {
            $id = isset($ids[$i]) ? absint($ids[$i]) : 0;
            $url = isset($urls[$i]) ? esc_url_raw(trim((string) $urls[$i])) : '';
            $alt = isset($alts[$i]) ? sanitize_text_field($alts[$i]) : '';

            if ($url === '') {
                continue;
            }

            $rows[] = array(
                'id'  => $id,
                'url' => $url,
                'alt' => $alt,
            );
        }

        update_option($option, $rows);

        $redirect = add_query_arg(
            array(
                'page'    => 'fajar-sections-image-manager',
                'tab'     => $tab,
                'updated' => '1',
            ),
            admin_url('admin.php')
        );

        wp_safe_redirect($redirect);
        exit;
    }

    public function render_admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        wp_enqueue_media();

        $tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'featured';
        if (!in_array($tab, array('featured', 'gallery'), true)) {
            $tab = 'featured';
        }

        $is_gallery = ($tab === 'gallery');
        $title = $is_gallery ? 'Gallery' : 'Dokumentasi Kerja Kami';
        $rows = $is_gallery ? $this->get_gallery_images() : $this->get_featured_images();
        ?>
        <div class="wrap">
          <h1>Fajar Sections Image Manager</h1>
          <?php if (isset($_GET['updated']) && $_GET['updated'] === '1') : ?>
            <div class="notice notice-success is-dismissible"><p>Data gambar berhasil disimpan.</p></div>
          <?php endif; ?>

          <h2 class="nav-tab-wrapper" style="margin-bottom:16px;">
            <a href="<?php echo esc_url(add_query_arg(array('page' => 'fajar-sections-image-manager', 'tab' => 'featured'), admin_url('admin.php'))); ?>" class="nav-tab <?php echo $tab === 'featured' ? 'nav-tab-active' : ''; ?>">Dokumentasi Kerja Kami</a>
            <a href="<?php echo esc_url(add_query_arg(array('page' => 'fajar-sections-image-manager', 'tab' => 'gallery'), admin_url('admin.php'))); ?>" class="nav-tab <?php echo $tab === 'gallery' ? 'nav-tab-active' : ''; ?>">Gallery</a>
          </h2>

          <form method="post" action="">
            <?php wp_nonce_field('fajar_sections_save', 'fajar_sections_nonce'); ?>
            <input type="hidden" name="tab" value="<?php echo esc_attr($tab); ?>">

            <p>Kelola gambar untuk section: <strong><?php echo esc_html($title); ?></strong>.</p>

            <table class="widefat striped" id="fajar-image-table">
              <thead>
                <tr>
                  <th style="width:90px;">Preview</th>
                  <th>Image URL</th>
                  <th>Alt Text</th>
                  <th style="width:160px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $row) : ?>
                <tr>
                  <td>
                    <img src="<?php echo esc_url($row['url']); ?>" alt="" style="width:72px;height:54px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">
                  </td>
                  <td>
                    <input type="hidden" name="image_id[]" value="<?php echo isset($row['id']) ? absint($row['id']) : 0; ?>">
                    <input type="url" name="image_url[]" value="<?php echo esc_attr($row['url']); ?>" class="regular-text" style="width:100%;">
                  </td>
                  <td>
                    <input type="text" name="image_alt[]" value="<?php echo esc_attr($row['alt']); ?>" class="regular-text" style="width:100%;">
                  </td>
                  <td>
                    <button type="button" class="button fajar-upload-btn">Upload</button>
                    <button type="button" class="button-link-delete fajar-remove-row" style="margin-left:8px;">Hapus</button>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <p style="margin-top:12px;">
              <button type="button" class="button" id="fajar-add-row">+ Tambah gambar (URL / Upload)</button>
            </p>

            <p>
              <button type="submit" class="button button-primary">Simpan Perubahan</button>
            </p>
          </form>
        </div>

        <template id="fajar-row-template">
          <tr>
            <td>
              <img src="" alt="" style="width:72px;height:54px;object-fit:cover;border-radius:6px;border:1px solid #ddd;display:none;">
            </td>
            <td>
              <input type="hidden" name="image_id[]" value="0">
              <input type="url" name="image_url[]" value="" class="regular-text" style="width:100%;" placeholder="https://...">
            </td>
            <td>
              <input type="text" name="image_alt[]" value="" class="regular-text" style="width:100%;" placeholder="Alt text">
            </td>
            <td>
              <button type="button" class="button fajar-upload-btn">Upload</button>
              <button type="button" class="button-link-delete fajar-remove-row" style="margin-left:8px;">Hapus</button>
            </td>
          </tr>
        </template>

        <script>
          (function(){
            const tableBody = document.querySelector('#fajar-image-table tbody');
            const addBtn = document.getElementById('fajar-add-row');
            const template = document.getElementById('fajar-row-template');

            if (!tableBody || !addBtn || !template) return;

            function bindRow(row) {
              const removeBtn = row.querySelector('.fajar-remove-row');
              const uploadBtn = row.querySelector('.fajar-upload-btn');
              const urlInput = row.querySelector('input[name="image_url[]"]');
              const idInput = row.querySelector('input[name="image_id[]"]');
              const altInput = row.querySelector('input[name="image_alt[]"]');
              const preview = row.querySelector('img');

              if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                  row.remove();
                });
              }

              if (urlInput) {
                urlInput.addEventListener('input', function() {
                  const val = this.value.trim();
                  if (preview && val) {
                    preview.src = val;
                    preview.style.display = '';
                  } else if (preview) {
                    preview.style.display = 'none';
                    preview.removeAttribute('src');
                  }
                });
              }

              if (uploadBtn) {
                uploadBtn.addEventListener('click', function() {
                  const frame = wp.media({
                    title: 'Pilih gambar',
                    button: { text: 'Pakai gambar ini' },
                    multiple: false
                  });

                  frame.on('select', function() {
                    const attachment = frame.state().get('selection').first().toJSON();
                    if (!attachment || !attachment.url) return;

                    if (urlInput) urlInput.value = attachment.url;
                    if (idInput) idInput.value = attachment.id || 0;
                    if (altInput && !altInput.value && attachment.alt) altInput.value = attachment.alt;
                    if (preview) {
                      preview.src = attachment.url;
                      preview.style.display = '';
                    }
                  });

                  frame.open();
                });
              }
            }

            addBtn.addEventListener('click', function() {
              const node = template.content.firstElementChild.cloneNode(true);
              tableBody.appendChild(node);
              bindRow(node);
            });

            Array.from(tableBody.querySelectorAll('tr')).forEach(bindRow);
          })();
        </script>
        <?php
    }

    private function get_featured_images() {
        $saved = get_option(self::OPTION_FEATURED, array());
        if (!empty($saved) && is_array($saved)) {
            return $saved;
        }

        return array(
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/WhatsApp-Image-2023-04-14-at-14.25.14.jpeg', 'alt' => 'Dokumentasi 1'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/WhatsApp-Image-2023-04-14-at-14.24.45.jpeg', 'alt' => 'Dokumentasi 2'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/WhatsApp-Image-2023-04-14-at-14.25.37.jpeg', 'alt' => 'Dokumentasi 3'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/WhatsApp-Image-2023-04-14-at-14.23.50.jpeg', 'alt' => 'Dokumentasi 4'),
        );
    }

    private function get_gallery_images() {
        $saved = get_option(self::OPTION_GALLERY, array());
        if (!empty($saved) && is_array($saved)) {
            return $saved;
        }

        return array(
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/84053e25be8f797e75e08f8beddc4617.jpg', 'alt' => 'Gallery 1'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/516a4fc507cabe755a2810956e3aa004.jpg', 'alt' => 'Gallery 2'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/793ab3cb7188e3de2747c3bc92119873.jpg', 'alt' => 'Gallery 3'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/840f12e52a5afe01ada6c19f39c5c0e3.jpg', 'alt' => 'Gallery 4'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/6c3dbe5a4d0249c991305bbc85f1af73.jpg', 'alt' => 'Gallery 5'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/cf3351cf26782ab495a709659fdabb3e.jpg', 'alt' => 'Gallery 6'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/fb63edb10bd9b7c3ba0a5f5795db034e.jpg', 'alt' => 'Gallery 7'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/46f265d09896089f47afba209e2b0e9e.jpg', 'alt' => 'Gallery 8'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/7325caac86047b0bded34f33618c5349.jpg', 'alt' => 'Gallery 9'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/57f3d521ffcb7a3e0da6fb74c48c35f3.jpg', 'alt' => 'Gallery 10'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/WhatsApp-Image-2023-04-14-at-14.28.42.jpeg', 'alt' => 'Gallery 11'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/WhatsApp-Image-2023-04-14-at-14.28.36.jpeg', 'alt' => 'Gallery 12'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/WhatsApp-Image-2023-04-14-at-14.28.37.jpeg', 'alt' => 'Gallery 13'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/WhatsApp-Image-2023-04-14-at-14.28.43.jpeg', 'alt' => 'Gallery 14'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/WhatsApp-Image-2023-04-07-at-12.09.14.jpeg', 'alt' => 'Gallery 15'),
            array('id' => 0, 'url' => 'https://everz-digital.site/wp-content/uploads/2026/02/WhatsApp-Image-2023-04-14-at-14.28.38.jpeg', 'alt' => 'Gallery 16'),
        );
    }

    private function render_front_assets_once() {
        if (self::$assets_printed) {
            return;
        }
        self::$assets_printed = true;
        ?>
        <style>
          .container{width:min(92vw,1150px);margin:0 auto}
          .featured-work{padding:60px 0 40px;background:#fff}
          .featured-title{text-align:center;margin-bottom:26px}
          .featured-heading{margin:0;font-size:clamp(28px,5vw,40px);letter-spacing:.02em}
          .featured-slider{overflow-x:auto;position:relative;padding:0 12%;scroll-snap-type:x mandatory;scroll-padding:0 12%;-webkit-overflow-scrolling:touch}
          .featured-track{display:flex;gap:18px;align-items:stretch;padding:0}
          .featured-slider::-webkit-scrollbar{display:none}.featured-slider{scrollbar-width:none}
          .featured-nav{position:absolute;top:50%;transform:translateY(-50%);width:40px;height:40px;border-radius:50%;border:1px solid rgba(15,23,42,.2);background:#fff;box-shadow:0 8px 18px rgba(15,23,42,.12);display:grid;place-items:center;color:#0f172a;cursor:pointer;z-index:2}
          .featured-nav.prev{left:8px}.featured-nav.next{right:8px}
          .featured-item{border-radius:16px;overflow:hidden;position:relative;min-height:210px;aspect-ratio:3 / 4;flex:0 0 70%;background:center/cover no-repeat;box-shadow:0 12px 28px rgba(15,23,42,.12);scroll-snap-align:center}
          .featured-item.is-active{transform:translateY(-6px) scale(1.04);z-index:1;box-shadow:0 18px 40px rgba(15,23,42,.22)}

          .gallery-section{padding:60px 0 40px;background:#fff}
          .gallery-title{text-align:center;margin-bottom:18px}
          .gallery-heading{margin:0;font-size:clamp(30px,6vw,44px);letter-spacing:.02em}
          .gallery-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
          .gallery-card{border-radius:0;overflow:hidden;background:#fff;border:1px solid #e3eaf5;box-shadow:0 12px 28px rgba(15,23,42,.12)}
          .gallery-img{aspect-ratio:3 / 4;height:auto;background:center/cover no-repeat}
          .btn.btn-outline{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 18px;border-radius:999px;font-size:13px;font-weight:600;border:1px solid #2f6ce0;background:transparent;color:#2f6ce0;box-shadow:none;cursor:pointer}

          @media (min-width:1024px){
            .featured-slider{padding:0;scroll-padding:0}
            .featured-track{gap:16px}
            .featured-item{flex:0 0 calc((100% - 48px) / 4);min-height:170px;aspect-ratio:4 / 3}
            .gallery-grid{grid-template-columns:repeat(4,1fr);gap:14px}
          }
          @media (max-width:980px){.gallery-grid{grid-template-columns:repeat(2,1fr)}}
          @media (max-width:640px){
            .featured-item{flex:0 0 78%}
            .featured-slider{padding:0 10%;scroll-padding:0 10%}
            .gallery-grid{grid-template-columns:1fr}
          }
        </style>
        <script>
          (function(){
            function initFeatured(section){
              const featuredSlider = section.querySelector('.featured-slider');
              const featuredTrack = section.querySelector('.featured-track');
              const featuredPrev = section.querySelector('.featured-nav.prev');
              const featuredNext = section.querySelector('.featured-nav.next');
              if (!featuredSlider || !featuredTrack) return;

              const originalSlides = Array.from(featuredTrack.children);
              let slides = [];
              let slideIndex = 0;
              let scrollTimer = null;
              let setWidth = 0;

              function rebuildLoop() {
                featuredTrack.innerHTML = '';
                originalSlides.forEach((slide) => featuredTrack.appendChild(slide.cloneNode(true)));
                originalSlides.forEach((slide) => featuredTrack.appendChild(slide.cloneNode(true)));
                originalSlides.forEach((slide) => featuredTrack.appendChild(slide.cloneNode(true)));
                slides = Array.from(featuredTrack.children);

                const firstSetStart = slides[originalSlides.length].offsetLeft;
                const lastInSet = slides[originalSlides.length * 2 - 1];
                setWidth = lastInSet.offsetLeft + lastInSet.offsetWidth - firstSetStart;
                featuredSlider.scrollLeft = firstSetStart;
                slideIndex = originalSlides.length;
                setActive();
              }

              function setActive() {
                slides.forEach((slide, index) => {
                  slide.classList.toggle('is-active', index === slideIndex);
                });
              }

              function goTo(index) {
                if (!slides.length) return;
                slideIndex = index;
                const slide = slides[slideIndex];
                const target = slide.offsetLeft - (featuredSlider.clientWidth - slide.offsetWidth) / 2;
                featuredSlider.scrollTo({ left: target, behavior: 'smooth' });
                setActive();
              }

              function normalizeLoop() {
                const firstSetStart = slides[originalSlides.length].offsetLeft;
                if (featuredSlider.scrollLeft <= firstSetStart - setWidth * 0.5) {
                  featuredSlider.scrollLeft += setWidth;
                } else if (featuredSlider.scrollLeft >= firstSetStart + setWidth * 1.5) {
                  featuredSlider.scrollLeft -= setWidth;
                }
              }

              function updateIndexFromScroll() {
                normalizeLoop();
                const center = featuredSlider.scrollLeft + featuredSlider.clientWidth / 2;
                let closestIndex = 0;
                let closestDistance = Infinity;
                slides.forEach((slide, index) => {
                  const slideCenter = slide.offsetLeft + slide.offsetWidth / 2;
                  const distance = Math.abs(center - slideCenter);
                  if (distance < closestDistance) {
                    closestDistance = distance;
                    closestIndex = index;
                  }
                });
                slideIndex = closestIndex;
                setActive();
              }

              featuredSlider.addEventListener('scroll', () => {
                clearTimeout(scrollTimer);
                scrollTimer = setTimeout(updateIndexFromScroll, 80);
              });

              window.addEventListener('resize', rebuildLoop);
              rebuildLoop();

              let autoTimer = null;
              const sliderObserver = new IntersectionObserver(
                (entries) => {
                  entries.forEach((entry) => {
                    if (entry.isIntersecting && !autoTimer) {
                      autoTimer = setInterval(() => goTo(slideIndex + 1), 4200);
                    } else if (!entry.isIntersecting && autoTimer) {
                      clearInterval(autoTimer);
                      autoTimer = null;
                    }
                  });
                },
                { threshold: 0.2 }
              );
              sliderObserver.observe(featuredSlider);

              if (featuredPrev) {
                featuredPrev.addEventListener('click', () => {
                  if (autoTimer) {
                    clearInterval(autoTimer);
                    autoTimer = null;
                  }
                  goTo(slideIndex - 1);
                });
              }
              if (featuredNext) {
                featuredNext.addEventListener('click', () => {
                  if (autoTimer) {
                    clearInterval(autoTimer);
                    autoTimer = null;
                  }
                  goTo(slideIndex + 1);
                });
              }
            }

            function initGallery(section){
              const galleryGrid = section.querySelector('.gallery-grid');
              const galleryMore = section.querySelector('.fajar-gallery-more');
              if (!galleryGrid) return;

              const galleryCards = Array.from(galleryGrid.querySelectorAll('.gallery-card'));
              const initialLimit = 8;
              let visibleLimit = initialLimit;

              function applyLimit() {
                galleryCards.forEach((card, index) => {
                  card.style.display = index < visibleLimit ? 'block' : 'none';
                });
                if (galleryMore) {
                  galleryMore.style.display = visibleLimit < galleryCards.length ? 'inline-flex' : 'none';
                }
              }

              applyLimit();

              if (galleryMore) {
                galleryMore.addEventListener('click', () => {
                  visibleLimit += 8;
                  applyLimit();
                });
              }
            }

            document.addEventListener('DOMContentLoaded', function(){
              document.querySelectorAll('[data-fajar-featured-section]').forEach(initFeatured);
              document.querySelectorAll('[data-fajar-gallery-section]').forEach(initGallery);
            });
          })();
        </script>
        <?php
    }

    public function shortcode_featured() {
        $images = $this->get_featured_images();
        if (empty($images)) {
            return '';
        }

        ob_start();
        $this->render_front_assets_once();
        ?>
        <section class="featured-work" data-fajar-featured-section>
          <div class="container featured-title">
            <h2 class="featured-heading">Dokumentasi Kerja Kami</h2>
          </div>
          <div class="container featured-slider">
            <button class="featured-nav prev" type="button" aria-label="Sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="featured-nav next" type="button" aria-label="Berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
            <div class="featured-track">
              <?php foreach ($images as $img) : ?>
                <div class="featured-item" style="background-image:url('<?php echo esc_url($img['url']); ?>');" aria-label="<?php echo esc_attr($img['alt']); ?>"></div>
              <?php endforeach; ?>
            </div>
          </div>
        </section>
        <?php
        return ob_get_clean();
    }

    public function shortcode_gallery() {
        $images = $this->get_gallery_images();
        if (empty($images)) {
            return '';
        }

        ob_start();
        $this->render_front_assets_once();
        ?>
        <section class="gallery-section" id="gallery" data-fajar-gallery-section>
          <div class="container gallery-title">
            <h2 class="gallery-heading">Gallery</h2>
          </div>
          <div class="container gallery-grid">
            <?php foreach ($images as $img) : ?>
              <div class="gallery-card">
                <div class="gallery-img" style="background-image:url('<?php echo esc_url($img['url']); ?>');" aria-label="<?php echo esc_attr($img['alt']); ?>"></div>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="container" style="text-align:center; margin-top:20px;">
            <button class="btn btn-outline fajar-gallery-more" type="button">Lainnya</button>
          </div>
        </section>
        <?php
        return ob_get_clean();
    }
}

new Fajar_Sections_Image_Manager();
