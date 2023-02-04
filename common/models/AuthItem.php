<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property User[] $users
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property Notification[] $notifications
 */
class AuthItem extends ActiveRecord
{
    const ROLE_ADMIN = 'admin';
	const ROLE_SIS_ADMIN = 'Admin SIS';
    const ROLE_DIRECTOR_COMERCIAL = 'Director Comercial';
    const ROLE_RSM = 'RSM';
    const ROLE_DSM = 'DSM';
    const ROLE_SELLER = 'SELLER';
    const ROLE_PM = 'PM';
    const ROLE_SIS_VIEWER = 'SIS Viewer';   

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['name', 'description', 'rule_name', 'data'], 'string'],
            [['type', 'created_at', 'updated_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['UserId' => 'user_id'])->viaTable('auth_assignment', ['item_name' => 'name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['ToProfileId' => 'name']);
    }
}
