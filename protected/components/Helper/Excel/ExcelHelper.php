<?php
/**
 * Handler the function to create/update excel file, also contain key and function for excel template
 */
class ExcelHelper{
	//the type of the template
	const EXCEL_TEMPLATE_TYPE_INCOMES = 'incomes';
	const EXCEL_TEMPLATE_TYPE_EXPENSES = 'expenses';

	//the type of excel
	const EXCEL_TYPE_EXCEL_5 = 'Excel5';
	const EXCEL_TYPE_EXCEL_2007 = 'Excel2007';

	/**
	 * Get the url of the income list template
	 */
	public static function getTemplatePath($type){
		if (strcmp($type, ExcelHelper::EXCEL_TEMPLATE_TYPE_INCOMES) == 0) {
		    return Yii::app()->basePath . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . "excel" . DIRECTORY_SEPARATOR."incomes".DIRECTORY_SEPARATOR."IncomesTemplate.xlsx";
		}
		else if (strcmp($type, ExcelHelper::EXCEL_TEMPLATE_TYPE_EXPENSES) == 0) {
		    return Yii::app()->basePath . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . "excel" . DIRECTORY_SEPARATOR."expenses".DIRECTORY_SEPARATOR."ExpensesTemplate.xlsx";
		}
		return null;
	}

	/**
	 * Get the excel object
	 */
	public static function getTemplateObject($type, $excelType){
		$path = ExcelHelper::getTemplatePath($type);
		if($path){
			$objPHPExcel = XPHPExcel::createPHPExcel();
	        $objReader = PHPExcel_IOFactory::createReader($excelType);
	        $objPHPExcel = $objReader->load($path);

	        //pre config
	        return $objPHPExcel;
		}
		return null;
	}

	/**
	 * Get the excel object from path
	 */
	public static function getExcelObject($path){
		if($path){
			$objPHPExcel = XPHPExcel::createPHPExcel();
		    $inputFileType = PHPExcel_IOFactory::identify($path);
		    $excelReader = PHPExcel_IOFactory::createReader($inputFileType);

    		$excel = $excelReader->load($path);
    		return $excel;
		}
		return null;
	}

	/**
	 * Get the excel object metadata
	 */
	public static function getTemplateMetaData($type){
		if (strcmp($type, ExcelHelper::EXCEL_TEMPLATE_TYPE_INCOMES) == 0) {
		    return array(
		    	"start_line" => 3
		    );
		}
		else if (strcmp($type, ExcelHelper::EXCEL_TEMPLATE_TYPE_EXPENSES) == 0) {
		    return array(
		    	"start_line" => 3
		    );
		}
		return null;
	}
}
?>