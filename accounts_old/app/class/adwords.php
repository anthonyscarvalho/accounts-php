<?php

class adwords
{
    private $db;

    public function create()
    {
        if (isset($_POST['date']))
        {
            $this->db = new sql();

            $vars = [
                "clients" => $_POST['clients'],
                "date" => $_POST['date'],
                "credit" => (!empty($_POST['credit']) ? $_POST['credit'] : null),
                "debit" => (!empty($_POST['debit']) ? $_POST['debit'] : null),
                "comment" => (!empty($_POST['comment']) ? $_POST['comment'] : null),
            ];

            $res = $this->db->insertData('adwords', $vars);
            if ($res)
            {
                $_results['data'] = 'true';
                $_results['message'] = 'added';
                performAction('manager', 'updateLog', [$_POST['clients'], $_SESSION['user'], 'inserted', 'adwords', $vars]);
            }
            else if (!$res)
            {
                $_results['data'] = 'false';
                $_results['message'] = $this->db->getError();
            }
        }
        else
        {
            $_results['data'] = 'false';
            $_results['message'] = "nothing passed";
        }
        return $_results;
    }

    public function credit()
    {
        $_results['nologin'] = 'true';
        $this->db = new sql();

        $_sql = "SELECT clients.business AS clientName, (Sum(adwords.credit) - Sum(adwords.debit)) AS adsCredit "
            . "FROM clients RIGHT JOIN adwords ON clients.id=adwords.clients "
            . "GROUP BY adwords.clients "
            . "ORDER BY clients.business;";

        $_results = $this->db->select($_sql);
        return $_results;
    }

    public function delete($id = null)
    {
        $this->db = new sql();
        $_client = $this->db->select("SELECT * FROM adwords WHERE id='" . $id . "';", "true");
        $res = $this->db->deleteData("adwords", "id=" . $id);
        if ($res == "true")
        {
            $_results['data'] = "true";
            $_results['message'] = "deleted";
            performAction('manager', 'updateLog', [$_client['data']['clients'], $_SESSION['user'], 'deleted', 'adwords', ["id" => $id]]);
        }
        else
        {
            $_results['data'] = "false";
            $_results['message'] = $this->db->getError();
        }
        return $_results;
    }

    public function edit($id = null)
    {
        $this->db = new sql();
        $_sql = "SELECT * "
            . "FROM adwords "
            . "WHERE id='" . $id . "';";

        $_results = $this->db->select($_sql, 'true');

        $res = $this->db->select("SELECT * FROM clients WHERE id='" . $_results['data']['clients'] . "';", 'true');
        $_results['business'] = $res['data']['business'];
        return $_results;
    }

    public function email()
    {
        $this->db = new sql();

        $contacts_query = "SELECT * FROM contacts WHERE clients='" . $_POST['clients'] . "' AND adwords='true';";

        $total_contacts = $this->db->numRows($contacts_query);

        if ($total_contacts > 0)
        {
            $_client = $this->db->select("SELECT * FROM clients WHERE id='" . $_POST['clients'] . "';", 'true');
            $origin_body = $_POST['emailbody'];
            $origin_body = str_replace('#business#', $_client['data']['business'], $origin_body);
            $origin_body = str_replace('#billing_address#', $_client['data']['billing_address'], $origin_body);
            $origin_body = str_replace('#city#', $_client['data']['city'], $origin_body);
            $origin_body = str_replace('#postal_code#', $_client['data']['postal_code'], $origin_body);
            $origin_body = str_replace('#vat#', $_client['data']['vat'], $origin_body);
            $origin_body = str_replace('#client_id#', $_client['data']['id'], $origin_body);
            $subject = $_POST['emailsubject'];
            $_mes = "";

            $contacts = $this->db->select($contacts_query);

            foreach ($contacts['data'] as $contact):
                $body = $origin_body;
                $body = str_replace('#name#', $contact['name'], $body);
                $body = str_replace('#surname#', $contact['surname'], $body);

                $body = str_replace('#date#', $_POST['date'], $body);
                $body = str_replace('#due_date#', $_POST['due_date'], $body);
                $body = str_replace('#month#', $_POST['month'], $body);
                $body = str_replace('#amount#', number_format($_POST['amount'], 2, '.', ' '), $body);

                $contacts['email'] = $contact['email'];
                $contacts['name'] = $contact['name'];
                $contacts['surname'] = $contact['surname'];

                $res2 = sendEmail($contacts, $subject, $body, '', '');

                if ($res2['status'])
            {
                    performAction('manager', 'updateEmailLog', [$contact['id'], $_SESSION['user'], $subject, $body, $res2['server_log']]);

                    $_mes .= "email sent to: " . $contacts['name'] . "<br/>";
                }
            else
            {
                    $_mes .= "email not sent to: " . $contacts['name'] . "<br/>";
                }
            endforeach;
        }
        if ($_mes != "")
        {
            $_results['data'] = 'true';
            $_results['message'] = $_mes;
        }
        return $_results;
    }

