<?php
// config/permissions.php
return [
    'roles' => [
        //for admin
        1 => [
            //members
            'view_members' => true,
            'edit_members' => true,
            'edit_personal' => true,
            'reschedule_booking' => true,
            'pause_membership' => true,
            'cancel_subscription' => true,
            'payment_information' => true,
            'change_password' => true,
            // transactions
            'view_transactions' => true,
            // transactions
            'view_subscriptions' => true,
            'add_subscriptions' => true,
            'edit_subscriptions' => true,
            //products
            'view_products' => true,
            'add_products' => true,
            'archive_products' => true,
            'edit_products' => true,
            //products
            'view_products' => true,
            //service
            'view_services' => true,
            'add_services' => true,
            'archive_services' => true,
            'edit_services' => true,
            // setting
            'view_settings' => true,
            'edit_setting' => true,
            'edit_design' => true,
            'edit_promotion' => true,
            'edit_gateway' => true,
            'edit_notifications' => true,
            'add_user' => true,
            'delete_user' => true,
            'edit_user' => true,
        ],
        //for Contracter
        3 => [
            //members
            'view_members' => true,
            'edit_members' => true,
            'edit_personal' => true,
            'reschedule_booking' => true,
            'pause_membership' => true,
            'cancel_subscription' => true,
            'payment_information' => true,
            'change_password' => false,
            // transactions
            'view_transactions' => true,
            // subscriptions
            'view_subscriptions' => true,
            'add_subscriptions' => false,
            'edit_subscriptions' => false,
            //products
            'view_products' => true,
            'add_products' => false,
            'archive_products' => false,
            'edit_products' => false,
            //service
            'view_services' => true,
            'add_services' => false,
            'archive_services' => false,
            'edit_services' => false,
            // setting
            'view_settings' => true,
            'edit_setting' => false,
            'edit_design' => false,
            'edit_promotion' => false,
            'edit_gateway' => false,
            'edit_notifications' => false,
            'add_user' => false,
            'edit_user' => false,
            'delete_user' => false,
        ],
        //for staff
        4 => [
            //members
            'view_members' => true,
            'edit_members' => true,
            'edit_personal' => true,
            'reschedule_booking' => true,
            'pause_membership' => true,
            'cancel_subscription' => true,
            'payment_information' => true,
            'change_password' => false,
            // transactions
            'view_transactions' => true,
            // transactions
            'view_subscriptions' => true,
            'add_subscriptions' => false,
            'edit_subscriptions' => false,
            //products
            'view_products' => true,
            'add_products' => false,
            'archive_products' => false,
            'edit_products' => false,
            //service
            'view_services' => true,
            'add_services' => false,
            'archive_services' => false,
            'edit_services' => false,
            // setting
            'view_settings' => true,
            'edit_setting' => true,
            'edit_design' => false,
            'edit_promotion' => true,
            'edit_gateway' => false,
            'edit_notifications' => true,
            'add_user' => false,
            'edit_user' => false,
            'delete_user' => false,
        ],
        // Add more roles...
    ],
];