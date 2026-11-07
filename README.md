# WordPress Practical Code Library

面向 WordPress 开发与日常维护的实用代码知识库，整理常见 PHP snippets、主题与插件开发示例、安全处理方法及故障排查方案，支持按主题查阅、复制和二次验证。

> 本仓库是持续维护的代码参考库，不是可直接安装的 WordPress 主题或插件。代码片段需要结合具体项目环境检查后再使用。

## 项目用途

本仓库希望解决一个实际问题：把开发和维护过程中反复遇到、已经整理过的 WordPress 解决方案沉淀下来，避免每次重新搜索或从旧项目中翻找。

适合用于：

- 查找常见 WordPress API、Hooks 和 PHP 写法
- 复用主题、插件、模板和后台开发代码
- 处理表单、用户权限、媒体、SEO 和性能问题
- 保存经过项目实践后值得复用的维护方案
- 使用 VS Code 阅读、整理和维护 PHP 代码片段

## 内容概览

仓库目前包含 **20 个主题分类、120+ 个代码使用场景**，每个主题同时提供 HTML 阅读页面和独立 PHP snippets 文件。

| 编号 | 分类 | 主要内容 |
|---|---|---|
| 01 | Hooks 与 functions.php | Action、Filter、自定义 Hook |
| 02 | 主题初始化与资源加载 | 主题支持、菜单、CSS / JS |
| 03 | 模板、Loop 与条件判断 | 主循环、自定义查询、模板片段 |
| 04 | 自定义文章类型与分类法 | CPT、Taxonomy、归档 |
| 05 | 媒体、图片与文件 | 特色图、附件、ALT、上传 |
| 06 | 菜单、小工具与侧边栏 | 菜单注册、侧边栏、页脚 |
| 07 | 后台 UI 与设置页 | 后台菜单、Settings API、通知 |
| 08 | 插件结构与模块化 | 插件加载、激活停用、类封装 |
| 09 | 短代码与内容组件 | Shortcode、属性、输出缓冲 |
| 10 | Gutenberg 与区块支持 | 编辑器样式、Block、Pattern |
| 11 | Elementor 代码集成 | 模板判断、短代码、资源加载 |
| 12 | Customizer 与 Settings API | Theme Mod、Option、设置保存 |
| 13 | 表单、邮件与 AJAX | Nonce、验证、邮件、提交处理 |
| 14 | 安全、权限与数据处理 | Capability、Nonce、Sanitize、Escape |
| 15 | 用户、角色与权限 | 用户管理、角色、登录相关 Hook |
| 16 | REST API 与 AJAX | REST 路由、权限回调、前端请求 |
| 17 | SEO、Schema 与 wp_head | Meta、Canonical、JSON-LD |
| 18 | 性能、缓存与查询优化 | Transients、Object Cache、查询 |
| 19 | 迁移、配置与环境 | wp-config、URL、迁移、环境判断 |
| 20 | 调试、测试与维护 | Debug、日志、Cron、维护与恢复 |

## 在线阅读与源码

- [打开代码索引首页](index.html)
- [查看 20 类调试、测试与维护](20-debug-testing-maintenance.html)
- [浏览全部 PHP snippets](snippets/)

目录结构：

```text
wordpress-learning-kit/
├── index.html
├── 01-hooks-functions.html
├── 02-theme-setup-assets.html
├── ...
├── 20-debug-testing-maintenance.html
├── css/
│   └── common.css
├── assets/
│   └── *.svg
├── snippets/
│   ├── 01-hooks-functions.php
│   ├── ...
│   └── 20-debug-testing-maintenance.php
└── README.md
```

## 如何使用

### 1. 浏览 HTML 页面

下载仓库或克隆到本地，打开根目录的 `index.html`，通过分类页面阅读代码说明和示例。

### 2. 复制 PHP 片段

使用 VS Code 打开 `snippets/`，找到对应分类的 PHP 文件，复制需要的代码到子主题或自定义插件中。

### 3. 在项目中验证

复制前先阅读代码注释，确认适用的 WordPress 版本、运行位置、依赖和权限要求；先在本地或 Staging 环境测试，再考虑部署到正式网站。

## 代码片段维护规范

后续新增代码时，尽量遵循统一结构：

1. **名称与用途**：说明代码解决什么问题。
2. **适用场景**：说明适合主题、插件、后台还是临时维护。
3. **放置位置**：说明建议放在子主题、插件或特定模板中。
4. **完整代码**：提供必要的上下文，避免只保存无法独立理解的零散语句。
5. **使用步骤**：说明配置项、调用方式和验证方法。
6. **风险说明**：标注权限、数据修改、缓存、兼容性及回滚注意事项。

当一个案例同时有 HTML 阅读页面和 PHP 文件时，应同步维护两份内容，避免说明与源码不一致。

## 特别维护案例：管理员密码紧急恢复

第 20 类包含一个**临时管理员密码恢复示例**，仅作为常规恢复方式不可用时的备用参考。

- 优先使用主机控制面板提供的密码恢复功能或 WP-CLI。
- 只有常规方式不可用时，才考虑临时加入主题 `functions.php`。
- 使用前必须替换管理员登录名和长随机 Token，并通过表单输入新密码。
- 成功后立即删除临时代码；不得作为长期功能保留。
- 仓库只保存占位符示例，不保存真实密码、Token 或客户站点凭据。
- `wp_set_password()` 不返回 `WP_Error`，不能用 `is_wp_error()` 判断执行结果。

对应文件：

- `snippets/20-debug-testing-maintenance.php`
- `20-debug-testing-maintenance.html`

## 安全与使用边界

本仓库是代码参考资料，不代表所有片段都适用于每个 WordPress 项目。复制到正式网站前，请先备份，并在测试环境验证。

尤其注意：

- 不要将数据库密码、管理员密码、API Key、SMTP 凭据或客户数据提交到仓库。
- 不要把临时维护代码长期留在 `functions.php`。
- 涉及写入、删除、用户管理或敏感操作时，必须检查权限、Nonce、输入验证和输出转义。
- 不要直接复制示例中的域名、用户名、路径或配置值；应替换为当前项目的实际配置。
- 发现代码与当前 WordPress / PHP 版本不兼容时，应先修正并记录适用条件。

## 后续扩展方向

保持现有 20 个分类结构，优先补充真实项目中反复出现、经过验证且具有复用价值的代码。未来可逐步增加：

- WordPress 主题与插件常用模块
- WooCommerce 开发与维护
- 数据迁移和批量处理工具
- 安全加固与故障恢复
- 性能诊断与优化
- 常见第三方服务集成

不以代码数量为目标，优先保证示例准确、说明清楚、风险可控、后期容易维护。

## 技术构成

- HTML5 / CSS3：代码阅读页面
- PHP：WordPress 代码片段
- WordPress APIs：Hooks、Template、Admin、REST API 等
- SVG：页面辅助图形

## License

MIT License
