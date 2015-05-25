<?php

Yii::import('application.models.Dto.QueryOption');
	class UserController extends Controller
	{
		public function actionIndex()
		{
			$this->render('index');
		}

		public function actionTestPrint(){
			$user = User::model()->find('password=:email',array(':email'=>'123456'));
			return AjaxHelper::jsonSuccess($user,'Success');
		}

		public function checkLogin($email, $password)
		{
			// $user = Yii::app()->db->createCommand()
			// ->select('id, facebook_id,first_name,last_name,lat,lng,birthday,gender,email')
			// ->from('user')
			// ->where('email=:email and password=:password',array(':email'=>$email,':password'=>$password))
			// ->queryAll();
			$user = User::model()->find('email=:email and password=:password',array(':email'=>$email,':password'=>$password));
			if($user){
				$access_token = StringHelper::generateRandomString(Yii::app()->params['ACCESS_TOKEN_LENGTH']);
				$user->access_token = $access_token;
				if($user->save()){
					HttpResponse::responseOk();
					$user = Yii::app()->db->createCommand()
					->select('id, facebook_id,first_name,last_name,lat,lng,birthday,gender,email,access_token, avatar_url')
					->from('user')
					->where('access_token=:token',array(':token'=>$access_token))
					->queryRow();
					return AjaxHelper::jsonSuccess($user,'Login sucees');
				}else{
					HttpResponse::responseInternalServerError();
					return AjaxHelper::jsonError("Have error on login");
				}
			}else{
				HttpResponse::responseAuthenticationDataIncorrect();
				return AjaxHelper::jsonError('Login failed');
			}
		}
		public function checkAuth($token){
			$user = Yii::app()->db->createCommand()
			->select('*')
			->from('user')
			->where('access_token=:token',array(':token'=>$token))
			->queryAll();
			return $user;
		}

		public function actionGetListUser(){
			$listIdiom = Yii::app()->db->createCommand()
			->select('*')
			->from('user')
			->queryAll();
			if($listIdiom){
				return AjaxHelper::jsonSuccess($listIdiom, 'Lists success');
			}else{
				return AjaxHelper::jsonError('No lists idiom found');
			}
		}

		public function actionLogin(){
			if(!isset($_POST['email']) || !Validator::validateEmail($_POST['email'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('Email is not empty');
			}
			if(!isset($_POST['password'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('Password is not empty');
			}
			$this->checkLogin($_POST['email'], $_POST['password']);

		}

		public function actionLoginWithFacebook(){
			if(!isset($_POST['access_token'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('access_token is not empty');
			}
		}

		public function actionLogout(){
			if(!isset($_POST['access_token'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('access_token is not empty');
			}
			$user = $this->checkAuth($_POST['access_token']);
			if($user){

				$user->access_token = "";
				if($user->update()){
					HttpResponse::responseOk();
					return AjaxHelper::jsonSuccess("Logout success");
				}else{
					HttpResponse::responseInternalServerError();
					return AjaxHelper::jsonError("Have error in logout");
				}

			}else{
				HttpResponse::responseAuthenticationDataIncorrect();
				return AjaxHelper::jsonError('access_token is not of any user');
			}

		}

		public function actionGetProfile(){
			if(!isset($_POST['access_token'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('access_token is not empty');
			}
			$user = $this->checkAuth($_POST['access_token']);
			if($user){
					HttpResponse::responseOk();
					return AjaxHelper::jsonSuccess($user, "User profile");
			}else{
				HttpResponse::responseAuthenticationDataIncorrect();
				return AjaxHelper::jsonError('access_token is not of any user');
			}

		}

		public function actionRegister(){
			$user = new User;
			if(isset($_POST['id'])){
				$address = User::model()->findByAttributes(array('id'=>$_POST['id']));
				if($address){
					HttpResponse::responseConflict();
					return AjaxHelper::jsonError('User ID = '. $_POST['id'] .' is existed');
				}
				$user->id =$_POST['id'];
				$user->email = $user->id ."@vinhdo.me";
				$user->password = "123456";
				$user->avatar_url = $_POST['avatar_url'];
			}else{
				if(!isset($_POST['email']) || !Validator::validateEmail($_POST['email'])){
					return AjaxHelper::jsonError('Email is empty');
				}
				if(!isset($_POST['password']))
					return AjaxHelper::jsonError('password is empty');
				if(strlen($_POST['password']) < 6)
					return AjaxHelper::jsonError('password must > 5 character');
				if(!isset($_POST['first_name']))
					return AjaxHelper::jsonError('first_name is empty');
				if(!isset($_POST['last_name']))
					return AjaxHelper::jsonError('last_name is empty');
				if(!isset($_POST['first_name']))
					return AjaxHelper::jsonError('first_name is empty');
				if(!isset($_POST['birthday']))
					return AjaxHelper::jsonError('birthday is empty');
				if(!isset($_POST['gender']))
					return AjaxHelper::jsonError('gender is empty');
				$user->birthday = $_POST['birthday'];
				$user->email = $_POST['email'];
				$user->password = $_POST['password'];
			}
			$user->first_name = $_POST['first_name'];
			$user->last_name = (isset($_POST['last_name'])) ? $_POST['last_name'] : '';
			$user->gender = (isset($_POST['gender'])) ? $_POST['gender'] : 'male';
			$user->facebook_id = (isset($_POST['facebook_id'])) ? $_POST['facebook_id'] : '';
			$user->address = (isset($_POST['address'])) ? $_POST['address'] : '';
			$user->lat = (isset($_POST['lat'])) ? $_POST['lat'] : 0;
			$user->lng = (isset($_POST['lng'])) ? $_POST['lng'] : 0;

			/**
			 * Avatar
			 */
			$savePath = Yii::app()->params['ASSETS_FOLDER'];
			if(isset($_FILES['avatar'])){
				           $allowedExts  =  array("gif", "jpeg", "jpg", "png");
                           $temp    =  explode(".", $_FILES["avatar"]["name"]);
                           $extension   =  end($temp);

                           if ((($_FILES["avatar"]["type"] == "image/gif")
                           || ($_FILES["avatar"]["type"] == "image/jpeg")
                           || ($_FILES["avatar"]["type"] == "image/jpg")
                           || ($_FILES["avatar"]["type"] == "image/pjpeg")
                           || ($_FILES["avatar"]["type"] == "image/x-png")
                           || ($_FILES["avatar"]["type"] == "image/png")
                           || ($_FILES["avatar"]["type"] == "application/octet-stream"))
                           && in_array($extension, $allowedExts)) {
                           	if($_FILES['avatar']['error'] > 0){
                           		HttpResponse::responseForbidden();
                           		return AjaxHelper::jsonError('have a error in file avatar upload');
                           	}

							$image_name = StringHelper::generateRandomString(15).'.'.$extension;
                           	move_uploaded_file($_FILES["avatar"]["tmp_name"],$savePath.'/'.$image_name);
                           	$user->avatar = $image_name;
														$user->avatar_url = $image_name;
                           }
			}
			$access_token = StringHelper::generateRandomString(Yii::app()->params['ACCESS_TOKEN_LENGTH']);
			$user->access_token = $access_token;
			if(!isset($_POST['id'])){
				$user->id = StringHelper::generateRandomOrderKey(Yii::app()->params['ID_LENGTH']);
			}
			$user->date_create = gmmktime();
			$user->date_update = gmmktime();
			if($user->save()){
				HttpResponse::responseOk();
				if(isset($_POST['id'])){
					return AjaxHelper::jsonSuccess($user->id,'Register sucees');
				}else{
					$user = Yii::app()->db->createCommand()
					->select('id, facebook_id,first_name,last_name,lat,lng,birthday,gender,email,access_token,avatar, avatar_url')
					->from('user')
					->where('access_token=:token',array(':token'=>$access_token))
					->queryRow();
					return AjaxHelper::jsonSuccess($user,'Register sucees');
				}
				}else{
					HttpResponse::responseInternalServerError();
					return AjaxHelper::jsonError("Have error on register");
				}

		}

		public function actionEditProfile(){
			if(!isset($_POST['access_token'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('access_token is empty');
			}
			$user = $this->checkAuth($_POST['access_token']);
			if(!$user){
				HttpResponse::responseAuthenticationDataIncorrect();
				return AjaxHelper::jsonError('Access_token is not correct');
			}

			/**
			 * Avatar
			 */
			$savePath = Yii::app()->params['ASSETS_FOLDER'];
			if(isset($_FILES['avatar'])){
				           $allowedExts  =  array("gif", "jpeg", "jpg", "png");
                           $temp    =  explode(".", $_FILES["avatar"]["name"]);
                           $extension   =  end($temp);

                           if ((($_FILES["avatar"]["type"] == "image/gif")
                           || ($_FILES["avatar"]["type"] == "image/jpeg")
                           || ($_FILES["avatar"]["type"] == "image/jpg")
                           || ($_FILES["avatar"]["type"] == "image/pjpeg")
                           || ($_FILES["avatar"]["type"] == "image/x-png")
                           || ($_FILES["avatar"]["type"] == "image/png")
                           || ($_FILES["avatar"]["type"] == "application/octet-stream"))
                           && in_array($extension, $allowedExts)) {
                           	if($_FILES['avatar']['error'] > 0){
                           		HttpResponse::responseForbidden();
                           		return AjaxHelper::jsonError('have a error in file avatar upload');
                           	}

							$image_name = StringHelper::generateRandomString(15).'.'.$extension;
                           	move_uploaded_file($_FILES["avatar"]["tmp_name"],$savePath.'/'.$image_name);
                           	$user->avatar = $image_name;
														$user->avatar_url = $image_name;
                           }
			}

			if($user->update()){
					HttpResponse::responseOk();
					return AjaxHelper::jsonSuccess("Update Success");
				}else{
					HttpResponse::responseInternalServerError();
					return AjaxHelper::jsonError("Have error on update profile");
				}
		}

		public function actionForgotPassword(){
			if(!isset($_POST['email']) || !Validator::validateEmail($_POST['email'])){
				return AjaxHelper::jsonError('Email chống');
			}
		}

	}
