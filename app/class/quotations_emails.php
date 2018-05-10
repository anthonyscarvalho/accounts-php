<?php

class quotations_emails
{
    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            if ($_POST['view_type'] == 'view')
            {
                $_sql = "SELECT * FROM quotations_emails WHERE quote='" . $_POST['quotations'] . "' ORDER BY date DESC";
                $_results = $db->select($_sql);
            }

            return $_results;
        }
    }
}
