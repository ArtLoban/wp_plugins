<?php

interface EncoderInterface
{
    /**
     * @return mixed
     */
    public function generateKeys();

    /**
     * @param $data
     * @return mixed
     */
    public function encodeData($data);

    /**
     * @param $data
     * @return mixed
     */
    public function decodeData($data);
}