    public function save()
    {
        $this->db = new sql();
        $vars = [
            "date" => $_POST['date'],
            "credit" => (!empty($_POST['credit']) ? $_POST['credit'] : null),
            "debit" => (!empty($_POST['debit']) ? $_POST['debit'] : null),
            "comment" => $_POST['comment'],
        ];

        $res = $this->db->update('adwords', $vars, $_POST['id']);
        if ($res)
        {
            $_results['data'] = 'true';
            $_results['message'] = 'record updated';
            performAction('manager', 'updateLog', [$_POST['clients'], $_SESSION['user'], 'updated', 'adwords', $vars]);
        }
        else if (!$res)
        {
            $_results['data'] = 'false';
            $_results['message'] = $this->db->getError();
        }
        return $_results;
    }

    public function view($id = null)
    {
        if (isset($_POST['view_type']))
        {
            $this->db = new sql();
// get the year and month to search for
            $temp = getdate(date("U", strtotime('now')));
            if (empty($_POST['year']))
            {
                $year = $temp['year'];
            }
            else
            {
                $year = $_POST['year'];
            }
            $date = $year;

            if (!empty($_POST['month']))
            {
                $month = $_POST['month'];
                $date .= '-' . $month . "-01";
            }
            else
            {
                $month = null;
                $date .= '-01-01';
            }

// view all clients
            if ($_POST['view_type'] == "view")
            {
// get any previous credit
                $_prevCredit = "SELECT (SUM(credit) - Sum(debit)) AS total FROM adwords WHERE date<'" . $date . "';";

                $_openCredit = $this->db->select($_prevCredit, 'true');
                if ($_openCredit['data']['total'] > 0)
                {
                    $_tempBal = ['id' => '0', 'clientName' => '', 'date' => $date, 'credit' => $_openCredit['data']['total'], 'debit' => null, 'comment' => 'Opening Balance'];
                }
                else
                {
                    $_tempBal = ['id' => '0', 'clientName' => '', 'date' => $date, 'credit' => null, 'debit' => $_openCredit['data']['total'], 'comment' => 'Opening Balance'];
                }

                $_sql = "SELECT adwords.id, clients.business AS clientName, adwords.date, adwords.credit, adwords.debit, adwords.comment "
                    . "FROM clients RIGHT JOIN adwords ON clients.id=adwords.clients "
                    . "WHERE Year(adwords.date)='" . $year . "' ";

                if (!empty($month))
                {
                    $_sql .= "AND Month(adwords.date)='" . $month . "' ";
                }

                $_sql .= "ORDER BY clients.business, adwords.date ASC;";

                $_results = $this->db->select($_sql);
                array_unshift($_results['data'], $_tempBal);
            }
// search for a specific client
            else if ($_POST['view_type'] == "search")
            {
                // get any previous credit
                $_prevCredit = "SELECT (SUM(credit) - Sum(debit)) AS total FROM adwords WHERE date<'" . $date . "' AND clients='" . $_POST['client'] . "';";
                $_openCredit = $this->db->select($_prevCredit, 'true');
                if ($_openCredit['data']['total'] > 0)
                {
                    $_tempBal = ['id' => '0', 'clientName' => '', 'date' => $date, 'credit' => $_openCredit['data']['total'], 'debit' => null, 'comment' => 'Opening Balance'];
                }
                else
                {
                    $_tempBal = ['id' => '0', 'clientName' => '', 'date' => $date, 'credit' => null, 'debit' => $_openCredit['data']['total'], 'comment' => 'Opening Balance'];
                }

                $_sql = "SELECT * "
                    . "FROM adwords "
                    . "WHERE Year(date)='" . $year . "' AND clients='" . $_POST['client'] . "' ";

                if (!empty($month))
                {
                    $_sql .= "AND Month(date)='" . $month . "' ";
                }

                $_sql .= "ORDER BY date ASC;";
                $_results['data'] = $this->db->select($_sql);

                array_unshift($_results['data']['data'], $_tempBal);

                $res = $this->db->select("SELECT * FROM clients WHERE id='" . $_POST['client'] . "';", 'true');
                $_results['data']['business'] = $res['data']['business'];

            }
// edit a record
            else if ($_POST['view_type'] == "edit")
            {
                $_sql = "SELECT * "
                    . "FROM adwords "
                    . "WHERE id='" . $_POST['id'] . "';";

                $_results = $this->db->select($_sql, 'true');
            }
            return $_results;
        }
    }
}
