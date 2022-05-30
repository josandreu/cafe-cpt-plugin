<?php

class Cafe_CPT_Updater {
	protected $file;
	protected $plugin;
	protected $basename;
	protected $active;

	/**
	 * The main plugin file’s path
	 * WordPress stores plugin information is by using the main plugin file’s path as a unique identifier
	 *
	 * @param $file
	 */
	public function __construct( $file ) {
		$this->file = $file;

		/*
		 * You may have noticed that I am using the action admin_init to set the plugin properties.
		 * This is because the function get_plugin_data() may not have been defined at the point in which this code was called.
		 * By hooking it to admin_init we are ensuring that we have that function available to get our plugin data.
		 * We are also checking if the plugin is activated, and assigning that and the plugin object to properties in our class.
		 */
		add_action( 'admin_init', array( $this, 'set_plugin_properties' ) );

		return $file;
	}

	public function set_plugin_properties() {
		$this->plugin   = get_plugin_data( $this->file );
		$this->basename = plugin_basename( $this->file );
		$this->active   = is_plugin_active( $this->basename );
	}

}
