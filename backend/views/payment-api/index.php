<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PaymentInApiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'API - операции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-in-api-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'pay_id',
            'pay_created',
            [
                'attribute' => 'api_id',
                'value' => 'api.api_title',
            ],
            [
                'attribute' => 'agent_id',
                'value' => 'userAgent.phone',
            ],
            [
                'attribute' => 'user_id',
                'value' => 'user.phone',
            ],
            'pay_sum',
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
