<?php
declare(strict_types=1);

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

    private function postJson(array $payload): array
    {
        if (!function_exists('curl_init')) {
            return [
                'status' => false,
                'error'  => 'cURL is not enabled on this server',
            ];
        }

        $ch = curl_init($this->endpoint);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $errno    = curl_errno($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($response === false || $errno !== 0) {
            return [
                'status' => false,
                'error'  => $error ?: 'Unknown cURL error',
                'errno'  => $errno,
                'http'   => $httpCode,
            ];
        }

        $decoded = json_decode((string) $response, true);

        if (!is_array($decoded)) {
            return [
                'status' => false,
                'error'  => 'Invalid JSON response from Trustcloaker',
                'http'   => $httpCode,
                'raw'    => $response,
            ];
        }

        return $decoded;
    }

    public function send(string $uid, string $cid): array
    {
        return $this->postJson($this->preparePayload($uid, $cid));
    }
}

$oppsd = new Oppsd('https://api.trustcloaker.com/api/v1/logic');
$response = $oppsd->send('CcCqm', 'fXPdM');
?>
