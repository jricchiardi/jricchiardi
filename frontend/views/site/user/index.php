<?php
use yii\helpers\Html;


$this->title = Yii::t('app','Usuarios');


$data = array(  

    array('Nombre'=>'Santiago','Username'=>'santi','Profile'=>'Administrador'),
    array('Nombre'=>'Carolina','Username'=>'clouzao','Profile'=>'DSM'),
    array('Nombre'=>'Juana','Username'=>'jkitroser','Profile'=>'Demand manager'),
    array('Nombre'=>'Camila','Username'=>'cdow','Profile'=>'Gerente Regional'),
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

$usernameField = new \Kendo\Data\DataSourceSchemaModelField('Username');
$usernameField->type('string')
          ->validation($requiredValidationString);

$profileField = new \Kendo\Data\DataSourceSchemaModelField('Profile');
$profileField->type('string')
          ->validation($requiredValidationString);



       

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


$grid->addColumn($nameField,$usernameField,$profileField,$command)
     ->dataSource($dataSource)
     ->addToolbarItem(
                       $createCommand->text(Yii::t('app','Add User')),
                       $saveCommand->text(Yii::t('app','Save Changes')), 
                       $cancelCommand->text(Yii::t('app','Cancel Changes'))
                     )          
     ->navigatable(true)
     ->scrollable(false)
     ->editable('popup')    
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

