<?php
Yii::app()->
theme = 'api';
?>
<!--[if lt IE 7]>
<html lang="en" ng-app="apiApp" class="no-js lt-ie9 lt-ie8 lt-ie7">
  <![endif]-->
  <!--[if IE 7]>
  <html lang="en" ng-app="apiApp" class="no-js lt-ie9 lt-ie8">
    <![endif]-->
    <!--[if IE 8]>
    <html lang="en" ng-app="apiApp" class="no-js lt-ie9">
      <![endif]-->
      <!--[if gt IE 8]>
      <!-->
      <html lang="en" ng-app="apiApp" class="no-js">
        <!--<![endif]-->
        <html class="no-js">
          <head>
            <meta charset="utf-8">
            <title></title>
            <meta name="description" content="">
            <meta name="viewport" content="width=device-width">
            <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
            <!-- build:css styles/vendor.css -->
            <!-- bower:css -->
            <link rel="stylesheet" href="<?php echo Yii::app()->
              theme->baseUrl; ?>/bootstrap/dist/css/bootstrap.css" />
              <!-- endbower -->
              <!-- endbuild -->
            </head>
            <body>
              <!--[if lt IE 7]>
              <p class="browsehappy">
              You are using an <strong>outdated</strong>
              browser. Please
              <a href="http://browsehappy.com/">upgrade your browser</a>
              to improve your experience.
              </p>
              <![endif]-->
              <!-- Add your site or application content here -->
              <div class="container">
                <div class="header">
                  <h3 class="text-muted">English Listening A-Z Api Test</h3>
                </div>
                <hr >
                <div ng-view></div>
                <script type="text/ng-template" id="tpl.html">
                <div class="page page-dashboard">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="list-group">
                        <a
                          href="#/getListDanhmuc"
                          class="list-group-item"
                        ng-class="{'active' : apiFunction=='getListDanhmuc'}">Get List Danhmuc</a>
                        <a
                          href="#/getListMenu"
                          class="list-group-item"
                        ng-class="{'active' : apiFunction=='getListMenu'}">Get Menu of Danhmuc</a>
                        <a
                          href="#/getListSubMenu"
                          class="list-group-item"
                        ng-class="{'active' : apiFunction=='getListSubMenu'}">Get Submenu of Menu</a>
                        <a
                          href="#/getListLession"
                          class="list-group-item"
                        ng-class="{'active' : apiFunction=='getListLession'}">Get Lession of Submenu</a>

                      </div>
                    </div>
                    <div class="form-group col-md-9">
                      <section class="panel panel-primary">
                        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> API Request Inputs</strong></div>
                        <div class="panel-body">
                          <form class="form-horizontal">
                            <div class="form-body">
                              <div class="alert alert-danger"> <strong>Request Url:</strong>  {{requestAction}}
                              </div>
                              <div class="alert alert-danger">
                                <strong>Request Method:</strong>  GET or POST
                              </div>
                            
                            
                            <div class="form-group" ng-repeat="param in params">
                              <div class="col-md-1">
                                <span class="label label-danger" ng-if="param.required">Required</span>
                              </div>
                              <div class="col-md-3">
                                <input type="text" ng-model="param.name" placeholder="Param Name" class="form-control bold"></div>
                                <div class="col-md-7">
                                  <input ng-if="param.type == 'file'" file-model="param.value" type="file" ng-model="param.value" placeholder="Param value" class="form-control">
                                  <input ng-if="param.type == 'text'" type="text" ng-model="param.value" title="{{param.placeholder}}" placeholder="{{param.placeholder}}" class="form-control">
                                <select  ng-if="param.type == 'select'" ng-model="param.value" ng-options="data.name for data in topics.data" class="form-control"></select>
                              </div>
                              <div class="col-md-1">
                                <a class="text-danger" ng-click="removeParam($index)"><b>X</b></a>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-12">
                                <button class="btn btn-primary btn-sm pull-right" ng-click="addParam($event)">+ Add Param</button>
                              </div>
                            </div>
                          </div>
                          <div class="form-actions bottom fluid ">
                            <div class="col-md-offset-4 col-md-6">
                              <button class="btn btn-success" ng-click="excute()" >Submit</button>
                              <button class="btn btn-default" type="button">Cancel</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </section>
                    <div class="panel panel-danger">
                      <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Api Request Result</strong></div>
                      <div class="panel-body">
                        <strong>Request String:</strong>
                                                        <pre class="prettyprint" result="paramsRequested || 'Request will be displayed here'"><pre></div>
                                                        <div class="panel-body">
                                                            <strong>Response String:</strong> 
                                                        <pre class="prettyprint" result="results || 'Response from server will display here'"><pre></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="footer">
                                            <p><span class="glyphicon glyphicon-heart"></span> from the Vinhdo.me</p>
                                        </div>
                                    </div>
                                </div>
          </script>
                          </div>
        <!--[if lt IE 9]>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/es5-shim/es5-shim.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/json3/lib/json3.min.js"></script>
        <![endif]-->
        <!-- build:js scripts/vendor.js -->
        <!-- bower:js -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/jquery/dist/jquery.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/angular/angular.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/json3/lib/json3.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/bootstrap/dist/js/bootstrap.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/angular-resource/angular-resource.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/angular-cookies/angular-cookies.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/angular-sanitize/angular-sanitize.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/angular-animate/angular-animate.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/angular-touch/angular-touch.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/angular-route/angular-route.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/angular-bootstrap/ui-bootstrap-tpls.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/footable/css/footable.core.min.css"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/footable/footable.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/footable/footable.sort.min.js"></script>
        
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/footable/footable.paginate.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/footable/footable.filter.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/ui-bootstrap-tpls-0.12.0.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/ui-bootstrap-0.12.0.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/lib/angular-ui-router.min.js"></script>
        <!-- endbower -->
        <!-- endbuild -->
        <!-- build:js({.tmp,app}) scripts/scripts.js -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/scripts/app.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/scripts/apiCtrl.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/scripts/apiService.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/scripts/apiDirectives.js"></script>
        <!-- endbuild -->
            </body>
                </html>