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
