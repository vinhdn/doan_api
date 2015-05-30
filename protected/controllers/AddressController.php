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
				return AjaxHelper::jsonError('id is empty');
			}
			$address = Yii::app()->db->createCommand()
					->select('*')
					->from('address')
					->where('id=:id and state = 0',array(':id'=>$_POST['id']))
					->queryRow();

			// Address::model()->find('id=:id',array(':id'=>$_POST['id']));
			if(!$address){
				return AjaxHelper::jsonError('Address ID = '. $_POST['id'] .' not found');
			}
			// return AjaxHelper::jsonSuccess($address);

			$list_post = $this->getListPostOfAddress($address['id']);
			$likes = $this->getListLikeAddress($address['id']);
			// var_dump($list_post);
			if($list_post){
				$address['posts'] = $list_post;
			}else{
				$address['posts'] = array();
			}
			if($likes){
				$address['likes'] = $likes;
			}else{
				$address['likes'] = array();
			}
			$address['is_like'] = false;
			$address['is_owner'] = false;
			if(isset($_POST['access_token']) && ($_POST['access_token'] !== "")){
				// HttpResponse::responseBadRequest();
				// return AjaxHelper::jsonError('access_token is not empty');
				$user = $this->checkAuth($_POST['access_token']);
				if($user){
					// $condition = 'user_id='.$user['id'].' and address_id='.$address['id'];
					// $like = LikeAddress::model()->find($condition);
					// if($like)
					// 	$address['is_like'] = true;
					// else
					// 	$address['is_like'] = false;
					$like = Yii::app()->db->createCommand()
										->select("*")
										->from("like_address")
										->where('user_id=:user_id and address_id=:address_id',
											array(':user_id'=>$user['id'],':address_id'=>$address['id']))
										->queryRow();
										if($like)
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
					$address['rate_number'] = $rates['count_id'];
				}
			}else{
				$address['rate'] = null;
				$address['rate_number'] = 0;
			}
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

		public function actionUpdateCover(){
			$addresses = Address::model()->findAll();
			foreach ($addresses as $var => $address) {
				$img = Yii::app()->db->createCommand()
										->select('image')
										->from('post')
										->where('address_id=:token and image NOT LIKE \'\'',array(':token'=>$address->id))
									->queryRow();
				if($img){
					$addresses[$var]->cover = $img['image'];
					$addresses[$var]->update();
				}
			}
		}

		public function actionGetList(){
			if(!isset($_POST['lat'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('lat is empty');
			}
			if(!isset($_POST['lng'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('lng is empty');
			}
			$dist = 2000;
			if(isset($_POST['dist'])){
				$dist = $_POST['dist'];
			}
			$min_dist = 0;
			if(isset($_POST['min_dist'])){
				$min_dist = $_POST['min_dist'];
				if($min_dist >= $dist){
					$min_dist = 0;
				}
			}
			$limit = 20;
			if(isset($_POST['limit'])){
				$limit = $_POST['limit'];
			}
			$offset = 0;
			if(isset($_POST['offset'])){
				$offset = $_POST['offset'];
			}
			$query = '';
			if(isset($_POST['q'])){
				$query = $_POST['q'];
			}
			$cate = '';
			if(isset($_POST['category_id'])){
				$cate = $_POST['category_id'];
			}
			$addresses = Yii::app()->db->createCommand('call geodist('.$_POST['lat'].', '.$_POST['lng'].','.$min_dist.', '.$dist.', '.$limit.', '.$offset.' , \''.$query.'\''.', \''.$cate.'\')')
										->queryAll();

			foreach ($addresses as $var => $address) {
				$addresses[$var]['category'] = Yii::app()->db->createCommand()
									->select('*')
									->from('category')
									->where('id=:token',array(':token'=>$address['category_id']))
									->queryRow();
			}

					HttpResponse::responseOk();
					return AjaxHelper::jsonSuccess($addresses,"list Address");
		}

		public function actionSearch(){
			if(!isset($_POST['lat'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('lat is empty');
			}
			if(!isset($_POST['lng'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('lng is empty');
			}
			$dist = 2000;
			if(isset($_POST['dist'])){
				$dist = $_POST['dist'];
			}
			$min_dist = 0;
			if(isset($_POST['min_dist'])){
				$min_dist = $_POST['min_dist'];
				if($min_dist >= $dist){
					$min_dist = 0;
				}
			}
			$limit = 20;
			if(isset($_POST['limit'])){
				$limit = $_POST['limit'];
			}
			$offset = 0;
			if(isset($_POST['offset'])){
				$offset = $_POST['offset'];
			}
			$query = '';
			if(isset($_POST['q'])){
				$query = $_POST['q'];
			}
			$cate = '';
			if(isset($_POST['category_id'])){
				$cate = $_POST['category_id'];
			}
			$is_reservation = 2;
			if(isset($_POST['is_reservation'])){
				$is_reservation = $_POST['is_reservation'];
			}
			$is_outdoor = 2;
			if(isset($_POST['is_outdoor'])){
				$is_outdoor = $_POST['is_outdoor'];
			}
			$is_lunch = 2;
			if(isset($_POST['is_lunch'])){
				$is_lunch = $_POST['is_lunch'];
			}
			$is_dinner = 2;
			if(isset($_POST['is_dinner'])){
				$is_dinner = $_POST['is_dinner'];
			}
			$is_wifi = 2;
			if(isset($_POST['is_wifi'])){
				$is_wifi = $_POST['is_wifi'];
			}
			$is_creditcard = 2;
			if(isset($_POST['is_creditcard'])){
				$is_creditcard = $_POST['is_creditcard'];
			}
			$is_breakfast = 2;
			if(isset($_POST['is_breakfast'])){
				$is_breakfast = $_POST['is_breakfast'];
			}

			$addresses = Yii::app()->db->createCommand('call search('.$_POST['lat'].', '.$_POST['lng'].','.$min_dist.', '.$dist.', '.$limit.', '.$offset.' , \''.$query.'\''.', \''.$cate.'\', '.$is_reservation.', '.$is_outdoor.', '.$is_lunch.', '.$is_dinner.', '.$is_wifi.', '.$is_creditcard.', '.$is_breakfast.')')
										->queryAll();

			foreach ($addresses as $var => $address) {
				$addresses[$var]['category'] = Yii::app()->db->createCommand()
									->select('*')
									->from('category')
									->where('id=:token',array(':token'=>$address['category_id']))
									->queryRow();
			}

					HttpResponse::responseOk();
					return AjaxHelper::jsonSuccess($addresses,"list Address");
		}

		/**
		 * [actionCreate description]
		 * @param id
		 * @param access_token
		 * @param email
		 * @param url
		 * @param name
		 * @param category_id
		 * @param about
		 * @param lat
		 * @param lng
		 * @param address
		 * @param street_number
		 * @param phone_number
		 * @return [type] [description]
		 */
		public function actionCreate(){
			if(isset($_POST["id"])){
				$addressP = Address::model()->findByAttributes(array('id'=>$_POST['id']));
				if($addressP){
					HttpResponse::responseConflict();
					return AjaxHelper::jsonError('Address ID = '. $_POST['id'] .' is existed');
				}
			}
			$address = new Address;
			if(isset($_POST['user_id'])){
			$address->owner_id = $_POST["user_id"];
			}else{
				if(isset($_POST['access_token']) && ($_POST['access_token'] !== "")){
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
			}
			if(isset($_POST['email']))
				$address->email = $_POST['email'];

			if(isset($_POST['url']))
				$address->url = $_POST['url'];
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
			$savePath = Yii::app()->params['ASSETS_FOLDER'];

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

			if(isset($_POST['rate']))
				$address->rate = $_POST['rate'];
			if(isset($_POST['rate_number']))
				$address->rate_number = $_POST['rate_number'];
			$address->date_update = gmmktime();
			$address->date_create = gmmktime();
			if(isset($_POST["id"])){
				$address->state = 0;
			}else
			$address->state = 1;
			if(isset($_POST["id"]))
				$address->id = $_POST["id"];
			else
				$address->id = StringHelper::generateRandomOrderKey(Yii::app()->params['ID_LENGTH']);
			if($address->save()){
				if(isset($_POST['time_open'])){
					$time_opens = $_POST['time_open'];
					try {
						$time_array = explode(";", $time_open);
						if($time_array){
							foreach ($time_array as $time) {
								$times = explode("*", $time);
								$to = new TimeOpen;
								$to->address_id = $address->id;
								$to->weekday = $times[0];
								$to->time_open = $times[1];
								$to->save();
							}
						}
					} catch (Exception $e) {

					}
				}
				HttpResponse::responseOk();
				$address = Yii::app()->db->createCommand()
					->select('*')
					->from('address')
					->where('id=:token',array(':token'=>$address->id))
					->queryRow();
					return AjaxHelper::jsonSuccess($address,'Register sucees');
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
			$address = Address::model()->findByAttributes(array('id'=>$_POST['id']));
			if(!$address){
				HttpResponse::responseNotFound();
				return AjaxHelper::jsonError('Address ID = '. $_POST['id'] .' not found');
			}
			if(isset($_POST['access_token']) && ($_POST['access_token'] !== "")){
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
			if(isset($_POST['access_token']) && ($_POST['access_token'] !== "")){
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

		public function actionLike(){
			$address = new LikeAddress;
			if(!isset($_POST['address_id'])){
				HttpResponse::responseBadRequest();
					return AjaxHelper::jsonError('address_id is empty');
			}
			if(isset($_POST['user_id'])){
				$address->user_id = $_POST["user_id"];
			}else{
				if(isset($_POST['access_token']) && ($_POST['access_token'] !== "")){
					$user = $this->checkAuth($_POST['access_token']);
					if($user){
						$address->user_id = $user["id"];
					}else{
						HttpResponse::responseAuthenticationFailure();
						AjaxHelper::jsonError('Authentication is failure');
					}
				}else{
					HttpResponse::responseBadRequest();
					return AjaxHelper::jsonError('access_token is empty');
				}
			}
			$address->address_id = $_POST['address_id'];
			if($address->save()){
				HttpResponse::responseOk();
				return AjaxHelper::jsonSuccess("liked success");
			}else{
				HttpResponse::responseConflict();
				return AjaxHelper::jsonError('Liked or Have error in process like');
			}
		}

		public function actionDisLike(){
			$address = new LikeAddress;
			if(!isset($_POST['address_id'])){
				HttpResponse::responseBadRequest();
					return AjaxHelper::jsonError('address_id is empty');
			}
			if(isset($_POST['user_id'])){
				$address->user_id = $_POST["user_id"];
			}else{
				if(isset($_POST['access_token']) && ($_POST['access_token'] !== "")){
					$user = $this->checkAuth($_POST['access_token']);
					if($user){
						$address->user_id = $user["id"];
					}else{
						HttpResponse::responseAuthenticationFailure();
						AjaxHelper::jsonError('Authentication is failure');
					}
				}else{
					HttpResponse::responseBadRequest();
					return AjaxHelper::jsonError('access_token is empty');
				}
			}
			$address->address_id = $_POST['address_id'];
			if($address->delete()){
				HttpResponse::responseOk();
				return AjaxHelper::jsonSuccess("dislike success");
			}else{
				HttpResponse::responseConflict();
				return AjaxHelper::jsonError('Liked or Have error in process dislike');
			}
		}

		public function actionRate(){
			$rates = new Rate;
			if(isset($_POST['access_token']) && ($_POST['access_token'] !== "")){
				$user = $this->checkAuth($_POST['access_token']);
				if($user){
						$rates->user_id = $user["id"];
				}else{
					HttpResponse::responseAuthenticationFailure();
					AjaxHelper::jsonError('Authentication is failure');
				}
			}else{
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('access_token is empty');
			}
			if(!isset($_POST['address_id'])){
				HttpResponse::responseBadRequest();
					return AjaxHelper::jsonError('address_id is empty');
			}
			if(!isset($_POST['content'])){
				HttpResponse::responseBadRequest();
					return AjaxHelper::jsonError('content is empty');
			}
			if(!isset($_POST['rate'])){
				HttpResponse::responseBadRequest();
					return AjaxHelper::jsonError('rate is empty');
			}
				
			$rates->rate = $_POST['rate'];
			$rates->content = $_POST['content'];
			$rates->address_id = $_POST['address_id'];
			$rates->id = StringHelper::generateRandomOrderKey(Yii::app()->params['ID_LENGTH']);
			$rates->date_update = gmmktime();
			$rates->date_create = gmmktime();
			if($rates->save()){
				HttpResponse::responseOk();
				return AjaxHelper::jsonSuccess($rates->id, "rate success");
			}else{
				HttpResponse::responseConflict();
				return AjaxHelper::jsonError('Have error in process create rate');
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
			foreach ($posts as $var => $post) {
				$posts[$var]['user'] = Yii::app()->db->createCommand()
								->select('id, first_name, last_name, avatar_url, avatar')
								->from('user')
								->where('id=:token',array(':token'=>$post['owner_id']))
								->queryRow();
			}

			return $posts;
		}

		public function getListLikeAddress($address_id){
			$likes = Yii::app()->db->createCommand()
									->select('*')
									->from('like_address')
									->where('address_id=:address_id',array(':address_id'=>$address_id))
									->queryAll();
			foreach ($likes as $var => $like) {
				$likes[$var] = Yii::app()->db->createCommand()
								->select('id, first_name, last_name, avatar_url, avatar')
								->from('user')
								->where('id=:token',array(':token'=>$like['user_id']))
								->queryRow();
			}

			return $likes;
		}

	}
