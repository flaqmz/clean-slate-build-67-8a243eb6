# Sunora — Summer Drop (PHP Export)

Statische Seite als PHP-Projekt. Keine Composer-Abhängigkeiten, kein Build.

## Dateien
- `index.php` — die Seite
- `styles.css` — Styling
- `app.js`    — Countdown

## Deployment auf Render

**Variante A — PHP Web Service (empfohlen wenn du später PHP-Code hinzufügst):**
1. Repo auf GitHub pushen (alle Dateien im Root).
2. Render → New → Web Service → Runtime **Docker** oder **Native Environment: PHP**.
3. Start Command: `php -S 0.0.0.0:$PORT -t .`
4. Health Check Path: `/`

**Variante B — Static Site (schneller & günstiger, da rein statisch):**
1. `index.php` in `index.html` umbenennen (der PHP-Teil setzt nur einen Default-Wert; du kannst `<?= (int)$countdownStart ?>` durch `178` und den `<?php ... ?>`-Block oben durch nichts ersetzen).
2. Render → New → Static Site → Publish Directory: `.`
3. Build Command: leer lassen.

## Lokal testen
```
php -S localhost:8000
```
Dann http://localhost:8000 öffnen.
