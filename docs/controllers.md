# Controllers

This document describes the application controllers and their responsibilities.

## AdminController
-   **Path**: `app/Http/Controllers/AdminController.php`
-   **Description**: Manages the admin dashboard, rewards system, user oversight, and category integrations.
-   **Methods**:
    -   `index()`: Renders the admin panel (`cyber.admin` view). Passes three paginated, sortable datasets:
        -   `$surveys`: with vote/question counts; sortable by `title`, `questions`, `responses`, `last_response`, `status`, `created`.
        -   `$users`: with roles, vote count, last activity (joined from `sessions`); sortable by `name`, `email`, `role`, `votes`, `last_activity`, `created`.
        -   `$rewards`: ordered by `points_required`.
        -   Active tab controlled via `?tab=` query param (`forms`, `users`, `rewards`, `integrations`).
    -   `storeReward(Request)`: Validates and creates a `Reward`. `points_required` must be unique.
    -   `updateReward(Request, Reward)`: Updates reward fields; ignores own ID in unique check for `points_required`.
    -   `destroyReward(Reward)`: Deletes a reward.
    -   `syncCategories(Request)`: Imports product/service categories from an external REST API (optional Bearer token) or raw JSON input. Uses a DB transaction and `firstOrCreate` to avoid duplicates. Validates the source URL for SSRF (see `isSafeUrl`).
    -   `isSafeUrl(string)` *(private)*: Blocks localhost, `127.0.0.1`, `0.0.0.0`, `::1`, and private/reserved IP ranges to prevent SSRF attacks.

## SurveyController
-   **Path**: `app/Http/Controllers/SurveyController.php`
-   **Description**: Handles the survey lifecycle and voting mechanism.
-   **Methods**:
    -   `show(Survey $survey)`: Displays the survey detail page with pre-calculated vote results (counts and percentages per option).
    -   `vote(Request $request, Survey $survey)`: Processes a user vote. Enforces one vote per user, requires all questions to be answered, wrapped in a DB transaction.
    -   `createView()`: Returns the survey creation view with available categories.
    -   `store(Request $request)`: Validates and persists a new survey with its questions and answer options.
    -   `editView(Survey $survey)`: Returns the survey edit view.
    -   `update(Request $request, Survey $survey)`: Updates a survey. If questions are modified, all existing votes are deleted to maintain consistency.
    -   `destroy(Survey $survey)`: Deletes a survey and all cascading data (votes, questions, options) within a DB transaction.

## ProfileController
-   **Path**: `app/Http/Controllers/ProfileController.php`
-   **Description**: Manages user profile operations — both self-service and admin-initiated.
-   **Methods** (self-service):
    -   `edit()`: Displays the profile edit form for the authenticated user.
    -   `update(Request)`: Updates name and email (triggers re-verification if email changed).
    -   `updatePassword(Request)`: Handles password changes with current-password validation.
    -   `destroy(Request)`: Deletes the authenticated user's account.
-   **Methods** (admin-only):
    -   `show(User)`: Admin view of any user's profile.
    -   `editUser(User)`: Admin edit form for any user.
    -   `updateUser(Request, User)`: Admin updates a user's name, email, role, and `is_active` status.
    -   `updateUserPassword(Request, User)`: Admin resets any user's password.
    -   `destroyUser(User)`: Admin deletes any user account.

## Auth Controllers
-   **Path**: `app/Http/Controllers/Auth/`
-   **Description**: Standard Laravel Breeze controllers.
    -   `AuthenticatedSessionController`: Login and logout.
    -   `RegisteredUserController`: New user registration.
    -   `PasswordController` & `NewPasswordController`: Password reset flow.
    -   `EmailVerificationPromptController` & `VerifyEmailController`: Email verification.

## ApiSurveyController
-   **Path**: `app/Http/Controllers/API/ApiSurveyController.php`
-   **Description**: REST API for survey data.
-   **Methods**:
    -   `show(Survey $survey)`: Returns a JSON representation of a survey including questions and answer options.

## ApiDemoController
-   **Path**: `app/Http/Controllers/API/ApiDemoController.php`
-   **Description**: Demo endpoint for integration testing.
-   **Methods**:
    -   `categories()`: Returns all product and service categories as JSON.
