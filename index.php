<?php
// Jika permintaan popup iklan
if (isset($_GET['popup']) && isset($_GET['url'])) {
    $link = urldecode($_GET['url']);
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
      <meta charset="UTF-8">
      <title>Iklan Sebelum Artikel</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <style>
        body { font-family: sans-serif; text-align: center; padding-top: 50px; }
        .popup-container { max-width: 400px; margin: auto; border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 20px; font-size: 16px; text-decoration: none; background: #007bff; color: #fff; border-radius: 5px; }
        .btn:hover { background: #0056b3; }
      </style>
    </head>
    <body>
      <div class="popup-container">
  <h3>🎯 Advertisement</h3>
  <p>Thank you! Please click the button below to go to the article.</p>

  <!-- ✅ KODE IKLAN BANNER 300x250 -->
  <div style="margin: 20px 0;">
    <!-- GANTI dengan kode dari jaringan iklan Anda -->
    <script type="text/javascript">
 atOptions = {
  'key' : '0bbaf30a29714185ee4a6e364cd2c548',
  'format' : 'iframe',
  'height' : 250,
  'width' : 300,
  'params' : {}
 };
</script>
<script type="text/javascript" src="//www.highperformanceformat.com/0bbaf30a29714185ee4a6e364cd2c548/invoke.js"></script>
  </div>

  <!-- Tombol lanjut -->
  <a href="<?= htmlspecialchars($link) ?>" target="_blank" class="btn">👉 Continue Reading</a>
</div>
    </body>
    </html>
    <?php exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>NYTimes Feed Grid</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card-img-top { height: 180px; object-fit: cover; }
    .card-text { font-size: 0.95rem; }
    .iklan-banner { text-align: center; margin: 20px 0; }
  </style>
</head>
<body>
  <div class="container py-5">
    <h1 class="mb-4 text-center">🗞️ Happening Now!</h1>

    <form method="GET" class="mb-4">
      <div class="row g-2 justify-content-center">
        <div class="col-md-6">
          <select name="kategori" class="form-select">
            <?php
            $kategoriList = ['HomePage', 'World', 'Technology', 'Science', 'Politics', 'Business'];
            $kategoriDipilih = $_GET['kategori'] ?? 'World';
            foreach ($kategoriList as $k) {
              $selected = ($k === $kategoriDipilih) ? 'selected' : '';
              echo "<option value=\"$k\" $selected>$k</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-primary w-100">Go!</button>
        </div>
      </div>
    </form>

    <div class="row">
<?php
$kategori = $_GET['kategori'] ?? 'World';
$rss_url = "https://rss.nytimes.com/services/xml/rss/nyt/" . urlencode($kategori) . ".xml";

function getRssViaCurl($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0'
    ]);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data ? simplexml_load_string($data) : false;
}

$rss = getRssViaCurl($rss_url);

if (!$rss) {
    echo "<div class='alert alert-danger'>Gagal memuat feed dari New York Times.</div>";
} else {
    $items = [];
    foreach ($rss->channel->item as $item) {
        $title = htmlspecialchars($item->title);
        $link = htmlspecialchars($item->link);
        $pubDate = date("d M Y H:i", strtotime($item->pubDate));
        $description = strip_tags($item->description);
        $summary = substr($description, 0, 140) . "...";

        $image = 'https://via.placeholder.com/400x200?text=No+Image';

        $ns = $item->getNamespaces(true);
        if (isset($ns['media'])) {
            $media = $item->children($ns['media']);
            if ($media->content && $media->content->attributes()) {
                $image = (string)$media->content->attributes()->url;
            }
        }

        if (empty($image) || strpos($image, 'placeholder') !== false) {
            if (preg_match('/<img.+src=["\'](?P<src>.+?)["\']/', $item->description, $matches)) {
                $image = $matches['src'];
            }
        }

        if (strpos($image, 'placeholder') !== false) continue;

        $items[] = [
            'title' => $title,
            'link' => $link,
            'pubDate' => $pubDate,
            'summary' => $summary,
            'image' => $image
        ];
    }

    // Tentukan 4 index acak untuk iklan
    $total = count($items);
    $iklanIndex = array_rand(range(0, max(0, $total - 1)), min(4, $total));
    $iklanIndex = (array)$iklanIndex;

    foreach ($items as $i => $item) {
    if (in_array($i, $iklanIndex)) {
        echo "<div class='col-md-4 mb-4 iklan-banner'>";
        echo <<<HTML
<script type="text/javascript">
 atOptions = {
  'key' : '0bbaf30a29714185ee4a6e364cd2c548',
  'format' : 'iframe',
  'height' : 250,
  'width' : 300,
  'params' : {}
 };
</script>
<script type="text/javascript" src="//www.highperformanceformat.com/0bbaf30a29714185ee4a6e364cd2c548/invoke.js"></script>
HTML;
        echo "</div>";
    }

    $popupURL = "?popup=1&url=" . urlencode($item['link']);
    echo "<div class='col-md-4 mb-4'>";
    echo "<div class='card h-100 shadow-sm'>";
    echo "<img src='{$item['image']}' class='card-img-top' alt='gambar'>";
    echo "<div class='card-body d-flex flex-column'>";
    echo "<h5 class='card-title'>{$item['title']}</h5>";
    echo "<p class='card-text'>{$item['summary']}</p>";
    echo "<p class='text-muted small mt-auto'>{$item['pubDate']}</p>";
    echo "<a href='$popupURL' target='_blank' class='btn btn-sm btn-outline-primary mt-2'>Read More</a>";
    echo "</div></div></div>";
}
}
?>
    </div>
  </div>
</body>
</html>
