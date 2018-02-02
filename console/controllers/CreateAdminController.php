<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use common\models\Admin;

class CreateAdminController extends Controller
{

    public function actionIndex($email, $username, $password)
    {
        $user = new Admin();
        $user->email = $email;
        $user->username = $username;
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->setAttributes([
            'created_at' => time(),
            'updated_at' => time(),
            'status' => Admin::STATUS_ACTIVE,
            'role' => Admin::ROLE_ADMIN,
            'pass' => $password,
        ]);

        if ($user->save())
        {
            $this->stdout(\Yii::t('user', 'User has been created') . "!\n", Console::FG_GREEN);
        } else
        {
            $this->getErrors($user);
        }
    }

    protected function getErrors($user)
    {
        $this->stdout(\Yii::t('user', 'Please fix following errors:') . "\n", Console::FG_RED);
        foreach ($user->errors as $errors)
        {
            foreach ($errors as $error)
            {
                $this->stdout(" - " . $error . "\n", Console::FG_RED);
            }
        }
    }

}
