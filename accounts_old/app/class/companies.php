<?php

class companies
{
    private $table = "companies";

    public function save()
    {
        global $db;
        $vars = [
            "company" => $_POST['company'],
            "invoice_header" => (!empty($_POST['invoice_header']) ? $_POST['invoice_header'] : null),
            "account_details" => (!empty($_POST['account_details']) ? $_POST['account_details'] : null),
        ];

        if ($_POST['view_type'] == 'create')
        {
            $vars['canceled'] = 'false';

            $res = $db->insertData($this->table, $vars);
            if ($res)
            {
                $_results['data'] = 'true';
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
                $_results['data'] = 'false';
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

        return $_results;
    }

    public function update($state = null, $id = null)
    {
        if ($state != null)
        {
            if ($state == 'cancel' || $state == 'enable')
            {
                global $db;

                $_tmp = $db->select("SELECT * FROM companies WHERE id='" . $id . "';", 'true');

                if ($state == 'cancel')
                {
                    $_sql = "UPDATE companies SET canceled='true' WHERE id='" . $id . "';";
                }
                elseif ($state == 'enable')
                {
                    $_sql = "UPDATE companies SET canceled='false' WHERE id='" . $id . "';";
                }

                $res = $db->update($_sql);

                if ($res)
                {
                    $_results['data'] = "true";
                    $_results['message'] = "state updated";

                    $_vars = [
                        "clients" => $_tmp['data']['id'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => $state,
                        "data" => json_encode($_tmp['data']),
                    ];
                    performAction('manager', 'updateLog', ['logs', $_vars]);
                }
                else
                {
                    $_results['data'] = "false";
                    $_results['message'] = $db->getError();
                }

                return $_results;
            }
        }
    }

    public function view($id = null)
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            if ($_POST['view_type'] == "view")
            {
                $_results = $db->select("SELECT * FROM companies;");
            }
            elseif ($_POST['view_type'] == "edit")
            {
                $_results = $db->select("SELECT * FROM companies WHERE id='" . $_POST['id'] . "';", 'true');
            }
            elseif ($_POST['view_type'] == 'search')
            {
                $_results = $db->select("SELECT id, company FROM companies WHERE canceled='false' ORDER BY company;");
            }

            return $_results;
        }
    }
}
