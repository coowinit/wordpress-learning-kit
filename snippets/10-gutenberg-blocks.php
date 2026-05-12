<?php
/**
 * 10 Gutenberg 与区块支持
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 添加区块编辑器主题支持
 * Level: 基础
 * Note: 让编辑器体验更接近前台。
 * ========================================================= */
function mysite_block_editor_setup() {
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'responsive-embeds' );
    add_editor_style( 'assets/css/editor-style.css' );
}
add_action( 'after_setup_theme', 'mysite_block_editor_setup' );


/* =========================================================
 * 2. 注册区块样式
 * Level: 实用
 * Note: 可给核心区块增加自定义样式选项。
 * ========================================================= */
function mysite_register_block_styles() {
    register_block_style( 'core/button', array(
        'name'  => 'outline-dark',
        'label' => '深色描边按钮',
    ) );
}
add_action( 'init', 'mysite_register_block_styles' );


/* =========================================================
 * 3. 注册区块 Pattern
 * Level: 实用
 * Note: Pattern 适合给编辑人员提供可复用版式。
 * ========================================================= */
function mysite_register_patterns() {
    register_block_pattern(
        'mysite/hero-simple',
        array(
            'title'       => 'Simple Hero',
            'description' => '一个简单的首页 Hero 区块组合。',
            'content'     => '<!-- wp:heading --><h2>Build Better Outdoor Spaces</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Premium WPC products for modern projects.</p><!-- /wp:paragraph -->',
        )
    );
}
add_action( 'init', 'mysite_register_patterns' );


/* =========================================================
 * 4. 限制可用区块
 * Level: 维护
 * Note: 适合交付客户时减少误操作，但要谨慎限制。
 * ========================================================= */
function mysite_allowed_blocks( $allowed_blocks, $editor_context ) {
    return array(
        'core/paragraph',
        'core/heading',
        'core/image',
        'core/buttons',
        'core/columns',
    );
}
add_filter( 'allowed_block_types_all', 'mysite_allowed_blocks', 10, 2 );


/* =========================================================
 * 5. 给编辑器加载 JS
 * Level: 进阶
 * Note: 编辑器资源和前台资源要区分加载。
 * ========================================================= */
function mysite_block_editor_assets() {
    wp_enqueue_script(
        'mysite-editor',
        get_stylesheet_directory_uri() . '/assets/js/editor.js',
        array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
        '1.0.0',
        true
    );
}
add_action( 'enqueue_block_editor_assets', 'mysite_block_editor_assets' );


/* =========================================================
 * 6. theme.json 示例结构
 * Level: 结构
 * Note: 区块主题和现代主题常用 theme.json 管理全局样式能力。
 * ========================================================= */
{
  "version": 2,
  "settings": {
    "color": {
      "palette": [
        { "slug": "brand", "color": "#1e3a8a", "name": "Brand" }
      ]
    },
    "spacing": {
      "units": ["px", "rem", "%"]
    }
  }
}
