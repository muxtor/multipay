<?php

namespace common\modules\settings\controllers;

use Yii;
use common\modules\settings\models\Setting;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\base\DynamicModel;

class DefaultController extends Controller
{
    /**
     * Defines the controller behaviors
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    /**
     * Lists all Settings.
     * @return mixed
     */
    public function actionIndex()
    {
        $settings = Yii::$app->settings;
        if (Yii::$app->request->isPost) {
            foreach (Yii::$app->request->post() as $key => $value) {
                if (strpos($key, 'currency-') !== false) {
                    $exploded = explode('-', $key);
                    $section = $exploded[0];
                    $key_name = $exploded[1];
                    $type = $exploded[2];

                    $validators = Setting::getTypes(false);
                    if (!array_key_exists($type, $validators)) {
                        Yii::$app->session->addFlash('error', Yii::t('settings', 'НЕКОРРЕКТНЫЙ_ФОРМАТ'));
                        return $this->render('index');
                    }

                    $model = DynamicModel::validateData([
                        'value' => $value
                    ], [
                        $validators[$type],
                    ]);

                    if ($model->hasErrors()) {
                        Yii::$app->session->addFlash('error', Yii::t('settings', $value . ' - неверный формат'));
                        return $this->render('index');
                    }

                    $settings->set($key_name, $value, $section, $type);
                }
            }
            Yii::$app->session->addFlash('success', Yii::t('settings', 'ДАННЫЕ_УСПЕШНО_СОХРАНЕНЫ'));
        }
        return $this->render('index');
    }
}