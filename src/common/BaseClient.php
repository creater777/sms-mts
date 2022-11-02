<?

namespace smsworker\common;

use Exception;

class BaseClient
{
    private $login;
    private $password;
    private $timeout = 100;

    protected $basicAuth;

    public function __construct(array $params)
    {
        foreach ($params as $param => $value) {
            $this->{$param} = $value;
        }
        if (empty($this->login) || empty($this->password)) {
            throw new Exception("Login or password is incorrect");
        }
        $this->basicAuth = 'Basic '.base64_encode("$this->login:$this->password");
    }

    /**
     * @param $url
     * @param $data
     * @param $headers
     * @throws Exception
     */
    public function curlRequest(string $url, string $data = null, string $method = "POST"): array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic " . $this->basicAuth,
            "Content-Type: application/json; charset=utf-8"
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $response = curl_exec($ch);
        if (curl_error($ch)) {
            throw new Exception('Curl Error: ' . curl_error($ch));
        }
        curl_close($ch);

        return json_decode($response);
    }
}
