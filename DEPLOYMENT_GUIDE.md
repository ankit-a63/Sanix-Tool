# 🚀 SANIX TOOL — Production Deployment Guide

Complete step-by-step deployment guide for hosting **Sanix Tool** on any standard PHP + MySQL web hosting provider (cPanel, Hostinger, Namecheap, DigitalOcean, AWS, or VPS).

---

## 📋 1. System & Hosting Requirements

### Server & PHP
- **PHP Version**: PHP 8.0, 8.1, 8.2, or 8.3 (PHP 8.2 recommended)
- **PHP Extensions Required**:
  - `pdo_mysql` (Database PDO connectivity)
  - `mbstring` (Multibyte text manipulation)
  - `json` (API JSON encoding & decoding)
  - `session` (PHP session management)
  - `gd` or `imagick` (Optional image header support)
- **Memory Limit**: `128M` or higher
- **Post Max Size / Upload Max Filesize**: `25M` or higher

### Database
- **MySQL Version**: MySQL 5.7 or higher / MariaDB 10.3 or higher
- **Charset & Collation**: `utf8mb4` with `utf8mb4_unicode_ci`

### Web Server
- **Apache 2.4+** or **LiteSpeed** (with `mod_rewrite` enabled), or **Nginx** + `PHP-FPM`
- **SSL Certificate**: Free Let's Encrypt SSL or Cloudflare SSL for `https://`

---

## 🗄️ 2. Database Setup & Creation

### Step 1: Create Database in Hosting Panel
1. Log into your hosting control panel (cPanel, Hostinger hPanel, or DirectAdmin).
2. Go to **MySQL Databases**.
3. Create a new database (e.g., `u12345_sanix_tool`).
4. Create a new database user (e.g., `u12345_sanix_user`) and assign a strong password.
5. Grant **ALL PRIVILEGES** to the user on the newly created database.

### Step 2: Import Database Schema
1. Open **phpMyAdmin** from your control panel.
2. Select your newly created database (`u12345_sanix_tool`).
3. Click the **Import** tab.
4. Choose file: Select `database/database.sql` from your local project.
5. Click **Go** / **Import** to create all required tables (`users`, `tool_usage`, `favorites`, `contact_messages`) and baseline data.

---

## 📤 3. File Upload Guide

### Files & Folders TO Upload:
Upload the following files and folders to your website root directory (`public_html/` or domain root):

```
public_html/
├── admin/
├── api/
├── assets/
├── config/
├── database/
├── includes/
├── temp/
├── tools/
├── uploads/
├── .htaccess
├── about.php
├── contact.php
├── dashboard.php
├── favorites.php
├── index.php
├── login.php
├── logout.php
├── privacy.php
├── profile.php
├── register.php
├── sitemap.php
├── terms.php
└── tools.php
```

### Files & Folders NOT TO Upload:
Do **NOT** upload local development & test files to your production server:

```
❌ Add_Sanix_Hosts.bat      (Local Windows batch script)
❌ scratch/                (Local CDP test scripts & temp outputs)
❌ .env.example            (Reference template only)
❌ DEPLOYMENT_GUIDE.md     (Internal deployment documentation)
❌ README.md               (Internal documentation)
❌ QA_REPORT.md            (QA notes)
```

---

## ⚙️ 4. Production Configuration

### Secure Database Credentials Setup
Configure database credentials securely inside `config/db.php` or using server Environment Variables.

#### Option A: Server Environment Variables (Recommended for VPS / cPanel)
If your hosting supports `SetEnv` in `.htaccess` or server environment variables:

```apache
SetEnv DB_HOST "localhost"
SetEnv DB_USER "u12345_sanix_user"
SetEnv DB_PASS "Your_Strong_Password_Here"
SetEnv DB_NAME "u12345_sanix_tool"
SetEnv DB_PORT "3306"
SetEnv APP_URL "https://yourdomain.com/"
```

#### Option B: Direct Config in `config/db.php`
Edit `config/db.php` on your server and update default fallback values:

