<?php
/**
 * 02 主题初始化与资源加载
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 主题初始化：标题、特色图、菜单
 * Level: 基础
 * Note: after_setup_theme 适合写主题支持、菜单、图片尺寸等初始化代码。
 * ========================================================= */
function mysite_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );

    register_nav_menus( array(
        'primary' => '主导航',
        'footer'  => '页脚导航',
    ) );
}
add_action( 'after_setup_theme', 'mysite_theme_setup' );


/* =========================================================
 * 2. 加载主样式和脚本
 * Level: 基础
 * Note: 不要在 header.php 里硬写多个 script/link，统一用 enqueue。
 * ========================================================= */
function mysite_enqueue_assets() {
    wp_enqueue_style(
        'mysite-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );

    wp_enqueue_script(
        'mysite-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        array(),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'mysite_enqueue_assets' );


/* =========================================================
 * 3. 使用 filemtime 解决缓存问题
 * Level: 实用
 * Note: 开发阶段常用 filemtime，文件变化后版本号自动变化。
 * ========================================================= */
function mysite_enqueue_versioned_css() {
    $file = get_stylesheet_directory() . '/assets/css/site.css';

    wp_enqueue_style(
        'mysite-site',
        get_stylesheet_directory_uri() . '/assets/css/site.css',
        array(),
        file_exists( $file ) ? filemtime( $file ) : '1.0.0'
    );
}
add_action( 'wp_enqueue_scripts', 'mysite_enqueue_versioned_css' );


/* =========================================================
 * 4. 按需加载：只在联系页面加载表单 JS
 * Level: 性能
 * Note: 性能优化的关键是：不需要的资源不要加载。
 * ========================================================= */
function mysite_enqueue_contact_assets() {
    if ( ! is_page( 'contact' ) ) {
        return;
    }

    wp_enqueue_script(
        'mysite-contact',
        get_stylesheet_directory_uri() . '/assets/js/contact.js',
        array(),
        '1.0.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'mysite_enqueue_contact_assets' );


/* =========================================================
 * 5. 给 JavaScript 传递 ajax_url 和 nonce
 * Level: 实用
 * Note: wp_localize_script 常用于把 PHP 数据传给前端 JS。
 * ========================================================= */
function mysite_enqueue_ajax_script() {
    wp_enqueue_script(
        'mysite-ajax',
        get_stylesheet_directory_uri() . '/assets/js/ajax.js',
        array( 'jquery' ),
        '1.0.0',
        true
    );

    wp_localize_script( 'mysite-ajax', 'mysiteAjax', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'mysite_ajax_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'mysite_enqueue_ajax_script' );


/* =========================================================
 * 6. 禁用不需要的区块样式示例
 * Level: 性能
 * Note: 谨慎使用。禁用前要确认页面没有依赖对应样式。
 * ========================================================= */
function mysite_maybe_dequeue_block_styles() {
    if ( is_front_page() ) {
        wp_dequeue_style( 'wp-block-library' );
    }
}
add_action( 'wp_enqueue_scripts', 'mysite_maybe_dequeue_block_styles', 100 );
