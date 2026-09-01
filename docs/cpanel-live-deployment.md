# cPanel live deployment checklist — member-only exchange rates

Use this checklist when copying the feature directly into the live KAAO
WordPress installation through **cPanel → File Manager**. All paths below are
relative to the WordPress document root (commonly `public_html`). Back up every
existing file before replacing it, and use the File Manager editor's **Save**
button only after confirming the full file was pasted without truncation.

## Files to upload or replace

### Modified existing file

| Live path | Action | Why it is needed |
| --- | --- | --- |
| `wp-content/themes/kaao/functions.php` | **Replace** with the updated version. | Loads the member-access module with the rest of the KAAO theme modules. |
| `wp-content/themes/kaao/assets/css/main.css` | **Replace** with the updated version. | Supplies the spacing, responsive padding, form controls, focus state, and card styling for the branded Member Login screen. |

### New files to create

| Live path | Action | Why it is needed |
| --- | --- | --- |
| `wp-content/themes/kaao/inc/member-access.php` | **Create/upload**. | Enforces member-only access to `/exchange-rates/`, authenticates the custom form, prevents REST exposure, and sends cache/indexing protection headers. |
| `wp-content/themes/kaao/page-templates/member-login.php` | **Create/upload**. | Provides the branded Member Login page using the existing KAAO header and footer. |

The following files are documentation only. They do **not** need to be copied
to production for the feature to work, but may be retained with the deployment
archive for operational reference:

| Repository path | Purpose |
| --- | --- |
| `docs/member-exchange-access.md` | Provisioning, password rotation, cache, and verification guidance. |
| `docs/cpanel-live-deployment.md` | This cPanel file manifest and rollout checklist. |

## cPanel deployment steps

1. In cPanel, open **File Manager** and navigate to
   `public_html/wp-content/themes/kaao/` (adjust `public_html` if WordPress is
   installed in a subdirectory).
2. Download backups of `functions.php` and `assets/css/main.css`. Then replace
   both files with the updated versions from this release.
3. In `inc/`, upload/create `member-access.php` from this release. In
   `page-templates/`, upload/create `member-login.php` from this release.
4. Ensure uploaded PHP files use permissions `0644` and directories use `0755`.
   Do not make PHP files writable by everyone (`0777`).
5. Do not create a WordPress page for `member-login`. The uploaded theme serves
   `/member-login/` directly, so it cannot fall back to the default WordPress
   login screen because of an unpublished page or an incorrect template choice.
6. In WordPress Admin, go to **Users → Add New** and create the shared account:
   username `kaaomember`, the required password, a KAAO-controlled email address,
   and the **Subscriber** role.
7. The capability must be granted once. Run the documented WP-CLI provisioning
   command from a secure server terminal if it is available. If cPanel does not
   provide SSH/WP-CLI, ask the hosting provider or site developer to run the
   command in `docs/member-exchange-access.md`; do not edit the database or add
   a password to PHP files.
8. Purge LiteSpeed/WordPress/CDN caches. Configure any page cache to bypass
   `/exchange-rates/` and respect WordPress login cookies.

## Post-deployment verification

1. Open `https://kaao.co.ke/exchange-rates/` in an incognito/private browser.
   It must redirect to `https://kaao.co.ke/member-login/`.
2. Sign in using `kaaomember`. It must return to the exchange-rates page and
   show the table.
3. Sign out, then revisit the exchange-rates URL. The table must not appear.
4. While signed out, visit the page's REST endpoint
   (`/wp-json/wp/v2/pages/<page-id>`). It must return HTTP 401 and no page body.
5. If the old WordPress login page appears instead of the branded page, confirm
   that both new theme files were uploaded to the active `kaao` theme, then
   purge the WordPress/LiteSpeed/CDN caches.

## Do not upload or change

* Do not change `wp-config.php` for this feature.
* Do not put the shared password in `functions.php`, `member-access.php`, or
  `member-login.php`.
* Do not replace the exchange-rates template; its content remains unchanged and
  is protected by the new access module.
