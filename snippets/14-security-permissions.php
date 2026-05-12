<?php
/**
 * 14 安全、权限与数据处理
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 权限检查
 * Level: 基础
 * Note: 后台敏感操作必须检查 capability。
 * ========================================================= */
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'You do not have permission.' );
}


/* =========================================================
 * 2. 创建和验证 nonce
 * Level: 基础
 * Note: nonce 防止跨站请求伪造，不替代权限检查。
 * ========================================================= */
// 输出
wp_nonce_field( 'delete_item_action', 'delete_item_nonce' );

// 验证
if ( ! isset( $_POST['delete_item_nonce'] ) || ! wp_verify_nonce( $_POST['delete_item_nonce'], 'delete_item_action' ) ) {
    wp_die( 'Invalid nonce.' );
}


/* =========================================================
 * 3. sanitize 输入
 * Level: 基础
 * Note: 不同类型数据使用不同清理函数。
 * ========================================================= */
$name  = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
$url   = isset( $_POST['url'] ) ? esc_url_raw( $_POST['url'] ) : '';
$text  = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';


/* =========================================================
 * 4. escape 输出
 * Level: 基础
 * Note: 输出到不同位置，用不同 escape 函数。
 * ========================================================= */
echo '<p>' . esc_html( $name ) . '</p>';
echo '<input value="' . esc_attr( $name ) . '">';
echo '<a href="' . esc_url( $url ) . '">Visit</a>';
echo wp_kses_post( $allowed_html_content );


/* =========================================================
 * 5. 验证白名单参数
 * Level: 实用
 * Note: 验证比清理更具体。白名单是很常用的安全方式。
 * ========================================================= */
$allowed_orderby = array( 'date', 'title', 'menu_order' );
$orderby = isset( $_GET['orderby'] ) ? sanitize_key( $_GET['orderby'] ) : 'date';

if ( ! in_array( $orderby, $allowed_orderby, true ) ) {
    $orderby = 'date';
}


/* =========================================================
 * 6. $wpdb prepare 查询
 * Level: 进阶
 * Note: 拼 SQL 时必须 prepare，不要直接拼接用户输入。
 * ========================================================= */
global $wpdb;

$email = 'customer@example.com';

$row = $wpdb->get_row(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->users} WHERE user_email = %s",
        $email
    )
);


/* =========================================================
 * 7. 安全删除操作流程
 * Level: 实用
 * Note: 删除操作至少要有权限、nonce、ID 类型处理和安全跳转。
 * ========================================================= */
function mysite_delete_item() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'No permission.' );
    }

    check_admin_referer( 'mysite_delete_item' );

    $item_id = isset( $_GET['item_id'] ) ? absint( $_GET['item_id'] ) : 0;

    if ( $item_id ) {
        wp_delete_post( $item_id, true );
    }

    wp_safe_redirect( admin_url( 'admin.php?page=mysite-items' ) );
    exit;
}
