<?php

namespace App\Models\Shared;

class CustomConstants
{
    // -------------------------------------------------------------------------
    // Response Status Codes
    // -------------------------------------------------------------------------
    const RESPONSE_STATUS_SUCCESS                = 1;
    const RESPONSE_STATUS_FAILED                 = 2;
    const RESPONSE_STATUS_PAYLOAD_VALIDATION_FAIL = 3;
    const RESPONSE_STATUS_RECORD_NOT_FOUND       = 4;
    const RESPONSE_STATUS_RECORD_EXISTS          = 5;
    const RESPONSE_STATUS_UPDATE_FAILED          = 6;

    // -------------------------------------------------------------------------
    // Response Messages — Generic
    // -------------------------------------------------------------------------
    const RESPONSE_MESSAGE_SUCCESS = 'success';
    const RESPONSE_MESSAGE_FAILED  = 'failed';

    // -------------------------------------------------------------------------
    // Record Status
    // -------------------------------------------------------------------------
    const STATUS_ACTIVE    = 1;
    const STATUS_INACTIVE  = 2;
    const STATUS_PENDING   = 3;
    const STATUS_DELETED   = 4;
    const STATUS_BLOCKED   = 5;
    const STATUS_VERIFIED  = 6;
    const STATUS_CANCELLED = 7;
    const STATUS_APPROVED  = 8;
    const STATUS_REJECTED  = 9;
    const STATUS_DORMANT   = 10;

    // -------------------------------------------------------------------------
    // User Roles
    // -------------------------------------------------------------------------
    const ROLE_ADMIN   = 1;
    const ROLE_OWNER   = 2;
    const ROLE_MANAGER = 3;
    const ROLE_STAFF   = 4;

    // -------------------------------------------------------------------------
    // Table Prefixes
    // -------------------------------------------------------------------------
    const ITEMS_TBL_PREFIX = 'ITM';
    const STAFF_TBL_PREFIX = 'STF';
    const BUSINESS_TBL_PREFIX = 'BIZ';


    // -------------------------------------------------------------------------
    // Item Messages
    // -------------------------------------------------------------------------
    const ITEM_CREATED = 'Item added successfully.';
    const ITEM_UPDATED = 'Item updated successfully.';
    const ITEM_DELETED = 'Item removed successfully.';

    // -------------------------------------------------------------------------
    // Staff Messages
    // -------------------------------------------------------------------------
    const STAFF_CREATED = 'Staff member added successfully.';
    const STAFF_UPDATED = 'Staff member updated successfully.';
    const STAFF_DELETED = 'Staff member removed successfully.';

    // -------------------------------------------------------------------------
    // User Messages
    // -------------------------------------------------------------------------
    const USER_CREATED  = 'User account created successfully.';
    const USER_UPDATED  = 'User account updated successfully.';
    const USER_DELETED  = 'User account removed successfully.';

    // -------------------------------------------------------------------------
    // Business Messages
    // -------------------------------------------------------------------------
    const BUSINESS_CREATED = 'Business created successfully.';
    const BUSINESS_UPDATED = 'Business updated successfully.';
    const BUSINESS_DELETED = 'Business removed successfully.';
    const SALE_CREATED = 'Sales created successfully.';

    const ORDER_CREATED   = 'Order created successfully.';
    const ORDER_UPDATED   = 'Order updated successfully.';
    const ORDER_CANCELLED = 'Order cancelled successfully.';
    const ORDER_TBL_PREFIX = 'ORD';



    // -------------------------------------------------------------------------
    // Generic CRUD Messages
    // -------------------------------------------------------------------------
    const RECORD_CREATED = 'Record created successfully.';
    const RECORD_UPDATED = 'Record updated successfully.';
    const RECORD_DELETED = 'Record removed successfully.';

    // -------------------------------------------------------------------------
    // Auth Messages
    // -------------------------------------------------------------------------
    const LOGIN_SUCCESS  = 'Welcome back! You are now logged in.';
    const LOGOUT_SUCCESS = 'You have been logged out successfully.';
    const PASSWORD_RESET = 'Password reset successfully.';

    // -------------------------------------------------------------------------
    // Error Messages
    // -------------------------------------------------------------------------
    const SOMETHING_WENT_WRONG   = 'Something went wrong. Please try again.';
    const UNAUTHORIZED           = 'You are not authorized to perform this action.';
    const NOT_FOUND              = 'The requested resource was not found.';
    const RECORD_ALREADY_EXISTS  = 'Record already exists.';



    /**
     * Staff created with temporary password message
     *
     * @param string $password
     * @return string
     */
    public static function staffCreatedWithPassword(string $password): string
    {
        return "Staff member added. Temporary password: {$password}";
    }

    /**
     * Generic created message
     *
     * @param string $model
     * @return string
     */
    public static function created(string $model): string
    {
        return "{$model} created successfully.";
    }

    /**
     * Generic updated message
     *
     * @param string $model
     * @return string
     */
    public static function updated(string $model): string
    {
        return "{$model} updated successfully.";
    }

    /**
     * Generic deleted message
     *
     * @param string $model
     * @return string
     */
    public static function deleted(string $model): string
    {
        return "{$model} removed successfully.";
    }
}
