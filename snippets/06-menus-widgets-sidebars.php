<?php
/**
 * 06 菜单、小工具与侧边栏
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 注册多个菜单位置
 * Level: 基础
 * Note: 菜单位置 key 不要随意修改，否则后台分配可能失效。
 * ========================================================= */
function mysite_register_menus() {
    register_nav_menus( array(
        'primary' => '主导航',
        'mobile'  => '移动端导航',
        'footer'  => '页脚导航',
    ) );
}
add_action( 'after_setup_theme', 'mysite_register_menus' );


/* =========================================================
 * 2. 输出主导航菜单
 * Level: 基础
 * Note: fallback_cb=false 可以避免未设置菜单时输出默认页面列表。
 * ========================================================= */
wp_nav_menu( array(
    'theme_location' => 'primary',
    'container'      => 'nav',
    'container_class'=> 'site-nav',
    'menu_class'     => 'site-nav__list',
    'fallback_cb'    => false,
) );


/* =========================================================
 * 3. 给菜单项添加自定义 class
 * Level: 实用
 * Note: 过滤菜单 class 可以方便统一前端命名。
 * ========================================================= */
function mysite_nav_menu_css_class( $classes, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
        $classes[] = 'primary-menu__item';
    }

    return $classes;
}
add_filter( 'nav_menu_css_class', 'mysite_nav_menu_css_class', 10, 4 );


/* =========================================================
 * 4. 注册页脚小工具区域
 * Level: 基础
 * Note: 侧边栏不仅用于 sidebar，也常用于页脚模块。
 * ========================================================= */
function mysite_register_sidebars() {
    register_sidebar( array(
        'name'          => '页脚区域 1',
        'id'            => 'footer-1',
        'before_widget' => '<section class="footer-widget">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="footer-widget__title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'mysite_register_sidebars' );


/* =========================================================
 * 5. 模板中输出小工具区域
 * Level: 基础
 * Note: 输出前先判断 is_active_sidebar，避免空容器。
 * ========================================================= */
if ( is_active_sidebar( 'footer-1' ) ) : ?>
    <div class="footer-widgets">
        <?php dynamic_sidebar( 'footer-1' ); ?>
    </div>
<?php endif; ?>


/* =========================================================
 * 6. 根据页面显示不同菜单
 * Level: 实用
 * Note: 条件输出适合活动页、落地页或多语言/多地区站点。
 * ========================================================= */
$location = is_front_page() ? 'primary' : 'mobile';

wp_nav_menu( array(
    'theme_location' => $location,
    'container'      => 'nav',
) );
