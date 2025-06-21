# Project Documentation

This document provides an overview of the main files and directories in the project and their purposes.

## Directories

- **app/**  
  Contains the core application code including controllers, models, services, exports, and providers.  
  - **Http/Controllers/**: Contains controller classes handling HTTP requests and business logic.  
  - **Models/**: Contains Eloquent models representing database tables.  
  - **Exports/**: Contains export classes for data export functionality (e.g., Excel exports).  
  - **Services/**: Contains service classes for specific business logic or integrations (e.g., RFIDService).  
  - **Providers/**: Contains service providers for application bootstrapping.

- **bootstrap/**  
  Contains files for application bootstrapping and caching.

- **config/**  
  Configuration files for the application and its services.

- **database/**  
  Contains database migrations, seeders, and factories.

- **public/**  
  Publicly accessible files such as index.php, assets (CSS, JS, images), and other web resources.

- **resources/**  
  Contains frontend assets and views.  
  - **views/**: Blade templates for rendering HTML pages.

- **routes/**  
  Contains route definition files.  
  - **web.php**: Routes for web interface, including admin and public pages.  
  - **api.php**: Routes for API endpoints.

- **storage/**  
  Storage for logs, cache, sessions, and other runtime files.

- **tests/**  
  Contains automated tests for the application.

## Key Files

- **artisan**  
  Laravel command-line interface tool.

- **composer.json / composer.lock**  
  PHP package manager files defining dependencies.

- **package.json / package-lock.json**  
  Node.js package manager files for frontend dependencies.

- **phpunit.xml**  
  Configuration for PHPUnit testing framework.

- **vite.config.js**  
  Configuration for Vite frontend build tool.

## Summary

This project is a Laravel-based web application for managing attendance and RFID scanning for santri (students). It includes web and API routes, controllers for handling business logic, models for database interaction, and views for frontend rendering.

The application supports features such as attendance recording, RFID scanning, data export, and admin dashboard management.

For detailed information on specific files or modules, please refer to the respective directories and files.
