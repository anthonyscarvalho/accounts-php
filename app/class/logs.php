<?php

class logs
{
    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            if ($_POST['view_type'] == "view")
            {
                $_sql = "SELECT logs.id, (clients.business) AS clientName, (users.name) AS userName, logs.date, logs.action, logs.affected_table FROM users RIGHT JOIN (clients RIGHT JOIN logs ON clients.id=logs.clients) ON users.id=logs.users ORDER BY logs.date DESC;";
                $_results = $db->select($_sql);
            }
            elseif ($_POST['view_type'] == "search")
            {
                $_results = $db->select("SELECT logs.id, (users.name) AS userName, logs.date, logs.action, logs.affected_table FROM users RIGHT JOIN (clients RIGHT JOIN logs ON clients.id=logs.clients) ON users.id=logs.users WHERE logs.clients='" . $_POST['id'] . "' ORDER BY logs.date DESC;");

                $res = $db->select("SELECT * FROM clients WHERE id='" . $_POST['id'] . "';", 'true');
                $_results['business'] = $res['data']['business'];
            }
            elseif ($_POST['view_type'] == "edit")
            {
                $_sql = "SELECT logs.id, clients.business, users.name AS userName, logs.date, logs.action, logs.affected_table, logs.data "
                    . "FROM users RIGHT JOIN (clients RIGHT JOIN logs ON clients.id=logs.clients) ON users.id=logs.users "
                    . "WHERE logs.id='" . $_POST['id'] . "';";
                $_results = $db->select($_sql, 'true');
            }

            return $_results;
        }
    }
}
