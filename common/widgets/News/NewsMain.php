<?php
namespace common\widgets\News;
use common\models\News;
use common\models\Language;

class NewsMain extends \yii\bootstrap\Widget
{
    public function init(){}

    public function run() {
        
        $query = News::find()
                ->where(['news_language'=>Language::getCurrent()->lang_url,'news_show'=>1])
                ->orderBy('news_date DESC')
                ->limit(2);
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->pagination = false;
        return $this->render('view', [
            'news' => $dataProvider->models,
        ]);
    }
}