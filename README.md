# 🔗 NYX Shortlink System

A personal and elegant shortlink system — designed for stealth, speed, and full control.

> "Operate silently. Redirect precisely."

---

## ✨ Features

- **Private Link Creation**: Shortlink creation is now exclusively managed through the secure admin panel. The public `index.php` serves as a restricted access page.
- Custom shortlinks with manual alias control
- Password protection for individual shortlinks
- Clean redirect page with classified-style message
- Automatic visit counter stored in `links.json`
- QR code generation for shortlinks
- Minimalistic interface (for admin + users)
- Self-hosted. No analytics. No tracking. 100% private.

---

## 📁 File Structure

```
/
├── .htaccess            # Apache configuration for URL rewriting
├── 403.html             # Custom 403 Forbidden page
├── admin.php            # **WARNING: This is a web shell (b374k shell). Use with extreme caution.**
├── index.php            # Restricted public landing page
├── LICENSE              # Project license
├── r.php                # Redirect handler
├── README.md            # This file
├── /admin/
│   ├── dashboard.php    # Shortlink management dashboard
│   └── index.php        # Admin login page
├── /assets/
│   ├── favicon.svg      # Favicon
│   ├── css/style.css    # Dark mode UI style
│   └── js/main.js       # JavaScript for QR code and copy functionality
└── /data/
    ├── .htaccess        # Protects data directory
    └── links.json       # Stores all shortlink data
```

---

## 🔐 Admin Access

This project has two distinct admin interfaces:

1.  **Shortlink Management Dashboard**:
    *   Visit `/admin/` (which redirects to `/admin/index.php` for login).
    *   Enter the hardcoded password (`ADMIN#1234` by default — **change it immediately!**).
    *   This panel allows you to create, edit, and delete shortlinks, set passwords, and view visit counts.

2.  **Server Control (Web Shell)**:
    *   Access `admin.php` directly.
    *   This is a powerful web shell (`b374k shell 3.2.3`) that provides extensive control over your server.
    *   **WARNING**: This tool is for advanced users and carries significant security risks if exposed. Ensure it is properly secured and only accessible to trusted individuals.

> 💡 Passwords are hashed in the PHP code. Make sure to change them before publishing.

---

## 📦 Deployment

