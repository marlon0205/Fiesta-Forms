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
| Method | URI | Middleware | Action | Name |
| :--- | :--- | :--- | :--- | :--- |
| POST | `/dashboard/form/{survey}/vote` | `auth`, `throttle:10,1` | `SurveyController@vote` | `dashboard.form.vote` |
| GET | `/dashboard/profile` | `auth`, `verified` | `ProfileController@edit` | `dashboard.profile` |
| GET | `/profile-edit` | `auth` | `ProfileController@edit` | `profile.edit` |
| PATCH | `/profile-edit` | `auth` | `ProfileController@update` | `profile.update` |
| PUT | `/password-update` | `auth` | `ProfileController@updatePassword` | `password.update` |
| DELETE | `/profile-edit` | `auth` | `ProfileController@destroy` | `profile.destroy` |

### Admin Routes — Dashboard prefix (`/dashboard/admin`)
Middleware: `auth`, `verified`, `admin`

| Method | URI | Action | Name |
| :--- | :--- | :--- | :--- |
| GET | `/dashboard/admin` | `AdminController@index` | `dashboard.admin` |
| POST | `/dashboard/admin/integrations` | `AdminController@syncCategories` | `dashboard.admin.integrations.sync` |
| DELETE | `/dashboard/admin/survey/{survey}` | `SurveyController@destroy` | `dashboard.admin.survey.destroy` |
| POST | `/dashboard/admin/rewards` | `AdminController@storeReward` | `dashboard.admin.rewards.store` |
| PATCH | `/dashboard/admin/rewards/{reward}` | `AdminController@updateReward` | `dashboard.admin.rewards.update` |
| DELETE | `/dashboard/admin/rewards/{reward}` | `AdminController@destroyReward` | `dashboard.admin.rewards.destroy` |

### Admin Routes — Survey & User management (`/admin` prefix)
Middleware: `auth`, `verified`, `admin`

| Method | URI | Action | Name |
| :--- | :--- | :--- | :--- |
| GET | `/admin/survey/create` | `SurveyController@createView` | `admin.survey.create` |
| POST | `/admin/survey` | `SurveyController@store` | `admin.survey.store` |
| GET | `/admin/survey/edit/{survey}` | `SurveyController@editView` | `admin.survey.edit` |
| PATCH | `/admin/survey/{survey}` | `SurveyController@update` | `admin.survey.update` |
| GET | `/admin/user/{user}/profile` | `ProfileController@show` | `admin.user.profile` |
| GET | `/admin/user/{user}/edit` | `ProfileController@editUser` | `admin.user.edit` |
| PATCH | `/admin/user/{user}` | `ProfileController@updateUser` | `admin.user.update` |
| PUT | `/admin/user/{user}/password` | `ProfileController@updateUserPassword` | `admin.user.password.update` |
| DELETE | `/admin/user/{user}` | `ProfileController@destroyUser` | `admin.user.destroy` |

## API Routes
All API routes are defined in `routes/web.php`, not `api.php`.

| Method | URI | Action | Auth |
| :--- | :--- | :--- | :--- |
| GET | `/api/surveys/{survey}` | `ApiSurveyController@show` | No |
| GET | `/api/demo/categories` | `ApiDemoController@categories` | No |

## Auth Routes (Breeze)
Standard authentication routes defined in `routes/auth.php`:
-   `/register`
-   `/login`
-   `/forgot-password`
-   `/reset-password`
-   `/verify-email`
-   `/logout`
