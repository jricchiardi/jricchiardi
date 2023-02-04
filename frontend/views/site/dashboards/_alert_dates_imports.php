<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="alert alert-info alert-dismissible" style="font-size: 18px;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        <a href="#"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;

            <?= Yii::t('app', 'Last Date of Update: DAS CyO: {dasCyo} - Dupont CyO: {dupontCyo} - DAS Sales: {dasSale} - Dupont Sales: {dupontSale}', [
                'dasCyo' => Yii::$app->formatter->asDatetime($results['lastDateAutomaticDASCyo'], "php:d-m-Y H:i:s"),
                'dupontCyo' => Yii::$app->formatter->asDatetime($results['lastDateAutomaticDupontCyo'], "php:d-m-Y H:i:s"),
                'dasSale' => Yii::$app->formatter->asDatetime($results['lastDateAutomaticDASSale'], "php:d-m-Y H:i:s"),
                'dupontSale' => Yii::$app->formatter->asDatetime($results['lastDateAutomaticDupontSale'], "php:d-m-Y H:i:s"),
            ]) ?>

        </a>
    </div>
</div>