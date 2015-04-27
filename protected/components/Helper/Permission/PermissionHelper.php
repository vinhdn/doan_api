<?php
class PermissionHelper{
	/**
	 * Get all the permission of an user have on a table
	 */
	public static function getPermissionsOnTable($user_id, $user_groups, $table){
		$permissionGroups = PosConstant::$groups;
		$query = PermissionHelper::getPermissionsOnTableQuery($user_id, $user_groups, $table);
   		$results = Yii::app()->db->createCommand($query)->queryAll();

   		$actions = array();
   		foreach ($results as $result) {
   			array_push($actions, $result["c_title"]);
   		}
   		return $actions;
	}

	/**
	 * Get query to get all the permission of an user have on a table
	 */
	public static function getPermissionsOnTableQuery($user_id, $user_groups, $table){
		$permissionGroups = PosConstant::$groups;
		$query = "select ac.c_title
				from
					-- Privileges that apply to the table and grant the given action
				    -- Not an inner join because the action may be granted even if there is no
				    -- privilege granting it.  For example, root users can take all actions.
   					tbl_action as ac
   					left outer join tbl_privilege as pr
			        	on pr.c_related_table = '$table'
				            and pr.c_action = ac.c_title
				            and pr.c_type = 'table'
				where
					-- The action must apply to tables (NOT apply to objects)
					(ac.c_apply_object = 0) and (
				        -- Members of the 'root' group are always allowed to do everything
				        ($user_groups & $permissionGroups[root] <> 0)
				        -- user privileges
				        or (pr.c_role = 'user' and pr.c_who = $user_id)
				        -- group privileges
				        or (pr.c_role = 'group' and (pr.c_who & $user_groups <> 0))
				    )
   				";
   		return $query;
	}

	/**
	 * Get all object which user can do a certain action on (for selected table)
	 */
	public static function getObjectForAnAction($user_id, $user_groups, $table, $action, $object_id_key){
		$query = PermissionHelper::queryGetObjectForAnAction($user_id, $user_groups, $table, $action, $object_id_key);
		$results = Yii::app()->db->createCommand($query)->queryAll();

		return $results;
	}
	/**
	 * query to get the object which can be applied an action
	 */
	public static function queryGetObjectForAnAction($user_id, $user_groups, $table, $action, $object_id_key, $id_only = true){
		$permissionGroups = PosConstant::$groups;
		$unixPermissions = PosConstant::$unixPermissions;

		$selectField = ($id_only)?$object_id_key:"*";

		$query = "
			select distinct obj.$selectField
			from $table as obj
				-- Filter out actions that do not apply to this object type
			   	inner join tbl_implemented_action as ia
			      	on ia.c_table = '$table'
			         	and ia.c_action = '$action'
			         	and ((ia.c_status = 0) or (ia.c_status & obj.c_status <> 0))
			         	inner join tbl_action as ac
					    on ac.c_title = '$action'
					   		-- Privileges that apply to the object (or every object in the table)
					   		-- and grant the given action
					   		left outer join tbl_privilege as pr
					      	on pr.c_related_table = '$table'
					         	and pr.c_action = '$action'
					         	and (
						            	(pr.c_type = 'object' and pr.c_related_uid = obj.$object_id_key)
						            	or pr.c_type = 'global'
						            	or (pr.c_role = 'self' and $user_id = obj.$object_id_key and '$table' = 'tbl_entity')
					            )
			where
			   	-- The action must apply to objects
			   	ac.c_apply_object
			   	and (
			   		-- Members of the 'root' group are always allowed to do everything
      				($user_groups & $permissionGroups[root] <> 0)
      				-- UNIX style read permissions in the bit field
      				or (
      					ac.c_title = 'read'
      					and (
      						-- The other_read permission bit is on
         					(obj.c_unixperms & $unixPermissions[other_read] <> 0)
         					or (
         						(obj.c_unixperms & $unixPermissions[owner_read] <> 0)
         						and 
         						obj.c_owner = $user_id
         					)
							or (
								(obj.c_unixperms & $unixPermissions[group_read] <> 0)
            					and 
            					($user_groups & obj.c_group <> 0)
							)
      					)
      				)
					-- UNIX style write permissions in the bit field
					or (
						ac.c_title = 'write' 
						and (
							-- The other_write permission bit is on
         					(obj.c_unixperms & $unixPermissions[other_write] <> 0)
         					-- The owner_write permission bit is on, and the member is the owner
         					or (
         						(obj.c_unixperms & $unixPermissions[owner_write] <> 0)
            					and 
            					obj.c_owner = $user_id
         					)
							-- The group_write permission bit is on, and the member is in the group
							or (
								(obj.c_unixperms & $unixPermissions[group_write] <> 0)
							    and 
							    ($user_groups & obj.c_group <> 0)
							)
						)
					)
					-- UNIX style delete permissions in the bit field
      				or (
      					ac.c_title = 'delete' 
      					and (
      						-- The other_write permission bit is on
         					(obj.c_unixperms & $unixPermissions[other_delete] <> 0)
         					-- The owner_write permission bit is on, and the member is the owner
         					or (
         						(obj.c_unixperms & $unixPermissions[owner_delete] <> 0)
            					and 
            					obj.c_owner = $user_id
         					)
							-- The group_write permission bit is on, and the member is in the group
							or (
								(obj.c_unixperms & $unixPermissions[group_delete] <> 0)
							    and 
							    ($user_groups & obj.c_group <> 0)
							)
      					)
      				)
					-- user privileges
					or (pr.c_role = 'user' and pr.c_who = $user_id)
					-- owner privileges
			      	or (pr.c_role = 'owner' and obj.c_owner = $user_id)
			      	-- owner_group privileges
			      	or (
			      		pr.c_role = 'owner_group' 
			      		and 
			      		(obj.c_group & $user_groups <> 0)
			      	)
					-- group privileges
      				or (
      					pr.c_role = 'group' 
      					and 
      					(pr.c_who & $user_groups <> 0)
      				)
					-- self privileges
      				or pr.c_role = 'self'
			    )
		";

		return $query;
	}

