# Tasks

## 1. Baseline visual dan aset

- [x] 1.1 Pada development, catat keputusan konsep terbaru pada context OpenSpec dan dokumentasi frontend/README; verifikasi konsisten dengan proposal dan tidak mengubah arsip onboarding atau baseline PHP.
  - Selesai (2026-10-29): context dan README mencatat izin konsep; arah visual tercatat di docs/frontend/mockup-direction.md. Branch development diperiksa, baseline PHP dan arsip tidak diubah.
- [x] 1.2 Gunakan frontend-design untuk memperbarui arah desain sesuai mockup dan ambil screenshot baseline Home/Profil pada 1440px serta 390px; verifikasi palet, font, section, alignment dan daftar pengecualian yang disepakati terdokumentasi.
  - Selesai (2026-10-29): Baseline Home/Profil pada 1440 dan 390px tersimpan di docs/verification/mockup-pages; arah visual dan pengecualian tercatat di docs/frontend/mockup-direction.md.
- [x] 1.3 Masukkan logo resmi asli dan turunan web yang mempertahankan identitas, aset ilustrasi mockup serta font self-hosted berlisensi; verifikasi provenance, ukuran, rasio, lisensi dan pemuatan lokal tanpa request font eksternal.
  - Selesai (2026-10-29): Logo sumber disalin identik (SHA256 cocok); presentasi web responsif 98/75px mempertahankan rasio asli tanpa crop. Lima ilustrasi dan lima font lokal beserta tiga lisensi tersedia, browser memuatnya lokal.

## 2. Konten konsep tiga bahasa

- [x] 2.1 Perluas read model, validator dan sumber statis dengan status editorial/provenance per blok/media serta pembatasan concept ke local/testing/preview; perbarui boundary test untuk penerimaan konsep sah, penolakan sumber hilang dan tidak tampilnya konsep pada environment resmi.
  - Selesai (2026-10-29): ConceptContent dan read model memisahkan status editorial/availability; ConceptPreviewTest memeriksa struktur, provenance, penolakan konsep tidak sah dan blok konsep pada production. Test lulus.
- [x] 2.2 Isi narasi mockup, statistik, kutipan, profil, deskripsi delapan kategori dan Kontak dalam ID/EN/AR; verifikasi kesetaraan editorial, nama/key/urutan resmi, kelengkapan locale, empty state tanpa fallback serta koleksi posting tetap kosong melalui pengujian sumber konten.
  - Selesai (2026-10-29): Tiga JSON locale lengkap; test memastikan delapan deskripsi, kategori resmi dan posting kosong. Screenshot ID/EN/AR memperlihatkan isi tanpa fallback.
- [x] 2.3 Dokumentasikan pemetaan konten/aset verified versus concept dan cara menggantinya; verifikasi renderer membaca kontrak sumber, tidak menyimpan narasi panjang dalam Blade, dan konsep tidak naik status karena diterjemahkan.
  - Selesai (2026-10-29): docs/frontend/concept-content.md mendokumentasikan sumber, status turunan, batas renderer/CMS dan revisi copy; data dibaca dari PublicContentSource, bukan narasi Blade.

## 3. Shell serta Beranda dan Profil

- [x] 3.1 Implementasikan header/logo, navigasi empat halaman, bahasa, footer dan label konsep bersama dengan menu mobile progressive enhancement; verifikasi keyboard, aria-current/expanded, link ekuivalen, RTL dan akses menu tanpa JS pada desktop/mobile.
  - Selesai (2026-10-29): Shell bersama dengan menu progressive enhancement, logo, ribbon serta locale links diterapkan; screenshot 24 kombinasi dan mode no-JS/keyboard lulus.
- [x] 3.2 Terapkan Beranda dari mockup dengan hero, statistik contoh, kutipan, kategori unggulan resmi, feature dan pengantar; hapus seluruh section/tombol/modal/handler donasi, verifikasi perbandingan screenshot baseline dan seluruh CTA menuju tujuan valid.
  - Selesai (2026-10-29): Home mengikuti komposisi mockup dan kategori resmi. Audit resources/app/routes tidak menemukan handler/fragment donasi. Screenshot baseline dan hasil direview; tautan internal lulus.
- [x] 3.3 Terapkan Profil dari mockup dengan cerita, visi–misi, nilai dan CTA Program; verifikasi screenshot ID/EN/AR desktop/mobile termasuk label konsep dan keterbacaan logo.
  - Selesai (2026-10-29): Profil memuat hero, cerita, visi-misi, nilai dan CTA program; enam kombinasi screenshot serta feature tests lulus. Perbaikan logo dan tinggi hero tercatat dalam review.md.
