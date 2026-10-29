# Laporan Verifikasi Onboarding — 2026-10-29

## Lingkup yang diverifikasi

Verifikasi ini hanya berlaku untuk runtime **CLI Windows lokal** di `web_yayasan2`. Web SAPI, hosting/cPanel, staging, domain, indexing produksi, dan deployment tidak diuji serta tetap berstatus **unverified**.

## Hasil per lapisan

| Lapisan | Perintah/pemeriksaan | Hasil |
| --- | --- | --- |
| Runtime | `hope:preflight --run-checks` | READY; 9 pemeriksaan pass |
| PHP | PHP 8.4.27, Windows package revision 1, session upload progress off | Pass |
| Composer | `validate --strict`, `check-platform-reqs` | Pass |
| Dependency PHP | `composer audit --locked` | Tidak ada advisory yang ditemukan |
| Format PHP | `pint --test` | Pass setelah formatter resmi memperbaiki 4 berkas |
| Test PHP | seluruh unit/feature test | 78 pass, 264 assertion |
| Dependency JavaScript | `npm audit` | 0 vulnerability |
| Test JavaScript | Tidak ada test JavaScript terpisah pada shell tanpa-JS | Tidak berlaku |
| Build frontend | `npm run build` (Vite 8.3.5) | Pass; manifest dan entry CSS/JS tersedia |

## Batas bukti

- Hasil CLI lokal tidak membuktikan konfigurasi PHP web SAPI.
- Manifest provenance lokal tidak membuktikan paket vendor Linux/cPanel.
- Tidak ada credential, database produksi, email, WhatsApp, hosting, DNS, atau sistem eksternal yang digunakan.
- Browser/screenshot review dicatat pada task verifikasi berikutnya.
