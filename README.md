# Simple Booking System

A lightweight, modular, object-oriented PHP and MariaDB booking system built with modern coding standards, single-responsibility principles, and isolated endpoints.

## Features
- **Object-Oriented Programming (OOP):** Encapsulated business logic using classes (`Database`, `Service`, `User`, `Booking`).
- **Secure Database Operations:** Uses PDO with prepared statements to prevent SQL injections.
- **Modular Design:** Clear separation of concerns between public UI views, isolated API endpoints, and business classes.
- **Conflict Prevention:** Built-in time slot overlap validation.

## Requirements
- PHP 8.1 or higher
- MariaDB / MySQL
- Web Server (Apache / Nginx / PHP Built-in Server)

## Installation & Setup

1. **Clone or Download the Repository**
   Place the project folder into your web server directory (e.g., `htdocs` for XAMPP).

2. **Setup the Database**
   Import the `schema.sql` file into your MariaDB server using phpMyAdmin or the command line:
   ```bash
   mysql -u root -p < schema.sql