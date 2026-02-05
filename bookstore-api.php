<?php
/**
 * Plugin Name: Bookstore API
 * Description: A custom API for managing books in WordPress.
 * Version: 1.0
 * Author: Abdullah
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Test route
add_action('rest_api_init', function () {
    register_rest_route('bookstore/v1', '/test', array(
        'methods' => 'GET',
        'callback' => function () {
            return array('message' => 'Bookstore API is working!');
        },
    ));
});

// Create table on activation
register_activation_hook(__FILE__, 'bookstore_api_create_table');
function bookstore_api_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        author varchar(255) NOT NULL,
        price decimal(10,2) NOT NULL,
        isbn varchar(50) NOT NULL UNIQUE,
        published_date date NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// POST /books endpoint
add_action('rest_api_init', function () {
    register_rest_route('bookstore/v1', '/books', array(
        'methods'  => 'POST',
        'callback' => 'bookstore_add_book',
        'permission_callback' => '__return_true', // open for testing
    ));
});

function bookstore_add_book($request) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';

    if (empty($request->get_param('title')) || empty($request->get_param('author')) || empty($request->get_param('isbn'))) {
        return new WP_Error('missing_fields', 'Title, Author, and ISBN are required', array('status' => 400));
    }

    $data = array(
        'title'          => sanitize_text_field($request->get_param('title')),
        'author'         => sanitize_text_field($request->get_param('author')),
        'price'          => floatval($request->get_param('price')),
        'isbn'           => sanitize_text_field($request->get_param('isbn')),
        'published_date' => sanitize_text_field($request->get_param('publishedDate')),
    );

    $inserted = $wpdb->insert($table_name, $data);

    if ($inserted) {
        return array(
            'success' => true,
            'message' => 'Book added successfully',
            'id'      => $wpdb->insert_id
        );
    } else {
        return new WP_Error('insert_failed', 'Failed to add book', array('status' => 500));
    }
}

// GET /books endpoint
add_action('rest_api_init', function () {
    register_rest_route('bookstore/v1', '/books', array(
        'methods'  => 'GET',
        'callback' => 'bookstore_get_books',
        'permission_callback' => '__return_true',
    ));
});
function bookstore_get_books($request) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';

    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    if ($results) {
        return array(
            'success' => true,
            'books'   => $results
        );
    } else {
        return array(
            'success' => false,
            'message' => 'No books found'
        );
    }
}

// GET /books/{id}
add_action('rest_api_init', function () {
    register_rest_route('bookstore/v1', '/books/(?P<id>\d+)', array(
        'methods'  => 'GET',
        'callback' => 'bookstore_get_book',
        'permission_callback' => '__return_true',
    ));
});

function bookstore_get_book($request) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';
    $id = intval($request['id']);

    $book = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
    if ($book) {
        return array(
            'success' => true,
            'book'    => $book
        );
    } else {
        return new WP_Error('not_found', 'Book not found', array('status' => 404));
    }
}

// PUT /books/{id}
add_action('rest_api_init', function () {
    register_rest_route('bookstore/v1', '/books/(?P<id>\d+)', array(
        'methods'  => 'PUT',
        'callback' => 'bookstore_update_book',
        'permission_callback' => '__return_true', // open for testing
    ));
});

function bookstore_update_book($request) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';
    $id = intval($request['id']);

    $data = array(
        'title'          => sanitize_text_field($request->get_param('title')),
        'author'         => sanitize_text_field($request->get_param('author')),
        'price'          => floatval($request->get_param('price')),
        'isbn'           => sanitize_text_field($request->get_param('isbn')),
        'published_date' => sanitize_text_field($request->get_param('publishedDate')),
    );

    $updated = $wpdb->update($table_name, $data, array('id' => $id));

    if ($updated !== false) {
        return array(
            'success' => true,
            'message' => 'Book updated successfully'
        );
    } else {
        return new WP_Error('update_failed', 'Failed to update book', array('status' => 500));
    }
}

// DELETE /books/{id}
add_action('rest_api_init', function () {
    register_rest_route('bookstore/v1', '/books/(?P<id>\d+)', array(
        'methods'  => 'DELETE',
        'callback' => 'bookstore_delete_book',
        'permission_callback' => '__return_true', // open for testing
    ));
});

function bookstore_delete_book($request) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';
    $id = intval($request['id']);

    $deleted = $wpdb->delete($table_name, array('id' => $id));

    if ($deleted !== false) {
        return array(
            'success' => true,
            'message' => 'Book deleted successfully'
        );
    } else {
        return new WP_Error('deleted_failed', 'Failed to delete book', array('status' => 500));
    }
}
