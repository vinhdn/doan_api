<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
    public function authenticate()
    {
        $record=User::model()->findByAttributes(array('username'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if(strcmp($record->password, md5($this->password)) != 0)
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->_id=$record->user_id;
            $this->setState('username', $record->username);
            $this->setState('name', $record->name);
            $this->setState('accessToken', '');
            if($record->permission){
            	$this->setState('permission', $record->permission);
            }
            else{
            	$this->setState('permission', '');
            }
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}