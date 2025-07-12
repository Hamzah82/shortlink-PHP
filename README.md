# 🔗 NYX Shortlink System

A personal and elegant shortlink system — designed for stealth, speed, and full control.

> "Operate silently. Redirect precisely."

---

## ✨ Features

- **Private Link Creation**: Shortlink creation is now exclusively managed through the secure admin panel. The public `index.php` serves as a restricted access page.
- Custom shortlinks with manual alias control
- Password protection for individual shortlinks
- Clean redirect page with classified-style message
- Automatic visit counter stored in `links.json`
- QR code generation for shortlinks
- Minimalistic interface (for admin + users)
- Self-hosted. No analytics. No tracking. 100% private.

---

## 📁 File Structure

```
/
├── .htaccess            # Apache configuration for URL rewriting
├── 403.html             # Custom 403 Forbidden page
├── admin.php            # **WARNING: This is a web shell (b374k shell). Use with extreme caution.**
├── index.php            # Restricted public landing page
├── LICENSE              # Project license
├── r.php                # Redirect handler
├── README.md            # This file
├── /admin/
│   ├── dashboard.php    # Shortlink management dashboard
│   └── index.php        # Admin login page
├── /assets/
│   ├── favicon.svg      # Favicon
│   ├── css/style.css    # Dark mode UI style
│   └── js/main.js       # JavaScript for QR code and copy functionality
└── /data/
    ├── .htaccess        # Protects data directory
    └── links.json       # Stores all shortlink data
```

---

## 🔐 Admin Access

This project has two distinct admin interfaces:

1.  **Shortlink Management Dashboard**:
    *   Visit `/admin/` (which redirects to `/admin/index.php` for login).
    *   Enter the hardcoded password (`ADMIN#1234` by default — **change it immediately!**).
    *   This panel allows you to create, edit, and delete shortlinks, set passwords, and view visit counts.

2.  **Server Control (Web Shell)**:
    *   Access `admin.php` directly.
    *   This is a powerful web shell (`b374k shell 3.2.3`) that provides extensive control over your server.
    *   **WARNING**: This tool is for advanced users and carries significant security risks if exposed. Ensure it is properly secured and only accessible to trusted individuals.

> 💡 Passwords are hashed in the PHP code. Make sure to change them before publishing.

---

## 📦 Deployment

