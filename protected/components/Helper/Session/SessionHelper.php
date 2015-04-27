<?php
class SessionHelper{
	
	public $navigations = array();
    public $apiNavigations = array();
	
	public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new SessionHelper();
        }
        return $inst;
    }

    /**
     * Private ctor so nobody else can instance it
     *
     */
    private function __construct()
    {

    }

    /* ----------------------------------------------- SESSION ----------------------------------------------- */
    /**
     * Get current visit pos user
     *
     */
    public function getCurrentVisitPosUserId(){
        return Yii::app()->session['SESSION_KEY_SYSTEM_CURRENT_POS_USER_PROFILE'];
    }

    /**
     * Set current visit pos user
     *
     */
    public function setCurrentVisitPosUserId($userId){
        Yii::app()->session[Yii::app()->params['SESSION_KEY_SYSTEM_CURRENT_POS_USER_PROFILE']] = $userId;
    }

    /**
     * Get current visit category
     *
     */
    public function getCurrentVisitProductCategoryId(){
        return Yii::app()->session['SESSION_KEY_SYSTEM_CURRENT_PRODUCT_CATEGORY_PROFILE'];
    }

    /**
     * Set current visit category
     *
     */
    public function setCurrentVisitProductCategoryId($categoryId){
        Yii::app()->session[Yii::app()->params['SESSION_KEY_SYSTEM_CURRENT_PRODUCT_CATEGORY_PROFILE']] = $categoryId;
    }

    /* ----------------------------------------------- SAVE PAGE NAVIGATION ----------------------------------------------- */
    
    /**
     * Load dashboard page
     *
     */
    public function loadDashboard(){
        $this->navigations = array();
        array_push($this->navigations, Yii::app()->params['NAV_POS_DASHBOARD_INDEX']);
    }

    /**
     * Load user management page
     *
     */
    public function loadUserManagement(){
        $this->navigations = array();
        array_push($this->navigations, Yii::app()->params['NAV_POS_ADMINISTRATION_INDEX']);
        array_push($this->navigations, Yii::app()->params['MENU_POS_USER_LIST_INDEX']);
    }

    public function loadUserProfile(){
        $this->navigations = array();
        array_push($this->navigations, Yii::app()->params['NAV_POS_ADMINISTRATION_INDEX']);
        array_push($this->navigations, Yii::app()->params['MENU_POS_USER_LIST_INDEX']);
    }

    public function loadSettingUserPermission(){
        $this->navigations = array();
        array_push($this->navigations, Yii::app()->params['NAV_POS_SETTING_INDEX']);
        array_push($this->navigations, Yii::app()->params['NAV_POS_SETTING_SYSTEM_INDEX']);
        array_push($this->navigations, Yii::app()->params['MENU_POS_USER_PERMISSION_INDEX']);
    }

    public function loadProductManagement(){
        $this->navigations = array();
        array_push($this->navigations, Yii::app()->params['NAV_POS_ADMINISTRATION_INDEX']);
        array_push($this->navigations, Yii::app()->params['MENU_POS_PRODUCT_INDEX']);
    }

    /* ----------------------------------------------- SAVE API PAGE NAVIGATION ----------------------------------------------- */
    public function loadLoginApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_AUTH_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_LOGIN_INDEX']);
    }

    public function loadLogoutApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_AUTH_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_LOGOUT_INDEX']);
    }

    public function loadListUserApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_USER_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_LIST_USER_INDEX']);
    }

    public function loadCreateUserApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_USER_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POS_USER_CREATE_INDEX']);
    }

    public function loadUpdateUserApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_USER_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_UPDATE_USER_INDEX']);
    }

    public function loadRemoveUsersApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_USER_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_REMOVE_USERS_INDEX']);
    }

    public function loadChangeUsersStateApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_USER_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_CHANGE_USERS_STATE_INDEX']);
    }

    public function loadUpdateUserAvatarApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_USER_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_UPDATE_USER_AVATAR_INDEX']);
    }

    public function loadListUserPermissionGroupApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_SETTING_PERMISSION_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_LIST_USER_PERMISSION_GROUP_INDEX']);
    }

    public function loadCreateUserPermissionGroupApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_SETTING_PERMISSION_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_CREATE_USER_PERMISSION_GROUP_INDEX']);
    }

    public function loadUpdateUserPermissionGroupApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_SETTING_PERMISSION_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_UPDATE_USER_PERMISSION_GROUP_INDEX']);
    }

    public function loadRemoveUserPermissionGroupApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_SETTING_PERMISSION_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_REMOVE_USER_PERMISSION_GROUP_INDEX']);
    }

    public function loadAddUserToPermissionGroupApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_SETTING_PERMISSION_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_ADD_USER_TO_PERMISSION_GROUP_INDEX']);
    }

    public function loadRemoveUserFromPermissionGroupApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_SETTING_PERMISSION_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_REMOVE_USER_FROM_PERMISSION_GROUP_INDEX']);
    }

    public function loadListPermissionGroupUsersApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_SETTING_PERMISSION_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_LIST_PERMISSION_GROUP_USERS_INDEX']);
    }

    public function loadFindUsersToAddToPermissionGroupApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_SETTING_PERMISSION_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_FIND_USERS_ADD_TO_PERMISSION_GROUP_INDEX']);
    }

    public function loadUsersOfPermissionGroupApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_SETTING_PERMISSION_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_USER_OF_PERMISSION_GROUP_INDEX']);
    }

    public function loadListProductCategoriesApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_CATEGORY_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_LIST_PRODUCT_CATEGORY_INDEX']);
    }

    public function loadNestListProductCategoriesApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_CATEGORY_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_NEST_LIST_PRODUCT_CATEGORY_INDEX']);
    }

    public function loadCreateProductCategoryApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_CATEGORY_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_CREATE_PRODUCT_CATEGORY_INDEX']);
    }

    public function loadUpdateProductCategoryApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_CATEGORY_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_UPDATE_PRODUCT_CATEGORY_INDEX']);
    }

    public function loadRemoveProductCategoriesApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_CATEGORY_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_REMOVE_PRODUCT_CATEGORY_INDEX']);
    }

    public function loadChangeProductCategoryImageApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_CATEGORY_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_UPDATE_PRODUCT_CATEGORY_IMAGE_INDEX']);
    }

    public function loadChangeProductCategoryOrdersApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_CATEGORY_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_UPDATE_PRODUCT_CATEGORY_ORDERS_INDEX']);
    }

    public function loadGetProductCategoryListForSelectAjax(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_CATEGORY_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_GET_CATEGORY_LIST_FOR_AJAX_SELECT_INDEX']);
    }

    public function loadListAllProductApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_LIST_ALL_PRODUCT_INDEX']);
    }

    public function loadListAllProductInCategoryApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_LIST_ALL_PRODUCT_IN_CATEGORY_INDEX']);
    }

    public function loadCreateProductApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_CREATE_PRODUCT_INDEX']);
    }

    public function loadUpdateProductApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_UPDATE_PRODUCT_INDEX']);
    }

    public function loadRemoveProductsApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_REMOVE_PRODUCTS_INDEX']);
    }

    public function loadUpdateProductImageApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_UPDATE_PRODUCT_IMAGE_INDEX']);
    }

    public function loadUpdateProductsStateApiPage(){
        $this->apiNavigations = array();
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['NAV_POSAPI_PRODUCT_SUB_PRODUCT_INDEX']);
        array_push($this->apiNavigations, Yii::app()->params['MENU_POSAPI_CHANGE_PRODUCTS_STATE_INDEX']);
    }
}