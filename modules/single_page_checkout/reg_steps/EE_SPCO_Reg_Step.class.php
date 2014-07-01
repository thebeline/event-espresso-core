<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) { exit('No direct script access allowed'); }
 /**
 *
 * Class EE_SPCO_Reg_Step
 *
 * Description
 *
 * @package 			Event Espresso
 * @subpackage 	core
 * @author 				Brent Christensen
 * @since 				4.5.0
 *
 */
abstract class EE_SPCO_Reg_Step {

	/**
	 * 	$_slug - URL param for this step
	 * 	@access protected
	 *	@type string $_slug
	 */
	protected $_slug = NULL;

	/**
	 * 	$_name - Step Name - translatable string
	 * 	@access protected
	 *	@type string $_slug
	 */
	protected $_name = NULL;

	/**
	 * 	$_order - when the reg step should be run relative to other steps
	 * 	@access protected
	 *	@type int $_template
	 */
	protected $_order = NULL;

	/**
	 * 	$_is_current_step - TRUE if this is the current step
	 * 	@access protected
	 *	@type bool $_is_current_step
	 */
	protected $_is_current_step = FALSE;

	/**
	 * 	$_template - template name
	 * 	@access protected
	 *	@type string $_template
	 */
	protected $_template = NULL;

	/**
	 * 	$_reg_form_name - class name for this step's reg form
	 * 	@access protected
	 *	@var string $_reg_form_name
	 */
	protected $_reg_form_name = NULL;

	/**
	 * 	$reg_form - the registration form for this step
	 * 	@access public
	 *	@var EE_Form_Section_Proper $reg_form
	 */
	public $reg_form = NULL;

	/**
	 * 	$checkout - EE_Checkout object for handling the properties of the current checkout process
	 * 	@access public
	 *	@var EE_Checkout $checkout
	 */
	public $checkout = NULL;




	/**
	 * @return string
	 */
	public function name() {
		return $this->_name;
	}



	/**
	 * @return string
	 */
	public function slug() {
		return $this->_slug;
	}



	/**
	 * @param boolean $is_current_step
	 */
	public function set_is_current_step( $is_current_step ) {
		$this->_is_current_step = $is_current_step;
	}



	/**
	 * @return boolean
	 */
	public function is_current_step() {
		return $this->_is_current_step;
	}



	/**
	 * @param int $order
	 */
	public function set_order( $order ) {
		$this->_order = $order;
	}



	/**
	 * @return int
	 */
	public function order() {
		return $this->_order;
	}



	/**
	 * @return string
	 */
	public function template() {
		return $this->_template;
	}




	/**
	 * @return string
	 */
	public function reg_form_name() {
		return $this->_reg_form_name;
	}



	/**
	 * @return EE_Form_Input_Base[]
	 */
	public function reg_step_hidden_inputs() {
		// multidimensional array containing the following input details: id, name, value
		$input_details = array(
			array(
				'id' => 'spco-' . $this->slug() . '-action',
				'name' => 'ajax_action',
				'value' => 'espresso_process_reg_step'
			),
			array(
				'id' => 'spco-' . $this->slug() . '-noheader',
				'name' => 'noheader',
				'value' => ''
			),
			array(
				'id' => 'spco-' . $this->slug() . '-next-step',
				'name' => 'next_step',
				'value' => $this->checkout->next_step->slug()
			),
			array(
				'id' => 'spco-reg_url_link',
				'name' => 'e_reg_url_link',
				'value' => $this->checkout->reg_url_link
			),
			array(
				'id' => 'spco-revisit',
				'name' => 'revisit',
				'value' => $this->checkout->revisit
			)
		);
		// array to hold generated inputs
		$inputs = array();
		// loop thru input details
		foreach ( $input_details as $input ) {
			// set array of args
			$input_constructor_args = array(
				'layout_strategy' => new EE_Div_Per_Section_Layout(),
				'name' 				=> $input['name'],
				'html_name' 		=> $input['name'],
				'html_id' 				=> $input['id'],
				'default'				=> $input['value']
			);
			// generate input
			$inputs[ $input['name'] ] = new EE_Hidden_Input( $input_constructor_args );
		}
		$form_args = array(
			'name' 			=> 'hidden-inputs',
			'html_id' 			=> 'hidden-inputs',
			'subsections' 	=> $inputs,
			'exclude' 	=> array(),
			'layout_strategy'	=> new EE_Div_Per_Section_Layout()
		);
		return new EE_Form_Section_Proper( $form_args );
	}



