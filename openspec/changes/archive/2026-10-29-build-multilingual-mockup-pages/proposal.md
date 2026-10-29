# Proposal

## Why

Shell onboarding belum memberi gambaran website untuk presentasi remote. Yayasan membutuhkan empat halaman penuh dengan tampilan dan isi mockup sebagai bahan review sebelum memberikan konten resmi.

## What Changes

- Follow-up pengguna: gunakan logo transparan dan lengkapi footer bersama dengan navigasi serta empat ikon sosial tanpa tautan akun rekaan; tersedia dalam tiga bahasa.

- Pertahankan komposisi, section, warna, tipografi, gambar, dan isi mockup Beranda/Profil; ganti identitas dengan logo serta nama Hope Charity dan hapus seluruh donasi.
- Buat Program dan Kontak mengikuti bahasa visual yang sama; tampilkan delapan kategori resmi dengan terjemahan pengguna.
- Sediakan seluruh isi dalam ID/EN/AR, termasuk RTL dan navigasi antarhalaman ekuivalen.
- Izinkan materi mockup sebagai konten konsep berlabel untuk review, terpisah dari fakta terverifikasi. Statistik, narasi, visi–misi, kutipan, dan foto tidak menjadi klaim resmi.
- **BREAKING**: kontrak yang sebelumnya melarang semua materi mockup diganti izin terbatas untuk preview konsep. Posting program tetap kosong.

## Capabilities

### New Capabilities

- `public-mockup-presentation`: kesetiaan visual mockup, empat halaman lengkap, identitas Hope Charity, dan pengalaman review tanpa donasi.

### Modified Capabilities

- `static-public-content`: status konsep, provenance, media ilustratif, dan pembedaan dari konten resmi.
- `multilingual-public-foundation`: konten konsep terlokalisasi serta status bahasa tanpa fallback terselubung.

## Impact

Blade, CSS/JS, lang, read model/sumber konten statis, validator, pengujian konten dan browser, aset publik serta dokumentasi frontend terdampak. Laravel 13, kontrak repository dan baseline PHP ^8.4.27 dipertahankan; Windows minimal build 8.4.27-1 dan session upload progress tetap nonaktif.

Fakta teramati: mockup hanya mempunyai Beranda/Profil dan lima foto ilustratif; implementasi kini memakai empty state. Logo tersedia dari lampiran pengguna dan belum masuk repository. Sumber: mockups/charity-demo, logo lampiran, daftar program pengguna, dan keputusan percakapan.

Keputusan pengguna: setia pada mockup, hapus donasi, tambah Program/Kontak, tiga bahasa, pengerjaan pada development, dan target review di Rumahweb. Usulan teknis: konten konsep terstruktur, label review terlokalisasi dan font self-hosted. Asumsi: Kontak menggunakan slot informasi belum tersedia dan formulir demonstratif nonaktif; tidak membuat nomor/alamat contoh yang menyerupai kontak nyata.

Non-goals: CMS, database konten, akun admin, posting rekaan, transaksi, formulir aktif, hosting/DNS dan indexing produksi. Gate berikutnya: screenshot serta acceptance lokal, lalu change publikasi preview Rumahweb dengan URL, paket release, verifikasi runtime dan rollback khusus Hope Charity. Website lain yang sudah live menjadi referensi operasional, bukan sumber credential.
