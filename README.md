# Simpelfas

SIMPELFAS (Sistem Manajemen Pelaporan dan Perbaikan Fasilitas Kampus) adalah aplikasi website yang digunakan untuk mempermudah proses pelaporan kerusakan serta pemantauan perbaikan fasilitas di lingkungan kampus. Sistem ini dirancang agar mahasiswa dan staf kampus dapat melaporkan masalah dengan cepat, dan pihak terkait dapat menindaklanjuti secara efisien. (upcoming)



Gunakan panduan di bawah ini untuk proses clone, pull, dan push dengan aman agar terhindar dari konflik kode.

```bash
# Clone repository
git clone https://github.com/jioooo20/simpelfas.git
cd simpelfas

# Git Pull (jika ada perubahan lokal)
git stash             # Simpan perubahan lokal sementara
git pull              # Ambil perubahan terbaru dari remote
git stash pop         # Kembalikan perubahan lokal

# Jika terjadi konflik saat stash pop:
# (perbaiki file yang konflik secara manual)
git add .
git commit -m "Fix conflict after pull"

# Git Push (pastikan sinkronisasi dengan remote terlebih dahulu)
git stash             # Simpan perubahan lokal
git pull              # Tarik update dari remote
git stash pop         # Kembalikan perubahan lokal

# Jika terjadi konflik saat stash pop:
# (perbaiki file yang konflik secara manual)
git add .
git commit -m "Fix conflict before push"

# Setelah semua aman, lakukan push:
git push origin main  # Ganti 'main' dengan nama branch jika berbeda

# Selalu cek status branch:
git status
