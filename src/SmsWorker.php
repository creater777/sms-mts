<?
namespace smsworker;

use smsworker\common\BaseClient;

class SmsWorker extends BaseClient{
    
    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function sentMessages(array $texts, array $phones, string $sender): object {
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
        return $this->send(parent::POST_MESSAGE_URL, json_encode([
            "messages" => $messages
        ]));
    }

    public function sentBatch()
}