<?php
/**
 * 18 性能、缓存与查询优化
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. Transient 缓存查询结果
 * Level: 缓存
 * Note: Transient 适合缓存不需要每次实时计算的数据。
 * ========================================================= */
function mysite_get_featured_posts_cached() {
    $cached = get_transient( 'mysite_featured_posts' );

    if ( false !== $cached ) {
        return $cached;
    }

    $posts = get_posts( array(
        'posts_per_page' => 5,
        'meta_key'       => '_featured',
        'meta_value'     => '1',
    ) );

    set_transient( 'mysite_featured_posts', $posts, HOUR_IN_SECONDS );

    return $posts;
}


/* =========================================================
 * 2. 保存文章时清理缓存
 * Level: 缓存
 * Note: 有缓存就要考虑什么时候失效。
 * ========================================================= */
function mysite_clear_featured_posts_cache() {
    delete_transient( 'mysite_featured_posts' );
}
add_action( 'save_post', 'mysite_clear_featured_posts_cache' );


/* =========================================================
 * 3. 优化 WP_Query 参数
 * Level: 查询
 * Note: 不需要分页、不需要 meta/term 时，可以减少查询负担。
 * ========================================================= */
$q = new WP_Query( array(
    'post_type'              => 'post',
    'posts_per_page'         => 6,
    'no_found_rows'          => true,
    'ignore_sticky_posts'    => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
) );


/* =========================================================
 * 4. 只在需要页面加载重资源
 * Level: 性能
 * Note: 地图、轮播、动画脚本都适合按需加载。
 * ========================================================= */
function mysite_load_map_assets() {
    if ( ! is_page( 'contact' ) ) {
        return;
    }

    wp_enqueue_script( 'mysite-map', get_stylesheet_directory_uri() . '/assets/js/map.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'mysite_load_map_assets' );


/* =========================================================
 * 5. 移除 emoji 脚本
 * Level: 性能
 * Note: 适合不使用 emoji 功能的网站。
 * ========================================================= */
function mysite_disable_emoji_assets() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'mysite_disable_emoji_assets' );


/* =========================================================
 * 6. 调整 Heartbeat 频率
 * Level: 后台
 * Note: Heartbeat 影响后台自动保存、锁定等功能，不要盲目关闭。
 * ========================================================= */
function mysite_heartbeat_settings( $settings ) {
    $settings['interval'] = 60;
    return $settings;
}
add_filter( 'heartbeat_settings', 'mysite_heartbeat_settings' );
