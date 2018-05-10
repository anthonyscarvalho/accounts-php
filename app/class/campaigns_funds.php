<?php

class campaigns_funds
{
    private $table = "ad_transactions";

    public function delete()
    {
        global $db;
        $_tmp = $db->select("SELECT * FROM ad_transactions WHERE id='" . $_POST['id'] . "';", 'true');
        if ($_tmp['data'] != "")
        {
            $res = $db->deleteData($this->table, 'id=' . $_tmp['data']['id']);

            $_vars = [
                "campaigns" => $_tmp['data']['campaigns'],
                "date" => current_dateTime(),
                "users" => $_SESSION['user'],
                "affected_table" => $this->table,
                "action" => "removed",
                "data" => json_encode($_tmp['data']),
            ];

            performAction('logs', 'updateLog', ['ad_logs', $_vars]);
            if ($res)
            {
                $_results['data'] = "true";
                $_results['message'] = "removed";
            }
            else
            {
                $_results['data'] = "false";
                $_results['message'] = $db->getError();
            }
        }
        else
        {
            $_results['data'] = 'false';
            $_results['message'] = 'no data';
        }

        return $_results;
    }

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            $vars = [
                "campaigns" => $_POST['campaigns'],
                "clients" => ((isset($_POST['clients'])) ? $_POST['clients'] : null),
                "date" => $_POST['date'],
                "credit" => ((isset($_POST['credit'])) ? $_POST['credit'] : 0.00),
                "debit" => ((isset($_POST['debit'])) ? $_POST['debit'] : 0.00),
                "comment" => ((isset($_POST['comment'])) ? $_POST['comment'] : null),
                "commission" => ((isset($_POST['commission'])) ? $_POST['commission'] : null),
            ];

