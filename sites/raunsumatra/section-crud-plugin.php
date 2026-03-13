<?php

declare(strict_types=1);

$baseDir = __DIR__;
$backupDir = $baseDir . '/.section-backups';

$targets = [
    'index' => [
        'label' => 'index.html',
        'path' => $baseDir . '/index.html',
    ],
    'wisata' => [
        'label' => 'wisata-sumbar.html',
        'path' => $baseDir . '/wisata-sumbar.html',
    ],
];

$currentKey = isset($_GET['file']) && isset($targets[$_GET['file']]) ? $_GET['file'] : 'index';
$message = null;
$error = null;

function readFileContent(string $path): string
{
    $content = @file_get_contents($path);
    if ($content === false) {
        throw new RuntimeException('Gagal membaca file: ' . $path);
    }

    return $content;
}

function writeFileContent(string $path, string $content): void
{
    $result = @file_put_contents($path, $content);
    if ($result === false) {
        throw new RuntimeException('Gagal menulis file: ' . $path);
    }
}

function sectionLabelFromBlock(string $tag, string $attrs, int $index): string
{
    $id = '';
    $aria = '';

    if (preg_match('/\bid\s*=\s*["\']([^"\']+)["\']/i', $attrs, $m)) {
        $id = trim($m[1]);
    }
    if (preg_match('/\baria-label\s*=\s*["\']([^"\']+)["\']/i', $attrs, $m)) {
        $aria = trim($m[1]);
    }

    if ($id !== '') {
        return strtoupper($tag) . ' #' . $id;
    }
    if ($aria !== '') {
        return strtoupper($tag) . ' ' . $aria;
    }

    return strtoupper($tag) . ' block ' . $index;
}

function detectSections(string $html): array
{
    $sections = [];

    if (preg_match_all(
        '/<!--\s*Start\s+(.+?)\s*-->(.*?)<!--\s*End\s+(.+?)\s*-->/is',
        $html,
        $matches,
        PREG_OFFSET_CAPTURE
    )) {
        foreach ($matches[0] as $i => $fullMatch) {
            $block = $fullMatch[0];
            $start = (int) $fullMatch[1];
            $end = $start + strlen($block);
            $label = trim((string) $matches[1][$i][0]);

            $sections[] = [
                'key' => 'comment-' . $i,
                'label' => $label === '' ? 'Section ' . ($i + 1) : $label,
                'start' => $start,
                'end' => $end,
                'html' => $block,
                'kind' => 'comment',
            ];
        }

        usort($sections, static fn($a, $b) => $a['start'] <=> $b['start']);
        return $sections;
    }

    if (preg_match_all('/<(header|section|footer)\b([^>]*)>.*?<\/\1>/is', $html, $matches, PREG_OFFSET_CAPTURE)) {
        foreach ($matches[0] as $i => $fullMatch) {
            $block = $fullMatch[0];
            $start = (int) $fullMatch[1];
            $end = $start + strlen($block);
            $tag = strtolower((string) $matches[1][$i][0]);
            $attrs = (string) $matches[2][$i][0];

            $sections[] = [
                'key' => 'tag-' . $i,
                'label' => sectionLabelFromBlock($tag, $attrs, $i + 1),
                'start' => $start,
                'end' => $end,
                'html' => $block,
                'kind' => 'tag',
            ];
        }
    }

    usort($sections, static fn($a, $b) => $a['start'] <=> $b['start']);
    return $sections;
}

function backupFile(string $backupDir, string $filename, string $content): string
{
    if (!is_dir($backupDir) && !mkdir($backupDir, 0775, true) && !is_dir($backupDir)) {
        throw new RuntimeException('Gagal membuat folder backup: ' . $backupDir);
    }

    $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '-', $filename);
    $timestamp = date('Ymd-His');
    $backupPath = rtrim($backupDir, '/') . '/' . $safeName . '.' . $timestamp . '.bak';

    writeFileContent($backupPath, $content);
    return $backupPath;
}

function replaceRange(string $html, int $start, int $end, string $replacement): string
{
    return substr($html, 0, $start) . $replacement . substr($html, $end);
}

