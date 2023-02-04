<?php
use yii\helpers\Html;


$this->title = Yii::t('app','Clientes');


$data = array(  

    array('Nombre'=>'ACOPIOS UNION SA',
          'Código' => '1002419',
          'Pais'=>'PARAGUAY',
          'Tipo'=>'DSM',          
        ),
      array('Nombre'=>'ACOPIOS UNION SA',
          'Código' => '1002419',
          'Pais'=>'PARAGUAY',
          'Tipo'=>'DSM',          
        ),
      array('Nombre'=>'ACOPIOS UNION SA',
          'Código' => '1002419',
          'Pais'=>'PARAGUAY',
          'Tipo'=>'DSM',          
        ),
      array('Nombre'=>'ACOPIOS UNION SA',
          'Código' => '1002419',
          'Pais'=>'PARAGUAY',
          'Tipo'=>'DSM',          
        ),
      array('Nombre'=>'ACOPIOS UNION SA',
          'Código' => '1002419',
          'Pais'=>'PARAGUAY',
          'Tipo'=>'DSM',          
        ),
      array('Nombre'=>'ACOPIOS UNION SA',
          'Código' => '1002419',
          'Pais'=>'PARAGUAY',
          'Tipo'=>'DSM',          
        ),
      array('Nombre'=>'ACOPIOS UNION SA',
          'Código' => '1002419',
          'Pais'=>'PARAGUAY',
          'Tipo'=>'DSM',          
        ),
      array('Nombre'=>'ACOPIOS UNION SA',
          'Código' => '1002419',
          'Pais'=>'PARAGUAY',
          'Tipo'=>'DSM',          
        ),
      array('Nombre'=>'ACOPIOS UNION SA',
          'Código' => '1002419',
          'Pais'=>'PARAGUAY',
          'Tipo'=>'DSM',          
        ),
      array('Nombre'=>'ACOPIOS UNION SA',
          'Código' => '1002419',
          'Pais'=>'PARAGUAY',
          'Tipo'=>'DSM',          
        ),
      array('Nombre'=>'ACOPIOS UNION SA',
          'Código' => '1002419',
          'Pais'=>'PARAGUAY',
          'Tipo'=>'DSM',          
        ),
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

$codeField = new \Kendo\Data\DataSourceSchemaModelField('Código');
$codeField->type('number')
          ->validation($requiredValidationNumber);

$nameField = new \Kendo\Data\DataSourceSchemaModelField('Nombre');
$nameField->type('string')
          ->validation($requiredValidationString);



$countryField = new \Kendo\Data\DataSourceSchemaModelField('Pais');
$countryField->type('string')
          ->validation($requiredValidationString);

$typeField = new \Kendo\Data\DataSourceSchemaModelField('Tipo');
$typeField->type('string')
          ->validation($requiredValidationString);




$command = new \Kendo\UI\GridColumn();
$command->addCommandItem(['name'=>'destroy','text'=>Yii::t('app','Delete')])      
        ->title('&nbsp;')        
        ->width(110);

$command->addCommandItem(['name'=>'products','text'=>Yii::t('app','Products'),'template'=>'<div class="k-button"><span class="k-icon k-i-hbars"></span> Productos</div>'])      
        ->title('&nbsp;')  
        ->width(210);

// define toolbars 
$createCommand = new \Kendo\UI\GridToolbarItem('create');
$saveCommand = new \Kendo\UI\GridToolbarItem('save');
$cancelCommand = new \Kendo\UI\GridToolbarItem('cancel');




// define grid and bindings
$grid = new \Kendo\UI\Grid('grid');


$gridFilterable = new \Kendo\UI\GridFilterable();


$grid->addColumn($codeField,$nameField,$countryField,$typeField,$command)
     ->dataSource($dataSource)
     ->addToolbarItem(
                       $createCommand->text(Yii::t('app','Add Client')),
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

                 