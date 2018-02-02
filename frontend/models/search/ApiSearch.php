<?php

namespace frontend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Api;

/**
 * ApiSearch represents the model behind the search form about `common\models\Api`.
 */
class ApiSearch extends Api
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['api_id'], 'integer'],
            [['api_title', 'api_description', 'api_key'], 'safe'],
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
        $query = Api::find()->where(['api_status'=>1, 'user_id'=>Yii::$app->user->id]);

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
            'api_id' => $this->api_id,
        ]);

        $query->andFilterWhere(['like', 'api_title', $this->api_title])
            ->andFilterWhere(['like', 'api_description', $this->api_description])
            ->andFilterWhere(['like', 'api_key', $this->api_key]);

        return $dataProvider;
    }
}
