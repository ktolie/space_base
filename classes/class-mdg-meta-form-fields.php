<?php
/**
 * MDG Meta Form Fields Class.
 */

/**
 * Contains all of the custom meta form fields.
 *
 * @package      WordPress
 * @subpackage   MDG_Base
 *
 * @author       Matchbox Design Group <info@matchboxdesigngroup.com>
 */
class MDG_Meta_Form_Fields extends MDG_Generic {
	/**
	 * Prefix for the multi input form field.
	 *
	 * @var  string
	 */
	public $multi_input_prefix = 'mdg_multi';

	/**
	 * Class constructor
	 *
	 * @param array   $config Class configuration
	 */
	function __construct( $config = array() ) {
		parent::__construct();

		$this->_add_class_mdg_form_fields_actions_filters();
	} // __construct()



	/**
	 * Add actions & filters
	 */
	private function _add_class_mdg_form_fields_actions_filters() {
		add_action( 'wp_ajax_mdg_form_field_multi_input_add', array( &$this, 'mdg_form_field_multi_input_add_callback' ) );
	} // _add_class_mdg_form_fields_actions_filters()







	/**
	 * Creates a label for a form input field.
	 *
	 * <code>$this->label( $label, $id );</code>
	 *
	 * @since   0.2.3
	 *
	 * @param   string  $id     The input fields ID value.
	 * @param   string  $label  The text to be displayed in the label.
	 *
	 * @return  string          The label for the form input field.
	 */
	public function label( $id, $label ) {
		if ( $label == '' ) {
			return '';
		} // if()

		return "<label for='{$id}' class='pull-left'>{$label}</label>";
	} // label()



	/**
	 * Merges two sets of attributes together and combines them for a HTML element.
	 *
	 * <code>$input_attrs = $this->merge_element_attributes( $default_attrs, $attrs );</code>
	 *
	 * @since   0.2.3
	 *
	 * @param   array   $defaults  The default attributes.
	 * @param   array   $attrs     The supplied attributes
	 *
	 * @return  string             The merged HTML element attributes.
	 */
	public function merge_element_attributes( $defaults, $attrs ) {
		if ( gettype( $defaults ) != 'array' or gettype( $attrs ) != 'array' ) {
			return $attrs;
		} // if()

		// Merge the attributes together
		foreach ( $defaults as $key => $value ) {
			if ( isset( $attrs[$key] ) ) {
				$attrs[$key] = "{$attrs[$key]} {$value}";
			} else {
				$attrs[$key] = $value;
			} // if/else()
		} // foreach()

		// Flatten the attributes
		$input_attrs = array();
		foreach ( $attrs as $attr => $attr_value ) {
			$attr_value    = ( $attr == 'class' ) ? "{$attr_value} pull-left" : $attr_value;
			$input_attrs[] = esc_attr( "{$attr}={$attr_value}" );
		} // foreach()
		$input_attrs = trim( implode( ' ', $input_attrs ) );

		return $input_attrs;
	} // merge_element_attributes()



	/**
	 * Creates a description for a form input field.
	 *
	 * <code>$this->description( $description, $id );</code>
	 *
	 * @since   0.2.3
	 *
	 * @param   string  $description  The text to be displayed in the description.
	 *
	 * @return  string          The description for the form input field.
	 */
	public function description( $description ) {
		if ( $description == '' ) {
			return '';
		} // if()

		return "<p class='description'>{$description}</p>";
	} // description()



	/**
	 * Creates a HTML text field and description.
	 *
	 * @param string  $id    id attribute
	 * @param string  $meta  meta value
	 * @param string  $desc  description
	 * @param array   $attrs Input attributes.
	 *
	 * @return string       The text field and description
	 */
	public function text_field( $id, $meta, $desc, $attrs = array() ) {
		$defaults = array(
			'class' => 'pull-left',
			'id'    => $id,
			'name'  => $id,
			'type'  => 'text',
			'size'  => '30',
		);
		$input_attrs = $this->merge_element_attributes( $defaults, $attrs );
		$text_field  = "<input {$input_attrs} value='{$meta}'>";
		$text_field .= $this->description( $desc );

		return $text_field;
	} // text_field()



	/**
	 * Creates a HTML hidden input field.
	 *
	 * @param string  $id    id attribute
	 * @param string  $meta  meta value
	 *
	 * @return string       The hidden input field and description
	 */
	public function hidden_field( $id, $meta ) {
		$attrs = array(
			'type'  => 'hidden',
		);

		$hidden_field = $this->text_field( $id, $meta, '', $attrs );

		return $hidden_field;
	} // hidden_field()



