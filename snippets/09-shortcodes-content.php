<?php
/**
 * 09 短代码与内容组件
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 基础短代码
 * Level: 基础
 * Note: 短代码回调必须 return，不建议直接 echo。
 * ========================================================= */
function mysite_button_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'text' => 'Learn More',
        'url'  => '#',
    ), $atts, 'mysite_button' );

    return '<a class="btn" href="' . esc_url( $atts['url'] ) . '">' . esc_html( $atts['text'] ) . '</a>';
}
add_shortcode( 'mysite_button', 'mysite_button_shortcode' );


/* =========================================================
 * 2. 包裹内容短代码
 * Level: 基础
 * Note: 包裹内容要处理 do_shortcode，允许内部短代码继续执行。
 * ========================================================= */
function mysite_notice_shortcode( $atts, $content = '' ) {
    $atts = shortcode_atts( array(
        'type' => 'info',
    ), $atts, 'mysite_notice' );

    return '<div class="notice notice-' . esc_attr( $atts['type'] ) . '">' . wp_kses_post( do_shortcode( $content ) ) . '</div>';
}
add_shortcode( 'mysite_notice', 'mysite_notice_shortcode' );

// 用法：[mysite_notice type="warning"]内容[/mysite_notice]


/* =========================================================
 * 3. 用 ob_start 写复杂 HTML
 * Level: 实用
 * Note: 复杂 HTML 用输出缓冲更容易维护。
 * ========================================================= */
function mysite_contact_card_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'title' => 'Contact Us',
        'email' => 'info@example.com',
    ), $atts, 'mysite_contact_card' );

    ob_start();
    ?>
    <div class="contact-card">
        <h3><?php echo esc_html( $atts['title'] ); ?></h3>
        <p><?php echo esc_html( $atts['email'] ); ?></p>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'mysite_contact_card', 'mysite_contact_card_shortcode' );


/* =========================================================
 * 4. 只在有短代码的页面加载 CSS
 * Level: 性能
 * Note: 短代码资源最好按需加载。
 * ========================================================= */
function mysite_shortcode_assets() {
    global $post;

    if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'mysite_contact_card' ) ) {
        wp_enqueue_style(
            'mysite-contact-card',
            plugin_dir_url( __FILE__ ) . 'assets/contact-card.css',
            array(),
            '1.0.0'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'mysite_shortcode_assets' );


/* =========================================================
 * 5. 短代码输出查询结果
 * Level: 进阶
 * Note: 短代码中写 WP_Query 后也要 reset。
 * ========================================================= */
function mysite_latest_posts_shortcode() {
    $q = new WP_Query( array(
        'posts_per_page' => 3,
        'post_status'    => 'publish',
    ) );

    ob_start();

    if ( $q->have_posts() ) {
        echo '<ul class="latest-posts">';
        while ( $q->have_posts() ) {
            $q->the_post();
            echo '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>';
        }
        echo '</ul>';
        wp_reset_postdata();
    }

    return ob_get_clean();
}
add_shortcode( 'mysite_latest_posts', 'mysite_latest_posts_shortcode' );


/* =========================================================
 * 6. 判断短代码是否已存在
 * Level: 兼容
 * Note: 避免和其他插件短代码同名覆盖。
 * ========================================================= */
if ( ! shortcode_exists( 'mysite_button' ) ) {
    add_shortcode( 'mysite_button', 'mysite_button_shortcode' );
}
