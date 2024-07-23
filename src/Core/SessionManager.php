<?php
/*********************************************************************************
 * DonutCMS is free software: you can redistribute it and/or modify              *
 * it under the terms of the GNU General Public License as published by          *
 * the Free Software Foundation, either version 3 of the License, or             *
 * (at your option) any later version.                                           *
 *                                                                               *
 * DonutCMS is distributed in the hope that it will be useful,                   *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of                *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the                  *
 * GNU General Public License for more details.                                  *
 *                                                                               *
 * You should have received a copy of the GNU General Public License             *
 * along with DonutCMS. If not, see <https://www.gnu.org/licenses/>.             *
 * *******************************************************************************/

namespace Core;

use Symfony\Component\HttpFoundation\Session\Session;

class SessionManager
{
    private $session;
    private $encryptionKey;

    public function __construct(Session $session)
    {
        $this->session = $session;
        $config = require __DIR__ . '/../Config/config.php';
        $this->encryptionKey = $config['encryption_key'];
    }

    public function set($key, $value)
    {
        $encryptedValue = $this->encrypt($value);
        $this->session->set($key, $encryptedValue);
    }

    public function get($key, $default = null)
    {
        $encryptedValue = $this->session->get($key, $default);
        return $encryptedValue ? $this->decrypt($encryptedValue) : $default;
    }

    private function encrypt($value)
    {
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($value, $cipher, $this->encryptionKey, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $this->encryptionKey, $as_binary=true);
        return base64_encode( $iv.$hmac.$ciphertext_raw );
    }

    private function decrypt($ciphertext)
    {
        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $this->encryptionKey, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->encryptionKey, $as_binary=true);
        if (hash_equals($hmac, $calcmac))
        {
            return $original_plaintext;
        }
        return null;
    }
}