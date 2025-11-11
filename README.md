# Part Management System

A full-featured **Part Management Web Application** built using **Laravel (Backend)** and **React (Frontend)**.  
This project is designed to manage sales, products, and inventory efficiently with real-time updates and smooth UI interaction.

---

## 🚀 Features

### 🛒 Sales Management
- Add new sales with live calculation of subtotal, discount, and total.
- Supports both **fixed** and **percentage** discount types.
- Real-time discount update on both Create and Edit forms.
- Prevents duplicate sales entries.
- Automatic stock update on sale confirmation.
- Low stock warning for items below threshold.

### 📦 Product Management
- Add, update, and delete products easily.
- Product stock automatically decreases after a sale.
- Low stock products highlighted for admin review.

### 👥 User Management
- Register and login system (basic authentication included).
- Logout functionality implemented.

### ⚙️ System Functions
- Integrated CRUD operations (Create, Read, Update, Delete).
- Dynamic and responsive UI with React.
- Backend powered by Laravel API routes and controllers.
- Reusable and modular blade components.

### ⚠️ Known Limitations
- **Forgot Password**, **Email Verification**, and some **Authentication Features** are currently not functional.
- May require manual setup for `.env` configuration (database, app key, etc.).

---

## 🧩 Tech Stack

| Layer          | Technology |
|--------------- |-------------|
| Frontend       | React (Vite) |
| Backend        | Laravel 10 |
| Database       | MySQL |
| Authentication | Laravel Breeze / Manual Login |
| Styling        | TailwindCSS / Bootstrap (as per build) |

---

## 🗂️ Project Structure

```
parts-management/
├── backend/ (Laravel)
│   ├── app/
│   ├── routes/
│   ├── database/
│   └── resources/views/
│       ├── sales/
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── index.blade.php
│       └── layouts/
│
├── frontend/ (React)
│   ├── src/
│   │   ├── components/
│   │   ├── pages/
│   │   └── App.jsx
│   └── public/
│
└── README.md
```

---

## 🧠 Developer Notes

This project was built with consistency and modularity in mind.  
The main focus was to ensure **real-time interactivity** for discount calculation, **stock synchronization**, and an **intuitive sales process**.

### 👨‍💻 Developed By:
**Mohammad Sadman Chowdhury**  
_Bachelor of Science in Computer Science & Engineering_  
Premier University, Chittagong  
📧 Email: [YourEmail@example.com]

> **© 2025 - parts Management System**  
> All rights reserved. Unauthorized copying of this project, via any medium, is strictly prohibited.

---

## 🧾 Setup Instructions

### Backend (Laravel)
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Frontend (React)
```bash
cd frontend
npm install
npm run dev
```

---

## 🧰 Additional Info
- **Discount Feature:** Real-time calculation and updates on both sales creation and editing.
- **Stock Management:** Decreases automatically and warns when low.
- **User-Friendly Interface:** Designed with simplicity and usability in mind.
- **Custom Footer:** Each page footer includes developer credit.
- **Code Protection:** Inline scripts can be obfuscated, but front-end code remains visible by nature of web apps.

---

## ⚡ Final Notes

This parts Management System template is production-ready for further customization.  
Anyone using this system template must include the original developer credit unless substantial changes are made.

> “Built with dedication, patience, and a cup of tea.” ☕  
> — *Mohammad Sadman Chowdhury*
