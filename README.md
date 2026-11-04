# Praktikum P9 - DOM, Event, dan Interaksi Web

Proyek ini dibuat berdasarkan Modul Praktikum P9 (Pertemuan 9) dan mencakup Latihan 1, Latihan 2, Latihan 3, serta proyek terpadu Daftar Kegiatan.

## Struktur folder

```text
praktikum_p9_dom_event/
├── index.html
├── latihan1.html
├── latihan2.html
├── latihan3.html
├── css/
│   └── style.css
└── js/
    └── app.js
```

## Cara menjalankan

1. Ekstrak file ZIP.
2. Buka folder `praktikum_p9_dom_event`.
3. Klik dua kali `index.html` untuk menjalankan aplikasi utama di browser.
4. Untuk melihat latihan satu per satu, buka `latihan1.html`, `latihan2.html`, atau `latihan3.html`.
5. Tekan `F12` lalu pilih tab Console untuk melihat hasil `console.log()` pada latihan DOM.

## Fitur aplikasi utama

- Menambahkan kegiatan melalui tombol Tambah atau tombol Enter.
- Validasi input kosong dan input yang hanya berisi spasi.
- Batas nama kegiatan maksimal 80 karakter.
- Memasukkan tanggal kegiatan.
- Menandai kegiatan selesai atau belum selesai.
- Menghapus satu kegiatan.
- Menghapus semua kegiatan dengan konfirmasi `confirm()`.
- Filter Semua, Belum selesai, dan Selesai.
- Ringkasan jumlah total, selesai, dan belum selesai.
- Tampilan responsif untuk layar ponsel.

## Catatan

Sesuai batas pembahasan modul, data disimpan di dalam array selama halaman masih terbuka. Data akan kembali kosong ketika halaman dimuat ulang. Penyimpanan `localStorage` merupakan pengembangan opsional dan tidak diaktifkan pada versi ini.
