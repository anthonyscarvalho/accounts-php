<?php

class campaigns_logs
{
    private $table = "ad_logs";

    public function view()
    {
        if (isset($_POST['view_type'])) {
            global $db;

            if ($_POST['view_type'] == "view") {
                $_sql = "SELECT ad_logs.id, (users.name) AS userName, ad_logs.date, ad_logs.action, ad_logs.affected_table FROM users RIGHT JOIN ad_logs ON users.id=ad_logs.users WHERE ad_logs.campaigns='" . $_POST['campaigns'] . "' ORDER BY ad_logs.date DESC;";

                $_results = $db->select($_sql);

            } elseif ($_POST['view_type'] == "edit") {
                $_results = $db->select("SELECT *, (SELECT name FROM users WHERE id=ad_logs.users) AS userName FROM " . $this->table . " WHERE id='" . $_POST['id'] . "';", 'true');
            }

            return $_results;
        }

    }

}
