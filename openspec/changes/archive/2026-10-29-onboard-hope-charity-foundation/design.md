# Design

## Context

Lihat `proposal.md` untuk motivasi. Repository target saat ini belum merupakan repository Git atau aplikasi Laravel; hanya ada OpenSpec dan mockup HTML/CSS/JavaScript dengan aset serta isi ilustratif. PHP, Composer, dan OpenSpec CLI tidak tersedia global pada PATH saat eksplorasi, sedangkan Node tersedia. Tiga delta spec pada change ini menjadi kontrak perilaku fondasi.

## Goals / Non-Goals

**Goals:**

- Membuat fondasi Laravel yang dapat direproduksi tanpa menimpa `openspec/`, `.agents/`, atau `mockups/`.
- Mengunci runtime PHP dan dependency dengan bukti provenance yang dapat diperiksa.
- Menyediakan batas modul publik, locale, serta sumber konten yang stabil untuk change frontend dan CMS berikutnya.
- Memberi test dan preflight yang gagal tertutup ketika environment atau data tidak memenuhi kontrak.

**Non-Goals:**

- Mengimplementasikan komposisi visual final dari mockup, optimasi logo/foto, atau screenshot approval final.
- Menyediakan CMS, database konten, login, akun staf/admin, editor, upload, atau workflow publikasi.
- Menyediakan donasi, formulir kontak aktif, email, WhatsApp, katalog, laporan, atau integrasi eksternal.
- Memeriksa atau mengubah hosting, DNS, domain, web SAPI produksi, maupun indexing produksi.

## Decisions

### 1. Scaffold Laravel digabung secara terkontrol ke root existing

Laravel 13 dibuat terlebih dahulu pada direktori staging di `.local/scaffold`, lalu file fondasinya digabung ke root dengan daftar target eksplisit. `openspec/`, `.agents/`, dan `mockups/` tidak menjadi target penggantian. Setelah struktur dan ignore rules diverifikasi, root diinisialisasi sebagai Git repository pada branch `main`; tidak ada remote, push, atau deploy dalam change ini.

Alternatif membuat Laravel langsung pada root ditolak karena root tidak kosong dan berisiko menimpa bahan perencanaan. Menyalin seluruh `web_yayasan` atau `web_sekolah` ditolak karena membawa identitas, kompleksitas, serta konfigurasi proyek lain.

### 2. Runtime lokal portable dan provenance menjadi prasyarat scaffold

Runtime Windows disimpan di `.local/runtime/php` dan Composer di `.local/runtime/composer`; keduanya diabaikan Git. Akuisisi hanya dari distribusi resmi, dengan versi minimum PHP 8.4.27-1 dan checksum paket yang diverifikasi. Manifest lokal mencatat URL sumber, nama paket, hash biner/arsip, versi, build, timestamp verifikasi, dan sumber bukti tanpa credential.

Wrapper PowerShell di `tools/local` menjalankan PHP/Composer melalui path eksplisit, sehingga PATH global tidak menjadi sumber kebenaran. Template INI proyek mematikan `session.upload_progress.enabled`, `display_errors` untuk mode non-development yang relevan, dan membatasi request/upload secara konservatif. Runtime non-Windows tidak dianggap terbukti hanya dari `PHP_VERSION`; preflight membutuhkan metadata paket vendor yang dapat direview.

Alternatif menerima PHP 8.3.36 ditolak oleh baseline proyek. Alternatif memakai versi panel cPanel atau `php -v` saja ditolak karena tidak membuktikan web SAPI, revision build Windows, atau dependency Brotli.

### 3. Preflight mempunyai core murni dan adapter environment

Pemeriksaan dibagi menjadi evaluator yang dapat diuji memakai fixture dan adapter yang membaca runtime aktual. Evaluator memeriksa:

- PHP stabil `>=8.4.27 <9.0` dan tidak prerelease;
- revision/provenance paket sesuai platform;
- `session.upload_progress.enabled=0`;
- ekstensi minimum: ctype, curl, dom/xml, exif, fileinfo, filter, gd, hash, intl, mbstring, openssl, pcre, PDO, session, dan tokenizer;
- identifier proyek, database/cache/storage, requirement Composer, hasil test, dan manifest Vite.

Output JSON dan ringkasan manusia memisahkan `pass`, `fail`, dan `unverified`. Secret, DSN lengkap, token, serta path privat tidak dicetak. Pemeriksaan aktual CLI tidak dianggap bukti web SAPI hosting; status hosting tetap `unverified` sampai change release terpisah mengukur keduanya.

### 4. Modular monolith dengan batas permukaan publik yang kecil

Satu aplikasi Laravel server-rendered memakai Blade, Vite, CSS custom, dan JavaScript progresif seperlunya. Route publik dipisahkan dari health/preflight; belum ada route auth/admin/content mutation.

```text
browser
  -> locale route + indexing middleware
  -> public page controller/read model
  -> PublicContentSource contract
       -> StaticPublicContentSource (onboarding)
       -> DatabasePublicContentSource (change CMS mendatang)
```

Controller hanya menerima read model, bukan array konfigurasi mentah. Provider aplikasi mengikat kontrak ke implementasi statis. Tidak ada request runtime ke proyek lain atau layanan eksternal.

### 5. Routing memakai slug ASCII stabil dengan prefix locale

