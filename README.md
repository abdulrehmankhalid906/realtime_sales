# Stacks Used
Core PHP, HTML/CSS, Bootstrap, JavaScript

# Project Structure & Organization
Implemented a Laravel-like hierarchy to structure the project in a more organized and maintainable way.
Set up autoloading for the app directory using Composer, similar to how Laravel manages its application structure.

# Composer Packages Used
- cboden/ratchet – for WebSocket server implementation.
- textalk/websocket – for sending real-time alerts to clients.

# Database
- Used SQLite for the database as per project requirements.
- This was my first time working with SQLite, so I referred to external resources to understand its syntax and integration with PHP.

# Project Setup:

- Requirement:
  PHP 8.2

- Setting Up:
  1. clone the project. 
  2. cd real-time-app
  3. composer install
  5. open broweser and hit "http://localhost/realtime_sale/create_table.php" the database will be created.
  4. open terminal and run "php server.php" to run the websocket server.
  6. now hit "http://localhost/realtime_sale/dashboard.php" for the dashboard.


# Manual:
  - Created the project hirarcy.
  - Designed and setup the end-points of the apis.
  - 
