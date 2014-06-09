<?php
/**
 * User: castro
 * Date: 09.06.14
 * Time: 15:20
 */
class hTemplate {

    static $mFunctionList = array();

    static function ExecuteFunction($pArguments = array()) {
        $function_name = str_replace('/','_',$pArguments[0]);
        $function_arguments = array_slice($pArguments, 1);


        if (!array_key_exists($function_name, static::$mFunctionList) ){
            $path_parts = explode('/', $pArguments[0]);
            $last_path_part = $path_parts[count($path_parts) -1];
            $template_path = VIEWS_PATH.str_replace($last_path_part, 'templates/'.$last_path_part , $pArguments[0]).'.php';
            static::$mFunctionList[$function_name] = create_function('$template_arguments', 'include("'.$template_path.'");');
        }
        ob_start();
        call_user_func(static::$mFunctionList[$function_name], $function_arguments);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    static function Get () {
        return static::ExecuteFunction(func_get_args());
    }

    static function Render() {
        echo static::ExecuteFunction(func_get_args());
    }



}