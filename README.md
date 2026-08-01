# Synex ERP
**Developed by Synex Technologies** — Empowering Businesses Through Smart ERP Solutions.

A multi-tenant, multi-company, multi-branch Cloud ERP SaaS platform, purpose-built for
garage / fleet / workshop operations plus full back-office ERP (accounting, inventory,
sales, purchase, HR).

## Tech Stack
- **Frontend:** Next.js 14 (App Router), React, TypeScript, Tailwind CSS, Shadcn UI, Redux Toolkit, React Query
- **Backend:** Laravel (latest), REST API, JWT, Repository + Service pattern
- **Database:** MySQL, Redis (cache/queue)
- **Storage:** S3-compatible + local fallback

## Repository Layout
```
synex-erp/
├── backend/     # Laravel API
├── frontend/    # Next.js app
├── docs/        # Architecture & module docs
└── docker-compose.yml
```

## Multi-Tenancy Model
Row-level tenancy: every tenant-scoped table carries a `company_id`. A global
`TenantScope` (see `backend/app/Models/Concerns/BelongsToCompany.php`) auto-filters every
query. Super Admin bypasses the scope. Branches nest under companies via `branch_id`.

## Build Status & Roadmap

| Phase | Module | Status |
|---|---|---|
| 0 | Foundation: multi-tenancy, auth, RBAC, i18n, theme, dashboard shell | ✅ |
| 1 | Vehicle / Garage / Workshop management (vehicles, job cards, bays, mechanics, gate in-out) | ✅ this delivery |
| 2 | Inventory & Warehouse | ⏳ next |
| 3 | Accounting & Invoicing | ⏳ |
| 4 | Sales & Purchase | ⏳ |
| 5 | HR & Payroll | ⏳ |
| 6 | Reporting & Analytics dashboards | ⏳ |
| 7 | Notifications, Audit/Activity log UI, Settings | ⏳ |
| 8 | Deployment: Docker, CI/CD, production hardening docs | ⏳ |

Each phase will be delivered complete (migrations, models, repositories, services,
controllers, API routes, and matching frontend pages/components) rather than as stubs.
Tell me if you want a different module order and I'll re-prioritize.

## Local Development
```bash
docker compose up -d          # mysql, redis, backend, frontend
cd backend && composer install && php artisan migrate --seed
cd frontend && npm install && npm run dev
```

## Roles (seeded)
Super Admin, Admin, Company Owner, Manager, Accountant, Cashier, Garage Supervisor,
Gate Operator, Workshop Supervisor, Inventory Manager, Sales Manager, Purchase Manager,
HR Manager, Employee, Mechanic, Driver, Vehicle Owner, Customer, Supplier, Auditor, Viewer.