	/**
	 * @return string
	 */
	public function display_reg_form() {
		$html = '';
		if ( $this->reg_form instanceof EE_Form_Section_Proper ) {
			echo '<br/><h5 style="color:#2EA2CC;">' . __CLASS__ . '<span style="font-weight:normal;color:#0074A2"> -> </span>' . __FUNCTION__ . '() <br/><span style="font-size:9px;font-weight:normal;color:#666">' . __FILE__ . '</span>    <b style="font-size:10px;color:#333">  ' . __LINE__ . ' </b></h5>';
			$this->reg_form->add_subsections( array( 'spco-next-step-btn' => $this->reg_step_submit_button() ));
			$html .= $this->reg_form->form_open( add_query_arg( array( 'ee' => '_register', 'step' => $this->slug(), 'action' => 'process_reg_step' ), $this->checkout->reg_page_base_url ));
			$html .= $this->reg_form->get_html_and_js();
			$html .= $this->reg_form->form_close();
		}
		return $html;
	}



	/**
	 * div_class - returns nothing for current step, but a css class of "hidden" for others
	 * @return string
	 */
	public function reg_step_submit_button() {
		$sbmt_btn = new EE_Submit_Input( array(
			'layout_strategy' => new EE_Div_Per_Section_Layout(),
			'name' 				=> 'spco-go-to-step-' . $this->checkout->next_step->slug(),
			'html_name' 		=> 'spco-go-to-step-' . $this->checkout->next_step->slug(),
			'html_id' 				=> 'spco-go-to-step-' . $this->checkout->next_step->slug(),
			'html_class' 		=> 'spco-next-step-btn',
			'default'				=> 'Proceed to ' . $this->checkout->next_step->name()
		));
		$sbmt_btn->set_button_css_attributes( TRUE, 'large', 'rel="' . $this->slug() . '"' );
		ob_start();
		do_action( 'AHEE__before_spco_whats_next_buttons', $this->slug(), $this->checkout->next_step->slug() );
		echo '<div id="spco-' . $this->slug() . '-whats-next-buttons-dv" class="spco-whats-next-buttons">';
		echo $sbmt_btn->get_html_for_input();
		echo '</div>';
		echo '<!--end spco-whats-next-buttons-->';
		return new EE_Form_Section_HTML( ob_get_clean() );
	}


	/**
	 * div_class - returns nothing for current step, but a css class of "hidden" for others
	 * @return string
	 */
	public function div_class() {
		return $this->is_current_step() ? '' : ' hidden';
	}



	/**
	 * div_class - returns  a css class of "hidden" for current step, but nothing for others
	 * @return string
	 */
	public function edit_lnk_url() {
		return 	add_query_arg( array( 'ee' => '_register', 'step' => $this->slug() ), $this->checkout->reg_page_base_url );

	}


	/**
	 * div_class - returns  a css class of "hidden" for current step, but nothing for others
	 * @return string
	 */
	public function edit_link_class() {
		return $this->is_current_step() ? ' hidden' : '';
	}



	/**
	 * @return void
	 */
	abstract public function translate_js_strings();

	/**
	 * @return void
	 */
	abstract public function enqueue_styles_and_scripts();

	/**
	 * @return boolean
	 */
	abstract public function initialize_reg_step();

	/**
	 * @return string
	 */
	abstract public function generate_reg_form();

	/**
	 * @return boolean
	 */
	abstract public function process_reg_step();

	/**
	 * @return boolean
	 */
	abstract public function update_reg_step();



}

// End of file EE_SPCO_Reg_Step.class.php
// Location: /EE_SPCO_Reg_Step.class.php