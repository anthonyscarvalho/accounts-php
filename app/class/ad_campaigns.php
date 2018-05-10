<?php

class ad_campaigns
{
    private $table = "ad_campaigns";

    public function clients()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            if ($_POST['view_type'] == "view")
            {
                if ((isset($_POST['notin'])) && ($_POST['notin'] == 'true'))
                {
                    $_sql = "SELECT id, business, city FROM clients WHERE id NOT IN (SELECT clients FROM ad_clients WHERE campaigns='" . $_POST['id'] . "') ORDER BY business";
                }
                else
                {
                    $_sql = "SELECT id, business, city FROM clients WHERE id IN (SELECT clients FROM ad_clients WHERE campaigns='" . $_POST['id'] . "')";
                }
                $_results = $db->select($_sql);

                $_tmp = $db->select("SELECT name FROM ad_campaigns WHERE id='" . $_POST['id'] . "';", 'true');
                if ($_tmp['data'] != '' && isset($_POST['notin']))
                {
                    $_results['campaign'] = $_tmp['data']['name'];
                }
            }
            else if ($_POST['view_type'] == "insert")
            {
                $vars = [
                    "campaigns" => $_POST['campaigns'],
                    "clients" => $_POST['clients'],
                ];

                $res = $db->insertData('ad_clients', $vars);
                if ($res)
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'added';
                    $_results['id'] = $db->lastInsertId();
                    performAction('manager', 'updateAdLog', [$_results['id'], $_SESSION['user'], 'inserted', $vars]);
                }
                else if (!$res)
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }
            }
            else if ($_POST['view_type'] == "delete")
            {
                $_tmp = $db->select("SELECT * FROM ad_clients WHERE campaigns='" . $_POST['campaigns'] . "' AND clients='" . $_POST['clients'] . "';", 'true');
                if ($_tmp['data'] != "")
                {
                    $res = $db->deleteData('ad_clients', 'id=' . $_tmp['data']['id']);
                    $vars = [
                        "id" => $_tmp['data']['id'],
                        "clients" => $_tmp['data']['clients'],
                        "campaigns" => $_tmp['data']['campaigns'],
                    ];
                    performAction('manager', 'updateAdLog', [$_POST['campaigns'], $_SESSION['user'], 'removed', $vars]);
                    if ($res)
                    {
                        $_results['data'] = "true";
                        $_results['message'] = "removed";
                    }
                    else
                    {
                        $_results['data'] = "false";
                        $_results['message'] = $db->getError();
                    }
                }
                else
                {
                    $_results['data'] = 'false';
                    $_results['message'] = 'no data';
                }
            }
            return $_results;
        }
    }

    public function funds()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

