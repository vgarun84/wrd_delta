<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * This class will be used to generate the form elements
 * User: Arun N
 * Date: 12/8/2015
 * Time: 7:18 PM
 */

namespace Forms;


class FormCreator
{

    /**
     * @var instance of this class
     */
    private static $instance = null;

    /**
     * @array $form_elements
     */

    private $form_elements = array();

    /**
     * FormCreator constructor.
     */
    protected function __construct()
    {
        //--
    }

    /**
     * Returns FormCreator instance throuh out life cycle.
     *
     * @return FormCreator
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Form Declaration
     *
     * Creates the opening portion of the form.
     *
     * @param	string	the URI segments of the form destination
     * @param	array	a key/value pair of attributes
     * @param	array	a key/value pair hidden data
     * @return	FormCreator itself
     */
    public function form_open($action = '', $attributes = array(), $hidden = array())
    {
        // If no action is provided then set to the current url
        if ( ! $action)
        {
            $action = site_url($_SERVER['REQUEST_URI']);
        }
        // If an action is not a full URL then turn it into one
        elseif (strpos($action, '://') === FALSE)
        {
            $action = site_url($action);
        }

        $attributes = _attributes_to_string($attributes);

        if (stripos($attributes, 'method=') === FALSE)
        {
            $attributes .= ' method="post"';
        }

        if (stripos($attributes, 'accept-charset=') === FALSE)
        {
            $attributes .= ' accept-charset="'.strtolower(DEFAUL_FORM_CHARSET).'"';
        }

        $form = '<form action="'.$action.'"'.$attributes.">\n";

        if (is_array($hidden))
        {
            foreach ($hidden as $name => $value)
            {
                $form .= '<input type="hidden" name="'.$name.'" value="'.html_escape($value).'" style="display:none;" />'."\n";
            }
        }

        $this->form_elements[] = $form;

        return $this;
    }

    /**
     * Form Close Tag
     *
     * @param	string
     * @return	FormCreator itself
     */
    public function form_close($extra = '')
    {
        $this->form_elements[] = '</form>'.$extra;

        return $this;
    }

    /**
     * Form Declaration - Multipart type
     *
     * Creates the opening portion of the form, but with "multipart/form-data".
     *
     * @param	string	the URI segments of the form destination
     * @param	array	a key/value pair of attributes
     * @param	array	a key/value pair hidden data
     * @return	FormCreator itself
     */
    public function form_open_multipart($action = '', $attributes = array(), $hidden = array())
    {
        if (is_string($attributes))
        {
            $attributes .= ' enctype="multipart/form-data"';
        }
        else
        {
            $attributes['enctype'] = 'multipart/form-data';
        }

        return $this->form_open($action, $attributes, $hidden);
    }

    /**
     * Hidden Input Field
     *
     * Generates hidden fields. You can pass a simple key/value string or
     * an associative array with multiple values.
     *
     * @param	mixed	$name		Field name
     * @param	string	$value		Field value
     * @param	bool	$recursing
     * @return	FormCreator Itself
     */
    public function form_hidden($name, $value = '', $recursing = FALSE)
    {
        static $form;

        if ($recursing === FALSE)
        {
            $form = "\n";
        }

        if (is_array($name))
        {
            foreach ($name as $key => $val)
            {
                form_hidden($key, $val, TRUE);
            }

            $this->form_elements[] = $form;

            return $this;
        }

        if ( ! is_array($value))
        {
            $form .= '<input type="hidden" name="'.$name.'" value="'.html_escape($value)."\" />\n";
        }
        else
        {
            foreach ($value as $k => $v)
            {
                $k = is_int($k) ? '' : $k;
                form_hidden($name.'['.$k.']', $v, TRUE);
            }
        }

        $this->form_elements[] = $form;

        return $this;
    }

    /**
     * Text Input Field
     *
     * @param	mixed
     * @param	string
     * @param	mixed
     * @return	FormCreator itself
     */
    public function form_input($data = '', $value = '', $extra = '')
    {
        $defaults = array(
            'type' => 'text',
            'name' => is_array($data) ? '' : $data,
            'value' => $value
        );

        $this->form_elements[] = '<input '._parse_form_attributes($data, $defaults)._attributes_to_string($extra)." />\n";

        return $this;
    }

