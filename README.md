# 🎓 Student Creative Hub

> **A modern student portfolio and achievement management platform built with Laravel 12, Blade SSR, Bootstrap 5, Vite, and MySQL.**

Student Creative Hub is a full-stack web application developed to help university students manage, organize, and showcase their academic journey in one centralized platform. The system enables students to build a professional digital portfolio by documenting their projects, achievements, certifications, organizational experiences, skills, and other supporting information.

Designed with usability and scalability in mind, Student Creative Hub provides an intuitive dashboard for students and administrators while offering a professional PDF portfolio generator that can be used for internships, scholarships, competitions, and career opportunities.

---

## ✨ Key Highlights

- 🎯 Comprehensive student portfolio management
- 🏆 Achievement and competition tracking
- 📁 Academic & personal project showcase
- 📜 Certificate management
- 💼 Organization and leadership experiences
- 🛠 Skills management
- 📄 Professional A4 Portfolio PDF Generator
- 👨‍💼 Administrator Dashboard & Project Verification
- 📊 Analytics and rule-based insights
- 🔒 Secure authentication and role-based access control (Admin & Mahasiswa)

---

## 🛠 Tech Stack

- **Backend:** Laravel 12 (PHP ^8.2)
- **Frontend / Rendering:** Blade Views (Server-Side Rendering)
- **UI & Styling:** Bootstrap 5, Bootstrap Icons, Custom Design System
- **Asset Bundler:** Vite (`laravel-vite-plugin`)
- **Database:** MySQL
- **Additional Libraries:** DomPDF (`barryvdh/laravel-dompdf`), Simple QRCode (`simplesoftwareio/simple-qrcode`), Chart.js

---

## 💻 Getting Started (Local Development)

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL (e.g. via Laragon / XAMPP)

### Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd Student_Creative_Hub
   ```

2. **One-Command Setup:**
   A dedicated setup command is provided in `composer.json` to handle dependency installation, `.env` file creation, app key generation, database migration, and asset compilation:
   ```bash
   composer setup
   ```

3. **Configure Database:**
   Ensure database credentials in your `.env` file match your local MySQL server (default in Laragon: `DB_DATABASE=student_creative_hub`, `DB_USERNAME=root`, `DB_PASSWORD=`).

4. **Run the Development Server:**
   Start the application server, queue worker, logging, and Vite asset compiler concurrently:
   ```bash
   composer dev
   ```
   Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

5. **Run Tests:**
   ```bash
   composer test
   ```

---

## 🚀 Project Status

> 🚧 **Currently under active development**

This project is being developed as part of a university internship (**Kerja Praktek**) and is continuously improved through incremental feature development, UI/UX enhancements, and documentation.
