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
  5. open browser and hit "http://localhost/realtime_sale/create_table.php" the database will be created.
  4. open terminal and run "php server.php" to run the websocket server.
  6. now hit "http://localhost/realtime_sale/dashboard.php" for the dashboard.


# Manual:
  - Created the setting up project hirarcy as laravel.
  - Designed and setup the end-points of the apis.
  - chatGpt + weatherApi.

# AI Help:
 - Took the help while setting the sqlite db.
 - Some of the design and charts design.

# Important:
- 05/19/2025: Just changed the AI-Model GPT to Mistral.
- 05/18/2025: I have implemented the OpenAi recommendation but due to the paid api's keys i was not ablt to check the expected result. The Weather api is integrated and working fine.

# APi Endpoints:
- (POST) http://localhost/realtime_sale/index?route=orders   //for Order
- (GET) http://localhost/realtime_sale/index?route=analytics   //for analytics
- (GET) http://localhost/realtime_sale/index?route=weather   //for weather
- (GET) http://localhost/realtime_sale/index?route=recommendations   //for recommations along weather
