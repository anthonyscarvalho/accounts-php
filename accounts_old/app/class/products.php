<?php

class products
{
    private $table = "products";

    public function delete($id = null)
    {
        global $db;
        $_tmp = $db->select("SELECT * FROM " . $this->table . " WHERE id=" . $id . ";", 'true');

        if (count($_tmp['data']) > 0)
        {
            $res = $db->update("DELETE FROM " . $this->table . " WHERE id='" . $id . "';");

            if ($res)
            {
                $_results['data'] = "true";
                $_results['message'] = "product deleted";

                $_vars = [
                    "clients" => $_tmp['data']['clients'],
                    "date" => current_dateTime(),
                    "users" => $_SESSION['user'],
                    "affected_table" => $this->table,
                    "action" => "removed",
                    "data" => json_encode($_tmp['data']),
                ];
                performAction('manager', 'updateLog', ['logs', $_vars]);
            }
            else
            {
                $_results['data'] = "false";
                $_results['message'] = $db->getError();
            }
        }
        else
        {
            $_results['data'] = "false";
            $_results['message'] = 'no products';
        }

        return $_results;
    }

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            $_date = getdate(date(strtotime($_POST['date'])));

            global $db;
            $vars = [
                "companies" => $_POST['companies'],
                "date" => $_POST['date'],
                "year" => $_date['year'],
                "month" => $_date['mon'],
                "description" => (isset($_POST['description']) ? $_POST['description'] : null),
                "price" => $_POST['price'],
                "renewable" => $_POST['renewable'],
            ];

