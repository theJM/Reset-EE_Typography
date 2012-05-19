<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reset_ee_typography_ext {

    var $name           = 'Reset EE_Typography';
    var $version        = '1.0';
    var $description    = 'runs the EE->typography->initialize() method after every use of the parse_type method';
    var $settings_exist = 'n';
    var $docs_url       = ''; // 'http://expressionengine.com/user_guide/';

    var $settings       = array();
    
    /**
     * Constructor
     *
     * @param   mixed   Settings array or empty string if none exist.
     */
    function __construct($settings = '')
    {
        $this->EE =& get_instance();

        $this->settings = $settings;
    }
    
    /**
     * Format Channel Title
     *
     * This function runs the EE->typography->initialize() method after every use of the parse_type method
     *
     * @param   string   The string currently being parsed
     * @return  string   The string just as it was passed in or returned by the previous extension
     */
    function run_initialize($str)
    {
        $this->EE->typography->initialize();
        if ($this->EE->extensions->last_call) return $this->EE->extensions->last_call;
        return $str;
    }
    
    /**
     * Activate Extension
     *
     * This function enters the extension into the exp_extensions table
     *
     * @see http://codeigniter.com/user_guide/database/index.html for
     * more information on the db class.
     *
     * @return void
     */
    function activate_extension()
    {
        $data = array(
            'class'     => __CLASS__,
            'method'    => 'run_initialize',
            'hook'      => 'typography_parse_type_end',
            'settings'  => serialize($this->settings),
            'priority'  => 1000000,
            'version'   => $this->version,
            'enabled'   => 'y'
        );
    
        $this->EE->db->insert('extensions', $data);
    }
    
    /**
     * Update Extension
     *
     * This function performs any necessary db updates when the extension
     * page is visited
     *
     * @return  mixed   void on update / false if none
     */
    function update_extension($current = '')
    {
        if ($current == '' OR $current == $this->version)
        {
            return FALSE;
        }
    
        if ($current < '1.0')
        {
            // Update to version 1.0
        }
    
        $this->EE->db->where('class', __CLASS__);
        $this->EE->db->update(
                    'extensions',
                    array('version' => $this->version)
        );
    }
    
}
// END CLASS