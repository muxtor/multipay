<?php
namespace frontend\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $phone;
    public $sms_code;
    public $password;
    public $verifyPassword;
    public $confirm;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['sms_code', 'required', 'on'=>'step2'],
            ['sms_code', 'validateCode', 'on'=>'step2'],
            ['password', 'required', 'on'=>'step2'],
            ['password', 'string', 'min' => 8],
            ['verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('common.models.user', "Повторный пароль указан неверно"), 'on'=>'step2'],
            ['phone', 'required', 'message' =>  Yii::t('common.models.user', 'Поле {attribute} не должно быть пустым')],
            ['phone', 'validatePhone', 'message' =>  Yii::t('common.models.user', 'Запись с таким номером телефона не существует')],
            ['confirm', 'required', 'requiredValue' => true, 'on'=>'step1'],
        ];
    }
    
    public function scenarios()
    {
        return [
            'step1' => ['phone', 'confirm'],
            'step2' => ['phone', 'verifyPassword', 'password', 'sms_code'],
        ];
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
    
    public function validatePhone($attribute, $params)
    {
        $user = $this->getUser();
        if ($this->_user === null) {
            $this->addError($attribute, Yii::t('common.models.user','Запись с таким номером телефона не существует'));
        } 
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->setPassword($this->password);
            $user->removePasswordResetToken();

            return $user->save(false);
        }

        return null;
    }
    
    protected function getUser()
    {
        $this->phone = preg_replace("/\D/", "", $this->phone);
        if ($this->_user === null) {
            $this->_user = \common\models\User::findByPhone($this->phone);
        }

        return $this->_user;
    }
}
