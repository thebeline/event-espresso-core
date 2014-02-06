<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) exit('No direct script access allowed');
/**
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package			Event Espresso
 * @ author			Seth Shoultes
 * @ copyright		(c) 2008-2011 Event Espresso  All Rights Reserved.
 * @ license			http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link				http://www.eventespresso.com
 * @ version		 	4.0
 *
 */




	/**
	 * espresso_event_venues
	 *
	 * @returns all venues related to an event
	 * @return array
	 */
	if ( ! function_exists( 'espresso_event_venues' )) {
		function espresso_event_venues() {
			return EEH_Venue_View::get_event_venues();
		}		
	}




	/**
	 * 	espresso_venue_name
	 *
	 *  @access 	public
	 *  @param 	string 	$link_to - options( details, website, none ) whether to turn Venue name into a clickable link to the Venue's details page or website
	 *  @return 	string
	 */
	if ( ! function_exists( 'espresso_venue_name' )) {
		function espresso_venue_name( $VNU_ID = FALSE, $link_to = 'details'  ) {
			echo EEH_Venue_View::venue_name( $link_to, $VNU_ID );
		}		
	}




	/**
	 * 	espresso_venue_name
	 *
	 *  @access 	public
	 *  @param 	int 		$VNU_ID
	 *  @param 	string 	$text
	 *  @return 	string
	 */
	if ( ! function_exists( 'espresso_venue_link' )) {
		function espresso_venue_link( $VNU_ID = FALSE, $text = '' ) {
			return EEH_Venue_View::venue_details_link( $VNU_ID, $text );
		}		
	}



	/**
	 * 	espresso_venue_description
	 *
	 *  @access 	public
	 *  @return 	string
	 */
	if ( ! function_exists( 'espresso_venue_description' )) {
		function espresso_venue_description( $VNU_ID = FALSE ) {
			echo EEH_Venue_View::venue_description( $VNU_ID );
		}		
	}


	/**
	 * 	espresso_venue_excerpt
	 *
	 *  @access 	public
	 *  @return 	string
	 */
	if ( ! function_exists( 'espresso_venue_excerpt' )) {
		function espresso_venue_excerpt( $VNU_ID = FALSE,  $echo = TRUE ) {
			if ( $echo ) {
				echo EEH_Venue_View::venue_excerpt( $VNU_ID,  $echo );
			} else {
				return EEH_Venue_View::venue_excerpt( $VNU_ID,  $echo );
			}
		}		
	}



	/**
	 * espresso_venue_categories
	 * returns the terms associated with a venue
	* 
	 * @return string
	 */
	if ( ! function_exists( 'espresso_venue_categories' )) {
		function espresso_venue_categories( $VNU_ID = FALSE, $hide_uncategorized = TRUE,  $echo = TRUE ) { 			
			if ( $echo ) {
				echo EEH_Venue_View::venue_categories( $VNU_ID, $hide_uncategorized );
			} else {
				return EEH_Venue_View::venue_categories( $VNU_ID, $hide_uncategorized );
			}
		}		
	}


	/**
	 * espresso_venue_address
	 * @param 	string 	'inline' or 'multiline'
	 * returns a formatted block of html  for displaying a venue's address
	 *
	 * @return string
	 */
	if ( ! function_exists( 'espresso_venue_address' )) {
		function espresso_venue_address( $type = 'multiline', $VNU_ID = FALSE, $echo = TRUE ) {
			if ( $echo ) {
				echo EEH_Venue_View::venue_address( $type, $VNU_ID );
			} else {
				return EEH_Venue_View::venue_address( $type, $VNU_ID );
			}
		}		
	}


	/**
	 * espresso_venue_has_address
	 * returns TRUE or FALSE if a Venue has address information
	 *
	 * @return string
	 */
	if ( ! function_exists( 'espresso_venue_has_address' )) {
		function espresso_venue_has_address( $VNU_ID = FALSE ) {
			return EEH_Venue_View::venue_has_address( $VNU_ID );
		}		
	}


	/**
	 * espresso_venue_gmap
	 * returns a google map for the venue address
	 *
	 * @return string
	 */
	if ( ! function_exists( 'espresso_venue_gmap' )) {
		function espresso_venue_gmap( $VNU_ID = FALSE, $map_ID = FALSE, $gmap = array(), $echo = TRUE  ) {
			if ( $echo ) {
				echo EEH_Venue_View::venue_gmap( $VNU_ID, $map_ID, $gmap );
			} else {
				return EEH_Venue_View::venue_gmap( $VNU_ID, $map_ID, $gmap );
			}
		}		
	}


	/**
	 * espresso_venue_phone
	 *
	 * @return string
	 */
	if ( ! function_exists( 'espresso_venue_phone' )) {
		function espresso_venue_phone( $VNU_ID = FALSE, $echo = TRUE ) {
			if ( $echo ) {
				echo EEH_Venue_View::venue_phone( $VNU_ID );
			} else {
				return EEH_Venue_View::venue_phone( $VNU_ID );
			}			
		}		
	}

	/**
	 * espresso_venue_website
	 *
	 * @return string
	 */
	if ( ! function_exists( 'espresso_venue_website' )) {
		function espresso_venue_website( $VNU_ID = FALSE ) {
			echo EEH_Venue_View::venue_website_link( $VNU_ID );
		}		
	}



	/**
	 * espresso_edit_venue_link
	 *
	 * @returns a link to edit a venue
	 * @return string
	 */
	if ( ! function_exists( 'espresso_edit_venue_link' )) {
		function espresso_edit_venue_link( $VNU_ID = FALSE ) {
			echo EEH_Venue_View::edit_venue_link( $VNU_ID );
		}		
	}





