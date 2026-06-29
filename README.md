# Bodaboda Pay

A motorcycle loan management system for the bodaboda (motorcycle-taxi) "work-and-pay" financing model. Built with Laravel 12.

Owners lend motorcycles to drivers under an installment contract. Drivers ride and repay in installments until the loan is settled. The system tracks contracts, generates installment schedules, records payments, and allocates them across installments automatically.

## Roles

- **Admin** — oversees all users and contracts system-wide.
- **Owner** — manages a motorcycle fleet, creates loan contracts, issues enrolment keys to drivers, records payments.
- **Driver** — enrols into a contract with a one-time key, tracks their loan balance and payment history.

## Tech Stack

- Laravel 12 / PHP 8.2
- MySQL
- Tailwind CSS v4
- Alpine.js (CDN, no Livewire)
- bcmath for all money arithmetic (no floating-point errors on currency)

## Setup

1. **Create the database** (MySQL must be running — e.g. via XAMPP):
   ```sql
   CREATE DATABASE bodaboda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Configure environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   `.env.example` defaults to `root` with no password (XAMPP default). Adjust `DB_USERNAME` / `DB_PASSWORD` in `.env` if your MySQL setup differs.

3. **Migrate and seed demo data**:
   ```bash
   php artisan migrate --seed
   ```

4. **Run**:
   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000`.

`vendor/` and the compiled frontend (`public/build/`) are already committed, so `composer install` / `npm install` / `npm run build` are not required to run the app. They're only needed if you intend to modify PHP dependencies or frontend assets (`npm run dev` for live CSS/JS reload).

## Demo Accounts

All seeded with password `password`. The login page has a clickable panel that fills these in automatically.

| Role   | Email                  |
|--------|-------------------------|
| Admin  | admin@bodaboda.test    |
| Owner  | owner@bodaboda.test    |
| Driver | driver@bodaboda.test   |

## Core Domain Flow

1. Owner adds a motorcycle to their fleet.
2. Owner creates a loan contract against that motorcycle (principal, markup, installment amount/frequency) — an installment schedule is generated automatically.
3. Owner generates a one-time enrolment key for the contract.
4. Driver claims the key to activate the contract and gets visibility into their schedule and balance.
5. Owner records payments as they come in; each payment is allocated FIFO across outstanding installments, and the contract is marked completed once the balance reaches zero.
