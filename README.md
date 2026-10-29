# Website Yayasan Hope Charity Indonesia

Fondasi Laravel 13 untuk website publik multilingual Yayasan Hope Charity Indonesia. Fase onboarding ini menyediakan Beranda, Profil, Program, dan Kontak dalam Bahasa Indonesia, Inggris, serta Arab (RTL), dengan sumber konten statis yang dapat diganti CMS kelak.

## Konsep review

Website mengikuti mockup pada empat halaman ID/EN/AR. Materi ilustratif
berstatus konsep hanya tersedia pada local/testing/preview. Donasi dihapus.
Deployment Rumahweb mengikuti change release setelah review lokal.

Implementasi frontend, logo transparan dan footer telah diverifikasi lokal.
Arsip: `openspec/changes/archive/2026-10-29-build-multilingual-mockup-pages/`.
Bukti review: `docs/verification/mockup-pages/review.md`.

## Batas fase onboarding

- Konten terverifikasi hanya nama resmi dan delapan kategori program.
- Belum ada CMS, database konten, akun admin, formulir aktif, donasi, email, WhatsApp, deployment, atau indexing produksi.
- Mockup di `mockups/` menjadi referensi visual; salinan isi/media tampil sebagai konsep berlabel pada local/testing/preview, bukan informasi resmi.
- Runtime hosting/web SAPI belum diverifikasi.

## Runtime lokal Windows

Baseline wajib PHP stabil `>=8.4.27 <9.0`; Windows memakai package revision `8.4.27-1` atau lebih baru. Runtime portable dan Composer lokal disimpan di `.local/runtime/` dan tidak masuk Git.

```powershell
.\tools\local\runtime-info.ps1
.\tools\local\composer.ps1 install
.\tools\local\php.ps1 artisan hope:preflight --run-checks
.\tools\local\php.ps1 artisan test
npm install
npm run build
```

Template konfigurasi PHP aman berada di `tools/runtime/php.ini`. Nilai `session.upload_progress.enabled` harus nonaktif sebelum aplikasi memproses request.

## Dokumentasi

- Arah visual onboarding: `docs/frontend/onboarding-direction.md`
- Hasil quality gate: `docs/verification/onboarding-2026-10-29.md`
- Browser/screenshot review: `docs/verification/browser-review-2026-10-29.md`
- Kontrak perubahan terarsip: `openspec/changes/archive/2026-10-29-onboard-hope-charity-foundation/`