    /**
     * Password Field
     *
     * Identical to the input function but adds the "password" type
     *
     * @param	mixed
     * @param	string
     * @param	mixed
     * @return	FormCreator itself
     */
    public function form_password($data = '', $value = '', $extra = '')
    {
        is_array($data) OR $data = array('name' => $data);
        $data['type'] = 'password';
        return $this->form_input($data, $value, $extra);
    }

    /**
     * Textarea field
     *
     * @param	mixed	$data
     * @param	string	$value
     * @param	mixed	$extra
     * @return	FormCreator itself
     */
    public function form_textarea($data = '', $value = '', $extra = '')
    {
        $defaults = array(
            'name' => is_array($data) ? '' : $data,
            'cols' => '40',
            'rows' => '10'
        );

        if ( ! is_array($data) OR ! isset($data['value']))
        {
            $val = $value;
        }
        else
        {
            $val = $data['value'];
            unset($data['value']); // textareas don't use the value attribute
        }

        $this->form_elements[] = '<textarea '._parse_form_attributes($data, $defaults)._attributes_to_string($extra).'>'
            .html_escape($val)
            ."</textarea>\n";

        return $this;

    }

    /**
     * Multi-select menu
     *
     * @param	string
     * @param	array
     * @param	mixed
     * @param	mixed
     * @return	FormCreator itself
     */
    public function form_multiselect($name = '', $options = array(), $selected = array(), $extra = '')
    {
        $extra = _attributes_to_string($extra);
        if (stripos($extra, 'multiple') === FALSE)
        {
            $extra .= ' multiple="multiple"';
        }

        return $this->form_dropdown($name, $options, $selected, $extra);
    }

    /**
     * Drop-down Menu
     *
     * @param	mixed	$data
     * @param	mixed	$options
     * @param	mixed	$selected
     * @param	mixed	$extra
     * @return	FormCreator itself
     */
    public function form_dropdown($data = '', $options = array(), $selected = array(), $extra = '')
    {
        $defaults = array();

        if (is_array($data))
        {
            if (isset($data['selected']))
            {
                $selected = $data['selected'];
                unset($data['selected']); // select tags don't have a selected attribute
            }

            if (isset($data['options']))
            {
                $options = $data['options'];
                unset($data['options']); // select tags don't use an options attribute
            }
        }
        else
        {
            $defaults = array('name' => $data);
        }

        is_array($selected) OR $selected = array($selected);
        is_array($options) OR $options = array($options);

        // If no selected state was submitted we will attempt to set it automatically
        if (empty($selected))
        {
            if (is_array($data))
            {
                if (isset($data['name'], $_POST[$data['name']]))
                {
                    $selected = array($_POST[$data['name']]);
                }
            }
            elseif (isset($_POST[$data]))
            {
                $selected = array($_POST[$data]);
            }
        }

        $extra = _attributes_to_string($extra);

        $multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

        $form = '<select '.rtrim(_parse_form_attributes($data, $defaults)).$extra.$multiple.">\n";

        foreach ($options as $key => $val)
        {
            $key = (string) $key;

            if (is_array($val))
            {
                if (empty($val))
                {
                    continue;
                }

                $form .= '<optgroup label="'.$key."\">\n";

                foreach ($val as $optgroup_key => $optgroup_val)
                {
                    $sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
                    $form .= '<option value="'.html_escape($optgroup_key).'"'.$sel.'>'
                        .(string) $optgroup_val."</option>\n";
                }

                $form .= "</optgroup>\n";
            }
            else
            {
                $form .= '<option value="'.html_escape($key).'"'
                    .(in_array($key, $selected) ? ' selected="selected"' : '').'>'
                    .(string) $val."</option>\n";
            }
        }

        $this->form_elements[] = $form."</select>\n";

        return $this;
    }

    /**
     * Checkbox Field
     *
     * @param	mixed
     * @param	string
     * @param	bool
     * @param	mixed
     * @param	string
     * @return	FormCreator Itself
     */
    public function form_checkbox($data = '', $value = '', $checked = FALSE, $extra = '', $lable_text='')
    {
        $defaults = array('type' => 'checkbox', 'name' => ( ! is_array($data) ? $data : ''), 'value' => $value);

        if (is_array($data) && array_key_exists('checked', $data))
        {
            $checked = $data['checked'];

            if ($checked == FALSE)
            {
                unset($data['checked']);
            }
            else
            {
                $data['checked'] = 'checked';
            }
        }

        if ($checked == TRUE)
        {
            $defaults['checked'] = 'checked';
        }
        else
        {
            unset($defaults['checked']);
        }

        $this->form_elements[] = '<input '._parse_form_attributes($data, $defaults)._attributes_to_string($extra)." />\n".$lable_text;

        return $this;
    }