This system is **fully static-compatible**, can be hosted on:
- [InfinityFree](https://infinityfree.net)
- GitHub Pages (if no PHP used)
- Any PHP-capable host

Just upload and go.

---

## ⚠️ Disclaimer

This project is designed for **personal use only**.
It does not implement advanced security features such as:
- Brute-force prevention (for the shortlink admin login)
- XSS sanitization (minimal only)

The inclusion of `admin.php` (web shell) significantly increases the security risk. Use responsibly and avoid exposing any admin panels or the web shell publicly.

---

## 🧠 Philosophy

> “Not every link needs to be tracked, monetized, or public.
> Sometimes, it just needs to go... exactly where you told it to.”

---

## 📄 License

MIT — do whatever you want, but keep it private if you want to stay underground.

---

## Tutorial Penggunaan NYX Shortlink System

NYX Shortlink System adalah sistem pemendek URL pribadi yang dirancang untuk kontrol penuh atas tautan Anda, dengan tambahan fungsionalitas web shell untuk manajemen server.

### 1. Prasyarat

Sebelum memulai, pastikan Anda memiliki hal-hal berikut:

*   **Server Web**: Apache, Nginx, atau server web lain yang mendukung PHP.
*   **PHP**: Versi PHP 7.4 atau lebih tinggi direkomendasikan.
*   **Akses FTP/SSH**: Untuk mengunggah file ke server Anda.

### 2. Instalasi / Deployment

1.  **Unduh Proyek**: Dapatkan semua file dari repositori proyek Anda.
2.  **Unggah ke Server**: Unggah semua file dan folder ke direktori root dokumen server web Anda (misalnya, `public_html` atau `www`).
3.  **Pastikan Izin File**: Pastikan direktori `data/` dan file `data/links.json` memiliki izin tulis yang benar (biasanya `0755` untuk direktori dan `0644` atau `0664` untuk file, tergantung konfigurasi server Anda). Ini penting agar sistem dapat menyimpan data shortlink.

### 3. Menggunakan Sistem Shortlink

Sistem shortlink sekarang sepenuhnya dikelola melalui panel admin. Halaman utama (`index.php`) berfungsi sebagai halaman akses terbatas.

#### 3.1. Mengakses Panel Admin Shortlink

1.  Buka browser Anda dan navigasikan ke `http://yourdomain.com/admin/` (ganti `yourdomain.com` dengan domain Anda).
2.  Anda akan diarahkan ke halaman login.
3.  Masukkan kata sandi admin. Secara default, ini adalah `ADMIN#1234`. **Sangat disarankan untuk segera mengubah kata sandi ini** langsung di kode sumber `admin/index.php` untuk keamanan. Kata sandi di-hash, jadi Anda perlu memperbarui nilai hash-nya.

#### 3.2. Fitur Dashboard Shortlink

Setelah login, Anda akan berada di `dashboard.php`.

*   **Membuat Tautan Baru**:
    1.  Di bagian "Create New Link", masukkan URL tujuan di kolom "Enter target URL".
    2.  Masukkan alias kustom yang Anda inginkan di kolom "Custom alias" (misalnya, `my-link`). Alias harus unik dan hanya berisi huruf, angka, garis bawah, atau tanda hubung.
    3.  (Opsional) Masukkan kata sandi di kolom "Optional password" jika Anda ingin tautan ini dilindungi.
    4.  Klik "Create Link".

*   **Mengedit Tautan yang Ada**:
    1.  Di bagian "Existing Links", temukan tautan yang ingin Anda edit.
    2.  Klik ikon pensil (Edit) di kolom "Actions".
    3.  Formulir "Edit Link" akan muncul di bagian atas. Ubah URL, alias, atau kata sandi sesuai kebutuhan.
    4.  Klik "Update Link".

*   **Menghapus Tautan**:
    1.  Di bagian "Existing Links", temukan tautan yang ingin Anda hapus.
    2.  Klik ikon tempat sampah (Delete) di kolom "Actions".
    3.  Konfirmasi penghapusan saat diminta.

*   **Melihat Jumlah Kunjungan**:
    *   Kolom "Visits" di tabel "Existing Links" akan menampilkan berapa kali shortlink tersebut telah dikunjungi.

*   **Membuat Kode QR**:
    1.  Di bagian "Existing Links", temukan tautan yang ingin Anda buat kode QR-nya.
    2.  Klik ikon kode QR di kolom "Actions".
    3.  Modal akan muncul menampilkan kode QR untuk shortlink tersebut. Anda dapat mengunduh gambar QR.

*   **Menyalin Shortlink**:
    1.  Di bagian "Existing Links", temukan tautan yang ingin Anda salin.
    2.  Klik ikon salin di kolom "Actions".
    3.  Shortlink akan disalin ke clipboard Anda.

#### 3.3. Cara Kerja Shortlink untuk Pengguna Akhir

Ketika pengguna mengakses shortlink Anda (misalnya, `http://yourdomain.com/my-link`), sistem akan:
1.  Memeriksa apakah shortlink tersebut dilindungi kata sandi.
2.  Jika dilindungi, pengguna akan diminta untuk memasukkan kata sandi.
3.  Setelah verifikasi (jika ada), pengguna akan diarahkan ke URL tujuan.
4.  Jumlah kunjungan untuk shortlink tersebut akan diperbarui.

### 4. Menggunakan Web Shell (`admin.php`)

**PERINGATAN PENTING**: `admin.php` adalah web shell yang sangat kuat (`b374k shell 3.2.3`). Ini memberikan akses langsung ke server Anda melalui antarmuka web. **Penggunaan yang tidak tepat atau paparan publik dapat menyebabkan kerentanan keamanan yang serius, termasuk pengambilalihan server Anda.** Gunakan dengan sangat hati-hati dan pastikan hanya orang yang sangat tepercaya yang memiliki akses.

1.  **Akses**: Buka browser Anda dan navigasikan langsung ke `http://yourdomain.com/admin.php`.
2.  **Login**: Anda akan diminta untuk memasukkan kata sandi. Kata sandi default diatur di dalam file `admin.php` itu sendiri (cari `$GLOBALS['pass']`). **Sangat disarankan untuk mengubah kata sandi ini segera.**
3.  **Fungsionalitas**: Setelah login, Anda akan melihat antarmuka web shell yang memungkinkan Anda untuk:
    *   Menjelajahi sistem file server.
    *   Menjalankan perintah shell.
    *   Mengedit, mengunggah, dan mengunduh file.
    *   Mengelola database.
    *   Dan banyak lagi.

**Penting**: Web shell ini terpisah dari panel admin shortlink. Perubahan yang Anda lakukan di sini memengaruhi server, bukan hanya shortlink Anda.

### 5. Pertimbangan Keamanan

*   **Ubah Kata Sandi Default**: Segera ubah kata sandi default untuk panel admin shortlink (`admin/index.php`) dan web shell (`admin.php`).
*   **Batasi Akses**: Jika memungkinkan, batasi akses ke direktori `/admin/` dan file `admin.php` hanya untuk alamat IP tepercaya menggunakan konfigurasi server web (misalnya, `.htaccess`).
*   **Jangan Ekspos Publik**: Hindari mengekspos web shell (`admin.php`) ke internet publik. Jika Anda tidak memerlukannya, pertimbangkan untuk menghapusnya atau memindahkannya ke lokasi yang sangat aman.
*   **Pahami Risiko**: Proyek ini dirancang untuk penggunaan pribadi dan tidak memiliki semua fitur keamanan tingkat produksi. Gunakan dengan bertanggung jawab.

---
