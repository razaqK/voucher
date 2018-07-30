<?php
/**
 * Created by PhpStorm.
 * User: Gp
 * Date: 2/12/18
 * Time: 11:33
 */

namespace Voucher\Library;


class GUMPHelper extends \GUMP
{

    /**
     * Get error messages.
     *
     * @return array
     */
    protected function get_messages()
    {
        $parentMessages = parent::get_messages();
        $lang_file = __DIR__.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$this->lang.'.php';
        $messages = require $lang_file;

        if ($validation_methods_errors = self::$validation_methods_errors) {
            $messages = array_merge($messages, $validation_methods_errors);
        }
        $messages = array_merge($messages, $parentMessages);
        return $messages;
    }

    /**
     * This is to check if a field is an object
     *
     * @param $field
     * @param $input
     * @param null $param
     *
     * @return array
     */
    public static function validate_is_object($field, $input, $param = null)
    {
        if (!isset($input[$field]) || empty($input[$field])) {
            return;
        }

        if (is_object($input[$field])) {
            return;
        }

        return [
            'field' => $field,
            'value' => null,
            'rule' => __FUNCTION__,
            'param' => $param,
        ];
    }


    /**
     * @param $field
     * @param $input
     * @param null $param
     * @return array|void
     */
    public static function validate_is_array($field, $input, $param = null)
    {
        if (!isset($input[$field]) || empty($input[$field])) {
            return;
        }

        if (is_array($input[$field])) {
            return;
        }

        return [
            'field' => $field,
            'value' => null,
            'rule' => __FUNCTION__,
            'param' => $param,
        ];
    }

    protected function validate_field_set($field, $input, $param = null)
    {
        if (isset($input[$field])) {
            return;
        }

        return array(
            'field' => $field,
            'value' => null,
            'rule' => __FUNCTION__,
            'param' => $param,
        );
    }

    /**
     * This is to check if a field is a string
     *
     * @param $field
     * @param $input
     * @param null $param
     *
     * @return array
     */
    public static function validate_is_string($field, $input, $param = null)
    {
        if (!isset($input[$field]) || empty($input[$field])) {
            return;
        }

        if (is_string($input[$field])) {
            return;
        }

        return [
            'field' => $field,
            'value' => null,
            'rule' => __FUNCTION__,
            'param' => $param,
        ];
    }

}