            if ($_POST['view_type'] == 'create')
            {
                $vars['clients'] = $_POST['clients'];
                $vars['categories'] = $_POST['categories'];
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
                        "action" => "created",
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
                        "clients" => $_POST['clients'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => "updated",
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
                        "clients" => $_POST['clients'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => $state,
                        "data" => json_encode(["id" => $id]),
                    ];
                    performAction('manager', 'updateLog', ['logs', $_vars]);
                    // // performAction('manager', 'updateLogs', [$id, $_SESSION['user'], $state, $this->table, ["id" => $id]]);
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

            if ($_POST['view_type'] == "view")
            {
                if ($_POST['state'] == 'true' || $_POST['state'] == 'false')
                {
                    $_sql = "SELECT products.*, clients.business AS clientName, categories.category AS categoryName, (SELECT date FROM invoices_items WHERE invoices_items.products=products.id ORDER BY date DESC LIMIT 1) AS lastInvoice "
                    . "FROM categories RIGHT JOIN (clients RIGHT JOIN products ON clients.id=products.clients) ON categories.id=products.categories "
                    . " WHERE products.canceled='" . $_POST['state'] . "';";
                    $_results = $db->select($_sql);
                }
                elseif ($_POST['state'] == '')
                {
                    $_sql = "SELECT products.*, clients.business AS clientName, categories.category AS categoryName, (SELECT date FROM invoices_items WHERE invoices_items.products=products.id ORDER BY date DESC LIMIT 1) AS lastInvoice "
                    . "FROM categories RIGHT JOIN (clients RIGHT JOIN products ON clients.id=products.clients) ON categories.id=products.categories ";
                    $_results = $db->select($_sql);
                }
                elseif ($_POST['state'] == 'due')
                {
                    $_sql = "SELECT products.*, clients.business AS clientName, categories.category AS categoryName, (SELECT date FROM invoices_items WHERE invoices_items.products=products.id ORDER BY date DESC LIMIT 1) AS lastInvoice "
                    . "FROM categories RIGHT JOIN (clients RIGHT JOIN products ON clients.id=products.clients) ON categories.id=products.categories "
                    . "WHERE products.canceled='false' AND products.renewable='a'";
                    $products = [];
                    $d1 = getdate(date(strtotime($_POST['date'])));
                    $d2 = getdate(date(strtotime(current_date())));

                    if (!isset($_POST['invoice_type']))
                    {
                        $_sql .= " AND products.month='" . $d1['mon'] . "'";
                    }
                    elseif (isset($_POST['invoice_type']))
                    {
                        switch ($_post['invoice_type'])
                        {
                            case "due":
                                $_sql .= " AND products.month='" . $d1['mon'] . "'";
                                break;
                            case "now":
                                $_sql .= " AND products.month='" . $d2['mon'] . "'";
                                break;
                            default:
                                break;
                        }
                    }
                    if (isset($_POST['clients']))
                    {
                        $_sql .= " AND clients='" . $_POST['clients'] . "'";
                    }
                    if (isset($_POST['companies']))
                    {
                        $_sql .= " AND companies='" . $_POST['companies'] . "'";
                    }
                    // echo $_sql;
                    $_totProd = $db->numRows($_sql);

                    if ($_totProd > 0)
                    {
                        $res = $db->select($_sql);

                        foreach ($res['data'] as $prod)
                        {
                            $add = false;
                            $month_diff = diff_months($_POST['date'], $prod['date']);
                            if (empty($prod['lastInvoice']))
                            {
                                $dif_year = diff_years($_POST['date'], $prod['date']);
                                if (!isset($_POST['invoice_type']))
                                {
                                    if (($dif_year >= 1) && ($month_diff == 0))
                                    {
                                        $add = true;
                                    }
                                }
                                elseif (isset($_POST['invoice_type']))
                                {
                                    if ($_POST['invoice_type'] == "now")
                                    {
                                        if (($dif_year >= 0) && ($month_diff == 0))
                                        {
                                            $add = true;
                                        }
                                    }
                                    elseif ($_POST['invoice_type'] == "due")
                                    {
                                        if (($dif_year >= 1) && ($month_diff == 0))
                                        {
                                            $add = true;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $dif_year = diff_years($_POST['date'], $prod['lastInvoice']);

                                if (($dif_year == 1) && ($month_diff == 0))
                                {
                                    $add = true;
                                }
                            }

                            if ($add)
                            {
                                $_temp = $prod;
                                array_push($products, $_temp);
                            }
                        }
                    }

                    $_sql = "SELECT products.*, clients.business AS clientName, categories.category AS categoryName, (SELECT date FROM invoices_items WHERE invoices_items.products=products.id ORDER BY date DESC LIMIT 1) AS lastInvoice "
                    . "FROM categories RIGHT JOIN (clients RIGHT JOIN products ON clients.id=products.clients) ON categories.id=products.categories "
                    . "WHERE products.canceled='false' AND (products.renewable='m' OR products.renewable='r' OR products.renewable='o')";
                    if (isset($_POST['clients']))
                    {
                        $_sql .= " AND clients='" . $_POST['clients'] . "'";
                    }
                    if (isset($_POST['companies']))
                    {
                        $_sql .= " AND companies='" . $_POST['companies'] . "'";
                    }
                    $_total = $db->numRows($_sql);
                    if ($_total > 0)
                    {
                        $res = $db->select($_sql);

                        foreach ($res['data'] as $prod)
                        {
                            $add = false;

                            if (empty($prod['lastInvoice']))
                            {
                                $add = true;
                            }
                            else
                            {
                                if ($prod['renewable'] == 'm')
                                {
                                    $month_diff = diff_months($_POST['date'], $prod['lastInvoice']);

                                    if ($month_diff == 1)
                                    {
                                        $add = true;
                                    }
                                }
                                elseif ($prod['renewable'] == 'r')
                                {
                                    $add = true;
                                }
                                elseif ($prod['renewable'] == 'o' && $prod['lastInvoice'] == '')
                                {
                                    $add = true;
                                }
                            }

                            if ($add)
                            {
                                $_temp = $prod;
                                array_push($products, $prod);
                            }
                        }
                    }

                    if (count($products) > 0)
                    {
                        $_results['products'] = $products;
                        $_results['data'] = 'true';
                    }
                    else
                    {
                        $_results['data'] = 'false';
                        $_results['products'] = [];
                        $_results['message'] = 'no products';
                    }
                }
            }
            elseif ($_POST['view_type'] == 'edit')
            {
                $_sql = "SELECT * FROM products WHERE id='" . $_POST['id'] . "';";
                $_results = $db->select($_sql, 'true');
            }
            elseif ($_POST['view_type'] == "search")
            {
                $_sql = "SELECT products.*, clients.business AS clientName, categories.category AS categoryName,(SELECT date FROM invoices_items WHERE invoices_items.products=products.id ORDER BY date DESC LIMIT 1) AS lastInvoice FROM categories RIGHT JOIN (clients RIGHT JOIN products ON clients.id=products.clients) ON categories.id=products.categories WHERE products.clients='" . $_POST['client'] . "'";

                if ($_POST['state'] == 'true' || $_POST['state'] == 'false')
                {
                    $_sql .= " AND products.canceled='" . $_POST['state'] . "';";
                }

                $_results = $db->select($_sql);

                $res = $db->select("SELECT * FROM clients WHERE id='" . $_POST['client'] . "';", 'true');
                $_results['business'] = $res['data']['business'];
            }

            return $_results;
        }
    }
}