	/**
	 * Creates a HTML email field and description.
	 *
	 * @param string  $id   id attribute
	 * @param string  $meta meta value
	 * @param string  $desc description
	 *
	 * @return string       The email field and description
	 */
	public function email_field( $id, $meta, $desc ) {
		$email_field  = '<input type="email" name="'.esc_attr( $id ).'" id="'.esc_attr( $id ).'" value="'.$meta.'" size="30">';
		$email_field .= '<br>';
		$email_field .= '<div class="description">'.wp_kses( $desc, 'post' ).'</div>';

		return $email_field;
	} // email_field()



	/**
	 * Creates a HTML URL field and description.
	 *
	 * @param string  $id   id attribute
	 * @param string  $meta meta value
	 * @param string  $desc description
	 *
	 * @return string       The URL field and description
	 */
	public function url_field( $id, $meta, $desc ) {
		$url_field  = '<input type="url" name="'.esc_attr( $id ).'" id="'.esc_attr( $id ).'" value="'.$meta.'" size="30">';
		$url_field .= '<br>';
		$url_field .= '<div class="description">'.wp_kses( $desc, 'post' ).'</div>';

		return $url_field;
	} // email_field()



	/**
	 * Creates a color picker.
	 *
	 * @param string  $id   id attribute
	 * @param string  $meta meta value
	 * @param string  $desc description
	 *
	 * @return string       The color picker and description
	 */
	public function color_picker( $id, $meta, $desc ) {
		$color_picker  = '<input type="text" name="'.esc_attr( $id ).'" id="'.esc_attr( $id ).'" value="'.$meta.'" size="30" class="mdg-color-picker">';
		$color_picker .= '<br>';
		$color_picker .= '<div class="description">'.wp_kses( $desc, 'post' ).'</div>';

		return $color_picker;
	} // color_picker()



	/**
	 * Creates a HTML input field and description.
	 *
	 * @param string  $id       id attribute
	 * @param string  $file_src meta value
	 * @param string  $desc     description
	 *
	 * @return string            The input field and description
	 */
	public function file_upload_field( $id, $file_src, $desc ) {
		$image_thumbnail = $this->file_upload_field_thumbnail( $file_src );

		$input_field  = '<div id="meta_upload_'.esc_attr( $id ).'" class="mdg-meta-upload">';
		$input_field .= '<input type="text" name="'.esc_attr( $id ).'" id="'.esc_attr( $id ).'" value="'.$file_src.'" size="30" />';
		$input_field .= '<a href="#" id="meta_upload_link_'.esc_attr( $id ).'" class="upload-link button">upload</a>';
		$input_field .= '<br>';
		$input_field .= '<div class="description">'.wp_kses( $desc, 'post' ).'</div>';
		$input_field .= $image_thumbnail;
		$input_field .= '</div>';
		return $input_field;
	}



	/**
	 * Retrieves file upload field thumbnails
	 *
	 * @param string  $file_src The files source url
	 *
	 * @return string           The image HTML or an empty string
	 */
	public function file_upload_field_thumbnail( $file_src ) {
		$file_id         = $this->get_attachment_id_from_src( $file_src );
		$image_thumbnail = '';

		if ( is_null( $file_id ) )
			return '';

		if ( ! wp_attachment_is_image( $file_id ) )
			return '';

		$image_sizes = get_intermediate_image_sizes();
		$width  = get_option( 'thumbnail' . '_size_w' );
		$height = get_option( 'thumbnail' . '_size_h' );

		$image_thumbnail .= '<br>';
		$image_thumbnail .= wp_get_attachment_image(
			$file_id,
			'thumbnail',
			false,
			array(
				'width'  => '150',
				'height' => '150',
				'class'  => 'meta-upload-thumb',
			)
		);

		return $image_thumbnail;
	} // file_upload_field_thumbnail()



	/**
	 * Creates a HTML textarea and description.
	 *
	 * @param string  $id   id attribute
	 * @param string  $meta meta value
	 * @param string  $desc description
	 *
	 * @return string            The input field and description
	 */
	public function textarea( $id, $meta, $desc ) {
		$textarea  = '<textarea name="'.esc_attr( $id ).'" id="'.esc_attr( $id ).'" cols="55" rows="4">'.$meta.'</textarea>';
		$textarea .= '<br>';
		$textarea .= '<div class="description">'.wp_kses( $desc, 'post' ).'</div>';

		return $textarea;
	} // textarea()



