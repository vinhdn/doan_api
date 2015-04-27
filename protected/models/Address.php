<?php
class Address extends CActiveRecord
{

	// public $posts;
	// public $is_owner;
	// public $is_like;
	// public $rate;
	// public $count_rate;
	// public $location;
	// public $list_time_open;
	// public $category;

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
		return '{{address}}';
	}

	protected function afterFind(){
        parent::afterFind();
    }	
}