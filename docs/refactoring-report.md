# Refactoring Report — Fiesta-Forms

**Date:** 2026-05-19  
**Reviewer:** ruflo code-reviewer agent + manual fixes applied

---

## Summary

16 files reviewed. Issues ranged from dead code and duplicated validation logic to Laravel naming convention violations and fat view components doing DB queries in constructors.

---

## Fixed

### Duplicate Validation in store() / surveyValidationRules()

**File:** `app/Http/Controllers/SurveyController.php`  
**Severity:** High

`store()` had its own inline validation rules (with `description: required`) diverging from `surveyValidationRules()` (with `description: nullable`). `store()` also duplicated the questions/options creation loop that `createQuestionsAndOptions()` already encapsulated.

**Fix:** `store()` now calls `$this->surveyValidationRules()` and `$this->createQuestionsAndOptions()`. Single source of truth.

---

### Dead Code: showView()

**File:** `app/Http/Controllers/SurveyController.php`  
**Severity:** High

`showView()` had no return statement and no route pointing to it. Removed entirely.

---

### Duplicate PHPDoc Block

**File:** `app/Http/Controllers/SurveyController.php`  
**Severity:** Low

`createView()` had two identical `/** Shows the survey creation page. */` doc blocks stacked. Removed the duplicate.

---

### Error Messages Exposing Internals

**File:** `app/Http/Controllers/SurveyController.php`, `AdminController.php`  
**Severity:** High (Security overlap)

`$e->getMessage()` surfaced in user-facing flash messages. Fixed: log full message via `Log::error()`, show generic string to user.

---

### TopUsers Component: Incorrect DB Pattern

**File:** `app/View/Components/Dashboard/TopUsers.php`  
**Severity:** Medium

Used `->with('user')` after `->groupBy('user_id')` — valid but a two-query pattern. Replaced with a single JOIN query. See performance report.

---

### RecentActivity and Explore: DB Queries in Constructor

**Files:** `app/View/Components/Dashboard/RecentActivity.php`, `app/View/Components/Survey/Explore.php`  
**Severity:** Medium

N+1 queries removed. See performance report for details.

---

## Not Fixed — Requires Future Work

### Model Naming Violations

**Files:** `Product_Categories.php`, `Service_Categories.php`, `Questions.php`, `AnswerOptions.php`, `Votes.php`, `VoteAnswers.php`  
**Severity:** Medium

Laravel convention: singular PascalCase (`ProductCategory`, `Question`, `AnswerOption`, `Vote`, `VoteAnswer`). Renaming requires updating all references across controllers, views, migrations, and tests. Safe to do with full test coverage; deferred.

---

### No Form Request Classes for Survey Operations

**Severity:** Medium

Survey create, update, and vote operations use inline `$request->validate()` rather than dedicated Form Request classes. `store()` and `update()` now share `surveyValidationRules()` which helps, but extracting to `StoreSurveyRequest` / `UpdateSurveyRequest` would add authorization and keep controllers thin.

---

### AdminController: Fat syncCategories Method

**File:** `app/Http/Controllers/AdminController.php`  
**Severity:** Medium

`syncCategories()` handles HTTP fetching, JSON parsing, URL validation, and DB insertion. Extract to a `CategorySyncService` for testability and separation of concerns.

---

### ProfileController: Repeated Session/User Queries

**File:** `app/Http/Controllers/ProfileController.php`  
**Severity:** Low

Same raw sessions query and `load()`/`loadCount()` calls repeated across multiple methods. Extract to private helper method.

---

### Mixed Language Comments

**Files:** Multiple controllers  
**Severity:** Low

Several comments are in German (`// Lade die Beziehungen`, `// Gib die Blade-View zurück`). Standardize to English for team consistency.

---

## Laravel Best Practices Violated

| Practice | Status |
|---------|--------|
| Singular PascalCase model names | ❌ Not fixed (scope too large) |
| Form Request classes for validation | ❌ Not fixed (future work) |
| No duplicate validation rules | ✅ Fixed |
| No dead methods | ✅ Fixed |
| No exception messages in UI | ✅ Fixed |
| Eager loading to prevent N+1 | ✅ Fixed |
| Single responsibility in controllers | ⚠️ Partially — syncCategories still fat |
