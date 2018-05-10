<?php

class email_log
{
    public function preview($id = null)
    {
        global $db;

        $_sql = "SELECT email_log.id, (users.name) AS userName, (contacts.name) AS contactName, email_log.subject, email_log.body, email_log.date, email_log.status, email_log.invoices, email_log.quotes "
            . "FROM contacts RIGHT JOIN (users RIGHT JOIN email_log ON users.id=email_log.users) ON contacts.id=email_log.contacts "
            . "WHERE email_log.id='" . $id . "' ORDER BY email_log.date DESC;";
        return $db->select($_sql, 'true');
    }

    public function search($id = null)
    {
        global $db;

        $_sql = "SELECT email_log.id, (users.name) AS userName, (contacts.name) AS contactName, email_log.subject, email_log.body, email_log.date "
            . "FROM contacts RIGHT JOIN (users RIGHT JOIN email_log ON users.id=email_log.users) ON contacts.id=email_log.contacts "
            . "WHERE contacts.clients='" . $id . "' ORDER BY email_log.date DESC;";
        $_results = $db->select($_sql);
        $res = $db->select("SELECT * FROM clients WHERE id='" . $id . "';", 'true');
        $_results['business'] = $res['data']['business'];
        return $_results;
    }
}
