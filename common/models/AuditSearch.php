<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Audit;

/**
 * AuditSearch represents the model behind the search form about `common\models\Audit`.
 */
class AuditSearch extends Audit {

    const SCENE_EXPORT = 'SCENE_EXPORT';      
    public $dateFrom;
    public $dateTo;
    public $RsmId;
    public $DsmId;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['AuditId', 'TypeAuditId', 'UserId', 'ClientId', 'CampaignId'], 'integer'],
            [['Description', 'Date'], 'safe'],
            [['dateFrom', 'dateTo'], 'safe'],
            [['DsmId','RsmId'],'safe'],
            [['CampaignId', 'dateFrom', 'dateTo'], 'required', 'on' => self::SCENE_EXPORT],                                
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Audit::find()->joinWith('user')
                              ->innerJoin('[user]'.' seller','[user].UserId = seller.UserId')
                              ->innerJoin('[user]'.' dsm', 'seller.ParentId = dsm.UserId')
                              ->innerJoin('[user]'.' rsm', 'dsm.ParentId=rsm.UserId')
                              ;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }




        // grid filtering conditions
        $query->andFilterWhere([
            'CampaignId' => ($this->CampaignId) ? $this->CampaignId : Campaign::getActualCampaign()->CampaignId,
            'AuditId' => $this->AuditId,
            'TypeAuditId' => $this->TypeAuditId,
            'seller.UserId' => $this->UserId,
            'dsm.UserId' => $this->DsmId,
            'rsm.UserId' => $this->RsmId,
            'ClientId' => $this->ClientId,
        ]);

        $query->andFilterWhere(['like', 'Description', $this->Description]);

        if (isset($this->dateFrom) && isset($this->dateTo)) {
            $query->andFilterWhere(['between', 'Date', $this->dateFrom, $this->dateTo]);
        }
        $query->orderBy('AuditId DESC');



        return $dataProvider;
    }
    
    public function export() 
    {
            $query = Audit::find()->select(['audit.AuditId',
                                            'audit.UserId',    
                                            'audit.TypeAuditId',    
                                            'audit.ClientId',    
                                            'type_audit.Name AS TypeAudit',
                                            'user.Fullname AS User',
                                            'client.Description AS Client',
                                            'Date'
                                            ])
                              ->joinWith('user')
                              ->joinWith('typeAudit')
                              ->joinWith('client')
                              ->innerJoin('[user]'.' seller','[user].UserId = seller.UserId')
                              ->innerJoin('[user]'.' dsm', 'seller.ParentId = dsm.UserId')
                              ->innerJoin('[user]'.' rsm', 'dsm.ParentId=rsm.UserId')
                              ;    
            
            // grid filtering conditions
        $query->andFilterWhere([
            'CampaignId' => ($this->CampaignId) ? $this->CampaignId : Campaign::getActualCampaign()->CampaignId,
            'AuditId' => $this->AuditId,
            'TypeAuditId' => $this->TypeAuditId,
            'seller.UserId' => $this->UserId,
            'dsm.UserId' => $this->DsmId,
            'rsm.RsmId' => $this->RsmId,
            'ClientId' => $this->ClientId,
        ]);

        $query->andFilterWhere(['like', 'Description', $this->Description]);
        
        if (isset($this->dateFrom) && isset($this->dateTo)) {
            $query->andFilterWhere(['between', 'Date', $this->dateFrom, $this->dateTo]);
        }
        $query->orderBy('AuditId DESC');
        
        return $query->asArray()->all();
    }

}
