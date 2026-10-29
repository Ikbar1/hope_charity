# Spec Delta

## Purpose

Menetapkan sumber konten statis terverifikasi yang dapat dibaca renderer publik sekarang dan diganti sumber CMS kelak tanpa mengubah kontrak konsumennya.

## ADDED Requirements

### Requirement: Renderer membaca konten melalui kontrak sumber
Renderer publik SHALL memperoleh halaman, kategori program, dan status locale melalui satu kontrak sumber yang tidak bergantung pada penyimpanan statis atau database tertentu.

#### Scenario: Sumber statis digunakan
- **WHEN** aplikasi onboarding melayani request publik
- **THEN** renderer membaca data melalui implementasi sumber statis tanpa query database konten

#### Scenario: Sumber database ditambahkan kemudian
- **WHEN** implementasi CMS menggantikan sumber statis pada change terpisah
- **THEN** renderer dapat memakai implementasi baru tanpa mengubah bentuk data publik yang dikonsumsi

### Requirement: Identitas awal dibatasi pada fakta terverifikasi
Sumber statis MUST memuat nama “Yayasan Hope Charity Indonesia” dan MUST tidak menambahkan profil, legalitas, kontak, lokasi, pengurus, capaian, donatur, atau klaim lain tanpa sumber yang disahkan.

#### Scenario: Field kelembagaan belum tersedia
- **WHEN** renderer meminta field yang belum mempunyai sumber terverifikasi
- **THEN** sumber mengembalikan status tidak tersedia atau nilai kosong yang terstruktur
- **AND** renderer tidak membuat placeholder yang menyerupai fakta

### Requirement: Delapan kategori program mempunyai identitas stabil
Sumber statis SHALL menyediakan tepat delapan kategori program dalam urutan yang disahkan, dengan stable key dan nama Indonesia, Arab, serta Inggris yang tidak berubah karena locale aktif.

#### Scenario: Daftar program dibaca
- **WHEN** renderer meminta kategori program pada locale manapun
- **THEN** sumber mengembalikan urutan dan pasangan berikut:

| Urutan | Stable key | Indonesia | Arab | Inggris |
|---:|---|---|---|---|
| 1 | `mosque-construction` | Pembangunan Masjid | بناء المساجد | Mosque Construction |
| 2 | `facility-development` | Pembangunan Fasilitas | إنشاء المرافق | Facility Development |
| 3 | `water-well-construction` | Pembuatan Sumur | حفر الآبار | Water Well Construction |
| 4 | `orphan-sponsorship` | Santunan Anak Yatim | كفالة الأيتام | Orphan Sponsorship |
| 5 | `assistance-programs` | Program Bantuan | برامج المساعدات | Assistance Programs |
| 6 | `iftar-programs` | Program Buka Puasa | إفطار الصائمين | Iftar Programs |
| 7 | `social-assistance` | Bantuan Sosial | المساعدات الاجتماعية | Social Assistance |
| 8 | `humanitarian-aid` | Bantuan Kemanusiaan | المساعدات الإنسانية | Humanitarian Aid |

#### Scenario: Locale program dipilih
- **WHEN** nama sebuah kategori diminta untuk `id`, `en`, atau `ar`
- **THEN** sumber mengembalikan nama pada kolom locale yang sama tanpa menerjemahkan ulang atau mengganti stable key

### Requirement: Posting program awal kosong
Sumber statis SHALL membedakan kategori program dari posting program dan SHALL mengembalikan koleksi posting kosong sampai publikasi terverifikasi tersedia.

#### Scenario: Kategori belum mempunyai posting
- **WHEN** renderer meminta posting untuk salah satu dari delapan kategori
- **THEN** sumber mengembalikan koleksi kosong dan status bahwa belum ada publikasi
- **AND** tidak membuat artikel, tanggal, lokasi, capaian, atau media contoh

### Requirement: Konten mockup tidak menjadi data publik
Angka statistik, donasi, hadis, visi-misi, deskripsi program, foto ilustratif, dan identitas contoh pada mockup MUST tetap berstatus referensi desain dan MUST tidak masuk sumber konten publik.

#### Scenario: Data onboarding dibangun
- **WHEN** implementasi statis disiapkan dari mockup dan sumber terverifikasi
- **THEN** hanya fakta yang disahkan masuk data publik
- **AND** setiap nilai ilustratif mockup tidak tersedia melalui kontrak konten

### Requirement: Referensi media bersifat opsional dan aman
Sumber konten SHALL hanya mengembalikan media dengan identitas serta provenance yang tercatat; ketiadaan atau kegagalan media MUST tidak menghasilkan URL rusak atau teks alternatif rekaan.

#### Scenario: Media belum terverifikasi
- **WHEN** kategori atau halaman belum mempunyai aset yang disahkan
- **THEN** sumber mengembalikan media kosong dan renderer memakai keadaan tanpa gambar yang layak

#### Scenario: Media gagal dilayani
- **WHEN** referensi media terverifikasi tidak dapat dimuat
- **THEN** halaman tetap menyajikan nama dan status konten tanpa ikon gambar rusak atau klaim visual pengganti

### Requirement: Konten statis dapat divalidasi sebelum boot publik
Data statis MUST ditolak bila stable key duplikat, urutan tidak unik, locale wajib hilang, atau field tak terverifikasi diisi tanpa status sumber.

#### Scenario: Data program valid
- **WHEN** validator memeriksa delapan kategori yang disahkan
- **THEN** validasi lulus dan menghasilkan urutan deterministik 1 sampai 8

#### Scenario: Data statis tidak konsisten
- **WHEN** terdapat key/urutan duplikat, nama locale kosong, atau field asing tanpa provenance
- **THEN** validasi gagal sebelum data tersebut dapat dirender sebagai konten publik