function redirectTo(string $key, array $params = []): void
{
    $params = array_merge(['file' => $key], $params);
    header('Location: ?' . http_build_query($params));
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postedKey = isset($_POST['file']) && isset($targets[$_POST['file']]) ? $_POST['file'] : $currentKey;
        $currentKey = $postedKey;
        $path = $targets[$currentKey]['path'];
        $filename = $targets[$currentKey]['label'];
        $html = readFileContent($path);
        $sections = detectSections($html);

        $action = isset($_POST['action']) ? trim((string) $_POST['action']) : '';

        if ($action === 'update') {
            $sectionKey = isset($_POST['section_key']) ? (string) $_POST['section_key'] : '';
            $newHtml = isset($_POST['section_html']) ? (string) $_POST['section_html'] : '';

            $targetSection = null;
            foreach ($sections as $section) {
                if ($section['key'] === $sectionKey) {
                    $targetSection = $section;
                    break;
                }
            }

            if ($targetSection === null) {
                throw new RuntimeException('Section tidak ditemukan. Refresh halaman lalu coba lagi.');
            }

            $backupPath = backupFile($backupDir, $filename, $html);
            $updated = replaceRange($html, $targetSection['start'], $targetSection['end'], $newHtml);
            writeFileContent($path, $updated);

            $message = 'Section berhasil diupdate. Backup: ' . basename($backupPath);
        } elseif ($action === 'delete') {
            $sectionKey = isset($_POST['section_key']) ? (string) $_POST['section_key'] : '';

            $targetSection = null;
            foreach ($sections as $section) {
                if ($section['key'] === $sectionKey) {
                    $targetSection = $section;
                    break;
                }
            }

            if ($targetSection === null) {
                throw new RuntimeException('Section tidak ditemukan. Refresh halaman lalu coba lagi.');
            }

            $backupPath = backupFile($backupDir, $filename, $html);
            $updated = replaceRange($html, $targetSection['start'], $targetSection['end'], '');
            writeFileContent($path, $updated);

            $message = 'Section berhasil dihapus. Backup: ' . basename($backupPath);
        } elseif ($action === 'create') {
            $insertBeforeKey = isset($_POST['insert_before']) ? (string) $_POST['insert_before'] : '';
            $newLabel = trim((string) ($_POST['new_label'] ?? 'New Section'));
            $newContent = (string) ($_POST['new_content'] ?? '');
            $wrapComments = isset($_POST['wrap_comments']) && $_POST['wrap_comments'] === '1';

            if (trim($newContent) === '') {
                throw new RuntimeException('Konten section baru tidak boleh kosong.');
            }

            $newBlock = $newContent;
            if ($wrapComments) {
                $safeLabel = $newLabel !== '' ? $newLabel : 'New Section';
                $newBlock = "<!-- Start {$safeLabel} -->\n" . $newContent . "\n<!-- End {$safeLabel} -->";
            }

            $insertPos = null;
            if ($insertBeforeKey !== '') {
                foreach ($sections as $section) {
                    if ($section['key'] === $insertBeforeKey) {
                        $insertPos = (int) $section['start'];
                        break;
                    }
                }
            }

            if ($insertPos === null) {
                $bodyPos = strripos($html, '</body>');
                $insertPos = $bodyPos === false ? strlen($html) : $bodyPos;
            }

            $backupPath = backupFile($backupDir, $filename, $html);
            $updated = substr($html, 0, $insertPos) . "\n" . $newBlock . "\n" . substr($html, $insertPos);
            writeFileContent($path, $updated);

            $message = 'Section baru berhasil ditambahkan. Backup: ' . basename($backupPath);
        }

        redirectTo($currentKey, ['saved' => '1']);
    }
} catch (Throwable $e) {
    $error = $e->getMessage();
}

try {
    $path = $targets[$currentKey]['path'];
    $filename = $targets[$currentKey]['label'];
    $html = readFileContent($path);
    $sections = detectSections($html);
} catch (Throwable $e) {
    $sections = [];
    $filename = $targets[$currentKey]['label'];
    $error = $error ?: $e->getMessage();
    $html = '';
}

$activeKey = isset($_GET['section']) ? (string) $_GET['section'] : ($sections[0]['key'] ?? '');
$activeSection = null;
foreach ($sections as $section) {
    if ($section['key'] === $activeKey) {
        $activeSection = $section;
        break;
    }
}
if ($activeSection === null && !empty($sections)) {
    $activeSection = $sections[0];
    $activeKey = $activeSection['key'];
}

