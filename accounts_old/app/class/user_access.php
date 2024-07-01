<?php

class user_access
{
    private $table = 'user_access';

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            $vars = [
                "campaigns" => $_POST['campaigns'],
                "categories" => $_POST['categories'],
                "clients" => $_POST['clients'],
                "companies" => $_POST['companies'],
                "contacts" => $_POST['contacts'],
                "email_log" => $_POST['email_log'],
                "expenditure" => $_POST['expenditure'],
                "invoices" => $_POST['invoices'],
                "invoices_emails" => $_POST['invoices_emails'],
                "invoices_items" => $_POST['invoices_items'],
                "logs" => $_POST['logs'],
                "products" => $_POST['products'],
                "statements" => $_POST['statements'],
                "template_attachments" => $_POST['template_attachments'],
                "template_emails" => $_POST['template_emails'],
                "template_quotations" => $_POST['template_quotations'],
                "transactions" => $_POST['transactions'],
                "users" => $_POST['users'],
                "user_access" => $_POST['user_access'],
                "user_roles" => $_POST['user_roles'],
                "company_income" => $_POST['company_income'],
                "report_overview" => $_POST['report_overview'],
                "report_controlsheet" => $_POST['report_controlsheet'],
                "quotations" => $_POST['quotations'],
            ];

            if ($_POST['view_type'] == "save")
            {
                $res = $db->update($this->table, $vars, $_POST['id']);
                if ($res)
                {
                    $_data = 'true';
                    $_message = 'record updated';
                }
                elseif (!$res)
                {
                    $_data = 'false';
                    $_message = $db->getError();
                }
            }
            elseif ($_POST['view_type'] == "create")
            {
                $res = $db->insertData($this->table, $vars);
                if ($res)
                {
                    $_data = 'true';
                    $_message = 'record inserted';
                }
                elseif (!$res)
                {
                    $_data = 'false';
                    $_message = $db->getError();
                }
            }
            $_results['data'] = $_data;
            $_results['message'] = $_message;

            return $_results;
        }
    }

    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            if ($_POST['view_type'] == "edit")
            {
                $_sql = "SELECT * FROM user_access WHERE id IN (SELECT access_list FROM users WHERE id='" . $_POST['id'] . "');";
                $_results = $db->select($_sql, 'true');
            }

            return $_results;
        }
    }
}
