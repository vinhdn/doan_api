<?php
class QueryOption{
	protected $_withs = array();				//relation tables
	protected $_orderBy = '';
	protected $_order = 'ASC';
	protected $_limit = 0;
	protected $_offset = 0;
	protected $_selects = array();
	protected $_conditions = array();
	protected $_simpleSearchText = '';
	protected $_simpleSearchFields = array();
	protected $_group = '';
	protected $_having = array();
	
	public function isWithsSet() {
		if(is_null($this->_withs) || count($this->_withs) == 0){
			return false;
		}
		else{
			return true;
		}
	}
	
	public function addWith($with) {
		if(!$this->_withs){
			$this->_withs = array();
		}
		
		if(!in_array($with, $this->_withs)){
			array_push($this->_withs, $with);
		}
	}
	
	public function resetWiths() {
		$this->_withs = array();
	}
	
	public function withs() {
		return $this->_withs;
	}

	public function setGroups($groups) {
		$this->_group = $groups;
	}

	public function addGroup($group) {
		if(!$this->_group || !is_array($this->_group )){
			$this->_group = array();
		}
		array_push($this->_group, $group);
	}
	
	public function group() {
		return $this->_group;
	}
	
	public function isOrderBySet() {
		if(is_null($this->_orderBy) || strcmp(trim($this->_orderBy), '') == 0){
			return false;
		}
		else{
			return true;
		}
	}
	
	public function setOrderBy($attribute) {
		$this->_orderBy = $attribute;
	}
	
	public function orderBy() {
		return $this->_orderBy;
	}
	
	public function isOrderSet() {
		if(is_null($this->_order) || strcmp(trim($this->_order), '') == 0){
			return false;
		}
		else{
			return true;
		}
	}
	
	public function setOrder($asc) {
		if($asc){
			$this->_order = 'ASC';
		}
		else{
			$this->_order = 'DESC';
		}
	}
	
	public function setOrderByText($asc) {
		$this->_order = $asc;
	}
	
	public function order() {
		return $this->_order;
	}
	
	public function isLimitSet() {
		if(is_null($this->_limit) || $this->_limit == 0){
			return false;
		}
		else{
			return true;
		}
	}
	
	public function setLimit($value) {
		$this->_limit = $value;
	}
	
	public function limit() {
		return $this->_limit;
	}
	
	public function isOffsetSet() {
		if(is_null($this->_offset) || $this->_offset == 0){
			return false;
		}
		else{
			return true;
		}
	}
	
	public function setOffset($value) {
		$this->_offset = $value;
	}
	
	public function offset() {
		return $this->_offset;
	}
	
	public function isSelectedSet() {
		if(is_null($this->_selects) || count($this->_selects) == 0){
			return false;
		}
		else{
			return true;
		}
	}
	
	public function setSelectWithJson($json) {
		$selects = json_decode($json, true);
		if($selects){
			foreach($selects as $select){
				$this->addSelect($select);
			}
		}
	}
	
	public function addSelect($attribute) {
		if(!in_array($attribute, $this->_selects)){
			array_push($this->_selects, $attribute);
		}
	}
	
	public function resetSelects() {
		$this->_selects = array();
	}
	
	public function selects() {
		return $this->_selects;
	}
	
	public function isConditionsSet() {
		if(is_null($this->_conditions) || count($this->_conditions) == 0){
			return false;
		}
		else{
			return true;
		}
	}

	public function checkConditionFieldName($field){
		if(!$field){
			return false;
		}

		//field cannot have space, if we meet field with space, return false
		if ( preg_match('/\s/',trim($field)) ){
			return false;
		}

		return true;
	}

	public function escapeConditionFieldName($field){
		//remove all sensitive string
		$field = str_replace(' ', '', $field);

		if (!(strpos($field, '`') !== false)){
			$part = explode(".",$field);
			if(count($part) <= 1){
				$field = '`'.$field.'`';
			}
		}

		return SafeSqlHelper::escapeInjection($field);
	}

	public function escapeConditionValue($value){
		//prevent from the sensitive string
		$value = str_replace('--', '', $value);
		$value = str_replace('tbl_', '', $value);

		return SafeSqlHelper::escapeInjection($value);
	}

	public function getConditions(){
		return $this->_conditions;
	}
	
	public function setConditionsWithJson($json) {
		$this->_conditions = array();
		$this->addConditionsWithJson($json);
	}

