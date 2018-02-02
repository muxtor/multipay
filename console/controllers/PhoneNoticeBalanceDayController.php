<?php
namespace console\controllers;

use common\models\User;
use yii\console\Controller;
use Yii;
use yii\db\Query;

/**
 * Class PhoneNoticeBalanceDayController
 * @package console\controllers
 * консольная комманда, которая каждый день должна мониторить баланс пользователя и наличие подписки и продлевать до конца месяца
 */

class PhoneNoticeBalanceDayController extends Controller
{
    public function actionIndex()
    {
        $usersIds = (new Query())
            ->select('id')
            ->from(User::tableName())
            ->where('MONTH(notice_balannce_isPhone_activationDateEnd) = MONTH(NOW())')
            ->andWhere('DAYOFMONTH(notice_balannce_isPhone_activationDateEnd) = :last', [':last' => (int)date('t')])
            ->andWhere(['notice_balannce_isPhone' => 1])
            ->andWhere(['status' => User::STATUS_ACTIVE])
            ->column();
        if ($usersIds) {
            foreach ($usersIds as $id) {
                $user = User::findOne($id);
                /** @var $user User */
                if ($user->ballance->money_amount <= 0) {//нет денег - нет подписки
                    $user->notice_balannce_isPhone = 0;
                    $user->save();
                } else {
                    //проверяем на сколько хватает баланса на счету, снимаем деньги и продлеваем подписку
                    $daysInMonth = (int) date('t');
                    $serviceCost = (int) Yii::$app->settings->get('sms_change_balance', 'currency');
                    $currentDay = (int) date('j');
                    $priceTillMonthEnd = ceil(($serviceCost/$daysInMonth) * ($daysInMonth - $currentDay));//стоимость подписки до конца месяца
                    if ($user->ballance->money_amount < $priceTillMonthEnd) {//нет денег до конца месяца
                        //на сколько дней есть денег на балансе
                        $daysEnough = floor($user->ballance->money_amount/((int) Yii::$app->settings->get('sms_change_balance', 'currency')/$daysInMonth));
                        if ($daysEnough > 0) {
                            $today = new \DateTime(date('Y-m-d'));
                            $dateEnd = $today->add(new \DateInterval('P'.$daysEnough.'D'));
                            $user->notice_balannce_isPhone_activationDateEnd = $dateEnd->format('Y-m-d') . ' 23:59:59';
                            //снимаем деньги с баланса
                            $user->ballance->money_amount -= ceil(($serviceCost/$daysInMonth) * $daysEnough);
                            $user->ballance->save(false);
                        }
                    } else {
                        $user->notice_balannce_isPhone_activationDateEnd = date('Y-m-t') . ' 23:59:59';
                        $user->ballance->money_amount -= $priceTillMonthEnd;
                        $user->ballance->save(false);
                    }
                }
            }
        }
    }
}