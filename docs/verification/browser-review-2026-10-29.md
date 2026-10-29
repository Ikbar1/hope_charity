# Browser Review Onboarding — 2026-10-29

## Matriks

- Browser utama: Codex in-app browser pada server lokal `127.0.0.1`.
- Desktop: 1440×900, 4 halaman × ID/EN/AR = 12 capture.
- Mobile: 390×844, 4 halaman × ID/EN/AR = 12 capture.
- Mode tambahan: ID Beranda tanpa JavaScript; ID Program dengan forced reduced motion; EN Program tanpa JavaScript + forced reduced motion. Edge headless yang sudah terpasang memakai profil sementara di `.local/`, bukan profil pengguna.
- Total artefak: 27 screenshot di `docs/verification/screenshots/`.

## Pemeriksaan terukur

- Seluruh 24 kombinasi desktop/mobile mempunyai `lang` dan `dir` yang sesuai: ID/EN LTR, AR RTL.
- `document.scrollWidth > clientWidth` bernilai false pada seluruh kombinasi.
- Setelah perbaikan, primary navigation mobile juga tidak memiliki overflow internal pada seluruh locale.
- Tidak ada `<img>` pada onboarding sehingga broken image count selalu 0; mockup media tidak dipublikasikan.
- Browser console tidak mencatat error atau warning.
- Setelah reload, fokus Tab pertama adalah skip link `#main-content`; urutan berikutnya berpindah ke brand dan navigasi link biasa.

## Temuan dan perbaikan

Review awal menemukan menu Arab mobile memakai scroll horizontal sehingga label Kontak terpotong pada posisi awal. CSS diperbaiki menjadi grid dua kolom pada breakpoint mobile. Screenshot serta metrik seluruh halaman mobile kemudian diulang dan semuanya lulus tanpa overflow.

Backend screenshot full-page mempunyai artefak koordinat pada dokumen RTL; capture Arab mobile disimpan sebagai viewport screenshot setelah DOM rectangle dan overflow aktual diverifikasi. Capture artefak yang menyesatkan dihapus dan tidak dimasukkan sebagai bukti.

## Batas

Review ini hanya terhadap local server dan desain onboarding minimal. Ini bukan approval desain final, bukan pemeriksaan hosting/web SAPI, dan bukan bukti staging/produksi.
