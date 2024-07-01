<?php

class manager
{
    public function combine($type = null)
    {
        $cache = false;
        $cachedir = ROOT . DS . 'tmp' . DS . 'cache';
        switch ($type)
        {
            case 'js':
                $elements = [
                    CORE_ASSETS_DIR . DS . 'js' . DS . "jquery.min",
                    CORE_ASSETS_DIR . DS . 'angular' . DS . "angular.min",
                    CORE_ASSETS_DIR . DS . 'angular' . DS . "angular-route.min",
                    CORE_ASSETS_DIR . DS . 'angular' . DS . "angular-animate.min",
                    CORE_ASSETS_DIR . DS . 'angular' . DS . "angular-resource.min",
                    CORE_ASSETS_DIR . DS . 'angular' . DS . "angular-sanitize.min",
                    CORE_ASSETS_DIR . DS . 'js' . DS . "chart.min",
                    CORE_ASSETS_DIR . DS . 'js' . DS . "bootstrap-datepicker.min",
                    CORE_ASSETS_DIR . DS . 'js' . DS . "chosen.min",
                    CORE_ASSETS_DIR . DS . 'js' . DS . "bootstrap-notify.min",
                    CORE_ASSETS_DIR . DS . 'angular' . DS . 'libs' . DS . "angular-bootstrap",
                    CORE_ASSETS_DIR . DS . 'angular' . DS . 'libs' . DS . "angular-chart.min",
                    CORE_ASSETS_DIR . DS . 'angular' . DS . 'libs' . DS . "angular-chosen.min",
                    CORE_ASSETS_DIR . DS . 'angular' . DS . 'libs' . DS . "dir-pagination",
                    CORE_ASSETS_DIR . DS . 'angular' . DS . 'libs' . DS . "angular-tinymce.min",
                    APP_DIR . DS . 'js' . DS . "app",
                    APP_DIR . DS . 'js' . DS . "configs",
                    APP_DIR . DS . 'js' . DS . "directives",
                    APP_DIR . DS . 'js' . DS . "factories",
                    APP_DIR . DS . 'js' . DS . "routes",
                ];
                $type = "js";
                break;
            case 'css':
                $elements = [
                    CORE_ASSETS_DIR . DS . 'css' . DS . "fonts.min",
                    CORE_ASSETS_DIR . DS . 'css' . DS . "bootstrap.min",
                    CORE_ASSETS_DIR . DS . 'css' . DS . "font-awesome.min",
                    CORE_ASSETS_DIR . DS . 'css' . DS . "angular-chart.min",
                    CORE_ASSETS_DIR . DS . 'css' . DS . "bootstrap-datepicker.min",
                    CORE_ASSETS_DIR . DS . 'css' . DS . "chosen.min",
                    CORE_ASSETS_DIR . DS . 'css' . DS . "animate",
                ];
                $type = "css";
                break;
        }

        if ($type === 'js')
        {
            $_dir = ROOT . DS . 'application' . DS . 'controls' . DS;
            $_scnaFiles = scandir($_dir);

            foreach ($_scnaFiles as $file)
            {
                if ($file != '.' && $file != '..')
                {
                    $tmp = explode('.', $file);
                    array_push($elements, $_dir . $tmp[0]);
                }
            }
        }
        elseif ($type === 'css')
        {
            $_dir = ROOT . DS . 'application' . DS . 'css' . DS;
            $_scnaFiles = scandir($_dir);

            foreach ($_scnaFiles as $file)
            {
                if ($file != '.' && $file != '..')
                {
                    $tmp = explode('.', $file);

                    array_push($elements, $_dir . $tmp[0]);
                }
            }
        }

        include ROOT . DS . 'app' . DS . 'library' . DS . 'combine.php';
    }

