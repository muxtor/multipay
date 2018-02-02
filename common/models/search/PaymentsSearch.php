<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Payments;

/**
 * PaymentsSearch represents the model behind the search form about `common\models\Payments`.
 */
class PaymentsSearch extends Payments
{
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pay_id', 'pay_user_id', 'pay_status', 'pay_is_checked', 'pay_is_payed', 'pay_check_status', 'pay_system', 'pay_type'], 'integer'],
            [['pay_pc_id', 'pay_created', 'pay_payed', 'pay_pc_provider_id', 'pay_currency', 'pay_result', 'pay_result_desc', 'pay_check_result_desc', 'pay_check_result'], 'safe'],
            [['pay_summ', 'pay_commission', 'pay_rate'], 'number'],
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
        $query = Payments::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'pay_id' => $this->pay_id,
            'pay_user_id' => $this->pay_user_id,
            'pay_status' => $this->pay_status,
            'pay_check_status' => $this->pay_check_status,
            'pay_created' => $this->pay_created,
            'pay_payed' => $this->pay_payed,
            'pay_summ' => $this->pay_summ,
            'pay_commission' => $this->pay_commission,
            'pay_rate' => $this->pay_rate,
            'pay_is_checked' => $this->pay_is_checked,
            'pay_is_payed' => $this->pay_is_payed,
            'pay_type' => $this->pay_type,
            'pay_system' => $this->pay_system,
        ]);

        $query->andFilterWhere(['like', 'pay_pc_id', $this->pay_pc_id])
            ->andFilterWhere(['like', 'pay_pc_provider_id', $this->pay_pc_provider_id])
            ->andFilterWhere(['like', 'pay_currency', $this->pay_currency])
            ->andFilterWhere(['like', 'pay_result', $this->pay_result])
            ->andFilterWhere(['like', 'pay_result_desc', $this->pay_result_desc])
            ->andFilterWhere(['like', 'pay_check_result', $this->pay_check_result])
            ->andFilterWhere(['like', 'pay_check_result_desc', $this->pay_check_result_desc]);

        return $dataProvider;
    }
}
