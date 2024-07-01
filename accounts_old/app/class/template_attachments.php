<?php

class template_attachments
{
    private $table = 'template_attachments';

    public function create()
    {
        global $db;

        $vars = [
            "name" => $_POST['name'],
            "template" => $_POST['template'],
        ];

        $res = $db->insertData('template_attachments', $vars);
        if ($res)
        {
            $_results['data'] = 'true';
            $_results['message'] = 'record added';
            performAction('manager', 'updateLog', [null, $_SESSION['user'], 'inserted', 'template_attachments', $vars]);
        }
        elseif (!$res)
        {
            $_results['data'] = 'false';
            $_results['message'] = $db->getError();
        }

        return $_results;
    }

    public function edit($id = null)
    {
        $_results['nologin'] = true;
        global $db;
        $_results = $db->select("SELECT * FROM template_attachments WHERE id='" . $id . "';", 'true');

        return $_results;
    }

    public function retrieve($type = null)
    {
        $_results['nologin'] = true;
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
        if ($id != '')
        {
            $_results = $db->select("SELECT * FROM template_attachments WHERE id='" . $id . "';", 'true');
        }
        else
        {
            $_results['data'] = '';
        }

        return $_results;
    }

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            $vars = [
                "name" => $_POST['name'],
                "template" => $_POST['template'],
            ];
            if ($_POST['view_type'] == 'create')
            {
                $res = $db->insertData($this->table, $vars);

                if ($res)
                {
                    $_results['data']['data'] = 'true';
                    $_results['message'] = 'added';
                    $_results['data']['id'] = $db->lastInsertId();

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
                    $_vars = [
                        "clients" => null,
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => 'updated',
                        "data" => json_encode($vars),
                    ];
                    performAction('manager', 'updateLog', ['logs', $_vars]);
                    $_results['data'] = 'true';
                    $_results['message'] = 'record updated';
                }
                elseif (!$res)
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }
            }

            return $_results;
        }
    }

    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            if ($_POST['view_type'] == 'view')
            {
                $_results = $db->select("SELECT * FROM template_attachments;");
            }
            elseif ($_POST['view_type'] == 'edit')
            {
                $_results = $db->select("SELECT * FROM template_attachments WHERE id='" . $_POST['id'] . "';", 'true');
            }

            return $_results;
        }
    }
}
