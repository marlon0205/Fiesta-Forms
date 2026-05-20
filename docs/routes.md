# Routes

This document maps application URLs to their respective controllers and views.

## Web Routes

### Public Routes
| Method | URI | Action | Name |
| :--- | :--- | :--- | :--- |
| GET | `/` | Redirects to `dashboard.home` | - |
| GET | `/dashboard` | `view('cyber.home')` | `dashboard.home` |
| GET | `/dashboard/explore` | `view('cyber.explore')` | `dashboard.explore` |
| GET | `/dashboard/form/{survey}` | `SurveyController@show` | `dashboard.form-detail` |

### User Routes (Authenticated)
| Method | URI | Action | Name |
| :--- | :--- | :--- | :--- |
| POST | `/dashboard/form/{survey}/vote` | `SurveyController@vote` | `dashboard.form.vote` |
| GET | `/dashboard/profile` | `ProfileController@edit` | `dashboard.profile` |
| GET | `/profile-edit` | `ProfileController@edit` | `profile.edit` |
| PATCH | `/profile-edit` | `ProfileController@update` | `profile.update` |
| DELETE | `/profile-edit` | `ProfileController@destroy` | `profile.destroy` |

### Admin Routes (Auth + Admin Middleware)
| Method | URI | Action | Name |
| :--- | :--- | :--- | :--- |
| GET | `/dashboard/admin` | `SurveyController@index` | `dashboard.admin` |
| DELETE | `/dashboard/admin/survey/{survey}` | `SurveyController@destroy` | `dashboard.admin.survey.destroy` |
| GET | `/admin/survey/create` | `SurveyController@createView` | `admin.survey.create` |
| POST | `/admin/survey` | `SurveyController@store` | `admin.survey.store` |
| GET | `/admin/survey/edit/{survey}` | `SurveyController@editView` | `admin.survey.edit` |
| PATCH | `/admin/survey/{survey}` | `SurveyController@update` | `admin.survey.update` |

## API Routes
| Method | URI | Action | Name |
| :--- | :--- | :--- | :--- |
| GET | `/api/surveys/{survey}` | `ApiSurveyController@show` | - |

**Note**: `ApiSurveyController` appears to be referenced in `web.php` but may not yet be implemented in the `app/Http/Controllers/API` directory.

## Auth Routes (Breeze)
Standard authentication routes are defined in `routes/auth.php`, including:
-   `/register`
-   `/login`
-   `/forgot-password`
-   `/reset-password`
-   `/verify-email`
-   `/logout`
