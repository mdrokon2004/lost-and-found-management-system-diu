# DIU Lost & Found Management System

A professional PHP + MySQL DBMS project for Daffodil International University.

## Stack
- PHP 8+
- MySQL 8+
- XAMPP
- Bootstrap 5
- Vanilla JavaScript
- CSS glassmorphism UI
- Git + GitHub

## Features
- User registration/login/logout
- Role-based admin access
- Lost/found reporting
- Secure image upload validation
- Search and filters
- Item details
- Ownership claims
- Notifications
- User dashboard
- Admin dashboard
- Categories, locations and statuses
- Activity logs
- MySQL view, stored procedure and trigger
- Prepared statements and password hashing

## Setup with XAMPP
1. Install XAMPP and start Apache + MySQL.
2. Copy this folder into `C:/xampp/htdocs/`.
3. Open phpMyAdmin.
4. Import `database/database.sql`.
5. Check `config/database.php`.
6. Open `http://localhost/lost-and-found-management-system/`.

### Demo admin
Email: `admin@diu-lostfound.local`
Password: `ChangeMe123!`

Change this password before any real deployment.

## Team
- MD ROKON — 242-15-550
- Abdullah Hell Kafi — 242-15-387
- Md.Iftekhar Ahmmed — 242-15-664
- Md.Meheraj Hossain — 242-15-440
- Md.Fayaz — 242-15-452

## Git workflow
Branches:
- main
- develop
- feature/rokon
- feature/kafi
- feature/iftekhar
- feature/meheraj
- feature/fayaz

Example:
```bash
git checkout -b feature/rokon
git add .
git commit -m "feat: implement user authentication"
git push -u origin feature/rokon
```

## Important
This package is a strong runnable academic foundation. Before live deployment, replace local database credentials, remove demo credentials, configure HTTPS, and review all authorization actions.

## Portals & UI
- **User Login:** `/login.php`
- **Admin Login:** `/admin/login.php`
- The navbar and homepage expose separate User/Admin login buttons.
- The UI includes an iOS-style glassmorphism design with persistent Light/Dark mode.
- Admin review pages provide **Approve / Reject** actions for Lost Reports, Found Reports, and Claims.

### Demo Admin
- Email: `admin@diu-lostfound.local`
- Password: `ChangeMe123!`

