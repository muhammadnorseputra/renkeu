---
name: codeigniter3
description: "CodeIgniter 3 (CI3) development for the emonev project. Use when: writing or editing PHP controllers, models, views, helpers, routes, or config in this repo; adding CRUD features; working with sessions, auth, datatables, uploads, exports (PDF/Excel), REST API endpoints, or the app/ and api/ controller folders. Covers project conventions: autoloaded helpers/models, custom helper functions (cek_session, privilages, encrypt_url, getSetting), table naming, and view layout."
---

# CodeIgniter 3 — emonev Project Conventions

## Project Stack

- **Framework:** CodeIgniter 3 (PHP >= 5.3.7, platform pinned to 7.3 in composer.json)
- **DB:** MySQL via `mysqli` driver. Tables prefixed `t_` (e.g. `t_users`).
- **Composer deps:** `dompdf/dompdf`, `chriskacerguis/codeigniter-restserver`, `rakit/validation`, `phpoffice/phpspreadsheet`, `chillerlan/php-qrcode`, `vlucas/phpdotenv`
- **Frontend:** Gentelella-style backend template in `template/backend/`, assets in `template/assets/`
- **Entry:** `index.php` at root; `index_page` is empty (clean URLs via `.htaccess`)

## Folder Map

| Path                           | Purpose                                                                                                                                                                                                        |
| ------------------------------ | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `application/controllers/`     | `Login.php`, `Welcome.php`, `Settings.php`                                                                                                                                                                     |
| `application/controllers/app/` | Authenticated app controllers (Dashboard, Users, Realisasi, Target, Indikator, Spj, Programs, Capaian, Payment, Messages, Bukujaga, Uploads, Export, Datatables, Select2, Inbox, Dokuments, Account, Whatsapp) |
| `application/controllers/api/` | REST endpoints (Anggarankinerja, Realisasi, Welcome) — uses codeigniter-restserver                                                                                                                             |
| `application/models/`          | 18 models, all extend `CI_Model`                                                                                                                                                                               |
| `application/helpers/`         | Custom helpers (see below)                                                                                                                                                                                     |
| `application/views/`           | `pages/`, `layout/`, `frontend/`, `notify/`, `errors/`                                                                                                                                                         |
| `application/config/`          | `routes.php`, `config.php`, `database.php`, `autoload.php`                                                                                                                                                     |
| `template/`                    | Static assets + backend template                                                                                                                                                                               |

## Autoloaded (available everywhere, no explicit load needed)

- **Libraries:** `database`, `session`, `form_validation`, `pagination`
- **Helpers:** `html`, `nominal`, `url`, `form`, `number`, `tgl_indo`, `rand`, `security`, `cookie`, `apiclient`, `tgl_sql`, `sensor`, `baseurl`, `tagscript`, `date`
- **Models (aliased):**
  - `ModelUsers` → `$this->users`
  - `ModelNotify` → `$this->notify`
  - `ModelSettings` → `$this->global`
  - `ModelCrud` → `$this->crud`

## Custom Helper Functions (security_helper.php)

- `cek_session()` — guard for authenticated controllers. Redirects to `/lockscreen` if `user_id` session empty or no `csrf_token`. **Call first in every `app/` controller constructor.**
- `privilages($key)` — checks menu/action permission (e.g. `privilages('priv_programs')`). Used in sidebar and controllers.
- `encrypt_url($string)` / `decrypt_url($string)` — AES-256-CBC via `security.ini` (encryption_key, iv, mechanism). Use for obfuscating IDs in URLs.
- `getSetting($key)` — reads a setting row via `$this->global->getSetting(['key' => $key, 'status' => 'Y'])`.
- `do_hash($str, $type)` — sha1/md5 wrapper.
- `baseurl_helper.php`: `curPageURL()`, `getBaseUrl()`.

## Auth Flow

1. `Login::index()` — if `user_id` session set → redirect `app/dashboard`; if `pic` + `user_name` set → redirect `/lockscreen`.
2. `Login::cek_akun()` — POST `username` + `pwd`, validates against session `csrf_token` (403 on mismatch), then `ModelAuth`.
3. `cek_session()` in each `app/` controller constructor.
4. Session keys used: `user_id`, `user_name`, `nama`, `pic`, `csrf_token`, `is_valid_profile`, `role`.

## Routes (application/config/routes.php)

```php
$route['default_controller'] = 'login';
$route['lockscreen'] = 'login/lockscreen';
$route['app/users/u/(:any)'] = 'app/users/update_profile/$1';
$route['frontend/about'] = 'welcome/page/about';
```

## Controller Template

```php
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_session();
        // privilages check if needed
    }

    public function index()
    {
        $data = ['title' => '...'];
        $this->load->view('layout/header', $data);
        $this->load->view('pages/...', $data);
        $this->load->view('layout/footer');
    }
}
```

## Model Template

```php
<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class ModelX extends CI_Model
{
    protected $table = 't_x';

    public function get($table = null)
    {
        return $this->db->get($table ?? $this->table);
    }
}
```

Use `$this->crud` (ModelCrud) for simple CRUD: `get`, `getWhere`, `getWhereIn`, `getLikes`, `insert`, `update`, `updateAll` (update_batch), `deleteWhere`.

## Datatables Pattern

Models like `ModelUsers` define `$column_order`, `$column_search`, `$order`, and a `get_datatables()` method using `ModelDatatables`-style query builder. Server-side datatables endpoints live in `app/Datatables.php` and `app/Select2.php`.

## Views & Layout

- Layout parts in `application/views/layout/` (e.g. `sidebar.php`, header/footer).
- Sidebar uses `privilages()` + `$this->session->userdata('is_valid_profile')` to gate menu items.
- URLs: `base_url('app/...')` everywhere; never hardcode paths.

## API Controllers (application/controllers/api/)

Extend `REST_Controller` from `chriskacerguis/codeigniter-restserver`. Config in `application/config/rest.php`. Return JSON via `$this->response($data, REST_Controller::HTTP_OK)`.

## Exports

- PDF: `application/libraries/Pdf.php` (dompdf wrapper).
- Excel: `phpoffice/phpspreadsheet` via `ModelExport` / `app/Export.php`.

## Validation

Use `form_validation` library (autoloaded) or `rakit/validation` for API payloads.

## Gotchas

- `$config['csrf_protection'] = FALSE` in config.php — CSRF is handled manually via session `csrf_token` in `Login::cek_akun()`.
- `sess_driver = files`, `sess_expiration = 1700`.
- `base_url` is computed dynamically from `$_SERVER` — don't hardcode.
- Always guard file access with `defined('BASEPATH') or exit(...)`.
- `security.ini` lives in `application/helpers/` — `encrypt_url`/`decrypt_url` parse it relative to CWD.
