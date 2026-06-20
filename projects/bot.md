# Project Name

# Nexbot – Telegram Product-Selling Bot

## Project Repository

GitHub Repository: mrlafa/nexbot

---

## Technology Used

Nexbot is built as a Laravel-based Telegram commerce backend using:

* Laravel 13
* PHP 8.3+
* PostgreSQL 13+
* Redis
* Laravel Queues
* Blade Admin Dashboard
* Telegram Bot API
* Telegram Webhook
* Composer
* Node.js
* npm
* Vite
* Laravel Storage
* Manual Payment Verification
* NPR / USD Pricing System

---

# 📘 Nexbot – Full Project Outline

## 🧭 1. Who Are We

**Nexbot** is a Telegram-based product-selling bot that allows customers to browse products, add items to cart, place orders, choose payment methods, upload payment proof, and track order status directly inside Telegram.

The platform combines:

* Telegram bot shopping experience
* Product catalog
* Cart system
* Checkout flow
* Manual payment verification
* NPR and USD pricing
* Admin dashboard
* Redis queue processing
* PostgreSQL database
* Telegram notifications

Our mission:

> “Make online selling faster, simpler, and more accessible through Telegram.”

Nexbot is not just a chatbot. It is a complete Telegram commerce system where businesses can sell digital or physical products without needing a full mobile app or ecommerce website.

---

## ❗ 2. What is the Main Problem It Solves

Many small businesses, freelancers, and digital sellers face problems such as:

* No proper ecommerce website
* High cost of building a full store
* Manual order handling through chat
* Confusing payment verification
* No cart system inside Telegram
* No automatic customer notification
* No simple admin dashboard
* No clear order status tracking
* Difficulty managing NPR and USD pricing
* Difficulty selling to both Nepal and international customers

Nexbot solves these problems by turning Telegram into a structured selling platform.

---

## 🛒 3. What Problem Nexbot Solves

Nexbot solves four major problems:

### 1. Manual Selling Problem

Many sellers take orders manually through Telegram, WhatsApp, or Messenger. Nexbot automates product browsing, cart, checkout, and order status.

### 2. Payment Verification Problem

Sellers often need to manually check screenshots and update customers. Nexbot allows customers to upload payment proof and lets admins approve or reject payments from the dashboard.

### 3. Multi-Currency Problem

Nepal users need NPR pricing, while international users may need USD pricing. Nexbot supports fixed NPR and USD product pricing.

### 4. Admin Management Problem

Businesses need a simple dashboard to manage products, customers, orders, payments, and settings. Nexbot provides a Blade-based admin dashboard.

---

## ⚙️ 4. How Nexbot Solves This Problem

Nexbot solves the problem through:

* Telegram Bot API integration
* Laravel backend
* PostgreSQL database
* Redis queue worker
* Webhook-based Telegram updates
* Product catalog system
* Cart and checkout flow
* Customer region selection
* NPR and USD pricing snapshots
* Manual payment proof upload
* Admin payment approval and rejection
* Telegram customer notifications
* Admin order management
* CSV order export
* Payment method management

---

## 🏗️ 5. Repository Structure

The project follows a standard Laravel application structure.

```txt
nexbot/
│
├── app/
│   └── Application logic, models, services, controllers, middleware
│
├── bootstrap/
│   └── Laravel bootstrap files
│
├── config/
│   └── Application configuration files
│
├── database/
│   └── Migrations, seeders, and database structure
│
├── public/
│   └── Public web root
│
├── resources/
│   └── Blade views, frontend assets, CSS, JavaScript
│
├── routes/
│   └── Web routes and API routes
│
├── storage/
│   └── Logs, cache, uploaded files, and payment proofs
│
├── tests/
│   └── Automated tests
│
├── .env.example
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── vite.config.js
└── README.md
```

---

## 🧑‍💻 6. Main Modules

## Telegram Bot Module

