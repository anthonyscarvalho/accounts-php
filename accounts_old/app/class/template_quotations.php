<?php

class template_quotations
{
    private $table = "template_quotations";

    public function retrieve()
    {
        $_results['nologin'] = true;
        global $db;

        $_results = $db->select("SELECT id, name FROM template_quotations ORDER BY name;");

        return $_results;
    }

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            $vars = [
                "name" => $_POST['name'],
                "companies" => $_POST['companies'],
                "content" => $_POST['content'],
                "inclusions" => $_POST['inclusions'],
                "exclusions" => $_POST['exclusions'],
                "extras" => $_POST['extras'],
                "warranty" => $_POST['warranty'],
                "signature" => $_POST['signature'],
                "annexure_a" => $_POST['annexure_a'],
            ];

            if ($_POST['view_type'] == 'create')
            {
                $vars['canceled'] = 'false';
                $vars['date_canceled'] = null;

                $res = $db->insertData($this->table, $vars);

                if ($res)
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'added';
                    $_results['id'] = $db->lastInsertId();

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
                    $_results['data'] = 'false';
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
            if ($_POST['view_type'] == "view")
            {
                $_results = $db->select("SELECT id, name, companies, date_created FROM template_quotations;");
            }
            elseif ($_POST['view_type'] == 'edit')
            {
                $_results = $db->select("SELECT * FROM template_quotations WHERE id='" . $_POST['id'] . "';", 'true');
            }

            return $_results;
        }
    }
}
