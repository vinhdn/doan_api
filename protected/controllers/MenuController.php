	<?php

	class MenuController extends Controller
	{
		public function actionIndex()
		{
			$this->render('index');
		}

	public function actionGetSubMenu(){
		if((isset($_POST['id']) && $_POST['id']!='') || (isset($_GET['id']) && $_GET['id']!='')){
			$id = -1;
			if((isset($_POST['id']) && $_POST['id']!=''))
				$id = $_POST['id'];
			if((isset($_GET['id']) && $_GET['id']!='')){
				$id = $_GET['id'];
			}
			$listIdiom = Yii::app()->db->createCommand()
				->select('*')
				->where('MenuId=:id',array(':id'=>$id))
				->from('submenu')
				->queryAll();
			if($listIdiom){
				return ajaxHelper::success($listIdiom, 'Lists success');
			}else{
			return ajaxHelper::error('No Submenu of menu '. $id .' found');
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