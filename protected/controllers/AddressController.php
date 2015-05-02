<?php

Yii::import('application.models.Dto.QueryOption');
	class AddressController extends Controller
	{
		public function actionIndex()
		{
			$this->render('index');
		}

		public function actionGetInfo(){
			if(!isset($_POST['id'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('id is not empty');
			}
			$address = Yii::app()->db->createCommand()
					->select('*')
					->from('address')
					->where('id=:id',array(':id'=>$_POST['id']))
					->queryRow();

			// Address::model()->find('id=:id',array(':id'=>$_POST['id']));
			if(!$address){
				return AjaxHelper::jsonError('Address ID = '. $_POST['id'] .' not found');
			}
			// return AjaxHelper::jsonSuccess($address);

			$list_post = $this->getListPostOfAddress($address['id']);
			// var_dump($list_post);
			if($list_post){
				$address['posts'] = $list_post;
			}

			if(isset($_POST['access_token'])){
				// HttpResponse::responseBadRequest();
				// return AjaxHelper::jsonError('access_token is not empty');
				$user = $this->checkAuth($_POST['access_token']);
				if($user){
					$condition = 'user_id='.$user['id'].' and address_id='.$address['id'];
					$like = LikeAddress::model()->find($condition);
					if($like)
						$address['is_like'] = true;
					else
						$address['is_like'] = false;
					if($address['owner_id'] == $user['id'])
						$address['is_owner'] = true;
					else
						$address['is_owner'] = false;
				}
			}

			$rates = Yii::app()->db->createCommand()
					->select('count(id) as count_id ,sum(rate) as sum_id')
					->from('rate')
					->where('address_id=:address_id',array(':address_id'=>$address['id']))
					->queryRow();
			if($rates){
				if($rates['count_id'] > 0){
					$address['rate'] = $rates['sum_id'] / $rates['count_id'];
				}
			}else
				$address['rate'] = null;
			$condition = 'id='.$address['category_id'];
			$address['category'] = Yii::app()->db->createCommand()
									->select('*')
									->from('category')
									->where('id=:token',array(':token'=>$address['category_id']))
									->queryRow();
			$address['time_open'] = Yii::app()->db->createCommand()
									->select('weekday, time_open, time_close')
									->from('time_open')
									->where('address_id=:address_id',array(':address_id'=>$address['id']))
									->queryAll();
			return AjaxHelper::jsonSuccess($address,"Address get info success");
		}

		public function actionGetList(){
			$addresses = Yii::app()->db->createCommand()
					->select('*')
					->from('address')
					->where('state=:id',array(':id'=>0))
					->queryAll();
					HttpResponse::responseOk();
					return AjaxHelper::jsonSuccess($addresses,"list Address");
		}

		public function actionCreate(){
			$address = new Address;
			if(isset($_POST['access_token'])){
				$user = $this->checkAuth($_POST['access_token']);
				if($user){
					$address->owner_id = $user["id"];
				}else{
					HttpResponse::responseAuthenticationFailure();
					AjaxHelper::jsonError('Authentication is failure');	
				}
			}else{
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('access_token is empty');
			}

			if(isset($_POST['email']))
				$address->email = $_POST['email'];
			if(isset($_POST['name']))
				$address->name = $_POST['name'];
			else{
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('name is empty');	
			}

			if(isset($_POST['category_id']))
				$address->category_id = $_POST['category_id'];
			else{
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('category_id is empty');	
			}
			$savePath = dirname(__FILE__) . '/assets/images';
			
			// TODO get Avatar
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
                           	
							if (!file_exists($savePath)) {
    							mkdir($savePath, 0777, true);
							}
							$avatar_name = StringHelper::generateRandomString(15).'.'.$extension;
                           	move_uploaded_file($_FILES["avatar"]["tmp_name"],$savePath.'/'.$avatar_name);
                           	$address->avatar = $avatar_name;
                           }
			}
			// TODO get cover
			if(isset($_FILES['cover'])){
				           $allowedExts  =  array("gif", "jpeg", "jpg", "png");
                           $temp    =  explode(".", $_FILES["cover"]["name"]);
                           $extension   =  end($temp);

                           if ((($_FILES["cover"]["type"] == "image/gif")
                           || ($_FILES["cover"]["type"] == "image/jpeg")
                           || ($_FILES["cover"]["type"] == "image/jpg")
                           || ($_FILES["cover"]["type"] == "image/pjpeg")
                           || ($_FILES["cover"]["type"] == "image/x-png")
                           || ($_FILES["cover"]["type"] == "image/png")
                           || ($_FILES["cover"]["type"] == "application/octet-stream"))
                           && in_array($extension, $allowedExts)) {
                           	if($_FILES['cover']['error'] > 0){
                           		HttpResponse::responseForbidden();
                           		return AjaxHelper::jsonError('have a error in file cover upload');
                           	}
       //                     	if (!file_exists('../asset/images')) {
    			// 				mkdir('../asset/images', 0777, true);
							// }
							$cover_name = StringHelper::generateRandomString(15).'.'.$extension;
                           	move_uploaded_file($_FILES["cover"]["tmp_name"],$savePath.'/'.$cover_name);
                           	$address->cover = $cover_name;
                           }
			}


			if(isset($_POST['about']))
				$address->about = $_POST['about'];
			
			if(isset($_POST['lat']))
				$address->lat = $_POST['lat'];

			if(isset($_POST['lng']))
				$address->lng = $_POST['lng'];

			if(isset($_POST['address']))
				$address->address = $_POST['address'];

			if(isset($_POST['city']))
				$address->city = $_POST['city'];
			
			if(isset($_POST['street_number']))
				$address->street_number = $_POST['street_number'];
				
			if(isset($_POST['phone_number']))
				$address->phone_number = $_POST['phone_number'];
			$address->date_update = gmmktime();
			$address->date_create = gmmktime();
			$address->id = StringHelper::generateRandomOrderKey(Yii::app()->params['ID_LENGTH']);
			if($address->save()){
				HttpResponse::responseOk();
				return AjaxHelper::jsonSuccess($address,"create success");
			}else{
				HttpResponse::responseInternalServerError();
				return AjaxHelper::jsonError('Have a error in process Create Address');	
			}

		}

		public function actionUpdate(){
			if(!isset($_POST['id'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('id is empty');
			}
			$condition = "id=".$_POST['id'];
			$address = Address::model()->find($condition);
			if(!$address){
				HttpResponse::responseNotFound();
				return AjaxHelper::jsonError('Address ID = '. $_POST['id'] .' not found');
			}
			if(isset($_POST['access_token'])){
				$user = $this->checkAuth($_POST['access_token']);
				if($user){
					if($address['owner_id'] != $user['id']){
						HttpResponse::responseAuthenticationFailure();
						AjaxHelper::jsonError('User not have permission to update data');		
					}
					
				}else{
					HttpResponse::responseAuthenticationFailure();
					AjaxHelper::jsonError('Authentication is failure');	
				}
			}else{
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('access_token is empty');
			}



			if(isset($_POST['email']))
				$address->email = $_POST['email'];
			if(isset($_POST['name']))
				$address->name = $_POST['name'];

			if(isset($_POST['category_id']))
				$address->category_id = $_POST['category_id'];
			$isChangeAvatar = false;
			$isChangeCover = false;
			$fileOld = array();
			$fileOld['avatar'] = $address->avatar;
			$fileOld['cover'] = $address->cover;
			// TODO get Avatar
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
                           	
							$savePath = dirname(__FILE__) . '/assets/images';
							if (!file_exists($savePath)) {
    							mkdir($savePath, 0777, true);
							}
							$avatar_name = StringHelper::generateRandomString(15).'.'.$extension;
                           	move_uploaded_file($_FILES["avatar"]["tmp_name"],$savePath.'/'.$avatar_name);
                           	$isChangeAvatar = true;
                           	$address->avatar = $avatar_name;
                           }
			}
			// TODO get cover
			if(isset($_FILES['cover'])){
				           $allowedExts  =  array("gif", "jpeg", "jpg", "png");
                           $temp    =  explode(".", $_FILES["cover"]["name"]);
                           $extension   =  end($temp);

                           if ((($_FILES["cover"]["type"] == "image/gif")
                           || ($_FILES["cover"]["type"] == "image/jpeg")
                           || ($_FILES["cover"]["type"] == "image/jpg")
                           || ($_FILES["cover"]["type"] == "image/pjpeg")
                           || ($_FILES["cover"]["type"] == "image/x-png")
                           || ($_FILES["cover"]["type"] == "image/png")
                           || ($_FILES["cover"]["type"] == "application/octet-stream"))
                           && in_array($extension, $allowedExts)) {
                           	if($_FILES['cover']['error'] > 0){
                           		HttpResponse::responseForbidden();
                           		return AjaxHelper::jsonError('have a error in file cover upload');
                           	}
       //                     	if (!file_exists('../asset/images')) {
    			// 				mkdir('../asset/images', 0777, true);
							// }
							$savePath = dirname(__FILE__) . '/assets/images';
							$cover_name = StringHelper::generateRandomString(15).'.'.$extension;
                           	move_uploaded_file($_FILES["cover"]["tmp_name"],$savePath.'/'.$cover_name);
                           	$isChangeCover = true;
                           	$address->cover = $cover_name;
                           }
			}


			if(isset($_POST['about']))
				$address->about = $_POST['about'];
			
			if(isset($_POST['lat']))
				$address->lat = $_POST['lat'];
			else{
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('lat is empty');	
			}

			if(isset($_POST['lng']))
				$address->lng = $_POST['lng'];
			else{
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('lng is empty');	
			}

			if(isset($_POST['address']))
				$address->address = $_POST['address'];
			else{
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('address is empty');	
			}

			if(isset($_POST['city']))
				$address->city = $_POST['city'];
			
			if(isset($_POST['street_number']))
				$address->street_number = $_POST['street_number'];
				
			if(isset($_POST['phone_number']))
				$address->phone_number = $_POST['phone_number'];

			$address->date_update = gmmktime();
			if($address->update()){
				try{
					if($isChangeAvatar){
						unlink($savePath.'/'. $fileOld['avatar']);
					}
					if($isChangeCover){
						unlink($savePath.'/'. $fileOld['cover']);
					}
				}catch(Exception $e){

				}
				HttpResponse::responseOk();
				return AjaxHelper::jsonSuccess($address,"update success");
			}else{
				HttpResponse::responseInternalServerError();
				return AjaxHelper::jsonError('Have a error in process Update Address');	
			}
		}

		public function actionApprove(){
			if(!isset($_POST['id'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('id is empty');
			}
			$condition = "id=".$_POST['id'];
			$address = Address::model()->find($condition);
			if(!$address){
				HttpResponse::responseNotFound();
				return AjaxHelper::jsonError('Address ID = '. $_POST['id'] .' not found');
			}
			$address->state = 0;
			if($address->update()){
				HttpResponse::responseOk();
				return AjaxHelper::jsonSuccess($address,"create success");
			}else{
				HttpResponse::responseInternalServerError();
				return AjaxHelper::jsonError('Have error in process update');
			}
		}
		
		/**
		 * [actionReport description]
		 * @param address_id
		 * @param accesss_token
		 * @param content
		 * @return [type] [description]
		 */
		public function actionReport(){
			$report = new Report;
			if(!isset($_POST['address_id'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('address_id is empty');
			}
			$condition = "id=".$_POST['address_id'];
			$address = Address::model()->find($condition);
			if(!$address){
				HttpResponse::responseNotFound();
				return AjaxHelper::jsonError('Address ID = '. $_POST['address_id'] .' not found');
			}
			if(isset($_POST['access_token'])){
				$user = $this->checkAuth($_POST['access_token']);
				if($user){
					$report->user_id = $user['id'];
				}else{
					HttpResponse::responseAuthenticationFailure();
					AjaxHelper::jsonError('Authentication is failure');	
				}
			}else{
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('access_token is empty');
			}

			$report->address_id = $_POST['address_id'];

			if(!isset($_POST['content'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('id is empty');
			}

			$report->content = $_POST['content'];
			$report->date_create = gmmktime();
			if($report->save()){

			}else{
				HttpResponse::responseInternalServerError();
				return AjaxHelper::jsonError('Have error in process create report');
			}

		}

		public function checkAuth($token){
			$user = Yii::app()->db->createCommand()
			->select('*')
			->from('user')
			->where('access_token=:token',array(':token'=>$token))
			->queryRow();
			return $user;
		}

		public function getListPostOfAddress($address_id){
			$posts = Yii::app()->db->createCommand()
									->select('*')
									->from('post')
									->where('address_id=:address_id and state = 0',array(':address_id'=>$address_id))
									->queryAll();
			return $posts;
		}
			
	
	}