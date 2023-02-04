<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LockForecast;

/**
 * LockForecastSearch represents the model behind the search form about `common\models\LockForecast`.
 */
class LockForecastSearch extends LockForecast
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['LockId'], 'integer'],
            [['DateFrom', 'DateTo'], 'safe'],
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
        $query = LockForecast::find();

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
            'LockId' => $this->LockId,
            'DateFrom' => $this->DateFrom,
            'DateTo' => $this->DateTo,
        ]);

        return $dataProvider;
    }
}
