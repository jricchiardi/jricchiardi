<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    const STATUS_ACTIVE = 1;
    public $Email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['Email', 'filter', 'filter' => 'trim'],
            ['Email', 'required'],
            ['Email', 'email'],
            ['Email', 'exist',
                'targetClass' => '\common\models\User',                
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    
     public static function findPasswordByEmail($email)
    {
		$user= User::findOne(['Email' => $email, 'IsActive' => self::STATUS_ACTIVE]);
		if($user)
		{
			$pass = rand(400000, 500000);
		
			$user->PasswordHash = (md5($pass));
			$user->save();
			
			return $pass;
		}else
			return false;
		exit;
		
    }
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
      
        /* @var $user User */
        $user = User::findOne([            
            'Email' => $this->Email,
        ]);
     
        if ($user) 
        {
      
            if (!User::isPasswordResetTokenValid($user->PasswordResetToken)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) 
            {
                // return true;
                return \Yii::$app->mailer->compose('PasswordResetToken', ['user' => $user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => 'Dow FORECAST'])
                    ->setTo($this->Email)
                    ->setSubject('Reinicio de Password ')
                    ->send();
      
            }
        }

        return false;
    }
}
