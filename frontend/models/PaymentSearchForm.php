<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class PaymentSearchForm extends Model
{
    
    public $check_number;
    public $check_year;
    public $check_month;
    public $check_day;
    public $check_hour;
    public $check_min;
    public $check_sec;
    public $terminal_id;
    public $check_date;
        
    public function rules()
    {
        $p = new \yii\helpers\HtmlPurifier();
        return [
            [['check_number', 'check_date', 'terminal_id'], 'required'],
//            [['check_number', 'check_year', 'check_month', 'check_day',
//                'check_hour', 'check_min', 'check_sec', 'terminal_id'], 'required'],
            [['check_year', 'check_month', 'check_day',
                'check_hour', 'check_min', 'check_sec'], 'integer'],
            [['check_number', 'terminal_id', 'check_date'], 'string', 'max' => 250],
            [['check_number', 'terminal_id', 'check_date'], 'filter', 'filter' => [$p, 'process']],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'check_number' => Yii::t('payment/search', 'Номер чека'),
            'check_year' => Yii::t('payment/search', 'Год'),
            'check_month' => Yii::t('payment/search', 'Месяц'),
            'check_day' => Yii::t('payment/search', 'День'),
            'check_hour' => Yii::t('payment/search', 'Час'),
            'check_min' => Yii::t('payment/search', 'Минуты'),
            'check_sec' => Yii::t('payment/search', 'Секунды'),
            'terminal_id' => Yii::t('payment/search', 'Терминальная сеть'),
            'check_date' => Yii::t('payment/search', 'Дата чека'),
        ];
    }
    
}

