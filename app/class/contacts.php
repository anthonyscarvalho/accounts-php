<?php

class contacts
{
    private $table = "contacts";

    public function edit($id = null)
    {
        global $db;

        if (!empty($id))
        {
            $_sql = "SELECT * FROM " . $this->table . " WHERE id='" . $id . "';";
            $_total = $db->numRows("SELECT * FROM " . $this->table . " WHERE id='" . $id . "';");

            if ($_total > 0)
            {
                $_results = $db->select($_sql, 'true');
            }
            else
            {
                $_results['data'] = 'false';
                $_results['message'] = 'record does not exist';
            }
        }
        else
        {
            $_results['data'] = 'false';
            $_results['message'] = 'no data passed';
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
                "quotes" => $_POST['quotes']
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
                        "clients" => $_POST['clients'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => 'created',
                        "data" => json_encode($vars)
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
                        "clients" => $_POST['clients'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => 'updated',
                        "data" => json_encode($vars)
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

    public function update()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            $_tmp = $db->select("SELECT * FROM " . $this->table . " WHERE id='" . $_POST['id'] . "';", 'true');

            if ($_POST['view_type'] == 'cancel' || $_POST['view_type'] == 'enable')
            {
                if ($_POST['view_type'] == 'cancel')
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='true', canceled_date='" . current_date() . "' WHERE id='" . $_POST['id'] . "';";
                }
                elseif ($_POST['view_type'] == 'enable')
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='false', canceled_date=null WHERE id='" . $_POST['id'] . "';";
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
            elseif ($_POST['view_type'] == 'delete')
            {
                $res = $db->deleteData($this->table, "id='" . $_POST['id'] . "'");

                if ($res == "true")
                {
                    $_results['data'] = "true";
                    $_results['message'] = "record deleted";
                }
                else
                {
                    $_results['data'] = "false";
                    $_results['message'] = $db->getError();
                }
            }

            if ($_results['data'] == 'true')
            {
                $_vars = [
                    "clients" => $_tmp['data']['clients'],
                    "date" => current_dateTime(),
                    "users" => $_SESSION['user'],
                    "affected_table" => $this->table,
                    "action" => $_POST['view_type'],
                    "data" => json_encode($_tmp['data'])
                ];

                performAction('manager', 'updateLog', ['logs', $_vars]);
            }
        }
        else
        {
            $_results['data'] = 'false';
            $_results['message'] = 'no data passed';
        }

        return $_results;
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
                $_whereArray = [];
                $_searchArray = [];

                if ($_POST['state'] == 'true' || $_POST['state'] == 'false')
                {
                    array_push($_whereArray, ['column' => 'contacts.canceled', 'value' => $_POST['state'], 'operator' => '=', 'selector' => 'AND']);
                }

                if (isset($_POST['client']))
                {
                    array_push($_whereArray, ['column' => 'contacts.clients', 'value' => $_POST['client'], 'operator' => '=', 'selector' => 'AND']);
                }

                if (isset($_POST['sortSearch']) && ($_POST['sortSearch'] != ''))
                {
                    array_push($_searchArray, ['column' => 'contacts.name', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR']);
                    array_push($_searchArray, ['column' => 'contacts.surname', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR']);
                    array_push($_searchArray, ['column' => 'contacts.email', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR']);
                    array_push($_searchArray, ['column' => 'clients.business', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR']);
                }

                if (count($_whereArray) > 0)
                {
                    $_where = " WHERE ";

                    foreach ($_whereArray as $_tmp)
                    {
                        $_where .= " " . $_tmp['column'] . $_tmp['operator'] . "'" . $_tmp['value'] . "' " . $_tmp['selector'];
                    }

                    if (count($_searchArray) == 0)
                    {
                        $_where = rtrim($_where, "AND");
                        $_where = rtrim($_where, "OR");
                    }
                }

                if (count($_searchArray) > 0)
                {
                    if (count($_whereArray) == 0)
                    {
                        $_where = " WHERE ";
                    }

                    $_where .= " ( ";

                    foreach ($_searchArray as $_tmp)
                    {
                        $_where .= " " . $_tmp['column'] . $_tmp['operator'] . "'" . $_tmp['value'] . "' " . $_tmp['selector'];
                    }

                    $_where = rtrim($_where, "AND");
                    $_where = rtrim($_where, "OR");

                    $_where .= " ) ";
                }

                $_sql = "SELECT contacts.*, clients.business FROM clients RIGHT JOIN contacts ON clients.id=contacts.clients";

                if (isset($_where))
                {
                    $_sql = $_sql . $_where;
                }

                $_total = $db->numRows($_sql);

                if (isset($_POST['sort']))
                {
                    $_sql .= " ORDER BY " . $_POST['sort'] . ((isset($_POST['sortOrder'])) ? " " . $_POST['sortOrder'] : '');
                }

                if (isset($_POST['page']) && isset($_POST['records']))
                {
                    $_limitOffset = (($_POST['page'] - 1) * $_POST['records']);

                    $_sql .= " LIMIT  " . $_limitOffset . ", " . $_POST['records'];
                }

                $_sql .= ';';

                $_results = $db->select($_sql);
                $_results['records'] = $_total;

                if (isset($_POST['client']))
                {
                    $res = $db->select("SELECT * FROM clients WHERE id = '" . $_POST['client'] . "';", 'true');

                    $_results['business'] = $res['data']['business'];
                }
            }

            return $_results;
        }
    }
}
