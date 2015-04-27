<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class AdminIdentity extends CUserIdentity
{
	private $_id;

    public function authenticate()
    {
        $record=SystemAdmin::model()->findByAttributes(array('email'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if(strcmp($record->password, md5($this->password)) != 0)
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else if(!$record->active)
        	$this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
        	$access_token = StringHelper::generateRandomString(Yii::app()->params['ACCESS_TOKEN_LENGTH']);
        	$record->access_token = $access_token;
        	$record->save();
        	
            $this->_id=$record->admin_id;
            $this->setState('email', $record->email);
            $this->setState('name', $record->name);
            $this->setState('accessToken', $access_token);
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}