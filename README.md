# Random User Laravel App

This is a simple Laravel application that fetches and displays users from the [Random User API](https://randomuser.me/api/). It includes features like pagination, gender filtering, caching, and CSV export.

---

## Features

-   Fetches 50 users at a time from the Random User API
-   Displays:
    -   Name
    -   Email
    -   Gender
    -   Nationality
-   Pagination (10 users per page)
-   Gender filter via query param (`/users?gender=male`)
-   Caching (10-minute TTL per page/gender filter)
-   CSV export of the current filtered/paginated view
-   Error handling for API failures
-   Optional unit tests for filter/export features

---

## Setup Instructions

### 1. Clone the Repository

git clone https://github.com/madhusudanasdev/random-user-app.git
cd random-user-app

## Install Dependencies

composer install

## Copy .env File and Generate App Key

copy .env.example and rename to .env
php artisan key:generate

## Run the Application

php artisan serve

Open your browser and visit: http://localhost:8000/users

## Running Tests

php artisan test
