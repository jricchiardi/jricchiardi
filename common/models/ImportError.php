<?php

namespace common\models;

use yii\db\ActiveRecord;

class ImportError extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'import_error';
    }
}
