<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Ekhalti_payment_gateway
 * @subpackage Ekhalti_payment_gateway/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ekhalti_payment_gateway
 * @subpackage Ekhalti_payment_gateway/admin
 * @author     e-Khalti.com
 */
class Ekhalti_payment_gateway_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ekhalti_payment_gateway_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ekhalti_payment_gateway_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ekhalti_payment_gateway-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ekhalti_payment_gateway_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ekhalti_payment_gateway_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ekhalti_payment_gateway-admin.js', array('jquery'), $this->version, false);
    }

    function init() {
        $this->ekhalti_order_post_type();
        add_filter('post_row_actions', function($action, $post) {
            if ($post->post_type == 'ekhalti_order') {
                // Remove "Quick Edit"
                unset($actions['inline hide-if-no-js']);
            }
            return $actions;
        });

        add_filter('manage_ekhalti_order_posts_columns', array($this, 'custom_columns'));
        add_filter('manage_ekhalti_order_posts_custom_column', array($this, 'ekhalti_column'), 10, 2);
    }

    //Menu
    function admin_menu() {
        $page_title = 'E-khalti';
        $menu_title = 'E-khalti';
        $capability = 'edit_posts';
        $menu_slug = 'ekhalti-settings-page';
        $child_slug = 'ekhalti-settings-order';
        $child_capability = 'edit_pages';
        $function = array($this, 'ekhalti_settings_page');
        $icon_url = '';
        $position = 24;
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
        add_submenu_page($menu_slug, 'E-khalti Payment Orders', 'Orders', $capability, 'edit.php?post_type=ekhalti_order', NULL);
//        add_options_page('E-khalti Payment', 'E-khalti Payment', 'manage_options', 'ekhalti-settings-page', array($this, 'ekhalti_settings_page'));
//        add_action('edit_form_top', array($this, 'top_form_edit'));r
        add_action('edit_form_after_title', array($this, 'top_form_edit'));
        add_action('save_post', array($this, 'save_order_meta'), 10, 3);
    }

    function salcode_add_plugin_page_settings_link($links) {
        $links[] = '<a href="' .
                admin_url('admin.php?page=ekhalti-settings-page') .
                '">' . __('Settings') . '</a>';
        return $links;
    }

    function ekhalti_settings_page() {
        ?>
        <form action='options.php' method='post'>

            <h2>E-khalti Settings Page</h2>

            <?php
            settings_fields('ekhaltiPlugin');
            do_settings_sections('ekhaltiPlugin');
            submit_button();
            ?>

        </form>
        <?php
    }

    // Register Custom Post Type
    function ekhalti_order_post_type() {

        $labels = array(
            'name' => _x('Orders', 'Post Type General Name', 'text_domain'),
            'singular_name' => _x('Order', 'Post Type Singular Name', 'text_domain'),
            'menu_name' => __('Ekhaliti Types', 'text_domain'),
            'name_admin_bar' => __('Post Type', 'text_domain'),
            'archives' => __('Item Archives', 'text_domain'),
            'attributes' => __('Item Attributes', 'text_domain'),
            'parent_item_colon' => __('Parent Item:', 'text_domain'),
            'all_items' => __('All Items', 'text_domain'),
            'add_new_item' => __('Add New Item', 'text_domain'),
            'add_new' => __('Add New', 'text_domain'),
            'new_item' => __('New Order', 'text_domain'),
            'edit_item' => __('Edit Order', 'text_domain'),
            'update_item' => __('Update Order', 'text_domain'),
            'view_item' => __('View Order', 'text_domain'),
            'view_items' => __('View Orders', 'text_domain'),
            'search_items' => __('Search Order', 'text_domain'),
            'not_found' => __('Not found', 'text_domain'),
            'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
            'featured_image' => __('Featured Image', 'text_domain'),
            'set_featured_image' => __('Set featured image', 'text_domain'),
            'remove_featured_image' => __('Remove featured image', 'text_domain'),
            'use_featured_image' => __('Use as featured image', 'text_domain'),
            'insert_into_item' => __('Insert into item', 'text_domain'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
            'items_list' => __('Items list', 'text_domain'),
            'items_list_navigation' => __('Items list navigation', 'text_domain'),
            'filter_items_list' => __('Filter items list', 'text_domain'),
        );
        $args = array(
            'label' => __('Order', 'text_domain'),
            'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M1591 1448q56 89 21.5 152.5t-140.5 63.5h-1152q-106 0-140.5-63.5t21.5-152.5l503-793v-399h-64q-26 0-45-19t-19-45 19-45 45-19h512q26 0 45 19t19 45-19 45-45 19h-64v399zm-779-725l-272 429h712l-272-429-20-31v-436h-128v436z"/></svg>'),
            'description' => __('ekhalti orders', 'text_domain'),
            'labels' => $labels,
//            'supports' => array('title', 'editor', 'revisions', 'custom-fields', 'page-attributes'),
            'supports' => false,
            'taxonomies' => array(),
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'menu_position' => 20,
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => false,
            'map_meta_cap' => true,
            'capability_type' => 'post',
            'capabilities' => array(
                'create_posts' => false,
                'edit_posts' => true
            )
        );
        register_post_type('ekhalti_order', $args);
    }

    function admin_setting_init() {
        register_setting('ekhaltiPlugin', 'ekhalti_api_settings');
        add_settings_section(
                'ekhalti_api_ekhaltiPlugin_section', __('Our Section Title', 'wordpress'), array($this, 'ekhalti_api_settings_section_callback'), 'ekhaltiPlugin'
        );

        add_settings_field(
                'ekhalti_api_text_field_merchant_id', __('Merchant Id', 'wordpress'), array($this, 'ekhalti_api_text_field_merchant_id_render'), 'ekhaltiPlugin', 'ekhalti_api_ekhaltiPlugin_section'
        );
        add_settings_field(
                'ekhalti_api_text_field_merchant_key', __('Merchant Key', 'wordpress'), array($this, 'ekhalti_api_text_field_merchant_key_render'), 'ekhaltiPlugin', 'ekhalti_api_ekhaltiPlugin_section'
        );

        add_settings_field(
                'ekhalti_api_text_field_merchant_return_url', __('Return Url', 'wordpress'), array($this, 'ekhalti_api_text_field_merchant_return_url_render'), 'ekhaltiPlugin', 'ekhalti_api_ekhaltiPlugin_section'
        );

        add_settings_field(
                'ekhalti_api_text_field_merchant_fail_url', __('Fail Url', 'wordpress'), array($this, 'ekhalti_api_text_field_merchant_fail_url_render'), 'ekhaltiPlugin', 'ekhalti_api_ekhaltiPlugin_section'
        );
//        add_settings_field(
//                'ekhalti_api_select_field_1', __('Our Field 1 Title', 'wordpress'), 'ekhalti_api_select_field_1_render', 'ekhaltiPlugin', 'ekhalti_api_ekhaltiPlugin_section'
//        );
    }

    function ekhalti_api_text_field_merchant_id_render() {
        $options = get_option('ekhalti_api_settings');
        ?>
        <input type='text' name='ekhalti_api_settings[ekhalti_api_text_field_merchant_id]' value='<?php echo $options['ekhalti_api_text_field_merchant_id']; ?>'>
        <?php
    }

    function ekhalti_api_text_field_merchant_key_render() {
        $options = get_option('ekhalti_api_settings');
        ?>
        <input type='text' name='ekhalti_api_settings[ekhalti_api_text_field_merchant_key]' value='<?php echo $options['ekhalti_api_text_field_merchant_key']; ?>'>
        <?php
    }

    function ekhalti_api_text_field_merchant_return_url_render() {
        $options = get_option('ekhalti_api_settings');
        ?>
        <input type='text' name='ekhalti_api_settings[ekhalti_api_text_field_merchant_return_url]' value='<?php echo $options['ekhalti_api_text_field_merchant_return_url']; ?>'>
        <?php
    }

    function ekhalti_api_text_field_merchant_fail_url_render() {
        $options = get_option('ekhalti_api_settings');
        ?>
        <input type='text' name='ekhalti_api_settings[ekhalti_api_text_field_merchant_fail_url]' value='<?php echo $options['ekhalti_api_text_field_merchant_fail_url']; ?>'>
        <?php
    }

    private function modify_admin_menus() {
        global $submenu;

        if (array_key_exists('members', $submenu)) {

            foreach ($submenu['members'] as $key => $value) {
                $k = array_search('view_account_notes', $value);
                if ($k) {

                    $submenu['members'][$key][$k] = (current_user_can($submenu['members'][$key][1])) ?
                            admin_url('/edit.php?post_type=acct_notes') : "";
                }

                $l = array_search('new_account_note', $value);

                if ($l) {
                    $submenu['members'][$key][$l] = (current_user_can($submenu['members'][$key][1])) ?
                            admin_url('/post-new.php?post_type=dojo_acct_notes') : '';
                }
            }
        }
    }

    function top_form_edit($post) {
        if ('ekhalti_order' == $post->post_type) {
//            echo "<a href='#' id='my-custom-header-link'>$post->post_type</a>";
            $this->table_view($post);
        }
    }

    private function table_view($post) {
        ?>

        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>

                    <th id="cb" class="manage-column column-cb check-column" scope="col"></th>
                    <th id="columnname" class="manage-column column-columnname" scope="col"></th>
                    <th id="columnname" class="manage-column column-columnname num" scope="col"></th>

                </tr>
            </thead>

            <tfoot>
                <tr>

                    <th class="manage-column column-cb check-column" scope="col"></th>
                    <th class="manage-column column-columnname" scope="col"></th>
                    <th class="manage-column column-columnname num" scope="col"></th>

                </tr>
            </tfoot>

            <tbody>
                <tr class="alternate">
                    <th class="check-column" scope="row"></th>
                    <td class="column-columnname">Title</td>
                    <td class="column-columnname"><?php echo $post->post_title;        //print_r($post)                                              ?></td>
                </tr>
                <tr class="alternate">
                    <th class="check-column" scope="row"></th>
                    <td class="column-columnname">Description</td>
                    <td class="column-columnname"><?php echo $post->post_content;        //print_r($post)                                              ?></td>
                </tr>
                <tr class="alternate">
                    <th class="check-column" scope="row"></th>
                    <td class="column-columnname">price</td>
                    <td class="column-columnname"><?php echo get_post_meta($post->ID, 'price', true); ?></td>
                </tr>

                <tr>
                    <th class="check-column" scope="row"></th>
                    <td class="column-columnname">Reference</td>
                    <td class="column-columnname"><?php echo get_post_meta($post->ID, 'ref', true); ?></td>
                </tr>
                <tr>
                    <th class="check-column" scope="row"></th>
                    <td class="column-columnname">status</td>
                    <td class="column-columnname">
                        <select name="ekhalti_order_status">
                            <?php
                            $option = array(
                                0 => "Pending",
                                1 => "Processed",
                                2 => "Completed"
                            );
                            $order_status = get_post_meta($post->ID, 'order_status', true);
                            foreach ($option as $key => $value) {
                                $selected = ((int) $key == (int) $order_status) ? "selected" : "";
                                echo "<option value='$key' $selected >$value</option>";
                            }
                            ?>

                        </select>
                    </td>

                </tr>
                <!--                <tr class="alternate" valign="top">
                    <th class="check-column" scope="row"></th>
                    <td class="column-columnname">
                        <div class="row-actions">
                            <span><a href="#">Action</a> |</span>
                            <span><a href="#">Action</a></span>
                        </div>
                    </td>
                    <td class="column-columnname"></td>
                </tr>
                <tr valign="top">
                    <th class="check-column" scope="row"></th>
                    <td class="column-columnname">
                        <div class="row-actions">
                            <span><a href="#">Action</a> |</span>
                            <span><a href="#">Action</a></span>
                        </div>
                    </td>
                    <td class="column-columnname"></td>
                </tr>-->
            </tbody>
        </table>
        <?php
    }

    function save_order_meta($post_id, $post, $update) {

        /*
         * In production code, $slug should be set only once in the plugin,
         * preferably as a class property, rather than in each function that needs it.
         */
        $post_type = get_post_type($post_id);

        // If this isn't a 'book' post, don't update it.
        if ("ekhalti_order" != $post_type)
            return;

        // - Update the post's metadata.

        if (isset($_POST['ekhalti_order_status'])) {
            update_post_meta($post_id, 'order_status', sanitize_text_field($_POST['ekhalti_order_status']));
        }
    }

    /**
     * tables
     */
    function custom_columns($columns) {


        $columns = array(
            'cb' => $columns['cb'],
            'title' => __('Title'),
            'price' => __('Price', 'ekhalti'),
            'user' => __('User', 'ekhalti'),
            'item' => __('Item', 'ekhalti'),
            'status' => __('Status', 'ekhalti'),
            'ref' => __('Ref', 'ekhalti'),
            'time' => __('time', 'ekhalti'),
            'date' => __('Date', 'ekhalti'),
        );


        return $columns;
    }

    function ekhalti_column($column, $post_id) {


        // Price column
        if ('price' === $column) {
            $price = get_post_meta($post_id, 'price', true);


            echo $price;
        }

        // user column
        if ('user' === $column) {
            $user = get_post_meta($post_id, 'user', true);

            echo $price;
        }

        // time column
        if ('time' === $column) {
            $format = "g:i a, D, j F y";
            $datetime = get_the_date($format, $post_id);

            echo $datetime;
        }
        // Item column
        if ('item' === $column) {

            $title = get_post_field('post_content', $post_id);

            echo $title;
        }
        // Item column
        if ('ref' === $column) {

            $ref = get_post_meta($post_id, 'ref', true);

            echo "<small><i>$ref</i></small>";
        }
        // status column
        if ('status' === $column) {
            $status = get_post_meta($post_id, 'order_status', true);
            if ($status == 1) {
                echo "<b class='notice notice-error'>Processed</b>";
            } else if ($status == 2) {
                echo "<b class='notice notice-success'>Completed</b>";
            } else {
                echo "<b class='notice notice-error'>Pending</b>";
            }
        }
    }

}
