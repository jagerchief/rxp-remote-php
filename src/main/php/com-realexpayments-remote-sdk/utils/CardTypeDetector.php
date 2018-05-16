<?php
/**
 * Created by PhpStorm.
 * User: dani
 * Date: 16/05/18
 * Time: 13:06
 */

namespace com\realexpayments\remote\sdk\utils;


use com\realexpayments\remote\sdk\domain\CardType;

class CardTypeDetector
{
    private function isVisa(string $card) : bool
    {
        return preg_match("/^4[0-9]{0,15}$/i", $card);
    }
    private function isMasterCard(string $card) :  bool
    {
        return preg_match("/^5[1-5][0-9]{5,}|222[1-9][0-9]{3,}|22[3-9][0-9]{4,}|2[3-6][0-9]{5,}|27[01][0-9]{4,}|2720[0-9]{3,}$/i", $card);
    }
    private function isAmex(string $card) : bool
    {
        return preg_match("/^3$|^3[47][0-9]{0,13}$/i", $card);
    }
    private function isDiscover(string $card) : bool
    {
        return preg_match("/^6$|^6[05]$|^601[1]?$|^65[0-9][0-9]?$|^6(?:011|5[0-9]{2})[0-9]{0,12}$/i", $card);
    }
    private function isJCB(string $card) : bool
    {
        return preg_match("/^(?:2131|1800|35[0-9]{3})[0-9]{3,}$/i", $card);
    }
    private function isDinersClub(string $card) : bool
    {
        return preg_match("/^3(?:0[0-5]|[68][0-9])[0-9]{4,}$/i", $card);
    }
    public static function detect(string $card) : string
    {
        $cardTypes = [
            [
                'code' => CardType::VISA,
                'methodName' => 'isVisa'
            ],
            [
                'code' => CardType::MASTERCARD,
                'methodName' => 'isMasterCard'
            ],
            [
                'code' => CardType::AMEX,
                'methodName' => 'isAmex'
            ],
            [
                'code' => CardType::CB,
                'methodName' => 'isDiscover'
            ],
            [
                'code' => CardType::JCB,
                'methodName' => 'isJCB'
            ],
            [
                'code' => CardType::DINERS,
                'methodName' => 'isDinersClub'
            ]
        ];
        foreach ($cardTypes as $cardType) {
            $method = $cardType['methodName'];
            if (self::$method($card)) {
                return $cardType['code'];
            }
        }
        return 'Invalid Card';
    }

}