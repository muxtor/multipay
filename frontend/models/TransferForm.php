<?php
namespace frontend\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class TransferForm extends Model
{
    public $phone;
    public $sum_to;
    public $sum_from;
    public $sms_code;
    public $isProtected;
    public $protected_code;
    public $comment;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['sms_code', 'required', 'on'=>'step2'],
            ['sms_code', 'validateCode', 'on'=>'step2'],
            ['phone', 'required', 'message' =>  Yii::t('common.models.user', 'Поле {attribute} не должно быть пустым')],
            ['sum_to', 'required', 'message' =>  Yii::t('common.models.user', 'Поле {attribute} не должно быть пустым')],
            ['sum_to', 'double'],
            ['sum_from', 'required', 'message' =>  Yii::t('common.models.user', 'Поле {attribute} не должно быть пустым')],
            ['sum_from', 'validateBalance', 'except'=>'request'],
            [['comment'], 'string', 'max' => 255],
            ['isProtected', 'boolean'],
            ['phone', 'validatePhone', 'message' =>  Yii::t('common.models.user', 'Запись с таким номером телефона не существует')],
//            ['isProtected', 'required', 'requiredValue' => true, 'on'=>'step1'],
            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'phone' => Yii::t('common.models.user', 'Адресат'),
            'sum_to' => Yii::t('common.models.user', 'Сумма перевода'),
            'sum_from' => Yii::t('common.models.user', 'Сумма к списанию'),
            'comment' => Yii::t('common.models.user', 'Комментарий'),
            'isProtected' => Yii::t('common.models.user', 'Защищенный перевод'),
            'protected_code' => Yii::t('common.models.user', 'Код протекции'),
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
            $this->addError($attribute, Yii::t('common.models.user','Пользователя с таким номером телефона не существует'));
        }
        if ($user && $user->id == \Yii::$app->user->id) {
            $this->addError($attribute, Yii::t('common.models.user','Перевод средств самому себе невозможен!'));
        }
    }
    
    public function validateBalance($attribute, $params)
    {
        $user = \Yii::$app->user->identity;
        if (empty($user->ballance->money_amount) || $user->ballance->money_amount < $this->$attribute) {
            $this->addError($attribute, Yii::t('common.models.user','На вашем балансе недостаточно средств'));
        } 
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