// view all clients
            if ($_POST['view_type'] == "view")
            {
                $temp = getdate(date("U", strtotime('now')));
                if (!isset($_POST['month']))
                {
                    if (isset($_POST['year']))
                    {
                        $_year = $_POST['year'];
                    }
                    else
                    {
                        $_year = $temp['year'];
                    }
                    $startDate = $_year . '-03-01';
                    $endDate = ($_year + 1) . '-03-01';
                }
                else
                {
                    if (isset($_POST['year']))
                    {
                        $_year = $_POST['year'];
                    }
                    else
                    {
                        $_year = $temp['year'];
                    }
                    $startDate = $_year . '-' . $_POST['month'] . '-01';
                    $endDate = ($_year + 1) . '-' . ($_POST['month'] + 1) . '-01';
                }
// get any previous credit

                $_openCredit = $db->select("SELECT (SUM(credit) - Sum(debit)) AS total FROM ad_transactions WHERE campaigns='" . $_POST['campaigns'] . "' AND date>='" . $startDate . "';", 'true');
                if ($_openCredit['data']['total'] > 0)
                {
                    $_tempBal = ['id' => '0', 'clientName' => '', 'date' => $startDate, 'credit' => $_openCredit['data']['total'], 'debit' => 0.00, 'comment' => 'Opening Balance'];
                }
                else
                {
                    $_tempBal = ['id' => '0', 'clientName' => '', 'date' => $startDate, 'credit' => 0.00, 'debit' => $_openCredit['data']['total'], 'comment' => 'Opening Balance'];
                }
                $_sql = "SELECT *, (SELECT business FROM clients WHERE id=ad_transactions.clients) AS business FROM ad_transactions WHERE campaigns='" . $_POST['campaigns'] . "' AND date>'" . $startDate . "'  AND date<'" . $endDate . "' ORDER BY date ASC;";

                $_results = $db->select($_sql);
                $_results['query'] = $_sql;
                array_unshift($_results['data'], $_tempBal);
            }
            else if ($_POST['view_type'] == "insert")
            {
                $vars = [
                    "campaigns" => $_POST['campaigns'],
                    "clients" => ((isset($_POST['clients'])) ? $_POST['clients'] : null),
                    "date" => $_POST['date'],
                    "credit" => ((isset($_POST['credit'])) ? $_POST['credit'] : null),
                    "debit" => ((isset($_POST['debit'])) ? $_POST['debit'] : null),
                    "comment" => ((isset($_POST['comment'])) ? $_POST['comment'] : null),
                    "commission" => ((isset($_POST['commission'])) ? $_POST['commission'] : null),
                ];

                $res = $db->insertData('ad_clients', $vars);
                if ($res)
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'added';
                    $_results['id'] = $db->lastInsertId();
                    performAction('manager', 'updateAdLog', [$_results['id'], $_SESSION['user'], 'inserted', $vars]);
                }
                else if (!$res)
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }
            }
            else if ($_POST['view_type'] == "delete")
            {
                $_tmp = $db->select("SELECT * FROM ad_clients WHERE campaigns='" . $_POST['campaigns'] . "' AND clients='" . $_POST['clients'] . "';", 'true');
                if ($_tmp['data'] != "")
                {
                    $res = $db->deleteData('ad_clients', 'id=' . $_tmp['data']['id']);
                    $vars = [
                        "id" => $_tmp['data']['id'],
                        "clients" => $_tmp['data']['clients'],
                        "campaigns" => $_tmp['data']['campaigns'],
                    ];
                    performAction('manager', 'updateAdLog', [$_POST['campaigns'], $_SESSION['user'], 'removed', $vars]);
                    if ($res)
                    {
                        $_results['data'] = "true";
                        $_results['message'] = "removed";
                    }
                    else
                    {
                        $_results['data'] = "false";
                        $_results['message'] = $db->getError();
                    }
                }
                else
                {
                    $_results['data'] = 'false';
                    $_results['message'] = 'no data';
                }
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
                "name" => $_POST['name'],
            ];

            if ($_POST['view_type'] == 'create')
            {
                $res = $db->insertData($this->table, $vars);
                if ($res)
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'added';
                    performAction('manager', 'updateAdLog', [$db->lastInsertId(), $_SESSION['user'], 'inserted', $vars]);
                }
                else if (!$res)
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }
            }
            else if ($_POST['view_type'] == "save")
            {
                $res = $db->update($this->table, $vars, $_POST['id']);

                if ($res)
                {
                    performAction('manager', 'updateAdLog', [$_POST['id'], $_SESSION['user'], 'updated', $vars]);
                    $_results['data'] = 'true';
                    $_results['message'] = 'record updated';
                }
                else if (!$res)
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
                $_results = $db->select("SELECT * FROM ad_campaigns;");
            }
            else if ($_POST['view_type'] == "edit")
            {
                $_results = $db->select("SELECT * FROM ad_campaigns WHERE id='" . $_POST['id'] . "';", 'true');
            }
            else if ($_POST['view_type'] == "retrieve")
            {
                $_results = $db->select("SELECT id, category, price FROM categories WHERE link='" . $_POST['link'] . "' AND canceled='false' ORDER BY category ASC;");
                $_results['nologin'] = 'true';
            }
            return $_results;
        }
    }
}
