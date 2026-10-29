# Multilingual Public Foundation Specification

## Purpose

Menyediakan kontrak navigasi publik tiga bahasa yang konsisten, dapat diakses, dan jujur terhadap ketersediaan konten sebelum desain visual lengkap diterapkan.

## Requirements

### Requirement: Empat halaman tersedia pada tiga locale
Sistem SHALL menyediakan Beranda, Profil, Program, dan Kontak pada Indonesia, Inggris, dan Arab dengan Indonesia di root serta prefix `/en` dan `/ar` untuk locale lain.

#### Scenario: Halaman Indonesia dibuka
- **WHEN** pengunjung membuka `/`, `/profil`, `/program`, atau `/kontak`
- **THEN** sistem merender halaman ekuivalen dengan locale `id`

#### Scenario: Halaman Inggris atau Arab dibuka
- **WHEN** pengunjung menambahkan prefix `/en` atau `/ar` pada salah satu route publik
- **THEN** sistem merender halaman ekuivalen dengan locale prefix tersebut

#### Scenario: Locale atau route tidak dikenal
- **WHEN** pengunjung meminta prefix locale atau halaman di luar daftar yang didukung
- **THEN** sistem mengembalikan respons 404 tanpa menebak locale lain

### Requirement: Dokumen menyatakan bahasa dan arah baca
Setiap response publik MUST menetapkan bahasa dokumen dan arah baca sesuai locale aktif, dengan Arab RTL serta Indonesia dan Inggris LTR.

#### Scenario: Locale Arab dirender
- **WHEN** pengunjung membuka halaman dengan prefix `/ar`
- **THEN** elemen dokumen mempunyai `lang="ar"` dan `dir="rtl"`
- **AND** urutan navigasi mengikuti arah baca tanpa mencerminkan logo atau media

#### Scenario: Locale Latin dirender
- **WHEN** pengunjung membuka locale Indonesia atau Inggris
- **THEN** elemen dokumen mempunyai `lang` yang sesuai dan `dir="ltr"`

### Requirement: Pergantian bahasa mempertahankan tujuan halaman
Pemilih bahasa SHALL menautkan setiap locale ke halaman ekuivalen, bukan selalu mengembalikan pengunjung ke Beranda.

#### Scenario: Pengunjung mengganti locale
- **WHEN** pengunjung berada di Profil, Program, atau Kontak lalu memilih bahasa lain
- **THEN** tujuan membuka jenis halaman yang sama pada locale pilihan

#### Scenario: JavaScript tidak tersedia
- **WHEN** JavaScript gagal atau dinonaktifkan
- **THEN** tautan locale dan navigasi halaman tetap dapat digunakan sebagai tautan HTML biasa

### Requirement: Konten locale yang belum tersedia ditandai eksplisit
Sistem MUST merender versi verified atau concept yang tersedia pada locale aktif dengan statusnya; bila keduanya tidak tersedia, sistem MUST menampilkan empty state terlokalisasi tanpa menyisipkan bahasa lain.

#### Scenario: Isi locale tersedia
- **WHEN** sumber mempunyai versi verified atau concept untuk locale aktif
- **THEN** sistem merender versi tersebut dan menampilkan penanda konsep bila statusnya concept

#### Scenario: Isi locale belum tersedia
- **WHEN** sumber tidak mempunyai versi verified maupun concept untuk locale aktif
- **THEN** halaman menampilkan empty state terlokalisasi dan mempertahankan navigasi
- **AND** isi locale lain tidak dipakai sebagai fallback terselubung

### Requirement: Metadata locale konsisten dengan halaman
Setiap halaman SHALL menghasilkan canonical dan alternatif bahasa yang menunjuk route ekuivalen serta tidak mengklaim konten yang belum tersedia sebagai terjemahan lengkap.

#### Scenario: Alternatif locale tersedia
- **WHEN** halaman ekuivalen dapat dilayani pada locale lain
- **THEN** metadata alternatif menunjuk URL kanonis locale tersebut

#### Scenario: Halaman hanya berisi status belum tersedia
- **WHEN** konten utama locale belum terverifikasi
- **THEN** metadata deskripsi tidak memakai klaim atau isi dari locale lain

### Requirement: Navigasi dasar dapat diakses
Shell dasar MUST mempunyai landmark semantik, skip link, fokus keyboard yang terlihat, dan kontrol navigasi yang dapat dijangkau tanpa bergantung pada pointer atau animasi.

#### Scenario: Pengunjung memakai keyboard
- **WHEN** fokus bergerak melalui header, pemilih bahasa, navigasi, dan isi
- **THEN** urutannya logis dan indikator fokus tetap terlihat pada LTR maupun RTL

#### Scenario: Pengunjung memilih reduced motion
- **WHEN** browser mengaktifkan `prefers-reduced-motion`
- **THEN** shell tidak menjalankan motion non-esensial dan seluruh konten tetap tersedia

### Requirement: Pengalaman konsep lengkap pada tiga bahasa
Empat halaman SHALL menyediakan narasi, label, deskripsi kategori, alt ilustrasi, status review dan kontrol demonstratif dalam ID/EN/AR dengan makna setara serta nama kategori sesuai sumber pengguna.

#### Scenario: Bahasa Inggris atau Arab dibaca
- **WHEN** pengunjung mengganti bahasa pada salah satu dari empat halaman
- **THEN** seluruh isi yang terlihat tersedia pada bahasa tujuan tanpa key mentah atau teks antarmuka Indonesia yang tertinggal
- **AND** nama resmi/logo dipertahankan dan terjemahan konsep tidak diklaim sebagai naskah resmi yayasan

#### Scenario: Konten Arab ditampilkan
- **WHEN** halaman Arab dirender pada desktop dan mobile
- **THEN** tata letak, navigasi dan formulir mengikuti RTL, angka/URL memakai arah yang benar serta logo dan foto tidak dicerminkan
