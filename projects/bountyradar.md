# Project Name

# BountyRadar – Ethical Bug Bounty Research Prioritization Platform

## Project Repository

GitHub Repository: mrlafa/Bountyradar

---

## Technology Used

BountyRadar is built as a full-stack JavaScript web application using:

* React
* Vite
* Tailwind CSS
* Recharts
* Node.js
* Express.js
* PostgreSQL
* JWT Authentication
* JavaScript
* PLpgSQL
* REST API
* Optional Redis-backed scheduler support for future refresh jobs

---

# 📘 BountyRadar – Full Project Outline

## 🧭 1. Who Are We

**BountyRadar** is an ethical bug bounty research prioritization platform designed to help security researchers compare authorized bug bounty programs before deciding where to spend their research time.

The platform helps researchers organize and compare:

* HackerOne programs
* Intigriti programs
* Manually imported programs
* Public program metadata
* Public disclosure trends
* Authorized API-based platform data
* Scoring signals for research planning

Our mission:

> “Help ethical security researchers prioritize authorized bug bounty programs safely, clearly, and efficiently.”

BountyRadar is not a hacking tool, scanner, exploit framework, or attack automation system. It is a research planning dashboard for authorized bug bounty work only.

---

## ❗ 2. What is the Main Problem It Solves

Bug bounty researchers often face these problems:

* Too many programs to choose from
* Difficulty comparing bounty value
* Difficulty understanding program activity
* Difficulty checking response and triage speed
* Unclear safe-harbor and rules quality
* Hard to identify active vs inactive programs
* Time wasted on low-quality or over-saturated programs
* No central dashboard for comparing authorized programs
* Manual research across HackerOne, Intigriti, disclosures, and notes

BountyRadar solves this by giving researchers a structured way to compare programs using ethical and authorized data sources.

---

## 🎯 3. What Problem BountyRadar Solves

BountyRadar solves four major problems:

### 1. Program Prioritization Problem

Researchers need to know which authorized programs are worth their time. BountyRadar gives each program a research opportunity score.

### 2. Data Organization Problem

Program information is usually scattered across platforms, notes, policies, and public disclosures. BountyRadar organizes this into one dashboard.

### 3. Ethical Scope Awareness Problem

Bug bounty research must stay within authorized scope. BountyRadar includes scope reminders and safe-harbor visibility.

### 4. Research Planning Problem

Researchers need a safer way to plan learning and testing priorities without scanning or attacking anything directly from the tool.

---

## ⚙️ 4. How BountyRadar Solves This Problem

BountyRadar solves the problem through:

* Program comparison dashboard
* HackerOne authorized API integration
* Intigriti authorized API integration
* Manual program import
* Public program metadata tracking
* Public disclosure trend tracking
* Research opportunity scoring
* Editable scoring weights
* Program notes
* Safe-harbor reminders
* Scope reminders
* Admin import tools
* Admin refresh tools
* Integration status dashboard
* Logs and activity tracking

The platform gives planning signals only. Researchers must always verify the official program policy, scope, bounty eligibility, and safe-harbor terms before testing.

---

## 🏗️ 5. Repository Structure

The project is organized as a full-stack JavaScript application.

```txt
Bountyradar/
│
├── client/
│   ├── src/
│   │   ├── components/
│   │   ├── pages/
│   │   ├── services/
│   │   └── utils/
│   └── package.json
│
├── server/
│   ├── migrations/
│   ├── src/
│   │   ├── config/
│   │   ├── db/
│   │   ├── middleware/
│   │   ├── routes/
│   │   └── services/
│   └── package.json
│
├── .github/
│   └── workflows/
│
├── .env.example
├── .gitignore
├── README.md
├── SECURITY_ETHICS.md
├── neon_workflow.yml
├── package.json
└── package-lock.json
```

---

## 🧑‍💻 6. Main Applications

## Client App

The client app is the frontend dashboard for researchers and admins.

Main responsibilities:

* Program listing UI
* Program details UI
* Score visualization
* Research dashboard
* Admin import interface
* Admin scoring weight interface
* Notes interface
* Integration status UI
* Logs display
* Charts and analytics using Recharts

Technology:

* React
* Vite
* Tailwind CSS
* Recharts

Local development URL:

```txt
http://localhost:5173
```

---

## Server App

The server app handles authentication, API routes, scoring logic, imports, and integrations.

Main responsibilities:

