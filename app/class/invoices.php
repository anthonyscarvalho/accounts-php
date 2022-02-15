<?php
class invoices
{
    private $table = "invoices";

    public function create()
    {
        global $db;

        // $_d2 = getdate( date( strtotime( $_POST['prodDate'] ) ) );

        $products = $_POST['products'];
        $_message = '';
        $_sentTo = '';
        $_newIinvoices = [];

        if ( count( $products ) > 0 )
        {
            $_d1 = getdate( date( strtotime( $_POST['prodDate'] ) ) );
            $current_date = current_date();

            if ( $_POST['invoiceType'] == "due" )
            {
                $_dueDate = $_d1['year'] . "-" . $_d1['mon'] . "-01";
            }
            elseif ( $_POST['invoiceType'] == "now" )
            {
                $_dueDate = $_POST['prodDate'];
            }

            $vars = [
                "clients" => $_POST['clients'],
                "creation_date" => $current_date,
                "canceled_date" => null,
                "due_date" => $_dueDate,
                "paid_date" => null,
                "notes" => null,
                "paid" => 'false',
                "canceled" => 'false',
            ];

            foreach ( $products as $_comp )
            {

                if ( count( $_comp['items'] ) > 0 )
                {

                    $_invTotal = 0;

                    foreach ( $_comp['items'] as $prod )
                    {
                        $_invTotal += $prod['price'];
                    }

                    $vars['companies'] = $_comp['id'];
                    $vars['invoice_total'] = $_invTotal;
                    $vars['deposit'] = (  ( isset( $_POST['deposit'] ) && ( !empty( $_POST['deposit'] ) ) ) ? $_POST['deposit'] : null );
                    $vars['vat'] = (  ( isset( $_POST['vat'] ) && ( !empty( $_POST['vat'] ) ) ) ? $_POST['vat'] : null );

                    $res = $db->insertData( 'invoices', $vars );

                    if ( !$res )
                    {
                        $_results['data'] = 'false';
                        $_message .= $db->getError();
                        break;
                    }
                    elseif ( $res )
                    {
                        $_invoiceId = $db->lastInsertId();
                        array_push( $_newIinvoices, $_invoiceId );

                        $_ins = true;

                        foreach ( $_comp['items'] as $item )
                        {
                            $_renewable = $item['renewable'];

                            $_prodDate = getdate( date( strtotime( $item['date'] ) ) );

                            $_prodID = $item['id'];

                            $item['invoices'] = $_invoiceId;
                            $item['products'] = $item['id'];

                            if ( $_renewable == 'm' )
                            {
                                $_tmpDate = $_d1['year'] . "-" . $_d1['mon'] . "-" . $_prodDate['mday'];
                            }
                            elseif ( $_renewable == 'o' )
                            {
                                $_tmpDate = $_d1['year'] . "-" . $_prodDate['mon'] . "-" . $_prodDate['mday'];
                            }
                            elseif ( $_renewable == 'r' )
                            {
                                $_tmpDate = $_d1['year'] . "-" . $_d1['mon'] . "-" . $_d1['mday'];
                            }
                            elseif ( $_renewable == 'a' )
                            {
                                $_tmpDate = $_d1['year'] . "-" . $_prodDate['mon'] . "-" . $_prodDate['mday'];
                            }
                            else
                            {
                                $_tmpDate = $_POST['prodDate'];
                            }

                            $item['date'] = $_tmpDate;

                            unset( $item['id'] );
                            unset( $item['canceled'] );
                            unset( $item['canceled_date'] );
                            unset( $item['clientName'] );
                            unset( $item['categoryName'] );
                            unset( $item['clients'] );
                            unset( $item['companies'] );
                            unset( $item['lastInvoice'] );
                            unset( $item['month'] );
                            unset( $item['period'] );
                            unset( $item['renewable'] );
                            unset( $item['year'] );

                            $res2 = $db->insertData( 'invoices_items', $item );

                            if ( $res2 )
                            {
                                $_ins = true;

                                if (  ( $_renewable == "r" ) || ( $_renewable == "o" ) )
                                {
                                    $vars = [
                                        "canceled" => 'true',
                                        "canceled_date" => current_date(),
                                    ];

                                    $res = $db->update( 'products', $vars, $_prodID );
                                }

                            }
                            else
                            {
                                $_ins = false;
                            }

                        }

                        if ( $_ins )
                        {
                            $_results['data'] = 'true';
                            $_message .= 'invoice created for: ' . $_comp['company'] . "<br>";

                            $_vars = [
                                "clients" => $_POST['clients'],
                                "date" => current_dateTime(),
                                "users" => $_SESSION['user'],
                                "affected_table" => $this->table,
                                "action" => "created",
                                "data" => json_encode( $vars ),
                            ];

                            performAction( 'manager', 'updateLog', ['logs', $_vars] );

                            if ( $_POST['sendMail'] == 'true' )
                            {
                                $_POST['view_type'] = 'view';
                                $_POST['display'] = 'email';
                                $_POST['new_invoices'] = $_newIinvoices;
                                $_POST['email_type'] = 'email';

                                $res = performAction( 'invoices', 'email_invoice', [] );

                                $_sentTo .= $res['message'];
                            }

                        }
                        else
                        {
                            $_results['data'] = 'false';
                            $_message .= 'invoice items not inserted<br>';
                        }

                    }

                }
                else
                {
                    $_message .= 'no invoice created for: ' . $_comp['company'] . "<br>";
                }

            }

        }
        else
        {
            $_results['data'] = "false";
            $_message .= "no products to add<br>";
        }

        $_results['message'] = 'Invoices:<br>' . $_message . '<br><br>Emails Sent:<br>' . $_sentTo;

        return $_results;

    }

