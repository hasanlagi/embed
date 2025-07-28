<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $m3u8 = $_POST['m3u8'];

    // Simpan ke database
    $stmt = $db->prepare("INSERT INTO cameras (name, m3u8_url) VALUES (?, ?)");
    $stmt->execute([$name, $m3u8]);
    $id = $db->lastInsertId();

    // Buat file embed camera-[id].html
    $embedFile = __DIR__ . "/embed/camera-$id.html";
    $embedHTML = <<<HTML
<!DOCTYPE html>
<html>
<head>
  <title>Live Camera $id</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      width: 100%;
      background: black;
      overflow: hidden;
    }
    video {
      width: 100vw;
      height: 100vh;
      object-fit: cover;
      background: black;
    }
  </style>
</head>
<body>
  <video id="video" autoplay muted controls></video>
  <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
  <script>
    var video = document.getElementById('video');
    var videoSrc = '$m3u8';
    if (Hls.isSupported()) {
      var hls = new Hls();
      hls.loadSource(videoSrc);
      hls.attachMedia(video);
      hls.on(Hls.Events.MANIFEST_PARSED, function () { video.play(); });
    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
      video.src = videoSrc;
      video.addEventListener('loadedmetadata', function () { video.play(); });
    }
  </script>
</body>
</html>
HTML;

    file_put_contents($embedFile, $embedHTML);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Tambah Kamera</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h3>Tambah Kamera Baru</h3>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Nama Kamera</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">URL M3U8 (dari Shinobi)</label>
      <input type="text" name="m3u8" class="form-control" placeholder="http://2.2.2.15:8080/...s.m3u8" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="index.php" class="btn btn-secondary">Kembali</a>
  </form>
</body>
</html>
