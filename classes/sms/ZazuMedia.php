<?php
/**
 * Created by PhpStorm.
 * User: Evgeny.Levashov@gmail.com
 * Date: 10/15/2019
 * Time: 5:17 PM
 */

namespace classes\sms;

use \classes\AggregatorBase;
use \classes\interfaces\AggregatorInterface;

class ZazuMedia extends AggregatorBase  implements AggregatorInterface
{
    public function __construct($config)
    {
        parent::__construct($config);
        $this->url = 'http://api.zmtech.ru:7777/v1';
        //$this->port = 7777;
        $this->method = 'post';
        $this->type = 'json';
    }

    /**
     * Sends sms to the specified recipients
     *
     * @param array $recipients Phone numbers such as ['79001234567', ....]
     * @param string $message
     * @return mixed|void
     */
    public function send($recipients, $message)
    {
        $params = ['id'=>$this->id, 'password'=>$this->key];
        foreach($recipients as $recipient) {
            $params['pack'][] =
                [
                    'phone' => $recipient,
                    'message' => $message,
                    'sender' => $this->sender,
                ];
        }
        return $this->brand($params);
    }
}