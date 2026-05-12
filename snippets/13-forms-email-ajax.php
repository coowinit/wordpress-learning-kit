<?php
/**
 * 13 表单、邮件与 AJAX
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 表单 HTML：nonce 和 action
 * Level: 基础
 * Note: admin-post.php 适合处理普通表单提交。
 * ========================================================= */
<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
    <input type="hidden" name="action" value="mysite_contact_form">
    <?php wp_nonce_field( 'mysite_contact_form', 'mysite_contact_nonce' ); ?>

    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <textarea name="message" placeholder="Message"></textarea>
    <button type="submit">Submit</button>
</form>


/* =========================================================
 * 2. 处理 admin-post 表单
 * Level: 基础
 * Note: nopriv 用于未登录访客提交。
 * ========================================================= */
function mysite_handle_contact_form() {
    if ( ! isset( $_POST['mysite_contact_nonce'] ) || ! wp_verify_nonce( $_POST['mysite_contact_nonce'], 'mysite_contact_form' ) ) {
        wp_die( 'Invalid request.' );
    }

    $name    = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
    $email   = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

    wp_mail(
        get_option( 'admin_email' ),
        'New Contact Form Submission',
        "Name: {$name}\nEmail: {$email}\nMessage:\n{$message}"
    );

    wp_safe_redirect( home_url( '/thank-you/' ) );
    exit;
}
add_action( 'admin_post_mysite_contact_form', 'mysite_handle_contact_form' );
add_action( 'admin_post_nopriv_mysite_contact_form', 'mysite_handle_contact_form' );


/* =========================================================
 * 3. AJAX 表单处理
 * Level: 进阶
 * Note: AJAX 回调用 wp_send_json_success/error 返回 JSON。
 * ========================================================= */
function mysite_ajax_contact_form() {
    check_ajax_referer( 'mysite_ajax_nonce', 'nonce' );

    $name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';

    if ( empty( $name ) ) {
        wp_send_json_error( array( 'message' => 'Name is required.' ) );
    }

    wp_send_json_success( array( 'message' => 'Thanks, ' . $name ) );
}
add_action( 'wp_ajax_mysite_contact', 'mysite_ajax_contact_form' );
add_action( 'wp_ajax_nopriv_mysite_contact', 'mysite_ajax_contact_form' );


/* =========================================================
 * 4. 保存提交记录为 CPT
 * Level: 进阶
 * Note: 重要表单建议保存记录，避免只依赖邮件。
 * ========================================================= */
function mysite_save_submission( $name, $email, $message ) {
    $post_id = wp_insert_post( array(
        'post_type'   => 'contact_submission',
        'post_status' => 'private',
        'post_title'  => $name . ' - ' . current_time( 'mysql' ),
        'post_content'=> $message,
    ) );

    if ( $post_id && ! is_wp_error( $post_id ) ) {
        update_post_meta( $post_id, '_email', sanitize_email( $email ) );
    }

    return $post_id;
}


/* =========================================================
 * 5. 设置 HTML 邮件内容类型
 * Level: 邮件
 * Note: 发送后移除 filter，避免影响其他邮件。
 * ========================================================= */
function mysite_set_html_mail_content_type() {
    return 'text/html';
}

add_filter( 'wp_mail_content_type', 'mysite_set_html_mail_content_type' );

wp_mail(
    'admin@example.com',
    'HTML Email',
    '<h1>New Submission</h1><p>Message content...</p>'
);

remove_filter( 'wp_mail_content_type', 'mysite_set_html_mail_content_type' );


/* =========================================================
 * 6. 邮件头 Reply-To
 * Level: 邮件
 * Note: Reply-To 可以让销售直接回复客户邮箱。
 * ========================================================= */
$headers = array(
    'Content-Type: text/plain; charset=UTF-8',
    'Reply-To: Customer <customer@example.com>',
);

wp_mail(
    'sales@example.com',
    'New Quote Request',
    'Customer message...',
    $headers
);
