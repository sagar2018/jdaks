# JDAKS Infra — Road Project Management System
## Laravel Web Application — Full Project Development Plan
**Version:** 1.0 | **Date:** May 2026 | **Stack:** Laravel 11 + MySQL + Bootstrap 5

---

## Table of Contents
1. [Project Overview](#1-project-overview)
2. [Tech Stack](#2-tech-stack)
3. [Directory Structure](#3-directory-structure)
4. [Database Schema](#4-database-schema)
5. [Phase-by-Phase Development Plan](#5-phase-by-phase-development-plan)
6. [Module Specifications](#6-module-specifications)
7. [Authentication & RBAC](#7-authentication--rbac)
8. [API Endpoints](#8-api-endpoints)
9. [UI/UX Guidelines](#9-uiux-guidelines)
10. [Security Checklist](#10-security-checklist)
11. [Testing Strategy](#11-testing-strategy)
12. [Deployment Checklist](#12-deployment-checklist)
13. [Dependencies](#13-dependencies)

---

## 1. Project Overview

**Goal:** Migrate the existing JDAKS Infra single-page HTML dashboard (localStorage-based) to a production-grade, multi-user Laravel 11 web application with secure MySQL persistence and RBAC.

### Key Objectives
| # | Objective |
|---|-----------|
| 1 | Replace browser localStorage with a secure MySQL database |
| 2 | Admin-controlled user management with Role-Based Access Control (RBAC) |
| 3 | Preserve all 6 module functionalities (Progress, DSR, Billing, Inventory, PV, Expenses) |
| 4 | Fully responsive Bootstrap 5 UI for desktop and mobile |
| 5 | Secure login with forgot-password email flow (no self-registration) |
| 6 | PDF export, Excel export, and server-side AJAX APIs |

---

## 2. Tech Stack

### Back-End
| Component | Choice |
|-----------|--------|
| Language | PHP 8.2+ |
| Framework | Laravel 11 (LTS) |
| Database | MySQL 8.0 + Eloquent ORM |
| Auth | Laravel built-in + Spatie Laravel-Permission |
| API Auth | Laravel Sanctum (future mobile) |
| Queues | Laravel Queues (database driver) |
| Mail | Laravel Mail (SMTP / Mailgun) |
| PDF | barryvdh/laravel-dompdf ^2.0 |
| Excel | maatwebsite/excel ^3.1 (PhpSpreadsheet) |
| Debug | Laravel Telescope (dev only) |

### Front-End
| Component | Choice |
|-----------|--------|
| CSS Framework | Bootstrap 5.3 |
| JS | Vanilla JS + Alpine.js |
| Charts | Chart.js 4 |
| Templates | Laravel Blade |
| Build Tool | Laravel Vite |

### Infrastructure
- Apache 2.4+ (XAMPP for local dev) / Nginx for production
- MySQL 8.0
- Redis (optional — sessions / queue)
- SSL via Let's Encrypt (production)

---

## 3. Directory Structure

```
jdaks/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── ForgotPasswordController.php
│   │   │   │   └── ResetPasswordController.php
│   │   │   ├── Admin/
│   │   │   │   ├── UserController.php
│   │   │   │   └── SettingsController.php
│   │   │   ├── ProjectController.php
│   │   │   ├── BoqController.php
│   │   │   ├── ProgressController.php
│   │   │   ├── BillingController.php
│   │   │   ├── DsrController.php
│   │   │   ├── InventoryController.php
│   │   │   ├── PvController.php
│   │   │   └── ExpenseController.php
│   │   ├── Middleware/
│   │   │   ├── RoleMiddleware.php
│   │   │   ├── ProjectAccessMiddleware.php
│   │   │   └── ModulePermissionMiddleware.php
│   │   └── Requests/            ← Form Request validators
│   ├── Models/
│   │   ├── User.php
│   │   ├── Project.php
│   │   ├── BoqItem.php
│   │   ├── ProgressEntry.php
│   │   ├── RaBill.php
│   │   ├── BillingConfig.php
│   │   ├── DsrItem.php
│   │   ├── InventoryMaterial.php
│   │   ├── InventoryTransaction.php
│   │   ├── PvIndex.php
│   │   └── Expense.php
│   ├── Policies/
│   │   ├── ProjectPolicy.php
│   │   ├── BillingPolicy.php
│   │   └── ProgressPolicy.php
│   └── Services/
│       ├── BillingService.php
│       ├── ProgressService.php
│       ├── PvCalculationService.php
│       ├── PdfExportService.php
│       └── ExcelExportService.php
├── database/
│   ├── migrations/              ← 20+ migration files
│   └── seeders/
│       ├── RolePermissionSeeder.php
│       ├── AdminSeeder.php
│       └── DemoDataSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php      ← authenticated layout (sidebar + topbar)
│   │   │   ├── auth.blade.php     ← login layout
│   │   │   └── admin.blade.php
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   ├── forgot-password.blade.php
│   │   │   └── reset-password.blade.php
│   │   ├── admin/users/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   └── show.blade.php
│   │   ├── projects/
│   │   │   ├── index.blade.php    ← Home dashboard
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   └── hub.blade.php      ← Project Hub
│   │   ├── progress/index.blade.php
│   │   ├── billing/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── show.blade.php
│   │   ├── dsr/index.blade.php
│   │   ├── inventory/index.blade.php
│   │   ├── pv/index.blade.php
│   │   ├── expenses/index.blade.php
│   │   └── pdf/
│   │       ├── progress-report.blade.php
│   │       └── ra-bill.blade.php
│   ├── js/
│   │   ├── app.js
│   │   ├── progress-tracker.js
│   │   ├── dsr-calculator.js
│   │   └── charts.js
│   └── css/
│       └── app.css               ← custom variables matching existing colours
├── routes/
│   ├── web.php
│   └── api.php
└── tests/
    └── Feature/
        ├── AuthTest.php
        ├── ProjectTest.php
        ├── ProgressTest.php
        ├── BillingTest.php
        └── InventoryTest.php
```

---

## 4. Database Schema

### 4.1 Core / Auth Tables
```sql
-- users
id | name | email (unique) | password | status (active/inactive)
created_by | last_login_at | created_at | updated_at | deleted_at

-- Spatie tables (auto-created):
roles | permissions | model_has_roles | model_has_permissions | role_has_permissions

-- project_user (pivot)
project_id | user_id | assigned_by | assigned_at
```

### 4.2 Project Tables
```sql
-- projects
id | name | package | location | contractor
chainage_start | chainage_end | start_date | end_date | deadline
bid_date | bid_type | bid_pct | bit_grade | dual_lane | notes | created_by
created_at | updated_at | deleted_at

-- boq_items
id | project_id | category | description | unit
quantity | rate | amount | sort_order
created_at | updated_at | deleted_at

-- progress_entries
id | project_id | boq_item_id | date | quantity
chainage_from | chainage_to | status | notes | created_by
created_at | updated_at | deleted_at

-- billing_configs
id | project_id | loa_number | tendered_value | bid_type | bid_pct
sd_pct | it_tds_pct | labour_cess_pct | gst_tds_pct | configured_at

-- ra_bills
id | project_id | bill_number | bill_date | gross_amount
deductions_json | net_payable | status | submitted_at
certified_at | paid_at | paid_amount | notes
created_at | updated_at | deleted_at
```

### 4.3 Module Tables
```sql
-- dsr_items
id | project_id | boq_item_id | material_json | labour_json
machinery_json | deduction_pct_json | updated_by
created_at | updated_at

-- inventory_materials
id | project_id | material_code | name | unit
reorder_qty | opening_stock
created_at | updated_at | deleted_at

-- inventory_transactions
id | project_id | material_id | txn_type (purchase/consumption/wastage)
date | quantity | rate | amount | reference_id | notes | created_by
created_at | updated_at

-- pv_indices
id | project_id | month | labour_idx | cement_idx | steel_idx
bitumen_idx | pol_idx | other_idx | plant_idx | base_month | created_by
created_at | updated_at

-- expenses
id | project_id | date | category (labour/machinery/other)
description | amount | created_by
created_at | updated_at | deleted_at
```

---

## 5. Phase-by-Phase Development Plan

### Overview
| Phase | Name | Duration | Milestone |
|-------|------|----------|-----------|
| 1 | Project Setup & Architecture | 1 week | Repo + DB schema ready |
| 2 | Authentication & User Management | 1.5 weeks | Auth system live |
| 3 | Project Management Core | 2 weeks | Projects & BOQ functional |
| 4 | Progress Tracker | 2.5 weeks | Progress module complete |
| 5 | DSR Calculator | 1.5 weeks | DSR module complete |
| 6 | Billing & RA Bills | 2 weeks | Billing module complete |
| 7 | Inventory & Materials | 1.5 weeks | Inventory module complete |
| 8 | Price Variation & Expense Sheet | 1.5 weeks | PV + Expense complete |
| 9 | Mobile Optimisation & Polish | 1 week | Mobile-ready |
| 10 | Testing, UAT & Deployment | 1 week | Production live |
| **Total** | | **16 weeks** (with QA buffer) | |

---

### Phase 1 — Project Setup & Architecture (Week 1)

**Goal:** Fully configured Laravel 11 project with DB schema and base layout.

#### Tasks
- [ ] Install Laravel 11 via Composer in `/Applications/XAMPP/xamppfiles/htdocs/jdaks`
- [ ] Configure `.env` — DB credentials, mail, app URL
- [ ] Run `php artisan key:generate`
- [ ] Install Composer packages: `spatie/laravel-permission`, `barryvdh/laravel-dompdf`, `maatwebsite/excel`, `laravel/sanctum`, `laravel/telescope`
- [ ] Install npm packages: Bootstrap 5.3, Alpine.js, Chart.js — configure Vite
- [ ] Create all 20+ database migrations (see §4)
- [ ] Run `php artisan migrate`
- [ ] Create `RolePermissionSeeder` — seeds roles: `admin`, `site_manager`, `field_engineer` with granular permissions
- [ ] Create `AdminSeeder` — seeds single `admin@jdaksinfra.com` admin user
- [ ] Build `resources/views/layouts/app.blade.php` — sticky topbar + left sidebar + mobile drawer
- [ ] Build `resources/views/layouts/auth.blade.php` — centred card layout
- [ ] Set up `resources/css/app.css` with CSS custom properties matching design system colours
- [ ] Configure `routes/web.php` with 3 route groups (guest / auth / admin)
- [ ] Register `RoleMiddleware`, `ProjectAccessMiddleware`, `ModulePermissionMiddleware` in `bootstrap/app.php`

#### Deliverables
- Running Laravel app at `http://localhost/jdaks/public`
- All DB tables created
- Bootstrap 5 layout skeleton rendering
- Admin user seeded

---

### Phase 2 — Authentication & User Management (Weeks 2–3, ~1.5 weeks)

**Goal:** Fully functional login system and admin user management.

#### Tasks

**Authentication**
- [ ] `LoginController` — email/password login with `Auth::attempt()`
- [ ] Rate limiting — `RateLimiter::for('login', ...)` — 5 attempts → 15-minute lockout
- [ ] Session expiry — `SESSION_LIFETIME=120` in `.env`
- [ ] `ForgotPasswordController` + `ResetPasswordController` using Laravel Password Broker
- [ ] Password complexity validation (8+ chars, mixed case, number) via `Rules\Password`
- [ ] `login.blade.php` — JDAKS Infra logo, email + password fields, show/hide toggle, Forgot Password link, NO Register link
- [ ] `forgot-password.blade.php` + `reset-password.blade.php`
- [ ] Error states: invalid credentials, account locked (with countdown), account inactive

**User Management (Admin only)**
- [ ] `Admin\UserController` — index, create, store, edit, update, destroy
- [ ] User list table: name, email, role badge, status, last login, assigned projects count
- [ ] Create User modal: name, email, role selector (Site Manager / Field Engineer only), auto-generated temporary password with copy button, send welcome email option
- [ ] Edit User: name, email, role, status (active/inactive)
- [ ] Assign Projects: multi-select checkbox list
- [ ] Reset Password: trigger reset email
- [ ] Deactivate/Reactivate user (status toggle)
- [ ] Prevent deletion of Admin account
- [ ] `ProjectAccessMiddleware` — verify user is assigned to requested project
- [ ] `ModulePermissionMiddleware` — verify role can access module

#### Deliverables
- Login/logout flow working
- Password reset via email working
- Admin can create/edit/assign/deactivate users
- Role-based route access enforced

---

### Phase 3 — Project Management Core (Weeks 3.5–5.5, ~2 weeks)

**Goal:** Full project CRUD, BOQ management, and project dashboard.

#### Tasks

**Project CRUD**
- [ ] `ProjectController` — index, create, store, show (hub), edit, update, destroy
- [ ] `ProjectPolicy` — only Admin can create/delete; Site Manager can edit
- [ ] Project creation form: name, package, location, contractor, chainage start/end, dates, bid config, bitumen grade, dual lane toggle
- [ ] `projects/index.blade.php` — responsive card grid, search + status filter bar, quick stats strip (total projects, total BOQ value, total earned), New Project button (Admin only)
- [ ] `projects/hub.blade.php` — project summary strip + 6 module cards with live badge summaries + Edit/Delete buttons (Admin only)

**BOQ Management**
- [ ] `BoqController` — CRUD for BOQ items per project
- [ ] BOQ item form: category, description, unit, quantity, rate (auto-calculates amount), sort order
- [ ] BOQ Excel import: `maatwebsite/excel` import class reading standard template
- [ ] BOQ Excel export

**AJAX APIs**
- [ ] `GET /projects/{id}/boq/summary` — returns BOQ completion JSON for dashboard cards

#### Deliverables
- Full project CRUD with access control
- BOQ items manageable per project
- Excel BOQ import/export working
- Project hub with live module summary badges

---

### Phase 4 — Progress Tracker (Weeks 5.5–8, ~2.5 weeks)

**Goal:** Complete progress tracking module with chainage, date log, and chart views.

#### Tasks

**Back-End**
- [ ] `ProgressController` — index, store, update, destroy, pdfExport, excelExport
- [ ] `ProgressService` — calculate BOQ completion %, chainage progress, earned value
- [ ] `ProgressPolicy` — Field Engineer: create only; Site Manager: create + edit; Admin: full
- [ ] `POST /projects/{id}/progress` — add entry, returns updated BOQ item summary JSON

**Front-End (4 Tabs)**
- [ ] **BOQ Items tab** — list with category colour coding, quantity done/total progress bar, status pill (Not Started / In Progress / Completed), expandable row with Add Entry modal
  - Add Entry modal fields: date, chainage from/to, quantity, status (Completed/Partial), notes
  - Filter by category: Earthwork / Bituminous / Base Course / Concrete / Drainage / Signage / Furniture
- [ ] **Chainage Map tab** — visual strip (HTML/CSS/JS) showing road progress with colour-coded segments per BOQ category
- [ ] **Date Log tab** — date-wise grouped collapsible cards showing all entries for that date
- [ ] **Road Overview tab** — Chart.js bar chart (planned vs actual quantities), doughnut (completion %), summary statistics table
- [ ] Export to PDF button — calls `PdfExportService` → DomPDF → `pdf/progress-report.blade.php`
- [ ] Export to Excel button — calls `ExcelExportService`

#### Deliverables
- All 4 Progress Tracker tabs functional
- PDF and Excel export working
- Real-time BOQ completion % updating on entry

---

### Phase 5 — DSR Calculator (Weeks 8–9.5, ~1.5 weeks)

**Goal:** Complete DSR cost analysis module.

#### Tasks
- [ ] `DsrController` — index, store/update DSR data per BOQ item
- [ ] DSR data stored in `dsr_items.material_json`, `labour_json`, `machinery_json` as structured JSON arrays
- [ ] `dsr/index.blade.php` — item list with expandable analysis rows
  - Material sub-table: description, unit, quantity, rate, amount (add/edit/delete rows)
  - Labour sub-table: same structure
  - Machinery sub-table: same structure
  - Auto-calculated totals: cost per unit, earned rate vs tendered rate, margin %
- [ ] Deductions configuration panel: SD %, IT TDS %, Labour Cess %, GST TDS %
- [ ] Cost Analysis panel: Theoretical vs Actual vs Variance (chainage-based)
- [ ] Bid % recommendation algorithm based on deduction percentages
- [ ] PIN-protected access (configurable per-role in admin settings) — PIN verified via AJAX before unlocking DSR view

#### Deliverables
- DSR Calculator fully functional with live calculations
- PIN protection working
- Bid % recommendation displaying

---

### Phase 6 — Billing & RA Bills (Weeks 9.5–11.5, ~2 weeks)

**Goal:** Full RA bill lifecycle management with PDF export.

#### Tasks
- [ ] `BillingController` — index, create, store, show, updateStatus, markPaid, pdfExport
- [ ] `BillingService` — calculate gross amount, apply deductions (SD, IT TDS, Labour Cess, GST TDS), compute net payable
- [ ] `BillingPolicy` — Field Engineer: view-only; Site Manager: view + create; Admin: full
- [ ] `billing/index.blade.php` — configuration step (LOA number, tendered value, bid type/%, deduction %s) + RA bill list table
  - Bill list columns: bill number, date, gross amount, deductions total, net payable, status badge, actions
- [ ] `billing/create.blade.php` — select BOQ items + quantities, auto-calculate gross, live deduction preview
- [ ] Bill status workflow UI: Draft → Submitted → Certified → Approved → Paid
  - `PATCH /projects/{id}/billing/bills/{billId}/status`
- [ ] Mark as Paid modal: paid amount + paid date
- [ ] `billing/show.blade.php` (PDF-ready view) — formal RA Bill format: LOA details, item table, deductions, net payable, signature blocks
- [ ] `PdfExportService::generateRaBill()` — DomPDF from `pdf/ra-bill.blade.php`
- [ ] Payment summary card: total bills raised, total paid, total outstanding

#### Deliverables
- RA bill creation with live deduction calculations
- Full status workflow working
- Formal PDF bill download working

---

### Phase 7 — Inventory & Materials (Weeks 11.5–13, ~1.5 weeks)

**Goal:** Full material stock management with ledger.

#### Tasks
- [ ] `InventoryController` — materials CRUD, transactions (purchase/consumption/wastage), stock positions
- [ ] `GET /projects/{id}/inventory/stock` — current stock JSON
- [ ] `POST /projects/{id}/inventory/transactions` — log transaction, update running balance
- [ ] `inventory/index.blade.php` — tabs: Materials | Transactions | Ledger
  - **Materials tab**: list with current stock, unit, reorder threshold, status pill (OK / Low / Out), Add Material button
  - **Transactions tab**: Add Purchase form, Log Consumption form (with optional progress entry link), Log Wastage form
  - **Ledger tab**: chronological transaction log table with running balance column
- [ ] Low stock alerts: materials below reorder threshold highlighted red with reorder suggestion
- [ ] Stock balance computed via sum of transactions (purchase +, consumption/wastage -)

#### Deliverables
- Material catalogue manageable
- Purchases, consumption, wastage logged correctly
- Running balance accurate in stock ledger
- Low-stock alerts triggering

---

### Phase 8 — Price Variation & Expense Sheet (Weeks 13–14.5, ~1.5 weeks)

**Goal:** PV calculator and expense sheet modules.

#### Tasks

**Price Variation (PV)**
- [ ] `PvController` — index, store/update monthly index entries
- [ ] `PvCalculationService` — PVC formula per component, total PVC amount calculation
- [ ] `pv/index.blade.php` — 3 sections:
  - Index entry table: month selector, 7 component index inputs (Labour, Cement, Steel, Bitumen, POL, Other, Plant), base month selector
  - Auto-calculated PVC per component and total PVC amount (live Alpine.js)
  - Month-wise PVC comparison table
  - Bitumen P&L analysis tab: purchase price vs index-adjusted price

**Expense Sheet**
- [ ] `ExpenseController` — CRUD for daily expense entries
- [ ] `expenses/index.blade.php` — date-wise log table + Add Expense form (date, category, description, amount)
- [ ] Summary card: totals by category (Labour / Machinery / Other)
- [ ] Margin analysis: total expenses vs earned value (from progress), net margin %
- [ ] Chart.js line chart: expenses trend vs earned value over time

#### Deliverables
- PV Calculator with live index-based calculations
- Month-wise PVC table functional
- Expense sheet with margin analysis working

---

### Phase 9 — Mobile Optimisation & Polish (Week 15)

**Goal:** Fully responsive, touch-friendly UI across all screens and breakpoints.

#### Tasks
- [ ] Audit all screens at 375px (mobile), 768px (tablet), 1024px, 1440px
- [ ] Sidebar → off-canvas drawer on mobile (`bootstrap offcanvas`)
- [ ] Tables with `table-responsive` wrapper; key tables transform to card view on mobile
- [ ] All modals use `modal-fullscreen-sm-down` on mobile
- [ ] Buttons full-width on mobile, minimum tap target 44px
- [ ] Sticky top navbar on all inner screens with project name breadcrumb and back navigation
- [ ] Chainage map strip reflows on narrow screens
- [ ] Chart.js charts responsive (`maintainAspectRatio: false` with container constraints)
- [ ] Test all forms on iOS Safari + Android Chrome
- [ ] Performance: eager load relations, add DB indexes on foreign keys and date columns
- [ ] Laravel Telescope review — fix N+1 queries identified

#### Deliverables
- All screens verified responsive at 4 breakpoints
- No horizontal scroll on mobile (except data tables)
- Page load time acceptable on mobile

---

### Phase 10 — Testing, UAT & Deployment (Week 16)

**Goal:** Tested, production-deployed application.

#### Tasks

**Automated Tests**
- [ ] `AuthTest.php` — login success/failure, lockout, password reset flow
- [ ] `ProjectTest.php` — CRUD, RBAC access, project-user assignment
- [ ] `ProgressTest.php` — entry creation, BOQ % calculation, chainage validation
- [ ] `BillingTest.php` — bill creation, deduction calculations, status workflow
- [ ] `InventoryTest.php` — purchase/consumption/wastage, running balance

**UAT**
- [ ] Test with Admin, Site Manager, Field Engineer accounts concurrently
- [ ] PDF export validation: bill and progress report layout
- [ ] Excel BOQ import/export roundtrip
- [ ] Mobile browser testing: Chrome Android + Safari iOS

**Production Deployment**
- [ ] `composer install --optimize-autoloader --no-dev`
- [ ] `php artisan migrate --force`
- [ ] `php artisan db:seed --class=RolePermissionSeeder`
- [ ] `php artisan db:seed --class=AdminSeeder`
- [ ] `npm run build`
- [ ] `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- [ ] Configure web server document root to `/public`
- [ ] Enable HTTPS (Let's Encrypt)
- [ ] Test all module flows in production
- [ ] Change admin password on first login

---

## 6. Module Specifications

| # | Module | Routes Prefix | Key Views | Controller |
|---|--------|---------------|-----------|------------|
| 1 | Progress Tracker | `/projects/{id}/progress` | progress/index (4 tabs) | ProgressController |
| 2 | DSR Calculator | `/projects/{id}/dsr` | dsr/index | DsrController |
| 3 | Billing & RA Bills | `/projects/{id}/billing` | billing/index, create, show | BillingController |
| 4 | Inventory & Materials | `/projects/{id}/inventory` | inventory/index (3 tabs) | InventoryController |
| 5 | Price Variation | `/projects/{id}/pv` | pv/index | PvController |
| 6 | Expense Sheet | `/projects/{id}/expenses` | expenses/index | ExpenseController |

---

## 7. Authentication & RBAC

### Roles
| Role | Level | Key Permissions |
|------|-------|-----------------|
| `admin` | 3 — Full | All permissions. Create users. Delete projects. System settings. Cannot be deleted. |
| `site_manager` | 2 — Medium | View all assigned projects. Create/edit progress, expenses, inventory. View billing. Cannot delete projects or manage users. |
| `field_engineer` | 1 — Limited | Log progress entries and expenses on assigned projects. View-only on BOQ, Billing, DSR. |

### Permissions (Spatie)
```
view_projects | create_projects | edit_projects | delete_projects
view_progress | create_progress | edit_progress | delete_progress
view_billing  | create_billing  | edit_billing  | delete_billing
view_dsr      | edit_dsr
view_inventory| create_inventory
view_pv       | edit_pv
view_expenses | create_expenses
manage_users  | manage_settings
```

### Login Rules
- No public registration (login page: Sign In + Forgot Password only)
- Rate limited: 5 failed attempts → locked 15 minutes
- Sessions expire: 2 hours inactivity (`SESSION_LIFETIME=120`)
- Password complexity: 8+ chars, mixed case, at least 1 number
- Password reset tokens: single-use, expire in 60 minutes

---

## 8. API Endpoints

### Web Routes (Blade + AJAX)
```
GET    /login
POST   /login
POST   /logout
GET    /forgot-password
POST   /forgot-password
GET    /reset-password/{token}
POST   /reset-password

GET    /dashboard                         ← project list
GET    /projects/create
POST   /projects
GET    /projects/{id}                     ← project hub
GET    /projects/{id}/edit
PUT    /projects/{id}
DELETE /projects/{id}

GET    /projects/{id}/boq
POST   /projects/{id}/boq
PUT    /projects/{id}/boq/{boqId}
DELETE /projects/{id}/boq/{boqId}

GET    /projects/{id}/progress
POST   /projects/{id}/progress
GET    /projects/{id}/progress/export-pdf
GET    /projects/{id}/progress/export-excel

GET    /projects/{id}/billing
GET    /projects/{id}/billing/create
POST   /projects/{id}/billing
PATCH  /projects/{id}/billing/{billId}/status
GET    /projects/{id}/billing/{billId}/pdf

GET    /projects/{id}/dsr
POST   /projects/{id}/dsr

GET    /projects/{id}/inventory
POST   /projects/{id}/inventory/transactions

GET    /projects/{id}/pv
POST   /projects/{id}/pv

GET    /projects/{id}/expenses
POST   /projects/{id}/expenses

GET    /admin/users
POST   /admin/users
GET    /admin/users/{id}/edit
PUT    /admin/users/{id}
DELETE /admin/users/{id}
POST   /admin/users/{id}/reset-password
```

### JSON API Routes (AJAX)
```
GET    /api/projects/{id}/boq/summary
POST   /api/projects/{id}/progress
PATCH  /api/projects/{id}/billing/bills/{billId}/status
POST   /api/projects/{id}/inventory/transactions
GET    /api/projects/{id}/inventory/stock
```

---

## 9. UI/UX Guidelines

### Colour Palette
```css
--bg-page:      #EFEFEF;
--bg-surface:   #FFFFFF;
--text-primary: #1A1A1A;
--accent-amber: #C47C0A;
--accent-green: #1A7A3A;
--accent-red:   #B03020;
--accent-blue:  #1A4FA0;
```

### Typography
- Font: `system-ui, 'Segoe UI', sans-serif`
- Base: 14px | Labels: 11px | Metric values: 22px

### Component Standards
- Border radius: 8px standard, 12px large cards
- Status pills: `badge` + bg colours per state (amber = in progress, green = completed, red = overdue/low stock, blue = submitted)
- Progress bars: 7px height, amber fill < 100%, green fill = 100%

### Responsive Breakpoints
| Breakpoint | Layout |
|------------|--------|
| 375px (xs) | 1-column, full-width modals, card-view tables, hamburger nav |
| 768px (md) | 2-column grid, side drawer |
| 1024px (lg) | 3–4 column grid, persistent sidebar |
| 1440px (xl) | 4-column grid, wide sidebar |

---

## 10. Security Checklist

- [x] CSRF: `@csrf` on all forms (Laravel built-in)
- [x] SQL Injection: Eloquent ORM + parameterised queries only
- [x] XSS: Blade `{{ }}` auto-escaping; `{!! !!}` only for trusted content
- [x] Password hashing: `bcrypt` via `Hash::make()`
- [x] Rate limiting: login throttled (5/15 min) via `RateLimiter`
- [x] Authorisation: `Policy` or `Gate` checked in every controller action
- [x] Project access: `ProjectAccessMiddleware` on all project routes
- [x] Admin routes: `role:admin` middleware on `/admin/*`
- [x] Password reset tokens: single-use, 60-minute expiry
- [x] HTTPS: `ForceHttps` middleware in production
- [x] Secrets: all in `.env`, never committed to version control
- [x] Session: `secure`, `httpOnly`, `sameSite=lax` cookie flags
- [x] File uploads (BOQ import): MIME validation, size limits, stored outside webroot

---

## 11. Testing Strategy

### Automated Tests (Pest / PHPUnit)
| Test File | Covers |
|-----------|--------|
| `AuthTest.php` | Login success/fail, lockout after 5 attempts, forgot/reset password flow, session timeout |
| `ProjectTest.php` | CRUD by role, ProjectAccess middleware, BOQ import/export |
| `ProgressTest.php` | Entry creation, BOQ % calculation, chainage validation, PDF/Excel export |
| `BillingTest.php` | Bill creation, deduction math, status transitions, PDF generation |
| `InventoryTest.php` | Purchase/consumption/wastage, running stock balance, low-stock alert |
| `RbacTest.php` | Admin accesses all, Site Manager cannot hit /admin/*, Field Engineer is view-only on billing/DSR |

### Manual / UAT
- [ ] Mobile: Chrome Android + Safari iOS at 375px
- [ ] Responsive layout at 375px, 768px, 1024px, 1440px
- [ ] PDF export: verify bill and progress report rendering
- [ ] Excel import/export roundtrip (import BOQ → export → re-import)
- [ ] Multi-user concurrency: 2 users on different projects simultaneously
- [ ] Data migration: import existing HTML localStorage JSON via admin panel

---

## 12. Deployment Checklist

```bash
# 1. Clone / copy project to server
# 2. Set permissions
chmod -R 755 storage bootstrap/cache

# 3. Configure environment
cp .env.example .env
# Edit .env: APP_KEY, DB_*, MAIL_*, SESSION_DRIVER, APP_URL

# 4. Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# 5. Generate app key
php artisan key:generate

# 6. Run migrations & seeders
php artisan migrate --force
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=AdminSeeder

# 7. Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Web server: document root → /public
# 9. HTTPS: Let's Encrypt SSL
# 10. Test all flows, change admin password on first login
```

### Initial Admin Credentials
- **Email:** `admin@jdaksinfra.com`
- **Password:** Auto-generated — displayed once in seeder output. Save immediately and change on first login.

---

## 13. Dependencies

### Composer (`composer.json`)
```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^11.0",
    "laravel/sanctum": "^4.0",
    "spatie/laravel-permission": "^6.0",
    "barryvdh/laravel-dompdf": "^2.0",
    "maatwebsite/excel": "^3.1"
  },
  "require-dev": {
    "laravel/telescope": "^5.0",
    "pestphp/pest": "^2.0",
    "pestphp/pest-plugin-laravel": "^2.0"
  }
}
```

### NPM (`package.json`)
```json
{
  "devDependencies": {
    "vite": "^5.0",
    "laravel-vite-plugin": "^1.0",
    "bootstrap": "^5.3.0",
    "@popperjs/core": "^2.11.8"
  },
  "dependencies": {
    "alpinejs": "^3.13.0",
    "chart.js": "^4.4.0"
  }
}
```

### Key `.env` Variables
```ini
APP_NAME=JDAKS_Infra
APP_ENV=production
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jdaks_infra
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your_mail_user
MAIL_PASSWORD=your_mail_password
MAIL_FROM_ADDRESS=noreply@jdaksinfra.com
MAIL_FROM_NAME="JDAKS Infra"

SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
```

---

*JDAKS Infra — Road Project Management System — Project Plan v1.0 — May 2026 — Confidential*
