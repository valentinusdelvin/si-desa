# 📘 Panduan Pengerjaan Proyek Kelompok

Hai freeen! 👋  
Ini panduan tata cara ngerjain projek TIS, tolong dibaca bener bener yaaa,
kalo ada bingung bisa langsung tanya aja di grup

---

## 🧩 Persiapan Awal

### 1. Clone Repository
Clone repository ini terlebih dahulu:
```bash
git clone https://github.com/valentinusdelvin/si-desa.git
```

### 2. Masuk ke Folder Proyek
```bash
cd si-desa
```

### 3. Install Dependencies
Jalankan perintah berikut untuk menginstal dependency:
```bash
composer install
```

### 4. Setup Environment
Buat file `.env` berdasarkan contoh terus diisi sesuai settingan local kalian:
```bash
cp .env.example .env
```
Isi konfigurasi di file `.env` sesuai pengaturan lokal kalian (database, app key, dsb).

### 5. Migrasi Database
Jalankan migrasi database (jika diperlukan):
```bash
php artisan migrate
```

### 6. Pembuatan Application Key
Jalankan command berikut untuk generate application key sehingga website dapat dijalankan
```bash
php artisan key:generate
```
---

## 🧑‍💻 Alur Pengerjaan

### 1. Buat Branch Baru
Buat branch baru untuk fitur atau perbaikan bug:
```bash
git checkout -b nama-branch
```
**Contoh:** `git checkout -b fitur-login` atau `git checkout -b fitur-aspirasi`

### 2. Commit Perubahan
Setelah selesai coding, lakukan commit:
```bash
git add .
git commit -m "deskripsi singkat perubahan"
```

### 3. Push ke GitHub
Push branch kalian ke GitHub:
```bash
git push origin nama-branch
```

### 4. Buat Pull Request
Buat Pull Request (PR) di GitHub agar perubahan bisa direview sebelum digabung ke branch `main` (kabarin gw kalo pull request).

---

## ⚠️ Catatan Penting

- ❌ **Jangan langsung push ke branch `main` tanpa review**
- 🔄 **Selalu pull perubahan terbaru sebelum mulai ngoding:**
  ```bash
  git pull origin main
  ```
- 💬 **Kalau ada conflict, tanyain di grup baee**

---

**Selamat Ngoding! 🚀💪**
