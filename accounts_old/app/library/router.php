<?php
//Convert all json into $_POST

if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false)
{
    $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
}

//Core api loading scripts
function callHook()
{
    global $url;
    global $_class;
    global $db;

    $queryString = [];

    $urlArray = [];
    $urlArray = explode("/", $url);

    if ($urlArray[0] == "app")
    {
        array_shift($urlArray);
    }

    $class = $urlArray[0];
    array_shift($urlArray);

    if (isset($urlArray[0]))
    {
        $action = $urlArray[0];
        array_shift($urlArray);
    }
    else
    {
        $action = '';
    }

    $queryString = $urlArray;

    $_class = strtolower($class);

    $dispatch = new $_class();

    $_manager = new manager();

    if ((int) method_exists($_class, $action))
    {
        $db = new sql();
        $access = false;
        $valid = false;

        $verify = call_user_func_array([$_manager, "verify"], ['true']);

        if ($verify['logged_in'] == 'true')
        {
            if ((isset($verify['user_roles'][$action])) && ($verify['user_roles'][$action] === 'true'))
            {
                $valid = true;
            }
            elseif ((isset($verify['user_roles'][$action])) && ($verify['user_roles'][$action] === 'false'))
            {
                $valid = false;
            }
            else
            {
                $valid = true;
            }

            if (isset($verify['user_access'][$_class]))
            {
                $access = true;

                if ($action != 'view')
                {
                    if ($verify['user_access'][$_class] == 'true')
                    {
                        $valid = true;
                    }
                }
                else
                {
                    $valid = true;
                }
            }
            else
            {
                $access = true;
                $valid = true;
            }
        }
        elseif ($_class == 'manager')
        {
            if ($action == 'signin' || $action == 'combine' || $action == 'db_backup' || $action == 'ticketImport')
            {
                $access = true;
                $valid = true;
            }
        }
        else
        {
            $access = false;
            $valid = false;
        }

        if ($access && $valid)
        {
            $_res = call_user_func_array([$dispatch, $action], $queryString);

            // $_res['user'] = $verify['user'];

            if (isset($_res['nologin']))
            {
                unset($_res['user']);
                unset($_res['nologin']);
            }

// print_r(print_r($_res));
            if (!isset($_res['nodata']))
            {
                if (is_array($_res))
                {
                    // print_r(print_r($_res));
                    echo json_encode($_res);
                }
                else
                {
                    echo $_res;
                }
            }

            $db = null;
        }
        else
        {
            $results = ["logged_in" => "false", "user_roles" => "", "user_access" => "", "message" => 'you are no longer logged in', "redirect" => 'true'];
            echo (json_encode($results));
            exit;
        }
    }
}

function __autoload($className)
{
    if (file_exists(ROOT . DS . 'app' . DS . 'class' . DS . $className . '.php'))
    {
        require_once ROOT . DS . 'app' . DS . 'class' . DS . $className . '.php';
    }
    elseif (file_exists(ROOT . DS . 'app' . DS . 'library' . DS . $className . '.class.php'))
    {
        require_once ROOT . DS . 'app' . DS . 'library' . DS . $className . '.class.php';
    }
    else
    {
        echo 'no such class';
    }
}
