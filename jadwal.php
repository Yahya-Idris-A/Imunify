<!DOCTYPE html>
<?php
// Koneksi ke database
$host = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "imunify"; 

$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data puskesmas
$sql = "SELECT 
            p.nama,
            p.alamat,
            p.kontak,
            j.hari,
            j.jam AS jam_format,
            j.jenis_imunisasi
        FROM puskesmas p
        LEFT JOIN jadwal j ON p.nama = j.nama_puskesmas
        ORDER BY p.nama, FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), j.jam";
$result = $conn->query($sql);

// Kelompokkan data berdasarkan puskesmas
$dataPuskesmas = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $namaPuskesmas = $row['nama'];
        if (!isset($dataPuskesmas[$namaPuskesmas])) {
            $dataPuskesmas[$namaPuskesmas] = [
                'alamat' => $row['alamat'],
                'kontak' => $row['kontak'],  // Simpan nomor WhatsApp
                'jadwal' => []
            ];
        }
        if ($row['hari']) {
            $dataPuskesmas[$namaPuskesmas]['jadwal'][] = [
                'hari' => $row['hari'],
                'jam' => $row['jam_format'],
                'jenis_imunisasi' => $row['jenis_imunisasi']
            ];
        }
    }
}

$conn->close();
?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Imunify</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">


    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"> 

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="lib/twentytwenty/twentytwenty.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        /* Add custom styles for the cards */
        .card {
            color: #343a40; /* White text */
            border-radius: 20px; /* Rounding corners */
            padding: 20px; /* Adding padding */
            margin: 20px; /* Margin around cards */
            flex: 1; /* Make cards flexible */
            max-width: 48%; /* Limit max-width */
            margin-bottom: 4px;
            margin-left: 8px;
            padding-right: 16px;
            padding-bottom: 16px;
            position: relative;
            overflow: visible;
            box-sizing: border-box;
        }
        .schedule-container, .schedule-item {
            margin: 10px 0;
        }

        .card .h1 {
            font-size: 24px;
            margin-bottom: 30px;
        }

        .card .h2 {
            font-size: 18px;
            font-weight: bold;
        }

        .card .h3 {
            font-size: 16px;
            font-weight: lighter;
        }

        .card .jadwal {
            margin-top: 10px;
            font-weight: bold;
        }

        .card .jam {
        float: right;
        font-weight: normal;
        padding-right: 16px;
        }

        .card .jenis {
          margin-top: 5px;
          white-space: pre-line;
        }
    
        .card .hubungi {
          position: inherit;;
          bottom: 15px;
          right: 15px;
          display: flex;
          align-items: center;
          font-size: 14px;
        }

        .card .hubungi img {
          margin-left: 5px;
          width: 36px;
          height: 36px;
          align-items: center;
        }
        .vaccine-link {
            color: white; /* Ensure links are white */
        }
        .row {
            display: flex; /* Use flexbox for row layout */
            flex-wrap: wrap; /* Allow wrapping for smaller screens */
        }

        .whatsapp-link {
            display: inline-block;
            background-color: #4dff8e;
            color: rgb(0, 0, 0);
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            margin-top: 15px;
            font-weight: 500;
            transition: background-color 0.3s;
            align-items: center;
        }

        .whatsapp-btn {
            display: inline-block;
            background-color: #25D366;
            color: white;
            padding: 12px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 4px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .whatsapp-btn:hover {
            background-color: #128C7E;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            color: aliceblue;
        }
    </style>
</head>

<body>

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm px-5 py-3 py-lg-0">
        <a href="index.html" class="navbar-brand p-0 d-flex align-items-center">
            <img src="About.jpg" alt="Logo_Imunify" style="height: 50px; margin-right: 10px;">
            <span style="color: #00B2FF; font-weight: bold; font-size: 30px;">Imunify</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="index.html" class="nav-item nav-link">Home</a>
                <a href="About.html" class="nav-item nav-link">About</a>
                <a href="jadwal.php" class="nav-item nav-link active">Jadwal</a>
                <a href="infoimunisasi.html" class="nav-item nav-link">Informasi Imunisasi</a>
                <a href="ArtikelBaru.html" class="nav-item nav-link">Artikel</a>
            </div>
            <button type="button" class="btn text-dark" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i></button>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Full Screen Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" style="background: rgba(9, 30, 62, .7);">
                <div class="modal-header border-0">
                    <button type="button" class="btn bg-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center">
                    <div class="input-group" style="max-width: 600px;">
                        <input type="text" class="form-control bg-transparent border-primary p-3" placeholder="Type search keyword">
                        <button class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Full Screen Search End -->


    <!-- Hero Start -->
    <div class="container-fluid bg-primary py-5 hero-header mb-5">
        <div class="row py-3">
            <div class="col-12 text-center">
                <h1 class="display-3 text-white animated zoomIn">Jadwal Imunisasi</h1>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Service Start -->
    <!-- Service Start -->
<div style="background-color: #06a6dd; padding: 20px 0;">
    <div class="container-fluid py-1 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-1">
            <div class="row justify-content-center">
                <?php 
                // Hitung jumlah untuk menentukan row
                $count = 0;
                foreach ($dataPuskesmas as $namaPuskesmas => $data): 
                    // Buka row baru setiap 2 card
                    if ($count % 2 == 0) {
                        echo '<div class="background-color: #06a6dd; d-flex flex">';
                    }
                ?>
                <div class="card" style="margin-bottom: <?php echo ($count % 2 == 0) ? '4px' : '2px'; ?>;">
                    <h2><?php echo htmlspecialchars($namaPuskesmas); ?></h2>
                    <div class="schedule-container">
                        <ul>
                            <?php foreach ($data['jadwal'] as $jadwal): ?>
                            <div class="schedule-item">
                                <li>
                                    <span class="time h1"><?php echo htmlspecialchars($jadwal['hari']); ?></span>
                                    <span class="jam h2"><?php echo htmlspecialchars($jadwal['jam']); ?></span><br>
                                    <a class="jenis h3" style="color: #343a40;"><?php echo htmlspecialchars($jadwal['jenis_imunisasi']); ?></a>
                                </li>
                            </div>
                            <?php endforeach; ?>
                            
                            <p>📍 <?php echo htmlspecialchars($data['alamat']); ?></p><br>
                            <div class="hubungi">
                                <a href="https://wa.me/<?php echo htmlspecialchars($data['kontak']); ?>?text=Mau%20daftar%20imunisasi%20di%20<?php echo urlencode($namaPuskesmas); ?>" 
                                    class="whatsapp-btn" 
                                    onclick="return confirmRedirect()">
                                    <span class="whatsapp-icon"></span> Klik untuk Daftar Imunisasi
                                </a>
                            </div>
                        </ul>
                    </div>
                </div>
                <?php 
                    // Tutup row setelah 2 card
                    if ($count % 2 == 1 || $count == count($dataPuskesmas) - 1) {
                        echo '</div>';
                    }
                    $count++;
                endforeach; 
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Service End -->
    

    <!-- Footer Start -->
    <div class="container-fluid text-light py-4" style="background: #051225;">
        <div class="container">
            <div class="row g-0">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-md-0">&copy; <a class="text-white border-bottom" href="#">Pemrograman Web Kelompok 5</a></p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Manajemen Informasi Kesehatan 2022 <a class="text-white border-bottom">
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="lib/twentytwenty/jquery.event.move.js"></script>
    <script src="lib/twentytwenty/jquery.twentytwenty.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script>
    function confirmRedirect() {
        const userConfirmed = confirm("Anda akan diarahkan ke WhatsApp puskesmas");
        return userConfirmed; // Jika user klik OK akan lanjut, jika Cancel tidak lanjut
    }
</script>
</body>

</html>