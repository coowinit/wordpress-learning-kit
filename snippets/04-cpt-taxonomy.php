<?php
/**
 * 04 自定义文章类型与分类法
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 注册 Projects 自定义文章类型
 * Level: 基础
 * Note: CPT 适合管理案例、项目、产品资料、团队成员等独立内容。
 * ========================================================= */
function mysite_register_project_cpt() {
    register_post_type( 'project', array(
        'labels' => array(
            'name'          => 'Projects',
            'singular_name' => 'Project',
        ),
        'public'       => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-portfolio',
        'show_in_rest' => true,
        'rewrite'      => array( 'slug' => 'projects' ),
        'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    ) );
}
add_action( 'init', 'mysite_register_project_cpt' );


/* =========================================================
 * 2. 给 CPT 注册分类法
 * Level: 基础
 * Note: hierarchical=true 更像分类；false 更像标签。
 * ========================================================= */
function mysite_register_project_taxonomy() {
    register_taxonomy( 'project_type', array( 'project' ), array(
        'labels' => array(
            'name' => 'Project Types',
        ),
        'public'       => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => array( 'slug' => 'project-type' ),
    ) );
}
add_action( 'init', 'mysite_register_project_taxonomy' );


/* =========================================================
 * 3. 查询 CPT 列表
 * Level: 实用
 * Note: CPT 查询和普通文章查询类似，关键是 post_type。
 * ========================================================= */
$projects = new WP_Query( array(
    'post_type'      => 'project',
    'posts_per_page' => 6,
) );

if ( $projects->have_posts() ) {
    while ( $projects->have_posts() ) {
        $projects->the_post();
        the_title( '<h3>', '</h3>' );
    }
    wp_reset_postdata();
}


/* =========================================================
 * 4. 按自定义分类法查询 CPT
 * Level: 实用
 * Note: tax_query 是项目筛选、案例分类、产品属性筛选的基础。
 * ========================================================= */
$decking_projects = new WP_Query( array(
    'post_type' => 'project',
    'tax_query' => array(
        array(
            'taxonomy' => 'project_type',
            'field'    => 'slug',
            'terms'    => 'decking',
        ),
    ),
) );


/* =========================================================
 * 5. 启用/停用插件时刷新 rewrite
 * Level: 维护
 * Note: 不要每次 init 都 flush_rewrite_rules，会影响性能。
 * ========================================================= */
function mysite_project_plugin_activate() {
    mysite_register_project_cpt();
    mysite_register_project_taxonomy();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mysite_project_plugin_activate' );

function mysite_project_plugin_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'mysite_project_plugin_deactivate' );


/* =========================================================
 * 6. 给 CPT 后台列表增加缩略图列
 * Level: 后台
 * Note: 后台列表列可以提升内容维护效率。
 * ========================================================= */
function mysite_project_columns( $columns ) {
    $columns['thumbnail'] = '缩略图';
    return $columns;
}
add_filter( 'manage_project_posts_columns', 'mysite_project_columns' );

function mysite_project_column_content( $column, $post_id ) {
    if ( 'thumbnail' === $column ) {
        echo get_the_post_thumbnail( $post_id, array( 80, 80 ) );
    }
}
add_action( 'manage_project_posts_custom_column', 'mysite_project_column_content', 10, 2 );
