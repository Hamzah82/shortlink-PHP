# 🔗 NYX Shortlink System

A personal and elegant shortlink system — designed for stealth, speed, and full control.

> "Operate silently. Redirect precisely."

---

## ✨ Features

- Custom shortlinks with manual alias control
- Clean redirect page with classified-style message
- Automatic visit counter stored in `links.json`
- Minimalistic interface (for admin + users)
- Self-hosted. No analytics. No tracking. 100% private.

---

## 📁 File Structure

```
/
├── index.php            # Public landing page (personal use only)
├── r.php                # Redirect handler
├── shorten.php          # Process shortlink (public form)
├── /admin/
│   ├── dashboard.php    # Admin panel to add/edit/delete links
│   └── login.php        # Simple login for admin access
├── /data/
│   └── links.json       # Stores all shortlink data
└── /assets/
    └── css/style.css    # Dark mode UI style
```

---

## 🔐 Admin Access

To access the admin dashboard:
1. Visit `/admin/login.php`
2. Enter the hardcoded password (`ADMIN#1234` by default — **change it!**)

> 💡 Password is stored directly in the PHP code. Make sure to change it before publishing.

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
- CSRF protection
- Brute-force prevention
- XSS sanitization (minimal only)

Use responsibly and avoid exposing your admin panel publicly.

---

## 🧠 Philosophy

> “Not every link needs to be tracked, monetized, or public.  
> Sometimes, it just needs to go... exactly where you told it to.”

---

## 📄 License

MIT — do whatever you want, but keep it private if you want to stay underground.
