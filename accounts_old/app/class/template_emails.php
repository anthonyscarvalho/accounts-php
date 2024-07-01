<?php

class template_emails
{
    private $table = 'template_emails';

    public function create()
    {
        global $db;

        $vars = [
            "name" => $_POST['name'],
            "subject" => $_POST['subject'],
            "body" => $_POST['body'],
        ];

        $res = $db->insertData('template_emails', $vars);

        if ($res)
        {
            $_reslults['data'] = 'true';
            $_reslults['message'] = 'record added';
            performAction('manager', 'updateLog', [null, $_SESSION['user'], 'inserted', 'template_emails', $vars]);
        }
        elseif (!$res)
        {
            $_reslults['data'] = 'false';
            $_reslults['message'] = $db->getError();
        }

        return $_reslults;
    }

    public function edit($id = null)
    {
        global $db;

        return $db->select("SELECT * FROM template_emails WHERE id='" . $id . "';", 'true');
    }

    public function retrieve($type = null)
    {
        $_reslults['nologin'] = 'true';
        global $db;

        if ($type == 'email')
        {
            $id = 1;
        }
        elseif ($type == 'reminder')
        {
            $id = 2;
        }
        elseif ($type == 'suspend')
        {
            $id = 5;
        }
        elseif ($type == 'termination')
        {
            $id = 6;
        }
        elseif ($type == 'paid')
        {
            $id = 4;
        }
        elseif ($type == 'statement')
        {
            $id = 8;
        }

        if ($id != '')
        {
            $_reslults = $db->select("SELECT * FROM template_emails WHERE id='" . $id . "';", 'true');
        }
        else
        {
            $_reslults = '';
        }

        return $_reslults;
    }

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            $vars = [
                "name" => $_POST['name'],
                "subject" => $_POST['subject'],
                "body" => $_POST['body'],
            ];
            if ($_POST['view_type'] == 'create')
            {
                $res = $db->insertData($this->table, $vars);

                if ($res)
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'added';
                    $_results['data'] = $db->lastInsertId();

                    $_vars = [
                        "clients" => null,
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => 'created',
                        "data" => json_encode($vars),
                    ];
                    performAction('manager', 'updateLog', ['logs', $_vars]);
                }
                elseif (!$res)
                {
                    $_results['data']['data'] = 'false';
                    $_results['message'] = $db->getError();
                }
            }
            elseif ($_POST['view_type'] == "save")
            {
                $res = $db->update($this->table, $vars, $_POST['id']);

                if ($res)
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'record updated';

                    $_vars = [
                        "clients" => null,
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => 'updated',
                        "data" => json_encode($vars),
                    ];
                    performAction('manager', 'updateLog', ['logs', $_vars]);
                }
                elseif (!$res)
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }
            }

            return $_reslults;
        }
    }

    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            if ($_POST['view_type'] == 'view')
            {
                $_results = $db->select("SELECT * FROM template_emails;");
            }
            elseif ($_POST['view_type'] == 'edit')
            {
                $_results = $db->select("SELECT * FROM template_emails WHERE id='" . $_POST['id'] . "';", 'true');
            }

            return $_results;
        }
    }
}
