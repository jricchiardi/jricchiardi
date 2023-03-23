<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\db\Expression;

/**
 * User model
 *
 * @property integer $UserId
 * @property string $Username
 * @property string $Fullname
 * @property string $AuthKey
 * @property string $PasswordHash
 * @property string $PasswordResetToken
 * @property string $Email
 * @property string $ParentId
 * @property AuthAssignment $authAssignment 
 * @property string $CreatedAt
 * @property string $UpdatedAt
 * @property boolean $IsActive
 * @property string $Password write-only password
 * 
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 * @property Notification[] $notifications
 * @property User $parent
 * @property User[] $users
 * 
 */
class User extends ActiveRecordCustom implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    public $Temporal = false;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['CreatedAt', 'UpdatedAt'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'UpdatedAt',
                ],
                'value' => new Expression('GETDATE()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [

            ['Username', 'filter', 'filter' => 'trim'],
            ['Username', 'required'],
            ['Username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['Username', 'string', 'min' => 2, 'max' => 255],
            ['Fullname', 'string', 'min' => 2, 'max' => 255],
            ['ParentId', 'integer'],
            ['Email', 'filter', 'filter' => 'trim'],
            ['Email', 'required'],
            ['Email', 'email'],
            ['Email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este email ya existe en el sistema'],
            ['PasswordHash', 'required'],
            ['PasswordResetToken', 'string'],
            ['PasswordHash', 'string', 'min' => 6],
            ['resetPassword', 'boolean'],
            ['IsActive', 'default', 'value' => self::STATUS_ACTIVE],
            ['IsActive', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['IsActive', 'boolean'],
            [['PasswordHash'], 'validatePasswordCustom'],
            ['Temporal', 'boolean'],
            ['Temporal', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'UserId' => Yii::t('app', 'User ID'),
            'Username' => Yii::t('app', 'User'),
            'Fullname' => Yii::t('app', 'Fullname'),
            'AuthKey' => Yii::t('app', 'Auth Key'),
            'PasswordHash' => Yii::t('app', 'Password'),
            'PasswordResetToken' => Yii::t('app', 'Password Reset Token'),
            'Email' => Yii::t('app', 'Email'),
            'CreatedAt' => Yii::t('app', 'Created At'),
            'UpdatedAt' => Yii::t('app', 'Updated At'),
            'ParentId' => Yii::t('app', 'Supervisor'),
            'IsActive' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments() {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'UserId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames() {
        return $this->hasMany(AuthItem::className(), ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'UserId']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['UserId' => $id, 'IsActive' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username, $field = 'Username') {
        return static::findOne([$field => $username, 'IsActive' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'PasswordResetToken' => $token,
                    'IsActive' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->AuthKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        // This line was change to maintain compatibility with current Dow Voucher list
        // Matias Luzardi 2015-01-08
        // return Yii::$app->security->validatePassword($password, $this->PasswordHash);
        return (md5($password) === $this->PasswordHash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        // This line was change to maintain compatibility with current Dow Voucher list
        // Matias Luzardi 2015-01-08
        // $this->PasswordHash = Yii::$app->security->generatePasswordHash($password);
        $this->PasswordHash = md5($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->AuthKey = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->PasswordResetToken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->PasswordResetToken = null;
    }

    public function signup($rol, $id) {
        if ($this->validate()) {
            $this->setPassword($this->PasswordHash);

            $this->generateAuthKey();
            $this->assignIdRol($rol, $id); // asign id in column correspond at rol
            $this->save();
            $authAssignment = new AuthAssignment();
            $authAssignment->user_id = $this->UserId;
            $authAssignment->item_name = $rol;
            $authAssignment->save();

            return $this;
        }
        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignment() {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'UserId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent() {
        return $this->hasMany(User::className(), ['UserId' => 'ParentId']);
    }

    public static function findPasswordByEmail($email) {
        $user = static::findOne(['Email' => $email, 'IsActive' => self::STATUS_ACTIVE]);
        if ($user) {
            $pass = rand(400000, 500000);

            $user->PasswordHash = (md5($pass));
            $user->save();

            return $pass;
        } else
            return false;
        exit;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications() {
        return $this->hasMany(Notification::className(), ['ToUserId' => 'UserId']);
    }

    public function validatePasswordCustom($attr, $params) {
        // if password is 123456 
        if (!$this->Temporal && trim($this->PasswordHash) == '123456') {
            $this->addError('PasswordHash', 'El Password no puede ser "123456"');
        }
    }

}
