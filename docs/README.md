# Fiesta-Forms Documentation

Welcome to the official documentation for the Fiesta-Forms project. This project is a survey and voting application built with Laravel 12.

## Table of Contents

1.  [Architecture](architecture.md) - High-level overview of the tech stack and system design.
2.  [Database & Models](database.md) - Database schema, migrations, and Eloquent relationships.
3.  [Entities](entities.md) - Detailed description of the core business entities.
4.  [Controllers](controllers.md) - Logic and responsibilities of the application controllers.
5.  [Routes](routes.md) - Mapping of URLs to controllers and views.
6.  [Frontend](frontend.md) - UI stack, themes, and layout information.
7.  [API](api.md) - Documentation for the REST API endpoints.

## Project Overview

Fiesta-Forms is designed to allow users to create, share, and participate in surveys. It features a "Cyber" themed dashboard and robust administrative tools for managing surveys, questions, and categories.

### Key Features

-   **Survey Management**: Create, edit, and delete surveys with multiple questions and answer options.
-   **Voting System**: Single-vote-per-user enforcement with rate limiting (10 req/min) and DB transaction wrapping.
-   **Role-Based Access**: `admin`, `customer`, and `guest` roles via Spatie Laravel Permission.
-   **Admin Dashboard**: Tabbed panel (Forms, Users, Rewards, Integrations) with sortable, paginated tables.
-   **User Management**: Admins can view, edit, reset passwords, and delete any user account.
-   **Rewards System**: Define point-threshold rewards tied to user `vote_count`.
-   **Category Integration**: Import product/service categories from an external REST API or raw JSON (with SSRF protection).
-   **REST API**: JSON endpoints for survey data (`/api/surveys/{id}`) and category listings (`/api/demo/categories`).
-   **Modern UI**: Responsive Cyber-themed design with Tailwind CSS.