            if ($_POST['view_type'] == "insert")
            {
                $res = $db->insertData($this->table, $vars);
                if ($res)
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'added';
                    $vars['id'] = $db->lastInsertId();
                    $_vars = [
                        "campaigns" => $_POST['campaigns'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => "inserted",
                        "data" => json_encode($vars),
                    ];

                    performAction('manager', 'updateLog', ['ad_logs', $_vars]);

                    if ($_POST['email'] == "true" && isset($_POST['clients']) && isset($_POST['credit']))
                    {
                        $contacts_query = "SELECT * FROM contacts WHERE clients='" . $_POST['clients'] . "' AND payment='true';";

                        $total_contacts = $db->numRows($contacts_query);

                        if ($total_contacts > 0)
                        {
                            $email = $db->select("SELECT * FROM template_emails WHERE id='3';", 'true');
                            $origin_body = $email['data']['body'];
                            $subject = $email['data']['subject'];

                            $body = str_replace('#amount#', number_format($_POST['credit'], 2, '.', ' '), $origin_body);
                            $body = str_replace('#date#', $_POST['date'], $body);
                            $emailer = new emailer();
                            $emailer->setEmailSubject( $subject );

                            $contacts = $db->select($contacts_query);
                            $_vars2 = [
                                "campaigns" => $_POST['campaigns'],
                                "users" => $_SESSION['user'],
                                "subject" => $subject,
                            ];
                            foreach ($contacts['data'] as $_contact)
                            {
                                $_body = $body;
                                $_body = str_replace( '#name#', $_contact['name'], $_body );
                                $_body = str_replace( '#surname#', $_contact['surname'], $_body );
                                $emailer->setEmailBody( $_body );

                                $emailer->setContact( $_contact['id'], $_contact['name'], $_contact['surname'], $_contact['email'] );

                               $_res = $emailer->sendMail('ads');
                            }
                        }
                    }
                }
                elseif (!$res)
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }
            }
            elseif ($_POST['view_type'] == "save")
            {
                $res = $db->update('ad_transactions', $vars, $_POST['id']);

                if ($res)
                {
                    $_vars = [
                        "campaigns" => $_POST['campaigns'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => "updated",
                        "data" => json_encode($vars),
                    ];

                    performAction('logs', 'updateLog', ['ad_logs', $_vars]);
                    $_results['data'] = 'true';
                    $_results['message'] = 'record updated';
                }
                elseif (!$res)
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }
            }

            return $_results;
        }
    }

    public function statement()
    {
        ob_start();
        global $db;
        //opening balance
        $temp = getdate(date("U", strtotime('now')));
        if ((isset($_POST['year'])) && (isset($_POST['month'])))
        {
            $startDate = $_POST['year'] . '-' . $_POST['month'] . '-01';
            $endDate = $_POST['year'] . '-' . ($_POST['month'] + 1) . '-01';
        }
        elseif ((!isset($_POST['year'])) && (isset($_POST['month'])))
        {
            $startDate = $temp['year'] . '-' . $_POST['month'] . '-01';
            $endDate = $temp['year'] . '-' . ($_POST['month'] + 1) . '-01';
        }
        elseif ((isset($_POST['year'])) && (!isset($_POST['month'])))
        {
            $startDate = $_POST['year'] . '-03-01';
            $endDate = ($_POST['year'] + 1) . '-03-01';
        }
        elseif ((!isset($_POST['year'])) && (!isset($_POST['month'])))
        {
            $startDate = $temp['year'] . '-03-01';
            $endDate = ($temp['year'] + 1) . '-03-01';
        }
// get any previous credit

        $_openCredit = $db->select("SELECT (SUM(credit) - Sum(debit)) AS total FROM ad_transactions WHERE campaigns='" . $_POST['campaigns'] . "' AND date<'" . $startDate . "';", 'true');

        if ($_openCredit['data']['total'] > 0)
        {
            $_tempBal = ['id' => '0', 'date' => $startDate, 'business' => '', 'description' => 'Opening Balance', 'credit' => $_openCredit['data']['total'], 'debit' => 0.00];
        }
        elseif ($_openCredit['data']['total'] < 0)
        {
            $_tempBal = ['id' => '0', 'date' => $startDate, 'business' => '', 'description' => 'Opening Balance', 'credit' => 0.00, 'debit' => -($_openCredit['data']['total'])];
        }
        else
        {
            $_tempBal = ['id' => '0', 'date' => $startDate, 'business' => '', 'description' => 'Opening Balance', 'credit' => 0.00, 'debit' => 0.00];
        }
        //credits
        $_sql = "SELECT id, date, (SELECT business FROM clients WHERE id=ad_transactions.clients) AS business, 'Payment Received - Thank You' AS description, credit, debit
        FROM ad_transactions
        WHERE credit>0 AND campaigns='" . $_POST['campaigns'] . "' AND (date>='" . $startDate . "'  AND date<='" . $endDate . "')
        ORDER BY date ASC;";

        $credits = $db->select($_sql);
        //debits
        $_sql = "SELECT id, date, (SELECT business FROM clients WHERE id=ad_transactions.clients) AS business, 'Payment To Google' AS description, credit, debit
        FROM ad_transactions
        WHERE debit>0 AND campaigns='" . $_POST['campaigns'] . "' AND (date>='" . $startDate . "'  AND date<='" . $endDate . "') AND commission='false'
        ORDER BY date ASC;";
        $debits = $db->select($_sql);
        //commission
        $_sql = "SELECT id, date, (SELECT business FROM clients WHERE id=ad_transactions.clients) AS business, 'Google Adwords Management Fee' AS description, credit, debit
        FROM ad_transactions
        WHERE debit>0 AND campaigns='" . $_POST['campaigns'] . "' AND (date>='" . $startDate . "'  AND date<='" . $endDate . "') AND commission='true'
        ORDER BY date ASC;";
        $comm = $db->select($_sql);

        //combine all data together
        $statements = array_merge($credits['data'], $debits['data'], $comm['data']);

        usort($statements, make_comparer('id'));
        array_unshift($statements, $_tempBal);
        $_totalStatments = count($statements);

// get data needed for the templates
        $_campaign = $db->select("SELECT * FROM ad_campaigns WHERE id='" . $_POST['campaigns'] . "';", 'true');
        $_company = $db->select("SELECT * FROM companies WHERE id='1';", 'true');
// get the various templates
        $_statementTemplate = $db->select("SELECT * FROM template_attachments WHERE id='5';", 'true');
// set the name of the pdf
        $_pdfData['pdfName'] = $_client['data']['id'] . '-' . $_client['data']['business'] . '-Statement for:' . $_POST['startDate'] . '_' . $_POST['endDate'] . '.pdf';
// start replacing data in the template
        $template = $_statementTemplate['data']['template'];

        $template = str_replace('#invoice_header#', $_company['data']['invoice_header'], $template);

        $template = str_replace('#campaign#', $_campaign['data']['name'], $template);
        $template = str_replace('#year#', $_POST['year'], $template);
        $template = str_replace('#month#', $_POST['month'], $template);

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
            foreach ($statements as $_statement)
            {
                if ($_statement['credit'] > 0)
                {
                    $total += $_statement['credit'];
                    $_credit += $_statement['credit'];
                }
                elseif ($_statement['credit'] == 0)
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
        // print_r($_pdfData);

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

        // return $_results;
    }

    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            if ($_POST['view_type'] == "view")
            {
                $temp = getdate(date("U", strtotime('now')));
                if ((isset($_POST['year'])) && (isset($_POST['month'])))
                {
                    $startDate = $_POST['year'] . '-' . $_POST['month'] . '-01';
                    $endDate = $_POST['year'] . '-' . ($_POST['month'] + 1) . '-01';
                }
                elseif ((!isset($_POST['year'])) && (isset($_POST['month'])))
                {
                    $startDate = $temp['year'] . '-' . $_POST['month'] . '-01';
                    $endDate = $temp['year'] . '-' . ($_POST['month'] + 1) . '-01';
                }
                elseif ((isset($_POST['year'])) && (!isset($_POST['month'])))
                {
                    $startDate = $_POST['year'] . '-03-01';
                    $endDate = ($_POST['year'] + 1) . '-03-01';
                }
                elseif ((!isset($_POST['year'])) && (!isset($_POST['month'])))
                {
                    $startDate = $temp['year'] . '-03-01';
                    $endDate = ($temp['year'] + 1) . '-03-01';
                }
// get any previous credit

                $_openCredit = $db->select("SELECT (SUM(credit) - Sum(debit)) AS total FROM ad_transactions WHERE campaigns='" . $_POST['campaigns'] . "' AND date<'" . $startDate . "';", 'true');

                if ($_openCredit['data']['total'] > 0)
                {
                    $_tempBal = ['id' => '0', 'clientName' => '', 'date' => $startDate, 'credit' => $_openCredit['data']['total'], 'debit' => 0.00, 'comment' => 'Opening Balance'];
                }
                elseif ($_openCredit['data']['total'] < 0)
                {
                    $_tempBal = ['id' => '0', 'clientName' => '', 'date' => $startDate, 'credit' => 0.00, 'debit' => -($_openCredit['data']['total']), 'comment' => 'Opening Balance'];
                }
                else
                {
                    $_tempBal = ['id' => '0', 'clientName' => '', 'date' => $startDate, 'credit' => 0.00, 'debit' => 0.00, 'comment' => 'Opening Balance'];
                }
                $_sql = "SELECT *, (SELECT business FROM clients WHERE id=ad_transactions.clients) AS business FROM ad_transactions WHERE campaigns='" . $_POST['campaigns'] . "' AND date>='" . $startDate . "'  AND date<'" . $endDate . "' ORDER BY date ASC;";

                $_results = $db->select($_sql);

                array_unshift($_results['data'], $_tempBal);
            }
            elseif ($_POST['view_type'] == "edit")
            {
                $_results = $db->select("SELECT * FROM ad_transactions WHERE id='" . $_POST['id'] . "';", 'true');
            }

            return $_results;
        }
    }
}
