<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * CampaignProductSearch represents the model behind the search form about `common\models\CampaignProduct`.
 */
class ReportSearch extends \yii\base\Model {

    public $DsmId = null;
    public $RsmId = null;
    public $CampaignId = null;
    public $CampaignFutureId = null;


    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['CampaignId', 'DsmId', 'RsmId'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'CampaignId' => Yii::t('app', 'Campaign'),
            'DsmId' => Yii::t('app', 'DSM'),
            'RsmId' => Yii::t('app', 'RSM'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function reportResume($params) {
        $this->load($params);        
        
        $resume = ReportComparativeBySellers::find()
                ->andFilterWhere(['RsmId' => $this->RsmId,
                                  'DsmId' => $this->DsmId,
                                  'CampaignId' => $this->CampaignId])               
                ->asArray()
                ->orderBy('SellerName ASC')
                ->all();
         
   
        return ['resume' =>$resume];
    }

}
