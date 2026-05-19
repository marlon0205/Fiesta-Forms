# API

This document describes the REST API endpoints available in Fiesta-Forms.

## Overview
The API provides programmatic access to survey data. Currently, it is used primarily for fetching survey details and questions.

**Base URL**: `/api`

## Endpoints

### Get Survey Details
Returns the details of a specific survey, including its questions and answer options.

-   **URL**: `/api/surveys/{survey_id}`
-   **Method**: `GET`
-   **Auth Required**: No (currently public as per `web.php` reference)
-   **URL Params**:
    -   `survey_id` (Integer): The unique ID of the survey.

**Response Body (JSON)**:
```json
{
    "survey_id": 1,
    "title": "Example Survey",
    "description": "This is a sample description.",
    "is_active": true,
    "service_category": {
        "service_category_id": 1,
        "name": "Consulting"
    },
    "questions": [
        {
            "question_id": 10,
            "question_text": "How satisfied are you?",
            "answer_options": [
                {
                    "option_id": 101,
                    "option_text": "Very Satisfied"
                },
                {
                    "option_id": 102,
                    "option_text": "Neutral"
                }
            ]
        }
    ]
}
```

### Get Current User
Returns the authenticated user's information.

-   **URL**: `/api/user`
-   **Method**: `GET`
-   **Auth Required**: Yes (Sanctum/Session)

**Response Body (JSON)**:
```json
{
    "user_id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": "2026-05-16T12:00:00.000000Z"
}
```

## Security & Rate Limiting
-   **Authentication**: Managed via Laravel Sanctum (for token-based access) or standard session cookies (for web-based API calls).
-   **Middleware**: The API routes are protected by the `api` middleware group, which includes rate limiting by default.
