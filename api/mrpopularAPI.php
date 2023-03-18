<?php
class Api
{
    //config
    public $api_url = 'https://mrpopular.net/api/v2.php'; // API URL
    public $username = 'sahiibz'; //your username
    public $password = 'Sahib2232708'; //your password
    public $currency = 'RUB';

    public function order($data)
    { // add order
        $post = array_merge(array(
            'username' => $this->username,
            'password' => $this->password,
            'action' => 'order'
        ), $data);
        return json_decode($this->connect($post));
    }

    public function status($order)
    { // get order status
        return json_decode($this->connect(array(
            'username' => $this->username,
            'password' => $this->password,
            'action' => 'status',
            'order' => $order
        )));
    }

    public function service()
    { // get service list
        return json_decode($this->connect(array(
            'username' => $this->username,
            'password' => $this->password,
            'action' => 'service',
        )));
    }

    public function balance()
    { // get balance
        return json_decode($this->connect(array(
            'username' => $this->username,
            'password' => $this->password,
            'action' => 'balance',
        )));
    }


    function connect($post)
    {
        $_post = array();
        if (is_array($post)) {
            foreach ($post as $name => $value) {
                $_post[] = $name . '=' . urlencode($value);
            }
        }

        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if (is_array($post)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        $result = curl_exec($ch);
        if (curl_errno($ch) != 0 && empty($result)) {
            $result = false;
        }
        curl_close($ch);
        return $result;
    }
}

//start API
//$api = new Api();

//check balance
//$balance = $api->balance();
//print_r($balance);

//new order
/*$order = $api->order(array(
'service' => 462,
'quantity' => $qnty,
'link' => $src
));
print_r($order);*/

//order status
/*$status = $api->status(12232);
print_r($status);*/

//service list
//$service = $api->service()->service;


