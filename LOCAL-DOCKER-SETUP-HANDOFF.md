# Local Moodle Docker Setup Handoff

This document records the local Moodle setup and code changes made during this session. It is intended to make the changes easy to reapply after a revert.

## Goal and original issues

- The checkout is Moodle **3.11.4**. The installed XAMPP PHP 8.2 runtime emitted deprecated return-type and dynamic-property notices from older Moodle code.
- The local PostgreSQL 18 service initially refused connections, then rejected the configured Moodle password.
- Internal links in the downloaded site source pointed to the live Moodle host, so local navigation could leave the local copy.
- A custom front-page include assumed the wrong document root, and a custom calendar query failed against the restored database.

No Moodle core files were edited to silence PHP notices. The compatible local runtime is PHP 7.3 in Docker; keep XAMPP stopped and open `http://localhost:3010` when using this setup.

## Current local runtime

- Moodle web container: `immast-local-moodle-app`
- PHP runtime: PHP 7.3.33 with `pgsql` and `pdo_pgsql`
- PostgreSQL container: `immast-local-postgres`, PostgreSQL 14
- Host ports: Moodle `127.0.0.1:3010`; PostgreSQL `127.0.0.1:5433`
- Moodle data directory: existing `C:\moodledata`, bind-mounted into the web container at `/var/www/moodledata`
- Database: the newer supplied custom-format backup, `backup_20260909_234351.dump`, was restored into a newly created local database volume named `immast-local-moodle-pgdata`. The existing Windows PostgreSQL 18 database was not used for this restore.
- The Moodle container shares the external Docker network `immast-local-network`; the database container is reachable from it as `db`.

The local Compose file manages the web app. It expects the existing PostgreSQL container and external Docker network to remain available. Both containers were configured to restart unless stopped.

## Reapply or start the app

In PowerShell, run this from any directory:

```powershell
docker compose -f C:\xampp\htdocs\immast\docker-compose.local.yml up -d --build
```

Then open:

```text
http://localhost:3010
```

If the database container was manually stopped, start it first:

```powershell
docker start immast-local-postgres
docker compose -f C:\xampp\htdocs\immast\docker-compose.local.yml up -d --build
```

To stop only the Compose-managed Moodle web app:

```powershell
docker compose -f C:\xampp\htdocs\immast\docker-compose.local.yml down
```

Do not use `down -v` or remove the local PostgreSQL volume if you want to keep the restored database. `docker-compose.local.yml` is local-only; do not use it as the AWS deployment Compose file.

## Local AWS SES SMTP configuration

The PHP 7.4 container on port 8081 reads `SMTP_HOST`, `SMTP_PORT`,
`SMTP_SECURE`, `SMTP_FROM`, `SMTP_USERNAME`, and `SMTP_PASSWORD` from the
project-root `.env` file through Docker Compose interpolation. `.env` is
gitignored; `.env.example` contains blank credential fields. Keep actual
credentials only in the local `.env`, protect access to that file, and never
put credentials in shell command text or committed files. Docker administrators
can inspect container environment variables.

The defaults are the Mumbai SES endpoint, port 587, and
`no-reply@sunpharma.com`. Moodle uses STARTTLS (`smtpsecure = tls`) on port 587;
`SMTP_SECURE=false` means no implicit TLS, not that STARTTLS should be disabled.
Both SMTP credentials must be configured before recreating the PHP 7.4
container. The sender must be verified for sending in SES.

## Source changes made

### Environment-aware configuration — `config.php`

- Windows selects `C:/moodledata` and the local PostgreSQL port `5433` by default.
- `MOODLE_URL`, `MOODLE_DOMAIN`, `DB_PORT`, `MOODLE_SSLPROXY`, `MOODLE_COOKIE_DOMAIN`, and `MOODLE_DATAROOT` can override the appropriate settings.
- Local loopback Moodle instances can receive SES SMTP settings through `MOODLE_SMTP_*`; authentication settings are applied only when both username and password are present.
- Windows defaults the Moodle URL to `http://localhost/immast`, disables proxy SSL locally, and leaves the local cookie domain empty.
- The Linux/AWS fallback Moodle URL remains `https://courses.immast.org`; proxy SSL remains enabled by default; the production dataroot fallback remains `/var/www/courses.immast.org/private/moodledata`.
- Startup checks that the selected dataroot exists and is writable.

