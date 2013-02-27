<?php

/**
 * SetnGeti
 *
 * Requires PHP version 5.4
 *
 * @category  Tests
 * @package   SetnGeti
 * @author    Guido van Biemen <guidovanbiemen@gmail.com>
 * @copyright 2012 Guido van Biemen
 * @license   http://www.gnu.org/licenses/lgpl.html GNU LGPL
 * @link      http://github.com/guidovanbiemen/setngeti/ SetnGeti
 */

spl_autoload_register(function ($class) {
    $filename = stream_resolve_include_path(ltrim(str_replace('\\', '/', $class), '/') . '.php');
    if ($filename) {
        include $filename;
    }

    return class_exists($class, false) || interface_exists($class, false);
});
