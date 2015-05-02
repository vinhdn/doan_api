<?php
/**
* 			
*/
class CommentController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	/**
	 * Create Comment
	 * @return [type] [description]
	 */
	public function actionCreate(){
		$comment = new Comment;
		if(isset($_POST['access_token'])){
				$user = $this->checkAuth($_POST['access_token']);
				if($user){
					$comment->owner_id = $user["id"];
				}else{
					HttpResponse::responseAuthenticationFailure();
					AjaxHelper::jsonError('Authentication is failure');	
				}
			}else{
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('access_token is empty');
			}

		if(!isset($_POST['comment'])){
			HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('comment is empty');	
		}
		if(!isset($_POST['post_id'])){
			HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('post_id is empty');	
		}

		$condition = 'id='.$_POST['post_id'];
		$post = Post::model()->find($condition);
		if(!$post){
			HttpResponse::responseNotFound();
			return AjaxHelper::jsonError('Post ID = '. $_POST['post_id'] .' not found');
		}

		$comment->post_id = $_POST['post_id'];
		$comment->comment = $_POST['comment'];
		if(isset($_POST['type']) && $_POST['type'] > 0){
			$comment->type = $_POST['type'];
		}
		$comment->date_update = gmmktime();
		$comment->date_create = gmmktime();
		$comment->id = StringHelper::generateRandomOrderKey(Yii::app()->params['ID_LENGTH']);

		if($comment->save()){
			HttpResponse::responseOk();
			return AjaxHelper::jsonSuccess($comment,"create success");
			}else{
				HttpResponse::responseInternalServerError();
				return AjaxHelper::jsonError('Have a error in process Create comment');	
			}
	}

	public function actionDelete(){
		if(!isset($_POST['id'])){
			HttpResponse::responseBadRequest();
			return AjaxHelper::jsonError('id is empty');
		}
		$condition = 'id='.$_POST['id'];
		$comment = Comment::model()->find($condition);
		if(!$comment){
			HttpResponse::responseNotFound();
			return AjaxHelper::jsonError('Comment ID = '. $_POST['id'] .' not found');
		}
		if(isset($_POST['access_token'])){
				$user = $this->checkAuth($_POST['access_token']);
				if($user){
					if($comment->owner_id != $user["id"]){
						HttpResponse::responseForbidden();
						AjaxHelper::jsonError('no permission to delete this comment');		
					}
				}else{
					HttpResponse::responseAuthenticationFailure();
					AjaxHelper::jsonError('Authentication is failure');	
				}
			}else{
				HttpResponse::responseBadRequest();
				return AjaxHelper::jsonError('access_token is empty');
			}
		$comment->state = 2;
		if($comment->update()){
			HttpResponse::responseOk();
				return AjaxHelper::jsonSuccess($comment->id,"delete success");
			}else{
				HttpResponse::responseInternalServerError();
				return AjaxHelper::jsonError('Have a error in process delete Comment');	
			}
	}

	public function actionReport(){

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