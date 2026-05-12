<?php
/**
 * 19 迁移、配置与环境
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 数据库配置
 * Level: 配置
 * Note: 不要把真实数据库密码提交到公开仓库。
 * ========================================================= */
define( 'DB_NAME', 'database_name_here' );
define( 'DB_USER', 'username_here' );
define( 'DB_PASSWORD', 'password_here' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );


/* =========================================================
 * 2. 固定站点 URL
 * Level: 迁移
 * Note: 迁移后后台进不去时，可临时在 wp-config.php 中指定 URL。
 * ========================================================= */
define( 'WP_HOME', 'https://example.com' );
define( 'WP_SITEURL', 'https://example.com' );


/* =========================================================
 * 3. 环境类型
 * Level: 配置
 * Note: 不同环境使用不同调试策略。
 * ========================================================= */
define( 'WP_ENVIRONMENT_TYPE', 'development' ); // local, development, staging, production

if ( 'production' !== wp_get_environment_type() ) {
    define( 'WP_DEBUG', true );
    define( 'WP_DEBUG_LOG', true );
    define( 'WP_DEBUG_DISPLAY', false );
}


/* =========================================================
 * 4. 提高内存限制
 * Level: 配置
 * Note: 是否生效还取决于主机和 PHP 配置。
 * ========================================================= */
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );


/* =========================================================
 * 5. 禁用文件编辑器
 * Level: 安全
 * Note: 生产站建议禁用后台主题/插件编辑器。
 * ========================================================= */
define( 'DISALLOW_FILE_EDIT', true );


/* =========================================================
 * 6. 迁移后 WP-CLI 替换域名示例
 * Level: 运维
 * Note: 先 dry-run 检查。WP-CLI 能正确处理序列化数据。
 * ========================================================= */
wp search-replace 'https://old-domain.com' 'https://new-domain.com' --skip-columns=guid --dry-run

wp search-replace 'https://old-domain.com' 'https://new-domain.com' --skip-columns=guid


/* =========================================================
 * 7. 维护模式文件
 * Level: 运维
 * Note: 根目录存在 .maintenance 时，WordPress 会进入维护模式。更新失败时可检查是否残留该文件。
 * ========================================================= */
// .maintenance 文件示例
$upgrading = time();
