<?php
include 'koneksi.php'; // Include file koneksi MySQLi

$loginError = false; // Variabel untuk mendeteksi kesalahan login
$registerSuccess = false; // Variabel untuk mendeteksi sukses registrasi

// Fungsi login
function login($conn)
{
    global $loginError; // Akses variabel global untuk error login

    if (isset($_POST['npm']) && isset($_POST['password'])) {
        $npm = $_POST['npm']; // Untuk mahasiswa, gunakan npm
        $password = $_POST['password'];

        // Login sebagai mahasiswa
        $stmtMahasiswa = $conn->prepare("SELECT * FROM mahasiswa WHERE npm = ?");
        $stmtMahasiswa->bind_param("s", $npm);
        $stmtMahasiswa->execute();
        $resultMahasiswa = $stmtMahasiswa->get_result();
        $userMahasiswa = $resultMahasiswa->fetch_assoc();

        // Verifikasi password untuk mahasiswa
        if ($userMahasiswa && password_verify($password, $userMahasiswa['password'])) {
            session_start();
            $_SESSION['npm'] = $userMahasiswa['npm']; // Simpan NPM ke session
            echo "<script>alert('Login Berhasil!'); window.location.href = 'main.php';</script>";
            exit;
        }

        // Login sebagai dosen
        $stmtDosen = $conn->prepare("SELECT * FROM dosen WHERE nidn = ?");
        $stmtDosen->bind_param("s", $npm); // Gunakan variabel yang sama ($npm) untuk nidn
        $stmtDosen->execute();
        $resultDosen = $stmtDosen->get_result();
        $userDosen = $resultDosen->fetch_assoc();

        // Verifikasi password untuk dosen
        if ($userDosen && $password === "adminunsika") {
            session_start();
            $_SESSION['nidn'] = $userDosen['nidn']; // Simpan NIDN ke session
            echo "<script>alert('Login Berhasil!'); window.location.href = 'maindosen.php';</script>";
            exit;
        }

        $loginError = true; // Menandakan bahwa login gagal
        echo "<script>alert('NPM atau Password salah!');</script>"; // Alert ketika login gagal
    }
}

// Fungsi registrasi
function register($conn)
{
    global $registerSuccess;

    if (isset($_POST['nama']) && isset($_POST['npm']) && isset($_POST['email']) && isset($_POST['password'])) {
        $nama = $_POST['nama'];
        $npm = $_POST['npm'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        // Cek apakah NPM sudah ada di database
        $stmtCheckNpm = $conn->prepare("SELECT * FROM mahasiswa WHERE npm = ?");
        $stmtCheckNpm->bind_param("s", $npm);
        $stmtCheckNpm->execute();
        $resultNpm = $stmtCheckNpm->get_result();
        if ($resultNpm->num_rows > 0) {
            echo "<script>alert('NPM sudah terdaftar.');</script>";
            return;
        }

        // Cek apakah email sudah ada di database
        $stmtCheckEmail = $conn->prepare("SELECT * FROM mahasiswa WHERE email = ?");
        $stmtCheckEmail->bind_param("s", $email);
        $stmtCheckEmail->execute();
        $resultEmail = $stmtCheckEmail->get_result();
        if ($resultEmail->num_rows > 0) {
            echo "<script>alert('Email sudah terdaftar.');</script>";
            return;
        }

        // Query untuk memasukkan data pengguna baru
        $stmt = $conn->prepare("INSERT INTO mahasiswa (nama, npm, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $npm, $email, $password);

        try {
            $stmt->execute();
            $registerSuccess = true;
            echo "<script>alert('Registrasi berhasil, silakan login.'); window.location.href = 'index.php';</script>";
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Kode 1062 adalah kode untuk pelanggaran unik (duplicate entry) di MySQL
                echo "<script>alert('NPM atau email sudah terdaftar.');</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "');</script>";
            }
        }
    }
}


// Tentukan apakah permintaan login atau registrasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'login') {
        login($conn);
    } elseif ($_POST['action'] === 'register') {
        register($conn);
        if ($registerSuccess) {
            echo "<script>alert('Registrasi berhasil, silakan login.'); window.location.href = 'index.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Register</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="style/stylelogin.css">
</head>

<body>
    <section>
        <!-- Login Box -->
        <div class="form-box" id="login-box">
            <div class="form-value">
                <form action="index.php" method="POST">
                    <h2>Login</h2>
                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="npm" required>
                        <label>NPM</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <div class="forget">
                        <label><input type="checkbox"> Remember Me</label>
                        <a href="#">Forgot Password?</a>
                    </div>
                    <input type="hidden" name="action" value="login">
                    <button type="submit">Log In</button>
                    <div class="register">
                        <p>Don't have an account? <a href="#" id="register-link">Sign Up</a></p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Register Box -->
        <div class="form-box" id="register-box" style="display: none;">
            <div class="form-value">
                <form action="index.php" method="POST">
                    <h2>Register</h2>
                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="nama" required>
                        <label>Nama</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="card-outline"></ion-icon>
                        <input type="text" name="npm" required>
                        <label>NPM</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <input type="hidden" name="action" value="register">
                    <button type="submit">Register</button>
                    <div class="register">
                        <p>Already have an account? <a href="#" id="login-link">Log In</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        const registerLink = document.getElementById('register-link');
        const loginLink = document.getElementById('login-link');
        const loginBox = document.getElementById('login-box');
        const registerBox = document.getElementById('register-box');

        registerLink.addEventListener('click', (e) => {
            e.preventDefault();
            toggleFormVisibility(registerBox, loginBox);
        });

        loginLink.addEventListener('click', (e) => {
            e.preventDefault();
            toggleFormVisibility(loginBox, registerBox);
        });

        function toggleFormVisibility(showBox, hideBox) {
            hideBox.style.opacity = 0;
            hideBox.style.transform = "translateY(20px)";
            setTimeout(() => {
                hideBox.style.display = 'none';
                showBox.style.display = 'block';
                setTimeout(() => {
                    showBox.style.opacity = 1;
                    showBox.style.transform = "translateY(0)";
                }, 10);
            }, 300);
        }
    </script>
</body>

</html>