### Docker runtime — `Dockerfile`, `docker-compose.local.yml`

- The project Dockerfile already used PHP 7.3. Its Debian package source URLs had begun returning 404, so the build now uses Debian archive mirrors.
- `docker-compose.local.yml` starts the PHP 7.3 Moodle web app on localhost port 3010, mounts the project and existing `C:/moodledata`, and connects to the existing local PostgreSQL container through `immast-local-network`.
- The local Compose file sets the local Moodle URL, disables proxy SSL for local HTTP, and sets the cookie domain to `localhost`.

### Local and production navigation — theme and API code

- `theme/immast/templates/header.mustache` and `footer.mustache` now use Moodle’s configured base URL for internal course links.
- `theme/immast/javascript/app.js`, `enrol/ccavenue/lib.php`, `enrol/ccavenue/enrol.html`, and `my/reports.php` now build Moodle API URLs from the active Moodle base URL instead of hard-coding the live course host.
- `theme/immast/layout/includes/header.php` and `includes/footer.php` choose Home links based on the Moodle host. Local hosts use the local Moodle homepage; production keeps the existing marketing homepage `https://immast.org/`.
- The local Virtual Tour link falls back to the local Moodle homepage because the Virtual Tour page/assets are not present in this checkout. Production retains `https://www.immast.org/virtual-tour/?linkid=virtual-tour`.
- `error/404.html` uses a relative homepage link so it stays within the current site.
- Links to the separate marketing site (for example About Us pages), its images, and other external websites remain external. Those pages are not included in this Moodle checkout.

### Local runtime fixes

- `theme/immast/layout/frontpage.php` now loads `immast-custom/course_calendar_func.php` relative to the project tree instead of assuming the project is directly under Apache’s document root.
- `immast-custom/course_calendar_func.php` includes `cat.sortorder` in the category query’s `GROUP BY`, fixing the SQL error seen with the restored local database.

## Files changed

- `config.php`
- `Dockerfile`
- `docker-compose.local.yml` (new, local only)
- `error/404.html`
- `enrol/ccavenue/enrol.html`
- `enrol/ccavenue/lib.php`
- `immast-custom/course_calendar_func.php`
- `my/reports.php`
- `theme/immast/javascript/app.js`
- `theme/immast/layout/frontpage.php`
- `theme/immast/layout/includes/footer.php`
- `theme/immast/layout/includes/header.php`
- `theme/immast/templates/footer.mustache`
- `theme/immast/templates/header.mustache`

## Verification performed

- Docker Compose rebuilt and started the local app successfully.
- PHP 7.3.33 was active in the Moodle web container, with PostgreSQL extensions loaded.
- `/var/www/moodledata` was writable from the container.
- The local Moodle homepage returned HTTP 200 with title `IMMAST eLearning Portal`.
- The rendered local homepage had zero PHP Deprecated notices and no database/include error.
- Rendered Moodle course links and Home/Virtual Tour links were checked to resolve to localhost in the local environment.
- PHP lint and `git diff --check` passed for changed files checked during the session.

## AWS deployment review before pushing

The URL branching is designed to retain AWS defaults, but this session did **not** test against the live AWS environment. Before deployment, verify:

1. AWS still supplies the intended `MOODLE_DOMAIN`/`MOODLE_URL`, database environment, and production `MOODLE_DATAROOT`.
2. The AWS build pipeline can build the updated PHP 7.3 Dockerfile using the archived Debian package mirrors.
3. The AWS database accepts the calendar query with `cat.sortorder` added to its `GROUP BY`.
4. The `docker-compose.local.yml` file is not used for production deployment.

Never commit database passwords or tunnel tokens into this handoff. This file intentionally contains no credentials.
