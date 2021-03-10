<?php

spl_autoload_register(function ($class) {
  $base_dir = '../';
  $file = $base_dir . str_replace('\\', '/', $class) . '.php';
  if (file_exists($file)) {
    require $file;
  } else {
    echo '<b>Class:</b> '.$class.' is not registered in spl_autoload_register()<br>';
    echo '<b>Location:</b> '.$file.'<br>';
  }
});