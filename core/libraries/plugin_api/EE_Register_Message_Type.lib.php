<?php
/**
 * This file contains the EE_Register_Message_Type class that implements EEI_Plugin_API
 * @package      Event Espresso
 * @subpackage plugin api, messages
 * @since           4.3.0
 */
if ( ! defined('EVENT_ESPRESSO_VERSION')) exit('No direct script access allowed');

/**
 * Use this to register or deregister a new message type for the EE messages system.
 *
 * @package        Event Espresso
 * @subpackage  plugin api, messages
 * @since            4.3.0
 * @author          Darren Ethier
 */
class EE_Register_Message_Type implements EEI_Plugin_API {


	/**
	 * Holds values for registered message types
	 * @var array
	 */
	protected static $_ee_message_type_registry = array();



	/**
	 * Method for registering new message types in the EE_messages system.
	 *
	 * Note:  All message types must have the following files in order to work:
	 *
	 * Template files for default templates getting setup.
	 * See /core/libraries/messages/defaults/default/ for examples
	 * (note that template files match a specific naming schema).
	 * These templates will need to be registered with the default template pack.
	 *
	 * - EE_Messages_Validator extended class(es).  See /core/libraries/messages/validators/email/
	 *      for examples.  Note for any new message types, there will need to be a validator for each
	 *      messenger combo this message type can activate with.
	 * - And of course the main EE_{Message_Type_Name}_message_type class that defines the new
	 *      message type and its properties.
	 *
	 * @since    4.3.0
	 * @param string $mt_name 		Whatever is defined for the $name property of
	 *                              the message type you are registering (eg.
	 *                              declined_registration). Required.
	 * @param  array $setup_args    An array of arguments provided for registering the message type.
	 * @throws \EE_Error
	 * }
	 */
	public static function register( $mt_name = NULL, $setup_args = array() ) {
		//required fields MUST be present, so let's make sure they are.
		if (
			! isset( $mt_name )
			|| ! is_array( $setup_args )
			|| empty( $setup_args['mtfilename'] ) || empty( $setup_args['autoloadpaths'] )
		){
			throw new EE_Error(
				__( 'In order to register a message type with EE_Register_Message_Type::register, you must include a unique name for the message type, plus an array containing the following keys: "mtfilename", "autoloadpaths"', 'event_espresso' )
			);
		}

		//make sure we don't register twice
		if( isset( self::$_ee_message_type_registry[ $mt_name ] ) ){
			return;
		}

		//make sure this was called in the right place!
		if (
			! did_action( 'EE_Brewing_Regular___messages_caf' )
			|| did_action( 'AHEE__EE_System__perform_activations_upgrades_and_migrations' )
		) {
			EE_Error::doing_it_wrong(
				__METHOD__,
				sprintf(
					__('A message type named "%s" has been attempted to be registered with the EE Messages System.  It may or may not work because it should be only called on the "EE_Brewing_Regular___messages_caf" hook.','event_espresso'),
					$mt_name
				),
				'4.3.0'
			);
		}
		//setup $__ee_message_type_registry array from incoming values.
		self::$_ee_message_type_registry[ $mt_name ] = array(
			'mtfilename' => (string) $setup_args['mtfilename'],
			'autoloadpaths' => (array) $setup_args['autoloadpaths'],
			'messengers_to_activate_with' => ! empty( $setup_args['messengers_to_activate_with'] )
				? (array) $setup_args['messengers_to_activate_with']
				: array(),
			'messengers_to_validate_with' => ! empty( $setup_args['messengers_to_validate_with'] )
				? (array) $setup_args['messengers_to_validate_with']
				: array(),
			'force_activation' => ! empty( $setup_args['force_activation'] )
				? (bool) $setup_args['force_activation']
				: array()
		);
		//add filters
		add_filter(
			'FHEE__EED_Messages___set_messages_paths___MSG_PATHS',
			array( 'EE_Register_Message_Type', 'register_msgs_autoload_paths' ),
			10
		);
		add_filter(
			'FHEE__EE_messages__get_installed__messagetype_files',
			array( 'EE_Register_Message_Type', 'register_messagetype_files' ),
			10,
			1
		);
		add_filter(
			'FHEE__EE_messenger__get_default_message_types__default_types',
			array( 'EE_Register_Message_Type', 'register_messengers_to_activate_mt_with' ),
			10,
			2
		);
		add_filter(
			'FHEE__EE_messenger__get_valid_message_types__valid_types',
			array( 'EE_Register_Message_Type', 'register_messengers_to_validate_mt_with' ),
			10,
			2
		);
		//actions
		add_action(
			'AHEE__EE_Addon__initialize_default_data__begin',
			array( 'EE_Register_Message_Type', 'set_defaults' )
		);
	}



