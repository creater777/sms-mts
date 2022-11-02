<?
namespace smsworker;

use smsworker\common\BaseClient;

class SmsWorkerMts_v1 extends BaseClient implements WorkerInterface{
    
    const BASE_URI = "https://omnichannel.mts.ru/http-api/v1/";

    const POST_MESSAGE_URL = "messages";
    const BATCH_MAESSGES_URL = "b/messages";
    const GET_STATUS_URL = "messages/info";
    
    public function __construct($params)
    {
        parent::__construct($params);
    }

    /**
     * 
     */
    private function fetchMessages(array $texts, array $phones, string $sender, $url): array 
    {
        $messages = array_map(function($text) use ($phones, $sender) {
            return [
                "content" => [
                    "short_text" => $text
                ],
                "to" => array_map(function($phone){
                    return ["msisdn" => $phone];
                }, $phones),
                "from" => ["sms_address" => $sender]
            ];
        }, $texts);
        return $this->curlRequest($url, json_encode([
            "messages" => $messages
        ]));
    }

    /**
     * 
     */
    public function sendMessage(array $texts, array $phones, string $sender): array
    {
        return $this->fetchMessages($texts, $phones, $sender, self::BASE_URI . self::POST_MESSAGE_URL);
    }

    /**
     * 
     */
    public function sendBatch(array $texts, array $phones, string $sender): array
    {
        return $this->fetchMessages($texts, $phones, $sender, self::BASE_URI . self::BATCH_MAESSGES_URL);
    }

    /**
     * 
     */
    public function getStatus(array $ids): array
    {
        return $this->curlRequest(self::BASE_URI . self::GET_STATUS_URL, json_encode([
            "msg_ids" => $ids
        ]));
    }
}