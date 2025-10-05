# 🚀 Galaxy Glide — Web App

A Laravel-based platform that allows users to **explore massive NASA space images** with deep zoom support.
Admins can upload and manage high-resolution space datasets, while users can view and explore them interactively.

---

## 🌌 Features

* 🔭 **Deep Zoom (DZI) Image Viewer** using [OpenSeadragon](https://openseadragon.github.io/).
* 👨‍🚀 **Role-based Access Control**

  * **Admin:** Upload, manage, and delete datasets.
  * **User:** Explore space datasets interactively.
* 🪐 **High-Resolution Image Support** (JPG, JEPG → converted into Deep Zoom tiles).
* 🌍 **Cross-device Access** (supports public viewing via ngrok/hosting).
* 🎨 **Modern UI** with starry background animations.

---

## 📂 Project Structure

```bash
├── app/                # Controllers, Models, Middleware
├── bootstrap/          
├── config/             
├── database/           # Migrations & Seeders
├── public/             # Storage symlink for uploaded images
├── resources/          
│   ├── views/          # Blade templates (Admin + User dashboards)
│   └── css/js/         # Frontend assets
├── routes/             
│   └── web.php         # Routes (public, user, admin)
└── storage/            
```

---

## ⚙️ Installation

### 1️⃣ Clone Repository

```bash
git clone https://github.com/your-username/-Galaxy-Glide---Web-App.git
cd Galaxy-Glide---Web-App
```

### 2️⃣ Install Dependencies

```bash
composer install
npm install && npm run build
```

### 3️⃣ Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with:

* Database connection (`DB_*`)
* Storage (`FILESYSTEM_DISK=public`)
* VIPS binary for Deep Zoom (if needed)

### 4️⃣ Migrate & Seed Database

```bash
php artisan migrate --seed
```

👉 This creates:

* An **admin account** (`admin@example.com / password123`)

### 5️⃣ Storage Symlink

```bash
php artisan storage:link
```

---

## 🚀 Running the Project

```bash
php artisan serve
```

Then open:
`http://localhost:8000`

If you want external access:

```bash
ngrok http 8000
```

---

## 🖼️ Usage

* **Admin Panel:** `/admin/images`

  * Upload new high-resolution datasets.
  * Manage (edit/delete) datasets.
* **User Dashboard:** `/dashboard`

  * Explore available space datasets.
  * Interactive zoom using OpenSeadragon.

---

## 📊 Roadmap / To Do

* 📹 Support **video datasets** (NASA also provides multi-spectral + video data).
* 🤖 Add **AI-powered pattern discovery** (optional).
* 🌐 Connect with NASA APIs directly (Worldview, LROC, TESS, EarthData).


