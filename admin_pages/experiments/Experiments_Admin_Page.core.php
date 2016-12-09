<?php
if (! defined('EVENT_ESPRESSO_VERSION')) {
    exit('NO direct script access allowed');
}

/**
 *                  Experiments_Admin_Page
 *                  This contains the logic for setting up the Help and Support related admin pages.  Any methods
 *                  without phpdoc comments have inline docs with parent class.
 * @package         Experiments_Admin_Page
 * @subpackage      includes/core/admin/support/Experiments_Admin_Page.core.php
 * @author          Darren Ethier
 *                  ------------------------------------------------------------------------
 */
class Experiments_Admin_Page extends EE_Admin_Page
{


    protected function _init_page_props()
    {
        $this->page_slug        = EE_EXPERIMENTS_SLUG;
        $this->page_label       = __('Experiments', 'event_espresso');
        $this->_admin_base_url  = EE_EXPERIMENTS_ADMIN_URL;
        $this->_admin_base_path = EE_EXPERIMENTS_ADMIN;
    }


    protected function _ajax_hooks()
    {
    }


    protected function _define_page_props()
    {
        $this->_labels           = array();
        $this->_admin_page_title = $this->page_label;
    }


    protected function _set_page_routes()
    {
        $this->_page_routes = array(
            'default'    => array(
                'func'       => '_view_experimentation',
                'capability' => 'ee_read_ee',
            ),
        );
    }


    protected function _set_page_config()
    {
        $this->_page_config = array(
            'default'    => array(
                'nav'           => array(
                    'label' => __('Vue Experimentation', 'event_espresso'),
                    'order' => 30,
                ),
                'require_nonce' => false,
            ),
        );
    }


    //none of the below group are currently used for Support pages
    protected function _add_screen_options()
    {
    }

    protected function _add_feature_pointers()
    {
    }

    public function admin_init()
    {
    }

    public function admin_notices()
    {
    }

    public function admin_footer_scripts()
    {
    }

    public function load_scripts_styles()
    {
    }


    public function load_scripts_styles_default() {
        wp_register_script(
            'vue',
            'https://unpkg.com/vue/dist/vue.min.js',
            array( 'underscore' ),
            null,
            true
        );
        wp_register_script(
            'vuex',
            'https://unpkg.com/vuex/dist/vuex.min.js',
            array('vue'),
            null,
            true
        );
        wp_register_script(
            'vue-resource',
            'https://unpkg.com/vue-resource/dist/vue-resource.min.js',
            array('vue'),
            null,
            true
        );
        wp_register_script(
            'eeapi',
            EE_PLUGIN_DIR_URL . 'core/packaged_assets/vue/dist/ee-vue-models.build.js',
            array( 'vue','vuex','vue-resource' ),
            espresso_version(),
            true
        );
        wp_register_script(
            'eventexperiment',
            EE_EXPERIMENTS_ASSETS_URL . 'eventsexperiment.js',
            array('eeapi'),
            espresso_version(),
            true
        );
        wp_enqueue_script('eventexperiment');

        wp_localize_script(
            'eeapi',
            'eejs',
            array(
                'paths' => array(
                    'rest_route' => rest_url('ee/v4.8.36/')
                ),
                'templates' => array(
                    'event' => EEH_Template::display_template(
                        EE_EXPERIMENTS_ADMIN_TEMPLATE_PATH . 'event_template.html',
                        '',
                        true
                    ),
                    'datetime' => EEH_Template::display_template(
                        EE_EXPERIMENTS_ADMIN_TEMPLATE_PATH . 'datetime_template.html',
                        '',
                        true
                    )
                )
            )
        );
    }


    protected function _view_experimentation()
    {
        $this->_template_args['admin_page_content'] = EEH_Template::display_template(
            EE_EXPERIMENTS_ADMIN_TEMPLATE_PATH . 'app_container.html',
            '',
            true
        );
        $this->display_admin_page_with_no_sidebar();
    }
} //end Experiments_Admin_Page class