* User registration
* User login
* JWT authentication
* Program CRUD
* Admin-only program import
* Integration refresh endpoints
* Scoring calculation
* Notes API
* Logs API
* Integration status API
* PostgreSQL database access

Technology:

* Node.js
* Express.js
* PostgreSQL
* JWT
* REST API

Local backend URL:

```txt
http://localhost:4000/api
```

---

## Database

PostgreSQL stores the core application data.

Main responsibilities:

* Users
* Programs
* Notes
* Scoring data
* Integration logs
* External opportunities
* Imported records
* Admin configuration
* Scoring weights

---

## 🔌 7. Main Integrations

BountyRadar supports authorized connectors only.

### HackerOne

Used to import authorized bug bounty program data through the official HackerOne API.

### Intigriti

Used to import authorized bug bounty program data through the Intigriti API endpoint configured by the user.

### Manual Import

Admins can import programs manually using JSON or CSV records.

### Upwork

Used for business development or market opportunity records, not bug bounty authorization.

### Clutch

Used for provider or market records only through official API, partner endpoint, or approved MCP-backed access.

Important note:

> Upwork and Clutch records are not bug bounty authorization records. They should not be treated as permission to test any target.

---

## 🆚 8. Main Competitors

BountyRadar can compete with or complement:

### Bug Bounty Platforms

* HackerOne
* Bugcrowd
* Intigriti
* YesWeHack

### Research Planning Tools

* Notion-based bug bounty trackers
* Google Sheets trackers
* Airtable research databases
* Personal vulnerability research dashboards

### Security Intelligence Tools

* Public disclosure databases
* CVE tracking tools
* Recon dashboards
* OSINT note-taking systems

BountyRadar is different because it is focused on ethical program prioritization, not exploitation or automated scanning.

---

## 🚀 9. How BountyRadar Is Different

| Area                  | Normal Research Workflow | BountyRadar                         |
| --------------------- | ------------------------ | ----------------------------------- |
| Program comparison    | Manual browsing          | Central comparison dashboard        |
| Program scoring       | Personal guesswork       | Weighted research opportunity score |
| Notes                 | Separate docs            | Built-in program notes              |
| Safe-harbor reminders | Easy to miss             | Repeated on program details         |
| Integrations          | Manual checking          | Authorized API-based imports        |
| Admin controls        | Spreadsheet editing      | Admin dashboard and import tools    |
| Scoring logic         | Unclear                  | Editable scoring weights            |
| Ethical boundary      | Often undefined          | Clear ethics and scope reminders    |
| Data storage          | Scattered                | PostgreSQL-backed structure         |

BountyRadar is different because it helps researchers plan responsibly before doing any testing.

---

## 🔥 10. Why This is “Need to Have”

BountyRadar is useful because bug bounty researchers need to use their time wisely and legally.

Security researchers need:

* Better program comparison
* Clearer bounty opportunity signals
* Safer research planning
* Notes and learning organization
* Authorized data imports
* Scope reminders
* Safe-harbor visibility
* Activity and disclosure trend awareness
* Less time wasted on poor-fit programs

BountyRadar helps researchers focus on authorized, well-understood, and better-prioritized programs.

---

## 📚 11. What the Team Will Learn

Building BountyRadar will help the team learn:

* React dashboard development
* Vite frontend workflow
* Tailwind CSS UI development
* Recharts data visualization
* Node.js backend development
* Express API structure
* PostgreSQL schema design
* JWT authentication
* Admin role management
* API integration design
* Secure secret handling
* Bug bounty workflow design
* Ethical cybersecurity product design
* Research scoring models
* CSV and JSON import handling
* Logging and integration status monitoring

---

## 📊 12. Financial Overview

## Revenue Streams

BountyRadar can generate revenue through:

1. SaaS subscription for researchers
2. Team plans for security groups
3. Premium scoring features
4. Advanced analytics
5. Private notes and collaboration features
6. API integration limits
7. Research workspace plans
8. Agency or consultancy plans
9. Enterprise security research dashboards
10. Training and bug bounty workflow templates

---

## 📈 Example Projection

| Year   |       Users / Teams | Estimated Revenue |
| ------ | ------------------: | ----------------: |
| Year 1 |   1,000 researchers |              $30K |
| Year 2 |   5,000 researchers |             $150K |
| Year 3 |  20,000 researchers |            $750K+ |
| Year 4 | 50,000+ researchers |              $2M+ |

