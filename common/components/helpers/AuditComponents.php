<?php

namespace common\components\helpers;

use Yii;
use yii\base\Component;
use common\models\Audit;
use common\models\TypeAudit;

class AuditComponents extends Component implements IAudit {

    public function getAudits($options = NULL) 
    {
        $searchModel = new AuditSearch();
        $dataProvider = $searchModel->search($options);
        return $dataProvider;
    }
    
    public function createAudit($options = NULL) {
        
        $result = false;
        $audit = new Audit();
        if(isset($options['ClientId']))
        {
          $audit->ClientId = $options['ClientId'];
        }
        $audit->UserId = $options['UserId'];
        $audit->Date = date('Y-m-d H:i:s');
        $audit->TypeAuditId = $options['TypeAuditId'];
        $audit->CampaignId =  \common\models\Campaign::getActualCampaign()->CampaignId;
        if(isset($options['Description']))
        {
            $audit->Description = $options['Description'];
        }
        
        if($audit->validate() && $audit->save() )
        {
          $result = true;
        }   
           
        return $result;            
    }

}
