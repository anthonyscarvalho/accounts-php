<?php

class campaigns_statements
{
    public function preview()
    {
        ob_start();
        global $db;
        $statements = performAction('statements', 'view', []);

        $_totalStatments = count($statements);

        $_invSql = "SELECT * FROM invoices WHERE clients='" . $_POST['clients'] . "' AND paid='false' AND canceled='false'";

        if ($_POST['endDate'] != '')
        {
            $_invSql .= " AND due_date<'" . $_POST['endDate'] . "'";
        }

// total invoices
        $_totalInv = $db->numRows($_invSql);
// get data needed for the templates
        $_client = $db->select("SELECT * FROM clients WHERE id='" . $_POST['clients'] . "';", 'true');
        $_company = $db->select("SELECT * FROM companies WHERE id='1';", 'true');
// get the various templates
        $_statementTemplate = $db->select("SELECT * FROM template_attachments WHERE id='3';", 'true');
        $_invoiceTemplate = $db->select("SELECT * FROM template_attachments WHERE id='1';", 'true');
// set the name of the pdf
        $_pdfData['pdfName'] = $_client['data']['id'] . '-' . $_client['data']['business'] . '-Statement for:' . $_POST['startDate'] . '_' . $_POST['endDate'] . '.pdf';
// start replacing data in the template
        $template = $_statementTemplate['data']['template'];
        $template = str_replace('#invoice_header#', $_company['data']['invoice_header'], $template);
        $template = str_replace('#start_date#', $_POST['startDate'], $template);
        $template = str_replace('#end_date#', $_POST['endDate'], $template);
        $template = str_replace('#business#', $_client['data']['business'], $template);
        $template = str_replace('#billing_address#', $_client['data']['billing_address'], $template);
        $template = str_replace('#city#', $_client['data']['city'], $template);
        $template = str_replace('#postal_code#', $_client['data']['postal_code'], $template);

        if ($_client['data']['vat'] != "")
        {
            $template = str_replace('#vat#', "VAT #: " . $_client['data']['vat'], $template);
        }
        else
        {
            $template = str_replace('#vat#', '', $template);
        }

        $template = str_replace('#account_details#', $_company['data']['account_details'], $template);
// start the layout of all transactions
        $trans_html = '<table bgcolor="#ccc" cellspacing="1" cellpadding="2" border="0" style="font-size:8pt; width:1920px;">'
            . '<tr height="30" style="font-weight:bold; text-align:center; background-color:#efefef;">'
            . '<td style="width:80px;">Date</td>'
            . '<td style="width:200px;">Description</td>'
            . '<td style="width:80px;">Payments</td>'
            . '<td style="width:80px;">Debit</td>'
            . '<td style="width:80px;">Balance</td>'
            . '</tr>';

// loop through transactions and add to template
        if ($_totalStatments > 0)
        {
            $_credit = 0;
            $_debit = 0;
            $total = 0;
            foreach ($statements['data'] as $_statement)
            {
                if (!empty($_statement['credit']))
                {
                    $total += $_statement['credit'];
                    $_credit += $_statement['credit'];
                }
                elseif (empty($_statement['credit']))
                {
                    $total -= $_statement['debit'];
                    $_debit += $_statement['debit'];
                }

                $trans_html .= '<tr style = "background-color: #fff;">'
                . '<td>' . $_statement['date'] . '</td>'
                . '<td>' . substr($_statement['description'], 0, 150) . '</td>'
                . '<td>' . (isset($_statement['credit']) ? 'R ' . number_format($_statement['credit'], 2, '.', ' ') : '') . '</td>'
                . '<td>' . (isset($_statement['debit']) ? 'R ' . number_format($_statement['debit'], 2, '.', ' ') : '') . '</td>'
                . '<td>R ' . number_format($total, 2, '.', ' ') . '</td>'
                    . '</tr>';
                $trans_total = $total;
            }

            $trans_html .= '<tr height = "30" style = "background-color: #efefef;">'
            . '<td colspan="2" style="text-align:right;">Totals</td>'
            . '<td>R ' . number_format($_credit, 2, '.', ' ') . '</td>'
            . '<td>R ' . number_format($_debit, 2, '.', ' ') . '</td>'
            . '<td>R ' . number_format($trans_total, 2, '.', ' ') . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td colspan="4" style="text-align:right;">Outstanding</td>'
            . '<td>R ' . number_format($trans_total, 2, '.', ' ') . '</td>'
                . '</tr>'
                . '</table>';
        }
        else
        {
            $trans_html .= '<tr height = "30" style = "background-color: #efefef;">'
            . '<td colspan = "2" style = "text-align:right;">Totals</td>'
            . '<td>R ' . number_format($_credit, 2, '.', ' ') . '</td>'
            . '<td>R ' . number_format($_debit, 2, '.', ' ') . '</td>'
            . '<td>R ' . number_format($total, 2, '.', ' ') . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td colspan = "4" style = "text-align:right;">Outstanding</td>'
            . '<td>R ' . number_format($total, 2, '.', ' ') . '</td>'
                . '</tr>'
                . '</table>';
        }

        $template = str_replace('#transactions#', $trans_html, $template);
// add template to pdf variable
        $_pdfData['page'][0] = $template;

        if ($_totalInv > 0)
        {
            $invoices = '';
// find any unpaid invoices
            $_allInv = $db->select($_invSql);

// loop through all unpaid invoices
            foreach ($_allInv['data'] as $_inv)
            {
// determine invoice overlay
                if ($_inv['paid'] == "true")
                {
                    $invoices['overlay'] = 'true';
                    $invoices['overlayFill'][0] = 151;
                    $invoices['overlayFill'][1] = 223;
                    $invoices['overlayFill'][2] = 74;
                    $invoices['overlayDraw'][0] = 110;
                    $invoices['overlayDraw'][1] = 129;
                    $invoices['overlayDraw'][2] = 70;
                    $invoices['overlayText'] = 'paid';
                }
                elseif ($_inv['paid'] == "false")
                {
                    $invoices['overlay'] = 'true';
                    $invoices['overlayFill'][0] = 249;
                    $invoices['overlayFill'][1] = 59;
                    $invoices['overlayFill'][2] = 59;
                    $invoices['overlayDraw'][0] = 127;
                    $invoices['overlayDraw'][1] = 0;
                    $invoices['overlayDraw'][2] = 0;
                    $invoices['overlayText'] = 'unpaid';
                }
                else
                {
                    $invoices['overlay'] = 'false';
                }

// setup the invoice template
                $temp_template = $_invoiceTemplate['data']['template'];
                $temp_template = str_replace('#invoice_header#', $_company['data']['invoice_header'], $temp_template);
                $temp_template = str_replace('#invoice_id#', $_inv['id'], $temp_template);
                $temp_template = str_replace('#creation_date#', $_inv['creation_date'], $temp_template);
                $temp_template = str_replace('#due_date#', $_inv['due_date'], $temp_template);
                $temp_template = str_replace('#business#', $_client['data']['business'], $temp_template);
                $temp_template = str_replace('#billing_address#', $_client['data']['billing_address'], $temp_template);
                $temp_template = str_replace('#city#', $_client['data']['city'], $temp_template);
                $temp_template = str_replace('#postal_code#', $_client['data']['postal_code'], $temp_template);

                if ($_client['data']['vat'] != "")
                {
                    $temp_template = str_replace('#vat#', "VAT #: " . $_client['data']['vat'], $temp_template);
                }
                else
                {
                    $temp_template = str_replace('#vat#', '', $temp_template);
                }

                $temp_template = str_replace('#client_id#', $_client['data']['id'], $temp_template);
                $temp_template = str_replace('#account_details#', $_company['data']['account_details'], $temp_template);

//find all items associated with unpaid invoice
                # Transaction Items
                $_invTrans = $db->select("SELECT * FROM transactions WHERE invoices='" . $_inv['id'] . "';");
                $trans_html = '<table width="100 % " bgcolor="#ccc" cellspacing="1" cellpadding="2" border="0" style="font-size:9pt;">'
                    . '<thead>'
                    . '<tr height="30" style="font-weight:bold; text-align:center; background-color:#efefef;">'
                    . '<td style="width:60px;">Date</td>'
                    . '<td style="width:380px;">Description</td>'
                    . '<td style="width:100px;">Amount</td>'
                    . '</tr>'
                    . '</thead>';

                $trans_total = 0;

                foreach ($_invTrans['data'] as $trans)
                {
                    if (!empty($trans['debit']))
                    {
                        $trans_html .= '<tr style="background-color: #fff;">'
                        . '<td style="width:60px;">' . $trans['date'] . '</td>'
                        . '<td style="width:380px;">' . $trans['description'] . '</td>'
                        . '<td style="width:100px;">R ' . number_format($trans['debit'], 2, '.', ' ') . '</td>'
                            . '</tr>';
                        $trans_total += $trans['debit'];
                    }
                }

                $trans_html .= '</table>';
# Invoice Items
                $_invItems = $db->select("SELECT invoices_items.date, categories.category AS categoryName, invoices_items.description, invoices_items.price FROM categories RIGHT JOIN invoices_items ON categories.id=invoices_items.categories WHERE invoices_items.invoices='" . $_inv['id'] . "' ORDER BY categories.category;");
                $items_html = '<table width="100%" cellspacing="1" cellpadding="2" border="0" style="background-color:#ccc; font-size:9pt;">'
                    . '<tr height="30" style="font-weight:bold; text-align:center; background-color:#efefef;">'
                    . '<td style="width:60px;">Date</td>'
                    . '<td style="width:400px;">Item</td>'
                    . '<td style="width:80px;">Price</td>'
                    . '</tr>';

                $subtotal = 0;

                foreach ($_invItems['data'] as $items)
                {
                    $items_html .= '<tr style="background-color:#fff;">'
                    . '<td>' . $items['date'] . '</td>'
                    . '<td>' . $items['categoryName'] . '</td>'
                    . '<td>R ' . number_format($items['price'], 2, '.', ' ') . '</td>'
                        . '</tr>';

                    if ($items['description'] != "")
                    {
                        $items_html .= '<tr style="background-color:#fff;"><td></td><td colspan="2">' . $items['description'] . '</td></tr>';
                    }

                    $subtotal += $items['price'];
                }

                $items_html .= '<tr height="30" style="background-color: #efefef;">'
                . '<td colspan="2" style="text-align:right;">Invoice Total</td>'
                . '<td>R ' . number_format($subtotal, 2, '.', ' ') . '</td>'
                . '</tr>'
                . '<tr height="30" style="background-color: #efefef;">'
                . '<td colspan="2" style="text-align:right;">Total Paid</td>'
                . '<td>R ' . number_format($trans_total, 2, '.', ' ') . '</td>'
                . '</tr>'
                . '<tr height="30" style="background-color: #010180; font-weight:bold; color:#fff;">'
                . '<td colspan="2" style="text-align:right;">Total Due</td>'
                . '<td>R ' . number_format(($subtotal - $trans_total), 2, '.', ' ') . '</td>'
                    . '</tr>'
                    . '</table>';

                $temp_template = str_replace('#invoice_items#', $items_html, $temp_template);

                $temp_template = str_replace('#invoice_transactions#', $trans_html, $temp_template);
                $invoices['body'] = $temp_template;
                array_push($_pdfData['page'], $invoices);
            }
        }

        if ($_POST['display'] == "print")
        {
            createPDF('print', $_pdfData);
            $_results['nologin'] = 'true';
            $_results['nodata'] = 'true';
        }
        elseif ($_POST['display'] == "email")
        {
// set the email body and subject up
            $origin_body = $_POST['emailbody'];
            $subject = $_POST['emailsubject'];
// print the pdf for email use
            $_invoicePDF = createPDF('email', $_pdfData);
// tally total contacts
            $_contactSql = "SELECT * FROM contacts WHERE clients='" . $_POST['clients'] . "';";
            $_totContacts = $db->numRows($_contactSql);

            if ($_totContacts > 0)
            {
// get all contacts for client
                $_contacts = $db->select($_contactSql);
                $_results['message'] = '';

                foreach ($_contacts['data'] as $_contact)
                {
                    $body = $origin_body;
                    $body = str_replace('#name#', $_contact['name'], $body);
                    $body = str_replace('#surname#', $_contact['surname'], $body);

                    $res = sendEmail($_contact, $subject, $body, $_pdfData['pdfName'], $_invoicePDF);

                    performAction('manager', 'updateEmailLog', [$_contact['id'], $_SESSION['user'], $subject, $body, $res['server_log']]);
                    $_results['message'] .= 'sent: ' . $_contact['name'] . ' ' . $_contact['surname'] . "<br>";
                }

                $_results['data'] = 'true';
            }
            else
            {
                $_results['data'] = 'false';
                $_results['message'] = 'no contacts to send to';
            }
        }

        return $_results;
    }

    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            if ($_POST['view_type'] == "view")
            {
                $d1 = getdate(date(strtotime($_POST['date'])));
// get the opening balance

                $_res = $db->select("SELECT SUM(credit) AS totCredit FROM ad_transactions WHERE campaigns=" . $_POST['campaigns'] . " AND date<'" . $d1['year'] . "-" . $d1['mon'] . "-01" . "';", 'true');
                $_credit = $_res['data']['totCredit'];
                $_res = $db->select("SELECT SUM(debit) AS totDebit FROM ad_transactions WHERE campaigns=" . $_POST['campaigns'] . " AND date<'" . $d1['year'] . "-" . $d1['mon'] . "-01" . "';", 'true');
                $_debit = $_res['data']['totDebit'];

                $_balance = ($_credit - $_debit);

                if ($_balance < 0)
                {
                    $_tempBal = ['date' => $_POST['start'], 'description' => 'Opening Balance', 'credit' => null, 'debit' => -($_balance)];
                }
                else
                {
                    $_tempBal = ['date' => $_POST['start'], 'description' => 'Opening Balance', 'credit' => $_balance, 'debit' => null];
                }

                // get all other records

                $_sql = "SELECT id, date, 'Payment - Thank You' AS description, credit, debit FROM ad_transactions
                WHERE campaigns='" . $_POST['campaigns'] . "' AND credit IS NOT NULL AND date>='" . $d1['year'] . "-" . $d1['mon'] . "-01" . "' AND date<'" . $d1['year'] . "-" . ($d1['mon'] + 1) . "-01" . "'  AND commission='false'
                ORDER BY id;";

                $temp = $db->select($_sql);
                $_temp1 = $temp['data'];

                $_sql = "SELECT id, date, 'Payment - To Google' AS description, credit, debit FROM ad_transactions
                WHERE campaigns='" . $_POST['campaigns'] . "' AND debit IS NOT NULL AND date>='" . $d1['year'] . "-" . $d1['mon'] . "-01" . "' AND date<'" . $d1['year'] . "-" . ($d1['mon'] + 1) . "-01" . "' AND commission='false'
                ORDER BY id;";

                $temp = $db->select($_sql);
                $_temp2 = $temp['data'];

                $_sql = "SELECT id, date, 'Payment - To ZAWebs' AS description, credit, debit FROM ad_transactions
                WHERE campaigns='" . $_POST['campaigns'] . "' AND debit IS NOT NULL AND date>='" . $d1['year'] . "-" . $d1['mon'] . "-01" . "' AND date<'" . $d1['year'] . "-" . ($d1['mon'] + 1) . "-01" . "' AND commission='true'
                ORDER BY id;";

                $temp = $db->select($_sql);
                $_temp3 = $temp['data'];

                $_reults['data'] = array_merge($_temp1, $_temp2, $_temp3);
                // $_reults['data'] = array_merge($_reults['data'], $_temp3);
                usort($_reults['data'], make_comparer('id'));

                array_unshift($_reults['data'], $_tempBal);
            }

            return $_reults;
        }
    }
}
