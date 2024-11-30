<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['npm'])) {
    header("Location: index.php");
    exit;
}

$npm = $_SESSION['npm'];
$stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE npm = ?");
$stmt->bind_param("s", $npm);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPESAT</title>
    <link rel="stylesheet" href="style/style.css">
    <link href='https://upnkg.com/boxicon@2.1.4/css/boxicons.min.css'rel='stylesheet'>
</head>
<body>
    <!--Make Login Form-->
    <!-- index.html -->
<header>
  <nav class="navbar" aria-label="Main navigation">
    <div class="navbar-container">
      <a href="main.php" class="brand">
        <span class="logo">SIPESAT</span>
      </a>

      <button class="menu-toggle" aria-expanded="false" aria-controls="primary-navigation">
        <span class="hamburger"></span>
        <span class="sr-only"></span>
      </button>

      <div class="nav-content" id="primary-navigation">
        <ul class="nav-links">
          <li><a href="main.php">Beranda</a></li>
          <li><a href="penjadwalan.php">Penjadwalan</a></li>
          <li class="dropdown">
            <button class="dropdown-toggle" aria-expanded="false">
              Pendaftaran
              <span class="dropdown-icon" aria-hidden="true">▼</span>
            </button>
            <ul class="submenu">
              <li><a href="pendaftaranta.php">Sidang TA</a></li>
              <li><a href="pendaftaransp.php">Seminar Penelitian</a></li>
              <li><a href="pendaftarankp.php">Seminar KP</a></li>
              <li><a href="pendaftaranbimbingan.php">Bimbingan</a></li>
            </ul>
          </li>
          <li><a href="penilaian.php">Input Nilai</a></li>
          <li class="dropdown">
            <button class="dropdown-toggle" aria-expanded="false">
              Profil Pengguna
              <span class="dropdown-icon" aria-hidden="true">▼</span>
            </button>
            <ul class="submenu">
              <li><a href="profile.php">Data Diri</a></li>
              <li><a href="riwayatseminar.php">Riwayat Seminar</a></li>
              <li><a href="riwayatbimbingan.php">Riwayat Bimbingan</a></li>
            </ul>
          </li>
        </ul>
          <div class="theme-switcher" onclick="toggleTheme()">🌞</div>
          <a href="logout.php" class="logout-button">Logout</a>
      </div>
    </div>
  </nav>
</header>
       <!-- Image Slider -->
  <div class="slider-container">
    <!-- Slider Utama -->
    <div class="slider">
        <div class="slide">
            <img src="BG1.png" alt="Image 1">
        </div>
        <div class="slide">
            <img src="BG2.png" alt="Image 2">
        </div>
        <div class="slide">
            <img src="BG3.jpg" alt="Image 3">
        </div>
      </div>
    <!-- Tombol Navigasi -->
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>

    <!-- Navigasi Bulat -->
    <div class="dots"></div>
  </div>

  <!-- Slide Bawah (Statis) -->
  <div class="slidebaru">
      <img src="seminar.png" alt="Image 4">
  </div>
  <div class="slidebaru">
      <img src="Bimbingan.png" alt="Image 5">
  </div>
  <!-- Contact Admin Section -->
  <div class="location">
    <div class="title">
        <h2>Lokasi Unsika</h2>
    </div>
    <div class="box">
        <!-- Map -->
        <div class="contact map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.5450964859006!2d107.30410823865117!3d-6.3233209618998725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6977ccc7ce5af1%3A0x387007f6eb3cd4a8!2sFakultas%20Teknik%20UNSIKA!5e0!3m2!1sid!2sid!4v1732001035986!5m2!1sid!2sid" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

 <!-- Footer Section -->

 <!-- Footer dengan struktur semantik yang lebih baik -->
<footer class="site-footer">
  <div class="footer-container">
    <!-- Seksi Subscribe dengan form yang accessible -->
    <section class="footer-section information-chat">
      <div class="footer-container">
        <!-- Logo -->
        <div class="footer-logos">
          <img src="logo-unsika.png" alt="Logo UNSIKA" class="footer-logo">
          <img src="logo-blu.png" alt="Logo BLU" class="footer-logo">
          <img src="logo-kampus-merdeka.png" alt="Logo Kampus Merdeka" class="footer-logo">
        </div>
        <!-- Informasi -->
        <div class="footer-info">
          <h2>Teknik Kimia Unsika</h2>
          <p>Jl. HS. Ronggowaluyo, Telukjambe Timur, Karawang - 41363</p>
          <p>📞 <a href="https://wa.me/+628389352002">0838-9135-2002</a></p>
          <p>✉️ <a href="mailto:sipesatunsika@gmail.com">sipesatunsika@gmail.com</a></p>
        </div>
      </div>
    </section>

    <!-- Seksi About -->
    <section class="footer-section about">
      <h2>About us</h2>
      <p style="text-align: justify;">
        SIPESAT (Sistem Informasi Penjadwalan Seminar dan Tugas Akhir) adalah platform terpadu yang memudahkan mahasiswa dan dosen dalam mengelola kegiatan akademik, seperti seminar dan bimbingan. Dengan SIMBA, mahasiswa dapat mendaftarkan jadwal seminar, mencatat kemajuan bimbingan, serta berkomunikasi dengan dosen secara efisien. Platform ini juga membantu dosen memantau perkembangan mahasiswa dengan lebih mudah dan memastikan bimbingan berjalan sesuai rencana. SIPESAT hadir untuk menciptakan proses akademik yang lebih terstruktur, transparan, dan efektif.
    </p>    
    </section>

    <!-- Seksi Quick Links dengan navigasi semantik -->
    <nav class="footer-section quicklinks">
      <h2>Quick Links</h2>
      <ul>
        <li><a href="main.php" data-scroll>Beranda</a></li>
        <li><a href="penjadwalan.php" data-scroll>Jadwal Seminar</a></li>
        <li><a href="#pendaftaran" data-scroll>Pendaftaran</a></li>
        <li><a href="penilaian.php" data-scroll>Input Nilai</a></li>
        <li><a href="#profil-pengguna" data-scroll>Profil Pengguna</a></li>
      </ul>
    </nav>
  </div>
<!--Language-->
    <div class="google-translate-widget">
      <div class="widget-title">
          <h3 class="title">Bahasa</h3>
      </div>
      <div id="google_translate_element"></div>
    </div>

  <!-- Copyright section -->
  <div class="copyright">
    <p>&copy; 2024 SIMBA | All Rights Reserved</p>
  </div>

  <!-- Back to top button dengan aria label -->
  <a href="#top" class="scroll-to-top" aria-label="Scroll to top" data-scroll>
    <span aria-hidden="true">⭡</span>
  </a>
</footer>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script src="js/main.js"></script>
</body>
</html>