	public function addConditionsWithJson($json){
		if(is_array($json)){
			$conditions = $json;
		}
		else{
			$conditions = json_decode($json, false);
		}

		if($conditions){
			foreach($conditions as $condition){

				if(isset($condition->express) && $this->checkConditionFieldName($condition->express->field)){
					$express = '';
					if(strcmp(strtolower($condition->express->type), 'like') == 0){
						$express = "( " . $this->escapeConditionFieldName($condition->express->field) . ' LIKE "%' . $this->escapeConditionValue($condition->express->value) . '%"' . " )";
					}
					else if(strcmp(strtolower($condition->express->type), '=') == 0){
						$express = "( " . $this->escapeConditionFieldName($condition->express->field) . ' = "' . $this->escapeConditionValue($condition->express->value) . '"'. " )";
					}
					else if(strcmp(strtolower($condition->express->type), '>') == 0){
						$express = "( " . $this->escapeConditionFieldName($condition->express->field) . ' > ' . $this->escapeConditionValue($condition->express->value) . '' . " )";
					}
					else if(strcmp(strtolower($condition->express->type), '<') == 0){
						$express = "( " . $this->escapeConditionFieldName($condition->express->field) . ' < ' . $this->escapeConditionValue($condition->express->value) . '' . " )";
					}
					else if(strcmp(strtolower($condition->express->type), '>=') == 0){
						$express = "( " . $this->escapeConditionFieldName($condition->express->field) . ' >= ' . $this->escapeConditionValue($condition->express->value) . '' . " )";
					}
					else if(strcmp(strtolower($condition->express->type), '<=') == 0){
						$express = "( " . $this->escapeConditionFieldName($condition->express->field) . ' <= ' . $this->escapeConditionValue($condition->express->value) . '' . " )";
					}
					else if(strcmp(strtolower($condition->express->type), 'in') == 0 && is_array($condition->express->value)){
						if(count($condition->express->value) > 0){
							$inValues = '';
							$countIn = 0;
							foreach($condition->express->value as $inValue){
								$countIn++;
								if($countIn == count($condition->express->value)){
									$inValues .= "'".$this->escapeConditionValue($inValue)."'";
								}
								else{
									$inValues .= "'".$this->escapeConditionValue($inValue)."'" .',';
								}
							}
							$express = "( " . $this->escapeConditionFieldName($condition->express->field) . ' IN (' . $inValues . ')' . " )";
						}
					}
					else if(strcmp(strtolower($condition->express->type), 'notin') == 0 && is_array($condition->express->value)){
						if(count($condition->express->value) > 0){
							$inValues = '';
							$countIn = 0;
							foreach($condition->express->value as $inValue){
								$countIn++;
								if($countIn == count($condition->express->value)){
									$inValues .= "'".$this->escapeConditionValue($inValue)."'";
								}
								else{
									$inValues .= "'".$this->escapeConditionValue($inValue)."'" .',';
								}
							}
							$express = "( " . $this->escapeConditionFieldName($condition->express->field) . ' NOT IN (' . $inValues . ')' . " )";
						}
					}
					else if(strcmp(strtolower($condition->express->type), 'containlikeall') == 0 && is_array($condition->express->value)){
						$express = "( ";
						$countIn = 0;
						foreach($condition->express->value as $inValue){
							$countIn++;
							if($countIn != count($condition->express->value)){
								$express .= ' ' . $this->escapeConditionFieldName($condition->express->field) . ' LIKE "%' . $this->escapeConditionValue($inValue) . '%" AND ';
							}
							else{
								$express .= ' ' . $this->escapeConditionFieldName($condition->express->field) . ' LIKE "%' . $this->escapeConditionValue($inValue) . '%" ) ';
							}
						}
					}
					else if(strcmp(strtolower($condition->express->type), 'range') == 0){
						$express = "( " . $this->escapeConditionFieldName($condition->express->field) . ' >= ' . $this->escapeConditionValue($condition->express->valueBottom) . ' AND ' . $condition->express->field . ' <= ' . $this->escapeConditionValue($condition->express->valueTop) . " )";
					}
					else if(strcmp(strtolower($condition->express->type), 'is not null') == 0){
						$express = "( " . $this->escapeConditionFieldName($condition->express->field) . ' IS NOT NULL )';
					}
					else if(strcmp(strtolower($condition->express->type), 'is null') == 0){
						$express = "( " . $this->escapeConditionFieldName($condition->express->field) . ' IS NULL )';
					}

					if(isset($condition->isHaving) && $condition->isHaving == true){
						$this->addHaving($express);
					}
					else{
						if(isset($condition->connect)){
							$this->addCondition($express, $condition->connect);
						}
						else{
							$this->addCondition($express);
						}
					}
				}
			}
		}
	}
	
