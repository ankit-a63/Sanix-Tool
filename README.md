# SANIX TOOL — Production-Quality All-in-One Online Tools Platform

> **Brand Tagline:** One Platform. Every Tool.  
> **Local VirtualHost URL:** `http://sanix-tool.local/`  
> **Database:** `sanix_tool` (MariaDB / MySQL)

---

## 🌟 Overview

**SANIX TOOL** is a professional, production-grade All-in-One Online Tools platform built with high visual standards, privacy-first browser processing, and responsive design.

### Key Highlights
* **100% Working Tools:** Zero placeholders, fake progress bars, or broken buttons. Every tool has been implemented and tested with real browser-side and server-side processing.
* **Privacy First:** Client-side processing using HTML5 Canvas, Web Crypto API, `pdf-lib`, `JSZip`, and `Cropper.js` ensures your files and text remain private on your machine.
* **Interactive Assistant (SANI):** Original anime-style assistant with dynamic eye tracking (follows cursor), natural random blinking, emotion morphing (`idle`, `happy`, `thinking`, `surprised`, `success`, `error`), and speech notifications.
* **Complete Design System:** Dark theme by default (Futuristic Obsidian) + Polished Light theme with dynamic theme toggle (`[data-theme="light"]`), CSS variables, glassmorphism, custom toast alerts, and ARIA accessibility.
* **User Accounts & Database:** Authentication system with session security, `password_hash()`, CSRF protection, tool usage metrics tracking, favorites management (MySQL + LocalStorage sync), and an Admin Control Center.

---

## 🔧 XAMPP VirtualHost & Database Configuration Setup

To run **SANIX TOOL** locally at `http://sanix-tool.local/`, follow these simple configuration steps:

### 1. Edit Apache VirtualHost Configuration File
Open `C:\xampp\apache\conf\extra\httpd-vhosts.conf` in a text editor and add the following VirtualHost block at the bottom:

```apache
<VirtualHost *:80>
    ServerName sanix-tool.local
    DocumentRoot "E:/Sanni/Sanix Tool"
    <Directory "E:/Sanni/Sanix Tool">
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog "logs/sanix-tool-error.log"
    CustomLog "logs/sanix-tool-access.log" combined
</VirtualHost>
```

### 2. Edit Windows Hosts File
Open Notepad as **Administrator**, open `C:\Windows\System32\drivers\etc\hosts`, and append the following line:

```hosts
127.0.0.1       sanix-tool.local
```

### 3. Database Import
1. Start **MySQL** in XAMPP Control Panel.
2. Open phpMyAdmin (`http://localhost/phpmyadmin/`) or MySQL CLI.
3. Import the SQL schema file:
   `E:\Sanni\Sanix Tool\database\database.sql`

This creates the database `sanix_tool` with four tables: `users`, `tool_usage`, `favorites`, and `contact_messages`.

