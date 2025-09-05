# WCI Web Development Foundational Course - CRUD Application

This repository contains a simple CRUD (Create, Read, Update, Delete) application developed as part of the WCI Web Development Foundational Course. The application demonstrates basic web development concepts including user authentication, dashboard functionality, and profile management.

## Features

- User Authentication (Signup/Login)
- User Dashboard
- Profile Management
- CRUD Operations

## Tech Stack

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL

## Project Structure

```
wci-crud/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── script.js
├── config/
│   └── database.php
├── includes/
│   ├── auth.php
│   ├── footer.php
│   └── header.php
├── pages/
│   ├── dashboard.php
│   ├── login.php
│   ├── profile.php
│   ├── register.php
│   └── tasks.php
├── database.sql
├── index.php
└── README.md
```

## Setup Instructions

1. Clone this repository to your local machine
2. Start your local server (e.g., XAMPP, WAMP)
3. Import the database schema from `database.sql` using phpMyAdmin or MySQL command line
   - The SQL file includes table creation scripts and optional sample data (commented out)
   - You can uncomment the sample data section for testing purposes
4. Configure your database connection in `config/database.php` if needed
   - Default configuration uses:
     - Host: localhost
     - Username: root
     - Password: (empty)
     - Database: wci_crud
5. Access the application through your web browser

## Usage

1. Register a new account
2. Login with your credentials
3. Navigate to the dashboard
4. Manage your profile information
5. Create, view, update, and delete tasks

## Learning Objectives

- Understanding CRUD operations
- Implementing user authentication
- Working with databases
- Frontend-backend integration
- Form handling and validation

## License

This project is part of the WCI Web Development Foundational Course and is intended for educational purposes only.