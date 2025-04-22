# Leave Management System

## Description
The Leave Management System is a web application built with PHP and MongoDB that allows users to manage their leave requests efficiently. Users can sign up, log in, request leave, and view their leave history through a simple and intuitive interface.

## Features
- User registration and authentication
- User dashboard with personalized welcome
- Leave request submission
- View leave history
- Responsive design with CSS styling

## Technologies Used
- PHP
- MongoDB
- Composer (for dependency management)
- HTML/CSS

## Installation

### Requirements
- PHP 7.4 or higher
- Composer
- MongoDB server (cloud or local)

### Steps
1. Clone or download the repository to your local machine.
2. Navigate to the project directory `leave-management-system`.
3. Install PHP dependencies using Composer:
   ```
   composer install
   ```
4. Configure the MongoDB connection string in `config/db.php` if needed. The current connection string uses a MongoDB Atlas cluster.
5. Ensure your web server (e.g., Apache) is configured to serve the project directory or use PHP's built-in server:
   ```
   php -S localhost:8000
   ```
6. Open your browser and go to `http://localhost:8000/index.php` to access the application.

## File Structure
- `index.php` - Main landing page prompting login or signup
- `signup.php` - User registration page
- `login.php` - User login page
- `dashboard.php` - User dashboard after login
- `leave_request.php` - Page to submit leave requests
- `leave_list.php` - Page to view leave history
- `config/db.php` - MongoDB database connection setup
- `includes/` - Header and footer includes for consistent layout
- `css/style.css` - Stylesheet for the application
- `composer.json` - PHP dependencies configuration

## Usage
- Register a new user via the Sign Up page.
- Log in with your credentials.
- Access the dashboard to request leave or view your leave history.
- Log out when finished.

## License
This project is open source and available under the MIT License.

## Author
Developed by Alen Roy.
