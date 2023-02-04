<?php
namespace frontend\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    
    public $repeatpassword;
    
    public $Password;

    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) 
        {
           $this->_user = Yii::$app->user->identity;
        }
        else
            $this->_user = User::findByPasswordResetToken($token);
        
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['Password', 'required'],
            ['Password', 'string', 'min' => 6],     
            ['repeatpassword', 'safe'], 
            ['repeatpassword', 'compare', 'compareAttribute'=>'Password', 'message'=>"El password no coincide con el ingresado anteriormente"],
            [['Password'],'validatePasswordCustom']
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->Password = $this->Password;
        $user->resetPassword = false;
        $user->removePasswordResetToken();

        return $user->save();
    }
    
     public function validatePasswordCustom($attr, $params) 
     {
         // if password is 123456 
        if(trim($this->Password) == '123456')
        {         
            $this->addError('Password','El Password no puede ser "123456"');
        }        
     }
}
