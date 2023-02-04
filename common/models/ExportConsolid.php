<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ExportConsolid".
 *
 * @property string $Pais
 * @property integer $RSMId
 * @property string $RSM
 * @property string $Nombre RSM
 * @property integer $DSMId
 * @property string $DSM
 * @property string $Nombre DSM
 * @property integer $SellerId
 * @property string $Vendedor
 * @property string $Nombre Vendedor
 * @property integer $Cliente
 * @property string $Nombre Cliente
 * @property string $Clasificacion
 * @property string $Value Center
 * @property string $Trade Product
 * @property string $Nombre Trade Product
 * @property string $Performance
 * @property string $Nombre Performance
 * @property integer $GMID
 * @property string $Nombre GMID
 * @property integer $MES
 * @property integer $Q
 * @property string $Precio
 * @property integer $Volumen
 * @property string $USD
 * @property string $CampaignId
 */
class ExportConsolid extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ExportConsolid';
    }

    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Pais', 'RSMId', 'DSMId', 'SellerId', 'Nombre Cliente', 'Value Center', 'Trade Product', 'Performance', 'Volumen'], 'required'],
            [['Pais', 'RSM', 'Nombre RSM', 'DSM', 'Nombre DSM', 'Vendedor', 'Nombre Vendedor', 'Nombre Cliente', 'Clasificacion', 'Value Center', 'Trade Product', 'Nombre Trade Product', 'Performance', 'Nombre Performance', 'Nombre GMID'], 'string'],
            [['CampaignId','RSMId', 'DSMId', 'SellerId', 'Cliente', 'MES', 'Q', 'Volumen','GMID'], 'integer'],
            [['Precio', 'USD'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Pais' => Yii::t('app', 'Country'),
            'RSMId' => Yii::t('app', 'Rsmid'),
            'RSM' => Yii::t('app', 'Rsm'),
            'Nombre RSM' => Yii::t('app', 'Rsm Name'),
            'DSMId' => Yii::t('app', 'Dsmid'),
            'DSM' => Yii::t('app', 'Dsm'),
            'Nombre DSM' => Yii::t('app', 'Dsm Name'),
            'SellerId' => Yii::t('app', 'Seller ID'),
            'Vendedor' => Yii::t('app', 'Seller'),
            'Nombre Vendedor' => Yii::t('app', 'Seller Name'),
            'Cliente' => Yii::t('app', 'Client'),
            'Nombre Cliente' => Yii::t('app', 'Client Name'),
            'Clasificacion' => Yii::t('app', 'Clasification'),
            'Value Center' => Yii::t('app', 'Value  Center'),
            'Trade Product' => Yii::t('app', 'Trade  Product'),
            'Nombre Trade Product' => Yii::t('app', 'Trade  Product Name'),
            'Performance' => Yii::t('app', 'Performance'),
            'Nombre Performance' => Yii::t('app', 'Performance Name'),
            'GMID' => Yii::t('app', 'Gmid'),
            'Nombre GMID' => Yii::t('app', 'Gmid Name'),
            'MES' => Yii::t('app', 'Month'),
            'Q' => Yii::t('app', 'Q'),
            'Precio' => Yii::t('app', 'Price'),
            'Volumen' => Yii::t('app', 'Volume'),
            'USD' => Yii::t('app', 'Usd'),
        ];
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = ExportConsolid::find();
        
        $this->load($params);
        
        $query->andFilterWhere([
            'CampaignId' => $this->CampaignId,
            'RSMId' => $this->RSMId,
            'DSMId' => $this->DSMId,
           ]);
        
        $query->andWhere(['OR','Volumen <> 0','USD <> 0']); //(volumen <> 0 or usd <> 0 ) condicion en base de datos
        $query->orderBy('Pais,Nombre DSM,Nombre Vendedor,Nombre Cliente,Value Center,Nombre Trade Product,Nombre Performance,Nombre GMID,MES ASC');

      return $query->asArray()->all();
    }
}
