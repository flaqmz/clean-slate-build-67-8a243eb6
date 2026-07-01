<?php
// Sunora — Summer Drop landing page (static PHP export)
$countdownStart = 178; // 02:58
?>
<!doctype html>
<html lang="de">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Sunora — Summer Drop</title>
<meta name="description" content="Zu heiss, um falsch angezogen zu sein. Sichere dir jetzt deine Sunora Pieces." />
<meta property="og:title" content="Sunora — Summer Drop" />
<meta property="og:description" content="Zu heiss, um falsch angezogen zu sein." />
<meta property="og:type" content="website" />

<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@1,600&display=swap" />
<link rel="stylesheet" href="styles.css" />
</head>
<body>
  <main class="page">
    <div class="col">

      <div class="pill">
        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
        SUMMER DROP
      </div>

      <h1 class="wordmark">SUNORA</h1>

      <section class="bonus">
        <div class="bonus-row">
          <div class="bonus-text">
            <span class="badge">BONUS AKTIV</span>
            <div class="bonus-title">2x Gratis Geschenke</div>
            <div class="bonus-sub">Nur für kurze Zeit zu jeder Bestellung</div>
          </div>
          <div id="countdown" class="timer" data-start="<?= (int)$countdownStart ?>">02:58</div>
        </div>

        <div class="gifts">
          <div class="gift">
            <div class="thumb"></div>
            <svg class="plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
            <span>Rimowa Case</span>
          </div>
          <div class="gift">
            <div class="thumb"></div>
            <svg class="plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
            <span>YSL Armband</span>
          </div>
        </div>
      </section>

      <h2 class="headline">
        Zu heiss,<br />um falsch<br />angezogen<br />zu sein.
      </h2>

      <div class="proof">
        <svg class="star" viewBox="0 0 24 24" fill="#E5B34B" stroke="#E5B34B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        <p>Über 12.000 Kunden tragen unsere<br />Pieces</p>
      </div>

      <button class="cta" type="button">
        JETZT SOMMER FIT SICHERN<br />
        <span class="cta-sub">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
          HIER KLICKEN
        </span>
      </button>

      <div class="footer-note">
        <svg class="clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <p>Warte nicht — sichere dir deine Größe, bevor sie weg ist.</p>
      </div>

    </div>
  </main>
  <script src="app.js"></script>
</body>
</html>