$flash = null;
if (isset($_GET['saved']) && $_GET['saved'] === '1') {
    $flash = 'Perubahan disimpan.';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Raun Sumatra Section CRUD Plugin</title>
  <style>
    :root{
      --bg:#f6f7fb;
      --card:#ffffff;
      --ink:#111827;
      --muted:#4b5563;
      --line:#dbe0eb;
      --brand:#1f6d4d;
      --danger:#b42318;
      --radius:14px;
    }
    *{box-sizing:border-box}
    body{margin:0;background:var(--bg);font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,sans-serif;color:var(--ink)}
    .wrap{max-width:1320px;margin:24px auto;padding:0 16px}
    .topbar{display:flex;gap:12px;align-items:center;justify-content:space-between;margin-bottom:14px}
    .title{font-size:20px;font-weight:800;margin:0}
    .small{font-size:12px;color:var(--muted)}
    .grid{display:grid;grid-template-columns:310px 1fr;gap:14px}
    .card{background:var(--card);border:1px solid var(--line);border-radius:var(--radius);box-shadow:0 10px 28px rgba(17,24,39,.06)}
    .side{padding:12px}
    .main{padding:12px}
    .file-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:10px}
    .tab{display:inline-flex;padding:8px 12px;border:1px solid var(--line);border-radius:999px;text-decoration:none;color:var(--ink);font-size:13px;font-weight:700;background:#fff}
    .tab.active{background:#ecfdf3;border-color:#cce8db;color:#0d5b3f}
    .list{display:grid;gap:8px;max-height:72vh;overflow:auto;padding-right:4px}
    .item{display:block;padding:10px;border:1px solid var(--line);border-radius:10px;background:#fff;text-decoration:none;color:var(--ink)}
    .item.active{border-color:#a5d8c2;background:#ecfdf3}
    .item strong{display:block;font-size:13px;margin-bottom:2px}
    .item span{font-size:12px;color:var(--muted)}
    .status{margin:10px 0;padding:10px;border-radius:10px;font-size:13px}
    .status.ok{background:#ecfdf3;border:1px solid #b8e6cf;color:#0d5b3f}
    .status.err{background:#fef3f2;border:1px solid #f7c7c3;color:#8d1d17}
    .section-title{margin:0 0 8px;font-size:16px;font-weight:800}
    textarea,input,select{width:100%;font:inherit;border:1px solid var(--line);border-radius:10px;padding:10px;background:#fff;color:var(--ink)}
    textarea{min-height:360px;resize:vertical}
    label{display:block;font-size:12px;font-weight:700;margin:12px 0 6px;color:var(--muted);text-transform:uppercase;letter-spacing:.04em}
    .actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px}
    button{border:1px solid transparent;border-radius:10px;padding:10px 14px;cursor:pointer;font-weight:700}
    .btn-primary{background:var(--brand);color:#fff}
    .btn-danger{background:#fff;color:var(--danger);border-color:#f1b4ae}
    .split{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:12px}
    .muted{font-size:12px;color:var(--muted)}
    .source{font-size:12px;background:#f9fafb;border:1px dashed var(--line);padding:8px;border-radius:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    @media(max-width:980px){.grid{grid-template-columns:1fr}.list{max-height:220px}.split{grid-template-columns:1fr}}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="topbar">
      <div>
        <h1 class="title">Raun Sumatra Section CRUD Plugin</h1>
        <div class="small">Edit section `index.html` dan `wisata-sumbar.html` tanpa mengubah tampilan frontend.</div>
      </div>
      <div class="small">Target aktif: <strong><?php echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?></strong></div>
    </div>

    <?php if ($flash): ?>
      <div class="status ok"><?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <?php if ($message): ?>
      <div class="status ok"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="status err"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <div class="grid">
      <aside class="card side">
        <div class="file-tabs">
          <?php foreach ($targets as $key => $target): ?>
            <a class="tab <?php echo $key === $currentKey ? 'active' : ''; ?>" href="?file=<?php echo urlencode($key); ?>">
              <?php echo htmlspecialchars($target['label'], ENT_QUOTES, 'UTF-8'); ?>
            </a>
          <?php endforeach; ?>
        </div>

        <div class="small" style="margin-bottom:8px;">Sections terdeteksi: <strong><?php echo count($sections); ?></strong></div>
        <div class="list">
          <?php foreach ($sections as $section): ?>
            <a class="item <?php echo $section['key'] === $activeKey ? 'active' : ''; ?>" href="?file=<?php echo urlencode($currentKey); ?>&section=<?php echo urlencode($section['key']); ?>">
              <strong><?php echo htmlspecialchars($section['label'], ENT_QUOTES, 'UTF-8'); ?></strong>
              <span><?php echo htmlspecialchars($section['kind'] . ' | chars ' . strlen($section['html']), ENT_QUOTES, 'UTF-8'); ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </aside>

      <section class="card main">
        <?php if ($activeSection): ?>
          <h2 class="section-title">Edit Section: <?php echo htmlspecialchars($activeSection['label'], ENT_QUOTES, 'UTF-8'); ?></h2>
          <div class="source">File: <?php echo htmlspecialchars($targets[$currentKey]['path'], ENT_QUOTES, 'UTF-8'); ?></div>

          <form method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="file" value="<?php echo htmlspecialchars($currentKey, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="section_key" value="<?php echo htmlspecialchars($activeSection['key'], ENT_QUOTES, 'UTF-8'); ?>">
            <label for="section_html">HTML section (full block)</label>
            <textarea id="section_html" name="section_html"><?php echo htmlspecialchars($activeSection['html'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            <div class="actions">
              <button class="btn-primary" type="submit">Update Section</button>
            </div>
          </form>

          <form method="post" onsubmit="return confirm('Hapus section ini?');">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="file" value="<?php echo htmlspecialchars($currentKey, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="section_key" value="<?php echo htmlspecialchars($activeSection['key'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="actions">
              <button class="btn-danger" type="submit">Delete Section</button>
            </div>
          </form>
        <?php else: ?>
          <h2 class="section-title">Tidak ada section terdeteksi</h2>
          <p class="muted">Tambahkan section baru lewat form di bawah.</p>
        <?php endif; ?>

        <div class="split">
          <div>
            <h3 class="section-title" style="font-size:15px;margin-top:18px;">Create New Section</h3>
            <form method="post">
              <input type="hidden" name="action" value="create">
              <input type="hidden" name="file" value="<?php echo htmlspecialchars($currentKey, ENT_QUOTES, 'UTF-8'); ?>">

              <label for="new_label">Section label</label>
              <input id="new_label" type="text" name="new_label" placeholder="Contoh: Promo Section">

              <label for="insert_before">Insert before</label>
              <select id="insert_before" name="insert_before">
                <option value="">Akhir file (sebelum &lt;/body&gt;)</option>
                <?php foreach ($sections as $section): ?>
                  <option value="<?php echo htmlspecialchars($section['key'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($section['label'], ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                <?php endforeach; ?>
              </select>

              <label for="new_content">HTML content</label>
              <textarea id="new_content" name="new_content" placeholder="Tulis HTML section baru di sini..."></textarea>

              <label>
                <input type="checkbox" name="wrap_comments" value="1" checked style="width:auto;vertical-align:middle;">
                Bungkus otomatis dengan marker Start/End comment
              </label>

              <div class="actions">
                <button class="btn-primary" type="submit">Create Section</button>
              </div>
            </form>
          </div>

          <div>
            <h3 class="section-title" style="font-size:15px;margin-top:18px;">Catatan</h3>
            <p class="muted">Setiap aksi Create/Update/Delete otomatis membuat file backup di folder `.section-backups`.</p>
            <p class="muted">Supaya tampilan tetap sama persis, gunakan class/CSS existing dan hindari ubah struktur global di luar section.</p>
            <p class="muted">Jalankan plugin ini via local server PHP, contoh:</p>
            <pre class="source">php -S 127.0.0.1:8080 -t /home/devsant/website_project/sites/raunsumatra</pre>
            <p class="muted">Lalu buka: <code>http://127.0.0.1:8080/section-crud-plugin.php</code></p>
          </div>
        </div>
      </section>
    </div>
  </div>
</body>
</html>
