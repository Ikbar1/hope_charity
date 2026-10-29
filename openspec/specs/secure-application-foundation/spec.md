# Secure Application Foundation Specification

## Purpose

Menetapkan fondasi aplikasi yang terisolasi dan hanya dapat dijalankan atau dipromosikan pada runtime PHP, dependency, serta konfigurasi keamanan yang telah diverifikasi.

## Requirements

### Requirement: Aplikasi mempunyai identitas proyek yang terisolasi
Fondasi aplikasi SHALL menggunakan konfigurasi, cache namespace, database, storage, environment, dan credential khusus Hope Charity tanpa membaca milik proyek lain.

#### Scenario: Aplikasi dibootstrap secara lokal
- **WHEN** environment Hope Charity disiapkan untuk development atau testing
- **THEN** seluruh nama aplikasi, database, cache, storage, dan test guard menunjuk proyek Hope Charity
- **AND** tidak ada koneksi runtime ke `web_yayasan`, `web_sekolah`, atau layanan produksinya

#### Scenario: Konfigurasi proyek lain terdeteksi
- **WHEN** preflight menemukan identifier database, cache, path storage, atau environment milik proyek lain
- **THEN** preflight gagal sebelum migrasi, test yang menulis data, atau server aplikasi dijalankan

### Requirement: Runtime memakai versi PHP yang telah ditambal
Preflight MUST menerima PHP stabil `>=8.4.27 <9.0` dan MUST menolak versi lebih lama, versi prerelease, atau versi yang tidak dapat diidentifikasi.

#### Scenario: PHP memenuhi baseline
- **WHEN** preflight dijalankan dengan PHP stabil 8.4.27 atau versi stabil lebih baru di bawah 9.0
- **THEN** pemeriksaan versi lulus dan laporan mencatat versi serta SAPI yang terdeteksi

#### Scenario: PHP lama atau prerelease digunakan
- **WHEN** runtime lebih rendah dari 8.4.27, termasuk 8.3.x, atau mempunyai penanda alpha, beta, RC, atau dev
- **THEN** preflight gagal dengan alasan versi tidak memenuhi baseline keamanan

### Requirement: Paket runtime platform diverifikasi
Preflight SHALL membuktikan build Windows atau provenance paket non-Windows memenuhi patch platform yang diwajibkan sebelum environment dinyatakan layak.

#### Scenario: Runtime Windows digunakan
- **WHEN** preflight berjalan pada Windows
- **THEN** build MUST teridentifikasi sebagai PHP 8.4.27-1 atau build lebih baru yang membawa dependency Brotli tertambal

#### Scenario: Runtime non-Windows digunakan
- **WHEN** preflight berjalan pada Linux atau platform non-Windows lain
- **THEN** laporan MUST mencatat sumber paket/vendor dan bukti patch yang dapat direview
- **AND** ketiadaan bukti membuat kompatibilitas staging atau produksi tetap belum terverifikasi

### Requirement: Session upload progress dinonaktifkan sebelum request
Environment web SHALL menerapkan `session.upload_progress.enabled=0` pada konfigurasi server yang berlaku sebelum parsing request, bukan melalui perubahan runtime aplikasi.

#### Scenario: Konfigurasi aman terdeteksi
- **WHEN** preflight membaca konfigurasi efektif CLI atau web SAPI
- **THEN** pemeriksaan lulus hanya jika session upload progress nonaktif

#### Scenario: Session upload progress masih aktif
- **WHEN** nilai efektif `session.upload_progress.enabled` aktif atau tidak dapat diverifikasi
- **THEN** preflight gagal dan environment tidak boleh dianggap siap

### Requirement: Dependency dan ekstensi wajib diperiksa
Fondasi SHALL menyediakan pemeriksaan platform yang menolak ekstensi wajib yang hilang, dependency Composer yang tidak terpenuhi, atau build frontend yang tidak lengkap.

#### Scenario: Platform lengkap
- **WHEN** pemeriksaan platform, dependency, test, dan build dijalankan pada environment yang sesuai
- **THEN** setiap pemeriksaan menghasilkan status lulus yang terpisah dan dapat ditelusuri

#### Scenario: Salah satu prasyarat gagal
- **WHEN** ekstensi wajib, requirement Composer, test, manifest Vite, atau aset build tidak tersedia
- **THEN** pemeriksaan keseluruhan gagal tanpa menyatakan environment siap

### Requirement: Laporan preflight tidak membocorkan rahasia
Preflight SHALL mencatat versi PHP, SAPI, sumber paket, ekstensi, dan nilai INI relevan tanpa menampilkan secret, credential, DSN lengkap, atau path privat yang tidak diperlukan.

#### Scenario: Laporan preflight dibuat
- **WHEN** operator menjalankan preflight berhasil maupun gagal
- **THEN** laporan mempunyai hasil per pemeriksaan dan nilai nonrahasia yang dibutuhkan untuk audit
- **AND** secret serta credential disamarkan atau dihilangkan

### Requirement: Environment nonproduksi tidak dapat diindeks
Local, testing, dan preview MUST mengirim kebijakan `noindex` serta robots yang menolak crawling; indexing produksi MUST memerlukan aktivasi eksplisit setelah gate release terpisah.

#### Scenario: Halaman dibuka pada local atau preview
- **WHEN** request publik dilayani pada environment nonproduksi
- **THEN** response dan `robots.txt` menolak indexing

#### Scenario: Flag indexing tidak diotorisasi
- **WHEN** environment production belum mempunyai aktivasi indexing yang disetujui
- **THEN** aplikasi tetap noindex dan tidak menerbitkan sitemap yang dapat diindeks