/**
 * ------------------------------------------------------------------------
 *
 * EEH_Venue_View Helper
 *
 * @package		Event Espresso
 * @subpackage	/core/
 * @author		Brent Christensen 
 *
 * ------------------------------------------------------------------------
 */
class EEH_Venue_View extends EEH_Base {

	private static $_venue = NULL;


	/**
	 * 	get_venue
	* 	attempts to retrieve an EE_Venue object any way it can
	 *
	 *  @access 	private
	 *  @return 	object
	 */
	private static function get_venue( $VNU_ID = FALSE ) {
		$VNU_ID = absint( $VNU_ID );
		// do we already have the Venue you are looking for?
		if ( EEH_Venue_View::$_venue instanceof EE_Venue && $VNU_ID ==  EEH_Venue_View::$_venue->ID() ) {
			return EEH_Venue_View::$_venue;
		}
		// international newspaper?
		global $post;
		// if this is being called from an EE_Venue post, then we can just grab the attached EE_Venue object
		if ( $post->post_type == 'espresso_venues' ) {
			// let's try to get our EE model object
			EEH_Venue_View::$_venue = isset( $post->EE_Venue ) ? $post->EE_Venue : $post;
			// grab this for later in case we need it
			$VNU_ID = $post->ID;
		
		} else if ( isset( $post->post_type ) && $post->post_type == 'espresso_events' || $VNU_ID ) {
			// grab the events related venues
			$venues = EEH_Venue_View::get_event_venues();
			// make sure the result is an array
			$venues = is_array( $venues ) ? $venues : array();
			// do we have an ID for a specific venue?
			if ( $VNU_ID ) {
				// loop thru the related venues
				foreach( $venues as $venue ) {
					// untill we find the venue we're looking for
					if ( $venue->ID() == $VNU_ID ) {
						EEH_Venue_View::$_venue = $venue;
						break;
					}
				}
			// no venue ID ?				
			} else {
				// just grab the first related event venue
				EEH_Venue_View::$_venue = reset( $venues );		
			}
			// now if we STILL do NOT have an EE_Venue model object, BUT we have a Venue ID...
			if ( ! EEH_Venue_View::$_venue instanceof EE_Venue && $VNU_ID ) {
				// sigh... pull it from the db
				EEH_Venue_View::$_venue = EEM_Venue::instance()->get_one_by_ID( $VNU_ID );
			}
		}
		return EEH_Venue_View::$_venue;
	}



