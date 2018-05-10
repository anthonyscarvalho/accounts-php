<?php
class clients
{
    private $table = "clients";

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
        if (isset($_POST['view_type']))
        {
            $db = new sql();
            $vars = [
                "business" => $_POST['business'],
                "vat" => (!empty($_POST['vat']) ? $_POST['vat'] : null),
                "number" => (!empty($_POST['number']) ? $_POST['number'] : null),
                "fax" => (!empty($_POST['fax']) ? $_POST['fax'] : null),
                "registration" => (!empty($_POST['registration']) ? $_POST['registration'] : null),
                "billing_address" => (!empty($_POST['billing_address']) ? $_POST['billing_address'] : null),
                "city" => $_POST['city'],
                "postal_code" => (!empty($_POST['postal_code']) ? $_POST['postal_code'] : null),
                "notes" => (!empty($_POST['notes']) ? $_POST['notes'] : null),
                "bad_client" => $_POST['bad_client']
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
                    $_vars = [
                        "clients" => $_POST['id'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => 'updated',
                        "data" => json_encode($vars)
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

    public function update()
    {
        if ($_POST['state'] != null)
        {
            if ($_POST['state'] == 'cancel' || $_POST['state'] == 'enable')
            {
                global $db;
                $_client = $db->select("SELECT id FROM " . $this->table . " WHERE id='" . $_POST['id'] . "';", 'true');
                $_complete = false;

                if ($_POST['state'] == 'cancel')
                {
                    if (count($_client['data']) > 0)
                    {
                        $_res = $db->update("UPDATE contacts SET canceled='true', canceled_date='" . current_dateTime() . "' WHERE clients='" . $_client['data']['id'] . "' AND canceled='false';");

                        if ($_res)
                        {
                            $_res = $db->update("UPDATE products SET canceled='true', canceled_date='" . current_dateTime() . "' WHERE clients='" . $_client['data']['id'] . "' AND canceled='false';");

                            if ($_res)
                            {
                                $_res = $db->update("UPDATE invoices SET canceled='true', canceled_date='" . current_dateTime() . "' WHERE clients='" . $_client['data']['id'] . "' AND canceled='false' AND paid='false';");

                                if ($_res)
                                {
                                    $_res = $db->update("UPDATE clients SET canceled='true', canceled_date='" . current_dateTime() . "' WHERE id='" . $_client['data']['id'] . "' ;");

                                    if ($_res)
                                    {
                                        $_results['data'] = 'true';
                                        $_results['message'] = 'client canceled<br />client contacts canceled<br />client products canceled<br />client invoices canceled';
                                        $_complete = true;
                                    }
                                    else
                                    {
                                        $_results['data'] = 'false';
                                        $_results['message'] = 'invoices - ' . $db->getError();
                                    }
                                }
                                else
                                {
                                    $_results['data'] = 'false';
                                    $_results['message'] = 'invoices - ' . $db->getError();
                                }
                            }
                            else
                            {
                                $_results['data'] = 'false';
                                $_results['message'] = 'products - ' . $db->getError();
                            }
                        }
                        else
                        {
                            $_results['data'] = 'false';
                            $_results['message'] = 'contacts - ' . $db->getError();
                        }
                    }
                }
                elseif ($_POST['state'] == 'enable')
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='false', canceled_date=null WHERE id='" . $_POST['id'] . "';";
                    $res = $db->update($_sql);

                    if ($res)
                    {
                        $_results['data'] = 'true';
                        $_results['message'] = 'client enabled<br />please verify contacts';
                        $_complete = true;
                    }
                    else
                    {
                        $_results['data'] = 'false';
                        $_results['message'] = $db->getError();
                    }
                }

                if ($_complete)
                {
                    $_vars = [
                        "clients" => $_client['data']['id'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => $_POST['state'],
                        "data" => json_encode(["id" => $_POST['id']])
                    ];

                    performAction('manager', 'updateLog', ['logs', $_vars]);
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
                $_whereArray = [];
                $_searchArray = [];

                if ($_POST['state'] == 'true' || $_POST['state'] == 'false')
                {
                    array_push($_whereArray, ['column' => 'canceled', 'value' => $_POST['state'], 'operator' => '=', 'selector' => 'AND']);
                }

                if (isset($_POST['client']))
                {
                    array_push($_whereArray, ['column' => 'clients', 'value' => $_POST['client'], 'operator' => '=', 'selector' => 'AND']);
                }

                if (isset($_POST['sortCompany']) && ($_POST['sortCompany'] != '') && ($_POST['sortCompany'] != '0'))
                {
                    array_push($_whereArray, ['column' => 'companies', 'value' => $_POST['sortCompany'], 'operator' => '=', 'selector' => 'AND']);
                }

                if (isset($_POST['sortSearch']) && ($_POST['sortSearch'] != ''))
                {
                    array_push($_searchArray, ['column' => 'id', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR']);
                    array_push($_searchArray, ['column' => 'business', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR']);
                    array_push($_searchArray, ['column' => 'city', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR']);
                    array_push($_searchArray, ['column' => 'signup_date', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR']);
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

                $_sql = "SELECT id, business, city, signup_date, canceled, canceled_date FROM clients";

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