	/**
	 * Creates a HTML checkbox and description.
	 *
	 * @param string  $id   id attribute
	 * @param string  $meta meta value
	 * @param string  $desc description
	 *
	 * @return string            The input field and description
	 */
	public function checkbox( $id, $meta, $desc ) {
		$checked   = ( $meta == 'on' ) ? ' checked="checked"' : '';
		$checkbox  = '<input type="checkbox" name="'.esc_attr( $id ).'" id="'.esc_attr( $id ).'"'.$checked.'>';
		$checkbox .= '<label for="'.esc_attr( $id ).'">&nbsp;'.wp_kses( $desc, 'post' ).'</label>';

		return $checkbox;
	} // checkbox()


	/**
	 * Creates a HTML radio and description.
	 *
	 * @param string  $id      id attribute
	 * @param string  $meta    meta value
	 * @param string  $desc    description
	 * @param array   $options select options
	 *
	 * @return string            The input field and description
	 */
	public function radio( $id, $meta, $desc, $options ) {
		$i     = 1;
		$radio = '';
		foreach ( $options as $option ) {
			extract( $option );
			$checked = ( $value == $meta ) ? ' checked="checked"' : '';
			$radio  .= '<input type="radio" name="'.esc_attr( $id ).'" id="'.esc_attr( "{$id}_{$i}" ).'" value="'.esc_attr( $value ).'"'.$checked.'>';
			$radio  .= '<label for="'.esc_attr( "{$id}_{$i}" ).'">&nbsp;'.esc_attr( $label ).'</label><br><br>';
			$i = $i + 1;
		} // foreach()
		$radio .= '<br>';
		$radio .= '<div class="description">'.wp_kses( $desc, 'post' ).'</div>';

		return $radio;
	} // radio()


	/**
	 * Creates a HTML select and description.
	 *
	 * @param string  $id      id attribute
	 * @param string  $meta    meta value
	 * @param string  $desc    description
	 * @param array   $options select options
	 *
	 * @return string            The input field and description
	 */
	public function select( $id, $meta, $desc, $options ) {
		$select = '<select name="'.esc_attr( $id ).'" id="'.esc_attr( $id ).'">';
		foreach ( $options as $option ) {
			extract( $option );
			$selected = ( $value == $meta ) ? ' selected="selected"' : '';
			$select  .= '<option value="'.esc_attr( $value ).'"'.$selected.'>'.esc_attr( $label ).'</option>';
		} // foreach()
		$select .= '</select>';
		$select .= '<br>';
		$select .= '<div class="description">'.wp_kses( $desc, 'post' ).'</div>';

		return $select;
	} // select()



	/**
	 * Creates a HTML chosen select and description.
	 *
	 * @param string  $id      id attribute
	 * @param string  $meta    meta value
	 * @param string  $desc    description
	 * @param array   $options select options
	 *
	 * @return string            The input field and description
	 */
	public function chosen_select( $id, $meta, $desc, $options ) {
		$select = '<select name="'.esc_attr( $id ).'" id="'.esc_attr( $id ).'" class="mdg-chosen-select" style="width:200px;">';
		foreach ( $options as $option ) {
			extract( $option );
			$selected = ( $value == $meta ) ? ' selected="selected"' : '';
			$select  .= '<option value="'.esc_attr( $value ).'"'.$selected.'>'.esc_attr( $label ).'</option>';
		} // foreach()

		$select .= '</select>';
		$select .= '<br>';
		$select .= '<div class="description">'.wp_kses( $desc, 'post' ).'</div>';

		return $select;
	} // chosen_select()