    public function db_backup($install = null)
    {
        $_fileName = ROOT . DS . 'tmp' . DS . 'db' . DS . 'accounts2.sql';

        // file header stuff

        if (empty($install))
        {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
            $output = 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";' . "\n\n";
            $output .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n"
                . "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n"
                . "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n"
                . "/*!40101 SET NAMES utf8mb4 */;\n\n";
            // $output .= "--\n-- Database: `" . DB_NAME . "`\n--\n";
            // get all table names in db and stuff them into an array
            $tables = [];
            $stmt = $pdo->query("SHOW TABLES");
            while ($row = $stmt->fetch(PDO::FETCH_NUM))
            {
                $tables[] = $row[0];
            }
            // process each table in the db
            foreach ($tables as $table)
            {
                $fields = "";
                $sep2 = "";
                // $output .= "\n-- " . str_repeat("-", 60) . "\n\n";
                // $output .= "--\n-- Table structure for table `$table`\n--\n\n";
                // get table create info
                $stmt = $pdo->query("SHOW CREATE TABLE $table");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $output .= '--' . $table . '--' . "\n";
                $output .= "DROP TABLE IF EXISTS `$table`;\n";
                $output .= $row[1] . ";\n\n";
                // get table data
                // $output .= "--\n-- Dumping data for table `$table`\n--\n\n";
                $stmt = $pdo->query("SELECT * FROM $table");
                while ($row = $stmt->fetch(PDO::FETCH_OBJ))
                {
                    // runs once per table - create the INSERT INTO clause
                    if ($fields == "")
                    {
                        $fields = "INSERT INTO `$table` (";
                        $sep = "";
                        // grab each field name
                        foreach ($row as $col => $val)
                        {
                            $fields .= $sep . "`$col`";
                            $sep = ", ";
                        }
                        $fields .= ") VALUES";
                        //$output .= $fields . "\n";
                    }
                    // grab table data
                    $sep = "";
                    $fields .= $sep2 . "(";
                    foreach ($row as $col => $val)
                    {
                        // add slashes to field content
                        $val = addslashes($val);
                        // replace stuff that needs replacing
                        $search = ["\'", "\n", "\r"];
                        $replace = ["''", "\n", "\r"];
                        $val = str_replace($search, $replace, $val);
                        $fields .= $sep . "'$val'";
                        $sep = ", ";
                    }
                    // terminate row data
                    $fields .= ")";
                    $sep2 = ",\n";
                }
                // terminate insert data
                if ($fields != '')
                {
                    $fields .= ";\n";
                    $output .= $fields . "\n\n";
                }
                echo 'export complete: ' . $table . '<br>';
            }
            $handle = fopen($_fileName, 'w');
            fwrite($handle, $output);
            fclose($handle);
            // echo 'complete';
            $_results['nodata'] = 'true';
            echo 'backup complete';
        }
        elseif ($install === 'true')
        {
            global $db;
            // Temporary variable, used to store current query
            $templine = '';
// Read in entire file
            $lines = file($_fileName);
// Loop through each line
            foreach ($lines as $line)
            {
// Skip it if it's a comment
                if (substr($line, 0, 2) == '--' || $line == '')
                {
                    echo 'begin import: ' . $line . "<br>";
                    continue;
                }

// Add this line to the current segment
                $templine .= $line;
// If it has a semicolon at the end, it's the end of the query
                if (substr(trim($line), -1, 1) == ';')
                {
// Perform the query
                    $db->update($templine);
// Reset temp variable to empty
                    $templine = '';
                }
            }
            $_results['nodata'] = 'true';
            echo "tables imported successfully";
        }

        return $_results;
    }

    public function getActiveYear()
    {
        $date = getdate(date(strtotime("U")));
        if ($date['mon'] <= 3)
        {
            $_results['data'] = ($date['year'] - 1);
        }
        else
        {
            $_results['data'] = $date['year'];
        }

        return $_results;
    }

