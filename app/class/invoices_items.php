<?php

class invoices_items
{
    private $table = "invoices_items";

    public function delete()
    {
        global $db;
        $_invoiceItem = $db->select("SELECT * FROM invoices_items WHERE id='" . $_POST['id'] . "';", 'true');

        $_invoiceItems = $db->numRows("SELECT * FROM invoices_items WHERE invoices='" . $_invoiceItem['data']['invoices'] . "';", 'true');

        if ($_invoiceItems > 1)
        {
            $res = $db->deleteData('invoices_items', "id='".  $_POST['id']."'");

            if ($res == "true")
            {
                $invoice = $db->select("SELECT Sum(price) AS Total FROM invoices_items WHERE invoices='" . $_invoiceItem['data']['invoices'] . "';", "true");
                $res2 = $db->update("UPDATE invoices SET invoice_total='" . $invoice['data']['Total'] . "' WHERE id='" . $_invoiceItem['data']['invoices'] . "';");

                if ($res2)
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'item remove';
                }
                else
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'item removed but invoice not updated';
                }
            }
            else
            {
                $_results['data'] = "false";
                $_results['message'] = $db->getError();
            }
        }
        else
        {
            $_results['data'] = "false";
            $_results['message'] = 'can not remove item from invoice';
        }

        return $_results;
    }

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            $vars = [
                "price" => $_POST['price'],
                "description" => $_POST['description'],
            ];

            $res = $db->update('invoices_items', $vars, $_POST['id']);

            if ($res)
            {
                $invoice = $db->select("SELECT Sum(price) AS Total FROM invoices_items WHERE invoices='" . $_POST['invoices'] . "';", "true");
                $res2 = $db->update("UPDATE invoices SET invoice_total='" . $invoice['data']['Total'] . "' WHERE id='" . $_POST['invoices'] . "';");

                if ($res2 == "true")
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'item updated';
                    $_vars = [
                        "clients" => $_POST['clientsId'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => 'updated',
                        "data" => json_encode($vars),
                    ];
                    performAction('manager', 'updateLog', ['logs', $_vars]);
                }
                else
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'item updated but not invoice';
                }
            }
            elseif (!$res)
            {
                $_results['data'] = 'false';
                $_results['message'] = $db->getError();
            }

            return $_results;
        }
    }

    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            if ($_POST['view_type'] == "view")
            {
                $_sql = "SELECT invoices_items.id, invoices_items.date, categories.category as categoryName, invoices_items.description, invoices_items.price "
                    . "FROM categories RIGHT JOIN invoices_items ON categories.id=invoices_items.categories WHERE invoices_items.invoices='" . $_POST['invoice'] . "' ORDER BY date ASC;";
                $_results = $db->select($_sql);
            }
            elseif ($_POST['view_type'] == 'search')
            {
                $_sql = "SELECT * FROM invoices_items WHERE products='" . $_POST['id'] . "' ORDER BY date DESC LIMIT 15;";
                $_results = $db->select($_sql);
            }
            elseif ($_POST['view_type'] == "edit")
            {
                $_sql = "SELECT *, (SELECT category FROM categories WHERE id=invoices_items.categories) AS categoryName, (SELECT clients FROM invoices WHERE id=invoices_items.invoices) AS clientsId FROM invoices_items WHERE id='" . $_POST['id'] . "';";
                $_results = $db->select($_sql, 'true');
            }

            return $_results;
        }
    }
}
