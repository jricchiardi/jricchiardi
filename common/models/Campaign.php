<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "campaign".
 *
 * @property integer $CampaignId
 * @property string $Name
 * @property integer $IsFuture
 * @property integer $IsActual
 * @property string $PlanDateFrom
 * @property string $PlanDateTo
 * @property string $PlanSettingDateFrom
 * @property string $PlanSettingDateTo
 * @property string $DateBeginCampaign
 * @property integer $IsActive
 *
 * @property Forecast[] $forecasts
 * @property ClientProduct[] $clientProducts
 * @property Plan[] $plans
 * @property ClientProduct[] $clientProducts0
 * @property Sale[] $sales
 */
class Campaign extends \yii\db\ActiveRecord {

    const SCENARIO_REGISTER = 'create';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'campaign';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['DateBeginCampaign', 'PlanDateFrom', 'PlanDateTo', 'PlanSettingDateFrom', 'PlanSettingDateTo'], 'required'],
            [['Name'], 'required'],
            [['Name'], 'string'],
            [['IsFuture', 'IsActual', 'IsActive'], 'integer'],
            [['PlanDateFrom', 'PlanDateTo', 'PlanSettingDateFrom', 'PlanSettingDateTo'], 'safe'],
            ['Name', 'validateFuture', 'on' => self::SCENARIO_REGISTER],
            ['PlanDateFrom', 'validatePlanDate'],
            ['PlanSettingDateFrom', 'validatePlanSettingDate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'CampaignId' => Yii::t('app', 'Campaign ID'),
            'Name' => Yii::t('app', 'Name'),
            'IsFuture' => Yii::t('app', 'Is Future'),
            'IsActual' => Yii::t('app', 'Is Actual'),
            'PlanDateFrom' => Yii::t('app', 'Plan Date From'),
            'PlanDateTo' => Yii::t('app', 'Plan Date To'),
            'PlanSettingDateFrom' => Yii::t('app', 'Plan Setting Date From'),
            'PlanSettingDateTo' => Yii::t('app', 'Plan Setting Date To'),
            'IsActive' => Yii::t('app', 'Is Active'),
            'DateBeginCampaign' => Yii::t('app', 'DateBeginCampaign'),
        ];
    }

    public static function getFutureCampaign() {
        return Campaign::findOne(['IsFuture' => 1]);
    }

    public static function getActualCampaign() {
        return Campaign::findOne(['IsActual' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForecasts() {
        return $this->hasMany(Forecast::className(), ['CampaignId' => 'CampaignId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientProducts() {
        return $this->hasMany(ClientProduct::className(), ['ClientProductId' => 'ClientProductId'])->viaTable('forecast', ['CampaignId' => 'CampaignId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlans() {
        return $this->hasMany(Plan::className(), ['CampaignId' => 'CampaignId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientProducts0() {
        return $this->hasMany(ClientProduct::className(), ['ClientProductId' => 'ClientProductId'])->viaTable('plan', ['CampaignId' => 'CampaignId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales() {
        return $this->hasMany(Sale::className(), ['CampaignId' => 'CampaignId']);
    }

    public function getAll() {
        $campaigns = Campaign::find()->orderBy('Name ASC')->asArray()->all();

        return $campaigns;
    }
	
	public function getFive() {
        $campaigns = Campaign::find()->where(['in', 'CampaignId', [9,10,11,12,13,14]])->orderBy('Name ASC')->asArray()->all();

        return $campaigns;
    }

    public function validateFuture() {
        $campaignFuture = Campaign::findOne(['IsFuture' => true]);

        if ($campaignFuture) {
            $this->addError('Name', 'Ya existe un año forecast futuro configurado en el sistema');
        }
    }

    public function validatePlanDate() {

        if ($this->PlanDateFrom > $this->DateBeginCampaign ) {
            $this->addError('PlanDateFrom', 'No pueden existir fechas posteriores a la de inicio de una nueva campaña');
        }

        if($this->PlanDateTo > $this->DateBeginCampaign)
        {
            $this->addError('PlanDateTo', 'No pueden existir fechas posteriores a la de inicio de una nueva campaña');
        }
        if ($this->PlanDateFrom > $this->PlanDateTo) {
            $this->addError('PlanDateFrom', 'La fecha desde debe ser menor a la fecha hasta');
        }
    }

    public function validatePlanSettingDate() {
        if ($this->PlanSettingDateFrom > $this->DateBeginCampaign ) {
            $this->addError('PlanSettingDateFrom', 'No pueden existir fechas posteriores a la de inicio de una nueva campaña');
        }

        if($this->PlanSettingDateTo > $this->DateBeginCampaign)
        {
          $this->addError('PlanSettingDateTo', 'No pueden existir fechas posteriores a la de inicio de una nueva campaña');   
        }
        
        if ($this->PlanSettingDateFrom > $this->PlanSettingDateTo) {
            $this->addError('PlanSettingDateFrom', 'La fecha desde debe ser menor a la fecha hasta');
        }

        if (($this->PlanDateFrom <= $this->PlanSettingDateFrom) && ($this->PlanDateTo >= $this->PlanSettingDateFrom)) {
            $this->addError('PlanSettingDateFrom', 'La fecha desde no debe superponerse con la fecha del plan');
        }

        if (($this->PlanDateFrom <= $this->PlanSettingDateTo) && ($this->PlanDateTo >= $this->PlanSettingDateTo)) {
            $this->addError('PlanSettingDateTo', 'La fecha desde no debe superponerse con la fecha del plan');
        }
    }

    // Apply all products to all the clients i am in level 4 of duolingo
    public function applyNewConfig() {
        // SET THE NEW CAMPAIGN IN FUTURE  
        Campaign::updateAll(['IsFuture' => false]);
        $this->IsFuture = 1;
        $this->IsActual = 0;
        $this->save();

        /* COPY PRODUCTS TO PLAN AND FORECAST */
        $connection = \Yii::$app->db;
        $connection->createCommand("EXEC CreateConfigCampaign")->execute();
        $connection->close();
    }

    public function isSettingActive() {
        $value = true;
        $today = new \DateTime("now");
        $from = new \DateTime($this->PlanSettingDateFrom);
        $to = new \DateTime($this->PlanSettingDateTo);

        if ($from <= $today && $to >= $today)
            $value = false;

        return $value;
    }

}