	/**
	 * get the actions an user have on an object
	 */
	public static function getUserActionsOnObject($user_id, $user_groups, $table, $object_id, $object_id_key){
		$query = PermissionHelper::queryGetUserActionsOnObject($user_id, $user_groups, $table, $object_id, $object_id_key);
		$results = Yii::app()->db->createCommand($query)->queryAll();

		$actions = array();
   		foreach ($results as $result) {
   			array_push($actions, $result["c_title"]);
   		}
   		return $actions;
	}

	/**
	 * query to get the actions an user have on an object
	 */
	public static function queryGetUserActionsOnObject($user_id, $user_groups, $table, $object_id, $object_id_key){
		$permissionGroups = PosConstant::$groups;
		$unixPermissions = PosConstant::$unixPermissions;

		$selectField = $object_id_key;

		$query = "
			select distinct ac.c_title
			from
   				tbl_action as ac
   				-- join onto the object itself
   				inner join $table as obj on obj.$selectField = $object_id
   				-- Filter out actions that do not apply to this object type
   				inner join tbl_implemented_action as ia
   				on ia.c_action = ac.c_title
         			and ia.c_table = '$table'
         			and (
         				(ia.c_status = 0) 
         				or 
         				(ia.c_status & obj.c_status <> 0)
         			)
				-- Privileges that apply to the object (or every object in the table)
			    -- and grant the given action
			    left outer join tbl_privilege as pr
			    	on pr.c_related_table = '$table'
			    	and pr.c_action = ac.c_title
			        and (
			            (pr.c_type = 'object' and pr.c_related_uid = $object_id)
			            or pr.c_type = 'global'
			            or (pr.c_role = 'self' and $user_id = $object_id and '$table' = 'tbl_entity')
			        )
			where
				-- The action must apply to objects
   				ac.c_apply_object
   				and (
   					-- Members of the 'root' group are always allowed to do everything
      				($user_groups & $permissionGroups[root] <> 0)
      				-- UNIX style read permissions in the bit field
      				or (
      					ac.c_title = 'read'
      					and (
      						-- The other_read permission bit is on
         					(obj.c_unixperms & $unixPermissions[other_read] <> 0)
         					-- The owner_read permission bit is on, and the member is the owner
					        or (
					        	(obj.c_unixperms & $unixPermissions[owner_read] <> 0)
					            and obj.c_owner = $user_id
					        )
							-- The group_read permission bit is on, and the member is in the group
							or (
								(obj.c_unixperms & $unixPermissions[group_read] <> 0)
							    and 
							    ($user_groups & obj.c_group <> 0)
							)
      					)
      				)
					or (
      					ac.c_title = 'write'
      					and (
      						-- The other_write permission bit is on
         					(obj.c_unixperms & $unixPermissions[other_write] <> 0)
         					-- The owner_write permission bit is on, and the member is the owner
					        or (
					        	(obj.c_unixperms & $unixPermissions[owner_write] <> 0)
					            and obj.c_owner = $user_id
					        )
							-- The group_write permission bit is on, and the member is in the group
							or (
								(obj.c_unixperms & $unixPermissions[group_write] <> 0)
							    and 
							    ($user_groups & obj.c_group <> 0)
							)
      					)
      				)
					or (
      					ac.c_title = 'delete'
      					and (
      						-- The other_delete permission bit is on
         					(obj.c_unixperms & $unixPermissions[other_delete] <> 0)
         					-- The owner_delete permission bit is on, and the member is the owner
					        or (
					        	(obj.c_unixperms & $unixPermissions[owner_delete] <> 0)
					            and obj.c_owner = $user_id
					        )
							-- The group_delete permission bit is on, and the member is in the group
							or (
								(obj.c_unixperms & $unixPermissions[group_delete] <> 0)
							    and 
							    ($user_groups & obj.c_group <> 0)
							)
      					)
      				)
					-- user privileges
				    or (pr.c_role = 'user' and pr.c_who = $user_id)
				    -- owner privileges
				    or (pr.c_role = 'owner' and obj.c_owner = $user_id)
				    -- owner_group privileges
				    or (
				    	pr.c_role = 'owner_group' 
				    	and (obj.c_group & $user_groups <> 0)
				    )
					-- group privileges
      				or (
      					pr.c_role = 'group' 
      					and (pr.c_who & $user_groups <> 0)
      				)
					-- self privileges
      				or pr.c_role = 'self'
   				)
		";

		return $query;
	}

}