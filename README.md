# Sistem Manajemen Pelaporan dan Perbaikan Fasilitas Kampus

## Informasi Singkat

**Sistem Manajemen Pelaporan dan Perbaikan Fasilitas Kampus** adalah platform berbasis web yang dirancang untuk menyederhanakan proses pelaporan, pengelolaan, dan perbaikan fasilitas kampus di **Jurusan Teknologi Informasi**, dan akan dikembangkan ke seluruh **Politeknik Negeri Malang (Polinema)**. Sistem ini mengatasi ketidakefisienan proses pelaporan manual dengan menyediakan solusi digital yang memungkinkan pelaporan secara real-time, pelacakan transparan, dan pengelolaan perbaikan berbasis prioritas untuk fasilitas akademik seperti ruang kelas, laboratorium komputer, peralatan IT, dan infrastruktur jaringan.

Proyek ini dikembangkan sebagai bagian dari **Project Based Learning Semester 4 Program D4 Teknik Informatika**, dengan tujuan meningkatkan kualitas layanan fasilitas, mendukung kegiatan akademik, dan menciptakan lingkungan kampus yang terkelola dengan baik.

## Tujuan Proyek

Sistem ini bertujuan untuk:
1. Memudahkan mahasiswa, dosen, dan staf dalam melaporkan kerusakan fasilitas.
2. Membantu tim pengelola fasilitas dalam melacak, memprioritaskan, dan menangani permintaan perbaikan.
3. Mengotomatisasi dokumentasi dan menyediakan basis data terpusat untuk catatan perbaikan.
4. Menerapkan Sistem Pendukung Keputusan (DSS) untuk merekomendasikan perbaikan mendesak berdasarkan prioritas dan dampak.

## Fitur

### Admin
- **Manajemen Pengguna**: Menambah, mengedit, atau menghapus akun pengguna (mahasiswa, dosen, tendik, admin).
- **Manajemen Data Fasilitas**: Mengelola daftar fasilitas kampus seperti laboratorium, AC, proyektor, PC, dan jaringan.
- **Manajemen Data Gedung**: Mengelola informasi gedung tempat fasilitas berada, seperti Gedung Teknik Sipil atau Parkir Utama.
- **Pengelolaan Laporan Kerusakan**: Melihat dan memantau status laporan kerusakan.
- **Manajemen Prioritas Perbaikan**: Mengatur data prioritas perbaikan fasilitas.
- **Laporan dan Statistik**: Menghasilkan laporan periodik tentang status perbaikan dan analisis tren kerusakan.
- **Manajemen Periode**: Mengelola daftar periode pelaporan tahunan.

### Mahasiswa, Dosen, Tendik
- **Manajemen Akun & Profil**: Masuk dan memperbarui profil pengguna.
- **Pelaporan Kerusakan**: Melaporkan kerusakan fasilitas dengan formulir yang mencakup deskripsi, foto, dan fasilitas yang rusak.
- **Pemantauan Status Laporan**: Melacak status laporan (misalnya, diterima, sedang diperbaiki, selesai) dan menerima notifikasi.
- **Umpan Balik**: Memberikan rating dan umpan balik tentang kepuasan terhadap hasil perbaikan.

### Sarana dan Prasarana
- **Penentuan Prioritas**: Mengelola masukan tentang prioritas perbaikan berdasarkan dampak akademik.
- **Pengelolaan Laporan**: Menerima, memverifikasi, dan mengkategorikan laporan kerusakan.
- **Rekomendasi Perbaikan**: Menentukan prioritas perbaikan berdasarkan rekomendasi sistem yang dihasilkan oleh DSS (Decision Support System).
- **Penugasan Teknisi**: Mengalokasikan tugas perbaikan kepada teknisi dan memantau progres.
- **Analisis Statistik**: Mengelola statistik kerusakan dan kepuasan pengguna untuk perencanaan jangka panjang.

### Teknisi
- **Laporan Perbaikan**: Menerima dan mengelola tugas perbaikan fasilitas.
- **Riwayat Perbaikan**: Melihat riwayat laporan perbaikan yang telah dilakukan.

## Teknologi yang Digunakan

- **Bahasa Pemrograman**: PHP Versi 8
- **Framework**: Laravel 10
- **UI Framework & Library**: Bootstrap 4/5, DaisyUI 4.12.10, Tailwind CSS, Flowbite 3.1.2, Toastify-JS, Bootstrap Icons 1.11.3
- **Basis Data**: MySQL
- **Arsitektur**: Monolith (frontend, backend, dan database dalam satu aplikasi)

## Prasyarat

Untuk menjalankan proyek ini secara lokal, pastikan Anda memiliki:
- PHP >= 8.0
- Composer
- MySQL/MariaDB
- Node.js dan NPM (untuk mengelola aset frontend)
- Server web (misalnya, Apache atau Nginx)

## Petunjuk Program

1. **Klon Repositori**
   ```bash
   git clone https://github.com/jioooo20/simpelfas.git
   cd simpelfas
   ```

2. **Instal Dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   - Salin file `.env.example` menjadi `.env`:
     ```bash
     cp .env.example .env
     ```
   - Sesuaikan pengaturan database di file `.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=simpelfas
     DB_USERNAME=nama_pengguna
     DB_PASSWORD=kata_sandi
     ```

4. **Generate Kunci Aplikasi**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database**
   ```bash
   php artisan migrate
   ```

6. **Kompilasi Aset Frontend dan Backend Bersamaan**
   ```bash
   npm run app
   ```
   Akses aplikasi di `http://localhost:8000`.

## Struktur Direktori

```plaintext
├── app/                # Logika aplikasi (model, controller, dll.)
├── config/             # File konfigurasi
├── database/           # Migrasi dan seeder database
├── public/             # Aset publik (CSS, JS, gambar)
├── resources/          # View, aset frontend, dan sumber daya lainnya
├── routes/             # Definisi rute aplikasi
├── tests/              # Tes otomatis
├── .env                # Konfigurasi Environment
└── README.md           # Dokumentasi proyek
```


