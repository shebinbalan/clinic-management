# 🏥 Clinic Appointment System (Backend)

A Laravel-based backend using `web.php` routes for managing doctors, patients, and appointments with role-based access.

---

## 🚀 Features

- Role-Based Authentication (`Admin`, `Doctor`, `Patient`)
- Manage Doctors with Available Time Slots
- Register & Manage Patients
- Book Appointments with Time Validation
- View Appointments by Date, Doctor, or Patient
- Prevent Double Bookings
- Blade-based Interface (no API)

---

## 🛠 Tech Stack

- Laravel 10+
- Laravel Breeze (for web-based auth scaffolding)
- MySQL
- PHP 8.1+
- Bootstrap 5 (optional)

---

## 🔐 Authentication

- `/register` → Register (choose role: Admin / Doctor / Patient)
- `/login` → Login
- Middleware-based role restriction in `web.php`

---

## 👨‍⚕️ Doctors

- `GET /doctors` – List all doctors
- `GET /doctors/create` – Create new doctor (Admin only)
- `POST /doctors` – Save doctor
- `GET /doctors/{id}/edit` – Edit doctor
- `GET /doctors/{id}/appointments` – View doctor appointments

---

## 👨‍🦰 Patients

- `GET /patients/create` – Register patient
- `GET /patients/{id}/appointments` – Patient's appointments

---

## 📅 Appointments

- `GET /appointments/create` – Create appointment
- `POST /appointments` – Save appointment
- `GET /appointments` – Appointment listing with filters

---

## 🧪 Validation

- Double Booking Prevention
- Doctor Availability Slot Matching
- Unique Appointments per time slot

---

## 🗃 Database Tables

- `users`
- `doctors`
- `patients`
- `appointments`

---

## 🔧 Setup Instructions

```bash
git clone https://github.com/shebinbalan/clinic-management.git
cd clinic-management
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