The Telegram bot module handles all customer interactions inside Telegram.

Main responsibilities:

* Receive `/start` command
* Ask customer to select region
* Show products
* Show product details
* Manage cart actions
* Handle checkout steps
* Accept payment proof
* Show order status
* Send customer notifications

---

## Product Catalog Module

The product catalog module manages products available inside the bot.

Main responsibilities:

* Product code
* Product name
* Product description
* Product image path or URL
* NPR price
* USD price
* Stock quantity
* Product type
* Active/inactive status

---

## Cart Module

The cart module lets customers build an order before checkout.

Main responsibilities:

* Add product to cart
* Increase quantity
* Decrease quantity
* Remove item
* Clear cart
* Store currency snapshot
* Prevent mixed-currency confusion
* Prepare checkout totals

---

## Order Module

The order module manages confirmed customer purchases.

Main responsibilities:

* Store customer details
* Store ordered items
* Store total amount
* Store selected currency
* Store order status
* Track payment status
* Keep order snapshot
* Notify customer when status changes

---

## Payment Module

The payment module manages manual payment verification.

Main responsibilities:

* Show payment methods by region
* Accept payment screenshot
* Download Telegram payment proof
* Store proof in Laravel storage
* Allow admin to approve payment
* Allow admin to reject payment with reason
* Notify customer after approval or rejection
* Update stock after payment approval

---

## Payment Methods Module

This module allows admins to configure payment options.

Main responsibilities:

* Manage Nepal payment methods
* Manage international payment methods
* Enable or disable methods
* Support eSewa
* Support Khalti
* Support Bank Transfer
* Support QR Code Payment
* Support Cash on Delivery for physical products
* Support manual payment verification
* Keep crypto/Binance option disabled unless enabled by settings

---

## Region & Currency Module

Nexbot supports region-based pricing.

Main responsibilities:

* Ask user to choose Nepal or Outside Nepal
* Show NPR pricing for Nepal users
* Show USD pricing for outside Nepal users
* Store user currency preference
* Store price snapshot in cart and orders
* Clear active cart when region changes
* Avoid mixed-currency orders

---

## Admin Dashboard

The admin dashboard helps business owners manage the system.

Main responsibilities:

* Admin login
* Product management
* Customer management
* Order management
* Payment proof review
* Payment approval
* Payment rejection
* Stock reduction after approval
* Payment method management
* Default region/currency settings
* Crypto payment availability setting
* CSV order export
* Telegram customer notifications

---

## Queue Worker Module

Redis queue workers help process background tasks.

Main responsibilities:

* Handle Telegram notification jobs
* Process async backend tasks
* Improve performance
* Avoid blocking user interactions
* Support scalable bot operations

---

## 🆚 7. Main Competitors

Nexbot can compete with:

### Telegram Commerce Tools

* Custom Telegram shop bots
* BotFather-based manual bots
* Telegram mini shop systems
* Telegram channel-based selling

### Ecommerce Platforms

* Shopify
* WooCommerce
* Daraz seller system
* Facebook Marketplace
* Instagram shops

### Chat Commerce Tools

* WhatsApp Business
* Messenger shop workflows
* Manual chat-based order systems

---

## 🚀 8. How Nexbot Is Different

| Area             | Normal Chat Selling   | Nexbot                         |
| ---------------- | --------------------- | ------------------------------ |
| Product browsing | Manual messages       | Bot-based product catalog      |
| Cart             | No proper cart        | Built-in cart system           |
| Checkout         | Manual conversation   | Guided checkout flow           |
| Payment proof    | Sent randomly in chat | Structured upload and review   |
| Admin management | Manual spreadsheet    | Blade admin dashboard          |
| Currency         | Often confusing       | NPR/USD fixed pricing          |
| Order status     | Manual update         | Bot-based status tracking      |
| Stock            | Manual tracking       | Stock decreases after approval |
| Notifications    | Manual messages       | Telegram notifications         |
| Backend          | No structure          | Laravel + PostgreSQL + Redis   |

