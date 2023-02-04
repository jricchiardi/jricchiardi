<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Sale;

/**
 * SaleSearch represents the model behind the search form about `common\models\Sale`.
 */
class SaleSearch extends Sale
{
    public $TradeProductId;
    public $ValueCenterId;
    public $PerformanceCenterId;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientId', 'Month', 'Amount', 'Total', 'CampaignId'], 'integer'],
            [['GmidId'], 'safe'],
            [['TradeProductId','ValueCenterId','PerformanceCenterId'],'safe']
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
        $query = Sale::find()->joinWith(['gmid','gmid.tradeProduct','gmid.tradeProduct.performanceCenter']);

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
            'ClientId' => $this->ClientId,
            'Month' => $this->Month,
            'Amount' => $this->Amount,
            'Total' => $this->Total,
            'CampaignId' => $this->CampaignId,
            'trade_product.TradeProductId' => $this->TradeProductId,
            'performance_center.PerformanceCenterId' => $this->PerformanceCenterId,
            'performance_center.ValueCenterId' => $this->ValueCenterId,
        ]);

        $query->andFilterWhere(['like', 'GmidId', $this->GmidId]);

        return $dataProvider;
    }
}
