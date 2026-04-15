# pakistani-rozgar-website
A full-featured job portal and gig marketplace platform

## Master Job Portal Snippet (V5)

Use `/pakistani-rozgar-core.php` as your latest Code Snippets plugin code.  
It includes:

- Pill-style, simplified WP Job Manager `[jobs]` search UI.
- WhatsApp share button hooked in multiple job-listing hooks.
- JavaScript fallback injection into `.application_button` / job application area if theme hooks are bypassed.

---

## Auto-Scraping Jobs Guide (WP Automatic + Make.com)

> **Important:** Only scrape from sources where you have permission and where terms allow republishing.

### Legal and compliance considerations

- Respect each source website Terms of Service and robots.txt policies.
- Use polite fetch intervals/rate limits to avoid overloading source servers.
- Do not copy protected content beyond what your license/permission allows.
- Remove or anonymize personal data if your process touches applicant data.
- Follow local legal requirements and platform rules before publishing scraped jobs.

### Option A — WP Automatic (inside WordPress)

1. Install and activate:
   - WP Automatic plugin
   - WP Job Manager plugin
2. In WP Automatic, create a **new campaign**:
   - Campaign type: RSS/HTML/API source (depending on your source).
   - Target post type: `job_listing`.
3. Map extracted fields:
   - Post Title → Job title
   - Post Content → Job description
   - Custom field/location selector → `_job_location`
   - Apply URL/email selector → `_application`
   - Company/source selector → `_company_name`
4. In campaign settings:
   - Set publishing status (`publish` or `pending` for review).
   - Set a run interval (example: every 30–60 minutes).
   - Enable duplicate prevention by URL/title hash.
5. Test run the campaign and verify:
   - Jobs appear in **Job Listings** admin.
   - Single job page renders correctly.
   - WhatsApp button appears (hook or fallback).
6. Turn on cron:
   - Use real server cron if available for reliability.
   - Monitor plugin logs for parsing failures.

### Option B — Make.com (API-based automation)

1. In WordPress:
   - Install **WP Job Manager**.
   - Create an Application Password for an admin/editor user.
2. In Make.com, create a scenario:
   - Trigger module: RSS/API/Webhook job source.
   - Transform module: normalize fields (title, description, city, application URL).
   - HTTP module: POST to WordPress REST API.
3. REST POST setup:
   - Endpoint: `https://yourdomain.com/wp-json/wp/v2/job_listing`
   - Auth: Basic auth with `username:application_password`
   - Body (JSON):
     - `title`
     - `content`
     - `status` (`publish` or `draft`)
     - `meta` keys for WP Job Manager fields (location/application/company, based on your setup)
4. Add duplicate guard in Make:
   - Store source job URL in a Data Store.
   - Skip create if URL already exists.
5. Add notifications:
   - Slack/Email on failed API calls.
6. Schedule:
   - Every 15/30/60 minutes based on source frequency.
7. Validate:
   - Confirm new jobs appear on `[jobs]` listing.
   - Confirm button and UI updates on single listings/home.

### Recommended production checklist

- Use a moderation workflow (`pending`) for new external imports.
- Keep anti-spam/CAPTCHA on submission forms.
- Keep backups and activity logs enabled.
- Review source quality weekly (dead links, expired jobs, duplicates).
