<?php
use yii\helpers\Html;


$this->title = Yii::t('app','Campañas');


$data = array(  

    array('Nombre'=>'Campaña 2015','Año'=>2015,'FechaCierre'=>'10/02/2015'),
    array('Nombre'=>'Campaña 2014','Año'=>2014,'FechaCierre'=>'10/02/2014'),
    array('Nombre'=>'Campaña 2013','Año'=>2013,'FechaCierre'=>'10/02/2013'),
    array('Nombre'=>'Campaña 2012','Año'=>2012,'FechaCierre'=>'10/02/2012'),      
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

$nameField = new \Kendo\Data\DataSourceSchemaModelField('Nombre');
$nameField->type('string')
          ->validation($requiredValidationString);


$yearField = new \Kendo\Data\DataSourceSchemaModelField('Año');
$yearField->type('string')
          ->validation($requiredValidationString);



$closeDateField = new \Kendo\Data\DataSourceSchemaModelField('FechaCierre');
$closeDateField->type('date');

$closeDateField = new \Kendo\UI\GridColumn();
$closeDateField->field('FechaCierre')
          ->format('{0:dd/MM/yyyy}')
          ->title('Fecha de cierre');          

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


$grid->addColumn($nameField,$yearField,$closeDateField,$command)
     ->dataSource($dataSource)
     ->addToolbarItem(
                       $createCommand->text(Yii::t('app','Add Campaign')),
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

