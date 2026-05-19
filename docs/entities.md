# Entities

This document provides a detailed description of the core business entities (Models) in Fiesta-Forms.

## User
-   **Class**: `App\Models\User`
-   **Description**: Represents a user of the application. Users can be regular voters or administrators.
-   **Key Properties**:
    -   `user_id`: Primary key.
    -   `name`: Display name.
    -   `email`: Unique email address.
    -   `is_active`: Boolean status of the user account.
    -   `email_verified_at`: Timestamp of email verification.
-   **Traits**: `HasFactory`, `Notifiable`, `HasRoles`, `MustVerifyEmail`.

## Survey
-   **Class**: `App\Models\Survey`
-   **Description**: The central entity representing a questionnaire.
-   **Key Properties**:
    -   `survey_id`: Primary key.
    -   `title`: Title of the survey.
    -   `description`: Longer text describing the survey's purpose.
    -   `user_id`: Foreign key to the creator (User).
    -   `service_category_id`: Foreign key to `Service_Categories`.
    -   `product_category_id`: Foreign key to `Product_Categories`.
    -   `duration_days`: Number of days the survey is active.
    -   `is_active`: Boolean status of the survey.

## Questions
-   **Class**: `App\Models\Questions`
-   **Description**: A single question within a survey.
-   **Key Properties**:
    -   `question_id`: Primary key.
    -   `survey_id`: Foreign key to the parent `Survey`.
    -   `question_text`: The text of the question.

## AnswerOptions
-   **Class**: `App\Models\AnswerOptions`
-   **Description**: A selectable choice for a question.
-   **Key Properties**:
    -   `option_id`: Primary key.
    -   `question_id`: Foreign key to the parent `Question`.
    -   `option_text`: The text of the option.

## Votes
-   **Class**: `App\Models\Votes`
-   **Description**: Records an instance of a user participating in a survey.
-   **Key Properties**:
    -   `vote_id`: Primary key.
    -   `survey_id`: Foreign key to the `Survey`.
    -   `user_id`: Foreign key to the `User` who voted.

## VoteAnswers
-   **Class**: `App\Models\VoteAnswers`
-   **Description**: Stores the specific choice made for each question during a vote.
-   **Key Properties**:
    -   `vote_answer_id`: Primary key.
    -   `vote_id`: Foreign key to the parent `Vote`.
    -   `question_id`: Foreign key to the `Question` being answered.
    -   `option_id`: Foreign key to the chosen `AnswerOption`.

## Categories (Product & Service)
-   **Classes**: `App\Models\Product_Categories`, `App\Models\Service_Categories`
-   **Description**: Simple taxonomy for grouping surveys.
-   **Note**: These use non-standard table names (`product__categories` and `service__categories`).