### 4. Restart Apache Service
Restart Apache in XAMPP Control Panel and visit:
👉 **[http://sanix-tool.local/](http://sanix-tool.local/)**

---

## 🔐 Admin Account Credentials

| Attribute | Value |
|---|---|
| **Email** | `admin@sanix-tool.local` |
| **Password** | `Admin@123456` |
| **Admin Panel URL** | `http://sanix-tool.local/admin/index.php` |

---

## 📁 Complete Directory Architecture

```
E:\Sanni\Sanix Tool\
├── config/
│   ├── app.php                # App constants, URLs, base pathing
│   ├── db.php                 # PDO database connection
│   └── security.php           # CSRF, session protection, auth helpers
├── database/
│   └── database.sql           # Schema SQL import file
├── includes/
│   ├── header.php             # HTML head, CSS imports, loading screen
│   ├── navbar.php             # Navbar branding & links
│   ├── footer.php             # Footer links & JS script imports
│   ├── sani.php               # SANI assistant floating widget markup
│   └── toast.php              # Toast notification engine
├── assets/
│   ├── css/
│   │   ├── style.css          # Main layout & component system
│   │   ├── themes.css         # Dark & Light CSS variables
│   │   ├── sani.css           # Sani assistant animations & speech bubble
│   │   ├── tools.css          # Tool workspaces & drag-and-drop dropzones
│   │   └── admin.css          # Admin panel layout & tables
│   ├── js/
│   │   ├── app.js             # Global initialization & clipboard helpers
│   │   ├── sani.js            # Sani assistant eye tracking & emotion morphing
│   │   ├── theme.js           # Theme switcher (Dark / Light)
│   │   ├── toast.js           # Toast alert system
│   │   ├── favorites.js       # Favorites sync (Database + LocalStorage)
│   │   └── search.js          # Live search & shortcut (Ctrl+K)
│   ├── lib/
│   │   ├── pdf-lib.min.js     # Standalone offline PDF manipulation library
│   │   ├── jszip.min.js       # Standalone offline ZIP archiving library
│   │   ├── cropper.min.js     # Standalone offline image cropping JS
│   │   └── cropper.min.css    # Standalone Cropper.js stylesheet
│   └── images/
│       ├── branding/          # SVG logos, marks, favicons, OG preview
│       └── sani/              # Sani anime assistant SVG vector graphics
├── tools/
│   ├── image/
│   │   ├── compressor.php     # Browser Canvas Image Compressor (JPG, PNG, WEBP)
│   │   ├── resizer.php        # Image Resizer with Aspect Ratio locking
│   │   ├── cropper.php        # Cropper.js Image Cropper with presets (1:1, 16:9)
│   │   ├── converter.php      # Image Format Converter (JPG, PNG, WEBP)
│   │   ├── rotator.php        # Image Rotator (90/180/270) & Mirror Flipper
│   │   └── image-to-pdf.php   # Multi-Image to PDF Generator (pdf-lib)
│   ├── pdf/
│   │   ├── merger.php         # Multi-PDF Merger (pdf-lib)
│   │   ├── splitter.php       # PDF Page Range Extractor / Splitter
│   │   ├── rotator.php        # PDF Page Rotator
│   │   └── metadata.php       # PDF Structure & Metadata Inspector
│   ├── text/
│   │   ├── counter.php        # Real-time Word, Character, Sentence & Reading Time
│   │   ├── case-converter.php # UPPERCASE, lowercase, Title & Sentence case
│   │   ├── line-tools.php     # Duplicate Line Remover & Line Sorter
│   │   ├── cleaner.php        # Text Cleaner, Space Stripper & Find/Replace
│   │   ├── slug-generator.php # SEO Text Slug Generator
│   │   └── lorem-ipsum.php    # Lorem Ipsum Placeholder Text Generator
│   ├── developer/
│   │   ├── json.php           # JSON Formatter, Syntax Validator & Minifier
│   │   ├── base64.php         # Base64 Encoder & Decoder
│   │   ├── url.php            # URL Component Encoder & Decoder
│   │   ├── uuid.php           # Cryptographic UUID v4 Generator
│   │   ├── hash.php           # SHA-256, SHA-512, SHA-1 Cryptographic Hash Generator
│   │   ├── timestamp.php      # Epoch Unix Timestamp Converter
│   │   ├── color.php          # Color Code Converter (HEX, RGB, HSL, CMYK)
│   │   └── regex.php          # Regular Expression Tester & Match Debugger
│   ├── calculator/
│   │   ├── basic.php          # Scientific & Basic Visual Keypad Calculator
│   │   ├── percentage.php     # Percentage & Ratio Calculator
│   │   ├── age.php            # Exact Age & Total Days Lived Calculator
│   │   ├── emi.php            # Loan EMI & Interest Breakdown Calculator
│   │   └── unit.php           # Data Storage (Bytes, KB, MB, GB, TB) Converter
│   └── file/
│       ├── info.php           # File Metadata, MIME Type & Byte Analyzer
│       ├── zip.php            # Browser-side ZIP Archive Generator (JSZip)
│       └── hash.php           # Binary File Checksum Calculator (SHA-256)
├── api/
│   ├── favorites.php          # AJAX Favorites Toggle API
│   ├── track_usage.php        # AJAX Tool Usage Tracker API
│   └── contact.php            # AJAX Contact Message Submission API
├── admin/
│   ├── index.php              # Admin Overview Dashboard
│   ├── users.php              # User Account & Role Management
│   ├── tools.php              # Tool Popularity Analytics
│   └── messages.php           # Contact Form Messages Management
├── uploads/                   # Uploads directory (PHP execution blocked)
├── temp/                      # Temporary files directory (Auto-cleaned)
├── index.php                  # Landing page hero & popular tools
├── tools.php                  # Directory page for all tools
├── favorites.php              # User favorites grid
├── dashboard.php              # Logged-in user dashboard
├── profile.php                # Profile editor
├── login.php                  # User login page
├── register.php               # Account registration page
├── logout.php                 # Session logout
├── about.php                  # About platform page
├── contact.php                # Contact form page
├── privacy.php                # Privacy policy
├── terms.php                  # Terms of service
├── .htaccess                  # Root Apache rewrite & security headers
├── robots.txt                 # Search engine directives
├── sitemap.xml                # SEO XML Sitemap
└── README.md                  # Complete platform documentation
```

---

## 🛠️ Complete Guide to Tools

### 1. Image Tools (`/tools/image/`)
* **Image Compressor:** Adjust quality (1-100%), target format (JPG, PNG, WEBP), view original vs compressed size in real-time, see compression ratio %, and download result.
* **Image Resizer:** Custom width/height input in pixels, aspect ratio lock toggle, live Canvas rendering, and 1-click download.
* **Image Cropper:** Interactive crop handles using local `Cropper.js`, aspect ratio presets (1:1, 16:9, 4:3), 90° rotation, crop & download PNG.
* **Image Converter:** Convert between JPG, PNG, and WEBP formats with transparency fallback.
* **Image Rotator & Flipper:** Rotate by 90°, 180°, 270° or flip horizontally/vertically.
* **Image to PDF:** Drag & drop multiple images, reorder list (move up/down), set page size (A4, Letter, Fit), orientation (Portrait/Landscape), margin, and export PDF.

### 2. PDF Tools (`/tools/pdf/`)
* **PDF Merger:** Drag & drop multiple PDF files, reorder, and combine into a single clean PDF document using `pdf-lib`.
* **PDF Splitter:** Enter page range (e.g., `1-3, 5, 7-10`) to extract selected pages into a new PDF document.
* **PDF Page Rotator:** Rotate all pages by 90°, 180°, or 270° and download modified PDF.
* **PDF Metadata Viewer:** Inspect document title, author, subject, creator, producer, and page count.

### 3. Text Tools (`/tools/text/`)
* **Word & Character Counter:** Real-time word count, character count (with/without spaces), sentence count, paragraph count, and reading time.
* **Case Converter:** Transform text to UPPERCASE, lowercase, Title Case, Sentence case, Capitalized Case, and aLtErNaTiNg cAsE.
* **Duplicate Line Remover & Sorter:** Deduplicate lines, sort alphabetically A-Z / Z-A, or sort by line length.
* **Text Cleaner & Find Replace:** Strip extra spaces, remove HTML tags, strip line breaks, and search & replace text.
* **SEO Slug Generator:** Create clean URL slugs from headlines with custom separators (`-`, `_`, `.`).
* **Lorem Ipsum Generator:** Generate placeholder dummy text by paragraphs, sentences, or words.

### 4. Developer Tools (`/tools/developer/`)
* **JSON Formatter & Validator:** Prettify JSON with custom indentation (2/4 spaces/tab), minify, validate syntax, view syntax error line/column, and download `.json`.
* **Base64 Encoder & Decoder:** Encode plain text to Base64 or decode Base64 back to original text.
* **URL Encoder & Decoder:** Standard `encodeURIComponent` & `decodeURIComponent` for URI query string parameters.
* **UUID v4 Generator:** Generate cryptographically secure UUID v4 strings in single or bulk batches.
* **Cryptographic Hash Generator:** Compute SHA-256, SHA-512, and SHA-1 hashes using the Web Crypto API.
* **Unix Timestamp Converter:** Convert Unix epoch timestamps (seconds/milliseconds) to human-readable UTC/Local dates.
* **Color Code Converter:** HEX, RGB, HSL, and CMYK color conversions with visual color picker.
* **Regular Expression Tester:** Test Regex pattern matches against sample strings with live group capture results.

### 5. Calculators (`/tools/calculator/`)
* **Scientific Calculator:** Visual keypad calculator with expression history log, trig functions, square root, pi, exponent, and keyboard input support.
* **Percentage Calculator:** Calculate X% of Y, and X is what % of Y.
* **Age Calculator:** Calculate exact age in years, months, days, total days lived, and next birthday countdown.
* **Loan EMI Calculator:** Calculate monthly EMI, total interest payable, and total loan payment breakdown.
* **Data Storage Converter:** Convert data storage values between Bytes, KB, MB, GB, and TB.

### 6. File Utilities (`/tools/file/`)
* **File Info & Type Checker:** Inspect file MIME type, exact byte size, file extension, and last modified timestamp.
* **ZIP Creator & Archive Tool:** Add multiple files and compress into a `.zip` archive client-side using `JSZip`.
* **File Checksum Generator:** Calculate SHA-256 and SHA-1 file checksums via Web Crypto API slice stream.

---

## 🔒 Security Best Practices Implemented

* **Prepared SQL Statements:** All database queries use PDO prepared statements to eliminate SQL injection risks.
* **Password Hashing:** Passwords are hashed using `password_hash()` with `PASSWORD_BCRYPT`.
* **CSRF Token Validation:** Forms and AJAX POST requests include cryptographically secure CSRF tokens (`validateCsrfToken()`).
* **Output Escaping:** All dynamic user values rendered in HTML use `e()` (`htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`) to prevent Cross-Site Scripting (XSS).
* **Uploads Protection:** `uploads/` and `temp/` folders contain `.htaccess` files disabling script execution (`php_flag engine off`).

---

## 🎨 Design System & Micro-Interactions

* **Themes:** Toggles between obsidian dark mode (`#0b0f19`) and elevated light mode (`#f1f5f9`) via `assets/js/theme.js`.
* **Custom Toast Notifications:** Floating alerts rendered dynamically via `showToast(message, type, duration)`.
* **Interactive Sani Assistant:** Vector SVG assistant that tracks mouse movement across the document, blinks naturally, morphs mouth & eyebrow paths based on emotion, and displays context-aware speech bubbles.
* **Keyboard Shortcuts:** Press `Ctrl + K` or `/` anywhere on the site to immediately focus the live tool search bar.

---

## 🏁 Verification & Testing Instructions

1. Open **[http://sanix-tool.local/](http://sanix-tool.local/)** in your browser.
2. Click on **Log In** and sign in with `admin@sanix-tool.local` / `Admin@123456`.
3. Test **Image Compressor**: Upload an image, adjust quality slider, download compressed image.
4. Test **PDF Merger**: Select two PDF files, reorder, click **Merge PDFs & Download**.
5. Test **JSON Formatter**: Paste raw JSON, click **Format JSON** or **Minify JSON**.
6. Test **Scientific Calculator**: Perform calculations via visual keypad or keyboard.
7. Click the heart icon on any tool card to verify real-time database favorite synchronization!
8. Access **Admin Panel** at `http://sanix-tool.local/admin/index.php` to view live platform metrics.

---

**SANIX TOOL** is complete, fully functional, and ready for production deployment!
