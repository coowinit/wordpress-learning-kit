# WordPress Learning Kit - Code Study Edition

一个偏向代码研究的 WordPress 学习包。

本版本在原 WordPress Learning Kit 的基础上进行了大幅扩展，把更多 WordPress 开发和维护中常用的代码知识点整理成 HTML 页面和 PHP snippets 文件，方便后期系统学习、检索和深入研究。

## 项目定位

这不是 WordPress 主题，也不是插件，而是一套静态 HTML 学习资料和代码片段集合。

适合用于：

- 学习 WordPress 代码基础
- 研究 hooks、主题、插件和模板
- 整理 functions.php 常用写法
- 建立 WordPress 维护代码手册
- 后期迁移到自己的代码片段库

## 本版本重点

- 20 个代码主题页面
- 120+ 个代码使用场景
- 每页多个代码片段
- 每个代码片段都有使用说明
- 额外提供 `snippets/` PHP 文件
- 适合用 VS Code 打开长期研究
- README 中不包含 GitHub 上传信息

## 文件结构

```text
wordpress-learning-kit-code-study/
├── index.html
├── 01-hooks-functions.html
├── 02-theme-setup-assets.html
├── 03-template-loop-conditions.html
├── 04-cpt-taxonomy.html
├── 05-media-images.html
├── 06-menus-widgets-sidebars.html
├── 07-admin-ui-settings.html
├── 08-plugin-architecture.html
├── 09-shortcodes-content.html
├── 10-gutenberg-blocks.html
├── 11-elementor-integration.html
├── 12-customizer-settings-api.html
├── 13-forms-email-ajax.html
├── 14-security-permissions.html
├── 15-users-roles-capabilities.html
├── 16-rest-api-ajax.html
├── 17-seo-schema-head.html
├── 18-performance-cache.html
├── 19-migration-config.html
├── 20-debug-testing-maintenance.html
├── css/
│   └── common.css
├── assets/
│   └── *.svg
├── snippets/
│   └── *.php
└── README.md
```

## 页面说明

| 页面 | 主题 | 代码数量 |
|---|---|---|
| `01-hooks-functions.html` | 01 Hooks 与 functions.php | 6 个代码场景 |
| `02-theme-setup-assets.html` | 02 主题初始化与资源加载 | 6 个代码场景 |
| `03-template-loop-conditions.html` | 03 模板、Loop 与条件判断 | 6 个代码场景 |
| `04-cpt-taxonomy.html` | 04 自定义文章类型与分类法 | 6 个代码场景 |
| `05-media-images.html` | 05 媒体、图片与文件 | 6 个代码场景 |
| `06-menus-widgets-sidebars.html` | 06 菜单、小工具与侧边栏 | 6 个代码场景 |
| `07-admin-ui-settings.html` | 07 后台 UI 与设置页 | 6 个代码场景 |
| `08-plugin-architecture.html` | 08 插件结构与模块化 | 6 个代码场景 |
| `09-shortcodes-content.html` | 09 短代码与内容组件 | 6 个代码场景 |
| `10-gutenberg-blocks.html` | 10 Gutenberg 与区块支持 | 6 个代码场景 |
| `11-elementor-integration.html` | 11 Elementor 代码集成 | 6 个代码场景 |
| `12-customizer-settings-api.html` | 12 Customizer 与 Settings API | 6 个代码场景 |
| `13-forms-email-ajax.html` | 13 表单、邮件与 AJAX | 6 个代码场景 |
| `14-security-permissions.html` | 14 安全、权限与数据处理 | 7 个代码场景 |
| `15-users-roles-capabilities.html` | 15 用户、角色与权限 | 6 个代码场景 |
| `16-rest-api-ajax.html` | 16 REST API 与 AJAX | 6 个代码场景 |
| `17-seo-schema-head.html` | 17 SEO、Schema 与 wp_head | 6 个代码场景 |
| `18-performance-cache.html` | 18 性能、缓存与查询优化 | 6 个代码场景 |
| `19-migration-config.html` | 19 迁移、配置与环境 | 7 个代码场景 |
| `20-debug-testing-maintenance.html` | 20 调试、测试与维护 | 8 个代码场景 |

## snippets 文件

所有页面中的代码片段也单独整理到了 PHP 文件中：

