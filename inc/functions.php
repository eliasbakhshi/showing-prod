<?php

if ( ALLOWED ) {

  /**
   * @param string $name      Name of the parameter in the URL.
   * @param string $default   Return an empty string if there is no parameter with the given name.
   */
  function LoadString (string $name, string $default = '') {
    if (isset($_REQUEST[$name])) {
      $GLOBALS[str_replace('-', '_', $name)] = addslashes($_REQUEST[$name]);
    } else {
      $GLOBALS[str_replace('-', '_', $name)] = $default;
    }
  }

  /**
   * @param string $name      Name of the parameter in the URL.
   * @param int $default      Return an empty int if there is no parameter with the given name.
   */
  function LoadInt (string $name, int $default = 0) {
    if (isset($_REQUEST[$name])) {
      $GLOBALS[str_replace('-', '_', $name)] = (int)addslashes($_REQUEST[$name]);
    } else {
      $GLOBALS[str_replace('-', '_', $name)] = (int)$default;
    }
  }

  /**
   * @param string $number           The number that you want to round up.
   * @return number                  Returns the rounded number.
   */
  function roundUp($number,$x=10) {
    return (ceil($number)%$x === 0) ? ceil($number) : round(($number+$x/2)/$x)*$x;
  }
} else {
  echo "Permission denied.";
}

