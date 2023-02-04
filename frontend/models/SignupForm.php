<?php
namespace frontend\models;

use common\models\User;
use common\models\AuthAssignment;
use yii\base\Model;


/**
 * Signup form
 */
class SignupForm extends Model
{
    public $Username;
    public $Fullname;
    public $Email;
    public $Password;
    public $Rol;

    
    
    public function attributeLabels()
    {
        return [
            'Fullname' => 'Nombre',
            'Username' => 'Usuario',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['Username', 'filter', 'filter' => 'trim'],
            ['Username', 'required'],
            ['Username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['Username', 'string', 'min' => 2, 'max' => 255],            
            ['Fullname', 'string', 'min' => 2, 'max' => 255],            
            ['Email', 'filter', 'filter' => 'trim'],
            ['Email', 'required'],
            ['Email', 'email'],
            ['Email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],            
            ['Password', 'required'],
            ['Password', 'string', 'min' => 6],
            ['Rol', 'safe'],           

        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup($rol)
    {
        if ($this->validate()) 
        {
            $user = new User();
            $user->Username = $this->Username;
            $user->Email = $this->Email;
            $user->Fullname = $this->Fullname;            
            $user->setPassword($this->Password);
            $user->generateAuthKey();
            $user->save();            
            $authAssignment = new AuthAssignment();
            $authAssignment->user_id = $user->UserId;
            $authAssignment->item_name = $this->Rol;            
            $authAssignment->save();
            
            return $user;
        }
        return null;
    }
    
}