Nexbot is different because it converts Telegram from a simple chat app into a structured sales channel.

---

## 🔥 9. Why This is “Need to Have”

Nexbot is useful because many businesses already sell through Telegram and other chat platforms.

They need:

* Faster order handling
* Lower setup cost
* Easy product display
* Simple customer experience
* Payment proof tracking
* Admin dashboard
* Stock control
* Multi-currency pricing
* Better customer communication

Instead of building a full ecommerce website first, businesses can start selling directly through Telegram.

---

## 📚 10. What the Team Will Learn

Building Nexbot will help the team learn:

* Laravel 13 backend development
* Telegram Bot API integration
* Telegram webhook setup
* PostgreSQL database design
* Redis queue processing
* Manual payment verification flow
* Laravel storage handling
* Cart and order architecture
* Region-based currency logic
* Admin dashboard development
* Customer notification systems
* VPS deployment
* Nginx configuration
* Supervisor queue worker setup
* Secure bot token management

---

## 📊 11. Financial Overview

## Revenue Streams

Nexbot can generate revenue through:

1. Product margin
2. Digital product sales
3. Physical product sales
4. Bot setup fee for businesses
5. Monthly SaaS subscription
6. Payment verification service fee
7. Premium bot customization
8. Admin dashboard customization
9. Multi-store support
10. White-label Telegram shop bot service

---

## 📈 Example Projection

| Year   |                 Shops / Users | Estimated Revenue |
| ------ | ----------------------------: | ----------------: |
| Year 1 |      100 shops / 10,000 users |              $30K |
| Year 2 |      500 shops / 50,000 users |             $150K |
| Year 3 |   2,000 shops / 200,000 users |            $750K+ |
| Year 4 | 5,000+ shops / 500,000+ users |              $2M+ |

These numbers are projections only. Actual revenue depends on product demand, pricing, bot adoption, seller onboarding, payment support, and customer retention.

---

## 💸 12. How to Raise Funds

Nexbot can raise funds from:

* Angel investors
* Startup incubators
* Ecommerce investors
* SaaS investors
* Telegram ecosystem partners
* Local business partners
* Digital product sellers
* Venture capital

### Investors Will Look For

* Working MVP
* Real Telegram bot usage
* Number of sellers
* Number of orders
* Revenue per seller
* Payment success rate
* Customer retention
* Clear SaaS pricing
* Scalable backend
* Strong security
* Business adoption potential

---

## 💼 13. Revenue Generation Strategy

Nexbot’s revenue strategy can include:

### 1. Setup Fee

Charge businesses a one-time fee to set up their Telegram shop bot.

### 2. Monthly Subscription

Offer monthly plans for active sellers.

### 3. Sales Commission

Take a small percentage from completed orders.

### 4. Premium Dashboard

Offer advanced analytics, export, and product management tools.

### 5. Custom Bot Branding

Allow businesses to customize the bot with their own brand name, logo, and flow.

### 6. Multi-Currency Selling

Offer advanced international selling features for higher-tier users.

### 7. Digital Product Automation

Charge extra for automatic delivery of digital products.

---

## 🧩 14. Summarized Problems & Solutions

| Problem                 | Nexbot Solution                        |
| ----------------------- | -------------------------------------- |
| Manual Telegram selling | Automated Telegram bot                 |
| No product catalog      | Product listing and details inside bot |
| No cart system          | Built-in cart and cart items           |
| Confusing checkout      | Guided checkout flow                   |
| Payment screenshot mess | Structured proof upload                |
| Manual payment review   | Admin approval/rejection dashboard     |
| No currency clarity     | NPR/USD region pricing                 |
| Stock confusion         | Stock reduced after payment approval   |
| No order tracking       | Bot-based order status                 |
| Poor admin control      | Blade admin dashboard                  |

