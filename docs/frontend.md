# Frontend

This document describes the frontend stack, themes, and design patterns used in Fiesta-Forms.

## Tech Stack
-   **Templating**: Laravel Blade.
-   **CSS Framework**: Tailwind CSS.
-   **Asset Bundling**: Vite.
-   **Fonts**:
    -   **Outfit**: Used as the primary body font.
    -   **Figtree**: Used for secondary text and UI elements.
-   **Icons**: Material Symbols Outlined.

## Design Theme: "Cyber"

The application features a unique "Cyber" theme characterized by glassmorphism, gradients, and modern UI components.

### Layouts
1.  **Cyber Layout (`layouts/cyber.blade.php`)**: The primary layout for the dashboard and survey pages. It implements the core glassmorphism styles and provides a responsive shell.
2.  **App Layout (`layouts/app.blade.php`)**: Standard Laravel Breeze layout.
3.  **Guest Layout (`layouts/guest.blade.php`)**: Used for authentication pages (Login, Register).

### Glassmorphism System
The project uses custom CSS classes in `resources/css/app.css` to implement a consistent glass effect:
-   `.glass`: Standard blurred background with a light border.
-   `.glass-card`: Semi-transparent card style with shadow and blur.
-   `.glass-panel`: High-opacity blurred panel for larger sections.
-   `.glass-button`: Buttons that maintain the blur effect.

### Typography & Colors
-   **Text Gradients**: The `.text-gradient` class is used for emphasis, creating a smooth transition between indigo, purple, and pink.
-   **Dark Mode**: Extensive support for dark mode using Tailwind's `dark:` modifier, adjusting glass opacities and border colors for better contrast.

## Key Views
-   **Home (`cyber/home.blade.php`)**: The landing page for the dashboard.
-   **Explore (`cyber/explore.blade.php`)**: A marketplace/list view to find surveys.
-   **Survey Detail (`cyber/form-detail.blade.php`)**: A feature-rich view that displays both the voting form and real-time results visualization.
-   **Admin Dashboard (`cyber/admin.blade.php`)**: A tabular view for administrators to manage their surveys.

## Components
Reusable Blade components are used for:
-   `primary-button`, `secondary-button`, `danger-button`
-   `text-input`, `input-label`, `input-error`
-   `modal`, `dropdown`
-   Specific survey components (survey navigation, question blocks).
