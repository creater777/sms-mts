<?

namespace smsworker;

interface WorkerInterface
{
    public function sendMessage(string $text, string $phone, string $sender): array;

    public function sendBatch(string $text, array $phones, string $sender): array;

    public function getStatus(array $ids): array;
}
