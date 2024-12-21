<?php
session_start();
include 'config.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_data = null;

if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $result = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'");
    if (mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);
    }
}
?>



<!DOCTYPE html>
<html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
   YumeTales
  </title>
  <link rel="website icon" type="png"
  href="/img/Logo_YumeTales-removebg-preview.png">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet"/>
  <style>
   body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
            font-size: 1rem;
            background-color: #1c1c1c;
            color: #fff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #2c2c2c;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header img {
            height: 50px;
            flex: 0 0 auto;
            box-shadow: 0 2px 5px rgba(28, 28, 28, 1);
            border-radius: 15px;
        }
        .header nav {
            flex-grow: 1;
            text-align: left;
            position: relative;
        }
        .header nav a {
            color: #fff;
            margin: 0 15px;
            text-decoration: none;
        }
        .header .search.active {
            display: flex;
        }
        .header .actions {
            display: flex;
            align-items: center;
            flex: 0 0 auto;
        }
        .header .search {
            position: relative;
            min-width: 250px;
            flex: 1 1 auto;
            display: flex;
            align-items: center;
            margin-right: 15px;
            color: #fff;
            position: relative;
        }
        .header .search input {
            display: none;
            top: 20px;
            left: 0;
            width: 200px;
            padding: 5px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #2c2c2c;
            color: #fff;
            outline: none;
        }
        .header .search.active input {
            display: block;
        }
        .search-results {
            position: absolute;
            top: 40px;
            left: 0;
            background-color: #333;
            color: #fff;
            width: 250px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            display: none;
            flex-direction: column;
            z-index: 1000;
        }
        .search-results.active {
            display: flex;
        }
        .search-results .result-item {
            padding: 10px;
            border-bottom: 1px solid #444;
            cursor: pointer;
        }
        .search-results .result-item:last-child {
            border-bottom: none;
        }
        .search-results .result-item:hover {
            background-color: #444;
        }
        .search-results .close {
            padding: 10px;
            text-align: left;
            cursor: pointer;
            color: #aaa;
        }
        .search-results .close:hover {
            color: #fff;
        }
        
        .banner {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            scroll-behavior: smooth;
            scroll-snap-type: x mandatory;
            padding: 0 20px; /* Ruang di kiri dan kanan */
            background-color: #1c1c1c;
        }

        .banner img {
            width: auto;
            max-width: 90vw; /* Tidak lebih besar dari viewport */
            max-height: 300px; /* Sesuaikan tinggi */
            object-fit: cover;
            border-radius: 20px;
            margin: 0 10px; /* Jarak antar gambar */
            scroll-snap-align: center; /* Snap ke tengah */
            display: inline-block;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 5);
        }


        .section-title {
            margin: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .cards {
            margin-left: 20px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            padding: 10px;
        }
        .card {
            position: relative;
            width: 276px;
            height: 164px;
            background-color: #333;
            border-radius: 15px;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 1);
        }
        .card img {
            position:absolute;
            right: 0px;
            width: 164px;
            height: 164px;
            object-fit: cover;
        }
        .card .info {
            position: absolute;
            bottom: 15px;
            left: 5px;
            padding: 10px;
        }
        .card .info h3 {
            margin: 10px 0;
            font-size: 14px;
        }
        .card .info p {
            font-size: 12px;
            margin: 3px 0;
            color: #aaa;
        }
        .card .price-tag {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #6a0dad;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .old-price {
            text-decoration: line-through;
            color: red;
            margin-right: 5px;
        }
        .faq {
            padding: 20px;
        }
        .faq h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .faq-item {
            background-color: #333;
            border-radius: 10px;
            margin-bottom: 10px;
            overflow: hidden;
        }
        .faq-item button {
            width: 100%;
            padding: 15px;
            background-color: #333;
            color: #fff;
            border: none;
            text-align: left;
            font-size: 16px;
            cursor: pointer;
        }
        .faq-item .content {
            display: none;
            padding: 15px;
            background-color: #444;
        }
        @media (max-width: 768px) {
            body {
                font-size: 0.9rem;
            }
            .banner img {
                max-height: 300px;
            }
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
            .card {
                width: 100%;
                max-width: 100%;
            }
            .header {
                flex-direction: column;
                flex-wrap: nowrap;
                align-items: flex-end;
                padding: 10px;
            }
            .header img {
                margin-right: auto;
            }
            .header .search {
                flex-grow: 0;
                order: 2;
            }
            .header nav {
                width: 100%;
                margin-bottom: 10px;
            }
            .header .actions {
                flex-direction: column;
                gap: 10px;
                margin-left: auto;
            }
            .header .login a {
                margin-bottom: 5px;
            }
            .faq {
                padding: 10px;
            }
            .faq-item button {
                font-size: 14px;
                padding: 10px;
            }
            .faq-item .content {
                padding: 10px;
            }
        }
        @media (max-width: 480px) {
            body {
                font-size: 0.8rem;
            }
            .banner img {
                max-height: 200px;
            }
            .dropdown {
                position: static;
                display: block;
            }
            .cards {
                display: flex;
                flex-direction: column;
                margin-left: auto;
                gap: 15px;
                grid-template-columns: 1fr;
                align-items: center;
            }
        }
        .user-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: 20px;
        }
        .user-info {
            display: flex;
            align-items: center; /* Membuat elemen sejajar secara vertikal */
            gap: 10px; /* Jarak antara avatar dan username */
            margin-right: 20px; /* Jarak antara user-info dan ikon */
        }

        .user-avatar img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .user-name {
            font-size: 16px; /* Ukuran font username */
            font-weight: bold; /* Membuat username lebih tegas */
            color: #fff; /* Warna putih */
        }


  </style>
 </head>
 <body>
  <header class="header">
   <img alt="Logo" height="50" src="img/Logo_YumeTales-removebg-preview.png" width="85"/>
   <nav>
   </nav>
   <header>
    <div class="icon">
    </div>
    <div class="user-actions">
        <?php if ($is_logged_in && $user_data): ?>
            <a href="home.php" class="user-avatar">
            <div class="user-info">
            <?php
            $avatarPath = "uploaded_img/" . ($user_data['image'] ?? '');
            if (!file_exists($avatarPath) || empty($user_data['image'])) {
                $avatarPath = "images/default-avatar.png";
            }
            ?>
            <img src="<?php echo $avatarPath; ?>" alt="Avatar" class="user-avatar">
            <span class="user-name"><?php echo htmlspecialchars($user_data['name']); ?></span>
            </div>
            </a>
        <?php else: ?>
            <a href="login.php" style="color: white;">Login</a> | <a href="register.php" style="color: white;">Sign Up</a>

        <?php endif; ?>
    </div>
