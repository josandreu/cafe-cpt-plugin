<?php

class Cafe_CPT_Updater {
	protected $file;
	protected $plugin;
	protected $basename;
	protected $active;

	private $username;
	private $repository;
	private $authorize_token;
	private $github_response;

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

	// These setters allow us to modify usernames and repositories without reinstantiating our updater class.

	/**
	 * @param mixed $username
	 */
	public function set_username( $username ): void {
		$this->username = $username;
	}

	/**
	 * @param mixed $repository
	 */
	public function set_repository( $repository ): void {
		$this->repository = $repository;
	}

	public function authorize( $token ) {
		$this->authorize_token = $token;
	}

	private function get_repository_info() {
		if ( is_null( $this->github_response ) ) {
			// Build URI
			$request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases', $this->username, $this->repository );
			if ( $this->authorize_token ) {
				// Is there an access token? Append it
				$request_uri = add_query_arg( 'access_token', $this->authorize_token, $request_uri );
			}
			$response = json_decode( wp_remote_retrieve_body(
				wp_remote_get( $request_uri )
			), true );

			if ( is_array( $response ) ) {
				$response = current( $response );
			}

			if ( $this->authorize_token ) {
				// Update our zip url with token
				$response['zipball_url'] = add_query_arg( 'access_token', $this->authorize_token, $response['zipball_url'] );
			}

			$this->github_response = $response;
		}
	}

	public function initialize() {
		// First filter is going to modify the transient
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'modify_transient' ), 10, 1 );
		// Ensure that our plugin data gets passed into the WordPress interface
		add_filter( 'plugins_api', array( $this, 'plugin_popup' ), 10, 3 );
		// Will make sure that our plugin is activated after the update.
		add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 3 );
	}

	public function modify_transient( $transient ) {
		// Check if transient has a checked property
		if ( property_exists( $transient, 'checked' ) ) {
			// Did WordPress check for updates?
			if ( $checked = $transient->checked ) {
				$this->get_repository_info();
				$out_of_date = version_compare( $this->github_response['tag_name'], $checked[ $this->basename ], 'gt' );
				if ( $out_of_date ) {
					$new_files                              = $this->github_response['zipball_url']; // Get the ZIP
					$slug                                   = current( explode( '/', $this->basename ) );
					$plugin                                 = array(
						'url'         => $this->plugin['PluginURI'],
						'slug'        => $slug,
						'package'     => $new_files,
						'new_version' => $this->github_response['tag_name']
					);
					$transient->response[ $this->basename ] = (object) $plugin;
				}
			}
		}

		// Return filtered transient
		return $transient;
	}

	// We need to load our own data about the plugin in the admin page
	public function plugin_popup( $result, $action, $args ) {
		if ( ! empty( $args->slug ) ) { // If there is a slug
			if ( $args->slug == current( explode( '/', $this->basename ) ) ) { // And it's our slug
				$this->get_repository_info(); // Get our repo info
				// Set it to an array
				$plugin = array(
					'name'              => $this->plugin["Name"],
					'slug'              => $this->basename,
					'version'           => $this->github_response['tag_name'],
					'author'            => $this->plugin["AuthorName"],
					'author_profile'    => $this->plugin["AuthorURI"],
					'last_updated'      => $this->github_response['published_at'],
					'homepage'          => $this->plugin["PluginURI"],
					'short_description' => $this->plugin["Description"],
					'sections'          => array(
						'Description' => $this->plugin["Description"],
						'Updates'     => $this->github_response['body'],
					),
					'download_link'     => $this->github_response['zipball_url']
				);

				return (object) $plugin; // Return the data
			}
		}

		return $result; // Otherwise, return default
	}

	public function after_install( $response, $hook_extra, $result ) {
		global $wp_filesystem; // Get global FS object

		$install_directory = plugin_dir_path( $this->file ); // Our plugin directory
		$wp_filesystem->move( $result['destination'], $install_directory ); // Move files to the plugin dir
		$result['destination'] = $install_directory; // Set the destination for the rest of the stack

		if ( $this->active ) { // If it was active
			activate_plugin( $this->basename ); // Reactivate
		}

		return $result;
	}


}