    public function jsonLog()
    {
        global $db;
        $start = 4000;
        $limit = 1000;
        $logs = $db->select("SELECT id, data FROM logs ORDER BY id LIMIT " . $start . ", " . $limit . ";");
        foreach ($logs['data'] as $log)
        {
            $data = $log['data'];

            // $data = str_replace('[', '{', $data);
            // $data = str_replace(']', '}', $data);
            // $data = str_replace(',', ':', $data);
            // $data = str_replace('}: {', ', ', $data);
            // $data = str_replace(': ', ':', $data);
            $data = ltrim($data, '[');
            $data = rtrim($data, ']');
            $data = '[' . $data;
            $array = explode('], ', $data);
            // echo $data;
            // print_r($array);
            $tmp = [];
            foreach ($array as $val => $key)
            {
                $_tmp = ltrim($key, '[');
                $_tmp = str_replace('"', '', $_tmp);
                $_arr = explode(',', $_tmp);
                // print_r($_arr);
                // echo $_tmp;
                // array.push($tmp);
                $tmp[$_arr[0]] = $_arr[1];
// array_push($tmp,[$_arr[0]=>$_arr[1]]);
            }
// print_r($tmp);
            // echo json_encode($tmp);
            // // echo ('{'.$data.'}');
            // echo "\n";

            $vars = [
                'data' => json_encode($tmp),
            ];
// print_r($data);
            // $json = json_encode($data[0]);
            // echo $json;
            $db->update('logs', $vars, $log['id']);
        }
        echo 'complete';
    }

    public function logout()
    {
        $db = new sql();
        $_session = "SELECT * FROM sessions WHERE session='" . $_COOKIE["PHPSESSID"] . "';";

        $tot = $db->numRows($_session);
        if ($tot > 0)
        {
            $res = $db->select($_session, 'true');
            $_removeSession = "DELETE FROM sessions WHERE id='" . $res['data']['id'] . "';";

            $_res = $db->update($_removeSession);
            if ($_res)
            {
                $_results = ["logged_in" => "false", "user" => "", "message" => "you have been logged out", "data" => "true"];
            }
            else
            {
                $_results = ["logged_in" => "false", "user" => "", "message" => $db->getError(), "data" => "false"];
            }
        }
        else
        {
            $_results = ["logged_in" => "false", "user" => "", "message" => "record does not exist", "data" => "true"];
        }

        return $_results;
    }

