<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ClientProduct;

/**
 * ClientProductSearch represents the model behind the search form about `common\models\ClientProduct`.
 */
class ClientProductSearch extends ClientProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientProductId', 'ClientId', 'IsForecastable'], 'integer'],
            [['GmidId', 'TradeProductId'], 'safe'],
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
        $query = ClientProduct::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ClientProductId' => $this->ClientProductId,
            'ClientId' => $this->ClientId,
            'IsForecastable' => $this->IsForecastable,
        ]);

        $query->andFilterWhere(['like', 'GmidId', $this->GmidId])
            ->andFilterWhere(['like', 'TradeProductId', $this->TradeProductId]);

        return $dataProvider;
    }
}