```php
if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'u12345_sanix_user');
if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'Your_Strong_Password_Here');
if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'u12345_sanix_tool');
if (!defined('DB_PORT')) define('DB_PORT', getenv('DB_PORT') ?: 3306);
```

> ⚠️ **SECURITY WARNING**: Never put database passwords inside JavaScript, HTML, or publicly accessible files. Keep credentials strictly in `config/db.php` or server environment variables. Direct access to `config/` is automatically blocked by `.htaccess`.

---

## 🔒 5. Folder Permissions & Security

1. Set folder permissions via File Manager or FTP:
   - `uploads/` ➔ `755` (or `775`)
   - `temp/` ➔ `755` (or `775`)
   - All `.php` files ➔ `644`
   - All folders ➔ `755`

2. **Upload & Temp Security**:
   The `uploads/.htaccess` and `temp/.htaccess` files block PHP execution inside upload folders automatically to prevent malicious script uploads.

---

## 🌐 6. SSL / HTTPS Enforcement

Ensure your site is served over `https://`.
If using cPanel / Hostinger:
1. Enable **Free SSL** (Let's Encrypt / ZeroSSL) for your domain.
2. Force HTTPS redirect in your hosting panel or root `.htaccess`.

---

## 🔑 7. Changing Admin Default Password Procedure

The default admin account seeded in `database/database.sql` has the following initial credentials:
- **Default Email**: `admin@sanixtool.com`
- **Default Password**: `Admin@123456`

### How to Change Admin Password Before Going Live:
1. Log into your hosting **phpMyAdmin**.
2. Select your database and open the `users` table.
3. Edit the row where `id = 1` or `email = 'admin@sanixtool.com'`.
4. To set a custom password, run this SQL query replacing `YourNewStrongPassword` with your chosen password:

```sql
UPDATE `users` 
SET `email` = 'your-real-admin-email@yourdomain.com',
    `password` = '$2y$10$YourGeneratedBcryptHashHere'
WHERE `id` = 1;
```

Or generate a secure hash in PHP using `password_hash("YourNewPassword", PASSWORD_DEFAULT)` and paste it into phpMyAdmin.

---

## 🧪 8. Post-Deployment Verification Checklist

```
[ ] 1. HOMEPAGE & ASSETS
       - Open https://yourdomain.com/
       - Verify logo, CSS styles, and JavaScript controls load cleanly.

[ ] 2. TOOLS DIRECTORY
       - Open https://yourdomain.com/tools.php
       - Verify search input and category filter tabs (Image, PDF, Text, Developer, Calculators, File).

[ ] 3. IMAGE TOOLS FUNCTIONALITY
       - Test Image Compressor (tools/image/compressor.php) with a PNG image.
       - Test Image Resizer (tools/image/resizer.php) with presets and aspect ratio lock.
       - Test Background Remover (tools/image/bg-remover.php) and export result.
       - Test ID & Passport Photo Maker (tools/image/id-photo.php) and A4 print sheet preview.
       - Test Image to PDF (tools/image/image-to-pdf.php) and PDF download.

[ ] 4. SITEMAP & ROBOTS
       - Open https://yourdomain.com/sitemap.php and confirm dynamic XML output.
       - Open https://yourdomain.com/robots.txt and confirm Sitemap URL.

[ ] 5. SECURITY VERIFICATION
       - Try opening https://yourdomain.com/config/app.php in browser.
       - Confirm that server returns 403 Forbidden.
```

---

## ❓ 9. Common Errors & Troubleshooting

| Error / Symptom | Possible Cause | Fix |
| :--- | :--- | :--- |
| **500 Internal Server Error** | `.htaccess` syntax mismatch on PHP-FPM servers | Check `uploads/.htaccess` and remove `php_flag` if host forbids it. |
| **Database Connection Error** | Incorrect `DB_USER` / `DB_PASS` in `config/db.php` | Re-check credentials in phpMyAdmin and update `config/db.php`. |
| **Images Fail to Process** | Browser HTML5 Canvas limitation / low memory | Test with standard JPG/PNG files under 20MB. |
| **404 Not Found on Subpages** | `mod_rewrite` disabled on web server | Enable `mod_rewrite` in Apache configuration or contact host. |
