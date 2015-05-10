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

	/**
	 * Get info of post with id
	 * @param id
	 * @param access_token
	 * @return [type] [description]
	 */
	public function actionGetInfo(){
		if(!isset($_POST['id'])){
			HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('id is empty');
		}
		$post = Yii::app()->db->createCommand()
		->select('*')
		->from('post')
		->where('id=:id',array(':id'=>$_POST['id']))
		->queryRow();
		if(!$post){
			HttpResponse::responseNotFound();
			return AjaxHelper::jsonError('Post ID = '. $_POST['id'] .' not found');
		}
		$cmts = Yii::app()->db->createCommand()
		->select('*')
		->from('comment')
		->where('post_id=:post_id',array(':post_id'=>$_POST['id']))
		->queryAll();
		$post['comments'] = $cmts;
		$post['is_like'] = false;
		$address['is_owner'] = false;
		if(isset($_POST['access_token'])){
				// HttpResponse::responseBadRequest();
				// return AjaxHelper::jsonError('access_token is not empty');
				$user = $this->checkAuth($_POST['access_token']);
				if($user){
					$condition = 'user_id='.$user['id'].' and post_id='.$post['id'];
					$like = LikePost::model()->find($condition);
					if($like)
						$post['is_like'] = true;
					if($post['owner_id'] == $user['id'])
						$post['is_owner'] = true;
				}
			}
		HttpResponse::responseOk();
		return AjaxHelper::jsonSuccess($post,"Get info success");
	}


	/**
	  Create a post in address
	  @param access_token
	  @param address_id
	  @param image
	  @param content
	  @return [type] [description]
	 */
	public function actionCreate(){
		$post = new Post;
		if(isset($_POST['user_id'])){
			$post->owner_id = $_POST["user_id"];
		}
		else{
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
		}
			if(!isset($_POST['address_id'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('address_id is empty');
			}
			if(!isset($_POST['content'])){
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('content is empty');	
			}
			$address = Address::model()->findByAttributes(array('id'=>$_POST['address_id']));
			if(!$address){
				HttpResponse::responseNotFound();
				return AjaxHelper::jsonError('Address ID = '. $_POST['address_id'] .' not found');
			}
			$savePath = dirname(__FILE__) . '/assets/images';
			$image_name = "";
			if(isset($_POST['photo'])){
				$post->image = $_POST['photo'];
				$image_name = $_POST['photo'];
			}else{
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
			}
			$post->address_id = $_POST['address_id'];
			$post->content = $_POST['content'];
			if(isset($POST['date_create'])){

			}else{
				$post->date_update = gmmktime();
				$post->date_create = gmmktime();
			}
			if(isset($_POST["id"]))
				$post->id = $_POST["id"];
			else
				$post->id = StringHelper::generateRandomOrderKey(Yii::app()->params['ID_LENGTH']);
			if($post->save()){
				HttpResponse::responseOk();
				if($image_name != ""){
					$image = new Image;
					$image->post_id = $post->id;
					$image->date_create = gmmktime();
					$image->id = StringHelper::generateRandomOrderKey(Yii::app()->params['ID_LENGTH']);
					$image->url = $image_name;
					$image->content = $post->content;
					if($image->save()){

					}
				}
				$post = Yii::app()->db->createCommand()
				->select('*')
				->from('post')
				->where('id=:id',array(':id'=>$post->id))
				->queryRow();
				return AjaxHelper::jsonSuccess($post,"create success");
			}else{
				HttpResponse::responseInternalServerError();
				return AjaxHelper::jsonError('Have a error in process Create Post');	
			}
	}


	/**
	  Edit content post
	  @param * id - id of this post
	  @param * access_token - token of user
	  @param  content - text in post
	 * @return [type] [description]
	 */
	public function actionEdit(){
		if(!isset($_POST['id'])){
			HttpResponse::responseBadRequest();
			return AjaxHelper::jsonError('id is empty');
		}
		$condition = 'id='.$_POST['id'];
		$post = Post::model()->find($condition);
		if(!$post){
			HttpResponse::responseNotFound();
			return AjaxHelper::jsonError('Post ID = '. $_POST['id'] .' not found');
		}
		if(isset($_POST['access_token'])){
				$user = $this->checkAuth($_POST['access_token']);
				if($user){
					if($post->owner_id != $user["id"]){
						HttpResponse::responseForbidden();
						AjaxHelper::jsonError('no permission to edit this post');		
					}
				}else{
					HttpResponse::responseAuthenticationFailure();
					AjaxHelper::jsonError('Authentication is failure');	
				}
			}else{
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('access_token is empty');
			}
			$savePath = dirname(__FILE__) . '/assets/images';
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
			if(isset($_POST['content'])){
				$post->content = $_POST['content'];
			}
			$post->date_update = gmmktime();
			if($post->update()){
			HttpResponse::responseOk();
			$post = Yii::app()->db->createCommand()
				->select('*')
				->from('post')
				->where('id=:id',array(':id'=>$post->id))
				->queryRow();
				return AjaxHelper::jsonSuccess($post,"update success");
			}else{
				HttpResponse::responseInternalServerError();
				return AjaxHelper::jsonError('Have a error in process Create Post');	
			}	
	}

	public function actionDelete(){
		if(!isset($_POST['id'])){
			HttpResponse::responseBadRequest();
			return AjaxHelper::jsonError('id is empty');
		}
		$condition = 'id='.$_POST['id'];
		$post = Post::model()->find($condition);
		if(!$post){
			HttpResponse::responseNotFound();
			return AjaxHelper::jsonError('Post ID = '. $_POST['id'] .' not found');
		}
		if(isset($_POST['access_token'])){
				$user = $this->checkAuth($_POST['access_token']);
				if($user){
					if($post->owner_id != $user["id"]){
						HttpResponse::responseForbidden();
						AjaxHelper::jsonError('no permission to delete this post');		
					}
				}else{
					HttpResponse::responseAuthenticationFailure();
					AjaxHelper::jsonError('Authentication is failure');	
				}
			}else{
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('access_token is empty');
			}
		$post->state = 2;
		if($post->update()){
			HttpResponse::responseOk();
				return AjaxHelper::jsonSuccess($post->id,"delete success");
			}else{
				HttpResponse::responseInternalServerError();
				return AjaxHelper::jsonError('Have a error in process delete Post');	
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