	public function addCondition($condition, $andOr = null) {
		$conditionData = array();
		$conditionData['condition'] = $condition;
		if($andOr){
			$conditionData['connection'] = $andOr;
		}
		array_push($this->_conditions, $conditionData);
	}

	public function addHaving($having){
		if(!$this->_having){
			$this->_having = array();
		}
		array_push($this->_having, $having);	
	}
	
	public function resetConditions() {
		$this->_conditions = array();
	}
	
	public function conditions() {
		return $this->_conditions;
	}
	
	public function setSimpleSearchFields($fields){
		if($fields && is_array($fields) && count($fields) > 0){
			$this->_simpleSearchFields = $fields;
		}
	}
	
	public function setSimpleSearchText($search) {
		$this->_simpleSearchText = $search;
	}
	
	public function generateCountCriteria(){
		$criteria = $this->generateCriteria();
		$criteria->limit = -1;
		$criteria->offset = -1;
		return $criteria;
	}
	
	public function generateCriteria(){
		$criteria =  new CDbCriteria();

		if($this->_group && is_array($this->_group) && count($this->_group) > 0){
			$groupString = '';
			$groupCount = 0;
			foreach($this->_group as $groupItem){
				$groupCount++;
				if($groupCount == count($this->_group)){
					$groupString .= $groupItem;
				}
				else{
					$groupString .= $groupItem .',';
				}
			}

			$criteria->group = $groupString; // optional group by
		}
		
		if($this->isWithsSet()){
			$criteria->with = $this->_withs;
			$criteria->together = true; // you'll need together so that the other tables are joined in the same query
		}
		
		if($this->isOrderBySet()){
			if($this->isOrderSet()){
				//check if this is the relate order
				$part = explode(".",$this->orderBy());
				if(count($part) > 1){
					$criteria->order = $this->orderBy() . ' '.$this->order();
				}
				else{
					$criteria->order = '`'.$this->orderBy() . '`' . ' '.$this->order();
				}
				
			}
			else{
				$part = explode(".",$this->orderBy());
				if(count($part) > 1){
					$criteria->order = $this->orderBy();
				}
				else{
					$criteria->order = '`'.$this->orderBy() . '`';
				}
			}
		}
		
		if($this->isLimitSet()){
			if(!$this->isOffsetSet()){
				$criteria->limit = $this->limit();
				$criteria->offset = 0;
			}
			else{
				$criteria->limit = $this->limit();
				$criteria->offset = $this->offset();
			}
		}
		
		if($this->isSelectedSet()){
			$criteria->select = $this->selects();
		}
		
		if($this->isConditionsSet()){
			$conditions = $this->conditions();
			foreach($conditions as $condition){
				if(isset($condition['condition'])){
					if(isset($condition['connection'])){
						$criteria->addCondition($condition['condition'],$condition['connection']);
					}
					else{
						$criteria->addCondition($condition['condition'],'AND');
					}
				}
			}
		}

		//having
		if(count($this->_having) > 0){
			$havingString = '';
			$havingCount = 0;
			foreach($this->_having as $having){
				$havingCount++;
				if($havingCount == count($this->_having)){
					$havingString .= $having . ' ';
				}
				else{
					$havingString .= $having . ' AND ';
				}
			}

			$criteria->having = $havingString;
		}
		
		//simple search text, make sure the fields is set
		if($this->_simpleSearchFields && is_array($this->_simpleSearchFields) && count($this->_simpleSearchFields) > 0){
			//Make sure search string is set
			if($this->_simpleSearchText && strcmp(trim($this->_simpleSearchText), '') != 0){
				$express = "";
				$countExpress = 0;
				foreach($this->_simpleSearchFields as $field){
					//check if this is the relate order
					$part = explode(".",$field);
					if(count($part) > 1){
						$express .= " $field LIKE '%".$this->_simpleSearchText."%' ";
					}
					else{
						$express .= " `$field` LIKE '%".$this->_simpleSearchText."%' ";
					}

					$countExpress++;
					if($countExpress != count($this->_simpleSearchFields)){
						$express .= " OR";
					}
				}
				
				if($express && strcmp(trim($express), '') != 0){
					$criteria->addCondition($express,'AND');
				}
			}
		}
		
		return $criteria;
	}
}