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
 * 7. 作者归档页：自定义头像与作者简介
 * Level: 实用
 * Usage: 放入主题 author.php 的作者资料显示位置。
 * Note: 将示例作者 ID、图片路径替换成当前站点实际值；
 *       未映射的作者显示默认头像，图片文件需提前上传。
 *       作者简介读取用户资料 description；为空则不渲染段落。
 *       本片段面向作者归档页；不要把整个分类文件直接执行。
 * ========================================================= */
?>
<?php
// 在 author.php 作者归档模板中获取当前作者信息
$archive_author_id   = get_queried_object_id();
$archive_author_name = get_the_author_meta( 'display_name', $archive_author_id );
?>
<div class="author-profile__avatar">
    <?php
    // 作者 ID 与头像路径的映射关系（示例数据，请按实际网站修改）
    $author_avatars = [
        8  => '/wp-content/uploads/2024/06/kelin.webp',
        12 => '/wp-content/uploads/2024/06/galen.webp',
    ];

    // 未配置的作者使用默认头像
    $avatar_path = $author_avatars[ $archive_author_id ] ?? '/wp-content/uploads/2024/06/avatar-au.webp';
    $avatar_url  = home_url( $avatar_path );
    ?>
    <img class="author-avatar" src="<?php echo esc_url( $avatar_url ); ?>" alt="<?php echo esc_attr( $archive_author_name ); ?>" width="110" height="110" loading="eager" decoding="async">
</div>

<?php
// 作者简介为空时，不输出空的段落标签
$author_bio = get_the_author_meta( 'description', $archive_author_id );
if ( $author_bio ) : ?>
    <p class="author-profile">
        <?php echo esc_html( $author_bio ); ?>
    </p>
<?php endif; ?>
