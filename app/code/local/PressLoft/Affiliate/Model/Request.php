<?php

class PressLoft_Affiliate_Model_Request
{
    const TYPE_GET = 'GET';
    const TYPE_POST = 'POST';
    const CHECK_TOKEN_ENDPOINT = 'https://affiliates.pressloft.com/cookieperiod';
    const SALE_API_ENDPOINT = 'https://affiliates.pressloft.com/sale';
    const HEARTBEAT_API_ENDPOINT = 'https://affiliates.pressloft.com/heartbeat';
    const SUCCESS_STATUS_CODE = 200;

    /**
     * @param string $endpoint
     * @param string $requestType
     * @param array $params
     * @return array
     */
    public function sendRequest($endpoint, $requestType, $params)
    {
        /* Forced curl was used because Varien_Http_Client could not parse response headers from server */
        $curl = curl_init();

        if ($requestType == self::TYPE_GET) {
            $endpoint = $this->createEndpointWithParams($endpoint, $params);
        } else {
            $headers = array(
                "Content-Type: application/json"
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        }

        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $requestType);

        $response = curl_exec($curl);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        try {
            $response = Zend_Json::decode($response);
        } catch (Zend_Json_Exception $exception) {
            $response = ['code' => '422', 'response' => $exception->getMessage()];
        }

        return ['code' => $responseCode, 'response' => $response];
    }

    /**
     * @param $endpoint
     * @param $params
     * @return string
     */
    protected function createEndpointWithParams($endpoint, $params)
    {
        return $endpoint . '?' . http_build_query($params);
    }
}