- [x] 3.4 Tambahkan feature test untuk isi kedua halaman, label konsep, noindex/canonical dan ketiadaan donasi; catat hasil serta penyimpangan visual yang diotorisasi pada laporan review.
  - Selesai (2026-10-29): Feature tests metadata, landmark, concept dan no-donation lulus. Penyimpangan sesuai brief dicatat dalam docs/verification/mockup-pages/review.md.

## 4. Program dan Kontak

- [x] 4.1 Buat halaman Program dengan hero/pengantar serta delapan kategori berurutan, ringkasan konsep dan ilustrasi relevan; verifikasi ketiga locale, tidak adanya kategori tambahan/posting rekaan dan tampilan layak tanpa foto.
  - Selesai (2026-10-29): Program menampilkan delapan kartu stable key/nama resmi, deskripsi konsep dan publikasi kosong. Screenshot dan test kategori ID/EN/AR lulus.
- [x] 4.2 Buat Kontak dengan hero, slot informasi resmi belum tersedia dan form disabled beserta status demonstratif; verifikasi tidak ada nomor/alamat/peta rekaan, endpoint submit, request jaringan, penyimpanan atau pesan sukses pengiriman.
  - Selesai (2026-10-29): Kontak memakai fieldset disabled tanpa form/endpoint; tidak ada alamat/nomor rekaan. Test POST ditolak 405 dan browser tidak menemukan kontrol aktif.
- [x] 4.3 Tambahkan feature test untuk kategori, status Kontak, locale dan metadata kedua halaman; simpan screenshot desktop/mobile ID/EN/AR serta catatan review konsistensi dengan Home/Profil.
  - Selesai (2026-10-29): Feature test kategori/Kontak/locale/metadata lulus; enam screenshot Program dan enam Kontak tersimpan pada direktori bukti.

## 5. Acceptance terpadu

- [x] 5.1 Review 24 screenshot dari 4 halaman × 3 locale × desktop/mobile, bandingkan Home/Profil dengan mockup pada viewport sama; perbaiki overflow, crop, font, hierarki, kontras dan RTL lalu simpan bukti beserta daftar pengecualian yang disetujui.
  - Selesai (2026-10-29): 24 screenshot direview bersama empat baseline; logo, heading, crop hero serta kontras diperbaiki. results.json menunjukkan nol overflow/broken-image.
- [x] 5.2 Uji navigasi end-to-end dan bahasa, keyboard, no-JS, reduced motion, media/font gagal serta form demonstratif pada LTR/RTL; verifikasi semua tujuan valid, fallback layak dan tidak ada aksi donasi/kontak aktif.
  - Selesai (2026-10-29): tools/verify-mockup.cjs lulus 24 kombinasi dan tiga mode tambahan, memeriksa semua tautan/fragment, no-JS, reduced motion, keyboard, media/font gagal dan nihil kontrol aktif.
- [x] 5.3 Jalankan preflight --run-checks, Composer platform/dependency audit, Pint, test aplikasi, npm audit dan Vite build; verifikasi semua hasil tercatat per lapisan dan kegagalan gate diselesaikan sebelum menyatakan siap review lokal.
  - Selesai (2026-10-29): 83 test/311 assertion, Pint, build Vite, Composer platform dan preflight 9 pass; Composer audit tanpa advisory serta npm audit 0 vulnerability. Bukti dirangkum pada review.md.
- [x] 5.4 Validasi OpenSpec strict dan audit diff/aset publik; verifikasi hanya web_yayasan2 berubah, tidak ada secret/runtime privat, seluruh task memiliki bukti dan catatan Selesai bertanggal, serta README menjelaskan cara preview konsep dan batas kesiapan Rumahweb.
  - Selesai (2026-10-29): OpenSpec strict, diff check dan audit ignore lulus; .env/.local/vendor/node_modules tetap diabaikan. Perubahan hanya di web_yayasan2; README dan review.md menyatakan batas lokal/Rumahweb.

## Workflow follow-up

- Follow-up selesai (2026-10-29): logo transparan dan footer navigasi/empat ikon sosial diterapkan atas permintaan pengguna; screenshot logo/footer desktop/mobile ID/AR serta build, Pint dan 83 test/311 assertion lulus. Proposal/design/spec telah diselaraskan sebelum sync.

- Review hasil lokal bersama pengguna sebelum sync/archive sesuai permintaan berikutnya.
- Buat change publish-client-review-preview untuk URL Rumahweb, packaging, runtime web/CLI aktual dan rollback setelah frontend diterima; deployment tidak dihitung selesai dari bukti lokal.
- Commit/push/merge mengikuti instruksi Git pengguna; implementasi berikutnya tetap pada development.
