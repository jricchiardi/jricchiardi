<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Campaign;

/**
 * CampaignSearch represents the model behind the search form about `common\models\Campaign`.
 */
class CampaignSearch extends Campaign
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CampaignId', 'IsFuture', 'IsActual', 'IsActive'], 'integer'],
            [['Name', 'PlanDateFrom', 'PlanDateTo', 'PlanSettingDateFrom', 'PlanSettingDateTo'], 'safe'],
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
        $query = Campaign::find();

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
            'CampaignId' => $this->CampaignId,
            'IsFuture' => $this->IsFuture,
            'IsActual' => $this->IsActual,
            'PlanDateFrom' => $this->PlanDateFrom,
            'PlanDateTo' => $this->PlanDateTo,
            'PlanSettingDateFrom' => $this->PlanSettingDateFrom,
            'PlanSettingDateTo' => $this->PlanSettingDateTo,
            'IsActive' => $this->IsActive,
        ]);

        $query->andFilterWhere(['like', 'Name', $this->Name]);

        return $dataProvider;
    }
}
