<?php
include 'db.php';
$id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM cameras WHERE id = ?");
$stmt->execute([$id]);
$cam = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cam) {
    die("Kamera tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $m3u8 = $_POST['m3u8'];

    $stmt = $db->prepare("UPDATE cameras SET name = ?, m3u8_url = ? WHERE id = ?");
    $stmt->execute([$name, $m3u8, $id]);

    // Update embed file
    $embedFile = __DIR__ . "/embed/camera-$id.html";
    $html = <<<HTML
<!DOCTYPE html>
<html>
<head><title>Camera $id</title>
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<body>
<video id='video' width='640' height='360' controls autoplay></video>
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
    file_put_contents($embedFile, $html);

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Kamera</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h3>Edit Kamera</h3>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Nama Kamera</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($cam['name']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">URL M3U8</label>
      <input type="text" name="m3u8" class="form-control" value="<?= htmlspecialchars($cam['m3u8_url']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="index.php" class="btn btn-secondary">Batal</a>
  </form>
</body>
</html>