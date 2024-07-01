<?php

class quotations
{
    private $_results;
    private $_donotsubmitlogedindata;
    private $_donotsubmitdata;

    public function retrieve()
    {
        $this->_donotsubmitlogedindata = true;
        global $db;
        $_sql = "SELECT id, business FROM clients WHERE canceled='false' ORDER BY business;";
        $this->_results = $db->select($_sql);
    }

    public function view($state = null)
    {
        global $db;
        $_sql = "SELECT * FROM quotations";
        if (!empty($state))
        {
            if ($state == 'true')
            {
                $_sql .= " WHERE accepted='true";
            }
            else if ($state == 'false')
            {
                $_sql .= " WHERE canceled='" . $state . "'";
            }
            else if ($state == 'pending')
            {
                $_sql .= " WHERE accepted='false' AND canceled='" . $state . "'";
            }
        }
        $this->_results = $db->select($_sql);
    }

    public function edit($id = null)
    {
        global $db;
        $this->_results = $db->select("SELECT * FROM clients WHERE id='" . $id . "';", 'true');

        $_sql = "SELECT SUM(debit) AS total FROM statements WHERE clients='" . $id . "';";
        $res = $db->select($_sql, 'true');
        $debit = $res['data']['total'];

        $_sql = "SELECT SUM(credit) AS total FROM statements WHERE clients='" . $id . "';";
        $res = $db->select($_sql, 'true');
        $credit = $res['data']['total'];

        $this->_results['balance'] = ($credit - $debit);
    }

    public function create()
    {
        global $db;

        $vars = [
            "clients" => $_POST['business'],
            "companies" => $_POST['vat'],
            "template" => $_POST['number'],
            "domain" => $_POST['fax'],
            "creation_date" => current_date(),
            "city" => $_POST['city'],
            "postal_code" => $_POST['postal_code'],
            "signup_date" => $_POST['signup_date'],
            "canceled" => 'false',
        ];

        $res = $db->insertData('clients', $vars);
        if ($res)
        {
            $this->_results['data'] = 'true';
            $this->_results['message'] = 'added';
            $this->_results['id'] = $db->lastInsertId();
            performAction('manager', 'updateLog', [$this->_results['id'], $this->logged_in['user']['id'], 'inserted', 'clients', $vars]);
        }
        else if (!$res)
        {
            $this->_results['data'] = 'false';
            $this->_results['message'] = $db->getError();
        }
    }

    public function save()
    {
        global $db;
        $vars = [
            "business" => $_POST['business'],
            "vat" => $_POST['vat'],
            "number" => $_POST['number'],
            "fax" => $_POST['fax'],
            "billing_address" => $_POST['billing_address'],
            "city" => $_POST['city'],
            "postal_code" => $_POST['postal_code'],
            "notes" => $_POST['notes'],
            "bad_client" => $_POST['bad_client'],
        ];

        $res = $db->update('clients', $vars, $_POST['id']);

        if ($res)
        {
            performAction('manager', 'updateLog', [$_POST['id'], $this->logged_in['user']['id'], 'updated', 'clients', $vars]);
            $this->_results['data'] = 'true';
            $this->_results['message'] = 'record updated';
        }
        else if (!$res)
        {
            $this->_results['data'] = 'false';
            $this->_results['message'] = $db->getError();
        }
    }

    public function cancel($id = null)
    {
        global $db;
        $res = $db->update("UPDATE clients SET canceled='true', canceled_date='" . current_date() . "' WHERE id='" . $id . "';");
        if ($res)
        {
            $this->_results['data'] = "true";
            $this->_results['message'] = "canceled";
            performAction('manager', 'updateLog', [$id, $this->logged_in['user']['id'], 'canceled', 'clients', ["id" => $id]]);
        }
        else
        {
            $this->_results['data'] = "false";
            $this->_results['message'] = $db->getError();
        }
    }

    public function enable($id = null)
    {
        global $db;
        $res = $db->update("UPDATE clients SET canceled='false', canceled_date=NULL WHERE id='" . $id . "';");
        if ($res)
        {
            $this->_results['data'] = "true";
            $this->_results['message'] = "enabled";
            performAction('manager', 'updateLog', [$id, $this->logged_in['user']['id'], 'enabled', 'clients', ["id" => $id]]);
        }
        else
        {
            $this->_results['data'] = "false";
            $this->_results['message'] = $db->getError();
        }
    }

    public function afterAction($verified)
    {
        if ($this->_donotsubmitdata == false)
        {
            if ($this->_donotsubmitlogedindata == false)
            {
                $this->_results['user']['logged_in'] = $verified['logged_in'];
                $this->_results['user']['user_roles'] = $verified['user_roles'];
                $this->_results['user']['user_access'] = $verified['user_access'];
            }
            echo (json_encode($this->_results));
        }
    }
}