	/**
	 * 	edit_event_link
	 *
	 *  @access 	public
	 *  @return 	string
	 */
	public static function get_event_venues() {
		global $post;
		if ( $post->post_type == 'espresso_events' ) {
			if ( isset( $post->EE_Event ) && $post->EE_Event instanceof EE_Event ) {
				return $post->EE_Event->venues();
			}
		}
	}





	/**
	 * 	venue_description
	 *
	 *  @access 	public
	 *  @return 	string
	 */
	public static function venue_description( $VNU_ID = FALSE ) {
		$venue = EEH_Venue_View::get_venue( $VNU_ID );
		if ( $venue instanceof EE_Venue ) {
			return$venue->description();
		}
		return '';
	}




	/**
	 * 	venue_excerpt
	 *
	 *  @access 	public
	 *  @return 	string
	 */
	public static function venue_excerpt( $VNU_ID = FALSE ) {
		$venue = EEH_Venue_View::get_venue( $VNU_ID );
		if ( $venue instanceof EE_Venue ) {
			$excerpt = $venue->excerpt() != NULL && $venue->excerpt() ? $venue->excerpt() : $venue->description();
			$venue_link = ' ' . EEH_Venue_View::venue_details_link( $venue->ID(), __( 'more', 'event_espresso' ) . '&hellip;' );
			return ! empty( $excerpt ) ? wp_trim_words( $excerpt, 25, '' ) . $venue_link : '';			
		}
		return '';
	}




	/**
	 * 	venue_categories
	 *
	 *  @access 	public
	 *  @return 	string
	 */
	public static function venue_categories( $VNU_ID = FALSE, $hide_uncategorized = TRUE ) {
		$category_links = array();
		$venue = EEH_Venue_View::get_venue( $VNU_ID );
		if ( $venue instanceof EE_Venue ) {
			// get category terms
			if ( $venue_categories = get_the_terms( $venue->ID(), 'espresso_venue_categories' )) {
				// loop thru terms and create links
				foreach ( $venue_categories as $term ) {
					$url = get_term_link( $term, 'espresso_venue_categories' );
					if ( ! is_wp_error( $url ) && (( $hide_uncategorized && strtolower( $term->name ) != __( 'uncategorized', 'event_espresso' )) || ! $hide_uncategorized )) {
						$category_links[] = '<a href="' . esc_url( $url ) . '" rel="tag">' . $term->name . '</a> ';
					}					
				}
			}
		}
		return implode( ', ', $category_links );		
	}





	/**
	 * 	venue_address
	 *
	 *  @access 	public
	 *  @return 	string
	 */
	public static function venue_address( $type = 'multiline', $VNU_ID = FALSE ) {
		$venue = EEH_Venue_View::get_venue( $VNU_ID );
		if ( $venue instanceof EE_Venue ) {
			EE_Registry::instance()->load_helper( 'Formatter' );
			return EEH_Address::format( $venue, $type );
		}
		return NULL;
	}




	/**
	 * 	venue_has_address
	 *
	 *  @access 	public
	 *  @return 	string
	 */
	public static function venue_has_address( $VNU_ID = FALSE ) {
		$venue = EEH_Venue_View::get_venue( $VNU_ID );
		if ( $venue instanceof EE_Venue ) {
			EE_Registry::instance()->load_helper( 'Formatter' );
			return EEH_Address::format( $venue, 'inline', FALSE, FALSE );
		}
		return NULL;
	}





	/**
	 * 	venue_name
	 *
	 *  @access 	public
	 *  @param 	string 	$link_to - options( details, website, none ) whether to turn Venue name into a clickable link to the Venue's details page or website
	 *  @return 	string
	 */
	public static function venue_name( $link_to = 'details', $VNU_ID = FALSE ) {
		$venue = EEH_Venue_View::get_venue( $VNU_ID );
		if ( $venue instanceof EE_Venue ) {
			EE_Registry::instance()->load_helper( 'Formatter' );
			$venue_name = EEH_Schema::name( EEH_Venue_View::$_venue->name() );
			switch( $link_to ) {
				
				case 'details' :
					return EEH_Venue_View::venue_details_link( $venue->ID(), $venue_name );
				break;
				
				case 'website' :
					return EEH_Venue_View::venue_website_link( $venue->ID(), $venue_name );
				break;
				
				default :
					return $venue_name;
			}
		}
		return NULL;
	}




