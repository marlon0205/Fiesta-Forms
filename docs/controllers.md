# Controllers

This document describes the application controllers and their responsibilities.

## SurveyController
-   **Path**: `app/Http/Controllers/SurveyController.php`
-   **Description**: The primary controller handling the survey lifecycle and voting mechanism.
-   **Methods**:
    -   `index()`: Renders the admin dashboard with a paginated, sortable list of all surveys.
    -   `show(Survey $survey)`: Displays the detail page for a survey. It pre-calculates voting results (counts and percentages) for display.
    -   `vote(Request $request, Survey $survey)`: Processes a user's vote. It includes validation to ensure one vote per user and that all questions are answered. Uses a database transaction to ensure data integrity.
    -   `createView()`: Returns the view for creating a new survey, including available categories.
    -   `store(Request $request)`: Validates and persists a new survey along with its questions and answer options.
    -   `editView(Survey $survey)`: Returns the edit view for an existing survey.
    -   `update(Request $request, Survey $survey)`: Updates an existing survey. **Note**: If questions are changed, existing votes are reset to maintain data consistency.
    -   `destroy(Survey $survey)`: Safely deletes a survey and all its cascading dependencies (votes, questions, options) within a transaction.

## ProfileController
-   **Path**: `app/Http/Controllers/ProfileController.php`
-   **Description**: Manages user profile information.
-   **Methods**:
    -   `edit()`: Displays the profile edit form.
    -   `update()`: Updates user name and email (includes email verification logic).
    -   `updatePassword()`: Handles password changes with strict validation.
    -   `destroy()`: Allows a user to delete their account.

## Auth Controllers
-   **Path**: `app/Http/Controllers/Auth/`
-   **Description**: standard Laravel Breeze controllers handling:
    -   `AuthenticatedSessionController`: Login and logout.
    -   `RegisteredUserController`: New user registration.
    -   `PasswordController` & `NewPasswordController`: Password reset logic.
    -   `EmailVerificationPromptController` & `VerifyEmailController`: Email verification.

## ApiSurveyController
-   **Path**: `app/Http/Controllers/API/ApiSurveyController.php`
-   **Description**: Handles REST API requests.
-   **Methods**:
    -   `show(Survey $survey)`: Returns a JSON representation of a survey and its questions.