---

## 👨‍💻 15. Human Resources / Team Structure

## Core Team Needed

### 1. Project Manager

Responsibilities:

* Planning
* Coordination
* Timeline control
* Sprint planning
* Documentation
* Feature prioritization

### 2. Laravel Backend Developer

Responsibilities:

* Laravel application logic
* Telegram webhook handling
* Database migrations
* Models and relationships
* Cart and order logic
* Payment proof handling
* Admin dashboard backend
* Queue worker setup

### 3. Blade Frontend Developer

Responsibilities:

* Admin dashboard UI
* Product management pages
* Order detail pages
* Payment proof preview
* Settings pages
* Responsive admin layout
* Vite asset workflow

### 4. Telegram Bot Developer

Responsibilities:

* Bot command flow
* Inline keyboard actions
* Multi-step conversation state
* Telegram message formatting
* Telegram file download
* Customer notifications
* Webhook security

### 5. Database Engineer

Responsibilities:

* PostgreSQL schema design
* Order and payment data structure
* Currency snapshot logic
* Index optimization
* Backup planning
* Data integrity

### 6. QA Tester

Responsibilities:

* Telegram bot testing
* Cart testing
* Checkout testing
* Payment proof testing
* Admin dashboard testing
* Currency testing
* Order status testing
* Security testing

### 7. DevOps Engineer

Responsibilities:

* VPS setup
* Nginx configuration
* PHP-FPM setup
* PostgreSQL setup
* Redis setup
* Supervisor worker setup
* SSL certificate
* Webhook deployment
* Backup system

### 8. Business / Support Manager

Responsibilities:

* Product upload
* Order support
* Payment verification
* Customer support
* Seller communication
* Refund handling
* Sales operations

---

## 🧑‍🤝‍🧑 16. Team Members List

Recommended starting team:

* Founder / Product Lead
* CTO
* Laravel Developer
* Telegram Bot Developer
* Blade Dashboard Developer
* QA Tester
* DevOps Engineer
* Support Manager

For MVP, the minimum team can be:

* Founder / Product Manager
* Laravel Full-Stack Developer
* Telegram Bot Developer
* Support / Admin Operator

---

## ⚡ 17. High-Priority Features

## MVP Features

Nexbot MVP should include:

* Telegram `/start` command
* Region selection
* NPR pricing for Nepal
* USD pricing for outside Nepal
* Product listing
* Product details
* Add to cart
* Increase/decrease quantity
* Remove from cart
* Clear cart
* Checkout flow
* Customer name and phone collection
* Optional email and address collection
* Payment method selection
* Payment proof upload
* Admin payment review
* Approve payment
* Reject payment with reason
* Stock reduction after approval
* Order status tracking
* Telegram customer notifications
* Product management dashboard
* Order management dashboard
* Payment method management
* CSV order export

---

## 🚀 Next-Level Features

Future features can include:

* Filament admin panel
* Product image upload UI
* QR image upload UI
* Automated payment gateway integration
* eSewa API integration
* Khalti API integration
* Binance/crypto payment support
* Digital product auto-delivery
* Coupon codes
* Promotional pricing
* Referral system
* Customer loyalty points
* Abandoned cart reminder
* Multi-language support
* Multi-vendor support
* Seller dashboard
* Detailed audit logs
* Advanced analytics
* Web mini-app interface
* Subscription billing for shop owners

---

## 🛠️ 18. Installation Overview

Basic local setup:

```bash
git clone <repository-url> nexbot
cd nexbot
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
php artisan migrate --seed
php artisan storage:link
php artisan queue:work
php artisan serve
```

Default admin:

```txt
Email: admin@example.com
Password: password
```

The default admin password must be changed immediately after first login.

---

## 🔑 19. Environment Configuration

Important environment variables:

