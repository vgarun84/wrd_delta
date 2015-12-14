<?php
/**
 * Created by PhpStorm.
 * User: Arun N
 * Date: 12/9/2015
 * Time: 6:04 PM
 */

/**
 * Returns the Site URL
 *
 * @param string $str_uri_action The input string uri action
 * @return string
 */
function site_url($str_uri_action = '')
{
   return SITE_URL."/".$str_uri_action;
}
/**
 * Returns HTML escaped variable.
 *
 * @param	mixed	$var		The input string or array of strings to be escaped.
 * @param	bool	$double_encode	$double_encode set to FALSE prevents escaping twice.
 * @return	mixed			The escaped string or array of strings as a result.
 */
function html_escape($var, $double_encode = TRUE)
{
    if (empty($var))
    {
        return $var;
    }

    if (is_array($var))
    {
        foreach (array_keys($var) as $key)
        {
            $var[$key] = html_escape($var[$key], $double_encode);
        }

        return $var;
    }

    return htmlspecialchars($var, ENT_QUOTES, config_item('charset'), $double_encode);
}

/**
 * Returns the specified config item
 *
 * @param	string
 * @return	mixed
 */
function config_item($item)
{
     $_config = array
     (
         'charset' => DEFAUL_FORM_CHARSET
     );


    return isset($_config[0][$item]) ? $_config[0][$item] : NULL;
}

/**
 * Attributes To String
 *
 * Helper function used by some of the form helpers
 *
 * @param	mixed
 * @return	string
 */
function _attributes_to_string($attributes)
{
    if (empty($attributes))
    {
        return '';
    }

    if (is_object($attributes))
    {
        $attributes = (array) $attributes;
    }

    if (is_array($attributes))
    {
        $atts = '';

        foreach ($attributes as $key => $val)
        {
            $atts .= ' '.$key.'="'.$val.'"';
        }

        return $atts;
    }

    if (is_string($attributes))
    {
        return ' '.$attributes;
    }

    return FALSE;
}

/**
 * Parse the form attributes
 *
 * Helper function used by some of the form helpers
 *
 * @param	array	$attributes	List of attributes
 * @param	array	$default	Default values
 * @return	string
 */
function _parse_form_attributes($attributes, $default)
{
    if (is_array($attributes))
    {
        foreach ($default as $key => $val)
        {
            if (isset($attributes[$key]))
            {
                $default[$key] = $attributes[$key];
                unset($attributes[$key]);
            }
        }

        if (count($attributes) > 0)
        {
            $default = array_merge($default, $attributes);
        }
    }

    $att = '';

    foreach ($default as $key => $val)
    {
        if ($key === 'value')
        {
            $val = html_escape($val);
        }
        elseif ($key === 'name' && ! strlen($default['name']))
        {
            continue;
        }

        $att .= $key.'="'.$val.'" ';
    }

    return $att;
}

