<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard Kamera</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-header span.status {
      float: right;
      font-size: 0.9rem;
      padding: 3px 8px;
      background: #666;
      color: white;
      border-radius: 8px;
    }
    .btn-group .btn {
      margin-right: 5px;
      margin-bottom: 5px;
    }
  </style>
</head>
<body class="container mt-5">
  <h2 class="mb-4">Dashboard Kamera</h2>
  <a href="add_camera.php" class="btn btn-success mb-3">➕ Tambah Kamera</a>
  <div class="row">
    <?php
    $cams = $db->query("SELECT * FROM cameras")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cams as $cam):
    ?>
    <div class="col-md-6 mb-4">
      <div class="card shadow">
        <div class="card-header bg-dark text-white">
          <?= htmlspecialchars($cam['name']) ?>
          <span class="status">● Mati</span>
        </div>
        <div class="card-body">
          <video id="video<?= $cam['id'] ?>" width="100%" height="300" controls autoplay muted style="background: black;"></video>
          <script>
            const video<?= $cam['id'] ?> = document.getElementById('video<?= $cam['id'] ?>');
            const url<?= $cam['id'] ?> = "<?= htmlspecialchars($cam['m3u8_url']) ?>";
            if (Hls.isSupported()) {
              var hls<?= $cam['id'] ?> = new Hls();
              hls<?= $cam['id'] ?>.loadSource(url<?= $cam['id'] ?>);
              hls<?= $cam['id'] ?>.attachMedia(video<?= $cam['id'] ?>);
              hls<?= $cam['id'] ?>.on(Hls.Events.MANIFEST_PARSED, function () {
                video<?= $cam['id'] ?>.play();
              });
            } else if (video<?= $cam['id'] ?>.canPlayType('application/vnd.apple.mpegurl')) {
              video<?= $cam['id'] ?>.src = url<?= $cam['id'] ?>;
              video<?= $cam['id'] ?>.addEventListener('loadedmetadata', function () {
                video<?= $cam['id'] ?>.play();
              });
            }
          </script>

          <div class="mt-3">
            <label>Link Embed:</label>
            <div class="input-group mb-3">
              <input type="text" class="form-control" readonly value="https://nvr.hasannetwork.com/embed/camera-<?= $cam['id'] ?>.html">
              <button class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText(this.previousElementSibling.value)">📋 Salin</button>
            </div>
          </div>

          <div class="btn-group">
            <button class="btn btn-success">🟢 Start</button>
            <button class="btn btn-warning">🟡 Stop</button>
            <!-- Fullscreen button removed -->
            <a href="edit_camera.php?id=<?= $cam['id'] ?>" class="btn btn-secondary">✏️ Edit</a>
            <a href="delete_camera.php?id=<?= $cam['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus kamera ini?')">🗑️ Hapus</a>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</body>
</html>
