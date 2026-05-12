<?php
/**
 * 08 插件结构与模块化
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 最小插件文件
 * Level: 基础
 * Note: 插件头注释决定后台插件列表显示。
 * ========================================================= */
/**
 * Plugin Name: My Site Toolkit
 * Description: 网站自定义功能集合。
 * Version: 1.0.0
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'mysite_toolkit_init' );

function mysite_toolkit_init() {
    // 插件功能入口。
}


/* =========================================================
 * 2. 推荐插件目录结构
 * Level: 结构
 * Note: 代码多了以后要拆分文件，不要全部塞进一个 PHP。
 * ========================================================= */
my-site-toolkit/
├── my-site-toolkit.php
├── includes/
│   ├── helpers.php
│   ├── shortcodes.php
│   └── admin.php
├── assets/
│   ├── css/
│   └── js/
└── uninstall.php


/* =========================================================
 * 3. 加载 includes 文件
 * Level: 基础
 * Note: 定义 PATH 和 URL 常量能让文件引用更清晰。
 * ========================================================= */
define( 'MYSITE_TOOLKIT_PATH', plugin_dir_path( __FILE__ ) );
define( 'MYSITE_TOOLKIT_URL', plugin_dir_url( __FILE__ ) );

require_once MYSITE_TOOLKIT_PATH . 'includes/helpers.php';
require_once MYSITE_TOOLKIT_PATH . 'includes/shortcodes.php';
require_once MYSITE_TOOLKIT_PATH . 'includes/admin.php';


/* =========================================================
 * 4. 激活和停用 hook
 * Level: 维护
 * Note: 激活不是每次加载，停用也不是卸载。
 * ========================================================= */
function mysite_toolkit_activate() {
    // 创建默认选项、刷新 rewrite 等。
    update_option( 'mysite_toolkit_version', '1.0.0' );
}
register_activation_hook( __FILE__, 'mysite_toolkit_activate' );

function mysite_toolkit_deactivate() {
    // 停用时清理临时计划任务等。
}
register_deactivation_hook( __FILE__, 'mysite_toolkit_deactivate' );


/* =========================================================
 * 5. uninstall.php 清理数据
 * Level: 维护
 * Note: 只有用户删除插件时才会执行 uninstall.php。
 * ========================================================= */
// uninstall.php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'mysite_toolkit_version' );
delete_option( 'mysite_phone' );


/* =========================================================
 * 6. 类封装插件入口
 * Level: 进阶
 * Note: 类封装适合中大型插件，能减少全局函数冲突。
 * ========================================================= */
final class MySite_Toolkit {
    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
    }

    public function init() {
        // 初始化功能。
    }

    public function assets() {
        // 加载资源。
    }
}

new MySite_Toolkit();
