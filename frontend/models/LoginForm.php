<?php
namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $phone;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['phone', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect phone or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $session = Yii::$app->session;
            
            if ($user && !$session->has('notice_safety_wrong_IP') && $user->user_IP != Yii::$app->getRequest()->getUserIP()) {
                $session->set('notice_safety_wrong_IP', TRUE);
                $message = Yii::t('user/notice_safety', 'Зарегистрирован вход в Ваш аккаунт на MultiPay с другого IP адреса!', [], $user->user_language);
                Yii::$app->mainsms->api->sendSMS($user->phone, $message);
                if ($user->notice_safety_isEmail && $user->email) {
                    Yii::$app->mailer->compose()
                        ->setTo($user->email)
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                        ->setSubject(Yii::t('user/notice_safety', 'Уведомление безопасности от MultiPay!', [], $user->user_language))
                        ->setTextBody($message)
                        ->send();
                }
            }
            
            
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        $this->phone = preg_replace("/\D/", "", $this->phone);
        if ($this->_user === null) {
            $this->_user = User::findByPhone($this->phone);
//            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
