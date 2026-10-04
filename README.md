# Tasks for Today — TSA2

An independent CodeIgniter 4 application continued from the Technical 1 LokalCart POS foundation for IT0049 TSA2. It preserves the original shared layout and teal-and-mint design while adding a task database, full task CRUD, authentication, and soft deletion. The original repository and database are not used at runtime.

## Requirements

PHP 8.2+, Composer, intl, mbstring, and SQLite3. MySQL/MariaDB is optional. The included migration supports a fresh SQLite or MySQL database.

## Quick start

Run these commands from this repository:

```powershell
composer install
Copy-Item .env.example .env
php spark migrate
php spark db:seed DemoSeeder
php spark serve --port 8082
```

Open http://localhost:8082/. Sign in as `demo` with password `TasksToday2026!`.

On this computer, PHP is `C:\php\php.exe`. SQLite is available but not enabled in php.ini, so use the temporary extension flag:

```powershell
C:\php\php.exe -d extension=sqlite3 spark migrate
C:\php\php.exe -d extension=sqlite3 spark db:seed DemoSeeder
C:\php\php.exe -d extension=sqlite3 -S localhost:8082 -t public vendor/codeigniter4/framework/system/rewrite.php
```

The last command runs the local development server directly so its PHP process retains the SQLite flag. Stop it with Ctrl+C. Alternatively enable `extension=sqlite3` in your PHP configuration and use the normal Spark command.

The independent local database is `writable/tasks-for-today.sqlite`. It is excluded from Git, as are `.env`, sessions, logs, and dependencies. Copying `.env.example` is only needed on first setup. The seeder creates four sample tasks and one account; rerunning it does not replace existing records or passwords.

Set `DEMO_PASSWORD` before the first seed to choose a different demo password. The seeder stores a `password_hash()` hash, and login uses `password_verify()`.

## MySQL alternative

Create a new empty database named `tasks_for_today_tsa2`, uncomment the MySQL settings in `.env.example` when preparing `.env`, and enter that database's credentials. Then run migrate and seed. Do not point this project at a Technical 1 or later LokalCart database. The migration is for this independent project's fresh database, not an in-place upgrade of another application's schema.

## Pages and actions

| Route | Access | Purpose |
| --- | --- | --- |
| GET / | Public | Welcome and today's non-archived tasks |
| GET /tasks | Public | All non-archived tasks |
| GET /profile | Public | Demo account's public profile |
| GET /about | Public | Project description |
| GET, POST /login | Public | Sign in |
| POST /logout | Session action | Sign out |
| GET /tasks/new | Signed in | New task form |
| POST /tasks | Signed in | Create task |
| GET /tasks/{id}/edit | Signed in | Edit task form |
| POST /tasks/{id} | Signed in | Update task |
| POST /tasks/{id}/delete | Signed in | Set is_archived to true |

Title and date are required. Titles allow up to 150 characters; descriptions allow 2,000. Dates must be real YYYY-MM-DD dates, and status must be Pending, In progress, or Completed. Invalid submissions show errors and preserve safe input. Missing or archived task IDs return 404.

All signed-in accounts manage the shared task list, matching the assessment's authentication requirement. Ownership restrictions and registration are not part of this milestone. The interface calls the delete action **Archive** because the row is preserved. Archived records are omitted from both public lists and cannot be edited through the normal routes.

Explicit routes and an authentication filter protect every management action. Forms use session-based CSRF tokens, login regenerates the session ID, logout removes authentication and destroys the session, and rendered user text is escaped. Pages containing session-dependent navigation are not cached. Passwords are never repopulated into forms or placed in the public profile. The application timezone is Asia/Manila.

## Verification

```powershell
C:\php\php.exe -d extension=sqlite3 vendor/bin/phpunit
C:\php\php.exe -d extension=sqlite3 spark routes
```

The suite uses an in-memory SQLite database, separate from the local demo. It covers public pages, escaped output, all five protected routes, login/logout, create/update, invalid input, missing records, CSRF rejection, method restrictions, and archiving without deleting rows.

Manual walkthrough:

1. Signed out, view Welcome, Task List, Profile, and About. Open `/tasks/new` and confirm the login redirect.
2. Try an incorrect password, then sign in with the demo account.
3. Create a task dated today. Confirm it appears on Welcome and Task List.
4. Edit its title, date, description, and status. Confirm the changes persist.
5. Archive it. Confirm it disappears from both lists and its database row has `is_archived = 1`.
6. Sign out and confirm the management forms redirect to login again.

## Repository and hosting

This directory is a separate Git repository with its own history and no inherited remote. To publish it, create a new GitHub repository named `tasks-for-today-tsa2`, then add that new remote and push `main`. Never use the original LokalCart remote.

The assignment also asks for a hosted URL. Hosting is not configured by the local setup. A host needs PHP 8.2+, the required extensions, Composer dependencies, a document root pointing to `public/`, and writable storage. Set `CI_ENVIRONMENT=production` and `app.baseURL` to the HTTPS site URL. Use persistent storage for SQLite, or a separate MySQL database. Run migrations and seed on that new database, with a chosen demo password. Do not expose the project root or use PHP's development server for public hosting.

## Structure

- `app/Controllers/`: page rendering, authentication, and task actions
- `app/Models/`: users and task persistence
- `app/Filters/AuthFilter.php`: management-route access control
- `app/Database/Migrations/`: schema for users and tasks
- `app/Database/Seeds/`: hashed demo account and sample tasks
- `app/Views/`: shared layout, public pages, and forms
- `public/assets/css/`: original design foundation and task styles
- `tests/feature/PagesTest.php`: behavioral and security tests

## References

- [CodeIgniter validation](https://codeigniter4.github.io/userguide/libraries/validation.html)
- [CodeIgniter controller filters](https://codeigniter4.github.io/userguide/incoming/filters.html)
