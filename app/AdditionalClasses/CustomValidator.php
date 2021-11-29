<?php


namespace App\AdditionalClasses;


use Illuminate\Database\Eloquent\Model;

class CustomValidator
{
    /**
     * @param $phone_number
     * @return bool
     */
    public static function mobile_validator($phone_number, $onlyZero = false)
    {
        if ($onlyZero) {
            $pattern = "/^[0]{1}[9]{1}([0-9]{9,9}$)/i";
        } else {
            $pattern = "/^(\+98|0|)[9]{1}([0-9]{9,9}$)/i";
        }
        if(preg_match($pattern, $phone_number)) {
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public static function getPreviousRouteName()
    {
        return app('router')->getRoutes(url()->previous())->match(app('request')->create(url()->previous()))->getName();
    }
}
