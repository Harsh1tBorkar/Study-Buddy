# 🎓 Study Buddy — Centralized Academic Ecosystem

> **Connect, Collaborate, and Elevate Your Learning.** 
> Study Buddy is a premium, secure, and gamified educational web application designed for engineering students and faculty to share, rate, bookmark, and review academic resources. Built using a robust PHP/MySQL backend and a modern glassmorphism frontend styled with a curated Cream & Espresso palette.

---

## 🚀 Key Modules & Role-Based Access Control

The platform implements a multi-tier authorization hierarchy with three main roles:

### 1. 👨‍🎓 Student Dashboard
* **Resource Discovery:** Search and filter approved academic files (PDFs, PPTs, Images) by subject and semester tags (`FY - BTECH - SEM 1` through `LY - BTECH - SEM 8`).
* **Engagement Engine:** Rate files (1–5 stars), leave comments for interactive study, and bookmark resources for quick access.
* **Upload Queue:** Submit study documents (up to 25MB). Uploads remain in a secure pending state until reviewed and approved by faculty or the principal.
* **Gamified Milestones:** Earn credits for approved uploads to unlock visual progress toward ₹10 Google Play Vouchers (every 50 credits).
* **Bug Reporting:** Report system anomalies with details, severity (`Critical`, `Minor`, `Cosmetic`), and environment details.

### 2. 👩‍🏫 Faculty Dashboard
* **Subject-Locked Moderation:** View and moderate (Approve / Reject) pending uploads matching the faculty's assigned subjects.
* **Rejection Feedback:** Provide mandatory constructive remarks explaining to students why a document was rejected before they re-upload.
* **Report Handling:** Review flagged documents reported by students (due to duplication, incorrect tag, poor quality, etc.) and decide to dismiss the report or delete the document.

### 3. 👑 Principal Dashboard (Root Admin)
* **Global Moderation Access:** Approve/reject all pending submissions and resolve document reports across all subjects.
* **Faculty Management:** Dynamically assign additional subject permissions to faculty members to balance moderation workloads.
* **Resource Broadcasting:** Uploading a document as a Principal automatically broadcasts an asynchronous notification to all students and faculty.
* **Global Deletion:** Permanently purge files from the server and update related database references.

---

## 🛠️ Tech Stack & Architecture

* **Backend:** PHP (native sessions, MVC-style lightweight API routing).
* **Database:** MySQL relational DB with foreign key constraints, checks, and cascade deletions.
* **Security & Database Access:** Strict SQL injection prevention using **PDO Prepared Statements**; input sanitization.
* **Frontend:** Semantic HTML5, Vanilla CSS3 (custom variable-driven glassmorphism UI, transitions, responsive flexbox/grid layouts), and asynchronous Javascript (utilizing the native `Fetch API` for SPA-like responsiveness, toast notifications, modals, and dynamic data binding).

---

## 🔒 Security & Backend Safeguards

* **File Verification & Sanitization:**
  * Strict MIME type checking using `finfo_file` (restricting uploads to verified PDFs, PPT/PPTXs, and JPEGs/PNGs).
  * Fingerprint-based duplicate prevention: Generates a `SHA-256` hash of file content upon upload. If an identical file exists, the system rejects it with a copyright alert.
  * Auto-generation of secure unique file names on the server (e.g., `doc_6a66d34df3fce3...pdf`) to prevent directory traversal and overwrite attacks.
* **Developer Modes & Visual Bypasses:**
  * **Password Recovery Bypass:** Forgot password generating utility outputs the token link in a development alert box on-screen.
  * **OTP Login Bypass:** Generates a 6-digit random code and displays it directly in the UI for ease of local testing without configuring an SMTP/SMS gateway.

---

## 📂 Project Structure