	/**
	 * 	venue_details_link
	 *
	 *  @access 	public
	 *  @param	string $text 
	 *  @return 	string
	 */
	public static function venue_details_link( $VNU_ID = FALSE, $text = '' ) {
		$venue = EEH_Venue_View::get_venue( $VNU_ID );
		if ( $venue instanceof EE_Venue ) {
			return EEH_Schema::url( get_permalink( $venue->ID() ), $text );
		}
		return NULL;
	}



	/**
	 * 	venue_website_link
	 *
	 *  @access 	public
	 *  @param	string $text 
	 *  @return 	string
	 */
	public static function venue_website_link( $VNU_ID = FALSE, $text = '' ) {
		$venue = EEH_Venue_View::get_venue( $VNU_ID );
		if ( $venue instanceof EE_Venue ) {
			EE_Registry::instance()->load_helper( 'Formatter' );
			$url = $venue->venue_url();
			$text = ! empty( $text ) ? $text : $url;
			return ! empty( $url ) ? EEH_Schema::url( $url, $text ) : '';
		}
		return NULL;
	}




	/**
	 * 	venue_phone
	 *
	 *  @access 	public
	 *  @param	string $text 
	 *  @return 	string
	 */
	public static function venue_phone( $VNU_ID = FALSE) {
		$venue = EEH_Venue_View::get_venue( $VNU_ID );
		if ( $venue instanceof EE_Venue ) {
			EE_Registry::instance()->load_helper( 'Formatter' );
			return EEH_Schema::telephone( $venue->phone() );
		}
		return NULL;
	}



	/**
	 * 	venue_gmap
	 *
	 *  @access 	public
	 *  @param	string $map_ID a unique identifier for this map 
	 *  @param	array $gmap map options
	 *  @return 	string
	 */
	public static function venue_gmap( $VNU_ID = FALSE, $map_ID = FALSE, $gmap = array() ) {
		
		static $static_map_id = 0;		
		
		$venue = EEH_Venue_View::get_venue( $VNU_ID );
		if ( $venue instanceof EE_Venue ) {
			
			$map_cfg = EE_Registry::instance()->CFG->map_settings;
			
			if ( $map_cfg->use_google_maps && $venue->enable_for_gmap() ) {
				
				EE_Registry::instance()->load_helper( 'Maps' );
				EE_Registry::instance()->load_helper( 'Formatter' );
				
				$details_page = is_single();
				$options = array();		
				$options['map_ID'] = $map_ID && $map_ID != $venue->ID() ? $map_ID . '-' . $venue->ID()/* . '-' . $static_map_id*/ : $venue->ID()/* . '-' . $static_map_id*/;
//				$static_map_id++;
//				echo '<h1>$static_map_id: ' . $static_map_id  .'  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h1>';

				$options['location'] = EEH_Address::format( $venue, 'inline', FALSE, FALSE );
				
				$options['ee_map_width'] = $details_page ? $map_cfg->event_details_map_width : $map_cfg->event_list_map_width;	
				$options['ee_map_width'] = isset( $gmap['ee_map_width'] ) && ! empty( $gmap['ee_map_width'] ) ? $gmap['ee_map_width'] : $options['ee_map_width'];
				
				$options['ee_map_height'] = $details_page ? $map_cfg->event_details_map_height : $map_cfg->event_list_map_height;	
				$options['ee_map_height'] = isset( $gmap['ee_map_height'] ) && ! empty( $gmap['ee_map_height'] ) ? $gmap['ee_map_height'] : $options['ee_map_height'];
				
				$options['ee_map_zoom'] = $details_page ? $map_cfg->event_details_map_zoom : $map_cfg->event_list_map_zoom;	
				$options['ee_map_zoom'] = isset( $gmap['ee_map_zoom'] ) && ! empty( $gmap['ee_map_zoom'] ) ? $gmap['ee_map_zoom'] : $options['ee_map_zoom'];
				
				$options['ee_map_nav_display'] = $details_page ? $map_cfg->event_details_display_nav : $map_cfg->event_list_display_nav;	
				$options['ee_map_nav_display'] = isset( $gmap['ee_map_nav_display'] ) && ! empty( $gmap['ee_map_nav_display'] ) ? 'true' : $options['ee_map_nav_display'];;
				
				$options['ee_map_nav_size'] = $details_page ? $map_cfg->event_details_nav_size : $map_cfg->event_list_nav_size;	
				$options['ee_map_nav_size'] =  isset( $gmap['ee_map_nav_size'] ) && ! empty( $gmap['ee_map_nav_size'] )? $gmap['ee_map_nav_size'] : $options['ee_map_nav_size'];
				
				$options['ee_map_type_control'] = $details_page ? $map_cfg->event_details_control_type : $map_cfg->event_list_control_type;	
				$options['ee_map_type_control'] =  isset( $gmap['ee_map_type_control'] ) && ! empty( $gmap['ee_map_type_control'] )? $gmap['ee_map_type_control'] : $options['ee_map_type_control'];
				
				$options['ee_map_align'] = $details_page ? $map_cfg->event_details_map_align : $map_cfg->event_list_map_align;	
				$options['ee_map_align'] =  isset( $gmap['ee_map_align'] ) && ! empty( $gmap['ee_map_align'] )? $gmap['ee_map_align'] : $options['ee_map_align'];
				
				$options['ee_static_url'] =  isset( $gmap['ee_static_url'] ) && ! empty( $gmap['ee_static_url'] ) ? (bool)absint( $gmap['ee_static_url'] ) : $venue->google_map_link();

				return EEH_Maps::google_map( $options );
				
			}
		}
	
		return FALSE;

	}
	
