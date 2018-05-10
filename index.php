<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

// define('ASSETS_DIR', ROOT . DS . 'template' . DS . 'assets');
// define('CORE_ASSETS_DIR', ROOT . DS . 'assets');
$shortcode_tags = ['container', 'row', 'column', 'form', 'carousel', 'pagebreak', 'gallery', 'endcolumn', 'endrow', 'endcontain', 'feed', 'map', 'dirbutton'];

if (isset($_GET['url']))
{
    $url = $_GET['url'];
}

include ROOT . DS . 'config' . DS . 'config.php';

// include ROOT . DS . 'config' . DS . 'variables.php';

if (file_exists(ROOT . DS . 'config' . DS . 'install.php'))
{
    include ROOT . DS . 'config' . DS . 'install.php';
    rename(ROOT . DS . 'config' . DS . 'install.php', ROOT . DS . 'config' . DS . 'installed.php');

    if (isset($_COOKIE['cart']))
    {
        setcookie("cart", "", time() - 1);
        echo '<br>cart cookie unset';
    }

    echo '<br /><br /><p>Innitial setup completed!</p>';
    exit();
}
else
{
    include ROOT . DS . 'application' . DS . 'templates' . DS . 'html.htm';
    // callHook();
}
