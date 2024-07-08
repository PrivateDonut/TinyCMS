<?php

use Medoo\Medoo;

class TinyCMSSessionHandler implements \SessionHandlerInterface
{
    private $database;
    private $encryptionKey;

    public function __construct(Medoo $database, $encryptionKey)
    {
        $this->database = $database;
        $this->encryptionKey = $encryptionKey;
    }

    private function encrypt($data)
    {
        $iv = random_bytes(16);
        $ciphertext = openssl_encrypt($data, 'aes-256-cbc', $this->encryptionKey, 0, $iv);
        return base64_encode($iv . $ciphertext);
    }

    private function decrypt($data)
    {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $ciphertext = substr($data, 16);
        return openssl_decrypt($ciphertext, 'aes-256-cbc', $this->encryptionKey, 0, $iv);
    }

    public function open($savePath, $sessionName): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read($id): string
    {
        $data = $this->database->get('sessions', 'sess_data', ['sess_id' => $id]);
        return $data ? $this->decrypt($data) : '';
    }
    public function write($id, $data): bool
    {
        $encryptedData = $this->encrypt($data);

        try {
            if ($this->database->has('sessions', ['sess_id' => $id])) {
                $this->database->update('sessions', ['sess_data' => $encryptedData, 'sess_time' => time()], ['sess_id' => $id]);
            } else {
                $this->database->insert('sessions', ['sess_id' => $id, 'sess_data' => $encryptedData, 'sess_time' => time()]);
            }
            return true;
        } catch (Exception $e) {
            // Log the error
            error_log("Session write error: " . $e->getMessage());
            return false;
        }
    }

    public function destroy($id): bool
    {
        $this->database->delete('sessions', ['sess_id' => $id]);
        return true;
    }

    public function gc($maxlifetime): int|false
    {
        $this->database->delete('sessions', [
            'sess_time[<]' => time() - $maxlifetime,
        ]);
        return true;
    }
}
