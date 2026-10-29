# Proposal

## Why

Hope Charity baru mempunyai mockup statis yang berisi banyak konten ilustratif dan belum mempunyai fondasi aplikasi, pemeriksaan runtime, maupun kontrak konten. Onboarding diperlukan sekarang agar pengembangan berikutnya dimulai dari proyek Laravel yang terisolasi, memakai baseline PHP yang telah ditambal, dan memiliki batas multilingual serta konten yang dapat diuji sebelum desain publik dibangun.

## What Changes

- Membuat fondasi proyek modular monolith Laravel 13 yang mandiri di `web_yayasan2`, dengan PHP `>=8.4.27 <9.0`, build Vite, konfigurasi testing lokal, dan pemeriksaan preflight keamanan.
- Menambahkan gate yang menolak PHP lama/prerelease, konfigurasi session upload progress yang tidak aman, serta environment yang tidak memenuhi ekstensi dan dependency wajib.
- Menyediakan routing dasar untuk Beranda, Profil, Program, dan Kontak pada Indonesia, Inggris, dan Arab, termasuk arah dokumen RTL dan perilaku eksplisit ketika konten locale belum tersedia.
- Menetapkan local dan preview sebagai `noindex`, tanpa mengaktifkan hosting, domain, indexing produksi, formulir, email, WhatsApp, atau integrasi eksternal.
- Menyediakan kontrak repository konten publik dan implementasi statis awal yang memuat identitas resmi serta delapan kategori program terverifikasi dalam tiga bahasa.
- Mempertahankan mockup sebagai referensi desain saja; konten ilustratif, angka, foto, donasi, visi-misi, hadis, dan klaim lain tidak masuk sebagai fakta aplikasi.
- Menambahkan test fondasi untuk preflight, isolasi environment, routing locale, RTL, noindex, ketiadaan fallback terselubung, dan kontrak sumber konten.
- Menunda implementasi visual penuh, CMS, database konten, autentikasi, deployment, dan integrasi produksi ke change terpisah.

## Capabilities

### New Capabilities

- `secure-application-foundation`: Bootstrap aplikasi, baseline PHP aman, preflight environment, isolasi proyek, build, testing, serta kebijakan noindex nonproduksi.
- `multilingual-public-foundation`: Routing empat permukaan publik pada ID/EN/AR, metadata locale dan arah dokumen, pergantian bahasa, serta status konten locale yang belum tersedia.
- `static-public-content`: Kontrak sumber konten publik dan implementasi statis untuk identitas yayasan serta delapan kategori program terverifikasi, tanpa menjadikan placeholder sebagai fakta.

### Modified Capabilities

Tidak ada. Proyek belum mempunyai capability OpenSpec existing.

## Impact

- **Kode**: scaffold Laravel, konfigurasi aplikasi/testing, route dan renderer publik dasar, kontrak serta implementasi repository statis, preflight, dan test fondasi.
- **Dependency**: Laravel 13, PHP `^8.4.27`, Composer, Node/Vite, serta ekstensi PHP minimum yang akan dikunci oleh preflight dan `composer.json`.
- **Data**: tidak ada database konten atau migrasi data; delapan program disimpan sebagai data statis terverifikasi.
- **Sistem eksternal**: tidak ada akses atau perubahan pada `web_yayasan`, `web_sekolah`, hosting, DNS, email, WhatsApp, dan produksi.
- **Sumber**: keputusan pengguna mengenai scope, logo, tiga bahasa, dan delapan program; mockup lokal hanya sebagai bukti kondisi awal dan referensi visual; advisory resmi PHP untuk baseline keamanan.
- **Gate berikutnya**: frontend visual memerlukan change tersendiri beserta skill `frontend-design` dan review screenshot; CMS memerlukan model database/editorial tersendiri; staging/produksi memerlukan verifikasi runtime hosting dan otorisasi eksplisit.
