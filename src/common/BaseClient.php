<?

namespace smsworker\common;

use Exception;

class BaseClient
{
    const BASE_URI = "https://omnichannel.mts.ru/http-api/v1/";

    const POST_MESSAGE_URL = "messages";
    const BATCH_MAESSGES_URL = "b/messages";
    const GET_STATUS_URL = "messages/info";

    private $login;
    private $password;
    private $timeout = 10000;

    protected $basicAuth;

    public function __construct(array $params)
    {
        foreach ($params as $param => $value) {
            $this->{$param} = $value;
        }
        if (empty($this->login) || empty($this->password)) {
            throw new Exception("Login or pssword is incorrect");
        }
        $this->basicAuth = 'Basic '.base64_encode("$this->login:$this->password");
    }

    /**
     * @param $url
     * @param $data
     * @param $headers
     * @return bool|string
     */
    public function curlRequest($url, $data = NULL, $method = "POST")
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->host . $url);
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
            throw new Exception('Curl Error:' . curl_error($ch));
        }

        curl_close($ch);
        return $response;
    }

}