```bash
Study-budy/
├── config/
│   └── database.php         # PDO connection & MySQL configurations
├── api/
│   ├── auth.php             # Session management (Login, Register, Logout, Check)
│   ├── admin.php            # Moderation, Faculty assignment, and Reports APIs
│   ├── documents.php        # Search, Filter, Details, and personal uploads fetching
│   ├── engage.php           # Bookmark, Comment, and Rate APIs
│   ├── upload.php           # Secure file validation, hashing, and storage logic
│   ├── fetch_notifications.php
│   ├── mark_notifications_read.php
│   ├── report_document.php
│   ├── submit_bug.php
│   ├── forgot_password.php  # Dev-bypass password reset token generator
│   ├── reset_password.php
│   ├── request_otp.php      # Dev-bypass OTP generator
│   └── verify_otp.php
├── assets/
│   ├── css/
│   │   └── style.css        # Cream & Espresso variables, Glassmorphism styles
│   └── js/
│       └── main.js          # App state controller, AJAX handlers, Modals
├── uploads/                 # Target folder for user documents (Git-ignored content)
├── database.sql             # SQL database schema and seeds
├── index.php                # Authentication page (Login/Register/OTP/Reset UI)
├── dashboard.php            # Main user layout, search index, and viewer
├── profile.php              # User profile, statistics, and milestone rewards
├── reset.php                # Password resetting form landing page
├── admin.php                # Moderation board UI (Principal & Faculty views)
├── README.md
└── Study_Buddy_SRS.pdf       # Software Requirements Specification document
```

---

## 🎮 Gamification Credits System

Credits are awarded dynamically upon **Faculty/Principal approval** of uploads:

| Tier / Condition | Awarded Credits | Description |
| :--- | :--- | :--- |
| **First 3 Approved Uploads** | **20 Credits / file** | Phase 1 onboarding incentive |
| **Subsequent PPTs / Images** | **2 Credits / file** | Phase 2 flat reward |
| **Subsequent PDFs** | **2 Credits / page** *(Min: 2)* | Phase 2 dynamically calculated based on PDF length |

* **Rewards:** Reaching milestones in multiples of **50 credits** automatically unlocks a virtual ₹10 Google Play Voucher, tracked visually with a dynamic progress bar on the profile screen.

---

## 📥 Installation & Setup Instructions

### Prerequisites
* **XAMPP / WampServer / MAMP** (Apache, PHP 7.4+, MySQL)
* **Web Browser** (Chrome, Firefox, Safari)

### Steps

1. **Database Configuration:**
   * Open your database control panel (e.g. phpMyAdmin).
   * Create a new database named `study_buddy_db`.
   * Import the [database.sql](file:///c:/xampp/htdocs/Study-budy/Study-budy/database.sql) file into `study_buddy_db` to set up all tables, relationships, and default seed users.

2. **Project Placement:**
   * Move the entire `Study-budy` folder into your server's root folder (e.g., `C:\xampp\htdocs\` on Windows, `/var/www/html/` on Linux).

3. **Verify Connectivity:**
   * Open the file [config/database.php](file:///c:/xampp/htdocs/Study-budy/Study-budy/config/database.php).
   * Verify variables `$user`, `$pass`, and `$db` match your local database settings (Default XAMPP username is `root` with no password).

4. **Verify Upload Directory Permissions:**
   * Ensure that the `uploads/` directory has write permissions. If deploying on Linux or macOS, run:
     ```bash
     chmod 0777 uploads
     ```

5. **Start Servers:**
   * Launch your Apache server and MySQL service.
   * Access the login portal in your browser: `http://localhost/Study-budy/`

---

## 🔑 Test Credentials & Accounts

The database comes pre-seeded with multiple test accounts representing different roles and subject specializations.

> [!NOTE]
> The passwords for these accounts generally match their lowercase role or subject abbreviation.

| Username | Role | Email | Password | Assigned Subject / Specialty |
| :--- | :--- | :--- | :--- | :--- |
| `principle` | Principal (Admin) | `principle@example.edu` | `principle` | Global access, managing faculty |
| `faculty_FCSN` | Faculty | `fcsn@example.edu` | `fcsn` | FCSN (Subject specialist) |
| `faculty_FCPP` | Faculty | `fcpp@example.edu` | `fcpp` | FCPP (Subject specialist) |
| `faculty_EP` | Faculty | `ep@example.edu` | `ep` | Physics (Subject specialist) |
| `faculty_EM` | Faculty | `em@example.edu` | `em` | EM-2 (Subject specialist) |
| `student` | Student | `student@example.edu` | `student` | General student user |

*Feel free to register new accounts directly from the landing tab. Newly registered accounts are assigned the `student` role by default.*
