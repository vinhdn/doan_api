<?php
	/**
	 * QUERY HELPER FUNCTIONS
	 * @author Do Cong Bang docongbang1993@gmail.com
	 */
	class queryHelper {
		
		function __construct() {
			
		}
		private function renderFieldToPrototype($field) 
		{
			$return	=	array();
			if ($field['multi_values'] == 1) {
				$return[$field['slug']]	=	array();
				foreach ($field['value'] as $key => $value) {
					$return[$field['slug']]	=	$value;
				}
			} else {
				$return[$field['slug']]	=	$field['value']['value'];
			}
			return $return;
		}
		public function renderPoll($post, $fields = FALSE)
		{
			$cmsQuery	=	new cmsQuery();
			$poll_options	=	$cmsQuery->select('post')
										->where(
											array(
												'parent_id'	=>	$post['id'],
												'app'		=>	'poll-option'
											)
										)
										->query();
			$idsArray 	=	array();
			foreach ($poll_options as $key => $poll_option) {
				$idsArray[]	=	$poll_option['option_id'];
			}

			$number_of_players 	= 	0;
			$number_of_votes 	=	0;
			if (count($idsArray) > 0) {
					$countResults 	= 	Yii::app()->db->createCommand()
											    ->select('Count(Distinct user_id) AS number_of_players, Count(*) AS number_of_votes')
											    ->from('tbl_bids')
											    ->where(array('in', 'post_id', $idsArray))
											    ->queryRow();
					if ($countResults) {
						$number_of_players	=	$countResults['number_of_players'];
						$number_of_votes	=	$countResults['number_of_votes'];
					}
			}

			

			$result	= array(
							'poll_id'				=>	$post['id'],
							'title'					=>	$post['title'],
							'description'			=>	$post['content'],
							'image_url'				=>	$post['thumbnail'],
							'created_time'			=>	$post['created_time'],
							'updated_time'			=>	$post['updated_time'],
							'enable'				=>	(int) $post['enable'],
							'number_of_comments'	=>	(int) $post['numcomments'],
							'number_of_views'		=>	(int) $post['numviews'],
							'number_of_following'	=>	(int) $post['numsubscribes'],

							'number_of_players'		=>	(int) $number_of_players,
							'number_of_votes'		=>	(int) $number_of_votes,

							'category'				=>	$post['categories'][key($post['categories'])],		

							'user'					=>	$post['user'],

							'poll_options'	=>	$poll_options
						);

			if ($fields) {
				foreach ($fields as $key => $field) {
					$result	=	array_merge($result, self::renderFieldToPrototype($field));
				}				
			}
			return $result;
		}
		
		public function renderPollOption($post, $fields = FALSE)
		{
			$number_of_players 	= 	0;
			$number_of_votes 	=	0;
			$countResults 		= 	Yii::app()->db->createCommand()
											    ->select('Count(Distinct user_id) AS number_of_players, Count(*) AS number_of_votes')
											    ->from('tbl_bids')
											    ->where('post_id=:post_id', array(':post_id'=>$post['id']))
											    ->queryRow();
			if ($countResults) {
				$number_of_players	=	$countResults['number_of_players'];
				$number_of_votes	=	$countResults['number_of_votes'];
			}
			$result	= array(
							'option_id'			=>	$post['id'],
							'enable'			=>	(int) $post['enable'],
							'title'				=>	$post['title'],
							'description'		=>	$post['content'],
							'image_url'			=>	$post['thumbnail'],
							'created_time'		=>	$post['created_time'],
							'updated_time'		=>	$post['updated_time'],
							'number_of_views'	=>	(int) $post['numviews'],

							'number_of_players'	=>	(int) $number_of_players,
							'number_of_votes'	=>	(int) $number_of_votes,

						);

			if ($fields) {
				foreach ($fields as $key => $field) {
					$result	=	array_merge($result, self::renderFieldToPrototype($field));
				}				
			}
			return $result;
		}
		
		public function renderAuction($post, $fields = FALSE)
		{
			$number_of_players 	= 	0;
			$number_of_bids 	=	0;
			$countResults 		= 	Yii::app()->db->createCommand()
											    ->select('Count(Distinct user_id) AS number_of_players, Count(*) AS number_of_bids')
											    ->from('tbl_bids')
											    ->where('post_id=:post_id', array(':post_id'=>$post['id']))
											    ->queryRow(); 
			$current_bid_coins	=	Yii::app()->db->createCommand()
												->select(' max(bid_amount) AS current_bid_coins')
												->from('tbl_bids')
												->where('post_id=:post_id', array(':post_id'=>$post['id']))
												->queryScalar();
			if ($countResults) {
				$number_of_players	=	$countResults['number_of_players'];
				$number_of_bids		=	$countResults['number_of_bids'];
			}
			$result	= array(
							'auction_id'			=>	$post['id'],
							'enable'				=>	(int) $post['enable'],
							'title'				=>	$post['title'],
							'description'		=>	$post['content'],
							'image_url'			=>	$post['thumbnail'],
							'created_time'		=>	$post['created_time'],
							'updated_time'		=>	$post['updated_time'],

							'number_of_players'	=>	(int) $number_of_players,
							'number_of_bids'	=>	(int) $number_of_bids,
							'current_bid_coins'	=>	(int) $current_bid_coins,

							'number_of_comments'	=>	(int) $post['numcomments'],
							'number_of_views'		=>	(int) $post['numviews'],
							'number_of_following'	=>	(int) $post['numsubscribes'],

							'category'				=>	$post['categories'][key($post['categories'])],		

							'user'					=>	$post['user'],
						);

			if ($fields) {
				foreach ($fields as $key => $field) {
					$result	=	array_merge($result, self::renderFieldToPrototype($field));
				}				
			}
			return $result;
		}
	}
	
?>