- `snippets/01-hooks-functions.php`
- `snippets/02-theme-setup-assets.php`
- `snippets/03-template-loop-conditions.php`
- `snippets/04-cpt-taxonomy.php`
- `snippets/05-media-images.php`
- `snippets/06-menus-widgets-sidebars.php`
- `snippets/07-admin-ui-settings.php`
- `snippets/08-plugin-architecture.php`
- `snippets/09-shortcodes-content.php`
- `snippets/10-gutenberg-blocks.php`
- `snippets/11-elementor-integration.php`
- `snippets/12-customizer-settings-api.php`
- `snippets/13-forms-email-ajax.php`
- `snippets/14-security-permissions.php`
- `snippets/15-users-roles-capabilities.php`
- `snippets/16-rest-api-ajax.php`
- `snippets/17-seo-schema-head.php`
- `snippets/18-performance-cache.php`
- `snippets/19-migration-config.php`
- `snippets/20-debug-testing-maintenance.php`（含紧急密码恢复、维护检查等示例）

## 实用维护案例

第 20 类新增了 **紧急重置 WordPress 管理员密码** 的临时备用示例，适用于无法通过常规方式恢复管理员账户的特殊情况。使用前请阅读代码注释和安全要求：

- 优先使用主机控制面板的密码恢复功能或 WP-CLI。
- 只有常规方式不可用时，才考虑临时加入主题 `functions.php`。
- 必须替换管理员登录名和长随机 Token，并通过表单输入新密码。
- 成功后立即删除临时代码，不得长期留在正式网站。
- 代码库只保存占位符示例，禁止提交真实密码、Token 或客户站点凭据。
- `wp_set_password()` 不返回 `WP_Error`，不能用 `is_wp_error()` 判断其执行结果。

对应文件：`snippets/20-debug-testing-maintenance.php`，并同步展示于 `20-debug-testing-maintenance.html`。

## 推荐学习顺序

```text
Hooks 与 functions.php
↓
主题初始化与资源加载
↓
模板、Loop 与条件判断
↓
自定义文章类型与分类法
↓
媒体、图片与文件
↓
菜单、小工具与侧边栏
↓
后台 UI 与设置页
↓
插件结构与模块化
↓
短代码与内容组件
↓
Gutenberg 与区块支持
↓
Elementor 代码集成
↓
Customizer 与 Settings API
↓
表单、邮件与 AJAX
↓
安全、权限与数据处理
↓
用户、角色与权限
↓
REST API 与 AJAX
↓
SEO、Schema 与 wp_head
↓
性能、缓存与查询优化
↓
迁移、配置与环境
↓
调试、测试与维护
```

## 重点代码能力

### Hooks

```php
add_action( 'init', 'my_function' );
add_filter( 'the_title', 'my_filter_function', 10, 2 );
```

### 资源加载

```php
add_action( 'wp_enqueue_scripts', 'my_enqueue_assets' );
```

### 自定义文章类型

```php
register_post_type( 'project', array(
    'public' => true,
    'has_archive' => true,
) );
```

### 短代码

```php
add_shortcode( 'my_shortcode', 'my_shortcode_callback' );
```

### 表单安全

```php
wp_nonce_field( 'my_action', 'my_nonce' );
sanitize_text_field( $_POST['name'] );
esc_html( $name );
```

### AJAX / REST

```php
add_action( 'wp_ajax_my_action', 'my_ajax_callback' );
register_rest_route( 'my/v1', '/items', array(
    'methods' => 'GET',
    'callback' => 'my_rest_callback',
) );
```

### 调试

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

## 如何使用

### 方法一：直接阅读

1. 下载并解压项目
2. 双击打开 `index.html`
3. 按编号阅读不同页面

### 方法二：用 VS Code 研究代码

1. 用 VS Code 打开整个文件夹
2. 阅读 HTML 页面
3. 打开 `snippets/` 文件夹查看 PHP 片段
4. 把需要测试的片段复制到子主题或自定义插件中
5. 在测试环境验证

## 重要提醒

本项目中的代码用于学习和研究。复制到正式网站前，请先备份，并优先在测试环境验证。

不要把以下信息提交到公开仓库：

- 数据库账号和密码
- SMTP 密码
- API Key
- 支付密钥
- 客户个人数据
- 服务器敏感路径
- wp-config.php 中的真实密钥

## 后续扩展方向

这套学习包后续可以继续扩展：

- WordPress Theme Development Kit
- WordPress Plugin Development Kit
- WordPress Security Snippets
- WordPress Performance Snippets
- WordPress Admin UI Examples
- WordPress REST API Examples
- WordPress WooCommerce Code Kit

## 技术栈

- HTML5
- CSS3
- PHP code snippets
- WordPress APIs
- Responsive Design
- SVG placeholder assets

## License

MIT License
