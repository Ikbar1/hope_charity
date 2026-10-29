# Preview GitHub Pages

Target review yang disetujui pengguna: https://ikbar1.github.io/hope_charity/ . Repository sudah public sebelum deployment; visibility tidak diubah. Source Laravel tetap di development, sedangkan branch gh-pages hanya memuat hasil ekspor HTML/aset dan .nojekyll.

tools/export-pages.php merender 12 route melalui kernel Laravel pada environment preview, memetakan tautan/aset ke base path Pages dan menambahkan meta robots noindex/nofollow pada setiap HTML. Konsep, terjemahan dan formulir nonaktif tetap sama. Tidak ada PHP, .env, database atau credential dalam artefak ekspor. robots.txt pada subfolder bersifat tambahan; meta robots per halaman adalah kontrol noindex utama.

Jalankan tools/local/php.ps1 tools/export-pages.php setelah npm run build. Output baru berada di .local/pages-preview/<timestamp>; perintah gagal bila target sudah ada. Verifikasi memakai tools/verify-pages.cjs <output> dengan HOPE_PLAYWRIGHT menunjuk paket Playwright yang tersedia. Pemeriksaan mencakup 12 halaman × dua viewport, tautan lokal, aset, overflow dan meta noindex.

Publikasi memakai branch gh-pages dari hasil ekspor yang sudah diperiksa. Untuk update, gunakan checkout branch gh-pages dan commit hasil ekspor baru; jangan force-push source development/main. Pages disetel Deploy from branch, gh-pages, root /. Revert commit deployment untuk rollback; aplikasi Rumahweb tidak disentuh.

tools/github-pages.ps1 memakai credential GitHub tersimpan melalui Git Credential Manager untuk Inspect/Enable/Status. Credential hanya dipakai dalam memori dan tidak dicetak/disimpan. Helper tidak membuat token atau mengubah visibility repo.

GitHub Pages hanya untuk review frontend statis. CMS/formulir aktif tetap membutuhkan runtime Laravel pada hosting. Preview publik dapat dibaca siapa pun yang mempunyai URL; noindex tidak membatasi akses.

Verifikasi 29 Oktober 2026: Pages berstatus built dengan source gh-pages /. Artefak awal 6073912 dan pembaruan lisensi font 41ea210 dipush tanpa force. URL publik mengembalikan 200. Browser live lulus pada 12 route × viewport 1440/390px: meta noindex, logo termuat, tautan/aset menunjuk base path Pages dan nol overflow. Screenshot live tersimpan di docs/verification/mockup-pages/pages-live-home.jpg. Revisi source di development tidak otomatis terbit; ekspor dan commit gh-pages harus dijalankan ulang untuk update.
