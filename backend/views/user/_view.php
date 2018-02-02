<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

?>
<div class="user-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
//            'auth_key',
//            'password_hash',
//            'password_reset_token',
            'email:email',
            'status',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
    
    <?php 
    if(isset($model->ballance)) {
        echo DetailView::widget([
        'model' => $model->ballance,
        'attributes' => [
            'money_amount',
            'money_bonus_ballance',
        ],
    ]);
    }
    ?>

</div>
