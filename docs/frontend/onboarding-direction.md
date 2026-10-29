# Arah Visual Onboarding Hope Charity

Dokumen ini adalah fondasi visual minimal untuk memeriksa struktur, multilingual, dan aksesibilitas. Ini bukan persetujuan desain final dan tidak memindahkan foto, statistik, klaim, ataupun isi ilustratif dari mockup.

## Identitas dan palet

- **Emerald utama `#006B57`** dan **emerald dalam `#00473C`** diambil dari karakter hijau logo untuk navigasi, fokus, dan teks aksen.
- **Emas `#E9B719`** dipakai hemat sebagai garis/motif, bukan warna teks panjang.
- **Ink `#17312D`**, **mist `#F2F6F4`**, dan **putih hangat `#FFFDF8`** menjaga keterbacaan serta memberi ruang pada identitas logo.
- Kontras teks dan focus ring harus tetap jelas; warna tidak menjadi satu-satunya pembeda state.

## Tipografi

- Judul Latin memakai Georgia/Cambria sebagai serif lokal yang mendekati wibawa wordmark tanpa meniru logo secara literal. Isi Latin memakai system UI sans-serif.
- Arab memakai `Noto Naskh Arabic`, `Amiri`, lalu Tahoma/Segoe UI sebagai fallback lokal. Tidak ada font remote agar build dan privasi tidak bergantung layanan luar.
- Skala judul menggunakan `clamp()` dan maksimum tiga tingkat utama pada shell onboarding. Body mempertahankan line-height minimal 1.65.

## Layout dan arah baca

- Lebar baca maksimum 72rem, gutter responsif, dan alignment awal mengikuti `dir`: kiri pada ID/EN, kanan pada AR.
- Spacing dan border menggunakan logical properties (`inline`, `block`) agar RTL bukan sekadar membalik ikon.
- Navigasi tetap link HTML biasa, dapat dipakai tanpa JavaScript, dengan skip link, landmark, `aria-current`, dan focus ring yang terlihat.

## Elemen visual utama

Satu garis lengkung emerald–emas di bagian intro mengacu pada lengkung bawah logo. Motif dibuat dengan CSS dan tidak dipakai berulang sebagai dekorasi generik. Program kelak ditampilkan sebagai daftar berurutan, bukan grid kartu template dengan foto atau angka rekaan.

## Pemeriksaan anti-template

- Hindari hero foto penuh, statistik besar, testimonial, tombol donasi, dan card grid generik karena belum ada data terverifikasi.
- Shell harus terasa tenang dan institusional melalui proporsi, garis lengkung, serta tipografi—bukan melalui placeholder marketing.
- Motion tidak diperlukan untuk memahami halaman; semua transisi dimatikan pada `prefers-reduced-motion`.
