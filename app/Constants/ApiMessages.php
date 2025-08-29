<?php
namespace App\Constants;

class ApiMessages {

    //SUCCESS:
    public const REGISTER_SUCCESS = 'User registered successfully ✅';
    public const LOGIN_SUCCESS = 'Login successful ✅';
    public const LOGOUT_SUCCESS = 'Logout successful ✅';
    public const INDEX_CATEGORY_SUCCESS = 'Categories retrieved successfully ✅';
    public const STORE_CATEGORY_SUCCESS = 'Category created successfully ✅';
    public const SHOW_CATEGORY_SUCCESS = 'Category retrieved successfully ✅';
    public const UPDATE_CATEGORY_SUCCESS = 'Category updated successfully ✅';
    public const DELETE_CATEGORY_SUCCESS = 'Category deleted successfully ✅';
    public const RESTORE_CATEGORY_SUCCESS = 'Category restored successfully ✅';
    public const INDEX_ITEMS_SUCCESS = 'Items retrieved successfully ✅';
    public const STORE_ITEM_SUCCESS = 'Item created successfully ✅';
    public const SHOW_ITEM_SUCCESS = 'Item retrieved successfully ✅';
    public const UPDATE_ITEM_SUCCESS = 'Item updated successfully ✅';
    public const DELETE_ITEM_SUCCESS = 'Item deleted successfully ✅';
    public const RESTORE_ITEM_SUCCESS = 'Item restored successfully ✅';


    //ERRORS:
    public const REGISTER_ERROR = 'User registered Failed.⛔';

    public const LOGIN_ERROR = 'Login Failed.⛔';
    public const LOGIN_INVALID_USER_ERROR = 'Wrong Email or password.⛔';
    public const LOGOUT_ERROR = 'Logout Failed.⛔';
    public const UPDATE_CATEGORY_ERROR = 'Failed to update category ⛔';

}
