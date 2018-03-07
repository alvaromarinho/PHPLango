<?php

class Template
{
	private static $input_attr      = array("type"  => "text", "class" => "form-control");
	private static $input_config    = array("label" => true);

	private static $select_attr     = array("class" => "form-control");
	private static $select_config   = array("label" => true, "options" => array());

	private static $textarea_attr   = array("class" => "form-control");
	private static $textarea_config = array("label" => true);

	private static $link_attr   	= array("href" => "#", "class" => "btn btn-link");
	private static $button_attr     = array("class" => "btn btn-default");
	
	private static $checkbox_config = array("inline" => true, "label" => true);
	private static $radio_config 	= array("inline" => true, "label" => true);

	/**
	 * [Create label HTML]
	 * @param  string $name   [label's name]
	 * @param  string $config [configurations]
	 * @return string
	 */
	private static function _label($name, $config)
	{
		if($config == true) {
			$html = (is_bool($config))
				? "<label for='".$name."'>".ucwords(str_replace("_", " ", $name))."</label>"
				: "<label for='".$name."'>".ucwords(str_replace("_", " ", $config))."</label>";
		} else {
			$html = "";
		}
		return $html;
	}

	/**
	 * [Create attributes HTML]
	 * @param  array  $attr [attributes and values]
	 * @return string
	 */
	private static function _attributes($attr)
	{
		$html = "";
		foreach ($attr as $key => $value) {
			$value = (is_bool($value)) ? var_export($value, true) : $value ;
			$html .= $key."='".$value."' ";
		}
		return $html;
	}

	/**
	 * [Create tag option with array]
	 * @param  array  $array_obj [Object with 2 values]
	 * @return array
	 */
	private static function _array_option($array_obj)
	{
		$array = array();
		foreach ($array_obj as $obj){
			$html = $obj->attributes();
			if(count($html) == 2){
				$key   = reset($html);
				$value = end($html);
				$array[$key] = $value;
			}
		}
		return $array;
	}

	/**
	 * Create input tag with label
	 * @param  string     $name   [attributes name and id]
	 * @param  array|null $attr   [force attributes]
	 * @param  array|null $config [configuration of object]
	 * @return string
	 */
	public static function input($name, $attr = null, $config = null)
	{
		$attr       = $attr ?: self::$input_attr;
		$config     = $config ?: self::$input_config;
		$html_label = self::_label($name, $config['label']);
		$html_attr  = self::_attributes($attr);
		$html 	    = $html_label."<input ".$html_attr." id='".$name."' name='".$name."'>";
		return $html;
	}

	/**
	 * Create select tag with label
	 * @param  string     $name   [attributes name and id]
	 * @param  array|null $attr   [force attributes]
	 * @param  array|null $config [configuration of object]
	 * @return string
	 */
	public static function select($name, $attr = null, $config = null)
	{
		$attr       = $attr ?: self::$select_attr;
		$config     = $config ?: self::$select_config;
		$options    = $config['options'] ?: array();
		$html_label = self::_label($name, $config['label']);
		$html_attr  = self::_attributes($attr);
		$disabled   = null;
		$selected   = null;

		$html = $html_label."<select ".$html_attr." id='".$name."' name='".$name."'>";

		if(is_object(reset($options)))
			$options = self::_array_option($options);

		foreach ($options as $key => $value) {
			if(isset($config['selected']) && is_array($config['selected']))
				foreach ($config['selected'] as $sel)
					if($sel == $key){
						$selected = 'selected';
						break;
					} else
						$selected = '';
			else 
				$selected = (isset($config['selected']) && $config['selected'] == $key) ? 'selected' : '';

			if(isset($config['disabled']))
				foreach ($config['disabled'] as $dis)
					if($dis == $key){
						$disabled = 'disabled';
						break;
					} else
						$disabled = '';
			else 
				$disabled = (isset($config['disabled']) && $config['disabled'] == $key) ? 'disabled' : '';

			$html 	 .= "<option value='".$key."' ".$selected." ".$disabled.">".$value."</option>";
		}
		$html .= "</select>";
		return $html;
	}

