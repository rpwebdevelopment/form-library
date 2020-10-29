<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 29/10/2020
 * Time: 12:37
 */

namespace FormLibrary\Src;

use Volnix\CSRF\CSRF;

class FormBuilder
{
    public $csrf = '';

    public $fields = [];

    /**
     * FormBuilder constructor.
     * @param bool $error
     */
    public function __construct($error = false)
    {
        $this->open($error);
    }

    /**
     * loadHtml - return html string from given template file
     *
     * @param $file
     * @return string
     */
    protected function loadHtml($file) : string
    {
        return file_get_contents(__DIR__ . '/../templates/forms/components/' . $file . '.html');
    }

    /**
     * write - compile instance form into singular html string
     *
     * @return string
     */
    public function write() : string
    {
        $html = '';
        $this->fields[] = $this->close();
        foreach ($this->fields as $field) {
            $html .= $field;
        }
        return $html;
    }

    /**
     * open - populate form opening tags and add CSRF token
     *
     * @param bool $error
     * @return string
     */
    public function open($error = false) : string
    {
        $html = $this->loadHtml(__FUNCTION__);
        if ($error) {
            $alert = $this->loadHtml('alert');
            $html .= sprintf($alert, $error);
        }
        $this->fields[] = $html .= $this->csrf = CSRF::getHiddenInputString();
        return $html;
    }

    /**
     * close - populate form closing tag
     *
     * @return string
     */
    public function close() : string
    {
        return $this->loadHtml(__FUNCTION__);
    }

    /**
     * generateDefault - reusable method to prevent code duplication
     *
     * @param string $file
     * @param bool $required
     * @param array $custom
     * @param bool $error
     * @return array
     */
    protected function generateDefault($file = '', $required = false, $custom = [], $error = false) : array
    {
        return [
            'html' => $this->loadHtml($file),
            'required' => (($required) ? 'required="required"' : ''),
            'custom' => $this->customString($custom),
            'class' => (($error) ? ' is-invalid' : '')
        ];
    }

    /**
     * customString - generate custom string if any additional parameters are given
     *
     * @param array $custom
     * @return string
     */
    public function customString($custom = []) : string
    {
        $custom_string = '';
        foreach ($custom as $key => $value) {
            $custom_string .= $key . '="' . $value . '" ';
        }
        return $custom_string;
    }

    /**
     * stringify - generate valid snake case string
     *
     * @param $str
     * @return string
     */
    public function stringify($str) : string
    {
        return strtolower(
            str_replace(' ', '_',
                preg_replace('~(?<=\\w)([A-Z])~', '_$1', $str)
            )
        );
    }

    /**
     * error - load and populate error template
     *
     * @param string $error
     * @return string
     */
    public function error($error = '') : string
    {
        $html = $this->loadHtml(__FUNCTION__);
        return sprintf($html, $error);
    }

    /**
     * text - add text input to form
     *
     * @param bool $error
     * @param null $val
     * @param string $name
     * @param string $id
     * @param string $label
     * @param bool $required
     * @param array $custom
     * @return string
     */
    public function text($error = false, $val = null, $name = '', $id = '', $label = '', $required = false, $custom = []) : string
    {
        $default = $this->generateDefault(__FUNCTION__, $required, $custom, $error);
        $this->fields[] = $html = sprintf(
            $default['html'], $name, $label, $default['class'], $val, $id, $name, $default['required'], $default['custom'], $error
        );
        return $html;
    }

    /**
     * number - add numeric input to form
     *
     * @param bool $error
     * @param null $val
     * @param string $name
     * @param string $id
     * @param string $label
     * @param bool $required
     * @param array $custom
     * @return string
     */
    public function number($error = false, $val = null, $name = '', $id = '', $label = '', $required = false, $custom = []) : string
    {
        $default = $this->generateDefault(__FUNCTION__, $required, $custom, $error);
        $this->fields[] = $html = sprintf(
            $default['html'], $name, $label, $default['class'], $val, $id, $name, $default['required'], $default['custom'], $error
        );
        return $html;
    }

