<?php
/**
 * 05 媒体、图片与文件
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 启用特色图和自定义图片尺寸
 * Level: 基础
 * Note: 新增图片尺寸后，旧图通常需要重新生成缩略图。
 * ========================================================= */
function mysite_image_setup() {
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'hero-banner', 1600, 700, true );
    add_image_size( 'product-card', 600, 420, true );
}
add_action( 'after_setup_theme', 'mysite_image_setup' );


/* =========================================================
 * 2. 输出特色图并添加 class
 * Level: 基础
 * Note: the_post_thumbnail 可以传尺寸和属性数组。
 * ========================================================= */
if ( has_post_thumbnail() ) {
    the_post_thumbnail( 'product-card', array(
        'class' => 'product-card__image',
        'loading' => 'lazy',
    ) );
}


/* =========================================================
 * 3. 用 wp_get_attachment_image 输出图片
 * Level: 实用
 * Note: wp_get_attachment_image 会生成更完整的图片 HTML。
 * ========================================================= */
$image_id = get_post_thumbnail_id();

if ( $image_id ) {
    echo wp_get_attachment_image(
        $image_id,
        'hero-banner',
        false,
        array( 'class' => 'hero__image' )
    );
}


/* =========================================================
 * 4. 获取图片 Alt 文本
 * Level: SEO
 * Note: Alt 文本输出在属性中，要用 esc_attr。
 * ========================================================= */
$image_id = get_post_thumbnail_id();
$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

echo '<img src="' . esc_url( get_the_post_thumbnail_url( null, 'large' ) ) . '" alt="' . esc_attr( $alt ) . '">';


/* =========================================================
 * 5. 限制上传文件大小提示
 * Level: 后台
 * Note: 可以在媒体库后台给维护人员加提示。
 * ========================================================= */
function mysite_upload_size_notice() {
    $screen = get_current_screen();

    if ( 'upload' === $screen->id ) {
        echo '<div class="notice notice-info"><p>建议上传前压缩图片，避免超过 300KB。</p></div>';
    }
}
add_action( 'admin_notices', 'mysite_upload_size_notice' );


/* =========================================================
 * 6. 允许上传 SVG 示例
 * Level: 安全
 * Note: SVG 可能包含脚本风险，真实项目建议配合 SVG 清理库或专用插件。
 * ========================================================= */
function mysite_allow_svg_uploads( $mimes ) {
    if ( current_user_can( 'manage_options' ) ) {
        $mimes['svg'] = 'image/svg+xml';
    }

    return $mimes;
}
add_filter( 'upload_mimes', 'mysite_allow_svg_uploads' );


/* =========================================================
 * 7. 作者归档与博客详情页：自定义头像和简介
 * Level: 实用
 * Usage: 放入 author.php 或普通文章 single.php 对应的作者信息位置。
 * Note: 根据页面类型获取作者 ID；头像与简介各自独立判断。
 *       仅在有作者 ID 时生成头像 URL；没有简介时不输出段落。
 *       请调整作者 ID、图片路径，并确认图片已上传。
 *       简介类名为 author-profile__bio，注意与主题 CSS 对应。
 *       此代码是独立模板片段，不要直接执行整个分类文件。
 * ========================================================= */
?>
<?php
// 兼容作者归档页和普通文章详情页
$author_id = 0;

if ( is_author() ) {
    $author_id = get_queried_object_id();
} elseif ( is_singular( 'post' ) ) {
    $author_id = (int) get_post_field( 'post_author', get_queried_object_id() );
}

// 获取作者信息
$author_name = $author_id ? get_the_author_meta( 'display_name', $author_id ) : '';
$author_bio  = $author_id ? get_the_author_meta( 'description', $author_id ) : '';

// 作者头像映射（请按实际网站修改）
$author_avatars = [
    8  => '/wp-content/uploads/2024/06/kelin.webp',
    12 => '/wp-content/uploads/2024/06/galen.webp',
];
?>

<?php if ( $author_id ) : ?>
<div class="author-profile__avatar">
    <img class="author-avatar"
         src="<?php echo esc_url( home_url( $author_avatars[ $author_id ] ?? '/wp-content/uploads/2024/06/avatar-au.webp' ) ); ?>"
         alt="<?php echo esc_attr( $author_name ); ?>"
         width="110"
         height="110"
         loading="eager"
         decoding="async">
</div>
<?php endif; ?>

<?php if ( $author_bio ) : ?>
<p class="author-profile__bio">
    <?php echo esc_html( $author_bio ); ?>
</p>
<?php endif; ?>