	/**
	 * This just ensures that when an addon registers a message type that on initial activation/reactivation the defaults the addon sets are taken care of.
	 */
	public static function set_defaults() {
		/** @type EE_Message_Resource_Manager $message_resource_manager */
		$message_resource_manager = EE_Registry::instance()->load_lib( 'Message_Resource_Manager' );

		//for any message types with force activation, let's ensure they are activated
		foreach ( self::$_ee_message_type_registry as $message_type_name => $settings ) {
			if ( $settings['force_activation'] ) {
				foreach ( $settings['messengers_to_activate_with'] as $messenger ) {
					//DO not force activation if this message type has already been activated in the system
					if ( ! $message_resource_manager->has_message_type_been_activated_for_messenger( $message_type_name, $messenger ) ) {
						$message_resource_manager->ensure_message_type_is_active( $message_type_name, $messenger );
					}
				}
			}
		}
	}



	/**
	 * This deregisters a message type that was previously registered with a specific message_type_name.
	 *
	 * @since    4.3.0
	 *
	 * @param string  $message_type_name the name for the message type that was previously registered
	 * @return void
	 */
	public static function deregister( $message_type_name = null ) {
		if ( ! empty( self::$_ee_message_type_registry[ $message_type_name ] ) ) {
			//let's make sure that we remove any place this message type was made active
			/** @var EE_Message_Resource_Manager $Message_Resource_Manager */
			$Message_Resource_Manager = EE_Registry::instance()->load_lib( 'Message_Resource_Manager' );
			$Message_Resource_Manager->deactivate_message_type( $message_type_name );
			unset( self::$_ee_message_type_registry[ $message_type_name ] );
		}
	}



	/**
	 * callback for FHEE__EE_messages__get_installed__messagetype_files filter.
	 *
	 * @since   4.3.0
	 *
	 * @param  array  $messagetype_files The current array of message type file names
	 * @return  array                                 Array of message type file names
	 */
	public static function register_messagetype_files( $messagetype_files ) {
		if ( empty( self::$_ee_message_type_registry ) ) {
			return $messagetype_files;
		}
		foreach ( self::$_ee_message_type_registry as $mt_reg ) {
			if ( empty( $mt_reg['mtfilename' ] ) ) {
				continue;
			}
			$messagetype_files[] = $mt_reg['mtfilename'];
		}
		return $messagetype_files;
	}





	/**
	 * callback for FHEE__EED_Messages___set_messages_paths___MSG_PATHS filter.
	 *
	 * @since    4.3.0
	 *
	 * @param array $paths array of paths to be checked by EE_messages autoloader.
	 * @return array
	 */
	public static function register_msgs_autoload_paths( $paths  ) {
		if ( ! empty( self::$_ee_message_type_registry ) ) {
			foreach ( self::$_ee_message_type_registry as $mt_reg ) {
				if ( empty( $mt_reg['autoloadpaths'] ) ) {
					continue;
				}
				$paths = array_merge( $paths, $mt_reg['autoloadpaths'] );
			}
		}
		return $paths;
	}





	/**
	 * callback for FHEE__EE_messenger__get_default_message_types__default_types filter.
	 *
	 * @since  4.3.0
	 * @param  array        $default_types 	array of message types activated with messenger (
	 *                                      corresponds to the $name property of message type)
	 * @param  EE_messenger $messenger      The EE_messenger the filter is called from.
	 * @return array
	 */
	public static function register_messengers_to_activate_mt_with( $default_types, EE_messenger $messenger ) {
		if ( empty( self::$_ee_message_type_registry ) ) {
			return $default_types;
		}
		foreach ( self::$_ee_message_type_registry as $message_type_name => $mt_reg ) {
			if ( empty( $mt_reg['messengers_to_activate_with'] ) || empty( $mt_reg['mtfilename'] ) ) {
				continue;
			}
			// loop through each of the messengers and if it matches the loaded class
			// then we add this message type to the
			foreach ( $mt_reg['messengers_to_activate_with'] as $msgr ) {
				if ( $messenger->name == $msgr ) {
					$default_types[] = $message_type_name;
				}
			}
		}

		return $default_types;
	}



	 /**
	 * callback for FHEE__EE_messenger__get_valid_message_types__default_types filter.
	 *
	 * @since   4.3.0
	 * @param  array        $valid_types 	array of message types valid with messenger (
	 *                                      corresponds to the $name property of message type)
	 * @param  EE_messenger $messenger      The EE_messenger the filter is called from.
	 * @return  array
	 */
	public static function register_messengers_to_validate_mt_with( $valid_types, EE_messenger $messenger ) {
		if ( empty( self::$_ee_message_type_registry ) ) {
			return $valid_types;
		}
		foreach ( self::$_ee_message_type_registry as $message_type_name => $mt_reg ) {
			if ( empty( $mt_reg['messengers_to_validate_with'] ) || empty( $mt_reg['mtfilename'] ) ) {
				continue;
			}
			// loop through each of the messengers and if it matches the loaded class
			// then we add this message type to the
			foreach ( $mt_reg['messengers_to_validate_with'] as $msgr ) {
				if ( $messenger->name == $msgr ) {
					$valid_types[] = $message_type_name;
				}
			}
		}

		return $valid_types;
	}
}
