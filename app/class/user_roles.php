<?php

class user_roles
{
    private $_results;

    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            if ($_POST['view_type'] == 'view')
            {
                $_results = $db->select("SELECT * FROM user_roles ORDER BY role;");
            }
            return $_results;
        }
    }
}
