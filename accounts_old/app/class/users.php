<?php

class users
{
    private $table = 'users';

    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            if ($_POST['view_type'] == 'view')
            {
                $_sql = "SELECT * FROM users ORDER BY name;";
                $_results = $db->select($_sql);
            }
            else if ($_POST['view_type'] == 'edit')
            {
                $_results = $db->select("SELECT * FROM users WHERE id='" . $_POST['id'] . "';", 'true');
            }
            return $_results;
        }
    }

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            $vars = [
                "username" => strtolower($_POST['username']),
                "name" => $_POST['name'],
                "surname" => $_POST['surname'],
                "email_address" => (!empty($_POST['email_address']) ? strtolower($_POST['email_address']) : null),
                "roles" => $_POST['roles'],
            ];

            if (isset($_POST['new_password']))
            {
                if ((LOGIN_KEY . md5($_POST['new_password'])) != $_POST['password'])
                {
                    $vars['password'] = LOGIN_KEY . md5($_POST['new_password']);
                }
            }

            if ($_POST['view_type'] == "save")
            {
                $res = $db->update($this->table, $vars, $_POST['id']);
                if ($res)
                {
                    $_data = 'true';
                    $_message = 'record updated';
                }
                else if (!$res)
                {
                    $_data = 'false';
                    $_message = $db->getError();
                }
            }
            else if ($_POST['view_type'] == "create")
            {
                $vars['password'] = LOGIN_KEY . md5($_POST['password']);

                $vars2 = [
                    "pages"=>'false'
                ];
                $res = $db->insertData('user_access',$vars2);

                if ($res)
                {
                    $id = $db->lastInsertId('user_access');

                    if ($id != '')
                    {
                        $vars['access_list'] = $id;
                        $_res = $db->insertData($this->table, $vars);
                        if ($_res)
                        {
                            $_data = 'true';
                            $_message = 'record inserted';
                        }
                        else if (!$_res)
                        {
                            $_data = 'false';
                            $_message = $db->getError();
                        }
                    }
                    else
                    {
                        $_data = 'false';
                        $_message = 'no id';
                    }
                }
                else if (!$res)
                {
                    $_data = 'false';
                    $_message = $db->getError();
                }
            }
        }
        $_results['data'] = $_data;
        $_results['message'] = $_message;
        return $_results;
    }

    public function update()
    {
        if (isset($_POST['state']))
        {
            global $db;

            if ($_POST['state'] == 'cancel' || $_POST['state'] == 'enable')
            {
                if ($_POST['state'] == 'cancel')
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='true' WHERE id='" . $_POST['id'] . "';";
                }
                else if ($_POST['state'] == 'enable')
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='false' WHERE id='" . $_POST['id'] . "';";
                }
                $res = $db->update($_sql);
                if ($res)
                {
                    $_results['data'] = "true";
                    $_results['message'] = "state updated";
                }
                else
                {
                    $_results['data'] = "false";
                    $_results['message'] = $db->getError();
                }
            }
            else if ($_POST['state'] == 'delete')
            {
                $_user = $db->select("SELECT * FROM users WHERE id='" . $_POST['id'] . "'", 'true');
                if ($_user['data'] != "")
                {
                    $db->deleteData('user_access', "id='" . $_user['data']['access_list'] . "' AND link_type='page'");
                    $res = $db->deleteData($this->table, "id='" . $_POST['id'] . "'");
                    if ($res)
                    {
                        $_results['data'] = "true";
                        $_results['message'] = "item removed";
                    }
                    else
                    {
                        $_results['data'] = "false";
                        $_results['message'] = $db->getError();
                    }
                }
            }
            return $_results;
        }
    }
}
