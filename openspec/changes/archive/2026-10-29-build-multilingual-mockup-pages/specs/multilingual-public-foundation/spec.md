# Spec Delta

## MODIFIED Requirements

### Requirement: Konten locale yang belum tersedia ditandai eksplisit
Sistem MUST merender versi verified atau concept yang tersedia pada locale aktif dengan statusnya; bila keduanya tidak tersedia, sistem MUST menampilkan empty state terlokalisasi tanpa menyisipkan bahasa lain.

#### Scenario: Isi locale tersedia
- **WHEN** sumber mempunyai versi verified atau concept untuk locale aktif
- **THEN** sistem merender versi tersebut dan menampilkan penanda konsep bila statusnya concept

#### Scenario: Isi locale belum tersedia
- **WHEN** sumber tidak mempunyai versi verified maupun concept untuk locale aktif
- **THEN** halaman menampilkan empty state terlokalisasi dan mempertahankan navigasi
- **AND** isi locale lain tidak dipakai sebagai fallback terselubung

## ADDED Requirements

### Requirement: Pengalaman konsep lengkap pada tiga bahasa
Empat halaman SHALL menyediakan narasi, label, deskripsi kategori, alt ilustrasi, status review dan kontrol demonstratif dalam ID/EN/AR dengan makna setara serta nama kategori sesuai sumber pengguna.

#### Scenario: Bahasa Inggris atau Arab dibaca
- **WHEN** pengunjung mengganti bahasa pada salah satu dari empat halaman
- **THEN** seluruh isi yang terlihat tersedia pada bahasa tujuan tanpa key mentah atau teks antarmuka Indonesia yang tertinggal
- **AND** nama resmi/logo dipertahankan dan terjemahan konsep tidak diklaim sebagai naskah resmi yayasan

#### Scenario: Konten Arab ditampilkan
- **WHEN** halaman Arab dirender pada desktop dan mobile
- **THEN** tata letak, navigasi dan formulir mengikuti RTL, angka/URL memakai arah yang benar serta logo dan foto tidak dicerminkan
