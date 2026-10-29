# Review frontend konsep — 29 Oktober 2026

Follow-up footer (permintaan pengguna): logo transparan, navigasi empat halaman, copyright dan empat ikon sosial diterapkan pada shell bersama dalam ID/EN/AR. Ikon belum ditautkan karena akun resmi belum diberikan. Screenshot footer-id/ar-1440/390.png diperiksa: komposisi responsif, empat ikon, nol overflow dan nol tautan akun rekaan. Build, Pint dan 83 test/311 assertion lulus sesudah perubahan.

Implementasi lokal pada development. Skill frontend-design mengarahkan pemeliharaan palet, font, hero lengkung dan ritme mockup, serta review LTR/RTL. Browser Edge headless, 1440×900 dan 390×900. tools/verify-mockup.cjs menghasilkan baseline-index/profil pada kedua lebar serta 24 screenshot empat halaman × tiga bahasa × dua viewport. results.json merekam lang, dir, overflow, broken images, jumlah kategori dan nihil kontrol kontak aktif.

Home/Profil dibandingkan dengan baseline; perubahan yang mengikuti brief adalah logo/nama resmi, empat navigasi dan bahasa, kategori resmi, penghapusan section/CTA/modal donasi, serta label konsep dan perilaku aksesibel. Program/Kontak meneruskan hero, kartu, panel cream dan serif yang sama. Kategori tanpa ilustrasi relevan memakai simbol; foto AI mockup tidak diubah menjadi dokumentasi kegiatan.

Perbaikan dari review: logo awal terpotong kini ditampilkan utuh dengan rasio 1:1; heading program/feature dipulihkan skalanya; tinggi hero profil dikunci pada container agar dimensi intrinsik gambar tidak memperpanjang halaman; kontras kutipan hero diperkuat dengan latar transparan, terutama RTL. Navigasi mobile tanpa JS tetap terlihat. Semua screenshot diperiksa untuk komposisi, crop, hierarki, konsistensi, overflow dan arah baca.

Hasil terukur: 24 respons 200, lang/dir sesuai, tidak ada overflow horizontal/gambar rusak, Program tepat delapan kategori, Kontak tidak mempunyai kontrol aktif/form submit. Semua tautan internal dan fragment diperiksa. Mode tambahan: Kontak ID/AR tanpa JS dan reduced motion, serta Arab dengan kegagalan gambar/font; skip link, toggle menu dan Escape diuji lewat keyboard/browser. Foto yang gagal disembunyikan dengan layout fallback; font memakai fallback lokal.

PHPUnit: 83 test/311 assertion lulus. Pint lulus. Vite build lulus dan font dibundel lokal. Preflight READY dengan 9 pass termasuk Composer platform, suite aplikasi dan manifest. Composer audit tidak menemukan advisory/abandoned package; npm audit 0 vulnerability. Pemeriksaan hanya lokal; belum upload Rumahweb atau review editorial resmi yayasan.

Preview: http://127.0.0.1:8765/ ; Inggris /en ; Arab /ar. Jalankan tools/local/php.ps1 -S 127.0.0.1:8765 -t public bila server berhenti. APP_ENV lokal harus local/preview agar konsep terlihat; production tidak menampilkan blok konsep. CMS dan posting belum diaktifkan. Rilis Rumahweb membutuhkan change deployment berikutnya.
