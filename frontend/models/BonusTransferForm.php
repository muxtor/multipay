<?php
namespace frontend\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class BonusTransferForm extends Model
{
    public $bonus;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['bonus', 'required', 'message' =>  Yii::t('common.models.user', 'Поле {attribute} не должно быть пустым')],
            ['bonus', 'integer'],
            ['bonus', 'validateBalance'],
            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'bonus' => Yii::t('common.models.user', 'Сумма перевода'),
        ];
    }
    

    
    public function validateBalance($attribute, $params)
    {
        $user = \Yii::$app->user->identity;
        $settings = Yii::$app->settings;
        $settings->clearCache();
        if ($user->ballance->money_bonus_ballance < $this->$attribute) {
            $this->addError($attribute, Yii::t('common.models.user','У Вас недостаточно бонусов для этого обмена'));
        } elseif ($this->$attribute < $settings->get('min_bonus_transfer', 'currency')) {
            $this->addError($attribute, Yii::t('common.models.user','Слишком маленькое количество для обмена'));
        }
    }

}
