# 🏫 Skulary — Sistem Informasi Akademik (SIAKAD) SD, SMP, & SMA/SMK (Open Source)

**Skulary** adalah proyek **Open Source** untuk membangun **Sistem Informasi Akademik (SIAKAD)** terpadu yang ditujukan bagi sekolah dasar hingga menengah atas.  

Proyek ini dirancang agar mudah digunakan, mudah dikembangkan, dan dapat diimplementasikan oleh sekolah di seluruh Indonesia tanpa biaya lisensi.

---

## 🎯 Latar Belakang

Masih banyak sekolah di Indonesia — terutama jenjang SD hingga SMA/SMK — yang belum memiliki sistem akademik digital yang lengkap dan terintegrasi.  

Melalui **Skulary**, kami ingin menyediakan platform **gratis**, **terbuka**, dan **mudah diinstal** untuk membantu digitalisasi sekolah secara menyeluruh.

---

## 🌟 Fitur Utama (Roadmap Awal)

### 👥 Manajemen Pengguna
- Multi-role: **Admin**, **Guru**, **Siswa**, **Orang Tua**
- Sistem autentikasi modern (login, reset password, aktivasi)
- Dukungan **2FA (Two-Factor Authentication)**
- Setup awal pengguna (`fst_setup`)

### 🧍 Biodata & Alamat
- **Biodata** lengkap (nama, tempat/tanggal lahir, jenis kelamin, agama, golongan darah, tinggi, berat badan)
- **Alamat** fleksibel dengan jenis (`domisili`, `asal`, `orang_tua`)
- RT/RW, kelurahan, kecamatan, kota, provinsi, kode pos

### 🎓 Data Siswa
- NIS / NISN
- Tahun masuk & kelas aktif
- Status siswa (aktif, pindah, lulus)
- Relasi ke data orang tua & riwayat kelas

### 👨‍👩‍👧 Data Orang Tua / Wali
- Data ayah & ibu (nama, pekerjaan, pendidikan, penghasilan)
- Alamat & kontak darurat
- Relasi langsung ke siswa

### 👩‍🏫 Data Guru
- NIP / NUPTK
- Jabatan & status kepegawaian
- Mata pelajaran diampu
- Jadwal mengajar & rekap penilaian

### 📘 Akademik & Kurikulum
- Data kelas & rombongan belajar
- Jadwal pelajaran
- Nilai & rapor digital
- Absensi harian (guru & siswa)
- Tahun ajaran & semester dinamis

---

## 🧩 Tentang Proyek 

Detail Proyek ini bisa dilihat melalui url https://virtual.idev-fun.org/workspace/1b7f0133-0dd8-4a34-ac57-bc6943963080/X2HO4tu7FuABHxln3_3dR . Teknologi yang digunakan sebagai berikut:

| Komponen                    | Teknologi                              |
| --------------------------- | -------------------------------------- |
| **Backend**                 | Laravel 12                             |
| **Frontend**                | Blade + Tailwind CSS + Tailadmin       |
| **Database**                | MySQL / PostgreSQL                     |
| **Code Architecture**       | SRP + Datatables                       |

## 🚀 Installasi Proyek

### Clone repository
git clone https://github.com/mjaya69703/skulary.git
cd skulary

### Salin konfigurasi & install dependensi
cp .env.example .env
composer install
npm install && npm run build

### Generate app key & migrasi database
php artisan key:generate
php artisan migrate

### Jalankan server lokal
php artisan serve

## 🤝 Kontribusi

Kontribusi dari komunitas sangat kami harapkan!
Silakan ikuti langkah berikut:

1. Fork repository ini
2. Buat branch baru (feature/nama-fitur)
3. Lakukan commit dengan pesan yang jelas
4. Ajukan Pull Request

## 🛡️ Lisensi

Proyek ini dirilis di bawah [MIT License](https://github.com/mjaya69703/skulary/blob/latest/LICENSE) — bebas digunakan untuk keperluan pribadi maupun komersial, selama mencantumkan atribusi.

## 👨‍💻 Kredit
- [Laravel 12](https://laravel.com)
- [TailAdmin Dashboard](https://github.com/TailAdmin/tailadmin-free-tailwind-dashboard-template)