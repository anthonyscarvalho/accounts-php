<?php

class invoices_emails
{
    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            if ($_POST['view_type'] == 'view')
            {
                $_sql = "SELECT * FROM invoices_emails WHERE invoice='" . $_POST['invoice'] . "' ORDER BY date DESC";
                $_results = $db->select($_sql);
            }

            return $_results;
        }
    }
}
