# Architecture

This document describes the high-level architecture and technology stack of the Fiesta-Forms project.

## Tech Stack

-   **Backend Framework**: [Laravel 12](https://laravel.com/)
-   **Language**: PHP 8.2+
-   **Database**: Likely MySQL or PostgreSQL (standard Laravel relational setup)
-   **Frontend**:
    -   [Blade Templates](https://laravel.com/docs/12.x/blade)
    -   [Tailwind CSS](https://tailwindcss.com/)
    -   [Vite](https://vitejs.dev/)
-   **Authentication**: [Laravel Breeze](https://laravel.com/docs/12.x/starter-kits#laravel-breeze)
-   **Permissions**: [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6/introduction)
-   **Testing**: [Pest PHP](https://pestphp.com/)

## System Components

### 1. Web Layer (Blade/Controllers)
The web layer handles traditional HTTP requests, rendering Blade templates for the user interface. It is divided into:
-   **Public Routes**: Home, Explore, and Survey details.
-   **Protected Routes**: User profile and voting (requires `auth` middleware).
-   **Admin Routes**: Survey creation and management (requires `auth` and `admin` middleware).

### 2. API Layer
A REST-style API provides programmatic access to survey data. Currently, it includes endpoints for retrieving survey details.

### 3. Business Logic (Models/Controllers)
The business logic is primarily contained within the Eloquent Models and the `SurveyController`. The `SurveyController` is the central hub for managing the survey lifecycle, from creation to data collection (voting).

### 4. Security & Authentication
-   **Breeze**: Provides robust session-based authentication.
-   **Spatie Permissions**: Implements fine-grained access control using roles and permissions.
-   **Middlewares**:
    -   `auth`: Ensures the user is logged in.
    -   `verified`: Ensures the user's email is verified.
    -   `admin`: Custom middleware to restrict access to administrative actions.

## Design Patterns

-   **MVC (Model-View-Controller)**: The core pattern followed by Laravel.
-   **Route Model Binding**: Heavily used for automatic injection of Model instances into controller methods based on URL parameters.
-   **Fat Models / Skinny Controllers**: The project aims to keep logic within Models where appropriate, though the `SurveyController` currently holds significant logic.
-   **Factory Pattern**: Used in testing and database seeding to generate consistent dummy data.