	/**
	 * Gets the HTML to display a static map of the venue
	 * @param EE_Venue $venue
	 * @param array $atts like EEH_Maps::google_map_link
	 * @return string
	 */
	public static function espresso_google_static_map( EE_Venue $venue, $atts = array() ){
		EE_Registry::instance()->load_helper('Maps');
		$state = $venue->state_obj();
		$country = $venue->country_obj();
		$atts = shortcode_atts(
				array(
					'id' => $venue->ID(),
					'address' => $venue->get('VNU_address'),
					'city' => $venue->get('VNU_city'),
					'state' => $state ? $state->name() : '',
					'zip' => $venue->get('VNU_zip'),
					'country' => $country ? $country->name() : '',
					'type' => 'map',
					'map_w' => 200,
					'map_h' => 200
					),
			$atts);
		return EEH_Maps::google_map_link($atts);
	}





	/**
	 * 	edit_venue_link
	 *
	 *  @access 	public
	 *  @return 	string
	 */
	public static function edit_venue_link( $VNU_ID = FALSE, $link = '', $before = '<p class="edit-venue-lnk small-txt">', $after = '</p>' ) {
		$venue = EEH_Venue_View::get_venue( $VNU_ID );
		if ( $venue instanceof EE_Venue ) {
			// can the user edit this post ?
			if ( current_user_can( 'edit_post', $venue->ID() )) {
				// set link text
				$link = ! empty( $link ) ? $link : __('edit this venue');
				// generate nonce
				$nonce = wp_create_nonce( 'edit_nonce' );
				// generate url to venue editor for this venue
				$url = add_query_arg( array( 'page' => 'espresso_venues', 'action' => 'edit', 'post' => $venue->ID(), 'edit_nonce' => $nonce ), admin_url( 'admin.php' ) );
				// get edit CPT text
				$post_type_obj = get_post_type_object( 'espresso_venues' );
				// build final link html
				$link = '<a class="post-edit-link" href="' . $url . '" title="' . esc_attr( $post_type_obj->labels->edit_item ) . '">' . $link . '</a>';
				// put it all together 
				return $before . apply_filters( 'edit_post_link', $link, $venue->ID() ) . $after;			
			}
		}
	}






}
// End of file EEH_Venue_View.helper.php
// Location: /helpers/EEH_Venue_View.helper.php