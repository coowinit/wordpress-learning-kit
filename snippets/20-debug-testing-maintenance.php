<?php
/**
 * 20 调试、测试与维护
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. wp-config 调试配置
 * Level: 调试
 * Note: 正式站不建议把错误直接显示给访客。
 * ========================================================= */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );


/* =========================================================
 * 2. 写入调试日志
 * Level: 调试
 * Note: 临时排查可用 error_log，修复后应清理过多日志。
 * ========================================================= */
error_log( 'Contact form handler started.' );

$data = array( 'name' => 'Test', 'status' => 'ok' );
error_log( print_r( $data, true ) );


/* =========================================================
 * 3. 处理 WP_Error
 * Level: 稳定
 * Note: 调用接口、插入数据、上传文件时都可能返回 WP_Error。
 * ========================================================= */
$response = wp_remote_get( 'https://example.com/api' );

if ( is_wp_error( $response ) ) {
    error_log( $response->get_error_message() );
    return;
}

$body = wp_remote_retrieve_body( $response );


/* =========================================================
 * 4. 注册计划任务
 * Level: 维护
 * Note: WP-Cron 依赖访问触发，不等同于系统 cron。
 * ========================================================= */
function mysite_schedule_daily_task() {
    if ( ! wp_next_scheduled( 'mysite_daily_cleanup' ) ) {
        wp_schedule_event( time(), 'daily', 'mysite_daily_cleanup' );
    }
}
add_action( 'wp', 'mysite_schedule_daily_task' );

function mysite_daily_cleanup_callback() {
    // 每日清理逻辑。
}
add_action( 'mysite_daily_cleanup', 'mysite_daily_cleanup_callback' );


/* =========================================================
 * 5. 停用插件时清理计划任务
 * Level: 维护
 * Note: 注册了计划任务，就要考虑停用时清理。
 * ========================================================= */
function mysite_clear_scheduled_tasks() {
    $timestamp = wp_next_scheduled( 'mysite_daily_cleanup' );

    if ( $timestamp ) {
        wp_unschedule_event( $timestamp, 'mysite_daily_cleanup' );
    }
}
register_deactivation_hook( __FILE__, 'mysite_clear_scheduled_tasks' );


/* =========================================================
 * 6. 维护模式短路输出
 * Level: 维护
 * Note: 维护模式要允许管理员访问，且返回合适状态码。
 * ========================================================= */
function mysite_simple_maintenance_mode() {
    if ( current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( get_option( 'mysite_maintenance_mode' ) ) {
        wp_die( '网站维护中，请稍后访问。', 'Maintenance', array( 'response' => 503 ) );
    }
}
add_action( 'template_redirect', 'mysite_simple_maintenance_mode' );


/* =========================================================
 * 7. 基础维护检查清单代码注释
 * Level: 维护
 * Note: 把维护流程写进项目文档，比只靠记忆更可靠。
 * ========================================================= */
/**
 * 每次更新前检查：
 * 1. 备份数据库和文件
 * 2. 记录当前 WordPress / 主题 / 插件版本
 * 3. 在测试环境更新
 * 4. 测试首页、表单、购物车、登录、搜索
 * 5. 查看 debug.log 是否有新错误
 */



/* =========================================================
 * 8. 紧急重置 WordPress 管理员密码（临时方案）
 * Level: 紧急维护
 * Note:
 * - 优先使用主机面板的密码重置功能或 WP-CLI。
 * - 仅在无法使用常规恢复方式时，短暂加入 functions.php。
 * - 必须替换管理员登录名和随机 Token；不要把真实密码写入公开仓库。
 * - 成功后立即从 functions.php 删除整段代码。
 * ========================================================= */

/*
 * 使用步骤：
 * 1. 将本段临时加入当前主题的 functions.php。
 * 2. 将下面两个占位符替换为真实管理员登录名和高强度随机 Token。
 * 3. 访问 https://example.com/?wem_reset_form=1
 * 4. 在表单中输入 Token 和新的强密码。
 * 5. 成功后立即删除 functions.php 中的本段代码。
 *
 * 注意：此代码是应急示例，不是长期部署功能。
 */

add_action( 'init', function () {

    $reset_token = 'REPLACE_WITH_A_LONG_RANDOM_ONE_TIME_TOKEN';
    $username    = 'REPLACE_WITH_ADMIN_USERNAME';

    // 先处理 POST，避免提交后再次显示表单。
    if (
        'POST' === $_SERVER['REQUEST_METHOD']
        && isset( $_POST['wem_reset_submit'] )
    ) {
        $submitted_token = isset( $_POST['wem_reset_token'] )
            ? (string) wp_unslash( $_POST['wem_reset_token'] )
            : '';

        if ( ! hash_equals( $reset_token, $submitted_token ) ) {
            wp_die( 'Invalid security token.', 'Password Reset', array( 'response' => 403 ) );
        }

        $new_password = isset( $_POST['wem_new_password'] )
            ? (string) wp_unslash( $_POST['wem_new_password'] )
            : '';

        if ( strlen( $new_password ) < 16 ) {
            wp_die( 'Use a password with at least 16 characters.', 'Password Reset', array( 'response' => 400 ) );
        }

        $user = get_user_by( 'login', $username );

        if ( ! $user || ! in_array( 'administrator', (array) $user->roles, true ) ) {
            wp_die( 'Administrator account not found.', 'Password Reset', array( 'response' => 404 ) );
        }

        // wp_set_password() updates the password and does not return WP_Error.
        wp_set_password( $new_password, $user->ID );

        wp_die(
            'Password reset request completed. Remove this temporary code from functions.php immediately, then sign in with the new password.',
            'Password Reset'
        );
    }

    // Only show the form on the explicit GET endpoint.
    if (
        'GET' === $_SERVER['REQUEST_METHOD']
        && isset( $_GET['wem_reset_form'] )
        && '1' === (string) $_GET['wem_reset_form']
    ) {
        $form = '
            <form method="post" autocomplete="off" style="max-width:440px;margin:80px auto;font-family:Arial,sans-serif;">
                <h2>Administrator Password Reset</h2>
                <p>Temporary security token</p>
                <input type="password" name="wem_reset_token" required autocomplete="off" style="width:100%;padding:12px;box-sizing:border-box;">
                <p>New password (minimum 16 characters)</p>
                <input type="password" name="wem_new_password" required minlength="16" autocomplete="new-password" style="width:100%;padding:12px;box-sizing:border-box;">
                <button type="submit" name="wem_reset_submit" value="1" style="margin-top:15px;padding:12px 20px;cursor:pointer;">
                    Reset Password
                </button>
            </form>
        ';

        wp_die( $form, 'Password Reset' );
    }

} );
