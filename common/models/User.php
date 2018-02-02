<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\UserCards;
use common\models\UserWallets;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property string $last_login
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $notice_balannce_isPhone_isActive
 * @property integer $notice_safety_isEmail
 * @property integer $notice_plannedPayments_isEmail
 * @property integer $notice_latePayments_isEmail
 * @property integer $notice_news_isEmail
 * @property integer $notice_balannce_isEmail
 * @property integer $notice_balannce_isPhone
 * @property string $password write-only password
 * @property string $notice_balannce_isPhone_activationDate
 * @property string $notice_balannce_isPhone_activationDateEnd
 * @property string $user_language
 * @property integer $user_agent
 */

class User extends ActiveRecord implements IdentityInterface
{
    public $old_password;
    public $new_password;
    public $verifyPassword;
    
    
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_REG_STEP_ONE = 20;
    const STATUS_REG_STEP_TWO = 30;
    
    const CODE_SEND_NEVER = 0;
    const CODE_SEND_SUBNETWORK = 1;
    const CODE_SEND_IP = 2;
    const CODE_SEND_ALWAYS = 3;
    
    const CODE_METHOD_SMS = 1;
    const CODE_METHOD_EMAIL = 2;
    
    
    public static function codeSendLabels()
    {
        return [
            self::CODE_SEND_NEVER => Yii::t('user', 'Никогда не высылать'),
            self::CODE_SEND_SUBNETWORK => Yii::t('user', 'Высылать при изменении подсети'),
            self::CODE_SEND_IP => Yii::t('user', 'Высылать при изменении IP'),
            self::CODE_SEND_ALWAYS => Yii::t('user', 'Всегда высылать код'),
        ];
    }
    
    public static function codeMathodLabels()
    {
        return [
            self::CODE_METHOD_SMS => Yii::t('user', 'SMS'),
            self::CODE_METHOD_EMAIL => Yii::t('user', 'E-mail'),
        ];
    }
    
    
    public static function getStatusLabels()
    {
        return [
            static::STATUS_DELETED => 'Не авторизован',
            static::STATUS_ACTIVE => 'Авторизован',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED,  self::STATUS_REG_STEP_ONE, self::STATUS_REG_STEP_TWO]],
            [['email', 'firstName', 'lastName', 'date_bird', 'phone', 'user_language', 'user_IP'], 'string'],
            [['notice_safety_isEmail', 'notice_safety_isPhone', 'notice_plannedPayments_isEmail', 'notice_plannedPayments_isPhone', 'notice_latePayments_isEmail',
                'notice_latePayments_isPhone', 'notice_news_isEmail', 'notice_news_isPhone', 'verification_code_send', 'verification_code_method', 'country_id', 
                'isReferral', 'referral_id', 'notice_balannce_isEmail', 'notice_balannce_isPhone', 'notice_balannce_isPhone_isActive', 'user_agent'], 'integer'],
            [['country_id', 'last_login', 'isReferral', 'referral_id', 'notice_balannce_isPhone_activationDate', 'notice_balannce_isPhone_activationDateEnd'], 'safe'],
            [['email'], 'email'],
            [['phone'], 'unique'],
            ['old_password', 'required', 'message' =>  Yii::t('common.models.user', 'Поле {attribute} не должно быть пустым'), 'on'=>'access'],
            ['old_password', 'validateOldPassword', 'on'=>'access'],
            ['new_password', 'string', 'min' => 8, 'on'=>'access'],
            ['verifyPassword', 'compare', 'compareAttribute' => 'new_password', 'message' => Yii::t('common.models.user', "Повторный пароль указан неверно"), 'on'=>'access'],
        ];
    }
    

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
    public function validateOldPassword($attribute, $params)
    {
        if (!Yii::$app->security->validatePassword($this->old_password, $this->password_hash)) {
            $this->addError($attribute, 'Incorrect password.');
        }
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    public function getCards()
    {
        return $this->hasMany(UserCards::className(), ['card_user_id' => 'id']);
    }
    
    public function getWallets()
    {
        return $this->hasMany(UserWallets::className(), ['wallet_user_id' => 'id']);
    }
    
    public function getBallance()
    {
        $balance =  $this->hasOne(MoneyBallance::className(), ['money_user_id' => 'id']);
        if (!$balance->exists()) {
            $balance = new MoneyBallance();
            $balance->money_user_id = $this->id;
        }
        return $balance;
    }
    
    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]);
    }
    
    public static function getCountryes()
    {
        return Country::find()->asArray()->all();
    }
    
    public function getFullName()
    {
        $fio = $this->firstName.' '.$this->lastName;
        return $fio;
    }
    
    public function getTariffPlan()
    {
        if (!$this->isNewRecord && !MoneyBallance::find()->where(['money_user_id'=>  $this->id])->exists()) {
            $this->save(FALSE);
        }
        $tafif = TariffPlan::find()->where(':total >= tp_transfer_min  AND :total <= tp_transfer_max', [
            ':total' => $this->ballance->money_transaction_amount,
        ])->one();
        return $tafif ? $tafif : false;
    }
    
    public function getReferralLink()
    {
        $link = \yii\helpers\Url::to(['site/referral', 'rel' => $this->id], TRUE);
        return $link;
    }
    
    
}
