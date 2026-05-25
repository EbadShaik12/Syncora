# StartupConnect — A Platform to Connect Startups with Corporates

A complete Laravel 10 MVC project that connects innovative startups with corporates seeking partnerships. Built with Laravel, Tailwind CSS, and Alpine.js.

## ✨ Features

### Core Features
- **Role-based Authentication** — Startups, Corporates, and Admins each get a tailored experience
- **Profile Management** — Rich profiles with logos, tech tags, milestones, funding info
- **Admin Dashboard** — User management, approve/reject/suspend, analytics with charts
- **Advanced Search** — Filter by industry, stage, location, with AJAX live suggestions

### Wow Features
- 🔥 **Tinder-style Swipe Matching** — Discover partners with smooth card animations
- 🧠 **AI Compatibility Score** — Weighted algorithm (Industry 25% + Tech 20% + Partnership 20% + Stage 15% + Budget 10% + Location 10%) showing why a match works
- 🎯 **Innovation Challenge Board** — Corporates post challenges, startups apply with proposals
- 💬 **Real-time Chat** — AJAX polling (every 4s) gives a near-realtime messaging experience

### Additional Wow Features
- 🌙 **Dark Mode** — Smooth toggle persisted in localStorage
- 🏆 **Gamification Badges** — 7 badges unlock as users engage (First Connection, Power Networker, etc.)
- 📄 **PDF Report Generation** — Download a polished startup profile PDF (powered by DomPDF)
- 🗺 **Startup Timeline / Milestones** — Visual journey of the startup's progress

## 📋 Tech Stack

- **Backend**: Laravel 10, PHP 8.1+
- **Database**: SQLite (default) or MySQL 8.0
- **Frontend**: Blade templates + Tailwind CSS (via CDN) + Alpine.js
- **PDF**: barryvdh/laravel-dompdf
- **Auth**: Laravel native session auth + custom role middleware

## 🚀 Setup Instructions

### Prerequisites
- PHP 8.1 or higher
- Composer
- SQLite (or MySQL) — SQLite is the easiest, no setup needed

### Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy environment file
cp .env.example .env

# 3. Generate application key
php artisan key:generate

# 4. Set up the database (SQLite default)
touch database/database.sqlite

# 5. Run migrations and seed demo data
php artisan migrate --seed

# 6. Create storage symlink for uploaded logos/files
php artisan storage:link

# 7. Start the development server
php artisan serve
```

Visit **http://localhost:8000** in your browser.

### Optional: Using MySQL Instead of SQLite

In `.env`, comment out the SQLite line and uncomment the MySQL lines:

```env
# DB_CONNECTION=sqlite
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=startup_connect
DB_USERNAME=root
DB_PASSWORD=
```

Then create the database in MySQL/phpMyAdmin and run `php artisan migrate --seed`.

## 🔐 Demo Accounts

All demo accounts use the password **`password`**

| Role | Email |
|------|-------|
| **Admin** | admin@scp.test |
| **Startup** (NeuralCart AI) | priya@neuralcart.test |
| **Startup** (MediSync) | arjun@medisync.test |
| **Startup** (FinFlow) | sneha@finflow.test |
| **Startup** (GreenHarvest) | vikram@greenharvest.test |
| **Startup** (EduSphere) | anjali@edusphere.test |
| **Startup** (SecureNet) | rohan@securenet.test |
| **Corporate** (TATA Digital) | rajesh@tatadigital.test |
| **Corporate** (Apollo Healthcare) | sunita@apollohealthcare.test |
| **Corporate** (HDFC Innovation Labs) | karan@hdfclabs.test |
| **Corporate** (Reliance New Energy) | meera@ril-newenergy.test |

## 📂 Project Structure (MVC)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           # LoginController, RegisterController
│   │   ├── Startup/        # StartupDashboardController, StartupProfileController
│   │   ├── Corporate/      # CorporateDashboardController, ChallengeController
│   │   ├── Admin/          # AdminDashboardController, AdminUserController
│   │   └── (SwipeController, SearchController, ChatController, etc.)
│   └── Middleware/
│       └── RoleMiddleware.php
├── Models/                 # User, StartupProfile, CorporateProfile, Connection, Message, etc.
└── Services/
    ├── CompatibilityScoreService.php   # AI matching algorithm
    ├── NotificationService.php
    └── BadgeService.php

resources/
└── views/                  # Blade templates (Tailwind + Alpine.js)
    ├── layouts/
    ├── components/         # navbar, etc.
    ├── auth/               # login, register flows
    ├── startup/            # startup-specific views
    ├── corporate/          # corporate-specific views
    ├── admin/              # admin views
    ├── chat/               # chat UI
    └── pdf/                # PDF templates

database/
├── migrations/             # All schema migrations
└── seeders/                # Demo data seeders

routes/
└── web.php                 # All route definitions
```

## 🎨 UI Highlights

- **Indigo + Purple + Pink gradient theme** for a modern feel
- **Inter font** from Google Fonts
- **Rounded-2xl cards** with subtle shadows
- **Smooth animations** — fade-in, slide-up, bounce-in
- **Fully responsive** — works on mobile, tablet, and desktop
- **Dark mode** throughout, with proper contrast

## 🔄 Key User Flows

### Startup → Corporate Connection (Mutual Match)
1. Startup swipes "interested" on a corporate
2. Corporate swipes "interested" on the same startup
3. Both receive a "It's a Match!" notification
4. A Connection is created → chat unlocks
5. Either party can start the conversation

### Corporate Challenge → Startup Application
1. Corporate posts a challenge with budget + deadline
2. Startups browse open challenges
3. Startup submits an application with cover letter + proposal
4. Corporate reviews applications, shortlists or rejects
5. Shortlisted startup gets notified

### Admin Approval (Optional)
By default, new users are auto-approved. To enable manual admin approval, change `'status' => 'approved'` to `'status' => 'pending'` in `RegisterController`.

## 🛠 Configuration

### Email
The default mail driver is `log` — emails are written to `storage/logs/laravel.log`. Configure SMTP in `.env` if needed.

### File Uploads
- Profile logos go to `storage/app/public/logos`
- Challenge proposals go to `storage/app/public/proposals`
- Make sure `php artisan storage:link` was run

## 📝 Notes

- The AI compatibility score is a rule-based weighted algorithm — not a true ML model. For a college MVC project, this is industry-standard and explainable.
- Chat uses AJAX polling (every 4 seconds) instead of WebSockets for simplicity. To upgrade to true real-time, integrate Laravel Reverb or Pusher.
- All Tailwind classes are loaded via the CDN — no build step is required.

## 📜 License

This is an educational MVC project. Free to use and modify.

---

**Built with ❤️ as a College MVC Project**