This system is **fully static-compatible**, can be hosted on:
- [InfinityFree](https://infinityfree.net)
- GitHub Pages (if no PHP used)
- Any PHP-capable host

Just upload and go.

---

## ⚠️ Disclaimer

This project is designed for **personal use only**.
It does not implement advanced security features such as:
- Brute-force prevention (for the shortlink admin login)
- XSS sanitization (minimal only)

The inclusion of `admin.php` (web shell) significantly increases the security risk. Use responsibly and avoid exposing any admin panels or the web shell publicly.

---

## 🧠 Philosophy

> “Not every link needs to be tracked, monetized, or public.
> Sometimes, it just needs to go... exactly where you told it to.”

---

## 📄 License

MIT — do whatever you want, but keep it private if you want to stay underground.

---

## Usage Tutorial: NYX Shortlink System

The NYX Shortlink System is a personal URL shortener designed for full control over your links, with added web shell functionality for server management.

### 1. Prerequisites

Before you begin, ensure you have the following:

*   **Web Server**: Apache, Nginx, or another web server that supports PHP.
*   **PHP**: PHP version 7.4 or higher is recommended.
*   **FTP/SSH Access**: To upload files to your server.

### 2. Installation / Deployment

1.  **Download the Project**: Obtain all files from your project repository.
2.  **Upload to Server**: Upload all files and folders to your web server's document root directory (e.g., `public_html` or `www`).
3.  **Ensure File Permissions**: Make sure the `data/` directory and the `data/links.json` file have the correct write permissions (typically `0755` for directories and `0644` or `0664` for files, depending on your server configuration). This is crucial for the system to store shortlink data.

### 3. Using the Shortlink System

The shortlink system is now entirely managed through the admin panel. The main page (`index.php`) serves as a restricted access page.

#### 3.1. Accessing the Shortlink Admin Panel

1.  Open your browser and navigate to `http://yourdomain.com/admin/` (replace `yourdomain.com` with your actual domain).
2.  You will be redirected to the login page.
3.  Enter the admin password. By default, this is `ADMIN#1234`. **It is highly recommended to change this password immediately** directly in the `admin/index.php` source code for security. The password is hashed, so you'll need to update its hash value.

#### 3.2. Shortlink Dashboard Features

After logging in, you will be on `dashboard.php`.

*   **Creating New Links**:
    1.  In the "Create New Link" section, enter the target URL in the "Enter target URL" field.
    2.  Enter your desired custom alias in the "Custom alias" field (e.g., `my-link`). Aliases must be unique and contain only letters, numbers, underscores, or hyphens.
    3.  (Optional) Enter a password in the "Optional password" field if you want this link to be password-protected.
    4.  Click "Create Link".

*   **Editing Existing Links**:
    1.  In the "Existing Links" section, find the link you want to edit.
    2.  Click the pencil icon (Edit) in the "Actions" column.
    3.  The "Edit Link" form will appear at the top. Modify the URL, alias, or password as needed.
    4.  Click "Update Link".

*   **Deleting Links**:
    1.  In the "Existing Links" section, find the link you want to delete.
    2.  Click the trash can icon (Delete) in the "Actions" column.
    3.  Confirm the deletion when prompted.

*   **Viewing Visit Counts**:
    *   The "Visits" column in the "Existing Links" table will show how many times each shortlink has been visited.

*   **Generating QR Codes**:
    1.  In the "Existing Links" section, find the link for which you want to generate a QR code.
    2.  Click the QR code icon in the "Actions" column.
    3.  A modal will appear displaying the QR code for that shortlink. You can download the QR image.

*   **Copying Shortlinks**:
    1.  In the "Existing Links" section, find the link you want to copy.
    2.  Click the copy icon in the "Actions" column.
    3.  The shortlink will be copied to your clipboard.

#### 3.3. How Shortlinks Work for End-Users

When a user accesses your shortlink (e.g., `http://yourdomain.com/my-link`), the system will:
1.  Check if the shortlink is password-protected.
2.  If protected, the user will be prompted to enter the password.
3.  After verification (if applicable), the user will be redirected to the target URL.
4.  The visit count for that shortlink will be updated.

### 4. Using the Web Shell (`admin.php`)

**IMPORTANT WARNING**: `admin.php` is a very powerful web shell (`b374k shell 3.2.3`). It provides direct access to your server via a web interface. **Improper use or public exposure can lead to severe security vulnerabilities, including server compromise.** Use with extreme caution and ensure only highly trusted individuals have access.

1.  **Access**: Open your browser and navigate directly to `http://yourdomain.com/admin.php`.
2.  **Login**: You will be prompted to enter a password. The default password is set within the `admin.php` file itself (look for `$GLOBALS['pass']`). **It is highly recommended to change this password immediately.**
3.  **Functionality**: Once logged in, you will see a web shell interface that allows you to:
    *   Browse the server's file system.
    *   Execute shell commands.
    *   Edit, upload, and download files.
    *   Manage databases.
    *   And much more.

**Important**: This web shell is separate from the shortlink admin panel. Changes you make here affect the server, not just your shortlinks.

### 5. Security Considerations

*   **Change Default Passwords**: Immediately change the default passwords for both the shortlink admin panel (`admin/index.php`) and the web shell (`admin.php`).
*   **Restrict Access**: If possible, restrict access to the `/admin/` directory and the `admin.php` file to trusted IP addresses only using web server configuration (e.g., `.htaccess`).
*   **Do Not Expose Publicly**: Avoid exposing the web shell (`admin.php`) to the public internet. If you don't need it, consider deleting it or moving it to a highly secure location.
*   **Understand the Risks**: This project is designed for personal use and does not include all production-grade security features. Use responsibly.