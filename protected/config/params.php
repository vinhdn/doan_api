<?php

// this contains the application parameters that can be maintained via GUI
return array(
	// this is displayed in the header section
	'title'=>'Đồ án 2015 API',
	// this is used in error pages
	'adminEmail'=>'vinhdn07@gmail.com',
	// number of posts displayed per page
	'postsPerPage'=>10,
	// maximum number of comments that can be displayed in recent comments portlet
	'recentCommentCount'=>10,
	// maximum number of tags that can be displayed in tag cloud portlet
	'tagCloudCount'=>20,
	// whether post comments need to be approved before published
	'commentNeedApproval'=>true,
	// the copyright information displayed in the footer section
	'copyrightInfo'=>'Copyright &copy; 2015 by @vinhdo.me.',
	/*----session----*/
		'SESSION_DURATION' => 3600*24*7,
		'SESSION_KEY_SYSTEM_CURRENT_POS_USER_PROFILE' => 'SESSION_KEY_SYSTEM_CURRENT_POS_USER_PROFILE',
		'SESSION_KEY_SYSTEM_CURRENT_PRODUCT_CATEGORY_PROFILE' => 'SESSION_KEY_SYSTEM_CURRENT_PRODUCT_CATEGORY_PROFILE',

		/* SYSTEM */
		'SESSION_KEY_SYSTEM_CURRENT_ADMIN_PROFILE' => 'SESSION_KEY_SYSTEM_CURRENT_ADMIN_PROFILE',
		'SESSION_KEY_SYSTEM_CURRENT_CLIENT_PROFILE' => 'SESSION_KEY_SYSTEM_CURRENT_CLIENT_PROFILE',

		/* CLIENT */
		'SESSION_KEY_CURRENT_PROJECT' => 'SESSION_KEY_CURRENT_PROJECT',
		'SESSION_KEY_CURRENT_VIEW_PROFILE_USER' => 'SESSION_KEY_CURRENT_VIEW_PROFILE_USER',
		'SESSION_KEY_CURRENT_VIEW_PROFILE_CLIENT' => 'SESSION_KEY_CURRENT_VIEW_PROFILE_CLIENT',

		'SESSION_TOAST_WELCOME_LOGIN' => 'SESSION_TOAST_WELCOME_LOGIN',

		/*----config----*/
		'ACCESS_TOKEN_LENGTH' => 30,
		'ID_LENGTH' => 10,
		'CURRENCY' => '$',

		'NUMBER_ENTITY_PER_PAGE_DEFAULT' => 10,	//size for each page in list view
		'NUMBER_TIMELINE_ENTITY_PER_PAGE_DEFAULT' => 3,

		'TRANSLATE_FILE' => 'TRANSLATE_FILE',


		/*----Key define----*/

		/* ------- date time ----- */
		'DATE_FORMAT' => 'Y/m/d H:i:s',
		'DATE_FORMAT_FOR_FILE' => 'Y_m_d_H_i_s',
		'DATE_FORMAT_DATE_ONLY' => 'd M Y',
		'DATE_FORMAT_TIMELINE' => 'd. M',

		'DATE_FORMAT_ANGULAR_DATE_ONLY' => 'd MMM yyyy',

		'CLIENT_FILES_PATH' => 'upload/user',
		'ASSETS_FOLDER' => '/var/www/html/doan/assets/images',
);
