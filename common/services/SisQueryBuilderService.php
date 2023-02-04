<?php

namespace common\services;

use Yii;

class SisQueryBuilderService
{

    public $saleInputOptions = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    public function getMonths(){
        $cleanMonths = [];

        foreach($this->saleInputOptions as $month){
            if(Yii::$app->request->get('month_'.$month)){
                $cleanMonths[] = $month;
            }
        }

        return !empty($cleanMonths) ? $cleanMonths : $this->saleInputOptions;

    }

    public function getParams(){

        $params = [];

        foreach($this->saleInputOptions as $month){
            if(Yii::$app->request->get('month_'.$month)){
                $params['month_'.$month] = 1;
            }
        }

        if(Yii::$app->request->get('lvl')){
            $params['lvl'] = Yii::$app->request->get('lvl');
        }

        if(Yii::$app->request->get('dsm')){
            $params['dsm'] = Yii::$app->request->get('dsm');
        }

        return $params;
    }

    public function getBaseUserUrl(){
        $params = $this->getParams();

        unset($params['lvl']);
        unset($params['dsm']);

        return '/sis?' . http_build_query($params).'&lvl=Tam&dsm=';
    }


}



$saleInputOptions = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];



$params = [];

foreach($saleInputOptions as $month){
    if(Yii::$app->request->get('month_'.$month)){
        $params['month_'.$month] = 1;
    }
}

$baseUserUrl = '/sis?' . http_build_query($params).'&lvl=Tam&dsm=';

$saleInputColumns = getMonths();

$filterProduct = null;

$filterUserLvl = Yii::$app->request->get('lvl') ?? 'Dsm';

$filterUser = null;

if($filterUserLvl == 'Tam'){
    $filterDsm = Yii::$app->request->get('dsm');
    $dsmUser = \common\models\User::findIdentity($filterDsm);
}


$sql = "SELECT ";
switch($filterUserLvl){
    case "Tam":
        $sql .= "u.UserId AS UserId,u.Fullname AS Usuario,";
        break;
    case "Dsm":
    default:
        $sql .= "dsm.UserId AS UserId,dsm.Fullname AS Usuario,";
}
//	-- sale.GmidId,
//	-- g.TradeProductId,
//	-- sale.ClientId,
$sql .=	"SUM(".implode(') + SUM(', $saleInputColumns).") AS SaleInput
FROM InverseSale sale
INNER JOIN client_seller cs ON sale.ClientId = cs.ClientId
INNER JOIN [user] u ON cs.SellerId = u.UserId
INNER JOIN pm_dsm pd ON u.ParentId = pd.DsmId
INNER JOIN [user] dsm ON pd.DsmId = dsm.UserId
INNER JOIN pm_product pp ON pp.GmidId = sale.GmidId
INNER JOIN gmid g on g.GmidId = sale.GmidId";
$sql .= " WHERE 1=1 ";
if(!empty($filterDsm)){
    $sql .= " AND dsm.UserId = ".(int)$filterDsm;
}
$sql .=	" GROUP BY ";
switch($filterUserLvl){
    case "Tam":
        $sql .= "u.UserId,u.Fullname";
        break;
    case "Dsm":
    default:
        $sql .= "dsm.UserId,dsm.Fullname";
}
$sql .=	";";

//	sale.ClientId,
//	sale.GmidId,
//	g.TradeProductId";

$data = Yii::$app->db->createCommand($sql)->queryAll();



// define validations
$requiredValidationString = new \Kendo\Data\DataSourceSchemaModelFieldValidation();
$requiredValidationString->required(true);

$requiredValidationNumber = new \Kendo\Data\DataSourceSchemaModelFieldValidation();
$requiredValidationNumber->required(true)->min(1);
//DataSourceGroupItem()

// define the columns



$dsmTemplate = new \Kendo\Template('<a href="asd.com">#Usuario#</a>');
$userField = new \Kendo\Data\DataSourceSchemaModelField('Usuario');
$userField->field('Usuario');
//$userField->template = $dsmTemplate;
$userField = [
    'field'=>'Usuario',
];

$saleInputField = new \Kendo\Data\DataSourceAggregateItem();
$saleInputField->field('SaleInput');
$saleInputField->aggregate('sum');
//$saleInputField->type('number');
//$saleInputField->addAggregate($dsmField);

//
//$usernameField = new \Kendo\Data\DataSourceSchemaModelField('Username');
//$usernameField->type('string')
//    ->validation($requiredValidationString);
//
//$profileField = new \Kendo\Data\DataSourceSchemaModelField('Profile');
//$profileField->type('string')
//    ->validation($requiredValidationString);


// load array with information
$dataSource = new \Kendo\Data\DataSource();
$dataSource->data($data);
$dataSource->addAggregateItem($saleInputField);
$dataSource->pageSize(15);

// define toolbars


// define grid and bindings
$grid = new \Kendo\UI\Grid('grid');


$gridFilterable = new \Kendo\UI\TreeListColumnFilterable();


$grid->addColumn($userField, $saleInputField)
    ->dataSource($dataSource)
    ->rowTemplateId('myTemplate')
    ->navigatable(true)
    ->scrollable(false)
    ->editable(false)
    ->filterable($gridFilterable)
    ->pageable(true);
