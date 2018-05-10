<?php
class quotations
{
    private $table = "quotations";

    function print($_id = null, $_email = null)
    {
        if (!empty($_id) && is_numeric($_id))
        {
            global $db;

            $_quotation = $db->select("SELECT * FROM quotations WHERE id='" . $_id . "';", 'true');
            $_client = $db->select("SELECT * FROM clients WHERE id='" . $_quotation['data']['clients'] . "';", 'true');

            $_company = $db->select("SELECT * FROM companies WHERE id='" . $_quotation['data']['companies'] . "';", 'true');

            $_pdfData['pdfName'] = 'Quotation-' . $_client['data']['id'] . '-' . $_client['data']['business'] . '-' . $_quotation['data']['id'] . '.pdf';

            // start pdf layout
            $template = $_company['data']['invoice_header'];
            $template .= '<p><strong>Company:</strong>&nbsp;&nbsp;' . $_client['data']['business'] . '</p>';
            $template .= '<p><strong>Date:</strong>&nbsp;&nbsp;' . current_date() . '</p>';
            $template .= $_quotation['data']['scope'];

            $_pdfData['overlay'] = 'false';

            # Products
            $_tableHead = '<table width="100%" cellspacing="1" cellpadding="2" border="0" style="background-color:#ccc; font-size:10px;">'
                . '<tr height="30" style="font-weight:bold; text-align:center; background-color:#efefef;">'
                . '<td style="width:60px;">Date</td>'
                . '<td style="width:400px;">Item</td>'
                . '<td style="width:80px;">Price</td>'
                . '</tr>';
            $_a_items_html = $_tableHead;
            $_m_items_html = $_tableHead;
            $_o_items_html = $_tableHead;

            $o_subtotal = 0;
            $m_subtotal = 0;
            $a_subtotal = 0;

            $o_count = 0;
            $m_count = 0;
            $a_count = 0;

            $_products = json_decode($_quotation['data']['products'], true);

            foreach ($_products as $items)
            {
                if ($items['renewable'] == 'o' || $items['renewable'] == 'r')
                {
                    $_o_items_html .= '<tr style="background-color:#fff;">'
                    . '<td>' . $items['date'] . '</td>'
                    . '<td>' . $items['categoryName'] . '</td>'
                    . '<td>R ' . number_format($items['price'], 2, '.', ' ') . '</td>'
                        . '</tr>';

                    if ((isset($items['description'])) && ($items['description'] != ""))
                    {
                        $_o_items_html .= '<tr style="background-color:#fff;"><td></td><td colspan="2">' . $items['description'] . '</td></tr>';
                    }

                    $o_subtotal += $items['price'];
                    $o_count++;
                }

                if ($items['renewable'] == 'a')
                {
                    $_a_items_html .= '<tr style="background-color:#fff;">'
                    . '<td>' . $items['date'] . '</td>'
                    . '<td>' . $items['categoryName'] . '</td>'
                    . '<td>R ' . number_format($items['price'], 2, '.', ' ') . '</td>'
                        . '</tr>';

                    if ($items['description'] != "")
                    {
                        $_a_items_html .= '<tr style="background-color:#fff;"><td></td><td colspan="2">' . $items['description'] . '</td></tr>';
                    }

                    $a_subtotal += $items['price'];
                    $a_count++;
                }

                if ($items['renewable'] == 'm')
                {
                    $_m_items_html .= '<tr style="background-color:#fff;">'
                    . '<td>' . $items['date'] . '</td>'
                    . '<td>' . $items['categoryName'] . '</td>'
                    . '<td>R ' . number_format($items['price'], 2, '.', ' ') . '</td>'
                        . '</tr>';

                    if ($items['description'] != "")
                    {
                        $_m_items_html .= '<tr style="background-color:#fff;"><td></td><td colspan="2">' . $items['description'] . '</td></tr>';
                    }

                    $m_subtotal += $items['price'];
                    $m_count++;
                }
            }

            $items_html = '';

            if ($a_count > 0)
            {
                $items_html .= '<h2>Annual Fees</h2>' . $_a_items_html . '</table>';
            }

            if ($m_count > 0)
            {
                $items_html .= '<h2>Monthly Fees</h2>' . $_m_items_html . '</table>';
            }

            if ($o_count > 0)
            {
                $items_html .= '<h2>Once Off Fees</h2>' . $_o_items_html . '</table>';
            }

            # Set up the total table

            $items_html .= '<h2>Total Costs</h2><table width="100%" cellspacing="1" cellpadding="2" border="0" style="background-color:#ccc; font-size:10px;">';

            if (!empty($_quotation['data']['deposit']))
            {
                $items_html .= '<tr height="30" style="background-color: #efefef;">'
                . '<td style="text-align:right; width:460px;">Deposit Required</td>'
                . '<td style="width:80px;">R ' . number_format($_quotation['data']['deposit'], 2, '.', ' ') . '</td>'
                    . '</tr>';
            }

            $items_html .= '<tr height="30" style="background-color: #010180; color:#fff;">'
            . '<td style="text-align:right; width:460px;">Proposal Total</td>'
            . '<td style="width:80px;">R ' . number_format(($a_subtotal + $m_subtotal + $o_subtotal), 2, '.', ' ') . '</td>'
                . '</tr>';

            $items_html .= '</table>';

            # pages
            $count = 0;
            $_pdfData['page'][$count] = $template;
            $count++;
            $_pdfData['page'][$count] = $items_html . $_quotation['data']['signature'];
            $count++;

            if ($_quotation['data']['content'] != '')
            {
                $_pdfData['page'][$count] = $_quotation['data']['content'];
                $count++;
            }

            if ($_quotation['data']['annexure'] != '')
            {
                $_pdfData['page'][$count] = $_quotation['data']['annexure'];
                $count++;
            }

            if (empty($_email))
            {
                createPDF('print', $_pdfData);
            }
            elseif ((isset($_email)) && ($_email == 'email'))
            {
                // set the email body and subject u
                return createPDF('email', $_pdfData);
                exit();
            }

            $_results['nodata'] = 'true';

            return $_results;
        }
    }

