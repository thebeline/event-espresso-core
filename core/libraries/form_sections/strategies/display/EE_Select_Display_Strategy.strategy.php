<?php
/**
 * displays either simple arrays as selected, or if a 2d array is provided, seperates them
 * into optgroups
 */
class EE_Select_Display_Strategy extends EE_Display_Strategy_Base{
	/**
	 *
	 * @return string of html to display the field
	 */
	function display(){
		$input = $this->_input;
		if( ! $input instanceof EE_Form_Input_With_Options_Base){
			throw new EE_Error(sprintf(__("Cannot use Select Display Strategy with an input that doesn't ahve options", "event_espresso")));
		}
		$html= EEH_Formatter::nl(1) . "<select id='{$input->html_id()}' name='{$input->html_name()}' class='{$input->html_class()}' style='{$input->html_style()}'/>";
		EE_Registry::instance()->load_helper('Array');
		if(EEH_Array::is_multi_dimensional_array($input->options())){
			foreach($input->options() as $opt_group_label => $opt_group){
				$opt_group_label = esc_attr($opt_group_label);
				$html.=EEH_Formatter::nl(1) . "<optgroup label='{$opt_group_label}'>";
				$html.=$this->_display_options($opt_group);
				$html.=EEH_Formatter::nl(-1) . "</optgroup>";
			}
		}else{
			$html.=$this->_display_options($input->options());
		}

		$html.= EEH_Formatter::nl(-1) . "</select>";
		return $html;
	}
	/**
	 * Displays a falt list of options as option tags
	 * @param type $options
	 * @return string
	 */
	protected function _display_options($options){
		EE_Registry::instance()->load_helper('Formatter');
		$html = '';
		foreach($options as $value => $display_text){
			$cntr = 0;
			if($this->_check_if_option_selected($value)){
				$selected_attr = 'selected="selected"';
			}else{
				$selected_attr ='';
			}
			$tabs = $cntr == 0 ? 1 : 0;
			$value_in_form = esc_attr( $this->_input->get_normalization_strategy()->unnormalize( $value ) );
			$html.= EEH_Formatter::nl($tabs) . "<option value='$value_in_form' $selected_attr>$display_text</option>";
			$cntr++;
		}
		return $html;
	}
	/**
	 * Checks if that value is the one selected
	 * @param string|int $value
	 * @return boolean
	 */
	protected function _check_if_option_selected($value){
		$equality = ($this->_input->normalized_value() == $value);
		return $equality;
	}
}