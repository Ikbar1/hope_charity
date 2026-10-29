# Design

## Context

Lihat proposal.md untuk motivasi dan keputusan pengguna. Saat ini satu Blade page merender daftar program atau empty state. InstitutionalPage hanya memuat title/body/status/provenance; program media null dan posting kosong. Test StaticContentBoundariesTest mengunci larangan materi mockup sehingga perlu direvisi secara sadar. Mockup HTML/CSS/JS menyediakan dua halaman serta lima foto konsep AI. Routing locale, noindex dan preflight sudah tersedia.

## Goals / Non-Goals

Goals: memindahkan desain mockup ke komponen Blade dengan data konsep terstruktur, konsistensi empat halaman dan pengujian kesetiaan visual. Non-goals: page builder, perubahan model CMS, akun, database, layanan kontak aktif atau deployment pada apply ini.

## Decisions

### Follow-up logo dan footer

Permintaan pengguna setelah implementasi awal menambahkan PNG transparan sebagai turunan sumber asli serta footer tiga kolom desktop/bertumpuk mobile. Logo asli dipertahankan, turunan alpha digunakan pada header/footer; footer memakai sand, hijau tua dan garis emas. SVG sosial lokal berlabel mewakili Instagram, Facebook, YouTube dan TikTok. URL resmi belum tersedia sehingga ikon berupa elemen noninteraktif dengan status persiapan. Screenshot ID/AR desktop/mobile dan suite 83 test tetap lulus; rinciannya di docs/verification/mockup-pages/review.md.

### Kesetiaan mockup sebagai batas desain

Gunakan frontend-design saat apply untuk mencatat arah visual dan mengkritik hasil terhadap brief. Palet dasar #F6F1E8 cream, #EEE5D6 sand, #103E32 green, #AC7A3C gold, #181A16 ink dan #696B65 muted mempertahankan mockup; warna hijau/emas logo berada di aset resmi. Judul Latin Playfair Display, body DM Sans, Arab Noto Naskh Arabic dengan font lokal berlisensi. Body 15–16px, judul desktop 52–86px dan mobile mengikuti clamp mockup; Arab diberi line-height serta ukuran optik tersendiri. Alignment mengikuti start locale, paragraf tidak dijustifikasi.

Hero lengkung masjid adalah elemen utama. Pertahankan proporsi, crop, section dan ritme mockup. Alternatif desain minimal onboarding ditolak karena pengguna meminta tampilan sama. Penyesuaian hanya identitas, program, penghapusan donasi, navigasi, multilingual, responsive dan aksesibilitas. Jangan menggambar ulang logo; simpan sumber asli dan buat ukuran web tanpa distorsi melalui tooling aset yang sesuai. Bila file lampiran sudah hilang saat apply, minta unggah ulang sebelum finalisasi logo.

### Empat template halaman dengan shell bersama

Pisahkan layout, navigasi/bahasa, footer, hero dan komponen program; masing-masing halaman mempunyai template sendiri. Home mempertahankan statistik/kutipan/feature dan memperbarui unggulan ke kategori resmi (masjid, fasilitas, bantuan sosial); katalog masjid menjadi section fitur, bukan menu tambahan. Profil mempertahankan struktur mockup. Program menampilkan delapan kategori sebagai kartu/section dengan ringkasan konsep; foto yang tidak sesuai kategori tidak dipaksakan. Kontak memakai hero, slot alamat/email/telepon dan form demonstratif disabled dengan penjelasan terlihat. Tidak ada endpoint submit atau tautan mailto/tel/WhatsApp/peta contoh.

Semua CTA donasi, section, dialog dan handler dihapus, termasuk fragment yatim. CTA non-donasi menuju route/section valid. Menu mobile menggunakan progressive enhancement: link tetap terlihat/terjangkau tanpa JS, aria-expanded dan fokus benar saat JS aktif. Motion mengikuti mockup secara terbatas dan reduced motion mematikannya.

### Sumber konten dengan status eksplisit

Perluas read model dengan status editorial verified/concept terpisah dari availability, provenance per blok/media serta terjemahan. Pertahankan PublicContentSource dan PublicPageReader sebagai batas konsumsi; Blade tidak menyimpan narasi panjang. Validasi nama/key/urutan resmi tetap ketat, sedangkan isi konsep diizinkan hanya dengan sumber dan status. Label review pada shell dan konteks statistik/foto menjelaskan bahwa isi belum resmi tanpa menutupi komposisi.