</header>

   <div class="actions">
    <div class="search">
     <i class="fas fa-search"></i>
     <input type="text" id="searchInput" placeholder="Cari judul...">
     <div class="search-results" id="searchResults">
      <div class="close">Tutup</div>
     </div>
    </div>
   </div>
  </header>
  <section class="banner" id="banner">
    <img src="img/Navy Playful World Children's Book Day Banner (4).png" alt="Banner 1">
    <img src="img/Navy Playful World Children's Book Day Banner (4).png" alt="Banner 2">
    <img src="img/Navy Playful World Children's Book Day Banner (4).png" alt="Banner 3">
    <img src="img/Navy Playful World Children's Book Day Banner (4).png" alt="Banner ">
  </section>
  <section>
   <h2 class="section-title">
    Terpopuler
   </h2>
   <div class="cards">
    <div class="card">
     <div class="price-tag">Rp 5.000</div>
     <img alt="Character Image" src="img/Wanita Berwajah Penyok.png"/>
     <div class="info">
      <h3>
       Wanita<br> Berwajah Penyok
      </h3>
      <p>
       Drama | <span style="color: #007fff;">Cerpen</span>
      </p>
     </div>
    </div>
    <div class="card">
     <div class="price-tag">Rp 3.500</div>
     <img alt="Character Image" src="img/Melodi dari Alam Fantasi.png"/>
     <div class="info">
      <h3>
       Melodi dari<br>Alam Fantasi
      </h3>
      <p>
       Fantasy | <span style="color: #007fff;">Cerpen</span>
      </p>
     </div>
    </div>
    <div class="card">
     <div class="price-tag">Rp 4.000</div>
     <img alt="Character Image" src="img/karantina terakhir.png"/>
     <div class="info">
      <h3>
       Karantina Terakhir
      </h3>
      <p>
       Horror | <span style="color: #007fff;">Cerpen</span>
      </p>
     </div>
    </div>
    <div class="card">
     <div class="price-tag">Rp 2.500</div>
     <img alt="Character Image" src="img/pencarian jodoh yang gagal.png"/>
     <div class="info">
      <h3>
       Pencarian Jodoh<br> yang Gagal
      </h3>
      <p>
       Comedy | <span style="color: #007fff;">Cerpen</span>
      </p>
     </div>
    </div>
    <div class="card">
     <div class="price-tag"><span class="old-price">Rp 7.000<Br></span>Rp 0</div>
     <img alt="Character Image" src="img/Persahabatan.png"/>
     <div class="info">
      <h3>
       Persahabatan
      </h3>
      <p>
       Slice of Life | <span style="color: #007fff;">Cerpen</span>
      </p>
     </div>
    </div>
    <div class="card">
     <div class="price-tag">Rp 14.000</div>
     <img alt="Character Image" src="img/waktu tertunda.png"/>
     <div class="info">
      <h3>
       Waktu yang Tertunda
      </h3>
      <p>
       Sci-Fi | <span style="color: #007fff;">Cerpen</span>
      </p>
     </div>
    </div>
    <div class="card">
     <div class="price-tag">Rp 5.500</div>
     <img alt="Character Image" src="img/Kurir.png"/>
     <div class="info">
      <h3>
       Kurir
      </h3>
      <p>
       Thriller | <span style="color: #007fff;">Cerpen</span>
      </p>
     </div>
    </div>
    <div class="card">
     <div class="price-tag">Rp 2.500</div>
     <img alt="Character Image" src="img/wounded love.png"/>
     <div class="info">
      <h3>
       Wounded<br> Love
      </h3>
      <p>
       Romance | <span style="color: #007fff;">Novel</span>
      </p>
     </div>
    </div>
   </div>
  </section>
  <section class="faq">
   <h2>
    FAQ
   </h2>
   <div class="faq-item">
    <button>
     Apa itu YumeTales
    </button>
    <div class="content">
     <p>
        YumeTales adalah website yang dibuat untuk membaca cerpen dan novel secara mudah dan menyediakan beberapa bacaan gratis.
     </p>
    </div>
   </div>
   <div class="faq-item">
    <button>
     Bagaimana jika saya menemukan kesalahan?
    </button>
    <div class="content">
     <p>
      Jika anda menemukan kesalahan, Silahkan hubungi kami melalui media sosial kami. Kami sangat menghargai masukan anda dan akan segera memperbaikinya
     </p>
    </div>
   </div>
   <div class="faq-item">
    <button>
     Bagaimana jika saya ingin memberikan karya saya?
    </button>
    <div class="content">
     <p>
      Jika anda adalah penulis dan ingin berbagi karya anda, silahkan kirimkan minat anda melalui media sosial kami
     </p>
    </div>
   </div>
   <div class="faq-item">
    <button>
     Apa akun media sosial resmi YumeTales?
    </button>
    <div class="content">
     <p>
      Ig @yumetales_
     </p>
    </div>
   </div>
  </section>
  <script>
   document.querySelectorAll('.faq-item button').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                button.classList.toggle('active');
                if (button.classList.contains('active')) {
                    content.style.display = 'block';
                } else {
                    content.style.display = 'none';
                }
            });
        });

   // Search functionality
   const searchIcon = document.querySelector('.search i');
   const searchInput = document.getElementById('searchInput');
   const searchResults = document.getElementById('searchResults');

   searchIcon.addEventListener('click', () => {
      document.querySelector('.search').classList.toggle('active');
      searchInput.focus(); // Focus on input when search is active
   });

   searchInput.addEventListener('input', (e) => {
      const query = e.target.value.toLowerCase();
      const cards = document.querySelectorAll('.card');
      searchResults.innerHTML = '<div class="close">Tutup</div>'; // Reset results
      let hasResults = false;

      cards.forEach(card => {
         const title = card.querySelector('h3').textContent.toLowerCase();
         if (title.includes(query) && query !== '') {
            const resultItem = document.createElement('div');
            resultItem.className = 'result-item';
            resultItem.textContent = title;
            searchResults.appendChild(resultItem);
            hasResults = true;
         }
      });

      if (hasResults) {
         searchResults.classList.add('active');
      } else {
         searchResults.classList.remove('active');
      }
   });

   searchResults.addEventListener('click', (e) => {
      if (e.target.classList.contains('close')) {
         searchResults.classList.remove('active');
         searchInput.value = '';
         document.querySelector('.search').classList.remove('active');
      }
   });
   const banner = document.getElementById('banner');
let isUserScrolling = false;
let currentIndex = 0;

// Deteksi ketika pengguna mulai menggulir
banner.addEventListener('scroll', () => {
    isUserScrolling = true;
    clearTimeout(userScrollTimeout);
    userScrollTimeout = setTimeout(() => isUserScrolling = false, 2000);
});

let userScrollTimeout;

// Fungsi untuk auto-scroll
setInterval(() => {
    if (!isUserScrolling) {
        const banners = banner.querySelectorAll('img');
        const bannerWidth = banners[0].clientWidth + 20; // Tambahkan margin antar banner (10px kiri & kanan)
        currentIndex = (currentIndex + 1) % banners.length;

        banner.scrollTo({
            left: currentIndex * bannerWidth,
            behavior: 'smooth',
        });
    }
}, 5000); // Interval 5 detik

  </script>
 </body>
</html>
