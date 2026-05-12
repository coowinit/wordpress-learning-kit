<?php
/**
 * 16 REST API 与 AJAX
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. admin-ajax 基础回调
 * Level: 基础
 * Note: admin-ajax 适合传统 WordPress AJAX。
 * ========================================================= */
function mysite_ajax_get_message() {
    check_ajax_referer( 'mysite_ajax_nonce', 'nonce' );

    wp_send_json_success( array(
        'message' => 'Hello from WordPress AJAX.',
    ) );
}
add_action( 'wp_ajax_mysite_get_message', 'mysite_ajax_get_message' );
add_action( 'wp_ajax_nopriv_mysite_get_message', 'mysite_ajax_get_message' );


/* =========================================================
 * 2. 前端 fetch 请求 admin-ajax
 * Level: 前端
 * Note: ajaxUrl 和 nonce 可通过 wp_localize_script 传递。
 * ========================================================= */
fetch(mysiteAjax.ajaxUrl, {
  method: 'POST',
  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
  body: new URLSearchParams({
    action: 'mysite_get_message',
    nonce: mysiteAjax.nonce
  })
})
.then(response => response.json())
.then(data => console.log(data));


/* =========================================================
 * 3. 注册 REST API 路由
 * Level: 进阶
 * Note: 公开 GET 接口可以 permission_callback 为 __return_true。
 * ========================================================= */
function mysite_register_rest_routes() {
    register_rest_route( 'mysite/v1', '/products', array(
        'methods'             => 'GET',
        'callback'            => 'mysite_rest_get_products',
        'permission_callback' => '__return_true',
    ) );
}
add_action( 'rest_api_init', 'mysite_register_rest_routes' );

function mysite_rest_get_products() {
    return rest_ensure_response( array(
        'items' => array(
            array( 'title' => 'Product 01' ),
        ),
    ) );
}


/* =========================================================
 * 4. REST 权限控制
 * Level: 安全
 * Note: 修改数据的 REST 接口必须有权限控制。
 * ========================================================= */
register_rest_route( 'mysite/v1', '/settings', array(
    'methods'  => 'POST',
    'callback' => 'mysite_rest_save_settings',
    'permission_callback' => function () {
        return current_user_can( 'manage_options' );
    },
) );


/* =========================================================
 * 5. REST 参数清理
 * Level: 安全
 * Note: REST 请求参数也需要 sanitize/validate。
 * ========================================================= */
function mysite_rest_save_settings( WP_REST_Request $request ) {
    $phone = sanitize_text_field( $request->get_param( 'phone' ) );

    update_option( 'mysite_phone', $phone );

    return rest_ensure_response( array(
        'success' => true,
    ) );
}


/* =========================================================
 * 6. 前端请求 REST API
 * Level: 前端
 * Note: REST API 更适合结构化数据接口。
 * ========================================================= */
fetch('/wp-json/mysite/v1/products')
  .then(response => response.json())
  .then(data => {
    console.log(data.items);
  });
