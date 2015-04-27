<?php
class Submenu extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_user':
	 * @var integer $id_cdm
	 * @var integer $id_chiendich
	 * @var integer $img_adv
	 * @var integer $img_regis
	 * @var integer $img_list
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{submenu}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id, Name', 'required'),
			array('Id, Name', 'safe', 'on'=>'search'),
		);
	}

	protected function afterFind(){
        parent::afterFind();
    }	
}