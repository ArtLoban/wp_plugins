<?php
include_once 'EncoderInterface.php';

class Encoder implements EncoderInterface
{
    /**
     * @var string
     */
    private $privateKeyPath;

    /**
     * @var string
     */
    private $publicKeyPath;

    /**
     * @var null|string
     */
    private $publicKey = null;

    /**
     * @var null|string
     */
    private $privateKey = null;

    /**
     * Encoder constructor.
     */
    function __construct()
    {
        $this->privateKeyPath   = plugin_dir_path( __DIR__  ) . 'includes/private_key.txt';
        $this->publicKeyPath    = plugin_dir_path( __DIR__  ) . 'includes/public_key.txt';
    }

    /**
     * Generate privet and public keys
     *
     * @return array
     */
    public function generateKeys()
    {
        $config = [
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'private_key_bits' => 512
        ];

        // Generates a new private key
        $result = openssl_pkey_new($config);

        $privetKey = '';
        // Gets an exportable representation of a key into a string
        openssl_pkey_export($result, $privetKey);

        $privateKeyFile = fopen($this->privateKeyPath,'w');
        fwrite($privateKeyFile, $privetKey);
        fclose($privateKeyFile);

        // Some distinguished name
        $dn = [];

        //Certificate Signing Request
        $csr = openssl_csr_new($dn, $privetKey);

        // Sign a CSR with another certificate (or itself) and generate a certificate
        $certificate = openssl_csr_sign($csr,null, $privetKey,10);

        // Exports a certificate as a string
        openssl_x509_export($certificate, $stringCertificate);

        // Extract public key from certificate and prepare it for use
        $public_key = openssl_pkey_get_public($stringCertificate);

        //  Returns an array with the key details
        $public_key_details = openssl_pkey_get_details($public_key);

        $public_key_string = $public_key_details['key'];

        $publicKeyFile = fopen($this->publicKeyPath,'w');
        fwrite($publicKeyFile, $public_key_string);
        fclose($publicKeyFile);

        return [
            'privetKey' => $privetKey,
            'publicKey' => $public_key_string
        ];
    }

    /**
     * @param $data
     * @return string
     */
    public function encodeData($data): ?string
    {
        $publicKey = $this->publicKey;

        if ( ! isset($publicKey)) {
            $publicKey = $this->getPublicKey();
        }

        // Encrypts data with public key and stores the result into encrypted
        openssl_public_encrypt($data, $encrypted, $publicKey);

        return $encrypted;
    }

    /**
     * @param $data
     * @return string
     */
    public function decodeData($data): ?string
    {
        $privateKey = $this->privateKey;

        if ( ! isset($privateKey)) {
            $privateKey = $this->getPrivateKey();
        }

        // Decrypts data and stores the result into decrypted
        openssl_private_decrypt($data, $decrypted, $privateKey);

        return $decrypted;
    }

    /**
     * @return bool|string
     */
    private function getPublicKey()
    {
        $file = fopen($this->publicKeyPath,'r');
        $publicKey = fread($file,1024);
        fclose($file);

        $this->publicKey = $publicKey;

        return $publicKey;
    }

    /**
     * @return bool|string
     */
    private function getPrivateKey()
    {
        $file = fopen($this->privateKeyPath,'r');
        $privateKey = fread($file,1024);
        fclose($file);

        $this->privateKey = $privateKey;

        return $privateKey;
    }
}
