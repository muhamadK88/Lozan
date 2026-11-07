# LOZAN

The official Kurdish (Sorani) website and admin panel for **پەیمانگەی لۆزان**.

Two isolated hosts share one Laravel application:

| Host | Purpose | Authentication |
|------|---------|----------------|
| `lozan.com` | Public news site | None |
| `admin.lozan.com` | Admin panel | Session guard, 2 admins maximum |

## Stack

| Component | Version |
|-----------|---------|
| PHP | 8.4 (NTS) |
| Laravel | 13.38 |
| MySQL | 8.4.9 (portable, no Windows service) |
| Node.js | 24 LTS |
| Vite / Tailwind | 7 / 4 |
| HTML sanitizer | `ezyang/htmlpurifier` |

## Features

- Public homepage and article pages, fully cached to absorb traffic spikes.
- Admin-only article CRUD with scheduled publishing (`published_at`), drafts and search.
- Rich text editor (TinyMCE 7, GPL) with inline image upload and YouTube/Vimeo embeds.
- Uploaded images are resized and re-encoded as **WebP** automatically.
- Every rich-text write is sanitized with HTMLPurifier before it is stored.
- Dedicated `admins` table and `admin` auth guard; no public registration.
- Strict login throttling: 5 attempts/minute per e-mail+IP, 20/minute per IP.
- Admin routes exist only on the admin host; public `/login` returns 404.
- Tailwind CSS v4 with strict RTL layout and a persistent dark/light theme.
- Security headers and `no-store` / `noindex` headers on the admin panel.
- Eager-loaded relationships (`with('admin')`) to prevent N+1 queries.

## Local database (MySQL)

MySQL runs as a **portable server** in `C:\tools\mysql-8.4.9` (no admin rights or
Windows service needed). It also starts automatically at Windows logon via a
shortcut in your Startup folder.

```powershell
# start / stop manually
C:\tools\mysql-8.4.9\start-mysql.cmd
C:\tools\mysql-8.4.9\stop-mysql.cmd
```

| Setting | Value |
|---------|-------|
| Host | `127.0.0.1` |
| Port | `3306` |
| Database | `lozan` |
| User | `root` |
| Password | `root` |
| Data directory | `C:\tools\mysql-8.4.9\data` |
| Error log | `C:\tools\mysql-8.4.9\logs\mysql-error.log` |

> Local development credentials only. Never reuse them in production.

## Local hosts

`.env` is configured for local subdomains:

```dotenv
APP_URL=http://localhost:8000
PUBLIC_DOMAIN=localhost
ADMIN_DOMAIN=admin.localhost
```

`admin.localhost` resolves to `127.0.0.1` automatically on Windows, so no
`hosts` file change is needed locally.

```powershell
php artisan serve            # http://localhost:8000
php artisan serve --port=8000
# Admin panel → http://admin.localhost:8000
```

## Daily workflow

```powershell
composer run dev      # php artisan serve + queue:listen + vite dev
```

Or run the pieces separately:

```powershell
php artisan serve     # http://localhost:8000
npm run dev           # Vite dev server (HMR)
```

## First-time setup (new machine)

```powershell
composer install
npm install
copy .env.example .env             # fill in DB_PASSWORD / APP_KEY / ADMIN_PASSWORD
php artisan key:generate
php artisan migrate
php artisan db:seed                # creates the two admin accounts
php artisan storage:link
npm run build
```

Admin credentials are read from `.env` when seeding:

```dotenv
ADMIN_PASSWORD=change-this-immediately
ADMIN_1_EMAIL=admin1@lozan.com
ADMIN_2_EMAIL=admin2@lozan.com
```

The seeder refuses to run in production unless `ADMIN_PASSWORD` is set.

## Useful commands

```powershell
php artisan migrate                    # run migrations
php artisan migrate:fresh --seed       # rebuild schema + local demo data
php artisan test                       # PHPUnit suite
./vendor/bin/pint                      # code style
php artisan route:list --except-vendor # inspect host-isolated routes
php artisan cache:clear                # clear the public page cache
php artisan db:show --counts           # inspect the MySQL connection and tables
```

## Project layout

- `app/Http/Controllers/Public/` — cached public pages
- `app/Http/Controllers/Admin/` — auth, dashboard, articles, uploads
- `app/Models/Admin.php`, `app/Models/Article.php`
- `app/Support/HtmlSanitizer.php` — strict allow-list for rich text
- `app/Support/ArticleCache.php` — public cache keys and TTL
- `app/Services/ImageService.php` — WebP conversion and resizing
- `routes/web.php` — the two `Route::domain()` groups
- `routes/public.php`, `routes/admin.php`
- `database/migrations/` — `admins`, `articles`, cache, jobs, sessions
- `resources/views/public/`, `resources/views/admin/`
- `resources/css/app.css` — Tailwind entry, RTL typography, dark mode
- `tests/Feature/` — host isolation, auth, XSS, WebP, cache invalidation

## Troubleshooting

- **Site returns 404 on `http://127.0.0.1:8000`.** The public site answers on
  the configured host (`localhost` locally). Use `http://localhost:8000`; in
  local development `127.0.0.1` redirects there automatically.
- **Pages look unstyled or reference an old port.** Public HTML is cached for
  `ARTICLE_CACHE_TTL` seconds. After changing `APP_URL`, the port, or a domain,
  run `php artisan cache:clear` (and hard-refresh the browser).
- **Admin login posts fail locally.** Set `SESSION_SECURE_COOKIE=false` when
  serving over plain HTTP.

## Production checklist

1. Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://lozan.com`.
2. Set `PUBLIC_DOMAIN=lozan.com`, `ADMIN_DOMAIN=admin.lozan.com`.
3. Point both DNS records at the same server and serve them from one Laravel
   virtual host. TLS is required for both hosts.
4. Set `SESSION_SECURE_COOKIE=true`, `SESSION_DOMAIN=null` (host-only cookies).
5. Set `CACHE_STORE=redis` when running more than one application server.
6. Run `php artisan migrate --force`, then create the two admins with
   `php artisan db:seed --force` (with `ADMIN_PASSWORD` set).
7. Run `php artisan storage:link`, `npm run build`, and
   `php artisan optimize` (config/route/view caches).
8. Keep `public/` as the web root only. Never expose `.env`, `storage/` or
   `database/` through the web server.
9. Run `php artisan test` in CI before deploying.
