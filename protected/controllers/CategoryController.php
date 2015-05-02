<?php
/**
* 			
*/
class CategoryController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionGetList(){
		$cates = Yii::app()->db->createCommand()
		->select('*')
		->from('category')
		->queryAll();
		HttpResponse::responseOk();
		return AjaxHelper::jsonSuccess($cates,"List category");
	}
}