# Gymsys Architecture

This document outlines the architecture of the Gymsys application.

## Key Components

- **Frontend:** User interface built with HTML, CSS, and JavaScript.
- **Backend:** PHP-based server handling data management and API endpoints.
- **Database:** MySQL database for storing application data.
- **WebSocket Server:** Ratchet-based WebSocket server for real-time notifications.

## File Structure

- `public/`: Contains publicly accessible files (CSS, JavaScript, images).
- `src/`: Contains the application's source code (controllers, models, core classes, utils).
- `config/`: Contains configuration files (database credentials, etc.).
- `vendor/`: Contains Composer dependencies.
- `bin/`: Contains executable scripts.

## WebSocket Server

The application uses a Ratchet-based WebSocket server to provide real-time notifications to users.

### Running the WebSocket Server

To start the WebSocket server, execute the following command in the project root:

```bash
php bin/run-websocket.php
```

This will start the server on port 8080. Ensure that port 8080 is open and not blocked by any firewalls.

### Implementation Details

- The WebSocket server logic is located in `src/websocket/NotificationServer.php`.
- The client-side JavaScript code in `public/assets/js/comunes.js` establishes the WebSocket connection and handles incoming notifications.