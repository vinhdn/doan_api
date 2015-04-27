	<?php

	class SubmenuController extends Controller
	{
		public function actionIndex()
		{
			$this->render('index');
		}

	public function actionGetLession(){
		if((isset($_POST['id']) && $_POST['id']!='') || (isset($_GET['id']) && $_GET['id']!='')){
			$id = -1;
			if((isset($_POST['id']) && $_POST['id']!=''))
				$id = $_POST['id'];
			if((isset($_GET['id']) && $_GET['id']!='')){
				$id = $_GET['id'];
			}
			$listIdiom = Yii::app()->db->createCommand()
				->select('Id, Name, Link, Description')
				->where('SubMenuId=:id and Link <>:rong',array(':id'=>$id,':rong'=>""))
				->from('song')
				->queryAll();
			if($listIdiom){
				return ajaxHelper::success($listIdiom, 'Lists success');
			}else{
			return ajaxHelper::error('No Lession of Submenu '. $id .' found');
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