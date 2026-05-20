# API

This document describes the REST API endpoints available in Fiesta-Forms.

## Overview
All API routes are defined in `routes/web.php` (not `api.php`) and are currently public (no authentication required).

**Base URL**: `http://localhost` (dev)

---

## Endpoints

### GET /api/surveys/{survey}
Returns a survey with its questions and answer options.

-   **Auth Required**: No
-   **URL Params**: `survey` — the `survey_id` of the survey.

**Response (JSON)**:
```json
{
    "survey_id": 1,
    "title": "Example Survey",
    "description": "A sample description.",
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
                { "option_id": 101, "option_text": "Very Satisfied" },
                { "option_id": 102, "option_text": "Neutral" }
            ]
        }
    ]
}
```

---

### GET /api/demo/categories
Returns all product and service categories. Used for integration testing and the admin category import feature.

-   **Auth Required**: No
-   **Controller**: `ApiDemoController@categories`

**Response (JSON)**:
```json
{
    "product_categories": [
        { "product_category_id": 1, "name": "Electronics" }
    ],
    "service_categories": [
        { "service_category_id": 1, "name": "Consulting" }
    ]
}
```

---

## Security & Rate Limiting

-   **Voting endpoint** (`POST /dashboard/form/{survey}/vote`): Rate-limited to **10 requests per minute** per user (`throttle:10,1`). Requires authentication.
-   **API endpoints**: Currently public. No token required.
-   **Category sync** (`POST /dashboard/admin/integrations`): SSRF protection blocks requests to localhost, private IP ranges, and reserved addresses.
