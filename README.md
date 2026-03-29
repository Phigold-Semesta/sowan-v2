<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---


# 🏛️ SOWAN v2 - Digital Guest Book UKPBJ Karawang

**SOWAN v2** (**Sistem Otomasi Warta Administrasi Normatif**) adalah platform buku tamu digital mutakhir yang dirancang khusus untuk entitas pemerintah, khususnya **LPSE/UKPBJ Kabupaten Karawang**. Proyek ini hadir sebagai solusi digital untuk mentransformasi pencatatan kunjungan konvensional menjadi ekosistem data yang terintegrasi, real-time, dan akuntabel di lingkungan UKPBJ dengan sentuhan desain yang **Bold & Luxurious**.

---

## 🌿🧠💡 Filosofi Penamaan & Kearifan Lokal

Nama **SOWAN** dipilih bukan sekadar sebagai akronim teknis, melainkan membawa muatan filosofis dan budaya yang mendalam:

1.  **Etimologi Budaya (Kearifan Lokal)**
    Dalam budaya masyarakat Indonesia, istilah *"Sowan"* memiliki arti berkunjung atau menghadap kepada pihak yang dihormati dengan penuh tata krama. Penggunaan nama ini merepresentasikan bahwa setiap tamu yang datang ke kantor pemerintahan adalah subjek yang harus dilayani dengan rasa hormat dan integritas tinggi.

2.  **Makna Akronim Teknis**
    * **Sistem Otomasi:** Melambangkan transformasi dari proses manual yang repetitif menuju digitalisasi yang efisien dan cerdas melalui teknologi Laravel 12.
    * **Warta:** Berasal dari bahasa Sanskerta yang berarti berita atau informasi. Setiap tamu membawa "Warta" (maksud dan tujuan) yang harus dikelola secara akurat.
    * **Administrasi:** Menitikberatkan pada ketertiban tata kelola data untuk memastikan setiap jejak kunjungan terdokumentasi dengan rapi sebagai arsip negara.
    * **Normatif:** Menegaskan bahwa aplikasi ini dibangun di atas koridor aturan, standar operasional prosedur (SOP), dan transparansi yang berlaku di lingkungan birokrasi.

---


## ✨ Fitur Utama
* **Self-Registration (QR Code):** Tamu dapat mendaftar mandiri dengan memindai QR Code melalui smartphone masing-masing di lokasi.
* **Smart Recognition:** Sistem mengenali tamu lama hanya dengan input nomor WhatsApp atau Gmail untuk mempercepat proses log kunjungan tanpa isi form ulang.
* **Petugas Dashboard:** Manajemen status pelayanan tamu (Belum, Sedang, Sudah Dilayani) secara interaktif langsung dari halaman data tamu.
* **Role-Based Access Control:** Pemisahan hak akses antara Tamu, Petugas, Administrator, dan Pimpinan.
* **Real-time Analytics:** Monitoring antrean dan visualisasi data kunjungan harian untuk kebutuhan audit dan laporan pimpinan.

## 🛠️ Tech Stack
* **Framework:** Laravel 12 (PHP 8.x)
* **Database:** MySQL (Managed via phpMyAdmin)
* **Styling:** Tailwind CSS (Modern & Luxurious Design - Emerald Emerald-900 & Gold/Slate)
* **Monitoring:** Laravel Pulse & Pest Stress Testing
* **Authentication:** Custom Manual Login & Logout System (Tanpa Laravel Breeze/Fortify)

## 🚀 Cara Instalasi (Development)

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/Phigold-Semesta/sowan-v2.git](https://github.com/Phigold-Semesta/sowan-v2.git)
    ```
2.  **Instal Dependensi**
    ```bash
    composer install
    npm install && npm run dev
    ```
3.  **Konfigurasi Environment**
    Salin file `.env.example` menjadi `.env`, atur koneksi database, lalu jalankan:
    ```bash
    php artisan key:generate
    php artisan migrate --seed
    ```
4.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

## 👨‍💻 Author
**Farid Abdul Kholiq (Phigold Semesta)**
*Project ini dikembangkan sebagai Tugas Akhir D3 Di Universitas Bina Sarana Informatika Kabupaten Karawang dan portofolio persiapan S2 Artificial Intelligence di Universitas Gadjah Mada (UGM).*

---

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
