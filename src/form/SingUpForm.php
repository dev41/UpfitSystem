<?php
/**
 * Created by PhpStorm.
 * User: fanalis
 * Date: 25.12.17
 * Time: 1:27
 */

namespace app\src\form;


use yii\base\Model;

/**
 * Class SingUpForm
 * @package app\src\form
 */
class SingUpForm extends Model
{
    /**
     * @var
     */
    public $username;
    /**
     * @var
     */
    public $password;
    /**
     * @var
     */
    public $email;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email', 'password', 'username'], 'required'],
            [['password', 'username'], 'string'],
            [['email',], 'email'],
        ];
    }
}