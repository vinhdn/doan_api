<?php
class EmailHelper{
	public static function sendRecoveryPasswordEmail($user, $newPassword){
		$mail = new YiiMailer();
		$mail->setLayout('mail');
		$mail->setView('recoveryPassword');
		$mail->setData(array('administrator' => $user, 'new_password' => $newPassword));
		
		$mail->setFrom('quocviet.cntt.bk@gmail.com', Yii::app()->params['SITE_NAME']);
		$mail->setTo($user->email);
		$mail->setSubject(Yii::t(Yii::app()->params['TRANSLATE_FILE'],'Instruction to reset password from '.Yii::app()->params['SITE_NAME'].' system'));
		
		if ($mail->send()) {
			return null;
		} else {
			return $mail->getError();
		}
	}
}