These are projections only. Actual revenue depends on user adoption, pricing, integrations, community trust, and product quality.

---

## 💸 13. How to Raise Funds

BountyRadar can raise funds from:

* Cybersecurity investors
* SaaS investors
* Angel investors
* Startup incubators
* Security communities
* Bug bounty education communities
* Developer tool investors
* Venture capital
* Ethical hacking training partners

### Investors Will Look For

* Working MVP
* Active researchers
* Ethical compliance
* Clear product positioning
* Strong security model
* Retention rate
* Paid users
* API partnerships
* Scalable architecture
* Differentiation from spreadsheets and generic dashboards

---

## 💼 14. Revenue Generation Strategy

BountyRadar’s revenue strategy can include:

### 1. Free Plan

Allow new researchers to track a limited number of programs.

### 2. Pro Researcher Plan

Offer more saved programs, notes, scoring history, and integrations.

### 3. Team Plan

Allow teams to collaborate on program research and prioritization.

### 4. Advanced Analytics

Offer deeper scoring trends, disclosure analysis, and activity signals.

### 5. API Integration Plan

Charge for scheduled authorized refresh jobs and integration monitoring.

### 6. Training Bundle

Sell bug bounty planning templates, learning roadmaps, and ethical research workflows.

---

## 🧩 15. Summarized Problems & Solutions

| Problem                                            | BountyRadar Solution                  |
| -------------------------------------------------- | ------------------------------------- |
| Too many programs to compare                       | Central program dashboard             |
| Hard to know where to focus                        | Weighted opportunity score            |
| Scattered research notes                           | Built-in notes system                 |
| Unclear program quality                            | Metadata and scoring signals          |
| Forgetting scope limits                            | Safe-harbor and scope reminders       |
| Manual import work                                 | JSON/CSV import                       |
| Authorized platform data scattered                 | HackerOne and Intigriti connectors    |
| Business opportunities mixed with bug bounty scope | Separate external opportunities table |
| Static scoring                                     | Admin-editable scoring weights        |
| Poor visibility into integrations                  | Integration status and logs           |

---

## 👨‍💻 16. Human Resources / Team Structure

## Core Team Needed

### 1. Project Manager

Responsibilities:

* Planning
* Coordination
* Timeline control
* Sprint planning
* Documentation
* Feature prioritization
* Security ethics alignment

### 2. Frontend Developer

Responsibilities:

* React dashboard development
* Tailwind CSS interface
* Program listing pages
* Program detail pages
* Admin UI
* Recharts visualizations
* Responsive design

### 3. Backend Developer

Responsibilities:

* Express API development
* JWT authentication
* PostgreSQL integration
* Program scoring logic
* Admin routes
* Import endpoints
* Integration refresh logic
* Logging system

### 4. Database Engineer

Responsibilities:

* PostgreSQL schema design
* Migration management
* Index optimization
* External opportunity separation
* Data integrity
* Backup planning

### 5. Cybersecurity Advisor

Responsibilities:

* Bug bounty workflow review
* Ethics and scope guidance
* Safe-harbor language review
* Program data interpretation
* Security policy review
* Ensure the tool does not enable unauthorized activity

### 6. QA Tester

Responsibilities:

* Auth testing
* Program CRUD testing
* Import testing
* Scoring testing
* Admin route testing
* Integration testing
* UI testing
* Security testing

### 7. DevOps Engineer

Responsibilities:

* Hosting setup
* PostgreSQL deployment
* Environment variable management
* CI/CD setup
* Logging
* Backups
* SSL setup
* Monitoring

### 8. Marketing / Community Specialist

Responsibilities:

* Bug bounty community outreach
* Product tutorials
* SEO content
* Research workflow education
* Social media promotion
* Community feedback collection

---

## 🧑‍🤝‍🧑 17. Team Members List

Recommended starting team:

* Founder / Product Lead
* CTO
* Frontend Developer
* Backend Developer
* Database Engineer
* Cybersecurity Advisor
* QA Tester
* DevOps Engineer
* Marketing / Community Manager

For MVP, the minimum team can be:

* Founder / Product Lead
* Full-Stack JavaScript Developer
* Cybersecurity Advisor
* QA / Tester

---

## ⚡ 18. High-Priority Features

## MVP Features

BountyRadar MVP should include:

