<?php
/**
 * 15 用户、角色与权限
 * Code snippets for learning and research.
 * Copy to a child theme or custom plugin only after testing.
 */


/* =========================================================
 * 1. 创建自定义角色
 * Level: 基础
 * Note: 角色创建适合放插件激活时，避免每次加载重复执行。
 * ========================================================= */
function mysite_add_sales_role() {
    add_role(
        'sales_manager',
        'Sales Manager',
        array(
            'read'         => true,
            'edit_posts'   => true,
            'upload_files' => true,
        )
    );
}
register_activation_hook( __FILE__, 'mysite_add_sales_role' );


/* =========================================================
 * 2. 给角色添加能力
 * Level: 实用
 * Note: 添加能力前要确认角色存在。
 * ========================================================= */
function mysite_add_cap_to_editor() {
    $role = get_role( 'editor' );

    if ( $role ) {
        $role->add_cap( 'manage_woocommerce' );
    }
}


/* =========================================================
 * 3. 检查用户能力
 * Level: 基础
 * Note: 不要只判断角色名，优先判断 capability。
 * ========================================================= */
if ( current_user_can( 'edit_posts' ) ) {
    echo '你可以编辑文章。';
}

if ( current_user_can( 'manage_options' ) ) {
    echo '你可以管理网站设置。';
}


/* =========================================================
 * 4. 限制非管理员进入后台
 * Level: 实用
 * Note: 注意不要影响 AJAX 请求。
 * ========================================================= */
function mysite_redirect_non_admins() {
    if ( is_admin() && ! current_user_can( 'edit_posts' ) && ! wp_doing_ajax() ) {
        wp_safe_redirect( home_url() );
        exit;
    }
}
add_action( 'admin_init', 'mysite_redirect_non_admins' );


/* =========================================================
 * 5. 添加用户资料字段
 * Level: 进阶
 * Note: 用户字段显示和保存需要分别写。
 * ========================================================= */
function mysite_user_profile_field( $user ) {
    ?>
    <h2>额外信息</h2>
    <table class="form-table">
        <tr>
            <th><label for="department">部门</label></th>
            <td><input type="text" name="department" value="<?php echo esc_attr( get_user_meta( $user->ID, 'department', true ) ); ?>"></td>
        </tr>
    </table>
    <?php
}
add_action( 'show_user_profile', 'mysite_user_profile_field' );
add_action( 'edit_user_profile', 'mysite_user_profile_field' );


/* =========================================================
 * 6. 保存用户资料字段
 * Level: 进阶
 * Note: 保存用户字段时必须检查 edit_user 权限。
 * ========================================================= */
function mysite_save_user_profile_field( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    if ( isset( $_POST['department'] ) ) {
        update_user_meta( $user_id, 'department', sanitize_text_field( $_POST['department'] ) );
    }
}
add_action( 'personal_options_update', 'mysite_save_user_profile_field' );
add_action( 'edit_user_profile_update', 'mysite_save_user_profile_field' );