    public function signin()
    {
        if (isset($_POST['username']) && isset($_POST['password']))
        {
            $_usrName = strtolower($_POST['username']);
            $_usrPsw = $_POST['password'];

            if (($_usrName != "") && ($_usrPsw != ""))
            {
                global $db;
                $_user = "SELECT * FROM users WHERE username='" . $_usrName . "';";
                $total_users = $db->numRows($_user);

                if ($total_users == 1)
                {
                    $users = $db->select($_user, 'true');
                    $user = $users['data'];

                    if (!maintenance_mode)
                    {
                        $log_in = true;
                    }
                    else
                    {
                        if ($user['roles'] == 2)
                        {
                            $log_in = true;
                        }
                        else
                        {
                            $log_in = false;
                        }
                    }

                    if ($log_in)
                    {
                        if ($user['canceled'] == 'false')
                        {
                            $user_md5 = LOGIN_KEY . md5($_usrPsw);

                            $_tempUsers = $db->select("SELECT * FROM sessions WHERE user='" . $user['id'] . "';");
                            if (count($_tempUsers['data']) > 1)
                            {
                                $db->deleteData('sessions', "user='" . $user['id'] . "'");
                            }
                            elseif (count($_tempUsers['data']) == 1)
                            {
                                // print_r($_tempUsers);
                                if ($_tempUsers['data'][0]['session'] != $_COOKIE["PHPSESSID"])
                                {
                                    $vars = [
                                        "session" => $_COOKIE["PHPSESSID"],
                                    ];
                                    $db->update('sessions', $vars, $_tempUsers['data'][0]['id']);
                                }
                            }

                            $_session = "SELECT * FROM sessions WHERE session='" . $_COOKIE["PHPSESSID"] . "';";

                            $total_session = $db->numRows($_session);

                            if ($total_session > 1)
                            {
                                $db->deleteData('sessions', "session='" . $_COOKIE["PHPSESSID"] . "'");
                                $sessions = "";
                                $session_id = null;
                            }
                            elseif ($total_session == 1)
                            {
                                $sessions = $db->select($_session, 'true');
                                $session_id = $sessions['data']['id'];
                            }
                            else
                            {
                                $sessions = "";
                                $session_id = null;
                            }

                            if ($user_md5 === $user['password'])
                            {
                                $_valid = 'true';
                            }
                            elseif ($user_md5 !== $user['password'])
                            {
                                $_valid = 'false';
                            }

                            if ($_valid == "true")
                            {
                                $vars = [
                                    "session" => $_COOKIE["PHPSESSID"],
                                    "time" => current_dateTime(),
                                    "logged_in" => $_valid,
                                    "user" => $user['id'],
                                ];
                                if (!empty($session_id))
                                {
                                    $res = $db->update('sessions', $vars, $session_id);
                                }
                                else
                                {
                                    $res = $db->insertData('sessions', $vars);
                                }

                                if ($res)
                                {
                                    $_results['data'] = "true";
                                    $_results['message'] = "logged in";
                                }
                                else
                                {
                                    $_results['data'] = "false";
                                    $_results['message'] = "error occurred";
                                }
                            }
                            elseif ($_valid == "false")
                            {
                                $_results['data'] = "false";
                                $_results['message'] = "incorrect password";
                            }
                        }
                        else
                        {
                            $_results['data'] = "false";
                            $_results['message'] = "no such user";
                        }
                    }
                    else
                    {
                        $_results['data'] = "false";
                        $_results['message'] = "maintenance mode active";
                    }
                }
                else
                {
                    $_results['data'] = "false";
                    $_results['message'] = "no such user";
                }
            }
            elseif (($_usrName == "") || ($_usrPsw == ""))
            {
                $_results['data'] = "false";
                $_results['message'] = "no data passed";
            }
        }
        elseif (!isset($_POST['username']) || !isset($_POST['password']))
        {
            $_results['data'] = "false";
            $_results['message'] = "no data passed";
        }
        $_results['nologin'] = 'true';

        return $_results;
    }

    public function ticketImport()
    {
        $popCon = new mail_import();
        $popLogin = $popCon->pop3_login(emailServer, '110', emailAddress, emailPassword, $folder = "INBOX", $ssl = false);
        $_emails = $popCon->pop3_list($popLogin, $message = "");
        print_r($_emails);
        foreach ($_emails as $_email)
        {
            $_tmpHeaders = $popCon->pop3_retr($popLogin, $_email['mid']);
            $_from = $popCon->mail_parse_headers($_email);
            $_attachments = $popCon->mail_get_parts($popLogin, $_email['mid'], $part, $prefix);
            //$_headers = $popCon->mail_parse_headers($_tmpHeaders);
            //echo ($_tmpHeaders);
            $_body = '';
        }
        //$_headers = $popCon->pop3_retr($popCon, $message);
        $_results['headers'] = '';
        $_results['nologin'] = 'true';
        print_r($_results);
        //return $_results;
    }

    public function updateAdLog($_data = null)
    {
        global $db;

        $db->insertData('ad_logs', $_data);
    }

    public function updateEmailLog($contact = null, $user = null, $subject = null, $body = null, $status = null)
    {
        $db = new sql();

        $vars = [
            "users" => $user,
            "contacts" => $contact,
            "subject" => $subject,
            "body" => $body,
            "date" => current_dateTime(),
            "status" => $status,
        ];
        $db->insertData('email_log', $vars);
    }

