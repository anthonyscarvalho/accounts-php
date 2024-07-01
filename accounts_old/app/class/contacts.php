<?php

class contacts
{
    private $table = "contacts";

    public function delete($id = null)
    {
        global $db;
        $res = $db->deleteData('contacts', 'id=' . $id);

        if ($res == "true")
        {
            $_results['data'] = "true";
            $_results['message'] = "deleted";
        }
        else
        {
            $_results['data'] = "false";
            $_results['message'] = $db->getError();
        }

        return $_results;
    }

    public function save()
    {
        if (isset($_POST['name']) && isset($_POST['email']))
        {
            global $db;
            $vars = [
                "name" => $_POST['name'],
                "surname" => ((isset($_POST['surname'])) ? $_POST['surname'] : null),
                "contact_number_1" => ((isset($_POST['contact_number_1'])) ? $_POST['contact_number_1'] : null),
                "contact_number_2" => ((isset($_POST['contact_number_2'])) ? $_POST['contact_number_2'] : null),
                "email" => strtolower($_POST['email']),
                "payment" => $_POST['payment'],
                "invoice" => $_POST['invoice'],
                "receipt" => $_POST['receipt'],
                "suspension" => $_POST['suspension'],
                "adwords" => $_POST['adwords'],
            ];

            if ($_POST['view_type'] == 'create')
            {
                $vars['clients'] = $_POST['clients'];
                $vars['creation_date'] = current_dateTime();
                $vars['canceled'] = 'false';
                $vars['canceled_date'] = null;

                $res = $db->insertData($this->table, $vars);

                if ($res)
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'added';
                    $_results['id'] = $db->lastInsertId();

                    $_vars = [
                        "clients" => $db->lastInsertId(),
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
                        "clients" => $_POST['id'],
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
        }
        else
        {
            $_results['data'] = 'false';
            $_results['message'] = "nothing passed";
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

                $_tmp = $db->select("SELECT * FROM contacts WHERE id='" . $id . "';", 'true');

                if ($state == 'cancel')
                {
                    $_sql = "UPDATE contacts SET canceled='true', canceled_date='" . current_date() . "' WHERE id='" . $id . "';";
                }
                elseif ($state == 'enable')
                {
                    $_sql = "UPDATE contacts SET canceled='false', canceled_date=null WHERE id='" . $id . "';";
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

    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            if ($_POST['view_type'] == "edit")
            {
                $_results = $db->select("SELECT * FROM contacts WHERE id='" . $_POST['id'] . "';", 'true');
            }
            elseif ($_POST['view_type'] == "view")
            {
                $_sql = "SELECT contacts.*, clients.business FROM clients RIGHT JOIN contacts ON clients.id=contacts.clients ";

                if ($_POST['state'] == 'true' || $_POST['state'] == 'false')
                {
                    $_sql .= "WHERE contacts.canceled = '" . $_POST['state'] . "';";
                }

                $_results = $db->select($_sql);
            }
            elseif ($_POST['view_type'] == "search")
            {
                $_results = $db->select("SELECT * FROM contacts WHERE clients = '" . $_POST['id'] . "' ORDER BY name ASC;");

                $res = $db->select("SELECT * FROM clients WHERE id = '" . $_POST['id'] . "';", 'true');
                $_results['business'] = $res['data']['business'];
            }

            return $_results;
        }
    }
}