	/**
	 * Creates a HTML chosen select multiple and description.
	 *
	 * @param string  $id      id attribute
	 * @param string  $meta    meta value
	 * @param string  $desc    description
	 * @param array   $options select options
	 *
	 * @return string            The input field and description
	 */
	public function chosen_select_multi( $id, $meta, $desc, $options ) {
		$select = '<select name="'.esc_attr( $id ).'_multi_chosen" id="'.esc_attr( $id ).'_multi_chosen" multiple="multiple" class="mdg-chosen-select" style="width:200px;">';

		$meta_array = explode( ',', $meta );
		foreach ( $options as $option ) {
			extract( $option );
			$selected = ( in_array( $value, $meta_array ) ) ? ' selected="selected"' : '';
			$select  .= '<option value="'.esc_attr( $value ).'"'.$selected.'>'.esc_attr( $label ).'</option>';
		} // foreach()
		$select .= '</select>';
		$select .= '<input type="hidden"  name="'.esc_attr( $id ).'" id="'.esc_attr( $id ).'" value="' . esc_attr( $meta ) . '"  placeholder="" >';
		$select .= '<br>';
		$select .= '<div class="description">'.wp_kses( $desc, 'post' ).'</div>';

		return $select;
	} // chosen_select_multi()



	/**
	 * Creates a date picker.
	 *
	 * @param string  $id           Id attribute.
	 * @param string  $meta         Meta value.
	 * @param string  $desc         Description.
	 * @param string  $date_format  Optional, JavaScript date format default DD, MM d, yy.
	 *
	 * @return string       The date picker and description
	 */
	public function datepicker( $id, $meta, $desc, $date_format = 'DD, MM d, yy' ) {
		$datepicker  = '<input type="text" class="mdg-datepicker datepicker" name="'.esc_attr( $id ).'" id="'.esc_attr( $id ).'" value="'.$meta.'" size="30" data-format="'.esc_attr( $date_format ).'" />';
		$datepicker .= '<br />';
		$datepicker .= '<div class="description">'.wp_kses( $desc, 'post' ).'</div>';

		return $datepicker;
	} // datepicker()



	/**
	 * Creates a HTML text area WYSWIG editor and description.
	 *
	 * @param string  $id    id attribute
	 * @param string  $meta  meta value
	 * @param string  $desc  description
	 * @param string  $args  Customize wp_editor arguments.
	 *
	 * @return string            The text area and description
	 */
	public function wysiwg_editor( $id, $meta, $desc = '', $args = array() ) {
		$meta = html_entity_decode( $meta );
		$wysiwg_editor = '';
		$default_args  = array(
			'teeny'         => false,
			'editor_class'  => 'mdg-wyswig-editor',
			'textarea_rows' => 8,
		);
		$wp_editor_settings = array_merge( $default_args, $args );
		ob_start();
		wp_editor( $meta, $id, $wp_editor_settings );
		$wysiwg_editor .= ob_get_clean();

		$wysiwg_editor .= '<br>';
		$wysiwg_editor .= '<span class="description">'.esc_attr( $desc ).'</span>';

		return $wysiwg_editor;
	} // wysiwg_editor()



	/**
	 * Makes the multi field input
	 *
	 * @param array   $args  The input field arguments.
	 *
	 * @return string The multi input field and description.
	 */
	public function multi_input_field( $id, $meta, $desc, $fields, $attrs = array() ) {
		global $post;

		$html = '';

		if ( empty( $fields ) ) {
			return $html;
		} // if()

		$meta        = get_post_meta( $post->ID, $id, true );
		$meta        = ( $meta == '' ) ? array() : $meta;
		$prefix      = $this->multi_input_prefix;
		$field_count = count( $meta );
		$defaults    = array(
			'id'    => "{$prefix}_{$id}_wrap",
			'class' => 'mdg-multi-input-field',
		);

		$input_attrs = $this->merge_element_attributes( $defaults, $attrs );
		$data_fields = json_encode( $fields );
		$html        = "<div {$input_attrs}>";
		$html       .= '<div class="mdg-multi-input-field-add-new-wrap">';
		$html       .= "<button type='button' class='button button-primary mdg-multi-input-field-add-new' data-id='{$id}' data-fields='{$data_fields}' data-count={$field_count}>Add New Item</button>";
		$html       .= '</div>';
		array_reverse( $meta );
		foreach ( $meta as $key => $meta_field ) {
			$html .= $this->_multi_input_fields( $fields, $meta_field, $id, intval( $key ) );
		} // foreach()
		array_reverse( $meta );
		$html .= '</div>';

		return $html;
	} // multi_input_field()



