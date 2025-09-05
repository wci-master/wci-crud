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
├── .gitignore
├── assignment.md
├── database.sql
├── index.php
├── instructor_guide.md (git ignored)
├── student_checklist.md
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

## GitHub Submission

Students are required to submit their completed work via GitHub:

1. Create a new GitHub repository for your project
2. Push your code to the repository
3. Ensure you've included the SQL file in your repository
4. Add screenshots of your working application
5. Include setup instructions in your README.md
6. Submit the repository link to the WCI checker

## Learning Objectives

- Understanding CRUD operations
- Implementing user authentication
- Working with databases
- Frontend-backend integration
- Form handling and validation
- Version control with Git

## Version Control

This project includes a `.gitignore` file configured for web development projects:

- The instructor guide is excluded from version control to keep it private
- Configuration for common web development environments
- Ignores system files, IDE configurations, and dependency directories
- Students should use this configuration when pushing their work to GitHub

## Teaching Materials

This project includes teaching materials for instructors and students:

1. **Assignment File** (`assignment.md`):
   - Detailed requirements for each component
   - Grading criteria
   - Bonus features
   - Submission guidelines
   - Timeline template
   - Helpful resources

2. **Instructor Guide** (`instructor_guide.md`):
   - Comprehensive grading rubric
   - Common issues to look for
   - Feedback guidelines
   - Presentation evaluation criteria
   - Additional resources for students
   - *Note: This file is excluded from version control via .gitignore*

3. **Student Checklist** (`student_checklist.md`):
   - Pre-submission requirements verification
   - Feature implementation checklist
   - Final quality checks
   - GitHub repository submission steps

## License

This project is part of the WCI Web Development Foundational Course and is intended for educational purposes only.