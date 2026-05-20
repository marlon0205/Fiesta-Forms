# Database & Models

This document details the database schema, migration history, and Eloquent model relationships.

## Schema Overview

The database is structured to support a hierarchical survey system:
`User` -> `Survey` -> `Question` -> `AnswerOption`

The voting system records interactions:
`User` + `Survey` -> `Vote` -> `VoteAnswer` (links to `Question` and `AnswerOption`)

## Core Tables

| Table Name | Description | Key Columns |
| :--- | :--- | :--- |
| `users` | Application users. | `user_id`, `name`, `email`, `password` |
| `surveys` | Survey metadata. | `survey_id`, `title`, `description`, `user_id`, `is_active` |
| `questions` | Questions within a survey. | `question_id`, `survey_id`, `question_text` |
| `answer_options` | Available choices for a question. | `option_id`, `question_id`, `option_text` |
| `votes` | Record of a user voting on a survey. | `vote_id`, `survey_id`, `user_id` |
| `vote_answers` | The specific options chosen in a vote. | `vote_answer_id`, `vote_id`, `question_id`, `option_id` |
| `product_categories`| Categories for grouping surveys. | `product_category_id`, `name` |
| `service_categories`| Categories for grouping surveys. | `service_category_id`, `name` |

## Model Relationships

### Survey Model
-   `user()`: BelongsTo `User` (The creator)
-   `questions()`: HasMany `Questions`
-   `votes()`: HasMany `Votes`
-   `serviceCategory()`: BelongsTo `Service_Categories`
-   `productCategory()`: BelongsTo `Product_Categories`

### Questions Model
-   `survey()`: BelongsTo `Survey`
-   `answerOptions()`: HasMany `AnswerOptions`
-   `voteAnswers()`: HasMany `VoteAnswers`

### Votes Model
-   `user()`: BelongsTo `User`
-   `survey()`: BelongsTo `Survey`
-   `answers()`: HasMany `VoteAnswers`

### VoteAnswers Model
-   `vote()`: BelongsTo `Votes`
-   `question()`: BelongsTo `Questions`
-   `answerOption()`: BelongsTo `AnswerOptions`

## Migrations History

1.  **Foundational Tables**: Users, Cache, Jobs (Laravel defaults).
2.  **Category Tables**: Product and Service categories.
3.  **Survey Core**: Surveys, Questions, Answer Options.
4.  **Voting System**: Votes and Vote Answers.
5.  **Permissions**: Spatie's permission table migrations.
