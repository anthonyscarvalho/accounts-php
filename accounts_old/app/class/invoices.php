
<?php

    class invoices
    {
        private $table = "invoices";

        public function create()
        {
            global $db;
            $_d2 = getdate(date(strtotime($_POST['prodDate'])));

            // $_res = performAction('products', "retrieve", [$_POST['prodDate'], $_POST['clients'], $_POST['company']]);

            $products = $_POST['products'];
            if (count($products) > 0)
            {
                $_d1 = getdate(date(strtotime($_POST['prodDate'])));

                if ($_POST['invoiceType'] == "due")
                {
                    $_dueDate = $_d1['year'] . "-" . $_d1['mon'] . "-01";
                }
                elseif ($_POST['invoiceType'] == "now")
                {
                    $_dueDate = current_date();
                }
                $_invTotal = 0;
                foreach ($products as $prod)
                {
                    $_invTotal += $prod['price'];
                }
                $current_date = current_date();
                $vars = [
                    "clients" => $_POST['clients'],
                    "companies" => $_POST['company'],
                    "creation_date" => $current_date,
                    "canceled_date" => null,
                    "due_date" => $_dueDate,
                    "paid_date" => null,
                    "invoice_total" => $_invTotal,
                    "notes" => null,
                    "paid" => 'false',
                    "canceled" => 'false',
                ];

                $res = $db->insertData('invoices', $vars);
                if (!$res)
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }
                elseif ($res)
                {
                    $_invoiceId = $db->lastInsertId();
                    $_ins = true;
                    foreach ($products as $item)
                    {
                        $_renewable = $item['renewable'];
                        $_prodDate = getdate(date(strtotime($item['date'])));
                        $_prodID = $item['id'];

                        $item['invoices'] = $_invoiceId;
                        $item['products'] = $item['id'];

                        if ($_renewable == 'm')
                        {
                            $_tmpDate = $_d1['year'] . "-" . $_d1['mon'] . "-" . $_prodDate['mday'];
                        }
                        elseif ($_renewable == 'o')
                        {
                            $_tmpDate = $_d1['year'] . "-" . $_prodDate['mon'] . "-" . $_prodDate['mday'];
                        }
                        elseif ($_renewable == 'r')
                        {
                            $_tmpDate = $_d1['year'] . "-" . $_prodDate['mon'] . "-" . $_prodDate['mday'];
                        }
                        elseif ($_renewable == 'a')
                        {
                            $_tmpDate = $_d1['year'] . "-" . $_prodDate['mon'] . "-" . $_prodDate['mday'];
                        }
                        else
                        {
                            $_tmpDate = $_POST['prodDate'];
                        }

                        $item['date'] = $_tmpDate;

                        unset($item['id']);
                        unset($item['canceled']);
                        unset($item['canceled_date']);
                        unset($item['clientName']);
                        unset($item['categoryName']);
                        unset($item['clients']);
                        unset($item['companies']);
                        unset($item['lastInvoice']);
                        unset($item['month']);
                        unset($item['period']);
                        unset($item['renewable']);
                        unset($item['year']);

                        $res2 = $db->insertData('invoices_items', $item);
                        if ($res2)
                        {
                            $_ins = true;
                            if (($_renewable == "r") || ($_renewable == "o"))
                            {
                                $vars = [
                                    "canceled" => 'true',
                                    "canceled_date" => current_date(),
                                ];

                                $res = $db->update('products', $vars, $_prodID);
                            }
                        }
                        else
                        {
                            $_ins = false;
                        }
                    }
                    if ($_ins)
                    {
                        $_results['data'] = 'true';
                        $_results['message'] = 'inserted';
                        $_vars = [
                            "clients" => $_POST['clients'],
                            "date" => current_dateTime(),
                            "users" => $_SESSION['user'],
                            "affected_table" => $this->table,
                            "action" => "created",
                            "data" => json_encode($vars),
                        ];
                        // performAction('manager', 'updateLog', ['logs', $_vars]);
                        if ($_POST['sendMail'] == 'true')
                        {
                            $_POST['view_type'] = 'view';
                            $_POST['display'] = 'email';
                            $_POST['invoice'] = $_invoiceId;
                            $res = performAction('invoices', 'printInvoice', []);
                            $_results['message'] .= '<br>' . $res['message'];
                        }
                    }
                    else
                    {
                        $_results['data'] = 'false';
                        $_results['message'] = 'invoice items not inserted';
                    }
                }
            }
            else
            {
                $_results['data'] = "false";
                $_results['message'] = "no products to add";
            }

            return $_results;
        }

        public function credit()
        {
            global $db;
            $_invoice = $db->select("SELECT * FROM invoices WHERE id='" . $_POST['id'] . "';", 'true');
            if ($_invoice['data']['paid'] == 'false')
            {
                $_sql = "SELECT * FROM transactions WHERE clients='" . $_invoice['data']['clients'] . "' ORDER BY date;";
                $total_trans = $db->numRows($_sql);
                $_paidDate = "";
                $_paidTrans = 0;
                $_credit = 0;
                if ($total_trans > 0)
                {
                    $temp_trans = $db->select($_sql);

                    foreach ($temp_trans['data'] as $transaction)
                    {
                        if ($transaction['invoices'] == $_POST['id'])
                        {
                            $_paidTrans += $transaction['debit'];
                        }

                        if (!empty($transaction['credit']))
                        {
                            $_credit += $transaction['credit'];
                            $_paidDate = $transaction['date'];
                        }
                        elseif (empty($transaction['credit']) && !empty($transaction['debit']))
                        {
                            $_credit -= $transaction['debit'];
                        }
                    }
                }
                if ($_paidDate == "")
                {
                    $_paidDate = current_date();
                }

                $_totalDue = ($_invoice['data']['invoice_total'] - $_paidTrans);
                $_totalDue = number_format($_totalDue, 2, '.', '');
                $_credit = number_format($_credit, 2, '.', '');

                if (($_credit > 0) && ($_invoice['data']['invoice_total'] > 0))
                {
                    if ($_totalDue > $_credit)
                    {
                        $_paid = number_format($_credit, 2, '.', '');
                        $_invPaid = "false";
                    }
                    elseif ($_totalDue < $_credit)
                    {
                        $_paid = number_format($_totalDue, 2, '.', '');
                        $_invPaid = "true";
                    }
                    elseif ($_totalDue == $_credit)
                    {
                        $_paid = number_format($_totalDue, 2, '.', '');
                        $_invPaid = "true";
                    }

                    if ($_invPaid == "true")
                    {
                        $vars = [
                            "paid" => 'true',
                            "paid_date" => $_paidDate,
                        ];

                        $res = $db->update('invoices', $vars, $_POST['id']);
                    }
                    elseif ($_invPaid == "false")
                    {
                        $res = "true";
                    }

                    if ($res)
                    {
                        $vars = [
                            "clients" => $_invoice['data']['clients'],
                            "date" => $_paidDate,
                            "description" => "Invoice #" . $_POST['id'] . " - Credit",
                            "credit" => null,
                            "debit" => $_paid,
                            "invoices" => $_POST['id'],
                        ];

                        $res2 = $db->insertData('transactions', $vars);
                        if ($res2)
                        {
                            $_results['data'] = 'true';
                            $_results['message'] = "transaction added";
                        }
                        else
                        {
                            $_results['data'] = 'false';
                            $_results['message'] = $db->getError();
                        }
                    }
                }
                elseif ($_invoice['data']['invoice_total'] == 0)
                {
                    $vars = [
                        "paid" => 'true',
                        "paid_date" => current_date(),
                    ];

                    $res = $db->update('invoices', $vars, $_POST['id']);
                    if ($res)
                    {
                        $_results['data'] = 'true';
                        $_results['message'] = "invoice paid";
                    }
                    else
                    {
                        $_results['data'] = 'false';
                        $_results['message'] = $db->getError();
                    }
                }
                else
                {
                    $_results['data'] = 'false';
                    $_results['message'] = 'no funds available';
                }
            }
            else
            {
                $_results['data'] = 'false';
                $_results['message'] = 'already paid';
            }

            return $_results;
        }

        public function creditUpdate()
        {
            global $db;
            $_credit = $db->select("SELECT Sum(debit) AS total FROM transactions WHERE invoices='" . $_POST['invoice'] . "';", 'true');

            if ($_credit['data']['total'] > 0)
            {
                $_invoice = $db->select("SELECT * FROM invoices WHERE id='" . $_POST['invoice'] . "';", 'true');
                if ($_credit['data']['total'] < $_invoice['data']['invoice_total'])
                {
                    $vars = [
                        "paid" => 'false',
                        "paid_date" => null,
                    ];
                    $res = $db->update($this->table, $vars, $_POST['invoice']);
                }
                elseif ($_credit['data']['total'] > $_invoice['data']['invoice_total'])
                {
                    $vars = [
                        "paid" => 'false',
                        "paid_date" => null,
                    ];
                    $res = $db->update($this->table, $vars, $_POST['invoice']);
                    $_transsql = "SELECT * FROM transactions WHERE invoices='" . $_POST['invoice'] . "' ORDER BY date DESC;";
                    $_totTrans = $db->numRows($_transsql);
                    if ($_totTrans > 1)
                    {
                        $trans = $db->select("SELECT * FROM transactions WHERE invoices='" . $_POST['invoice'] . "' ORDER BY date DESC LIMIT 1;", 'true');
                        $res = $db->deleteData('transactions', 'id=' . $trans['data']['id']);
                    }
                    else
                    {
                        $trans = $db->select($_transsql, 'true');
                        $res = $db->deleteData('transactions', 'id=' . $trans['data']['id']);
                    }
                }
                else
                {
                    $res = true;
                }
            }
            else
            {
                $vars = [
                    "paid" => 'false',
                    "paid_date" => null,
                ];
                $res = $db->update($this->table, $vars, $_POST['invoice']);
            }

            if ($res)
            {
                $_results['data'] = "true";
                $_results['message'] = "updated";
            }
            elseif (!$res)
            {
                $_results['data'] = "false";
                $_results['message'] = $db->getError();
            }

            return $_results;
        }

        public function delete($id = null, $client = null)
        {
            global $db;
            $res = $db->deleteData('transactions', 'invoices=' . $id);
            if ($res)
            {
                $res = $db->deleteData('invoices_items', 'invoices=' . $id);
                if ($res)
                {
                    $res = $db->deleteData('invoices_emails', 'invoice=' . $id);
                    if ($res)
                    {
                        $res = $db->deleteData('invoices', 'id=' . $id);
                        if ($res)
                        {
                            $_results['data'] = "true";
                            $_results['message'] = "invoice removed";
                            // performAction('manager', 'updateLog', [$client, $_SESSION['user'], 'deleted', 'invoices', ["id" => $id]]);
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
                        $_results['message'] = 'transactions and items removed';
                    }
                }
                else
                {
                    $_results['data'] = "false";
                    $_results['message'] = 'transactions removed';
                }
            }
            else
            {
                $_results['data'] = "false";
                $_results['message'] = $db->getError();
            }

            return $_results;
        }

        public function edit($id = null)
        {
            global $db;
            $_sql = "SELECT invoices.*, companies.company AS companyName "
                . "FROM companies RIGHT JOIN invoices ON companies.id=invoices.companies "
                . "WHERE invoices.id='" . $id . "';";
            $_results = $db->select($_sql, 'true');

            $_sql = "SELECT business "
                . "FROM clients "
                . "WHERE id='" . $_results['data']['clients'] . "';";
            $res = $db->select($_sql, 'true');
            $_results['clientName'] = $res['data']['business'];

            return $_results;
        }

        public function printInvoice()
        {
            if (isset($_POST['view_type']))
            {
                ob_start();
                global $db;
                $_invoice = $db->select("SELECT * FROM invoices WHERE id='" . $_POST['invoice'] . "';", 'true');
                $_client = $db->select("SELECT * FROM clients WHERE id='" . $_invoice['data']['clients'] . "';", 'true');
                $_invItems = $db->select("SELECT invoices_items.date, categories.category, invoices_items.description, invoices_items.price FROM categories RIGHT JOIN invoices_items ON categories.id=invoices_items.categories WHERE invoices_items.invoices='" . $_POST['invoice'] . "' ORDER BY categories.category;");
                $_transactions = $db->select("SELECT * FROM transactions WHERE invoices='" . $_POST['invoice'] . "' ORDER BY date;");
                $_company = $db->select("SELECT * FROM companies WHERE id='" . $_invoice['data']['companies'] . "';", 'true');
                $_template = $db->select("SELECT * FROM template_attachments WHERE id='1';", 'true');
                $_termsOfService = $db->select("SELECT * FROM template_attachments WHERE id='2';", 'true');

                $_pdfData['pdfName'] = $_client['data']['id'] . '-' . $_client['data']['business'] . '-' . $_invoice['data']['id'] . '.pdf';

                // start pdf layout
                $template = $_template['data']['template'];
                $template = str_replace('#invoice_header#', $_company['data']['invoice_header'], $template);
                $template = str_replace('#invoice_id#', $_invoice['data']['id'], $template);
                $template = str_replace('#creation_date#', $_invoice['data']['creation_date'], $template);
                $template = str_replace('#due_date#', $_invoice['data']['due_date'], $template);
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

                $template = str_replace('#client_id#', $_client['data']['id'], $template);
                $template = str_replace('#account_details#', $_company['data']['account_details'], $template);

                if ($_invoice['data']['paid'] == "true" && $_invoice['data']['canceled'] == "false")
                {
                    $_pdfData['overlay'] = 'true';
                    $_pdfData['overlayFill'][0] = 151;
                    $_pdfData['overlayFill'][1] = 223;
                    $_pdfData['overlayFill'][2] = 74;
                    $_pdfData['overlayDraw'][0] = 110;
                    $_pdfData['overlayDraw'][1] = 129;
                    $_pdfData['overlayDraw'][2] = 70;
                    $_pdfData['overlayText'] = 'paid';
                }
                elseif ($_invoice['data']['paid'] == "false" && $_invoice['data']['canceled'] == "false")
                {
                    $_pdfData['overlay'] = 'true';
                    $_pdfData['overlayFill'][0] = 249;
                    $_pdfData['overlayFill'][1] = 59;
                    $_pdfData['overlayFill'][2] = 59;
                    $_pdfData['overlayDraw'][0] = 127;
                    $_pdfData['overlayDraw'][1] = 0;
                    $_pdfData['overlayDraw'][2] = 0;
                    $_pdfData['overlayText'] = 'unpaid';
                }
                elseif ($_invoice['data']['canceled'] == "true")
                {
                    $_pdfData['overlay'] = 'true';
                    $_pdfData['overlayFill'][0] = 200;
                    $_pdfData['overlayFill'][1] = 0;
                    $_pdfData['overlayFill'][2] = 0;
                    $_pdfData['overlayDraw'][0] = 140;
                    $_pdfData['overlayDraw'][1] = 0;
                    $_pdfData['overlayDraw'][2] = 0;
                    $_pdfData['overlayText'] = 'canceled';
                }
                else
                {
                    $_pdfData['overlay'] = 'false';
                }
                # Transaction Items
                $trans_html = '<table width="100 % " bgcolor="#ccc" cellspacing="1" cellpadding="2" border="0" style="font-size:9pt;">'
                    . '<thead>'
                    . '<tr height="30" style="font-weight:bold; text-align:center; background-color:#efefef;">'
                    . '<td style="width:60px;">Date</td>'
                    . '<td style="width:380px;">Description</td>'
                    . '<td style="width:100px;">Amount</td>'
                    . '</tr>'
                    . '</thead>';

                $trans_total = 0;
                foreach ($_transactions['data'] as $trans)
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
                    . '<td>' . $items['category'] . '</td>'
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

                $template = str_replace('#invoice_items#', $items_html, $template);

                $template = str_replace('#invoice_transactions#', $trans_html, $template);

                # pages
                $_pdfData['page'][0] = $template;

                if ($_invoice['data']['notes'] != '')
                {
                    $_pdfData['page'][1] = '<p style="font-size:10pt;"><strong>Invoice Notes</strong></p><span style="font-size:9pt;">' . $_invoice['data']['notes'] . '</span><br>' . $_termsOfService['data']['template'];
                }
                else
                {
                    $_pdfData['page'][1] = $_termsOfService['data']['template'];
                }

                if ($_POST['display'] == 'print')
                {
                    createPDF('print', $_pdfData);
                }
                elseif ($_POST['display'] == 'email')
                {
                    // set the email body and subject up
                    if ((!empty($_POST['emailbody'])) && (!empty($_POST['emailsubject'])))
                    {
                        $origin_body = $_POST['emailbody'];
                        $subject = $_POST['emailsubject'];
                        $_user = $_SESSION['user'];

                        // print the pdf for email use
                        $_invoicePDF = createPDF('email', $_pdfData);
                        // tally total contacts
                        $_contactSql = "SELECT * FROM contacts WHERE clients='" . $_client['data']['id'] . "' AND canceled='false' AND invoice='true';";
                        $_totContacts = $db->numRows($_contactSql);
                        $_vars = [
                            "users" => $_SESSION['user'],
                            "subject" => $_POST['emailsubject'],
                            "invoices" => $_POST['invoice'],
                        ];
                        if ($_totContacts > 0)
                        {
                            // get all contacts for client
                            $_contacts = $db->select($_contactSql);
                            $_results['message'] = '';
                            $sent = false;
                            foreach ($_contacts['data'] as $_contact)
                            {
                                $body = $origin_body;
                                $body = str_replace('#name#', $_contact['name'], $body);
                                $body = str_replace('#surname#', $_contact['surname'], $body);

                                $res = sendEmail($_contact, $subject, $body, $_pdfData['pdfName'], $_invoicePDF);
                                $_vars['contacts'] = $_contact['id'];
                                $_vars['status'] = $res['server_log'];
                                $_vars['date'] = current_dateTime();
                                $_vars['body'] = $body;
                                performAction('manager', 'updateLog', ['email_log', $_vars]);

                                if ($res['status'])
                                {
                                    $sent = true;
                                    $_results['message'] .= 'sent: ' . $_contact['name'] . "<br>";
                                }
                                else
                                {
                                    $_results['message'] .= 'not sent: ' . $_contact['name'] . "<br>";
                                }
                            }
                            if ($sent)
                            {
                                $_results['data'] = "true";
                                $db->update("INSERT INTO invoices_emails (invoice, email_type, date) VALUES ('" . $_POST['invoice'] . "', '" . $_POST['email_type'] . "', '" . current_dateTime() . "');");
                            }
                        }
                        else
                        {
                            $_results['data'] = 'false';
                            $_results['message'] = 'no contacts';
                        }
                    }
                    else
                    {
                        $_results['data'] = 'false';
                        $_results['message'] = 'no data passed';
                    }
                }

                return $_results;
            }
        }

        public function save()
        {
            if (isset($_POST['view_type']))
            {
                global $db;
                $vars = [
                    "creation_date" => $_POST['creation_date'],
                    "due_date" => $_POST['due_date'],
                    "notes" => $_POST['notes'],
                ];
                if ($_POST['view_type'] == 'save')
                {
                    $res = $db->update('invoices', $vars, $_POST['id']);
                    if ($res)
                    {
                        $_results['data'] = 'true';
                        $_results['message'] = 'record updated';
                        $_vars = [
                            "clients" => $_POST['clients'],
                            "date" => current_dateTime(),
                            "users" => $_SESSION['user'],
                            "affected_table" => $this->table,
                            "action" => 'updated',
                            "data" => json_encode($vars),
                        ];
                        performAction('manager', 'updateLog', ['logs', $_vars]);
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

        public function update($state = null, $id = null)
        {
            if ($state != null)
            {
                if ($state == 'cancel' || $state == 'enable')
                {
                    global $db;
                    if ($state == 'cancel')
                    {
                        $_sql = "UPDATE invoices SET canceled='true', canceled_date='" . current_date() . "' WHERE id='" . $id . "';";
                    }
                    elseif ($state == 'enable')
                    {
                        $_sql = "UPDATE invoices SET canceled='false', canceled_date=null WHERE id='" . $id . "';";
                    }
                    $res = $db->update($_sql);
                    if ($res)
                    {
                        $_results['data'] = "true";
                        $_results['message'] = "state updated";

                        $_vars = [
                            "clients" => $id,
                            "date" => current_dateTime(),
                            "users" => $_SESSION['user'],
                            "affected_table" => $this->table,
                            "action" => $state,
                            "data" => json_encode(["id" => $id]),
                        ];
                        performAction('manager', 'updateLog', ['logs', $_vars]);
                    }
                    else
                    {
                        $_results['data'] = "false";
                        $_results['message'] = $db->getError();
                    }

                    return $_results;
                }
            }
        }

        public function view($state = null, $paid = null)
        {
            if (isset($_POST['view_type']))
            {
                global $db;
                if ($_POST['view_type'] == 'view')
                {
                    $_sql = "SELECT clients.business AS clientName, invoices.*, (SELECT date FROM invoices_emails WHERE invoice=invoices.id ORDER BY date DESC LIMIT 1) AS lastInvoice, (SELECT COUNT(id) FROM invoices_items WHERE invoices=invoices.id) AS totalItems "
                        . "FROM clients, invoices "
                        . "WHERE clients.id = invoices.clients ";

                    if ($_POST['state'] == 'true' || $_POST['state'] == 'false')
                    {
                        $_sql .= "AND invoices.canceled='" . $_POST['state'] . "' ";
                    }
                    if ($_POST['paid'] == 'true' || $_POST['paid'] == 'false')
                    {
                        $_sql .= "AND invoices.paid='" . $_POST['paid'] . "' ";
                    }
                    $_results = $db->select($_sql);
                }
                elseif ($_POST['view_type'] == 'search')
                {
                    $_sql = "SELECT companies.company AS companyName, invoices.*, (SELECT date FROM invoices_emails WHERE invoice=invoices.id ORDER BY date DESC LIMIT 1) AS lastInvoice "
                        . "FROM clients RIGHT JOIN (companies RIGHT JOIN invoices ON companies.id=invoices.companies) ON clients.id=invoices.clients "
                        . "WHERE invoices.clients='" . $_POST['clients'] . "'";

                    if ($_POST['state'] == 'true')
                    {
                        $_sql .= " AND invoices.canceled='true';";
                    }
                    elseif ($_POST['state'] == 'false')
                    {
                        $_sql .= " AND invoices.canceled='false';";
                    }

                    $_totRows = $db->numRows($_sql);
                    if ($_totRows > 0)
                    {
                        $_results = $db->select($_sql);
                    }
                    else
                    {
                        $_resutls['data'] = '';
                    }

                    $res = $db->select("SELECT * FROM clients WHERE id='" . $_POST['clients'] . "';", 'true');
                    $_results['business'] = $res['data']['business'];
                }
                elseif ($_POST['view_type'] == 'edit')
                {
                    $_sql = "SELECT invoices.*, companies.company AS companyName "
                        . "FROM companies RIGHT JOIN invoices ON companies.id=invoices.companies "
                        . "WHERE invoices.id='" . $_POST['id'] . "';";
                    $_results = $db->select($_sql, 'true');

                    $_sql = "SELECT business "
                        . "FROM clients "
                        . "WHERE id='" . $_results['data']['clients'] . "';";
                    $res = $db->select($_sql, 'true');
                    $_results['clientName'] = $res['data']['business'];
                }

                return $_results;
            }
        }
};
