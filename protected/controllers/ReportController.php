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

	public function actionCreate(){
		$report = new Report;
		if(isset($_POST['access_token'])){
				$user = $this->checkAuth($_POST['access_token']);
				if($user){
					$report->user_id = $user["id"];
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

		$report->date_create = gmmktime();
		$report->id = StringHelper::generateRandomOrderKey(Yii::app()->params['ID_LENGTH']);

		if($report->save()){
			HttpResponse::responseOk();
			return AjaxHelper::jsonSuccess($report,"create success");
			}else{
				HttpResponse::responseInternalServerError();
				return AjaxHelper::jsonError('Have a error in process Create Report');	
			}
	}
}