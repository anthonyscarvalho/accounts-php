<?php

class campaigns_clients
{
    private $table = "ad_clients";

    public function delete()
    {
        global $db;
        $_tmp = $db->select("SELECT * FROM ad_clients WHERE campaigns='" . $_POST['campaigns'] . "' AND clients='" . $_POST['clients'] . "';", 'true');
        if ($_tmp['data'] != "")
        {
            $res = $db->deleteData('ad_clients', 'id=' . $_tmp['data']['id']);
            $_vars = [
                "campaigns" => $_POST['campaigns'],
                "date" => current_dateTime(),
                "users" => $_SESSION['user'],
                "affected_table" => $this->table,
                "action" => "removed",
                "data" => json_encode($_tmp['data']),
            ];

            performAction('manager', 'updateLog', ['ad_logs', $_vars]);
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
        return $_results;
    }

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            $vars = [
                "campaigns" => $_POST['campaigns'],
                "clients" => $_POST['clients'],
            ];

            if ($_POST['view_type'] == 'create')
            {
                $res = $db->insertData($this->table, $vars);
                if ($res)
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'added';
                    $vars['id'] = $db->lastInsertId();
                    $_vars = [
                        "campaigns" => $_POST['campaigns'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => "inserted",
                        "data" => json_encode($vars),
                    ];

                    performAction('manager', 'updateLog', ['ad_logs', $_vars]);
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
            return $_results;
        }
    }
}
