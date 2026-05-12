<?php
/**
 * 01 Hooks 与 functions.php
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. Action：在 init 阶段注册逻辑
 * Level: 基础
 * Note: Action 是“到某个时机执行我的函数”。
 * ========================================================= */
function mysite_register_features() {
    // 适合注册 CPT、taxonomy、rewrite 等。
}
add_action( 'init', 'mysite_register_features' );


/* =========================================================
 * 2. Filter：修改文章标题
 * Level: 基础
 * Note: Filter 必须返回修改后的值。这里 accepted_args 为 2，所以回调接收两个参数。
 * ========================================================= */
function mysite_add_title_suffix( $title, $post_id ) {
    if ( is_admin() ) {
        return $title;
    }

    return $title . ' | EVODEK';
}
add_filter( 'the_title', 'mysite_add_title_suffix', 10, 2 );


/* =========================================================
 * 3. Hook 优先级：控制执行顺序
 * Level: 进阶
 * Note: 数字越小越早执行；相同优先级按注册顺序执行。
 * ========================================================= */
function mysite_early_task() {
    // 更早执行
}
add_action( 'wp_enqueue_scripts', 'mysite_early_task', 5 );

function mysite_late_task() {
    // 更晚执行
}
add_action( 'wp_enqueue_scripts', 'mysite_late_task', 30 );


/* =========================================================
 * 4. 移除 hook：取消某个回调
 * Level: 进阶
 * Note: remove_action 的 hook、函数名、优先级必须和添加时一致。
 * ========================================================= */
function mysite_remove_parent_theme_feature() {
    remove_action( 'wp_footer', 'parent_theme_footer_credit' );
}
add_action( 'after_setup_theme', 'mysite_remove_parent_theme_feature', 20 );


/* =========================================================
 * 5. 自定义 action：给自己的代码留扩展点
 * Level: 进阶
 * Note: 自定义 action 方便后期在不改原函数的情况下插入内容。
 * ========================================================= */
function mysite_render_product_card() {
    echo '<article class="product-card">';

    do_action( 'mysite_before_product_card_content' );

    echo '<h3>Product Title</h3>';

    do_action( 'mysite_after_product_card_content' );

    echo '</article>';
}

function mysite_add_badge() {
    echo '<span class="badge">New</span>';
}
add_action( 'mysite_before_product_card_content', 'mysite_add_badge' );


/* =========================================================
 * 6. 自定义 filter：让文字可被二次修改
 * Level: 进阶
 * Note: apply_filters 会把默认值交给外部回调修改。
 * ========================================================= */
function mysite_get_cta_text() {
    $text = 'Learn More';

    return apply_filters( 'mysite_cta_text', $text );
}

function mysite_change_cta_text( $text ) {
    return 'Get a Free Quote';
}
add_filter( 'mysite_cta_text', 'mysite_change_cta_text' );
