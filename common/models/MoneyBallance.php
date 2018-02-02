<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "money_ballance".
 *
 * @property integer $money_id
 * @property integer $money_user_id
 * @property string $money_amount
 * @property string $money_bonus_ballance
 *
 * @property User $moneyUser
 */
class MoneyBallance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'money_ballance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money_user_id'], 'required'],
            [['money_user_id', 'money_bonus_ballance'], 'integer'],
            [['money_amount'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'money_id' => Yii::t('common.models.MoneyBallance', 'Money ID'),
            'money_user_id' => Yii::t('common.models.MoneyBallance', 'Money User ID'),
            'money_amount' => Yii::t('common.models.MoneyBallance', 'Money Amount'),
            'money_bonus_ballance' => Yii::t('common.models.MoneyBallance', 'Money Bonus Ballance'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMoneyUser()
    {
        return $this->hasOne(User::className(), ['id' => 'money_user_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $user = User::findOne($this->money_user_id);
        /** @var $user User */
        if ($user) {
            if ($user->notice_balannce_isPhone) {
                $on_site = (strtotime(date('Y-m-d H:i:s')) - $user->created_at)/(60*60*24);
                //пользователь на сайте меньше времени бесплатной подписки
                if ($on_site <= (int)Yii::$app->settings->get('sms_notif_balance_free_period', 'currency')) {
                    return;
                }
                $currentDateEnd = new \DateTime($user->notice_balannce_isPhone_activationDateEnd);
                //месяц окончания подписки совпадает с текущим или меньше текущего
                if ((int)$currentDateEnd->format('mm') <= (int) date('m')) {
                    if (strtotime($user->notice_balannce_isPhone_activationDateEnd < strtotime(date('Y-m-t') . ' 23:59:59'))) {//до конца месяца не проплачено
                        //проверяем на сколько хватает баланса на счету, снимаем деньги и продлеваем подписку
                        $daysInMonth = (int) date('t');
                        $serviceCost = (int) Yii::$app->settings->get('sms_change_balance', 'currency');
                        $currentDay = (int) date('j');
                        $priceTillMonthEnd = ceil(($serviceCost/$daysInMonth) * ($daysInMonth - $currentDay));//стоимость подписки до конца месяца
                        if ($this->money_amount < $priceTillMonthEnd) {//нет денег до конца месяца
                            //на сколько дней есть денег на балансе
                            $daysEnough = floor($this->money_amount/((int) Yii::$app->settings->get('sms_change_balance', 'currency')/$daysInMonth));
                            if ($daysEnough > 0) {
                                $today = new \DateTime(date('Y-m-d'));
                                $dateEnd = $today->add(new \DateInterval('P'.$daysEnough.'D'));
                                $user->notice_balannce_isPhone_activationDateEnd = $dateEnd->format('Y-m-d') . ' 23:59:59';
                                //снимаем деньги с баланса
                                $this->money_amount -= ceil(($serviceCost/$daysInMonth) * $daysEnough);
                                $this->save(false);
                            }
                        } else {
                            $user->notice_balannce_isPhone_activationDateEnd = date('Y-m-t') . ' 23:59:59';
                            $this->money_amount -= $priceTillMonthEnd;
                            $this->save(false);
                        }
                    }
                }
            }
        }//end if user
    }
}
