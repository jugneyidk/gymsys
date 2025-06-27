# CHANGELOG

## [2025-06-26] Mode: code
- Migrated notifications from interval polling to WebSocket using Ratchet.
- Modified `public/assets/js/comunes.js` to include WebSocket connection logic and handle notifications.
- Removed `setInterval` calls from `public/assets/js/asistencias.js`, `public/assets/js/atletas.js`, `public/assets/js/bitacora.js`, `public/assets/js/dashboard.js`, `public/assets/js/entrenadores.js`, and `public/assets/js/perfilatleta.js`.
- Created `src/websocket/NotificationServer.php` for the Ratchet WebSocket server.
- Created `bin/run-websocket.php` to start the WebSocket server.
- Added instructions for running the WebSocket server to `ARCHITECTURE.md`.
- Affected file(s): `public/assets/js/comunes.js`, `public/assets/js/asistencias.js`, `public/assets/js/atletas.js`, `public/assets/js/bitacora.js`, `public/assets/js/dashboard.js`, `public/assets/js/entrenadores.js`, `public/assets/js/perfilatleta.js`, `src/websocket/NotificationServer.php`, `bin/run-websocket.php`, `ARCHITECTURE.md`
- Summary of purpose or outcome: Implemented real-time notifications using WebSockets, improving application responsiveness and reducing server load.