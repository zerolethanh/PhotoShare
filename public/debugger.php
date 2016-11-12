<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2016/07/20
 * Time: 2:34
 */
// debug>>>>>>>>>>>>>
function ddd($fileInfoRow = true)
{
    $logFile = '/nfs/vol05/ldapusers/9000799/0debug.log';
    if (!file_exists($logFile)) $logFile = './0debug.log';

    try {
        ob_start();

        echo '>>>>>>>>>>-----------------------------  ', date('c'), '  -----------------------------<<<<<<<<<<';
        echo PHP_EOL;
        // debug_print_backtrace ();
        var_export(func_get_args());
        echo PHP_EOL;
        $walkTrace = function ($trace, $numorder)use($fileInfoRow) {
            /*
             * $trace
             * Possible returned elements from debug_backtrace &Name; &Type; &Description;
             * function string The current function name. See also __FUNCTION__.
             * line integer The current line number. See also __LINE__.
             * file string The current file name. See also __FILE__.
             * class string The current class name. See also __CLASS__
             * object object The current object.
             * type string The current call type. If a method call, "->" is returned. If a static method call, "::" is returned. If a function call, nothing is returned.
             * args array If inside a function, this lists the functions arguments. If inside an included file, this lists the included file name(s).
             */
            $file = isset($trace['file']) ? $trace['file'] : null;
            $line = isset($trace['line']) ? $trace['line'] : null;
            $class = isset($trace['class']) ? $trace['class'] : null;
            $type = isset($trace['type']) ? $trace['type'] : null;
            $function = isset($trace['function']) ? $trace['function'] : null;
//            $args = isset($trace['args']) ? print_r(array_slice($trace['args'], 0, 1), true) : null;

            $fileInfoRow = $fileInfoRow ?  "{$file} :{$line}" : null;
            echo <<<STRING
#{$numorder} {$fileInfoRow}
        {$class} {$type} {$function} ()
STRING;
            echo PHP_EOL . PHP_EOL;
        };

        $traces = array_reverse(debug_backtrace());
        print_r(array_walk($traces, $walkTrace));
//        dd($traces);

        echo '>>>>>>>>>>-----------------------------  ', date('c'), '  -----------------------------<<<<<<<<<<';
        echo PHP_VERSION . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
        // get content write to file
        $trace = ob_get_contents();
        ob_end_clean();
        error_log($trace, 3, $logFile);
    } catch (Exception $e) {
        error_log(print_r($e->getMessage()), 3, $logFile);
    }
} // <<<<<<<<<<<<<<<<<<debug


// debug>>>>>>>>>>>>>
function dbacktrace()
{
    $logFile = '/nfs/vol05/ldapusers/9000799/0debug.log';
    if (!file_exists($logFile)) $logFile = './0debug.log';

    try {
        ob_start();

        echo '>>>>>>>>>>-----------------------------  ', date('c'), '  -----------------------------<<<<<<<<<<';
        echo PHP_EOL;
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        //dd($traces);

        echo '>>>>>>>>>>-----------------------------  ', date('c'), '  -----------------------------<<<<<<<<<<';
        echo PHP_VERSION . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
        // get content write to file
        $trace = ob_get_contents();
        ob_end_clean();
        error_log($trace, 3, $logFile);
    } catch (Exception $e) {
        error_log(print_r($e->getMessage()), 3, $logFile);
    }
} // <<<<<<<<<<<<<<<<<<debug