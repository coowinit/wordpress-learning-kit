<?php
/**
 * 11 Elementor 代码集成
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 判断 Elementor 是否启用
 * Level: 基础
 * Note: is_plugin_active 需要先引入 plugin.php。
 * ========================================================= */
include_once ABSPATH . 'wp-admin/includes/plugin.php';

if ( is_plugin_active( 'elementor/elementor.php' ) ) {
    // Elementor 已启用。
}


/* =========================================================
 * 2. 给 Elementor 页面使用短代码
 * Level: 实用
 * Note: 最稳定的集成方式之一：PHP 逻辑封装成 shortcode，Elementor 页面插入。
 * ========================================================= */
function mysite_quote_box_shortcode() {
    return '<div class="quote-box"><h3>Request a Quote</h3><p>Contact our team for project pricing.</p></div>';
}
add_shortcode( 'mysite_quote_box', 'mysite_quote_box_shortcode' );

// Elementor 的 Shortcode 小工具中使用：
// [mysite_quote_box]


/* =========================================================
 * 3. Elementor 前台加载额外脚本
 * Level: 实用
 * Note: 适合只在 Elementor 前台上下文添加增强脚本。
 * ========================================================= */
function mysite_elementor_frontend_assets() {
    wp_enqueue_script(
        'mysite-elementor-enhance',
        get_stylesheet_directory_uri() . '/assets/js/elementor-enhance.js',
        array(),
        '1.0.0',
        true
    );
}
add_action( 'elementor/frontend/after_enqueue_scripts', 'mysite_elementor_frontend_assets' );


/* =========================================================
 * 4. 在 body class 中标记 Elementor 页面
 * Level: 实用
 * Note: 可以给 Elementor 相关页面加样式标记。
 * ========================================================= */
function mysite_elementor_body_class( $classes ) {
    if ( did_action( 'elementor/loaded' ) && is_singular() ) {
        $classes[] = 'has-elementor-support';
    }

    return $classes;
}
add_filter( 'body_class', 'mysite_elementor_body_class' );


/* =========================================================
 * 5. 避免 Elementor 页面重复加载主题模块
 * Level: 性能
 * Note: 减少 Elementor 页面中不必要的主题脚本。
 * ========================================================= */
function mysite_skip_theme_slider_on_elementor() {
    if ( did_action( 'elementor/loaded' ) && is_page_template( 'elementor_header_footer' ) ) {
        return;
    }

    wp_enqueue_script(
        'mysite-slider',
        get_stylesheet_directory_uri() . '/assets/js/slider.js',
        array(),
        '1.0.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'mysite_skip_theme_slider_on_elementor' );


/* =========================================================
 * 6. Elementor 中输出动态联系信息
 * Level: 实用
 * Note: 后台设置 + shortcode 是让 Elementor 内容动态化的实用组合。
 * ========================================================= */
function mysite_phone_shortcode() {
    $phone = get_option( 'mysite_phone', '+61 0000 0000' );

    return '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>';
}
add_shortcode( 'mysite_phone', 'mysite_phone_shortcode' );
