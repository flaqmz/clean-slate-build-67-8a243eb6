## Goal
Rebuild the "Sunora Summer Drop" landing page from the uploaded `Clean Slate Build.zip` into this project, 1:1, on a plain dark background (no hero photo behind the content).

## Changes

1. **`src/routes/index.tsx`** — replace the placeholder with the uploaded version:
   - `SUMMER DROP` pill (Sun icon + tracked label)
   - `SUNORA` wordmark (Playfair Display italic, gold `#E5B34B`)
   - Bonus card with `BONUS AKTIV` badge, "2x Gratis Geschenke" title, live `mm:ss` countdown (starts 02:58), and two gift chips ("Rimowa Case", "YSL Armband")
   - Anton-font headline "Zu heiss, um falsch angezogen zu sein."
   - Star + "Über 12.000 Kunden tragen unsere Pieces"
   - Cream CTA button "JETZT SOMMER FIT SICHERN / → HIER KLICKEN"
   - Footer clock line "Warte nicht — sichere dir deine Größe, bevor sie weg ist."
   - Solid `#0e0e0e` background, centered `max-w-md` mobile column — no background image.

2. **`src/routes/__root.tsx`** — add Google Fonts `<link>` tags for **Anton**, **Inter**, and **Playfair Display** in `head().links` (preconnect + stylesheet), keeping existing metadata, error/notFound boundaries, and `<Outlet />` intact.

No other files change. `lucide-react` is already installed.
