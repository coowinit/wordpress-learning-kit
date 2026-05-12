<?php
/**
 * 12 Customizer 与 Settings API
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 添加 Customizer 文本设置
 * Level: 基础
 * Note: Customizer 适合主题外观相关设置。
 * ========================================================= */
function mysite_customize_register( $wp_customize ) {
    $wp_customize->add_setting( 'mysite_footer_text', array(
        'default'           => '© My Website',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'mysite_footer_text', array(
        'label'   => '页脚版权文字',
        'section' => 'title_tagline',
        'type'    => 'text',
    ) );
}
add_action( 'customize_register', 'mysite_customize_register' );


/* =========================================================
 * 2. 前台输出 theme_mod
 * Level: 基础
 * Note: 前台输出必须 escape。
 * ========================================================= */
$footer_text = get_theme_mod( 'mysite_footer_text', '© My Website' );

echo '<p class="copyright">' . esc_html( $footer_text ) . '</p>';


/* =========================================================
 * 3. 添加颜色设置
 * Level: 实用
 * Note: 颜色值使用 sanitize_hex_color 更合适。
 * ========================================================= */
function mysite_customize_color( $wp_customize ) {
    $wp_customize->add_setting( 'mysite_brand_color', array(
        'default'           => '#1e3a8a',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'mysite_brand_color',
            array(
                'label'   => '品牌颜色',
                'section' => 'colors',
            )
        )
    );
}
add_action( 'customize_register', 'mysite_customize_color' );


/* =========================================================
 * 4. 输出动态 CSS
 * Level: 实用
 * Note: 输出 CSS 时也要确保值经过合适清理。
 * ========================================================= */
function mysite_output_customizer_css() {
    $color = get_theme_mod( 'mysite_brand_color', '#1e3a8a' );
    ?>
    <style>
        :root {
            --brand-color: <?php echo esc_html( $color ); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'mysite_output_customizer_css' );


/* =========================================================
 * 5. Settings API 保存 option
 * Level: 进阶
 * Note: option 更适合插件或功能设置；theme_mod 更偏主题外观。
 * ========================================================= */
function mysite_register_options() {
    register_setting( 'mysite_options', 'mysite_phone', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
    ) );
}
add_action( 'admin_init', 'mysite_register_options' );


/* =========================================================
 * 6. 读取 option 并输出
 * Level: 基础
 * Note: 电话链接 href 和显示文本要分别处理。
 * ========================================================= */
$phone = get_option( 'mysite_phone', '' );

if ( $phone ) {
    echo '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>';
}
