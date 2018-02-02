<?php
/**
 * Created by PhpStorm.
 * User: Ulugbek
 * Date: 09.04.16
 * Time: 13:44
 */ ?>
<ul>
    <li<?= $active == 0 ? ' style="background:#a5eb94;"' : '' ?>><a href="/user/settings-personal"><i class="fa-profile-settings"></i><?=Yii::t('user', 'Настройки профиля')?></a></li>
    <li<?= $active == 1 ? ' style="background:#a5eb94;"' : '' ?>><a href="/user/settings-safety"><i class="fa-security"></i><?=Yii::t('user', 'Безопасность')?></a></li>
    <li<?= $active == 2 ? ' style="background:#a5eb94;"' : '' ?>><a href="/user/settings-access-management"><i class="fa-dostup"></i><?=Yii::t('user', 'Управление доступом')?></a></li>
</ul>