<?php

namespace frontend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PaymentInApi;

/**
 * PaymentInApiSearch represents the model behind the search form about `common\models\PaymentInApi`.
 */
class PaymentInApiSearch extends PaymentInApi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pay_id', 'agent_id', 'user_id', 'api_id'], 'integer'],
            [['pay_created', 'pay_updated'], 'safe'],
            [['pay_sum'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PaymentInApi::find()->where(['agent_id'=>Yii::$app->user->id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'pay_id' => $this->pay_id,
            'pay_created' => $this->pay_created,
            'pay_updated' => $this->pay_updated,
            'agent_id' => $this->agent_id,
            'user_id' => $this->user_id,
            'pay_sum' => $this->pay_sum,
            'api_id' => $this->api_id,
        ]);

        return $dataProvider;
    }
}