    public function edit($id = null)
    {
        global $db;

        if (!empty($id))
        {
            $_sql = "SELECT * FROM " . $this->table . " WHERE link='" . $id . "';";
            $_total = $db->numRows("SELECT * FROM " . $this->table . " WHERE link='" . $id . "';");

            if ($_total > 0)
            {
                $_results = $db->select($_sql, 'true');
            }
            else
            {
                $_results['data'] = 'false';
                $_results['message'] = 'record does not exist';
            }
        }
        else
        {
            $_results['data'] = 'false';
            $_results['message'] = 'no data passed';
        }

        return $_results;
    }

    public function email_quote()
    {
        if (isset($_POST['view_type']))
        {
            if ((!empty($_POST['emailbody'])) && (!empty($_POST['emailsubject'])))
            {
                global $db;
                $_quote = $db->select("SELECT clients, link FROM quotations WHERE id='" . $_POST['quote'] . "';", 'true');
                $_client = $db->select("SELECT * FROM clients WHERE id='" . $_quote['data']['clients'] . "';", 'true');

                $origin_body = $_POST['emailbody'];
                $subject = $_POST['emailsubject'];
                $_user = $_SESSION['user'];

                // print the pdf for email use
                $_quotePDF = performAction('quotations', 'print', [$_POST['quote'], 'email']);
                $_pdfName = 'Quotation-' . $_client['data']['id'] . '-' . $_client['data']['business'] . '-' . $_POST['quote'] . '.pdf';

                // tally total contacts
                $_contactSql = "SELECT * FROM contacts WHERE clients='" . $_client['data']['id'] . "' AND canceled='false' AND quotes='true';";

                $_totContacts = $db->numRows($_contactSql);

                $_vars = [
                    "users" => $_SESSION['user'],
                    "subject" => $_POST['emailsubject'],
                    "quotes" => $_POST['quote']
                ];

                if ($_totContacts > 0)
                {
                    $_contacts = $db->select($_contactSql); // get all contacts for client

                    $_results['message'] = '';

                    $sent = false;
                    $_link = '';

                    foreach ($_contacts['data'] as $_contact)
                    {
                        $body = $origin_body;
                        $body = str_replace('#name#', $_contact['name'], $body);
                        $body = str_replace('#surname#', $_contact['surname'], $body);

                        if ((!empty($_quote['data']['link'])) && ($_quote['data']['link'] != ''))
                        {
                            $_link = '<p><a href="http://' . DOMAIN . '/app/manager/proposals/' . $_quote['data']['link'] . '/' . $_contact['id'] . '" target="_blank">Accept/Deny Proposal</a></p>';
                        }

                        $body = str_replace('<p>#links#</p>', $_link, $body);

                        $res = sendEmail($_contact, $subject, $body, $_pdfName, $_quotePDF);

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

                        $db->update("INSERT INTO quotations_emails (quote,  date) VALUES ('" . $_POST['quote'] . "', '" . current_dateTime() . "');");
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

            return $_results;
        }
    }

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            if ($_POST['notes'] == '<p><br data-mce-bogus="1"></p>')
            {
                $_POST['notes'] = null;
            }

            if ($_POST['annexure'] == '<p><br data-mce-bogus="1"></p>')
            {
                $_POST['annexure'] = null;
            }

            if ($_POST['content'] == '<p><br data-mce-bogus="1"></p>')
            {
                $_POST['content'] = null;
            }

            $vars = [
                "scope" => ((isset($_POST['scope'])) ? $_POST['scope'] : null),
                "content" => ((isset($_POST['content'])) ? $_POST['content'] : null),
                "signature" => ((isset($_POST['signature'])) ? $_POST['signature'] : null),
                "deposit" => ((isset($_POST['deposit'])) ? $_POST['deposit'] : null),
                "annexure" => ((isset($_POST['annexure'])) ? $_POST['annexure'] : null),
                "products" => ((isset($_POST['products'])) ? $_POST['products'] : null),
                "notes" => ((isset($_POST['notes'])) ? $_POST['notes'] : null)
            ];

            if ($_POST['view_type'] == 'create')
            {
                $_link = md5(current_dateTime());
                $vars['clients'] = $_POST['clients'];
                $vars['companies'] = $_POST['companies'];
                $vars['creation_date'] = current_dateTime();
                $vars['canceled'] = 'false';
                $vars['canceled_date'] = null;
                $vars['accepted'] = 'false';
                $vars['link'] = $_link;

                $res = $db->insertData($this->table, $vars);

                if ($res)
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'added';
                    $_results['id'] = $db->lastInsertId();

                    $_vars = [
                        "clients" => $_POST['clients'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => 'created',
                        "data" => json_encode($vars)
                    ];

                    performAction('manager', 'updateLog', ['logs', $_vars]);
                }
                elseif (!$res)
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }
            }
            elseif ($_POST['view_type'] == "save")
            {
                $res = $db->update($this->table, $vars, $_POST['id']);

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
                        "data" => json_encode($vars)
                    ];

                    performAction('manager', 'updateLog', ['logs', $_vars]);
                }
                elseif (!$res)
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }
            }
        }
        else
        {
            $_results['data'] = 'false';
            $_results['message'] = "nothing passed";
        }

        return $_results;
    }

    public function update()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            $_tmp = $db->select("SELECT * FROM " . $this->table . " WHERE id='" . $_POST['id'] . "';", 'true');

            if ($_POST['view_type'] == 'cancel' || $_POST['view_type'] == 'enable')
            {
                if ($_POST['view_type'] == 'cancel')
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='true', canceled_date='" . current_date() . "' WHERE id='" . $_POST['id'] . "';";
                }
                elseif ($_POST['view_type'] == 'enable')
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='false', canceled_date=null WHERE id='" . $_POST['id'] . "';";
                }

                $res = $db->update($_sql);

                if ($res)
                {
                    $_results['data'] = "true";
                    $_results['message'] = "state updated";
                }
                else
                {
                    $_results['data'] = "false";
                    $_results['message'] = $db->getError();
                }
            }
            elseif ($_POST['view_type'] == 'delete')
            {
                $res = $db->deleteData($this->table, 'id=' . $id);

                if ($res == "true")
                {
                    $_results['data'] = "true";
                    $_results['message'] = "record deleted";
                }
                else
                {
                    $_results['data'] = "false";
                    $_results['message'] = $db->getError();
                }
            }

            if ($_results['data'] == 'true')
            {
                $_vars = [
                    "clients" => $_tmp['data']['clients'],
                    "date" => current_dateTime(),
                    "users" => $_SESSION['user'],
                    "affected_table" => $this->table,
                    "action" => $_POST['view_type'],
                    "data" => json_encode($_tmp['data'])
                ];

                performAction('manager', 'updateLog', ['logs', $_vars]);
            }
        }
        else
        {
            $_results['data'] = 'false';
            $_results['message'] = 'no data passed';
        }

        return $_results;
    }

    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            if ($_POST['view_type'] == "edit")
            {
                $_results = $db->select("SELECT quotations.*, clients.business AS clientName FROM clients RIGHT JOIN quotations ON clients.id=quotations.clients WHERE quotations.id='" . $_POST['id'] . "';", 'true');
            }
            elseif ($_POST['view_type'] == "view")
            {
                $_sql = "SELECT quotations.*, clients.business AS clientName, (SELECT date FROM quotations_emails WHERE quote=quotations.id ORDER BY date DESC LIMIT 1) AS lastEmail FROM clients RIGHT JOIN quotations ON clients.id=quotations.clients ";

                if ($_POST['state'] == "true")
                {
                    $_state = "quotations.accepted='true' ";
                }
                elseif ($_POST['state'] == 'false')
                {
                    $_state = "quotations.canceled='true'";
                }
                elseif ($_POST['state'] == 'pending')
                {
                    $_state = "quotations.canceled='false' AND quotations.accepted='false'";
                }

                if (isset($_POST['client']))
                {
                    $_clients = "quotations.clients='" . $_POST['client'] . "'";
                }

                if ((isset($_state)) && (isset($_clients)))
                {
                    $_where = " WHERE " . $_state . " AND " . $_clients;
                }
                elseif ((isset($_state)) && (!isset($_clients)))
                {
                    $_where = " WHERE " . $_state;
                }
                elseif ((!isset($_state)) && (isset($_clients)))
                {
                    $_where = " WHERE " . $_clients;
                }

                if (isset($_where))
                {
                    $_sql = $_sql . $_where;
                }

                $_results = $db->select($_sql);

                if (isset($_POST['client']))
                {
                    $res = $db->select("SELECT * FROM clients WHERE id = '" . $_POST['client'] . "';", 'true');

                    $_results['business'] = $res['data']['business'];
                }
            }

            return $_results;
        }
    }
}
