<?php


?>
<div class="b-content">
    <div class="b-content-full-width">
        <h1><?= Yii::t('payment', 'ОЖИДАЕМ_РЕЗУЛЬТАТОВ_ПРОВЕРКИ')?></h1>
        <p><?= Yii::t('payment', 'Ожидайте результатов проверки аккаунта')?><span id="timer"></span> <?//= Yii::t('payment', 'СЕКУНД')?></p>
    </div>
</div>

<script>
    function timer(time){
        if(time>0){
            setTimeout (function (){
                time = time - 1;
                timer(time);
                dot = $('#timer span').length;
                if(dot==0){ dots = '<span>.</span>'; }
                if(dot==1){ dots = '<span>.</span><span>.</span>'; }
                if(dot==2){ dots = '<span>.</span><span>.</span><span>.</span>'; }
                if(dot==3){ dots = '<span>.</span>'; }
                $('#timer').html(dots); }, 1000);
        }else{
            $('#timer').html('');
            $.ajax({
                url: "/payments/change-check-status",
                type: "post",
                data: {id: <?= $id?>, date: '<?= $cur_date?>'},
                success: function (data) {
                    if (data.success == "yes") {
                        timer(10);
                    }
                }
            });
        }
    }
    window.onload = function () {
        timer(10);
    };
</script>