    public function credit()
    {
        global $db;

        $_invoice = $db->select( "SELECT * FROM invoices WHERE id='" . $_POST['id'] . "';", 'true' );

        if ( $_invoice['data']['paid'] == 'false' )
        {
            $_sql = "SELECT * FROM transactions WHERE clients='" . $_invoice['data']['clients'] . "' ORDER BY date;";

            $total_trans = $db->numRows( $_sql );
            $_paidDate = "";
            $_paidTrans = 0;
            $_credit = 0;

            if ( $total_trans > 0 )
            {
                $temp_trans = $db->select( $_sql );

                foreach ( $temp_trans['data'] as $transaction )
                {

                    if ( $transaction['invoices'] == $_POST['id'] )
                    {
                        $_paidTrans += $transaction['debit'];
                    }

                    if ( !empty( $transaction['credit'] ) )
                    {
                        $_credit += $transaction['credit'];
                        $_paidDate = $transaction['date'];
                    }
                    elseif ( empty( $transaction['credit'] ) && !empty( $transaction['debit'] ) )
                    {
                        $_credit -= $transaction['debit'];
                    }

                }

            }

            if ( $_paidDate == "" )
            {
                $_paidDate = current_date();
            }

            $_totalDue = ( $_invoice['data']['invoice_total'] - $_paidTrans );
            $_totalDue = number_format( $_totalDue, 2, '.', '' );
            $_credit = number_format( $_credit, 2, '.', '' );

            if (  ( $_credit > 0 ) && ( $_invoice['data']['invoice_total'] > 0 ) )
            {

                if ( $_totalDue > $_credit )
                {
                    $_paid = number_format( $_credit, 2, '.', '' );
                    $_invPaid = "false";
                }
                elseif ( $_totalDue < $_credit )
                {
                    $_paid = number_format( $_totalDue, 2, '.', '' );
                    $_invPaid = "true";
                }
                elseif ( $_totalDue == $_credit )
                {
                    $_paid = number_format( $_totalDue, 2, '.', '' );
                    $_invPaid = "true";
                }

                if ( $_invPaid == "true" )
                {
                    $vars = [
                        "paid" => 'true',
                        "paid_date" => $_paidDate,
                    ];

                    $res = $db->update( 'invoices', $vars, $_POST['id'] );
                }
                elseif ( $_invPaid == "false" )
                {
                    $res = "true";
                }

                if ( $res )
                {
                    $vars = [
                        "clients" => $_invoice['data']['clients'],
                        "date" => $_paidDate,
                        "description" => "Invoice #" . $_POST['id'] . " - Credit",
                        "credit" => null,
                        "debit" => $_paid,
                        "invoices" => $_POST['id'],
                    ];

                    $res2 = $db->insertData( 'transactions', $vars );

                    if ( $res2 )
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
            elseif ( $_invoice['data']['invoice_total'] == 0 )
            {
                $vars = [
                    "paid" => 'true',
                    "paid_date" => current_date(),
                ];

                $res = $db->update( 'invoices', $vars, $_POST['id'] );

                if ( $res )
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

        $_credit = $db->select( "SELECT Sum(debit) AS total FROM transactions WHERE invoices='" . $_POST['invoice'] . "';", 'true' );

        if ( $_credit['data']['total'] > 0 )
        {
            $_invoice = $db->select( "SELECT * FROM invoices WHERE id='" . $_POST['invoice'] . "';", 'true' );

            if ( $_credit['data']['total'] < $_invoice['data']['invoice_total'] )
            {
                $vars = [
                    "paid" => 'false',
                    "paid_date" => null,
                ];

                $res = $db->update( $this->table, $vars, $_POST['invoice'] );
            }
            elseif ( $_credit['data']['total'] > $_invoice['data']['invoice_total'] )
            {
                $vars = [
                    "paid" => 'false',
                    "paid_date" => null,
                ];

                $res = $db->update( $this->table, $vars, $_POST['invoice'] );

                $_transsql = "SELECT * FROM transactions WHERE invoices='" . $_POST['invoice'] . "' ORDER BY date DESC;";

                $_totTrans = $db->numRows( $_transsql );

                if ( $_totTrans > 1 )
                {
                    $trans = $db->select( "SELECT * FROM transactions WHERE invoices='" . $_POST['invoice'] . "' ORDER BY date DESC LIMIT 1;", 'true' );
                    $res = $db->deleteData( 'transactions', 'id=' . $trans['data']['id'] );
                }
                else
                {
                    $trans = $db->select( $_transsql, 'true' );
                    $res = $db->deleteData( 'transactions', 'id=' . $trans['data']['id'] );
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

            $res = $db->update( $this->table, $vars, $_POST['invoice'] );
        }

        if ( $res )
        {
            $_results['data'] = "true";
            $_results['message'] = "updated";
        }
        elseif ( !$res )
        {
            $_results['data'] = "false";
            $_results['message'] = $db->getError();
        }

        return $_results;
    }

    public function delete( $id = null, $client = null )
    {
        global $db;

        $res = $db->deleteData( 'transactions', 'invoices=' . $id );

        if ( $res )
        {
            $res = $db->deleteData( 'invoices_items', 'invoices=' . $id );

            if ( $res )
            {
                $res = $db->deleteData( 'invoices_emails', 'invoice=' . $id );

                if ( $res )
                {
                    $res = $db->deleteData( 'invoices', 'id=' . $id );

                    if ( $res )
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

    public function edit( $id = null )
    {
        global $db;

        $_sql = "SELECT invoices.*, companies.company AS companyName "
            . "FROM companies RIGHT JOIN invoices ON companies.id=invoices.companies "
            . "WHERE invoices.id='" . $id . "';";

        $_results = $db->select( $_sql, 'true' );

        $_sql = "SELECT business "
            . "FROM clients "
            . "WHERE id='" . $_results['data']['clients'] . "';";

        $res = $db->select( $_sql, 'true' );

        $_results['clientName'] = $res['data']['business'];

        return $_results;
    }

    public function email_invoice()
    {

        if ( isset( $_POST['view_type'] ) )
        {

            if (  ( !empty( $_POST['emailbody'] ) ) && ( !empty( $_POST['emailsubject'] ) ) )
            {
                global $db;
                $_invoices = [];
                $_message = '';
//set up emailer class
                $emailer = new emailer();
                $emailer->setEmailSubject( $_POST['emailsubject'] );
//retrieve client information
                $_client = $db->select( "SELECT * FROM clients WHERE id='" . $_POST['clients'] . "';", 'true' );
//retrieve contacts associated with client
                $_contactSql = "SELECT * FROM contacts WHERE clients='" . $_client['data']['id'] . "' AND canceled='false' AND invoice='true';";
//make sure there are contacts to send mail to
                $_totContacts = $db->numRows( $_contactSql );

                if ( count( $_totContacts ) > 0 )
                {

                    $_contacts = $db->select( $_contactSql );
//create variable of email subject
                    $origin_body = $_POST['emailbody'];

//loop through all contacts
                    foreach ( $_contacts['data'] as $_contact )
                    {
//set the contact for the individual email
                        $emailer->setContact( $_contact['id'], $_contact['name'], $_contact['surname'], $_contact['email'] );
//set the body of the email
                        $_body = $origin_body;
                        $_body = str_replace( '#name#', $_contact['name'], $_body );
                        $_body = str_replace( '#surname#', $_contact['surname'], $_body );
                        $emailer->setEmailBody( $_body );

//check how many invoices need to be created
                        if ( isset( $_POST['invoice'] ) )
                        {
                            array_push( $_invoices, $_POST['invoice'] );
                        }
                        elseif ( !isset( $_POST['invoice'] ) && isset( $_POST['new_invoices'] ) )
                        {
                            $_invoices = $_POST['new_invoices'];
                        }

//loop through all the newly created invoices for the client
                        foreach ( $_invoices as $key )
                        {
//create the invoice in pdf format and attach it to the mail
                            $_invoicePDF = performAction( 'invoices', 'printInvoice', [$key, 'email'] );
                            $emailer->setAttachment( 'invoices', $_invoicePDF, $_client['data']['id'] . '-' . $_client['data']['business'] . '-' . $key . '.pdf', $key );

//send one email per attachment to selected contact
                            $_res = $emailer->sendMail();

                            if ( $_res['data'] )
                            {
//update the emailed status of the invoice
                                $db->update( "INSERT INTO invoices_emails (invoice, email_type, date) VALUES ('" . $key . "', '" . $_POST['email_type'] . "', '" . current_dateTime() . "');" );
                            }

                            $_message .= $_res['message'] . '<br>';
                        }

                    }

                    $_results['data'] = "true";
                    $_results['message'] = $_message;

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

    public function printInvoice( $_id = null, $_email = null )
    {

        if ( !empty( $_id ) && is_numeric( $_id ) )
        {
            ob_start();

            global $db;

            $_settings = $db->select( "SELECT * FROM settings WHERE name='templates';", 'true' );

            $_templates = json_decode( $_settings['data']['value'], true );

            $_invoice = $db->select( "SELECT * FROM invoices WHERE id='" . $_id . "';", 'true' );
            $_client = $db->select( "SELECT * FROM clients WHERE id='" . $_invoice['data']['clients'] . "';", 'true' );
            $_invItems = $db->select( "SELECT invoices_items.date, categories.category, invoices_items.description, invoices_items.price FROM categories RIGHT JOIN invoices_items ON categories.id=invoices_items.categories WHERE invoices_items.invoices='" . $_id . "' ORDER BY categories.category;" );
            $_transactions = $db->select( "SELECT * FROM transactions WHERE invoices='" . $_id . "' ORDER BY date;" );
            $_company = $db->select( "SELECT * FROM companies WHERE id='" . $_invoice['data']['companies'] . "';", 'true' );
            $_template = $db->select( "SELECT * FROM template_attachments WHERE id='" . $_templates['first_invoice'] . "';", 'true' );

            if ( $_templates['terms_of_service'] != '' )
            {
                $_termsOfService = $db->select( "SELECT * FROM template_attachments WHERE id='" . $_templates['terms_of_service'] . "';", 'true' );
            }

            $_pdfData['pdfName'] = $_client['data']['id'] . '-' . $_client['data']['business'] . '-' . $_invoice['data']['id'] . '.pdf';

            // start pdf layout
            $template = $_template['data']['template'];
            $template = str_replace( '#invoice_header#', $_company['data']['invoice_header'], $template );
            $template = str_replace( '#invoice_id#', $_invoice['data']['id'], $template );
            $template = str_replace( '#creation_date#', $_invoice['data']['creation_date'], $template );
            $template = str_replace( '#due_date#', $_invoice['data']['due_date'], $template );
            $template = str_replace( '#business#', $_client['data']['business'], $template );
            $template = str_replace( '#billing_address#', $_client['data']['billing_address'], $template );
            $template = str_replace( '#city#', $_client['data']['city'], $template );
            $template = str_replace( '#postal_code#', $_client['data']['postal_code'], $template );

            if ( $_client['data']['vat'] != "" )
            {
                $template = str_replace( '#vat#', "VAT #: " . $_client['data']['vat'], $template );
            }
            else
            {
                $template = str_replace( '#vat#', '', $template );
            }

            if ( $_client['data']['registration'] != "" )
            {
                $template = str_replace( '#registration#', "Registration #: " . $_client['data']['registration'], $template );
            }
            else
            {
                $template = str_replace( '#registration#<br>', '', $template );
            }

            $template = str_replace( '#client_id#', $_client['data']['id'], $template );
            $template = str_replace( '#account_details#', $_company['data']['account_details'], $template );

            if ( $_invoice['data']['paid'] == "true" && $_invoice['data']['canceled'] == "false" )
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
            elseif ( $_invoice['data']['paid'] == "false" && $_invoice['data']['canceled'] == "false" )
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
            elseif ( $_invoice['data']['canceled'] == "true" )
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

            foreach ( $_transactions['data'] as $trans )
            {

                if ( !empty( $trans['debit'] ) )
                {
                    $trans_html .= '<tr style="background-color: #fff;">'
                    . '<td style="width:60px;">' . $trans['date'] . '</td>'
                    . '<td style="width:380px;">' . $trans['description'] . '</td>'
                    . '<td style="width:100px;">R ' . number_format( $trans['debit'], 2, '.', ' ' ) . '</td>'
                        . '</tr>';

                    $trans_total += $trans['debit'];
                }

            }

            $trans_html .= '</table>';

            # Invoice Items

            $items_html = '<table width="100%" cellspacing="1" cellpadding="2" border="0" style="background-color:#ccc; font-size:9pt;">'
                . '<tr height="30" style="font-weight:bold; text-align:center; background-color:#efefef;">';

            if ( $_invoice['data']['show_date'] == 'true' )
            {
                $items_html .= '<td style="width:60px;">Date</td>'
                    . '<td style="width:400px;">';
            }
            else
            {
                $items_html .= '<td colspan="2" style="width:460px;">';
            }

            $items_html .= 'Item</td>'
                . '<td style="width:80px;">Price</td>'
                . '</tr>';

            $subtotal = 0;

            foreach ( $_invItems['data'] as $items )
            {
                $items_html .= '<tr height="30" style="background-color:#fff;">';

                if ( $_invoice['data']['show_date'] == 'true' )
                {
                    $items_html .= '<td>' . $items['date'] . '</td>'
                        . '<td style="width:400px;">';
                }
                else
                {
                    $items_html .= '<td colspan="2" style="width:460px;">';
                }

                $items_html .= $items['category'];

                if ( $items['description'] != '' )
                {
                    $items_html .= ' - ' . $items['description'];
                }

                $items_html .= '</td>'
                . '<td>R ' . number_format( $items['price'], 2, '.', ' ' ) . '</td>'
                    . '</tr>';

                $subtotal += $items['price'];
            }

            if ( $_invoice['data']['vat'] > 0 )
            {
                $_vat = ( $subtotal * ( $_invoice['data']['vat'] / 100 ) );
                $items_html .= '<tr height="30" style="background-color: #efefef;">'
                . '<td colspan="2" style="text-align:right;">VAT On Invoice</td>'
                . '<td>R ' . number_format( $_vat, 2, '.', ' ' ) . '</td>'
                    . '</tr>';
                $subtotal = ( $subtotal + $_vat );
            }

            $items_html .= '<tr height="30" style="background-color: #efefef;">'
            . '<td colspan="2" style="text-align:right;">Invoice Total</td>'
            . '<td>R ' . number_format( $subtotal, 2, '.', ' ' ) . '</td>'
                . '</tr>';

            if ( !empty( $_invoice['data']['deposit'] ) )
            {
                $items_html .= '<tr height="30" style="background-color: #efefef;">'
                . '<td colspan="2" style="text-align:right;">Deposit Required</td>'
                . '<td>R ' . number_format( $_invoice['data']['deposit'], 2, '.', ' ' ) . '</td>'
                    . '</tr>';
            }

            $items_html .= '<tr height="30" style="background-color: #efefef;">'
            . '<td colspan="2" style="text-align:right;">Total Paid</td>'
            . '<td>R ' . number_format( $trans_total, 2, '.', ' ' ) . '</td>'
            . '</tr>'
            . '<tr height="30" style="background-color: #010180; font-weight:bold; color:#fff;">'
            . '<td colspan="2" style="text-align:right;">Total Due</td>'
            . '<td>R ' . number_format(  ( $subtotal - $trans_total ), 2, '.', ' ' ) . '</td>'
                . '</tr>'
                . '</table>';

            $template = str_replace( '#invoice_items#', $items_html, $template );
            $template = str_replace( '#invoice_transactions#', $trans_html, $template );

            # pages

            $_pdfData['page'][0] = $template;

            if ( $_invoice['data']['notes'] != '' )
            {
                $_pdfData['page'][1] = '<p style="font-size:10pt;"><strong>Invoice Notes</strong></p><span style="font-size:9pt;">' . $_invoice['data']['notes'] . '</span><br>' . $_termsOfService['data']['template'];
            }

            if(isset($_termsOfService) && $_termsOfService['data']['template'] != '')
            {
                $_pdfData['page'][1] = $_termsOfService['data']['template'];
            }

            if ( empty( $_email ) )
            {
                createPDF( 'print', $_pdfData );
            }
            elseif (  ( isset( $_email ) ) && ( $_email == 'email' ) )
            {
                // set the email body and subject u
                return createPDF( 'email', $_pdfData );
                exit();
            }

            $_results['nodata'] = 'true';

            return $_results;
        }

    }

    public function save()
    {

        if ( isset( $_POST['view_type'] ) )
        {
            global $db;

            $vars = [
                "creation_date" => $_POST['creation_date'],
                "due_date" => $_POST['due_date'],
                "notes" => $_POST['notes'],
                "deposit" => (  ( isset( $_POST['deposit'] ) && ( !empty( $_POST['deposit'] ) ) ) ? $_POST['deposit'] : null ),
                "vat" => (  ( isset( $_POST['vat'] ) && ( !empty( $_POST['vat'] ) ) ) ? $_POST['vat'] : 0 ),
            ];

            if ( $_POST['view_type'] == 'save' )
            {
                $res = $db->update( 'invoices', $vars, $_POST['id'] );

                if ( $res )
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'record updated';

                    $_vars = [
                        "clients" => $_POST['clients'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => 'updated',
                        "data" => json_encode( $vars ),
                    ];

                    performAction( 'manager', 'updateLog', ['logs', $_vars] );
                }
                elseif ( !$res )
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }

            }

            return $_results;
        }

    }

    public function update()
    {

        if ( isset( $_POST['view_type'] ) )
        {
            global $db;
            $_tmp = $db->select( "SELECT * FROM " . $this->table . " WHERE id='" . $_POST['id'] . "';", 'true' );

            if ( $_POST['view_type'] == 'cancel' || $_POST['view_type'] == 'enable' )
            {

                if ( $_POST['view_type'] == 'cancel' )
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='true', canceled_date='" . current_date() . "' WHERE id='" . $_POST['id'] . "';";
                }
                elseif ( $_POST['view_type'] == 'enable' )
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='false', canceled_date=null WHERE id='" . $_POST['id'] . "';";
                }

                $res = $db->update( $_sql );

                if ( $res )
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
            elseif ( $_POST['view_type'] == 'delete' )
            {
                $res = $db->deleteData( 'transactions', "invoices='" . $_POST['id'] . "'" );

                if ( $res )
                {
                    $res = $db->deleteData( 'invoices_items', "invoices='" . $_POST['id'] . "';" );

                    if ( $res )
                    {
                        $res = $db->deleteData( 'invoices_emails', "invoice='" . $_POST['id'] . "';" );

                        if ( $res )
                        {
                            $res = $db->deleteData( 'invoices', "id='" . $_POST['id'] . "';" );

                            if ( $res )
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
                    $_results['message'] = 'transactions: ' . $db->getError();
                }

            }

            if ( $_results['data'] == 'true' )
            {
                $_vars = [
                    "clients" => $_tmp['data']['clients'],
                    "date" => current_dateTime(),
                    "users" => $_SESSION['user'],
                    "affected_table" => $this->table,
                    "action" => $_POST['view_type'],
                    "data" => json_encode( $_tmp['data'] ),
                ];

                performAction( 'manager', 'updateLog', ['logs', $_vars] );
            }

        }
        else
        {
            $_results['data'] = 'false';
            $_results['message'] = 'no data passed';
        }

        return $_results;
    }

    public function view( $state = null, $paid = null )
    {

        if ( isset( $_POST['view_type'] ) )
        {
            global $db;

            if ( $_POST['view_type'] == 'view' )
            {
                $_whereArray = [];
                $_searchArray = [];

                if ( $_POST['state'] == 'true' || $_POST['state'] == 'false' )
                {
                    array_push( $_whereArray, ['column' => 'invoices.canceled', 'value' => $_POST['state'], 'operator' => '=', 'selector' => 'AND'] );
                }

                if ( $_POST['paid'] == 'true' || $_POST['paid'] == 'false' )
                {
                    array_push( $_whereArray, ['column' => 'invoices.paid', 'value' => $_POST['paid'], 'operator' => '=', 'selector' => 'AND'] );
                }

                if ( isset( $_POST['client'] ) )
                {
                    array_push( $_whereArray, ['column' => 'invoices.clients', 'value' => $_POST['client'], 'operator' => '=', 'selector' => 'AND'] );
                }

                if ( isset( $_POST['sortCompany'] ) && ( $_POST['sortCompany'] != '' ) && ( $_POST['sortCompany'] != '0' ) )
                {
                    array_push( $_whereArray, ['column' => 'invoices.companies', 'value' => $_POST['sortCompany'], 'operator' => '=', 'selector' => 'AND'] );
                }

                if ( isset( $_POST['sortSearch'] ) && ( $_POST['sortSearch'] != '' ) )
                {
                    array_push( $_searchArray, ['column' => 'invoices.id', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR'] );
                    array_push( $_searchArray, ['column' => 'invoices.creation_date', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR'] );
                    array_push( $_searchArray, ['column' => 'invoices.due_date', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR'] );
                    array_push( $_searchArray, ['column' => 'invoices.paid_date', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR'] );
                    array_push( $_searchArray, ['column' => 'clients.business', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR'] );
                }

                if ( count( $_whereArray ) > 0 )
                {
                    $_where = " WHERE ";

                    foreach ( $_whereArray as $_tmp )
                    {
                        $_where .= " " . $_tmp['column'] . $_tmp['operator'] . "'" . $_tmp['value'] . "' " . $_tmp['selector'];
                    }

                    if ( count( $_searchArray ) == 0 )
                    {
                        $_where = rtrim( $_where, "AND" );
                        $_where = rtrim( $_where, "OR" );
                    }

                }

                if ( count( $_searchArray ) > 0 )
                {

                    if ( count( $_whereArray ) == 0 )
                    {
                        $_where = " WHERE ";
                    }

                    $_where .= " ( ";

                    foreach ( $_searchArray as $_tmp )
                    {
                        $_where .= " " . $_tmp['column'] . $_tmp['operator'] . "'" . $_tmp['value'] . "' " . $_tmp['selector'];
                    }

                    $_where = rtrim( $_where, "AND" );
                    $_where = rtrim( $_where, "OR" );

                    $_where .= " ) ";
                }

                $_sql = "SELECT clients.business AS clientName, clients.id AS clients, invoices.id, invoices.creation_date, invoices.due_date, invoices.paid_date, invoices.canceled_date, invoices.invoice_total, invoices.canceled, (SELECT COUNT(id) FROM invoices_items WHERE invoices=invoices.id) AS totalItems, (SELECT invoices_emails.date FROM invoices_emails WHERE invoices_emails.invoice=invoices.id ORDER BY invoices_emails.date DESC LIMIT 1) AS lastInvoice FROM clients LEFT JOIN invoices ON clients.id=invoices.clients";

                if ( isset( $_where ) )
                {
                    $_sql = $_sql . $_where;
                }

                $_total = $db->numRows( $_sql );

                if ( isset( $_POST['sort'] ) )
                {
                    $_sql .= " ORDER BY " . $_POST['sort'] . (  ( isset( $_POST['sortOrder'] ) ) ? " " . $_POST['sortOrder'] : '' );
                }

                if ( isset( $_POST['page'] ) && isset( $_POST['records'] ) )
                {
                    $_limitOffset = (  ( $_POST['page'] - 1 ) * $_POST['records'] );

                    $_sql .= " LIMIT  " . $_limitOffset . ", " . $_POST['records'];
                }

                $_sql .= ';';

                $_results = $db->select( $_sql );
                $_results['records'] = $_total;

// $_results['query'] = $_sql;

                if ( isset( $_POST['client'] ) )
                {
                    $res = $db->select( "SELECT * FROM clients WHERE id = '" . $_POST['client'] . "';", 'true' );

                    $_results['business'] = $res['data']['business'];
                }

            }
            elseif ( $_POST['view_type'] == 'edit' )
            {
                $_sql = "SELECT invoices.*, companies.company AS companyName "
                    . "FROM companies RIGHT JOIN invoices ON companies.id=invoices.companies "
                    . "WHERE invoices.id='" . $_POST['id'] . "';";

                $_results = $db->select( $_sql, 'true' );

                $_sql = "SELECT business "
                    . "FROM clients "
                    . "WHERE id='" . $_results['data']['clients'] . "';";

                $res = $db->select( $_sql, 'true' );

                $_results['clientName'] = $res['data']['business'];
            }

            return $_results;
        }

    }

};
