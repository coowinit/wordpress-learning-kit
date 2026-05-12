<?php
/**
 * 17 SEO、Schema 与 wp_head
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 页面 meta description
 * Level: 基础
 * Note: 如果使用 SEO 插件，要避免重复输出。
 * ========================================================= */
function mysite_meta_description() {
    if ( is_singular() ) {
        $desc = get_the_excerpt();

        if ( $desc ) {
            echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $desc ) ) . '">' . "\n";
        }
    }
}
add_action( 'wp_head', 'mysite_meta_description' );


/* =========================================================
 * 2. Canonical URL
 * Level: SEO
 * Note: SEO 插件通常会处理 canonical，不要重复。
 * ========================================================= */
function mysite_canonical_url() {
    if ( is_singular() ) {
        echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'mysite_canonical_url' );


/* =========================================================
 * 3. Open Graph 基础信息
 * Level: SEO
 * Note: 社交分享信息可用 OG 标签控制。
 * ========================================================= */
function mysite_open_graph_tags() {
    if ( ! is_singular() ) {
        return;
    }

    echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '">' . "\n";

    if ( has_post_thumbnail() ) {
        echo '<meta property="og:image" content="' . esc_url( get_the_post_thumbnail_url( null, 'large' ) ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'mysite_open_graph_tags' );


/* =========================================================
 * 4. JSON-LD Organization 示例
 * Level: Schema
 * Note: JSON-LD 建议用 wp_json_encode 生成。
 * ========================================================= */
function mysite_organization_schema() {
    $data = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => get_bloginfo( 'name' ),
        'url'      => home_url( '/' ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
}
add_action( 'wp_head', 'mysite_organization_schema' );


/* =========================================================
 * 5. 修改文档标题 parts
 * Level: SEO
 * Note: title-tag 主题支持开启后，可以过滤文档标题 parts。
 * ========================================================= */
function mysite_document_title_parts( $title ) {
    if ( is_front_page() ) {
        $title['tagline'] = 'Premium Outdoor Building Materials';
    }

    return $title;
}
add_filter( 'document_title_parts', 'mysite_document_title_parts' );


/* =========================================================
 * 6. 简单面包屑输出
 * Level: 导航
 * Note: 复杂项目建议用 SEO 插件或更完整的面包屑逻辑。
 * ========================================================= */
function mysite_breadcrumbs() {
    echo '<nav class="breadcrumbs"><a href="' . esc_url( home_url( '/' ) ) . '">Home</a>';

    if ( is_singular() ) {
        echo ' / <span>' . esc_html( get_the_title() ) . '</span>';
    }

    echo '</nav>';
}