	/**
	 * Create textarea tag with label
	 * @param  string     $name   [attributes name and id]
	 * @param  array|null $attr   [force attributes]
	 * @param  array|null $config [configuration of object]
	 * @return string
	 */
	public static function textarea($name, $attr = null, $config = null)
	{
		$attr       = $attr ?: self::$textarea_attr;
		$config     = $config ?: self::$textarea_config;
		$html_label = self::_label($name, $config['label']);
		$html_attr  = self::_attributes($attr);
		$value 	    = isset($config['value']) ? $config['value'] : '';
		$html 	    = $html_label."<textarea ".$html_attr." id='".$name."' name='".$name."'>".$value."</textarea>";
		return $html;
	}
	
	/**
	 * Create input[checkbox] tag
	 * @param  string     $name   [attributes name and id]
	 * @param  array|null $attr   [force attributes]
	 * @param  array|null $config [configuration of object]
	 * @return string
	 */
	public static function checkbox($name, $attr = null, $config = null)
	{
		$attr = $attr ?: '';
		if(isset($attr['class'])){
			$class = $attr['class'];
			unset($attr['class']);
		} else
			$class = '';
		if(isset($attr['type']))
			unset($attr['type']);
		$html_attr = self::_attributes($attr);
		$config    = $config ?: self::$checkbox_config;
		$inline    = ($config['inline'] == true) ? 'form-check-inline' : '';
		if(isset($config['label']) && !is_bool($config['label']))
			$label = ucwords(str_replace("_", " ", $config['label']));
		else if(isset($config['label']) && is_bool($config['label']) && $config['label'] == false)
			$label = '';
		else
			$label = ucwords(str_replace("_", " ", $name));
		$html 	   = "<div class='form-check ".$inline."'><label class='form-check-label'><input type='checkbox' class='form-check-input ".$class."' ".$html_attr." name='".$name."'>".$label."</label></div>";
		return $html;
	}

	/**
	 * Create input[radio] tag
	 * @param  string     $name   [attributes name and id]
	 * @param  array|null $attr   [force attributes]
	 * @param  array|null $config [configuration of object]
	 * @return string
	 */
	public static function radio($name, $attr = null, $config = null)
	{
		$attr = $attr ?: '';
		if(isset($attr['type']))
			unset($attr['type']);
		$html_attr = self::_attributes($attr);
		$config    = $config ?: self::$radio_config;
		if(isset($config['label']) && !is_bool($config['label']))
			$label = ucwords(str_replace("_", " ", $config['label']));
		else if(isset($config['label']) && is_bool($config['label']) && $config['label'] == false)
			$label = '';
		else
			$label = ucwords(str_replace("_", " ", $name));
		$html 	   = ($config['inline'] == true) 
			? "<label class='radio-inline'><input type='radio' ".$html_attr." name='".$name."'>".$label."</label>"
			: "<div class='radio'><label><input type='radio' ".$html_attr." name='".$name."'>".$label."</label></div>";
		return $html;
	}

	/**
	 * Create link tag
	 * @param  string     $label  [link's label]
	 * @param  string     $href   [URL]
	 * @param  array|null $attr   [force attributes]
	 * @return string
	 */
	public static function link($label, $href, $attr = null)
	{
		$attr      = $attr ?: self::$link_attr;
		$html_attr = self::_attributes($attr);
		$href	   = $href ?: '#';
		$html 	   = "<a href='".$href."' ".$html_attr.">".$label."</a>";
		return $html;
	}

	/**
	 * Create button tag
	 * @param  string     $label  [button's lavel]
	 * @param  array|null $attr   [force attributes]
	 * @return string
	 */
	public static function button($label, $attr = null)
	{
		$attr      = $attr ?: self::$button_attr;
		$html_attr = self::_attributes($attr);
		$html 	   = "<button ".$html_attr.">".$label."</button>";
		return $html;
	}
}
