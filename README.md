# MochaTask API (Laravel)

![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.x-orange)

A robust task management API built with Laravel 12, featuring RESTful endpoints, authentication, and CRUD operations.

## Project Overview

Modern API backend for task management with Laravel's powerful ecosystem:

- **User Authentication**: Sanctum-based API token authentication
- **Task Management**: Full CRUD operations with validation
- **API Resources**: Consistent JSON response formatting
- **Migrations**: Database schema management
- **Eloquent ORM**: Advanced model relationships
- **Middleware**: Route protection and request handling

## Features

- RESTful API endpoints with proper HTTP verbs
- Request validation and error handling
- Database migrations and seeding
- Model factories for testing
- Rate limiting middleware

## Requirements

- PHP 8.2 or higher
- Composer 2.5 or higher
- PostgreSQL 9.x or MySQL 8.x or MariaDB 10.6+

## Installation

Follow these steps to set up the project:

1. **Clone repository**
   ```bash
   git clone https://github.com/SkiFFx0/MochaTask_api.git
   cd MochaTask_api

2. Install dependencies
    ```bash
    composer install

3. Configure environment
    ```bash
    cp .env.example .env
    php artisan key:generate

4. Edit .env file
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=mochanask
    DB_USERNAME=root
    DB_PASSWORD=

5. Database setup
   ```bash
   php artisan migrate --seed

6. Start development server
    ```bash
   php artisan serve

The API will be available at http://localhost:8000