```env
APP_URL=https://yourdomain.com

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=telegram_shop
DB_USERNAME=postgres
DB_PASSWORD=secret

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=database
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1

TELEGRAM_BOT_TOKEN=123456:token
TELEGRAM_WEBHOOK_SECRET=change-this-secret
TELEGRAM_ADMIN_CHAT_ID=123456789
TELEGRAM_BOT_USERNAME=your_bot_username

DEFAULT_CURRENCY=NPR
DEFAULT_REGION=nepal
CRYPTO_PAYMENTS_ENABLED=false
```

Important security rule:

> Never commit the Telegram bot token, webhook secret, database password, or admin credentials to GitHub.

---

## 🤖 20. Telegram Setup

Telegram setup process:

1. Create a bot using BotFather
2. Copy the bot token
3. Add the token to `TELEGRAM_BOT_TOKEN`
4. Generate a long random webhook secret
5. Set the webhook URL
6. Use HTTPS domain for production
7. Use ngrok for local testing

Webhook format:

```txt
https://yourdomain.com/api/telegram/webhook/SECRET
```

Local testing:

```bash
php artisan serve
ngrok http 8000
```

Then set the Telegram webhook to the ngrok HTTPS URL plus:

```txt
/api/telegram/webhook/SECRET
```

---

## 🧭 21. Main Customer Flow

1. Customer opens Telegram bot
2. Customer sends `/start`
3. Bot asks customer to choose region
4. Customer selects Nepal or Outside Nepal
5. Bot shows products with correct currency
6. Customer opens product detail
7. Customer chooses quantity
8. Customer adds item to cart
9. Customer reviews cart
10. Customer proceeds to checkout
11. Customer enters name and phone
12. Customer adds optional email/address
13. Customer confirms order
14. Customer chooses payment method
15. Customer uploads payment proof
16. Admin reviews payment
17. Customer receives approval or rejection message
18. Customer checks order status

---

## 🧑‍💼 22. Admin Flow

1. Admin logs into `/admin`
2. Admin creates or edits products
3. Admin sets NPR and USD prices
4. Admin checks customers
5. Admin checks orders
6. Admin opens payment screenshot
7. Admin approves or rejects payment
8. System reduces stock after approval
9. System notifies customer on Telegram
10. Admin updates order status
11. Admin manages payment methods
12. Admin exports orders to CSV
13. Admin updates default region/currency settings

---

## 💳 23. Payment Flow

### Nepal Users

Nepal users can see payment methods such as:

* eSewa
* Khalti
* Bank Transfer
* QR Code Payment
* Cash on Delivery for physical products
* Manual Payment Verification

### Outside Nepal Users

Outside Nepal users can see international/manual payment methods.

Crypto/Binance support can exist in the database but should remain hidden unless:

```txt
CRYPTO_PAYMENTS_ENABLED=true
```

and the payment method is active.

### Payment Proof Handling

1. Customer uploads payment screenshot in Telegram
2. Bot downloads screenshot using Telegram file API
3. File is stored in Laravel storage
4. Admin previews proof in dashboard
5. Admin approves or rejects payment
6. Customer receives Telegram notification

---

## 📦 24. Product Types

Nexbot can support:

* Digital products
* Physical products
* Subscription products
* Service packages
* Software licenses
* Course access
* Premium files
* Gift cards
* Local products
* International digital offers

---

## 📣 25. Marketing Strategy

Nexbot can grow through:

* Telegram communities
* Facebook groups
* TikTok demo videos
* YouTube Shorts
* Product-selling tutorials
* Digital product seller outreach
* Freelancer communities
* Small business outreach
* WhatsApp-to-Telegram migration campaigns
* Lifetime setup offer
* SaaS subscription campaign
* Referral program
* Agency partnerships
* Local ecommerce seller campaigns

---

## 📌 26. MVP Development Roadmap

## Phase 1: Project Foundation

* Review Laravel codebase
* Setup `.env`
* Install Composer dependencies
* Install npm dependencies
* Setup PostgreSQL database
* Setup Redis
* Run migrations and seeders
* Link storage
* Run local server

