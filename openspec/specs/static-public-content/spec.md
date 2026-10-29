# Static Public Content Specification

## Purpose

Menetapkan sumber konten statis terverifikasi yang dapat dibaca renderer publik sekarang dan diganti sumber CMS kelak tanpa mengubah kontrak konsumennya.

## Requirements

### Requirement: Renderer membaca konten melalui kontrak sumber
Renderer publik SHALL memperoleh halaman, kategori program, dan status locale melalui satu kontrak sumber yang tidak bergantung pada penyimpanan statis atau database tertentu.

#### Scenario: Sumber statis digunakan
- **WHEN** aplikasi onboarding melayani request publik
- **THEN** renderer membaca data melalui implementasi sumber statis tanpa query database konten

#### Scenario: Sumber database ditambahkan kemudian
- **WHEN** implementasi CMS menggantikan sumber statis pada change terpisah
- **THEN** renderer dapat memakai implementasi baru tanpa mengubah bentuk data publik yang dikonsumsi

### Requirement: Identitas awal dibatasi pada fakta terverifikasi
Sumber statis MUST memuat nama “Yayasan Hope Charity Indonesia” dan membedakan fakta terverifikasi dari konsep review. Klaim kelembagaan tanpa sumber MUST tidak ditandai sebagai fakta resmi.

#### Scenario: Field kelembagaan belum tersedia
- **WHEN** renderer meminta field resmi tanpa sumber terverifikasi
- **THEN** sumber mengembalikan nilai kosong/status tidak tersedia
- **AND** nilai konsep hanya tersedia secara terpisah dengan status konsep dan provenance

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
Materi mockup MUST tidak menjadi fakta publik resmi; sumber SHALL mengizinkan narasi, statistik, kutipan, visi–misi, deskripsi dan foto sebagai konsep pada local/testing/preview berlabel. Identitas lama dan donasi MUST tidak disajikan.

#### Scenario: Data onboarding dibangun
- **WHEN** sumber statis disiapkan untuk review konsep
- **THEN** fakta yang disahkan mempunyai status verified dan materi mockup mempunyai status concept dengan sumber tercatat
- **AND** status konsep tidak dinaikkan menjadi verified karena diterjemahkan atau dirender

#### Scenario: Preview konsep dibaca
- **WHEN** halaman memuat materi konsep
- **THEN** penanda terlokalisasi menyatakan konten dan gambar masih konsep untuk review dan bukan informasi resmi
- **AND** statistik diberi konteks contoh serta foto tidak disebut dokumentasi kegiatan Hope Charity

#### Scenario: Environment publik resmi digunakan
- **WHEN** environment selain local/testing/preview meminta materi concept
- **THEN** materi tersebut tidak ditampilkan sebagai informasi resmi dan tidak diaktifkan hanya dengan flag indexing

### Requirement: Referensi media bersifat opsional dan aman
Sumber konten SHALL hanya mengembalikan media dengan identitas, provenance dan status verified/concept; kegagalan media MUST tidak menghasilkan URL rusak atau teks alternatif rekaan.

#### Scenario: Media belum terverifikasi
- **WHEN** tersedia ilustrasi mockup yang diotorisasi untuk preview
- **THEN** sumber mengembalikannya sebagai concept dengan asal aset dan alt yang menyebut ilustrasi
- **AND** bila aset tidak tersedia sumber mengembalikan media kosong dengan tampilan tanpa gambar yang layak

#### Scenario: Media gagal dilayani
- **WHEN** media verified atau concept gagal dimuat
- **THEN** halaman mempertahankan nama/status konten dan alternatif layak tanpa ikon rusak atau klaim visual pengganti

### Requirement: Konten statis dapat divalidasi sebelum boot publik
Data statis MUST ditolak bila stable key duplikat, urutan tidak unik, locale wajib hilang atau field mempunyai status/sumber tidak sah; nilai concept MUST tidak dianggap verified.

#### Scenario: Data program valid
- **WHEN** validator memeriksa delapan kategori yang disahkan beserta deskripsi konsep
- **THEN** nama resmi dan urutan 1 sampai 8 lulus terpisah dari status deskripsi concept

#### Scenario: Data statis tidak konsisten
- **WHEN** key/urutan duplikat, nama locale kosong, provenance hilang atau konsep diklaim resmi tanpa bukti
- **THEN** validasi gagal sebelum data dirender
