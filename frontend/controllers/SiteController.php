<?php
namespace frontend\controllers;

use common\models\Language;
use common\models\StaticPage;
use Yii;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\db\Expression;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\components\FrontendController;
use common\components\ReCaptcha;
use common\models\User;

/**
 * Site controller
 */
class SiteController extends FrontendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $main_sliders = \common\models\MainSlider::find()->all();
        $partners = \common\models\Partners::find()->where(['status'=>1])->orderBy('sortorder')->all();
        $model_signup = new SignupForm();
        $model_signup->scenario = 'step0';
        if ($model_signup->load(Yii::$app->request->post()) && $model_signup->validate()) {
            $session = Yii::$app->session;
            $session->set('user_phone', preg_replace("/\D/", "", $model_signup->phone));
            
            $this->redirect('register-step-one');
        }
        return $this->render('index', [
            'main_sliders' => $main_sliders,
            'model_signup' => $model_signup,
            'partners' => $partners,
        ]);
    }
    
    
    public function actionRegisterStepOne()
    {
        $model_signup = new SignupForm();
        $model_signup->scenario = 'step1';
        $session = Yii::$app->session;
        $model_signup->phone = $session->get('user_phone');
        $phoneCheck = User::find()->where(['phone' => $session->get('user_phone')])->count();
        if ($phoneCheck > 0) {
            $session->remove('user_phone');
            return $this->render('signup_step_one');
        }
        
        if ($model_signup->load(Yii::$app->request->post()) && $model_signup->validate() 
//                && ReCaptcha::verify(Yii::$app->params['recaptchaSecret'], Yii::$app->request->post('g-recaptcha-response'))
                ) {
            $code = $model_signup->generateSmsCode();
            $session->set('sms_code', $code);
            $session->set('sms_code_time', time());
            //sms->send тут будет
            Yii::$app->mainsms->api->sendSMS($session->get('user_phone'), 'Code: '.$code);
                $this->redirect('register-step-two');
        }
        return $this->render('signup_step_one', [
            'model' => $model_signup
            ]);
    }
    
    public function actionRegisterStepTwo()
    {
        $session = Yii::$app->session;
        
        $phone = $session->get('user_phone');
        
        if (strlen($phone)<1) {
            return $this->actionIndex();
        }
        $model_signup = new SignupForm();
        $model_signup->scenario = 'step2';
        $model_signup->phone = $phone;
        
        if ($model_signup->load(Yii::$app->request->post()) && $user = $model_signup->signup()) {
            if (Yii::$app->getUser()->login($user)) {
                    $session->remove('user_phone');
                    $session->remove('sms_code');
                    $session->remove('sms_code_time');
                    $this->saveUserLoginStats();
                    return $this->redirect('/');
                }
        }
        return $this->render('signup_step_two', [
            'model' => $model_signup,
        ]);
    }
    
    public function actionReferral($rel)
    {
        setcookie('referral', $rel, time() + 60*60*24*1, '/', '');
        return $this->redirect('/');
    }
    
    
    public function actionResetPassword()
    {
        $model_signup = new ResetPasswordForm();
        $model_signup->scenario = 'step1';
        $session = Yii::$app->session;
        
        if ($model_signup->load(Yii::$app->request->post()) && $model_signup->validate()
                && ReCaptcha::verify(Yii::$app->params['recaptchaSecret'], Yii::$app->request->post('g-recaptcha-response'))) {
            $code = $model_signup->generateSmsCode();
            $session->set('user_phone', preg_replace("/\D/", "", $model_signup->phone));
            $session->set('sms_code', $code);
            $session->set('sms_code_time', time());
            //sms->send тут будет
            Yii::$app->mainsms->api->sendSMS($session->get('user_phone'), 'Code: '.$code);
                $this->redirect('reset-password-confirm');
        }
        return $this->render('change_password_step_one', [
            'model' => $model_signup,
        ]);
    }
    
    public function actionResetPasswordConfirm()
    {
        $model_signup = new ResetPasswordForm();
        $model_signup->scenario = 'step2';
        $session = Yii::$app->session;
        $model_signup->phone = $session->get('user_phone');
        
        if ($model_signup->load(Yii::$app->request->post()) && $model_signup->validate() && $model_signup->resetPassword()) {
            $session->remove('user_phone');
            $session->remove('sms_code');
            $session->remove('sms_code_time');
            Yii::$app->session->setFlash('success', Yii::t('common.models.user', 'Новый пароль сохранен.'));
            return $this->goHome();
        }
        return $this->render('change_password_step_two', [
            'model' => $model_signup,
        ]);
    }
    
    public function actionResendSms()
    {
        $session = Yii::$app->session;
        if (!$session->has('sms_code_time')) {
            $session->set('sms_code_time', time());
        }
        
        if (Yii::$app->request->isAjax && $session->has('user_phone') && $session->has('sms_code_time')) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $result = [
                'success' => false,
                'mes' => 'ждите'
            ];

            if((time()-$session->get('sms_code_time'))>60){
                $session->set('sms_code', SignupForm::generateSmsCode());
                //send SMS
                Yii::$app->mainsms->api->sendSMS($session->get('user_phone'), 'Code: '.$session->get('sms_code'));
                $session->set('sms_code_time', time());
                $result = [
                    'success' => TRUE,
                    'mes' => Yii::t('sendSMS', 'новый код отправлен'),
                    'code' => '*****'//$session->get('sms_code')
                ];
            } else {
                $time = 60 - (time()-$session->get('sms_code_time'));
                $result = [
                    'success' => false,
                    'mes' => Yii::t('sendSMS', 'повторная отправка sms будет доступна через ').'<span id="timer">'.$time . '</span>'.Yii::t('sendSMS', ' сек.'),
                    'code' => $session->get('sms_code'),
                    'time' => $time
                ];
            }
            return $result;
            
        }
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $session = Yii::$app->session;
        $count = $session->has('login_count') ? $session->get('login_count') : 1;
        
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $this->saveUserLoginStats();
            return $this->goHome();
        } else {
            if ($count>3) {
                $session->remove('login_count');
                //отправляем уведомление (проверив что такой пользователь есть)
                $user = User::findByPhone($model->phone);
                if ($user && !$session->has('notice_safety_wrong_pass')) {
                    $session->set('notice_safety_wrong_pass', TRUE);
                    $message = Yii::t('user/notice_safety', 'Зарегистрировано три неудачных попытки входа в Ваш аккаунт на MultiPay!', [], $user->user_language);
                    Yii::$app->mainsms->api->sendSMS($user->phone, $message);
                    if ($user->notice_safety_isEmail && $user->email) {
                        Yii::$app->mailer->compose()
                            ->setTo($user->email)
                            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                            ->setSubject(Yii::t('user/notice_safety', 'Уведомление безопасности от MultiPay!', [], $user->user_language))
                            ->setTextBody($message)
                            ->send();
                    }
                }
                return $this->redirect('logine');
            }
            $session->set('login_count', $count+1);
            return $this->goHome();    
        }
    }
    
     public function actionLogine()
    {
        $model = new LoginForm(); 
        if (ReCaptcha::verify(Yii::$app->params['recaptchaSecret'], Yii::$app->request->post('g-recaptcha-response')) && $model->load(Yii::$app->request->post()) && $model->login()) {
            $this->saveUserLoginStats();
            return $this->goHome();
        }
        
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    
    private function saveUserLoginStats() {
        $uls = new \common\models\UserLoginStats();
        $uls->uls_IP = Yii::$app->getRequest()->getUserIP();
        $uls->uls_app = (Yii::$app->devicedetect->isMobile() || Yii::$app->devicedetect->isTablet()) ? Yii::t('common.models.UserLoginStats', 'Мобильное устройство') : Yii::t('common.models.UserLoginStats', 'Web-версия');
        $country = json_decode(file_get_contents('http://api.sypexgeo.net/json/'.$uls->uls_IP));
        $uls->uls_location = isset($country->country->name_en) ? $country->country->name_en : Yii::t('common.models.UserLoginStats', 'Не определено');
        $uls->uls_user_id = \Yii::$app->user->identity->id;
//        \yii\helpers\VarDumper::dump($country->country, 10, 1);
//        \yii\helpers\VarDumper::dump($uls->save(), 10, 1);
//        die();
        $uls->save();
        Yii::$app->db->createCommand()->update('{{%user}}', [
            'last_login' => new Expression('NOW()'),
        ], 'id = :id', [':id' => \Yii::$app->user->identity->id])->execute();
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
       Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        $model = $this->loadModel('about');
        return $this->render('about',[
        'model' => $model
        ]);
    }

    public function loadModel($alias)
    {
        if (($model = StaticPage::find()->where('page_alias=:name AND page_language=:lang', [':name' => $alias,':lang' => Language::getCurrent()->lang_url])->one()) !== null) {
            return $model;
        }elseif(($model = StaticPage::find()->where('page_alias=:name', [':name' => $alias])->one()) !== null){
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
//    public function actionResetPassword($token)
//    {
//        try {
//            $model = new ResetPasswordForm($token);
//        } catch (InvalidParamException $e) {
//            throw new BadRequestHttpException($e->getMessage());
//        }
//
//        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
//            Yii::$app->session->setFlash('success', 'New password was saved.');
//
//            return $this->goHome();
//        }
//
//        return $this->render('resetPassword', [
//            'model' => $model,
//        ]);
//    }
}
