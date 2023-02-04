<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SnapshotForecast;

/**
 * SnapshotForecastSearch represents the model behind the search form about `common\models\SnapshotForecast`.
 */
class SnapshotForecastSearch extends SnapshotForecast
{
    public $ClientId;
    public $TradeProductId;
    public $SellerId;
    public $DsmId;
    public $RsmId;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClientProductId', 'CampaignId', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Total'], 'integer'],
            [['ClientId','TradeProductId','SellerId','DsmId','RsmId'],'safe'],
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
        $query = SnapshotForecast::find()
                ->innerJoinWith(['clientProduct',
                                 'clientProduct.client',
                                 'clientProduct.tradeProduct',
                                 'clientProduct.client.clientSellers',
                                 'clientProduct.client.clientSellers.seller'])
                ->innerJoin('user '.' p', 'p.UserId = [user].ParentId')
                ->innerJoin('user '.' rsm', 'p.ParentId = rsm.UserId');
        

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

        if(isset($this->ClientId) &&  $this->ClientId!="")
        {
            $query->andWhere(['client.ClientId'=>$this->ClientId]);
        }
        
        if(isset($this->SellerId) &&  $this->SellerId!="")
        {            
            $query->andWhere(['client_seller.SellerId'=>$this->SellerId]);
        }        
        
        if(isset($this->DsmId) &&  $this->DsmId!="")
        {            
            $query->andWhere(['p.UserId'=>$this->DsmId]);
        }         
        
        if(isset($this->RsmId) &&  $this->RsmId!="")
        {            
            $query->andWhere(['rsm.UserId'=>$this->RsmId]);
        }    
        
        // grid filtering conditions
        $query->andFilterWhere([           
            'CampaignId' => $this->CampaignId,            
        ]);
        $query->orderBy('client.Description,trade_product.Description ASC');
        return $dataProvider;
    }
}