## Phase 2: Telegram Bot Flow

* Setup BotFather bot
* Configure bot token
* Configure webhook secret
* Setup webhook
* Test `/start` command
* Test region selection
* Test product browsing
* Test cart actions

## Phase 3: Ecommerce Flow

* Finalize product catalog
* Finalize product details
* Finalize cart logic
* Finalize checkout flow
* Finalize order creation
* Save customer details
* Save currency snapshots

## Phase 4: Payment Verification

* Configure payment methods
* Test payment proof upload
* Store Telegram screenshot
* Preview proof in admin
* Approve payment
* Reject payment
* Notify customer
* Reduce stock after approval

## Phase 5: Admin Dashboard

* Product management
* Customer management
* Order management
* Payment management
* Payment method management
* Settings page
* CSV export
* Admin password update

## Phase 6: Deployment

* Setup VPS
* Setup Nginx
* Setup PHP-FPM
* Setup PostgreSQL
* Setup Redis
* Setup Supervisor queue worker
* Setup SSL
* Set production webhook
* Test live Telegram bot

## Phase 7: Growth Features

* Add image uploads
* Add QR uploads
* Add automated payment gateway
* Add coupon system
* Add digital product auto-delivery
* Add analytics
* Add subscription plans

---

## 🔐 27. Security Requirements

Nexbot should include:

* Strong admin password
* Long random webhook secret
* Telegram bot token stored only in `.env`
* HTTPS for production webhook
* Server firewall
* Restricted admin access
* Secure PostgreSQL credentials
* Redis protection
* Laravel validation
* Rate limiting
* Payment proof file protection
* Regular database backups
* Payment proof file backups
* Admin activity audit logs
* Safe crypto/payment compliance review

---

## 🧪 28. Testing Checklist

## Customer Testing

* Send `/start`
* Select Nepal
* Confirm prices show NPR
* Select Outside Nepal
* Confirm cart clears
* Confirm prices show USD
* Open product detail
* Add product to cart
* Increase quantity
* Decrease quantity
* Remove item
* Clear cart
* Checkout with name and phone
* Add optional email/address
* Confirm order
* Choose payment method
* Upload payment proof
* Check order status

## Admin Testing

* Login to `/admin`
* Create product
* Edit product
* Change NPR price
* Change USD price
* View customers
* View order details
* View payment screenshot
* Approve payment
* Confirm stock decreases
* Reject payment with reason
* Update order status
* Confirm Telegram notification
* Export orders CSV

## System Testing

* Run migrations
* Run seeders
* Run tests
* Run queue worker
* Confirm storage link
* Confirm webhook response
* Confirm payment proof storage
* Confirm Redis queue processing

---

## 📈 29. Success Metrics

Nexbot should track:

* Total Telegram users
* Active users
* Product views
* Cart additions
* Checkout starts
* Completed orders
* Payment proof uploads
* Payment approval rate
* Payment rejection rate
* Average order value
* Revenue by currency
* NPR sales
* USD sales
* Stock movement
* Repeat customers
* Admin response time
* Bot uptime
* Queue processing health

---

## 🧭 30. Final Vision

Nexbot’s final vision is to become a complete Telegram commerce platform.

> Telegram Bot + Product Catalog + Cart + Orders + Payments + Admin Dashboard

Nexbot aims to help sellers launch a working online shop directly inside Telegram without needing a full ecommerce website or mobile app.

The long-term vision is:

* Telegram-first ecommerce
* Digital and physical product selling
* NPR and USD pricing
* Manual and automated payments
* Admin dashboard
* Seller-friendly product management
* Customer order tracking
* Multi-store support
* SaaS model for businesses
* White-label Telegram shop bot system

Nexbot is built to become more than a Telegram bot.

It is a complete chat-commerce operating system for modern sellers.
