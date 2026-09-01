# Member-only exchange rates

For the exact live-file manifest and cPanel rollout steps, see
[the cPanel deployment checklist](cpanel-live-deployment.md).

`/exchange-rates/` is protected by the KAAO theme's `kaao_view_exchange_rates`
capability. Visitors who are not signed in are redirected to the standard
KAAO **Member Login** endpoint and returned to the requested page after a
successful login. The endpoint is rendered by the theme at `/member-login/` and
does not require a separately created WordPress page or a selected page template.
Signed-in users without the capability receive a 403 response. The same
restriction is applied to the WordPress REST page endpoint, so the page body
cannot be obtained through `/wp-json/`.

## First-time production setup

No WordPress page needs to be created for the custom login. Once the theme files
are deployed, the theme serves `/member-login/` with the standard KAAO header
and footer. This avoids accidentally showing `wp-login.php` when a WordPress
page is missing or has the wrong template selected.

Run this command from the WordPress installation directory, replacing the
email address with an address controlled by KAAO. It creates the shared
`kaaomember` WordPress account, grants the capability, and stores only a
WordPress password hash in the database.

```bash
KAAO_MEMBER_PASSWORD='@kaaomember2026' wp kaao exchange-member provision --email=members@kaao.co.ke
```

Do **not** add the password to `wp-config.php`, the theme, a shell history, or
source control. Prefer injecting `KAAO_MEMBER_PASSWORD` through the deployment
secret manager. If the shell environment cannot receive secrets, have a WordPress
administrator create the `kaaomember` account in **Users → Add New**, assign it
the Subscriber role, then run the command with a temporary secret to grant the
capability.

## Password rotation

Reset the shared account's password and retain its access capability with:

```bash
KAAO_MEMBER_PASSWORD='new-long-random-password' wp kaao exchange-member provision --email=members@kaao.co.ke --reset-password=true
```

Use a long, unique password after the initial rollout. A single shared account
cannot identify an individual member or be revoked for one person, so individual
WordPress accounts with the same capability are recommended if auditability is
required.

## Operational checks

1. In a private browser window, visit `https://kaao.co.ke/exchange-rates/` and
   verify that WordPress sends you to login.
2. Sign in as `kaaomember` and verify that the page and table load.
3. While signed out, request the page's REST endpoint (for example,
   `wp-json/wp/v2/pages/<page-id>`) and verify it returns HTTP 401 rather than
   page content.
4. Configure any CDN/page cache to bypass cache for this URL and to respect
   WordPress login cookies. The application also sends no-cache and noindex
   headers, but an edge cache must be configured to honour them.
