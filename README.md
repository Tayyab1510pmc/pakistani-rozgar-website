# PakistaniRozgar.com – WordPress Job Portal Setup Package

> A production-ready, one-click WordPress setup package for **PakistaniRozgar.com** — Pakistan's premium online job portal.

---

## 📦 What's in This Repository?

| Path | Purpose |
|------|---------|
| `wp-content/plugins/pakistani-rozgar-core/` | The master WordPress plugin that powers the entire site |
| `docker-compose.yml` | Spin up a full WordPress + MariaDB stack locally in minutes |
| `index.html` | Static prototype / reference HTML (not used by WordPress) |

---

## 🚀 Quick Start – Local Testing with Docker

The fastest way to see your site running locally is with Docker.

### Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed and running.

### Steps

```bash
# 1. Clone this repository
git clone https://github.com/Tayyab1510pmc/pakistani-rozgar-website.git
cd pakistani-rozgar-website

# 2. Start the stack (WordPress + MariaDB + phpMyAdmin)
docker compose up -d

# 3. Wait ~30 seconds for the database to initialise, then open:
#    WordPress:   http://localhost:8080
#    phpMyAdmin:  http://localhost:8081
```

### First-time WordPress Setup (one-time only)

1. Open **http://localhost:8080** in your browser.
2. Follow the WordPress installation wizard:
   - **Site title:** PakistaniRozgar.com
   - **Username / Password:** choose something secure.
3. Log in to **http://localhost:8080/wp-admin**.

### Install Required Plugins

Go to **Plugins → Add New** and install these in order:

| Plugin | Purpose |
|--------|---------|
| **WP Job Manager** | Core job listing engine |
| **Astra** (Theme) | Lightning-fast, Elementor-compatible theme |
| **Elementor** | Drag-and-drop page builder |
| **WP Automatic** *(optional)* | Auto-imports jobs from RSS/APIs |

After installing WP Job Manager, activate it.

### Activate the Core Plugin

Go to **Plugins → Installed Plugins** and activate **Pakistani Rozgar Core**.

On activation the plugin will automatically:

- ✅ Create 5 job categories (Government Jobs, IT & Software, Banking & Finance, Armed Forces, Remote Jobs)
- ✅ Publish 6 high-quality demo job listings across these categories
- ✅ Create the pages: **Home**, **Browse Jobs**, **About Us**, **Contact Us**
- ✅ Set **Home** as the static front page
- ✅ Link **Browse Jobs** to WP Job Manager's archive
- ✅ Create a **Main Menu** navigation and assign all pages to it
- ✅ Inject premium CSS to beautifully style all WP Job Manager listings (white cards, green hover borders, mobile-responsive grid)

### Use the Homepage Shortcode

The **Home** page is pre-populated with the `[pr_homepage_layout]` shortcode.  
This renders a full-featured homepage including:

- A gradient hero section with statistics
- A floating search/filter bar powered by WP Job Manager
- A responsive 5-column category grid with icons
- A "Latest Opportunities" section showing the 6 newest jobs
- A "Browse All Jobs" call-to-action button

To edit the page visually in Elementor:
1. Go to **Pages → Home → Edit with Elementor**.
2. Set the **Page Layout** to **Elementor Full Width** (gear icon, bottom-left).
3. The shortcode widget will render the full layout inside Elementor.

---

## 🌐 Deploying to a Live Web Host (cPanel / Shared Hosting)

### Step 1 – Upload the Plugin

1. Log in to your hosting **cPanel** → **File Manager**.
2. Navigate to `public_html/wp-content/plugins/`.
3. Upload the entire `pakistani-rozgar-core` folder from this repository.
4. Alternatively, zip the folder and use **Plugins → Upload Plugin** in wp-admin.

### Step 2 – Install Required Plugins on Live Site

From **Plugins → Add New**, search for and install:

1. **WP Job Manager** – Core job listing functionality
2. **Astra** – Install from **Appearance → Themes → Add New**
3. **Elementor** – Page builder
4. **WP Automatic** *(optional)* – Pulls job listings from Indeed, LinkedIn RSS, etc.

### Step 3 – Activate Pakistani Rozgar Core

Go to **Plugins → Installed Plugins** → Activate **Pakistani Rozgar Core**.

All pages, categories, demo jobs and the navigation menu are created automatically.

### Step 4 – Configure Astra + Elementor

1. Go to **Appearance → Astra Options** and set your logo/brand colour (`#149253` is the site green).
2. Edit the **Home** page with Elementor and set Page Layout to **Full Width**.
3. Your site is live! 🎉

---

## 🔌 Recommended Plugin Stack

| Plugin | Free / Paid | Why |
|--------|-------------|-----|
| WP Job Manager | Free | Core job listings, search, filters |
| Astra Theme | Free / Pro | Fastest WordPress theme, Elementor-ready |
| Elementor | Free / Pro | Visual drag-and-drop page builder |
| WP Job Manager – Applications | Free | Lets candidates apply via the site |
| WP Job Manager – Alerts | Free | Email job alerts for candidates |
| WP Automatic | Paid | Auto-fetches jobs from RSS/APIs (Rozee.pk, Indeed) |
| WPForms Lite | Free | Contact form on the Contact Us page |
| Yoast SEO | Free | Search engine optimisation |
| WP Super Cache | Free | Performance caching |

---

## 🎨 Branding & Customisation

| Setting | Value |
|---------|-------|
| Primary green | `#149253` |
| Dark green (hover) | `#0d703f` |
| Light green (background tint) | `#e8f5ee` |
| Font stack | Inter, system-ui, sans-serif |
| Border radius (cards) | `14px` |

All CSS lives in `pr_get_premium_css()` inside the plugin file and is easy to customise.

---

## 🐋 Docker Reference

| URL | Service |
|-----|---------|
| http://localhost:8080 | WordPress front-end |
| http://localhost:8080/wp-admin | WordPress admin |
| http://localhost:8081 | phpMyAdmin (DB GUI) |

```bash
# Stop all containers
docker compose down

# Stop and delete all data (fresh start)
docker compose down -v

# View logs
docker compose logs -f wordpress
```

> **Your plugin folder is live-mounted** into the container via a Docker volume bind. Any edits you make to files in `wp-content/plugins/pakistani-rozgar-core/` are reflected instantly — no rebuild needed.

---

## 📋 File Structure

```
pakistani-rozgar-website/
├── docker-compose.yml                          # Local dev stack
├── index.html                                  # Static HTML prototype
├── README.md                                   # This file
└── wp-content/
    └── plugins/
        └── pakistani-rozgar-core/
            └── pakistani-rozgar-core.php       # Master WordPress plugin
```

---

## 📄 License

This project is released under the [GPL-2.0-or-later](https://www.gnu.org/licenses/gpl-2.0.html) licence, consistent with the WordPress ecosystem.

---

*Made with ❤️ for Pakistan's job seekers.*
