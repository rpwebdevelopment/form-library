<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 29/10/2020
 * Time: 12:37
 */

namespace FormLibrary\App;

use Volnix\CSRF\CSRF;

class FormBuilder
{
    public $csrf = '';

    public $fields = [];

    public function __construct()
    {
        $this->open();
    }

    protected function loadHtml($file) : string
    {
        return file_get_contents(__DIR__ . '/../templates/forms/components/' . $file . '.html');
    }

    public function write() : string
    {
        $html = '';
        $this->fields[] = $this->close();
        foreach ($this->fields as $field) {
            $html .= $field;
        }
        return $html;
    }

    public function open() : string
    {
        $html = $this->loadHtml(__FUNCTION__);
        $this->fields[] = $html .= $this->csrf = CSRF::getHiddenInputString();
        return $html;
    }

    public function close() : string
    {
        return $this->loadHtml(__FUNCTION__);
    }

    protected function generateDefault($file = '', $required = false, $custom = [], $error = false) : array
    {
        return [
            'html' => $this->loadHtml($file),
            'required' => (($required) ? 'required="required"' : ''),
            'custom' => $this->customString($custom),
            'class' => (($error) ? ' is-invalid' : '')
        ];
    }

    public function customString($custom = []) : string
    {
        $custom_string = '';
        foreach ($custom as $key => $value) {
            $custom_string .= $key . '="' . $value . '" ';
        }
        return $custom_string;
    }

    public function error($error = '') : string
    {
        $html = $this->loadHtml(__FUNCTION__);
        return sprintf($html, $error);
    }

    public function text($name = '', $id = '', $label = '', $required = false, $custom = [], $error = false) : string
    {
        $default = $this->generateDefault(__FUNCTION__, $required, $custom, $error);
        $this->fields[] = $html = sprintf(
            $default['html'], $name, $label, $default['class'], $id, $name, $default['required'], $default['custom'], $error
        );
        return $html;
    }

    public function number($name = '', $id = '', $label = '', $required = false, $custom = [], $error = false) : string
    {
        $default = $this->generateDefault(__FUNCTION__, $required, $custom, $error);
        $this->fields[] = $html = sprintf(
            $default['html'], $name, $label, $default['class'], $id, $name, $default['required'], $default['custom'], $error
        );
        return $html;
    }

    public function email($name = '', $id = '', $label = '', $required = false, $custom = [], $error = false) : string
    {
        $default = $this->generateDefault(__FUNCTION__, $required, $custom, $error);
        $this->fields[] = $html = sprintf(
            $default['html'], $name, $label, $default['class'], $id, $name, $default['required'], $default['custom'], $error
        );
        return $html;
    }

    public function option($options = []) : string
    {
        $html = '';
        foreach ($options as $value => $text) {
            $temp_html = $this->loadHtml(__FUNCTION__);
            $html .= sprintf($temp_html, $value, $text);
        }
        return $html;
    }

    public function select($options = [], $name = '', $id = '', $label = '', $required = false, $custom = [], $error = false) : string
    {
        $default = $this->generateDefault(__FUNCTION__, $required, $custom, $error);
        $options = $this->option($options);
        $this->fields[] = $html = sprintf(
            $default['html'], $name, $label, $default['class'], $id, $name, $default['required'], $default['custom'], $options, $error
        );
        return $html;
    }

    public function textarea($name = '', $id = '', $label = '', $rows = 3, $custom = [], $error = false) : string
    {
        $default = $this->generateDefault(__FUNCTION__, false, $custom, $error);
        $this->fields[] = $html = sprintf(
            $default['html'], $name, $label, $default['class'], $id, $name, $rows, $default['custom'], $error
        );
        return $html;
    }

    public function checkbox($name = '', $id = '', $label = '', $required = false, $custom = [], $error = false) : string
    {
        $default = $this->generateDefault(__FUNCTION__, $required, $custom, $error);
        $this->fields[] = $html = sprintf(
            $default['html'], $default['class'], $id, $name, $default['required'], $default['custom'], $name, $label, $error
        );
        return $html;
    }

    public function radio($name = '', $id = '', $label = '', $custom = []) : string
    {
        $default = $this->generateDefault(__FUNCTION__, false, $custom, false);
        $this->fields[] = $html = sprintf(
            $default['html'], $id, $name, $default['custom'], $name, $label
        );
        return $html;
    }

    public function radioGroup($name = '', $id = '', $labels = [], $custom = [], $title = null, $error = false) : string
    {
        $html = '<div class="form-group">';
        $error = ($error) ? $this->error($error) : false;
        $this->fields[] = $html .= (!is_null($title)) ? "<p class='lead'>{$title}</p>" : '';
        $i = 1;
        foreach ($labels as $label) {
            $html .= $this->radio($name, $id . $i, $label, $custom, $i);
            $i++;
        }
        $this->fields[] = $error;
        $html .= $error;

        $this->fields[] = '</div>';
        $html .= '</div>';
        return $html;
    }

    public function submit() : string
    {
        $this->fields[] = $html = $this->loadHtml(__FUNCTION__);
        return $html;
    }
}
