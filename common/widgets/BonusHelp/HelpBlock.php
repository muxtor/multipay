<?php
namespace common\widgets\BonusHelp;
use common\models\BonusHelp;
use common\models\Language;

class HelpBlock extends \yii\bootstrap\Widget
{
    public $alias;
    
    public function init(){}

    public function run() {
        
        $model = BonusHelp::find()
                ->where(['bh_language'=>Language::getCurrent()->lang_url])
                ->andWhere('bh_alias = :alias', [':alias' => $this->alias])
                ->one();
        return $this->render('view', [
            'model' => $model,
        ]);
    }
}