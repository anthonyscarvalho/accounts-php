<?php

class clients
{
    private $table = "clients";

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            $db = new sql();

            $vars = [
                "business" => $_POST['business'],
                "vat" => (!empty($_POST['vat']) ? $_POST['vat'] : null),
                "number" => (!empty($_POST['number']) ? $_POST['number'] : null),
                "fax" => (!empty($_POST['fax']) ? $_POST['fax'] : null),
                "billing_address" => (!empty($_POST['billing_address']) ? $_POST['billing_address'] : null),
                "city" => $_POST['city'],
                "postal_code" => (!empty($_POST['postal_code']) ? $_POST['postal_code'] : null),
                "notes" => (!empty($_POST['notes']) ? $_POST['notes'] : null),
                "bad_client" => $_POST['bad_client'],
            ];

            if ($_POST['view_type'] == 'create')
            {
                $vars['signup_date'] = current_dateTime();
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
                    $_vars = [
                        "clients" => $_POST['id'],
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

    public function update($state = null, $id = null)
    {
        if ($state != null)
        {
            if ($state == 'cancel' || $state == 'enable')
            {
                global $db;

                if ($state == 'cancel')
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='true', canceled_date='" . current_date() . "' WHERE id='" . $id . "';";
                }
                elseif ($state == 'enable')
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='false', canceled_date=null WHERE id='" . $id . "';";
                }

                $res = $db->update($_sql);

                if ($res)
                {
                    $_results['data'] = "true";
                    $_results['message'] = "state updated";
                    $_vars = [
                        "clients" => $id,
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => $state,
                        "data" => json_encode(["id" => $id]),
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

            if ($_POST['view_type'] == 'view')
            {
                $_sql = "SELECT id, business, city, signup_date, canceled, canceled_date FROM clients";

                if ($_POST['state'] == 'true' || $_POST['state'] == 'false')
                {
                    $_sql .= " WHERE canceled='" . $_POST['state'] . "'";
                }

                $_sql .= " ORDER BY business;";

                $_results = $db->select($_sql);
            }
            elseif ($_POST['view_type'] == "retrieve")
            {
                $_results = $db->select("SELECT id, business FROM clients WHERE canceled='false' ORDER BY business;");
            }
            elseif ($_POST['view_type'] == 'edit')
            {
                $_results = $db->select("SELECT * FROM clients WHERE id='" . $_POST['id'] . "';", 'true');
            }

            return $_results;
        }
    }
}
