<?php
/**
 * @author Pavlyenko Maksym <maxmpvl@gmail.com>
 */

namespace common\components;

use Yii;
use yii\base\Component;
use common\models\User;
use yii\helpers\ArrayHelper;
use common\models\TariffPlanRules;
use common\models\BonusHistory;

class Helper extends Component
{
    public static function setBonus($id, $type)
    {
        $user = User::findOne($id);
        if (!$user) {
            return FALSE;
        }
        
        $tarif = $user->tariffPlan;
        if (!$tarif) {
            return FALSE;
        }
        $rules = ArrayHelper::index($tarif->rules, 'tpr_type');
        if (empty($rules[$type])) {
            return FALSE;
        }
        
        if ($rules[$type]['tpr_period'] == TariffPlanRules::PERIOD_ONETIME && BonusHistory::find()->where(['bh_user_id' => $id, 'bh_type' => $type])->exists()) {
            return FALSE;
        }
        
        if ($rules[$type]['tpr_active'] == TariffPlanRules::STATUS_BLOCK) {
            return FALSE;
        }
        
        $res = self::saveHistory($id, $type, $rules[$type]['tpr_period'], $rules[$type]['tpr_bonus_value']);
        if ($res) {
            $user->ballance->money_bonus_ballance += $rules[$type]['tpr_bonus_value'];
            $user->ballance->save(FALSE);
        }
        return TRUE;
    }
    
    private static function saveHistory($id, $type, $period, $bonus) {
        $history = new BonusHistory();
        $history->bh_user_id    = $id;
        $history->bh_type       = $type;
        $history->bh_period     = $period;
        $history->bh_bonus      = $bonus;
        if ($history->save()) {
            return TRUE;
        }
       
        return FALSE;
    }
    
    public static function generateSmsCode() {
        $num = range(0, 9);
        shuffle($num);
        $code_array = array_slice($num, 0, 6);
        $code = implode("", $code_array);
        return $code;
    }
    
    public static function prepareString($string) {
        $query = trim($string);
        $query = strip_tags($query);
        $symbols = ['\\', '\'', '"', '-', '_', '/', '(', ')', '[', ']', '{', '}', '@', '#', '$', '%', '^', '&',
            '*', '|', '?', '>', '<', ',', '.', ':', ';'];
        $query = str_replace($symbols, '', $query);
        $query = strtolower($query);
        return $query;
    }
}
