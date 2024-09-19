<?php
/*
Plugin Name: Student CRUD
Description: A plugin to manage CRUD operations for a Student table.
Version: 1.0
Author: Your Name
*/

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

class Student_CRUD_Plugin {
    
    public function __construct() {
        // Hook to create the student table upon plugin activation
        register_activation_hook(__FILE__, array($this, 'create_student_table'));
        add_action('admin_menu', array($this, 'student_crud_menu'));
    }

    // Create student table
    public function create_student_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'students';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            age smallint(3) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    // Add a menu in the WordPress admin
    public function student_crud_menu() {
        add_menu_page(
            'Student CRUD', 
            'Student CRUD', 
            'manage_options', 
            'student-crud', 
            array($this, 'student_crud_page')
        );
    }

    // Display student management page
    public function student_crud_page() {
        // Include the file that handles CRUD operations
        include_once plugin_dir_path(__FILE__) . 'admin/student-crud-page.php';
    }
}

// Initialize the plugin
new Student_CRUD_Plugin();
