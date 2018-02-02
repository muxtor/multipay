<?php

namespace common\models;

use Yii;
use common\models\User;
use common\models\Providers;

/**
 * This is the model class for table "{{%pay_template}}".
 *
 * @property integer $pt_id
 * @property integer $pt_user_id
 * @property integer $pt_provider_id
 * @property string $pt_accaunt
 * @property string $pt_currency
 * @property string $pt_summ
 * @property string $pt_system
 */
class PayTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pt_user_id', 'pt_provider_id', 'pt_system', 'pt_summ', 'pt_currency'], 'required'],
            [['pt_user_id', 'pt_provider_id'], 'integer'],
            [['pt_summ'], 'number'],
            [['pt_accaunt', 'pt_currency', 'pt_system'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pt_id' => Yii::t('paytemplate/model', 'PAY_TEMPLATE_ID'),
            'pt_user_id' => Yii::t('paytemplate/model', 'PAY_TEMPLATE_USER'),
            'pt_provider_id' => Yii::t('paytemplate/model', 'PAY_TEMPLATE_PROVIDER'),
            'pt_accaunt' => Yii::t('paytemplate/model', 'PAY_TEMPLATE_ACCOUNT'),
            'pt_currency' => Yii::t('paytemplate/model', 'PAY_TEMPLATE_CURRENCY'),
            'pt_summ' => Yii::t('paytemplate/model', 'PAY_TEMPLATE_SUMM'),
            'pt_system' => Yii::t('paytemplate/model', 'PAY_TEMPLATE_SYSTEM'),
        ];
    }
    
    public function getUser()
    {
        $user = $this->hasOne(User::className(), ['id' => 'pt_user_id']);
        return $user->exists() ? $user : new User(); 
    }
    
    public function getProvider()
    {
        $provider = $this->hasOne(Providers::className(), ['id' => 'pt_provider_id']);
        return $provider->exists() ? $provider : new Providers;
    }
}
