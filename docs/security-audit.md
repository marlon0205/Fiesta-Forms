# Security Audit — Fiesta-Forms

**Date:** 2026-05-19  
**Branch:** `41-refactoring-secruity-check-detailed-documentation`  
**Auditor:** ruflo security-auditor agent

---

## Executive Summary

17 files reviewed. 9 vulnerabilities found across critical, high, medium, and low severity. All fixable issues have been fixed in this branch. No SQL injection was found (all `DB::raw` usages pass values as bound parameters). CSRF is globally enforced by Laravel's middleware. Login rate-limiting is correctly implemented.

---

## Findings

### CRITICAL — Hardcoded Bearer Token

**File:** `app/Http/Controllers/API/ApiDemoController.php`  
**Status:** ✅ Fixed

`demo-token-123` was hardcoded in source, visible in version control. Token is now read from `config('services.demo_api.token')` → `env('DEMO_API_TOKEN')`. Comparison uses `hash_equals()` to prevent timing attacks.

**Action required:** Add `DEMO_API_TOKEN=<random-secret>` to `.env`. Generate with:
```bash
openssl rand -hex 32
```

---

### HIGH — Server-Side Request Forgery (SSRF)

**File:** `app/Http/Controllers/AdminController.php`, `syncCategories()`  
**Status:** ✅ Fixed

Admin form accepted arbitrary `api_url` and passed it directly to `Http::get()`. An attacker with admin access could fetch internal services (AWS metadata at `169.254.169.254`, `localhost`, private ranges).

Fix: `isSafeUrl()` private method added. Validates URL, blocks loopback/private IPs via `FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE`.

---

### HIGH — IDOR on Vote Submission

**File:** `app/Http/Controllers/SurveyController.php`, `vote()`  
**Status:** ✅ Fixed

`exists:answer_options,option_id` validation only checked that an option existed globally. Attacker could submit option IDs from a different survey. Now validates that each `question_id` belongs to the current survey, and each `option_id` belongs to that question.

---

### HIGH — Voting Allowed on Inactive Surveys

**File:** `app/Http/Controllers/SurveyController.php`, `vote()`  
**Status:** ✅ Fixed

No `is_active` check before accepting votes. Added guard at the top of `vote()`.

---

### MEDIUM — Unauthenticated REST API Endpoint

**File:** `routes/web.php`, `GET /api/surveys/{survey}`  
**Status:** ⚠️ Requires decision

Route has no auth or rate limiting. Anyone can enumerate survey data by iterating IDs.

**Recommended fix** — add auth middleware:
```php
Route::get('/api/surveys/{survey}', [ApiSurveyController::class, 'show'])
    ->middleware(['auth']);
```
Or if intentionally public, add rate limiting: `->middleware(['throttle:60,1'])`.

---

### MEDIUM — XSS via Flash Messages in Admin Panel

**File:** `resources/views/cyber/admin.blade.php`  
**Status:** ✅ Fixed

`{!! json_encode(session('success')) !!}` was vulnerable to script injection via survey titles containing `</script>`. Replaced with `{{ Js::from() }}` which applies `JSON_HEX_TAG` encoding.

Also: `SurveyController::destroy` passed `$surveyTitle` (user-controlled) directly into flash messages — that string now flows through the fixed renderer safely.

---

### MEDIUM — No Rate Limiting on Vote Endpoint

**File:** `routes/web.php`  
**Status:** ✅ Fixed

`POST /dashboard/form/{survey}/vote` now has `throttle:10,1` middleware (10 requests per minute per user).

---

### LOW — Exception Details Exposed to Users

**Files:** `app/Http/Controllers/SurveyController.php`, `app/Http/Controllers/AdminController.php`  
**Status:** ✅ Fixed

`$e->getMessage()` was included in user-facing flash messages. Now logged via `Log::error()` and replaced with generic messages.

---

### LOW — `is_active` in User `$fillable` Without Enforcement

**File:** `app/Models/User.php`  
**Status:** ⚠️ Requires decision

`is_active` is fillable but never checked during login. It is set to `false` on registration but has no effect on authentication.

Two options:
1. Remove from `$fillable` if activation is not a real feature
2. Add `EnsureUserIsActive` middleware to auth routes if account activation should be enforced

---

## Confirmed Safe

| Area | Status |
|------|--------|
| CSRF | ✅ Global `VerifyCsrfToken` enforced |
| SQL Injection | ✅ All raw queries use bound parameters |
| Password Hashing | ✅ `Hash::make()` + `hashed` cast on User model |
| Login Rate Limiting | ✅ 5-attempt limit by email + IP in `LoginRequest` |
| Mass Assignment on Registration | ✅ Explicit array construction, no `$request->all()` |
| Admin Middleware | ✅ Spatie `hasRole('admin')`, 403 on failure |
| Session Fixation | ✅ `session()->regenerate()` on login |

---

## Required Manual Actions

1. Set `DEMO_API_TOKEN` in `.env` (generate with `openssl rand -hex 32`)
2. Decide: authenticate or rate-limit `GET /api/surveys/{survey}`
3. Decide: enforce `is_active` at login or remove from `$fillable`
4. Audit all `showModal()` call sites — avoid `innerHTML` with server-generated content
