<?php
/**
 * 03 模板、Loop 与条件判断
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 标准 Loop 输出文章
 * Level: 基础
 * Note: 主循环用于输出当前页面查询结果。
 * ========================================================= */
if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class(); ?>>
            <h2><?php the_title(); ?></h2>
            <div><?php the_excerpt(); ?></div>
        </article>
    <?php endwhile; ?>
<?php else : ?>
    <p>暂无内容。</p>
<?php endif; ?>


/* =========================================================
 * 2. 自定义 WP_Query 输出 3 篇文章
 * Level: 基础
 * Note: 自定义查询结束后必须 wp_reset_postdata()。
 * ========================================================= */
$query = new WP_Query( array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
) );

if ( $query->have_posts() ) :
    while ( $query->have_posts() ) :
        $query->the_post();
        the_title( '<h3>', '</h3>' );
    endwhile;
    wp_reset_postdata();
endif;


/* =========================================================
 * 3. 按分类 slug 查询文章
 * Level: 实用
 * Note: category_name 使用分类 slug，不是分类显示名称。
 * ========================================================= */
$news = new WP_Query( array(
    'category_name'  => 'company-news',
    'posts_per_page' => 6,
) );


/* =========================================================
 * 4. 条件判断：不同页面输出不同内容
 * Level: 实用
 * Note: 条件标签适合控制模板中的局部差异。
 * ========================================================= */
if ( is_front_page() ) {
    echo '<p>这里是首页。</p>';
} elseif ( is_page( 'contact' ) ) {
    echo '<p>这里是联系页面。</p>';
} elseif ( is_single() ) {
    echo '<p>这里是文章详情页。</p>';
}


/* =========================================================
 * 5. 拆分模板：get_template_part
 * Level: 实用
 * Note: 模板片段可以提高复用性，减少重复代码。
 * ========================================================= */
// 加载 template-parts/card-product.php
get_template_part( 'template-parts/card', 'product' );

// 传递参数，WordPress 5.5+ 支持 args
get_template_part( 'template-parts/card', 'product', array(
    'label' => 'Featured',
) );


/* =========================================================
 * 6. 模板中安全输出自定义字段
 * Level: 安全
 * Note: 从数据库取出的内容也要根据输出位置进行转义。
 * ========================================================= */
$subtitle = get_post_meta( get_the_ID(), '_subtitle', true );

if ( $subtitle ) {
    echo '<p class="subtitle">' . esc_html( $subtitle ) . '</p>';
}
