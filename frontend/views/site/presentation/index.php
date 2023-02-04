<?php
use yii\helpers\Html;


$this->title = Yii::t('app','Presentation');

//•	Trade asociado
//•	Nombre
//•	GMID
//•	País (Se creará automáticamente si hay uno nuevo)
//•	Unidad de medida
//•	Precio  / producto
//•	Margen / producto (idem tabla Pecio producto pero con el margen del producto)

$data = array(  

    array('Trade'=>'116524', 
          'Nombre'=>'PANZERGOLD BTLHPE20L ARG',
          'GMID' => 'CORN',
          'Pais'=>'Argentina',
          'UnidadDeMedida'=>'BAG',
          'PrecioProducto' =>'12.65',
          'MargenProducto' =>'10',
        ),
       array('Trade'=>'116524', 
          'Nombre'=>'PANZERGOLD BTLHPE20L ARG',
          'GMID' => 'CORN',
          'Pais'=>'Argentina',
          'UnidadDeMedida'=>'BAG',
          'PrecioProducto' =>'12.65',
          'MargenProducto' =>'10',
        ),
        array('Trade'=>'116524', 
          'Nombre'=>'PANZERGOLD BTLHPE20L ARG',
          'GMID' => 'CORN',
          'Pais'=>'Argentina',
          'UnidadDeMedida'=>'BAG',
          'PrecioProducto' =>'12.65',
          'MargenProducto' =>'10',
        ),
        array('Trade'=>'116524', 
          'Nombre'=>'PANZERGOLD BTLHPE20L ARG',
          'GMID' => 'CORN',
          'Pais'=>'Argentina',
          'UnidadDeMedida'=>'BAG',
          'PrecioProducto' =>'12.65',
          'MargenProducto' =>'10',
        ),
        array('Trade'=>'116524', 
          'Nombre'=>'PANZERGOLD BTLHPE20L ARG',
          'GMID' => 'CORN',
          'Pais'=>'Argentina',
          'UnidadDeMedida'=>'BAG',
          'PrecioProducto' =>'12.65',
          'MargenProducto' =>'10',
        ),
        array('Trade'=>'116524', 
          'Nombre'=>'PANZERGOLD BTLHPE20L ARG',
          'GMID' => 'CORN',
          'Pais'=>'Argentina',
          'UnidadDeMedida'=>'BAG',
          'PrecioProducto' =>'12.65',
          'MargenProducto' =>'10',
        ),
        array('Trade'=>'116524', 
          'Nombre'=>'PANZERGOLD BTLHPE20L ARG',
          'GMID' => 'CORN',
          'Pais'=>'Argentina',
          'UnidadDeMedida'=>'BAG',
          'PrecioProducto' =>'12.65',
          'MargenProducto' =>'10',
        ),
        array('Trade'=>'116524', 
          'Nombre'=>'PANZERGOLD BTLHPE20L ARG',
          'GMID' => 'CORN',
          'Pais'=>'Argentina',
          'UnidadDeMedida'=>'BAG',
          'PrecioProducto' =>'12.65',
          'MargenProducto' =>'10',
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
$tradeField = new \Kendo\Data\DataSourceSchemaModelField('Trade');
$tradeField->type('string')
          ->validation($requiredValidationString);

$nameField = new \Kendo\Data\DataSourceSchemaModelField('Nombre');
$nameField->type('string')
          ->validation($requiredValidationString);

$GMIDField = new \Kendo\Data\DataSourceSchemaModelField('GMID');
$GMIDField->type('number')
          ->validation($requiredValidationNumber);

$countryField = new \Kendo\Data\DataSourceSchemaModelField('Pais');
$countryField->type('string')
          ->validation($requiredValidationString);

$unitField = new \Kendo\Data\DataSourceSchemaModelField('UnidadDeMedida');
$unitField->type('string')
          ->validation($requiredValidationString);

$priceField = new \Kendo\Data\DataSourceSchemaModelField('PrecioProducto');
$priceField->type('string')
          ->validation($requiredValidationString);

$margenField = new \Kendo\Data\DataSourceSchemaModelField('MargenProducto');
$margenField->type('string')
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


$grid->addColumn($GMIDField,$tradeField,$nameField,$countryField,$unitField,$priceField,$margenField,$command)
     ->dataSource($dataSource)
     ->addToolbarItem(
                       $createCommand->text(Yii::t('app','Add Presentation')),
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