    public function updateLog($table = null, $_data = null)
    {
        global $db;
        if (isset($table))
        {
            $db->insertData($table, $_data);
        }
    }

    public function updateLogs($parent = null, $user = null, $action = null, $affected_table = null, $_data = null)
    {
        $db = new sql();
        $data = '[';
        foreach ($_data as $key => $var)
        {
            $data .= '["' . $key . '", "' . $var . '"], ';
        }
        $data = substr($data, 0, -2);
        $data .= ']';
        $vars = [
            "date" => current_dateTime(),
            "clients" => $parent,
            "users" => $user,
            "action" => $action,
            "affected_table" => $affected_table,
            "data" => $data,
        ];

        $db->insertData('logs', $vars);
    }

    public function verify($return = null)
    {
        if (isset($_COOKIE["PHPSESSID"]))
        {
            $valid = 'false';
            $check = false;
            $_user = '';
            global $db;

            $_totalSessions = "SELECT * FROM sessions WHERE session='" . $_COOKIE["PHPSESSID"] . "';";
##get all avtive sessions from database
            $total_sessions = $db->numRows($_totalSessions);

            if ($total_sessions == 1)
            {
                $session = $db->select($_totalSessions, 'true');

##get user details of logged in user
                $_currentUser = $db->select("SELECT * FROM users WHERE id='" . $session['data']['user'] . "';", 'true');

                $_user = $_currentUser['data'];
                $_SESSION['user'] = $_user['id'];

#get the current roles assigned to the user
                $_curRoles = $db->select("SELECT * FROM user_roles WHERE id='" . $_user['roles'] . "';", 'true');

                $_roles = $_curRoles['data'];

                unset($_roles['role']);

##get the current users access list
                $_curAccess = $db->select("SELECT * FROM user_access WHERE id='" . $_user['access_list'] . "';", 'true');

                $_access = $_curAccess['data'];
                unset($_access['id']);

##check if system is in maintenance mode or not
                if (!maintenance_mode)
                {
                    $check = true;
                }
                else
                {
                    if ($_user['roles'] == 2)
                    {
                        $check = true;
                    }
                    else
                    {
                        $check = false;
                    }
                }

##make sure user is logged in and system is not in maintenance mode
                if ($check)
                {
                    if ($session['data']['logged_in'] == "true")
                    {
                        $_d1 = $session['data']['time'];
                        $_d2 = current_dateTime();

//Convert them to timestamps.
                        $_d1T = strtotime($_d1);
                        $_d2T = strtotime($_d2);

//Calculate the difference.
                        $diff = $_d2T - $_d1T;

##calculate the difference in seconds between last activity and current time
                        if ($diff <= login_time)
                        {
                            $valid = 'true';
                        }
                        elseif ($diff > login_time)
                        {
                            $valid = 'false';
                        }
                    }
                    else
                    {
                        $valid = 'false';
                    }
                }
                else
                {
                    $valid = 'false';
                }

                $_updateSession = "UPDATE sessions SET time='" . current_dateTime() . "', logged_in='" . $valid . "' WHERE id='" . $session['data']['id'] . "';";

                $db->update($_updateSession);
            }
            elseif ($total_sessions > 1)
            {
                $db->deleteData('sessions', "session='" . $_COOKIE["PHPSESSID"] . "'");
                $valid = 'false';
            }
            else
            {
                $valid = 'false';
            }

            if ($valid == 'true')
            {
                $_results = ["logged_in" => "true", "user_roles" => $_roles, "user_access" => $_access, "user" => $_user['name'] . " " . $_user['surname']];
            }
            elseif ($valid == 'false')
            {
                $_results = ["logged_in" => "false", "user_roles" => "", "user_access" => "", "user" => ""];
            }
        }
        else
        {
            $_results = ["logged_in" => "false", "user_roles" => "", "user_access" => "", "message" => "no session", "user" => ""];
        }

        return $_results;
    }
}