* User registration
* User login
* JWT authentication
* Program listing
* Program details
* Program notes
* Admin program creation
* Admin program update
* Manual program import
* HackerOne authorized refresh
* Intigriti authorized refresh
* Program scoring
* Editable scoring weights
* Integration status page
* Admin logs
* Safe-harbor reminders
* Scope reminders
* Basic charts and analytics

---

## 🚀 Next-Level Features

Future features can include:

* Redis-backed scheduled refresh jobs
* More bug bounty platform integrations
* Team workspaces
* Shared notes
* Research checklist templates
* Program watchlist
* Score history
* Change detection for program policy updates
* Notification system
* Slack or Discord alerts
* Advanced disclosure trend analysis
* Custom scoring presets
* Export reports
* Research learning roadmap
* AI-assisted program summary
* AI-assisted scope explanation
* Browser extension for saving programs
* Mobile-friendly dashboard
* SaaS billing system

---

## 🛠️ 19. Installation Overview

Basic local setup:

```bash
git clone <repository-url> Bountyradar
cd Bountyradar
npm install
cp .env.example .env
cp server/.env.example server/.env
cp client/.env.example client/.env
```

Create PostgreSQL database:

```bash
createdb bountyradar
```

Update important environment variables:

```env
DATABASE_URL=
JWT_SECRET=
```

Run migrations and seed demo data:

```bash
npm run seed
```

Start the application:

```bash
npm run dev
```

Frontend:

```txt
http://localhost:5173
```

Backend:

```txt
http://localhost:4000/api
```

Seeded admin:

```txt
Email: admin@bountyradar.local
Password: password
```

The default admin password must be changed immediately after first login.

---

## 🔑 20. Environment Configuration

Important environment variables may include:

```env
DATABASE_URL=
JWT_SECRET=

HACKERONE_API_USERNAME=
HACKERONE_API_TOKEN=

INTIGRITI_API_TOKEN=
INTIGRITI_API_BASE_URL=https://api.intigriti.com
INTIGRITI_PROGRAMS_ENDPOINT=/external/company/v2/programs

UPWORK_CLIENT_ID=
UPWORK_CLIENT_SECRET=
UPWORK_ACCESS_TOKEN=
UPWORK_ORGANIZATION_ID=

CLUTCH_API_BASE_URL=
CLUTCH_API_TOKEN=
CLUTCH_PROVIDERS_ENDPOINT=
```

Security rule:

> Never commit API tokens, JWT secrets, database URLs, or platform credentials to GitHub.

Only configure API credentials that the user is authorized to use.

---

## 🧠 21. Scoring Model

BountyRadar uses a weighted 0–100 research opportunity score.

Scoring signals may include:

* Bounty range
* Program recency
* Program activity
* Public disclosure volume
* Public disclosure recency
* Scope size
* Asset diversity
* Response speed
* Triage speed
* Rules clarity
* Safe-harbor signal
* Competition level
* Saturation estimate
* Technology diversity

Admin users can edit scoring weights from the admin area.

Important note:

> A higher score does not mean a researcher should attack a program. It only means the program may be worth reviewing for authorized research planning.

---

## 🧭 22. Main User Flow

1. User registers or logs in
2. User opens the dashboard
3. User views bug bounty programs
4. User filters or compares programs
5. User opens a program detail page
6. User reviews scope and safe-harbor reminders
7. User checks score signals
8. User adds private research notes
9. User uses the score to plan learning or authorized testing
10. User verifies the official program policy before any activity

---

## 🧑‍💼 23. Admin Flow

1. Admin logs in
2. Admin imports programs manually
3. Admin refreshes authorized HackerOne data
4. Admin refreshes authorized Intigriti data
5. Admin refreshes external opportunities if configured
6. Admin checks integration status
7. Admin reviews logs
8. Admin updates scoring weights
9. Admin recalculates program scores
10. Admin verifies data quality and ethical boundaries

---

## 📥 24. Import System

The admin import system can accept JSON or CSV records.

Example fields:

* Platform
* Program name
* Handle
* Industry
* Country
* Policy URL
* Program URL
* Summary
* Safe-harbor text
* Rules clarity score
* Response time
* Triage speed
* Competition level
* Saturation estimate
* Technologies

Manual imports should only include authorized program metadata and public information.

---

## 🔐 25. Security & Ethics Requirements

BountyRadar must follow strict ethical rules:

