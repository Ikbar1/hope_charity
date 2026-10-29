# public-mockup-presentation Specification

## Purpose

Menyediakan empat halaman konsep Hope Charity yang setia pada mockup agar yayasan dapat menilai desain dan isi dalam presentasi remote tiga bahasa.

## Requirements

### Requirement: Beranda dan Profil mengikuti mockup
Website SHALL mempertahankan komposisi, urutan section, warna, tipografi, foto dan isi mockup Beranda/Profil dengan penyesuaian identitas, program, navigasi empat halaman, bahasa, aksesibilitas dan penghapusan donasi.

#### Scenario: Beranda ditinjau
- **WHEN** Beranda dibandingkan dengan mockups/charity-demo/index.html pada desktop dan mobile
- **THEN** hero lengkung, statistik contoh, kutipan, program unggulan, feature masjid dan pengantar tetap dikenali dalam urutan mockup
- **AND** section donasi dihapus tanpa meninggalkan ruang kosong

#### Scenario: Profil ditinjau
- **WHEN** Profil dibandingkan dengan mockups/charity-demo/profil.html
- **THEN** hero, cerita, visi–misi, nilai dan CTA program mengikuti komposisi mockup serta menggunakan identitas Hope Charity

### Requirement: Identitas resmi dan navigasi konsisten
Setiap halaman SHALL menampilkan logo resmi tanpa distorsi serta navigasi Beranda, Profil, Program, Kontak dan pilihan tiga bahasa.

#### Scenario: Pengunjung berpindah halaman
- **WHEN** pengunjung mengikuti logo, navigasi atau pilihan bahasa
- **THEN** tujuan tersedia, halaman aktif ditandai dan pergantian bahasa mempertahankan jenis halaman
- **AND** logo tidak dicerminkan pada RTL serta identitas Pembawa Kebaikan tidak tampil

### Requirement: Halaman Program menyajikan kategori resmi
Halaman Program SHALL mempunyai hero, pengantar konsep dan delapan kategori resmi berurutan dengan deskripsi konsep terlokalisasi; kategori MUST dibedakan dari publikasi kegiatan.

#### Scenario: Daftar program dibuka
- **WHEN** pengunjung membuka Program pada locale apapun
- **THEN** delapan nama dan stable key sesuai sumber pengguna ditampilkan tanpa menambahkan kategori Pendidikan Qur’an dari mockup
- **AND** tidak ada artikel, tanggal, lokasi kegiatan atau capaian program rekaan

### Requirement: Logo web mempunyai latar transparan
Website SHALL memakai turunan logo dengan alpha transparan yang menjaga identitas dan rasio logo resmi serta mempertahankan file sumber asli.

#### Scenario: Logo muncul pada latar halaman
- **WHEN** header atau footer dirender pada desktop, mobile atau RTL
- **THEN** logo menyatu dengan latar tanpa kotak putih, tanpa distorsi atau pencerminan

### Requirement: Footer bersama menyediakan navigasi dan ikon sosial
Setiap halaman SHALL mempunyai footer berisi logo, nama yayasan, navigasi empat halaman, copyright, tautan kembali ke atas dan ikon Instagram/Facebook/YouTube/TikTok yang aksesibel serta terlokalisasi.

#### Scenario: Footer dibaca pada perangkat dan bahasa berbeda
- **WHEN** footer dibuka pada ID/EN/AR dalam desktop atau mobile
- **THEN** susunan responsif mengikuti arah baca dan tautan navigasi menuju locale aktif tanpa overflow

#### Scenario: Akun resmi belum tersedia
- **WHEN** URL media sosial resmi belum diberikan
- **THEN** empat ikon tampil dengan nama aksesibel dan status akun sedang dipersiapkan
- **AND** tidak ada tautan akun rekaan atau integrasi sosial aktif

### Requirement: Halaman Kontak dapat direview tanpa layanan aktif
Halaman Kontak SHALL mengikuti gaya mockup dengan pengantar, slot alamat/email/telepon yang belum tersedia dan formulir demonstratif nonaktif.

#### Scenario: Informasi resmi belum tersedia
- **WHEN** halaman Kontak dibuka
- **THEN** field kontak menyatakan belum tersedia pada bahasa aktif tanpa nomor, alamat, peta atau tautan kontak rekaan

#### Scenario: Formulir demonstratif ditinjau
- **WHEN** pengunjung mencoba memakai formulir
- **THEN** kontrol tidak menerima atau mengirim data dan status demo dijelaskan di dekat formulir
- **AND** tidak ada request jaringan, email, penyimpanan atau pesan sukses pengiriman

### Requirement: Donasi dihapus dari pengalaman website
Website MUST tidak menyajikan tombol, section, modal, nominal, simulasi, route pembayaran atau skrip donasi.

#### Scenario: Permukaan publik diperiksa
- **WHEN** semua halaman pada tiga bahasa dan bundle publik diperiksa
- **THEN** tidak ada aksi donasi atau handler donasi dan CTA yang tersisa menuju halaman/section yang tersedia

### Requirement: Presentasi tetap dapat digunakan pada kondisi terbatas
Website SHALL tetap terbaca pada mobile, keyboard, tanpa JavaScript, reduced motion dan media/font gagal dimuat, termasuk Arab RTL.

#### Scenario: JavaScript tidak tersedia
- **WHEN** JavaScript dimatikan pada mobile
- **THEN** seluruh navigasi, bahasa dan konten tetap dapat diakses tanpa menu tersembunyi permanen

#### Scenario: Keyboard dan reduced motion digunakan
- **WHEN** pengunjung menavigasi dengan keyboard dan mengaktifkan reduced motion
- **THEN** skip link, fokus, menu dan urutan baca tetap logis tanpa motion non-esensial

#### Scenario: Foto atau font gagal
- **WHEN** aset gagal dimuat
- **THEN** layout mempertahankan ukuran yang layak, memakai font fallback dan menyajikan alternatif media tanpa klaim foto kegiatan nyata
