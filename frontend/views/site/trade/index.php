<?php
use yii\helpers\Html;


$this->title = Yii::t('app','Products');

$data = array(  

    array('GMID'=>'269553', 'Nombre'=>'PANZERGOLD BTLHPE20L ARG','Performance' => 'CORN','Precio'=>'12.66','Margen'=>'12'),
    array('GMID'=>'269553','Nombre'=>'PANZERGOLD BTLHPE20L ARG','Performance' => 'SORGHUM','Precio'=>'12.66','Margen'=>'12'),
    array('GMID'=>'269553','Nombre'=>'PANZERGOLD BTLHPE20L BOL','Performance' => 'WEED MANAGEMENT','Precio'=>'12.66','Margen'=>'12'),
    array('GMID'=>'269553','Nombre'=>'PANZERGOLD BTLHPE20L ARG','Performance' => 'AG MISC','Precio'=>'12.66','Margen'=>'12'),
    array('GMID'=>'269553','Nombre'=>'PANZERGOLD BTLHPE20L ARG','Performance' => 'INSECT MANAGEMENT','Precio'=>'12.66','Margen'=>'12'),
    array('GMID'=>'269553','Nombre'=>'PANZERGOLD BTLHPE20L ARG','Performance' => 'DISEASE MANAGEMENT','Precio'=>'12.66','Margen'=>'12'),
    array('GMID'=>'269553','Nombre'=>'PANZERGOLD BTLHPE20L ARG','Performance' => 'SORGHUM','Precio'=>'12.66','Margen'=>'12'),
    array('GMID'=>'269553','Nombre'=>'PANZERGOLD BTLHPE20L ARG','Performance' => 'WEED MANAGEMENT','Precio'=>'12.66','Margen'=>'12'),
    array('GMID'=>'269553','Nombre'=>'PANZERGOLD BTLHPE20L ARG','Performance' => 'DISEASE MANAGEMENT','Precio'=>'12.66','Margen'=>'12'),
);



// load array with information
$dataSource = new \Kendo\Data\DataSource();
$dataSource->data($data);
$dataSource->pageSize(10);
// define validations
$requiredValidationString = new \Kendo\Data\DataSourceSchemaModelFieldValidation();
$requiredValidationString->required(true);

$requiredValidationNumber = new \Kendo\Data\DataSourceSchemaModelFieldValidation();
$requiredValidationNumber->required(true)->min(1);

// define the columns
$performanceField = new \Kendo\Data\DataSourceSchemaModelField('Performance');
$performanceField->type('string')
          ->validation($requiredValidationString);

$nameField = new \Kendo\Data\DataSourceSchemaModelField('Nombre');
$nameField->type('string')
          ->validation($requiredValidationString);

$GMIDField = new \Kendo\Data\DataSourceSchemaModelField('GMID');
$GMIDField->type('number')
          ->validation($requiredValidationNumber);

$priceField = new \Kendo\Data\DataSourceSchemaModelField('Precio');
$priceField->type('number')
          ->validation($requiredValidationNumber);

$margeField = new \Kendo\Data\DataSourceSchemaModelField('Margen');
$margeField->type('number')
          ->validation($requiredValidationNumber);

$command = new \Kendo\UI\GridColumn();
$command->addCommandItem(['name'=>'destroy','text'=>Yii::t('app','Delete')])      
        ->title('&nbsp;')         
        ->width(110);

// define toolbars 

$createCommand = new \Kendo\UI\GridToolbarItem('create');
$saveCommand = new \Kendo\UI\GridToolbarItem('save');
$cancelCommand = new \Kendo\UI\GridToolbarItem('cancel');



// define grid and bindings
$grid = new \Kendo\UI\Grid('grid');


$gridFilterable = new \Kendo\UI\GridFilterable();


$grid->addColumn($GMIDField,$nameField,$performanceField,$priceField,$margeField,$command)
     ->dataSource($dataSource)
     ->addToolbarItem(
                       $createCommand->text(Yii::t('app','Add Product')),
                       $saveCommand->text(Yii::t('app','Save Changes')), 
                       $cancelCommand->text(Yii::t('app','Cancel Changes'))
                     )          
     ->navigatable(true)
     ->scrollable(false)
     ->editable(true)   
     ->filterable($gridFilterable)
     ->pageable(true);





?>




    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>

    </div>

       
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <?=$grid->render(); ?>
                
            </div>
        </div>
    </div>