Materi concept dirender hanya pada allowlist local/testing/preview; environment lain menampilkan konten verified atau status belum tersedia. APP_ENV=production saja tidak mengotorisasi konsep. Ini mencegah rilis resmi membawa materi review tanpa sengaja. Alternatif mencampur teks mockup dengan fakta verified ditolak karena sulit diganti dan mudah disalahartikan.

CMS kelak mengganti implementasi sumber posting melalui kontrak yang sama; programPosts tetap kosong sekarang. Layout, profil, kontak dan navigasi tetap dimiliki kode. Tidak ada migration database.

### Terjemahan, media dan metadata

ID menjadi referensi isi konsep; EN/AR ditulis dengan makna setara, nama program memakai terjemahan pengguna. Jangan membuat kutipan agama tambahan; bagian kutipan dari mockup tetap diberi asal dan status konsep, terjemahannya memerlukan review editorial sebelum rilis resmi. Tidak perlu mengklaim autentikasi hadis pada fase ini. Validator/test memeriksa kelengkapan terjemahan tanpa fallback Laravel terselubung; bila blok tidak ada, gunakan status lokal eksplisit.

Copy lima aset mockup ke aset publik yang diperlukan dengan catatan asal ilustrasi AI dan batas penggunaan review. Font self-hosted beserta lisensi menghindari dependency Google Fonts saat runtime. Gunakan ukuran intrinsik, lazy loading selain hero, alt ilustrasi terlokalisasi dan fallback tanpa broken-image. Aset asli mockup tetap menjadi baseline pembanding.

Routing root ID serta /en dan /ar dipertahankan. Canonical menggunakan origin environment, metadata menyatakan konsep; hreflang hanya untuk locale tersedia. Middleware noindex dan robots tetap menolak indexing. Noindex bukan pembatas akses; perlindungan URL review diputuskan pada change deployment.

### Verifikasi dan runtime

Bandingkan screenshot mockup dan hasil pada viewport sama, minimal 1440px dan 390px untuk Home/Profil ID; catat setiap penyimpangan yang diotorisasi. Review seluruh 12 halaman desktop/mobile (24 capture), ditambah no-JS, reduced motion, keyboard dan media gagal pada LTR/RTL. Periksa crop, panjang teks, kontras dan overflow. Test fitur fokus pada kontrak konsep, locale, noindex, delapan kategori dan nihil request kontak/donasi.

Baseline PHP tetap ^8.4.27, Windows 8.4.27-1 atau lebih baru; preflight menolak runtime lama/prerelease, ekstensi hilang dan session upload progress aktif. Jalankan Composer platform checks, dependency audit, test, Pint dan build. Hosting Rumahweb adalah pilihan pengguna, tetapi bukti runtime CLI/web SAPI Hope Charity dan patch vendor diambil saat deployment terpisah.

## Risks / Trade-offs

- [Materi konsep disangka fakta] → label tiga bahasa, provenance, pembatasan environment dan statistik berlabel contoh.
- [Foto AI terlalu kecil atau tidak cocok kategori] → pertahankan ilustrasi relevan, review crop dan gunakan layout tanpa foto untuk kategori lain; jangan mengarang dokumentasi.
- [Logo penuh terlalu kecil di header] → turunan ukuran web dengan rasio asli dan pengujian keterbacaan mobile; tidak mengubah identitas.
- [Terjemahan mengubah tinggi layout] → ukuran/line-height khusus Arab dan screenshot seluruh locale.
- [Larangan lama bertentangan dengan keputusan terbaru] → delta mengganti requirement terkait dan task menyelaraskan context/docs; arsip onboarding dipertahankan sebagai riwayat.

## Migration Plan

Kerjakan pada development. Selaraskan context/docs dengan izin konsep terbaru, tambahkan aset dan read model, lalu shell serta empat halaman. Jalankan acceptance lokal sebelum sync/archive. Tidak ada upload hosting pada change ini. Publikasi Rumahweb memerlukan change release tersendiri dengan URL, environment preview, app root privat/public root, pemeriksaan runtime, paket source/build sepasang dan rollback. Rollback lokal mengembalikan kode/aset commit sebelumnya serta menyegarkan cache; tidak memerlukan pemulihan database.