	/**
	 * Multi input fields.
	 *
	 * @param   array  $fields  Fields for the multi input.
	 * @param   array  $meta    The current meta values.
	 *
	 * @return  string          The multi input fields.
	 */
	private function _multi_input_fields( $fields, $meta, $fields_id, $field_count ) {
		global $post;

		$prefix       = $this->multi_input_prefix;
		$fields_html  = '';
		$fields_html .= '<div class="mdg-field-wrap">';
		$fields_html .= '<div class="dashicons dashicons-dismiss mdg-field-wrap-remove"></div>';

		$i = 1;
		foreach ( $fields as $field ) {
			$label        = ( isset( $field['label'] ) ) ? $field['label'] : '';
			$field_id     = ( isset( $field['id'] ) ) ? $field['id'] : '';
			$field_meta   = ( isset( $meta[$field_id] ) ) ? $meta[$field_id] : '';
			$field['id']  = "{$prefix}_fieldcount{$field_count}_{$i}_{$fields_id}_{$field_id}";
			$fields_html .= '<div class="mdg-field-input-wrap">';
			$fields_html .= $this->label( $field['id'], $label );
			$fields_html .= $this->select_form_field( $field, $field_meta, false );
			$fields_html .= '</div>';

			$i = $i + 1;
		} // foreach()
		$fields_html .= '<span class="mdg-field-order-input">';
		$fields_html .= $this->hidden_field( "{$prefix}_order_fieldcount{$field_count}_{$fields_id}", $field_count );
		$fields_html .= '</span>';
		$fields_html .= '</div>';

		return $fields_html;
	} // _multi_input_fields()


	public function mdg_form_field_multi_input_add_callback() {
		// Make sure we have what we need
		if ( ! isset( $_GET['fields'] ) or ! isset( $_GET['count'] ) or ! isset( $_GET['id'] ) ) {
			wp_send_json_error();
		} // if()

		$fields = $_GET['fields'];
		$meta   = array();
		$count  = intval( $_GET['count'] );
		$id     = $_GET['id'];

		$data = array(
			'field' => $this->_multi_input_fields( $fields, $meta, $id, $count ),
		);
		wp_send_json_success( $data );
	} // mdg_form_field_multi_input_add_callback()



	public function select_form_field( $field, $meta ) {
		extract( $field );
		switch ( $type ) {
			case 'divider':
				return '<hr>';
				break;

			case 'markup':
				return $desc;
				break;

			case 'text':
				$text_field = $this->text_field( $id, $meta, $desc );
				return $text_field;
				break;

			case 'email':
				$email_field = $this->email_field( $id, $meta, $desc );
				return $email_field;
				break;

			case 'url':
				$url_field = $this->url_field( $id, $meta, $desc );
				return $url_field;
				break;

			case 'file':
				$file_upload = $this->file_upload_field( $id, $meta, $desc );
				return $file_upload;
				break;

			case 'textarea':
				$textarea = $this->textarea( $id, $meta, $desc );
				return $textarea;
				break;

			case 'checkbox':
				$checkbox = $this->checkbox( $id, $meta, $desc );
				return $checkbox;
				break;

			case 'radio':
				$radio = $this->radio( $id, $meta, $desc, $options );
				return $radio;
				break;

			case 'select':
				$select = $this->select( $id, $meta, $desc, $options );
				return $select;
				break;

			case 'chosen_select':
				$chosen_select = $this->chosen_select( $id, $meta, $desc, $options );
				return $chosen_select;
				break;

			case 'chosen_select_multi':
				$chosen_select_multi = $this->chosen_select_multi( $id, $meta, $desc, $options );
				return $chosen_select_multi;
				break;

			case 'date':
				$date_format = ( isset( $date_format ) ) ? $date_format : 'DD, MM d, yy';
				$datepicker  = $this->datepicker( $id, $meta, $desc, $date_format );
				return $datepicker;
				break;

			case 'title':
				return '<div class="form-group-title">'.esc_attr( $label ).'</div>';
				break;

			case 'wysiwg_editor':
				$meta = get_post_meta( $post->ID, $id, true );
				$args = ( isset( $args ) ) ? $args : array();
				$wysiwg_editor = $this->wysiwg_editor( $id, $meta, $desc, $args );
				return $wysiwg_editor;
				break;

			case 'multi_input':
				$multi_input_field = $this->multi_input_field( $id, $meta, $desc, $fields, $attrs );
				return $multi_input_field;
			break;

			case 'color_picker':
				$color_picker = $this->color_picker( $id, $meta, $desc );
				return $color_picker;
			break;
		} // switch()
	}
} // End class MDG_Meta_Form_Fields()

global $mdg_meta_form_fields;
$mdg_meta_form_fields = new MDG_Meta_Form_Fields();
