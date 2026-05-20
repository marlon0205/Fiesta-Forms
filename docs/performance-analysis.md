# Performance Analysis — Fiesta-Forms

**Date:** 2026-05-19  
**Analyst:** ruflo perf-engineer agent + manual fixes applied

---

## Summary

6 performance issues found and fixed. Most severe was an N+1 in `SurveyController::show` firing one COUNT query per answer option per question. A migration adding 14 missing indexes was created.

---

## Fixes Applied

### N+1: SurveyController::show

**File:** `app/Http/Controllers/SurveyController.php`

**Before:** `$question->voteAnswers()->where('option_id', $option->option_id)->count()` called inside nested loop — O(questions × options) queries.

**After:** Eager-load `questions.voteAnswers`, then group by `option_id` in PHP collection. Single additional query regardless of survey size.

```
Before: 1 + (questions × options) queries
After:  4 queries (survey + questions + answerOptions + voteAnswers)
```

---

### N+1: RecentActivity Component

**File:** `app/View/Components/Dashboard/RecentActivity.php`

**Before:** `votes()->count()` + lazy-loaded `serviceCategory`/`productCategory` inside `map()` — 1 + 9 queries for 3 surveys.

**After:** `with(['serviceCategory', 'productCategory'])->withCount('votes')` — 3 queries total. Uses `votes_count` from aggregate.

---

### N+1: Explore Component — votes loading

**File:** `app/View/Components/Survey/Explore.php`

**Before:** `Survey::with(['productCategory', 'serviceCategory', 'votes'])` loaded all vote rows into memory just to call `->count()`.

**After:** `withCount('votes')` — single COUNT aggregate folded into main query. Uses `votes_count`.

---

### TopUsers Component — 2-query pattern

**File:** `app/View/Components/Dashboard/TopUsers.php`

**Before:** Group votes by `user_id`, then eager-load `user` relationship — 2 queries.

**After:** Single `JOIN users` query with `COUNT(vote_id)` and `GROUP BY users.user_id, users.name`.

---

### Delete Loop Optimization

**File:** `app/Http/Controllers/SurveyController.php`, `update()` and `destroy()`

**Before:** `foreach ($survey->questions as $question) { $question->answerOptions()->delete(); }` — 1 DELETE per question.

**After:** `AnswerOptions::whereIn('question_id', $survey->questions()->pluck('question_id'))->delete()` — single DELETE query.

---

### Missing Database Indexes

**Migration:** `database/migrations/2026_05_19_100001_add_performance_indexes.php`

`foreignId()->constrained()` creates a FK constraint but NOT an index on the referencing column in PostgreSQL. These columns were missing indexes:

| Table | Column | Reason |
|-------|--------|--------|
| `surveys` | `user_id` | FK join, creator filter |
| `surveys` | `is_active` | Explore filter |
| `surveys` | `created_at` | `->latest()` sort everywhere |
| `questions` | `survey_id` | FK, eager load |
| `answer_options` | `question_id` | FK, eager load |
| `votes` | `survey_id` | FK, counted on every survey |
| `votes` | `user_id` | FK, duplicate-vote check |
| `votes` | `(survey_id, user_id)` | Composite unique — enforces one-vote-per-survey at DB level |
| `vote_answers` | `vote_id` | FK cascade, eager load |
| `vote_answers` | `question_id` | FK, voteAnswers eager load |
| `vote_answers` | `option_id` | FK, per-option lookups |
| `service__categories` | `name` | Category resolution by name |
| `product__categories` | `name` | Category resolution by name |

Run `./vendor/bin/sail artisan migrate` to apply.

---

## Remaining Recommendations

### StatsOverview: Cache aggregate counts

`app/View/Components/Dashboard/StatsOverview.php` runs 4 COUNT queries on every page load. Wrap in `Cache::remember('stats_overview', 300, fn() => [...])` for a 5-minute TTL.

### Explore: whereHas → direct column filter

`whereHas('productCategory', fn($q) => $q->where('name', ...))` generates a correlated subquery. Since `product_category_id` is on the surveys table, resolve the ID first then filter: `$query->where('product_category_id', $id)`.

### categoryNames() memoization

`SurveyController::categoryNames()` issues 2 queries each call. `surveyValidationRules()` calls it; so does each view. Cache result in a private property or use Laravel's `once()` helper.