    /**
     * email - add email input to form
     *
     * @param bool $error
     * @param null $val
     * @param string $name
     * @param string $id
     * @param string $label
     * @param bool $required
     * @param array $custom
     * @return string
     */
    public function email($error = false, $val = null, $name = '', $id = '', $label = '', $required = false, $custom = []) : string
    {
        $default = $this->generateDefault(__FUNCTION__, $required, $custom, $error);
        $this->fields[] = $html = sprintf(
            $default['html'], $name, $label, $default['class'], $val, $id, $name, $default['required'], $default['custom'], $error
        );
        return $html;
    }

    /**
     * option - return options for select dropdown
     *
     * @param array $options
     * @param null $val
     * @return string
     */
    private function option($options = [], $val = null) : string
    {
        $html = '';
        foreach ($options as $value => $text) {
            $temp_html = $this->loadHtml(__FUNCTION__);
            $insert = ($val == $value) ? 'selected="selected"' : '';
            $html .= sprintf($temp_html, $value, $insert, $text);
        }
        return $html;
    }

    /**
     * select - add select input to form
     *
     * @param bool $error
     * @param null $val
     * @param array $options
     * @param string $name
     * @param string $id
     * @param string $label
     * @param bool $required
     * @param array $custom
     * @return string
     */
    public function select($error = false, $val = null, $options = [], $name = '', $id = '', $label = '', $required = false, $custom = []) : string
    {
        $default = $this->generateDefault(__FUNCTION__, $required, $custom, $error);
        $options = $this->option($options, $val);
        $this->fields[] = $html = sprintf(
            $default['html'], $name, $label, $default['class'], $id, $name, $default['required'], $default['custom'], $options, $error
        );
        return $html;
    }

    /**
     * textarea - add textarea input to form
     *
     * @param bool $error
     * @param null $val
     * @param string $name
     * @param string $id
     * @param string $label
     * @param int $rows
     * @param array $custom
     * @return string
     */
    public function textarea($error = false, $val = null, $name = '', $id = '', $label = '', $rows = 3, $custom = []) : string
    {
        $default = $this->generateDefault(__FUNCTION__, false, $custom, $error);
        $this->fields[] = $html = sprintf(
            $default['html'], $name, $label, $default['class'], $id, $name, $rows, $default['custom'], $val, $error
        );
        return $html;
    }

    /**
     * checkbox - add checkbox input to form
     *
     * @param bool $error
     * @param null $val
     * @param string $name
     * @param string $id
     * @param string $label
     * @param bool $required
     * @param array $custom
     * @return string
     */
    public function checkbox($error = false, $val = null, $name = '', $id = '', $label = '', $required = false, $custom = []) : string
    {
        $default = $this->generateDefault(__FUNCTION__, $required, $custom, $error);
        $insert = ($val) ? 'checked="checked"' : '';
        $this->fields[] = $html = sprintf(
            $default['html'], $default['class'], $insert, $id, $name, $default['required'], $default['custom'], $name, $label, $error
        );
        return $html;
    }

    /**
     * radio - add singular radio input to form
     *
     * @param string $name
     * @param string $id
     * @param string $label
     * @param array $custom
     * @param null $val
     * @return string
     */
    public function radio($name = '', $id = '', $label = '', $custom = [], $val = null) : string
    {
        $default = $this->generateDefault(__FUNCTION__, false, $custom, false);
        $value = $this->stringify($label);
        $insert = ($value == $val) ? 'checked' : '';
        $this->fields[] = $html = sprintf(
            $default['html'], $value, $insert, $id, $name, $default['custom'], $id, $label
        );
        return $html;
    }

    /**
     * radioGroup - add collection of radio inputs to form
     *
     * @param bool $error
     * @param null $val
     * @param string $name
     * @param string $id
     * @param array $labels
     * @param null $title
     * @param array $custom
     * @return string
     */
    public function radioGroup($error = false, $val = null, $name = '', $id = '', $labels = [], $title = null, $custom = []) : string
    {
        $html = '<div class="form-group">';
        $error = ($error) ? $this->error($error) : false;
        $this->fields[] = $html .= (!is_null($title)) ? "<p class='lead'>{$title}</p>" : '';
        $i = 1;
        foreach ($labels as $label) {
            $html .= $this->radio($name, $id . $i, $label, $custom, $val);
            $i++;
        }
        $this->fields[] = $error;
        $html .= $error;

        $this->fields[] = '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * submit - add default submit button to form
     *
     * @return string
     */
    public function submit() : string
    {
        $this->fields[] = $html = $this->loadHtml(__FUNCTION__);
        return $html;
    }
}