* Do not scan targets
* Do not exploit systems
* Do not brute force credentials
* Do not bypass controls
* Do not scrape private reports
* Do not recommend attacking programs
* Do not treat public disclosures as attack instructions
* Do not treat Upwork or Clutch records as testing authorization
* Always verify official scope before research
* Always verify safe-harbor language
* Always follow each platform’s rules
* Store API tokens securely
* Use authorized API access only
* Protect user notes and account data
* Keep admin routes protected
* Use strong JWT secrets
* Log sensitive admin actions

---

## 🧪 26. Testing Checklist

## Frontend Testing

* Register user
* Login user
* View dashboard
* View program list
* Open program detail page
* Add program note
* View charts
* Test responsive layout
* Test admin pages

## Backend Testing

* Test auth register endpoint
* Test auth login endpoint
* Test JWT-protected routes
* Test admin-only routes
* Test program creation
* Test program update
* Test program scoring
* Test notes API
* Test import endpoint
* Test integration status endpoint
* Test logs endpoint

## Database Testing

* Run migrations
* Seed demo data
* Confirm users table
* Confirm programs table
* Confirm notes table
* Confirm external opportunities table
* Confirm logs table
* Confirm scoring weights table

## Ethics Testing

* Confirm the app does not run scans
* Confirm the app does not send exploit traffic
* Confirm the app does not scrape private reports
* Confirm program detail pages show reminders
* Confirm external opportunities are separated from bug bounty programs
* Confirm unauthorized integrations cannot run without credentials

---

## 📣 27. Marketing Strategy

BountyRadar can grow through:

* Bug bounty community posts
* Cybersecurity YouTube tutorials
* LinkedIn security content
* Twitter/X security threads
* Discord bug bounty groups
* Reddit cybersecurity communities
* SEO blog posts
* Research workflow templates
* Ethical hacking newsletters
* Bug bounty learning roadmaps
* Product Hunt launch
* Free researcher plan
* Open-source community feedback
* Partnerships with cybersecurity educators

---

## 📌 28. MVP Development Roadmap

## Phase 1: Project Foundation

* Review existing codebase
* Install dependencies
* Setup PostgreSQL
* Configure environment variables
* Run migrations
* Seed demo data
* Run frontend and backend locally

## Phase 2: Authentication & Roles

* Finalize user registration
* Finalize login
* Finalize JWT handling
* Add admin role protection
* Test protected routes

## Phase 3: Program Management

* Build program listing
* Build program details
* Build admin create/edit program
* Build notes system
* Add scope and safe-harbor reminders

## Phase 4: Scoring System

* Finalize scoring model
* Add weighted scoring
* Build admin weight editor
* Add score recalculation
* Show score breakdown in UI

## Phase 5: Integrations

* Configure HackerOne authorized connector
* Configure Intigriti authorized connector
* Configure manual import
* Keep Upwork and Clutch data separate
* Add integration status
* Add logs

## Phase 6: Security & Ethics Review

* Review SECURITY_ETHICS.md
* Add ethical disclaimers
* Ensure no scanning/exploitation features exist
* Protect secrets
* Protect admin routes
* Validate imported data
* Audit API endpoints

## Phase 7: Launch Preparation

* Improve UI
* Test all user flows
* Add documentation
* Setup deployment
* Setup database backup
* Setup monitoring
* Soft launch to trusted researchers

---

## 📈 29. Success Metrics

BountyRadar should track:

* Total users
* Active researchers
* Programs imported
* Programs viewed
* Notes created
* Score recalculations
* Integration refresh success rate
* Integration refresh failure rate
* Admin imports
* Program comparison activity
* Watchlisted programs
* Retention rate
* Pro plan conversion rate
* System uptime
* API error rate
* User feedback from researchers

---

## 🧭 30. Final Vision

BountyRadar’s final vision is to become a trusted ethical research planning platform for bug bounty hunters.

> Authorized Program Data + Scoring + Notes + Ethics + Research Planning

BountyRadar aims to help security researchers spend less time guessing and more time learning, planning, and working responsibly within authorized scopes.

The long-term vision is:

* Ethical bug bounty research dashboard
* Authorized platform integrations
* Program comparison engine
* Research opportunity scoring
* Safe-harbor visibility
* Scope-first workflow
* Notes and learning workspace
* Team research planning
* Advanced analytics
* Responsible cybersecurity SaaS platform

BountyRadar is built to become more than a dashboard.

It is a safe research planning system for ethical bug bounty work.
