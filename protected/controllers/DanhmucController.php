	<?php

	class DanhmucController extends Controller
	{
		public function actionIndex()
		{
			$this->render('index');
		}
		
	public function actionGetList(){
		$listIdiom = Yii::app()->db->createCommand()
		->select('*')
		->from('danhmuc')
		->queryAll();
		if($listIdiom){
			return ajaxHelper::success($listIdiom, 'Lists success');
		}else{
			return ajaxHelper::error('No lists idiom found');
		}
	}

	public function actionGetMenu(){
		if((isset($_POST['id']) && $_POST['id']!='') || (isset($_GET['id']) && $_GET['id']!='')){
			$id = -1;
			if((isset($_POST['id']) && $_POST['id']!=''))
				$id = $_POST['id'];
			if((isset($_GET['id']) && $_GET['id']!='')){
				$id = $_GET['id'];
			}
			$listIdiom = Yii::app()->db->createCommand()
				->select('*')
				->where('DanhMucId=:id',array(':id'=>$id))
				->from('menu')
				->queryAll();
			if($listIdiom){
				return ajaxHelper::success($listIdiom, 'Lists success');
			}else{
			return ajaxHelper::error('No Menu of Catelogory'. $id .' found');
		}
		}else{
			return ajaxHelper::error('Missing id param');
		}
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}