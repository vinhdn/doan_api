<?php
/**
* 			
*/
class PostController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function getListPostOfAddress($address_id){
		$posts = Yii::app()->db->createCommand()
								->select('*')
								->from('post')
								->where('address_id=:address_id',array(':address_id'=>$address_id))
								->queryAll();
		return $posts;
	}

	public function actionCreatePost(){
		$post = new Post;
		if(isset($_POST['access_token'])){
				$user = $this->checkAuth($_POST['access_token']);
				if($user){
					$post->owner_id = $user["id"];
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
			$condition = "id=".$_POST['address_id'];
			$address = Address::model()->find($condition);
			if(!$address){
				HttpResponse::responseNotFound();
				return AjaxHelper::jsonError('Address ID = '. $_POST['address_id'] .' not found');
			}

			if(isset($_FILES['image'])){
				           $allowedExts  =  array("gif", "jpeg", "jpg", "png");
                           $temp    =  explode(".", $_FILES["image"]["name"]);
                           $extension   =  end($temp);

                           if ((($_FILES["image"]["type"] == "image/gif")
                           || ($_FILES["image"]["type"] == "image/jpeg")
                           || ($_FILES["image"]["type"] == "image/jpg")
                           || ($_FILES["image"]["type"] == "image/pjpeg")
                           || ($_FILES["image"]["type"] == "image/x-png")
                           || ($_FILES["image"]["type"] == "image/png")
                           || ($_FILES["image"]["type"] == "application/octet-stream"))
                           && in_array($extension, $allowedExts)) {
                           	if($_FILES['image']['error'] > 0){
                           		HttpResponse::responseForbidden();
                           		return AjaxHelper::jsonError('have a error in file image upload');
                           	}
                           	
							if (!file_exists($savePath)) {
    							mkdir($savePath, 0777, true);
							}
							$image_name = StringHelper::generateRandomString(15).'.'.$extension;
                           	move_uploaded_file($_FILES["image"]["tmp_name"],$savePath.'/'.$image_name);
                           	$post->image = $image_name;
                           }
			}
			$post->address_id = $_POST['address_id'];
			$post->update_time = gmmktime();
			$post->create_time = gmmktime();
			$post->id = StringHelper::generateRandomOrderKey(Yii::app()->params['ID_LENGTH']);
			if($post->save()){
			HttpResponse::responseOk();
				return AjaxHelper::jsonSuccess($address->id,"create success");
			}else{
				HttpResponse::responseInternalServerError();
				return AjaxHelper::jsonError('Have a error in process Create Address');	
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
}