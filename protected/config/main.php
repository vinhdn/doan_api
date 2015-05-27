<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Đồ án 2015 API',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.Helper.Api.*',
		'application.components.Helper.gcm.*',
		'ext.giix-components.*', // giix components
		'ext.YiiMailer.YiiMailer',
		'ext.xupload.XUpload',
		'application.components.Helper.Date.*',
		'application.components.Helper.String.*',
		'application.components.Helper.Json.*',
		'application.components.Helper.Validator.*',
		'application.components.Helper.Email.*',
		'application.components.Helper.Permission.*',
		'application.components.Helper.File.*',
		'application.components.Helper.Array.*',
		'application.components.Helper.Currency.*',
		'application.components.Helper.Model.*',
		'application.components.Helper.HttpResponse.*',
		'application.components.Helper.EncryptHelper.*',
		'application.components.Helper.Image.*',
		'application.components.Helper.Site.*',
		'application.components.Helper.Session.*',
		'application.components.Helper.Geo.*',
		'application.components.Helper.Ssh.*',
		'application.components.Helper.Xml.*',
		'application.components.Helper.ServerInfo.*',
		'application.components.Helper.Excel.*',
		'application.components.Helper.Database.Sql.*',
		'ext.Paginator.*', // giix components
		'ext.phpexcel.XPHPExcel',
	),
	'modules'=>array(
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'12345678',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			'newFileMode'=>0666,
			'newDirMode'=>0777,
			'generatorPaths' => array(
					'ext.giix-core', // giix generators
					),
			),
		'testapi','admin'
	),
	'defaultController'=>'user',

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:protected/data/blog.db',
			'tablePrefix' => 'tbl_',
		),*/
		// uncomment the following to use a MySQL database

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=nearby',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => '',
		),

		// 'db'=>array(
		// 	'connectionString' => 'mysql:host=localhost;dbname=elistenaz',
		// 	'emulatePrepare' => true,
		// 	'username' => 'root',
		// 	'password' => 'damthatbai2015',
		// 	'charset' => 'utf8',
		// 	'tablePrefix' => '',
		// ),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'post/<id:\d+>/<title:.*?>'=>'post/view',
				'posts/<tag:.*?>'=>'post/index',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
			),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
