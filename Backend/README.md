# VisitHunnasgiriya - PHP + MySQL backend

## 1. Install (WAMP)
1. Copy the `VisitHunnasgiriya` folder into `C:\wamp64\www\`.
2. Open phpMyAdmin, create/import the database from `database/visithunnasgiriya.sql`
   (database name: `visithunnasgiriya`).
   If the import says "Unknown collation utf8mb4_0900_ai_ci", open the .sql file in a text
   editor and replace `utf8mb4_0900_ai_ci` with `utf8mb4_general_ci`, then import again.
3. Check `config/db.php` (default: user `root`, empty password).
4. Copy your place photos into `assets/images/` (same file names as in the database).
5. Open http://localhost/VisitHunnasgiriya/

## 2. Login (admins and visitors use the same page)
Open http://localhost/visithunnasgiriyapro/Backend/login.php
- Admin: email in your `admins` table (admin@gmail.com) -> goes to the admin panel.
- Visitor: press "Register" to make an account, then "Login".
- Logout: link in the top menu (works for both).

**If the admin password does not work:** your database stores only a hash, so run
`database/reset_admin_password.sql` in phpMyAdmin (SQL tab). It sets the admin password to
`Admin@12345`. Log in, then change it under Admin > Password.

**Visitor accounts save day plans:** run `database/upgrade_users.sql` once in phpMyAdmin
(adds a `user_id` column to `itineraries`). Logged-in visitors can then save plans and open
them again from "My Plans".

## 3. Folders
- index.php, attraction.php, planner.php, emergency.php  = visitor site
- login.php, register.php, logout.php, change-password.php, my-plans.php = accounts
- admin/   = (login redirects to login.php), dashboard, places (add/edit/delete + photo upload), categories, tips, restaurants, emergency contacts
- includes/ = shared header/footer, helpers (functions.php), generic add/edit/delete page (crud.php)
- config/db.php = database connection

## 4. Security built in
Prepared statements (no SQL injection), password_verify + session_regenerate_id for login,
CSRF token on every form, output escaping, photo upload checks (type, size, random file name).

## 5. Notes
- Make sure `assets/images/` is writable so photo upload works.
- The day planner keeps the plan in the visitor's session; "Save plan" stores it in the
  `itineraries` and `itinerary_items` tables and gives a link: planner.php?plan=ID
- Visit/travel times are estimated from the text in your table ("1-2 hours", "20 minutes").
