<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;
use common\models\Language;

/**
 * Signup form
 */
class SignupForm extends Model
{
//    public $username;
//    public $email;
    public $phone;
    public $sms_code;
    public $password;
    public $verifyPassword;
    public $confirm;

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            ['sms_code', 'required', 'message' =>  Yii::t('signup', 'Необходимо заполнить sms код'), 'on'=>'step2'],//Yii::t('common.models.user', 'Поле {attribute} не должно быть пустым')
            ['sms_code', 'validateCode', 'on'=>'step2'],
            ['password', 'required', 'message' =>  Yii::t('common.models.user', 'Пароль должен содержать:                                   Не менее 8 знаков                                      Цифры и буквы латинского алфавита'), 'on'=>'step2'], //Пароль должен содержать:<br />Не менее 8 знаков<br />Цифры и буквы латинского алфавита
            ['password', 'string', 'min'=>8, 'tooShort'=>Yii::t('common.models.user', 'Пароль должен содержать:                                   Не менее 8 знаков                                      Цифры и буквы латинского алфавита')],
            ['verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('user', "Пароли не совпадают"), 'on'=>'step2'],
            ['phone', 'required', 'message' =>  Yii::t('signup', 'Необходимо заполнить телефон')],//Yii::t('common.models.user', 'Поле {attribute} не должно быть пустым')
            ['phone', 'unique', 'targetClass' => '\common\models\User', 'message' =>  Yii::t('user', 'Запись с таким номером телефона уже существует')],
            ['confirm', 'required', 'requiredValue' => true, 'on'=>'step1'],
        ];
    }
    public function scenarios()
    {
        return [
            'step0' => ['phone'],
            'step1' => ['phone', 'confirm'],
            'step2' => ['phone', 'verifyPassword', 'password', 'sms_code'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        $this->phone = preg_replace("/\D/", "", $this->phone);
        if ($this->validate()) {
            $user = new User();
                
            $user->phone = $this->phone;
            $user->status = User::STATUS_ACTIVE;
//            \yii\helpers\VarDumper::dump($user->validate(), 10, 10);
//            die();
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                if (!empty($_COOKIE['referral'])) {
                    $referral = User::findOne($_COOKIE['referral']);
                    if ($referral) {
                        \common\components\Helper::setBonus($referral->id, \common\models\TariffPlanRules::TYPE_REG);
                        $user->isReferral = 1;
                        $user->referral_id = $referral->id;
                        $user->save(FALSE);
                        setcookie("referral","");
                    }
                }
                $user->ballance->save(FALSE);
                $user->user_language = Language::getCurrent()->lang_url;
                $user->user_IP = Yii::$app->getRequest()->getUserIP();
                $user->save(FALSE);
                \common\components\Helper::setBonus($user->id, \common\models\TariffPlanRules::TYPE_REG_SELF);
                return $user;
            }
        }

        return null;
    }
    public static function generateSmsCode() {
        $num = range(0, 9);
        shuffle($num);
        $code_array = array_slice($num, 0, 4);
        $code = implode("", $code_array);
        return $code;
    }
    
    public function validateCode($attribute, $params)
    {
        $session = Yii::$app->session;
        $code = $session->get('sms_code');
        if (!empty($code) && $this->$attribute != $code) {
            $this->addError($attribute, Yii::t('common.models.user', 'Неверный код подтверждения'));
        } elseif (empty($code)) {
            $this->addError($attribute, Yii::t('common.models.user','Код просрочен'));
        }
    }
}
