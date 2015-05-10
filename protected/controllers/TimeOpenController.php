<?php

Yii::import('application.models.Dto.QueryOption');
	class TimeOpenController extends Controller
	{
		public function actionIndex()
		{
			$this->render('index');
		}

		public function actionCreate(){
			$address_id = $_POST['address_id'];
			if(isset($_POST['time_open'])){
				$time_opens = $_POST['time_open'];
				try {
					$time_array = explode(";", $time_opens);
					if($time_array){
						foreach ($time_array as $time) {
							$times = explode("*", $time);
							$to = new TimeOpen;
							$to->address_id = $address_id;
							$to->weekday = $times[0];
							$to->time_open = $times[1];
							$to->save();
						}
					}
					HttpResponse::responseOk();
					return AjaxHelper::jsonSuccess($address_id,"create TimeOpen success");
				} catch (Exception $e) {
					HttpResponse::responseInternalServerError();
					return AjaxHelper::jsonError($e, 'Have a error in process Create Address');	
				}
			}
		}

	}