<?php
use yii\helpers\Html;


$this->title = Yii::t('app','Performances');

$data = array(  
    
    array('Nombre' => 'CORN'),
    array('Nombre' => 'SORGHUM'),
    array('Nombre' => 'WEED MANAGEMENT'),
    array('Nombre' => 'AG MISC'),
    array('Nombre' => 'INSECT MANAGEMENT'),
    array('Nombre' => 'DISEASE MANAGEMENT'),
    array('Nombre' => 'SORGHUM'),
    array('Nombre' => 'WEED MANAGEMENT'),
    array('Nombre' => 'DISEASE MANAGEMENT'),

);




// define validations

$nameValidation = new \Kendo\Data\DataSourceSchemaModelFieldValidation();
$nameValidation->required(true);


// define the columns
$nameField = new \Kendo\Data\DataSourceSchemaModelField('Nombre');
$nameField->type('string')
          ->validation($nameValidation);

$nameColumn = new \Kendo\UI\GridColumn();
$nameColumn->title('Nombre');
$nameColumn->field('Nombre');


$command = new \Kendo\UI\GridColumn();
$command->addCommandItem(['name'=>'destroy','text'=>Yii::t('app','Delete')])      
        ->title('&nbsp;')         
        ->width(110);


// ****************************** DEFINE DataSourceSchemaModel *******************************
$schemaPerformance= new \Kendo\Data\DataSourceSchemaModel();
$schemaPerformance->addField($nameField);



$schema = new \Kendo\Data\DataSourceSchema();
$schema->data('data')
        ->model($schemaPerformance)
        ->total('total');

$dataSource = new \Kendo\Data\DataSource();
$dataSource->schema($schemaPerformance);
$dataSource->data($data);
$dataSource->pageSize(10);

// define toolbars 
$createCommand = new \Kendo\UI\GridToolbarItem('create');
$saveCommand = new \Kendo\UI\GridToolbarItem('save');
$cancelCommand = new \Kendo\UI\GridToolbarItem('cancel');



// define grid and bindings
$grid = new \Kendo\UI\Grid('grid');

$gridFilterable = new \Kendo\UI\GridFilterable();


$grid->addColumn($nameColumn ,$command)
     ->dataSource($dataSource)
     ->addToolbarItem(
                       $createCommand->text(Yii::t('app','Add Performance')),
                       $saveCommand->text(Yii::t('app','Save Changes')), 
                       $cancelCommand->text(Yii::t('app','Cancel Changes'))
                     )          
     ->navigatable(true)
     ->scrollable(false)
     ->editable(true)        
     ->filterable($gridFilterable)
     ->sortable(true)
     ->navigatable(true)
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