Indonesia memakai `/`, `/profil`, `/program`, dan `/kontak`; Inggris dan Arab menambahkan `/en` atau `/ar` di depan slug yang sama. Nama route menyatakan page key dan locale sehingga pemilih bahasa dapat membangun URL halaman ekuivalen tanpa parsing URL.

Middleware locale menetapkan locale aplikasi, `lang`, dan `dir`; Arab memakai RTL. Locale tidak dikenal menghasilkan 404. Canonical dan hreflang dibangun dari route map yang sama. Konten locale yang belum tersedia menghasilkan read model `unavailable` dengan pesan lokal minimal, bukan fallback ke bahasa lain.

Alternatif slug terjemahan ditunda karena belum ada persetujuan editorial dan akan memperbesar mapping permalink sebelum konten tersedia.

### 6. Shell onboarding aksesibel tetapi visualnya sengaja minimal

Blade layout menyediakan skip link, header, navigasi empat halaman, pemilih bahasa, main landmark, empty state, dan footer minimal. Navigasi harus tetap bekerja tanpa JavaScript, mempunyai fokus jelas, dan menghormati reduced motion. CSS fondasi hanya menangani keterbacaan, LTR/RTL, responsive safety, dan state fokus; identitas visual final mengikuti change frontend terpisah dengan skill `frontend-design`.

Logo/foto mockup tidak diproses dalam onboarding. Kegagalan atau ketiadaan media menghasilkan layout tanpa gambar, bukan broken image. Aset ilustratif mockup tetap berada di folder referensi dan tidak dipindahkan ke aset publik aplikasi.

### 7. Konten statis memakai struktur bersama dan teks locale terpisah

Satu katalog statis menyimpan stable key, urutan, dan teks ID/EN/AR untuk delapan kategori. Validator boot/test menolak key atau urutan duplikat, locale wajib yang kosong, dan field faktual tanpa status sumber. Halaman kelembagaan memakai status `available` atau `unavailable`; hanya nama resmi yayasan dan daftar program yang berstatus terverifikasi.

Kontrak sumber menyediakan operasi untuk membaca halaman kelembagaan, katalog kategori, dan koleksi posting per kategori. Koleksi posting selalu kosong pada onboarding. Implementasi database masa depan wajib mengembalikan read model yang sama sehingga renderer tidak bergantung pada tabel, Eloquent, atau bentuk editor.

Alternatif meletakkan teks langsung di Blade ditolak karena mengikat content lifecycle ke template. JSON publik ditolak sebagai sumber runtime karena tidak memberi boundary aplikasi atau validasi tipe yang memadai.

### 8. Database konten tidak diaktifkan pada onboarding

Session, cache, dan queue development memakai driver non-database yang sesuai. Test boleh memakai SQLite in-memory hanya untuk kebutuhan framework/test guard, bukan untuk menyimpan konten publik. Tidak ada migration domain, model pengguna, seeder, atau akun default.

### 9. Noindex gagal tertutup

Middleware menambahkan `X-Robots-Tag: noindex, nofollow` pada local, testing, dan preview. `robots.txt` menolak semua crawler selama indexing belum diaktifkan oleh flag produksi eksplisit; sitemap tidak diterbitkan. Nilai production saja tidak cukup untuk mengaktifkan indexing.

## Risks / Trade-offs

- **[Runtime portable membesar dan cepat usang]** → Biner berada di `.local`, tidak masuk Git, serta selalu diverifikasi ulang terhadap baseline sebelum test/apply.
- **[Build revision Windows tidak tampak pada `PHP_VERSION`]** → Preflight mencocokkan manifest provenance dan hash paket/biner, bukan hanya output versi.
- **[CLI aman tetapi web SAPI hosting berbeda]** → Onboarding hanya membuktikan local; staging/produksi tetap unverified sampai preflight web SAPI pada change release.
- **[Shell minimal dinilai sebagai desain final]** → UI menampilkan penanda preview dan dokumentasi menyatakan bahwa visual final berada di change frontend terpisah.
- **[Kontrak konten terlalu dini membatasi CMS]** → Kontrak hanya memuat kebutuhan publik stabil; metadata editorial, revision, dan permission tetap internal pada implementasi CMS mendatang.
- **[Empty state tiga bahasa dapat dianggap terjemahan lengkap]** → Read model dan metadata membedakan `available` dari `unavailable`; hreflang tidak mengklaim teks yang tidak ada.

## Migration Plan

1. Verifikasi atau siapkan runtime portable resmi serta manifest provenance di `.local`.
2. Buat scaffold Laravel di `.local/scaffold`, verifikasi versi/dependency, lalu gabungkan file target ke root tanpa menyentuh artefak existing.
3. Kunci konfigurasi, ignore rules, wrapper runtime, preflight, serta test guard sebelum menjalankan migration atau server.
4. Tambahkan modul konten statis, route locale, shell minimal, noindex, dan test capability.
5. Jalankan preflight, Composer platform check, PHP test, JavaScript test bila ada, build Vite, dan browser smoke lokal.
6. Inisialisasi Git `main` setelah daftar file terverifikasi; jangan menambahkan `.local`, secret, runtime binary, atau remote.

Rollback dilakukan melalui Git setelah baseline terinisialisasi. Sebelum commit baseline tersedia, setiap langkah scaffold memakai daftar target eksplisit dan salinan staging tetap di `.local`; kegagalan menghentikan merge tanpa menghapus `openspec/`, `.agents/`, atau `mockups/`. Tidak ada rollback database, hosting, atau produksi karena area tersebut tidak disentuh.