    /**
     * Radio Button
     *
     * @param	mixed
     * @param	string
     * @param	bool
     * @param	mixed
     * @return	FormCreator Itself
     */
    public function form_radio($data = '', $value = '', $checked = FALSE, $extra = '', $lable_text)
    {
        is_array($data) OR $data = array('name' => $data);
        $data['type'] = 'radio';

        return $this->form_checkbox($data, $value, $checked, $extra, $lable_text);
    }

    /**
     * Submit Button
     *
     * @param	mixed
     * @param	string
     * @param	mixed
     * @return	FormCreator Itself
     */
    public function form_submit($data = '', $value = '', $extra = '')
    {
        $defaults = array(
            'type' => 'submit',
            'name' => is_array($data) ? '' : $data,
            'value' => $value
        );

        $this->form_elements[] = '<input '._parse_form_attributes($data, $defaults)._attributes_to_string($extra)." />\n";

        return $this;
    }

    /**
     * Reset Button
     *
     * @param	mixed
     * @param	string
     * @param	mixed
     * @return	FormCreator Itself
     */
    public function form_reset($data = '', $value = '', $extra = '')
    {
        $defaults = array(
            'type' => 'reset',
            'name' => is_array($data) ? '' : $data,
            'value' => $value
        );

        $this->form_elements[] = '<input '._parse_form_attributes($data, $defaults)._attributes_to_string($extra)." />\n";

        return $this;
    }

    /**
     * Form Button
     *
     * @param	mixed
     * @param	string
     * @param	mixed
     * @return	FormCreator Itself
     */
    public function form_button($data = '', $content = '', $extra = '')
    {
        $defaults = array(
            'name' => is_array($data) ? '' : $data,
            'type' => 'button'
        );

        if (is_array($data) && isset($data['content']))
        {
            $content = $data['content'];
            unset($data['content']); // content is not an attribute
        }

        $this->form_elements[] = '<button '._parse_form_attributes($data, $defaults)._attributes_to_string($extra).'>'
            .$content
            ."</button>\n";

        return $this;
    }

    /**
     * Form Label Tag
     *
     * @param	string	The text to appear onscreen
     * @param	string	The id the label applies to
     * @param	string	Additional attributes
     * @return	FormCreator Itself
     */
    public function form_label($label_text = '', $id = '', $attributes = array())
    {

        $label = '<label';

        if ($id !== '')
        {
            $label .= ' for="'.$id.'"';
        }

        if (is_array($attributes) && count($attributes) > 0)
        {
            foreach ($attributes as $key => $val)
            {
                $label .= ' '.$key.'="'.$val.'"';
            }
        }

        $this->form_elements[] = $label.'>'.$label_text.'</label>';

        return $this;
    }

    /**
     * Fieldset Tag
     *
     * Used to produce <fieldset><legend>text</legend>.  To close fieldset
     * use form_fieldset_close()
     *
     * @param	string	The legend text
     * @param	array	Additional attributes
     * @return	FormCreator Itself
     */
    public function form_fieldset($legend_text = '', $attributes = array())
    {
        $fieldset = '<fieldset'._attributes_to_string($attributes).">\n";
        if ($legend_text !== '')
        {
            $fieldset = $fieldset.'<legend>'.$legend_text."</legend>\n";
        }

        $this->form_elements[] = $fieldset;

        return $this;
    }

    /**
     * Fieldset Close Tag
     *
     * @paraum	string
     * @return	FormCreator Itself
     */
    public function form_fieldset_close($extra = '')
    {
        $this->form_elements[] = '</fieldset>'.$extra;

        return $this;
    }

    /**
     * run and render the form elements
     *
     */
    public function render()
    {
        $str_elements =  implode('', $this->form_elements);
        unset($this->form_elements);
        echo $str_elements;
    }

    /**
     * return form elements
     *
     * @string $str_elements
     */
    public function return_form()
    {
        $str_elements =  implode('', $this->form_elements);
        unset($this->form_elements);
        return $str_elements;
    }

}