<?php

class transactions
{
    private $table = "transactions";

    public function delete( $id = null, $client = null )
    {

        global $db;

        $res = $db->deleteData( 'transactions', "id='" . $_POST['id'] . "'" );

        if ( $res )
        {

            $_results['data'] = 'true';

            $_results['message'] = 'record deleted';

            $_vars = [
                "clients" => $_POST['clients'],
                "date" => current_dateTime(),
                "users" => $_SESSION['user'],
                "affected_table" => $this->table,
                "action" => 'removed',
                "data" => json_encode( ["id" => $_POST['id']] ),
            ];

            performAction( 'manager', 'updateLog', ['logs', $_vars] );

        }
        else
        {
            $_results['data'] = 'false';
            $_results['message'] = $db->getError();
        }

        return $_results;

    }

    public function deleteTrans()
    {

        global $db;

        $res = $db->deleteData( 'transactions', "id='" . $_POST['id'] . "'" );

        if ( $res )
        {

            $res2 = performAction( 'invoices', 'creditUpdate', [] );

            if ( $res2['data'] == 'true' )
            {

                $_results['data'] = 'true';

                $_results['message'] = 'record deleted ' . $res2['message'];

            }
            else
            {

                $_results['data'] = 'false';

                $_results['message'] = 'record deleted but invoice not updated';

            }

        }
        else
        {

            $_results['data'] = 'false';

            $_results['message'] = $db->getError();

        }

        return $_results;

    }

    public function getCredit( $id = null )
    {

        global $db;

        $_sql = "SELECT * FROM transactions WHERE clients='" . $id . "' ORDER BY date;";

        $total_trans = $db->numRows( $_sql );

        $credit = 0;

        if ( $total_trans > 0 )
        {

            $temp_trans = $db->select( $_sql );

            foreach ( $temp_trans['data'] as $transaction )
            {

                if ( !empty( $transaction['credit'] ) )
                {

                    $credit += $transaction['credit'];

                }
                elseif ( empty( $transaction['credit'] ) && !empty( $transaction['debit'] ) )
                {

                    $credit -= $transaction['debit'];

                }

            }

        }

        $_results['data'] = $credit;

        return $_results;

    }

    public function save()
    {

        if ( isset( $_POST['view_type'] ) )
        {

            global $db;

            $vars = [
                "clients" => $_POST['clients'],
                "date" => $_POST['date'],
                "description" => $_POST['description'],
                "credit" => $_POST['credit'],
                "companies" => $_POST['companies'],
            ];

            if ( $_POST['view_type'] == 'create' )
            {

                $vars['debit'] = null;

                $res = $db->insertData( $this->table, $vars );

                if ( $res )
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'transaction added';
                    $_results['id'] = $db->lastInsertId();
                    $vars['sendMail'] = $_POST['sendMail'];
                    $_vars = [
                        "clients" => $_POST['clients'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => 'created',
                        "data" => json_encode( $vars ),
                    ];

                    performAction( 'manager', 'updateLog', ['logs', $_vars] );

                    if ( $_POST['sendMail'] == "true" )
                    {
                        $_results['message'] .= '<br><br>';

                        $contacts_query = "SELECT * FROM contacts WHERE clients='" . $_POST['clients'] . "' AND payment='true' AND canceled='false';";

                        $total_contacts = $db->numRows( $contacts_query );

                        if ( $total_contacts > 0 )
                        {

                            $email = $db->select( "SELECT * FROM template_emails WHERE id='3';", 'true' );
                            $origin_body = $email['data']['body'];
                            $subject = $email['data']['subject'];
                            //set up mailer
                            $emailer = new emailer();
                            $emailer->setEmailSubject( $subject );
                            $body = str_replace( '#amount#', number_format( $_POST['credit'], 2, '.', ' ' ), $origin_body );
                            $body = str_replace( '#date#', $_POST['date'], $body );
                            $contacts = $db->select( $contacts_query );

                            foreach ( $contacts['data'] as $_contact )
                            {
                                //insert contact name into email body
                                $_body = $body;
                                $_body = str_replace( '#name#', $_contact['name'], $_body );
                                $_body = str_replace( '#surname#', $_contact['surname'], $_body );
                                $emailer->setEmailBody( $_body );

                                $emailer->setContact( $_contact['id'], $_contact['name'], $_contact['surname'], $_contact['email'] );

                                $_res = $emailer->sendMail();
                                $_results['message'] .= 'sent: ' . $_contact['name'] . ' ' . $_contact['surname'] . "<br>";

                            }

                        }

                    }

                }
                elseif ( !$res )
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }

            }
            elseif ( $_POST['view_type'] == "save" )
            {

                $res = $db->update( $this->table, $vars, $_POST['id'] );

                if ( $res )
                {

                    $_vars = [

                        "clients" => $_POST['clients'],

                        "date" => current_dateTime(),

                        "users" => $_SESSION['user'],

                        "affected_table" => $this->table,

                        "action" => 'updated',

                        "data" => json_encode( $vars ),

                    ];

                    performAction( 'manager', 'updateLog', ['logs', $_vars] );

                    $_results['data'] = 'true';

                    $_results['message'] = 'record updated';

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

    public function view()
    {

        if ( isset( $_POST['view_type'] ) )
        {

            global $db;

            if ( $_POST['view_type'] == 'view' )
            {

                $_sql = "SELECT * FROM transactions WHERE invoices='" . $_POST['invoice'] . "' ORDER BY date;";

                $_results = $db->select( $_sql );

            }
            elseif ( $_POST['view_type'] == "search" )
            {

                $_sql = "SELECT * FROM transactions WHERE clients='" . $_POST['client'] . "' ORDER BY date;";

                $_results = $db->select( $_sql );

                $res = $db->select( "SELECT * FROM clients WHERE id='" . $_POST['client'] . "';", 'true' );

                $_results['business'] = $res['data']['business'];

            }
            elseif ( $_POST['view_type'] == "edit" )
            {

                $_sql = "SELECT * FROM transactions WHERE id='" . $_POST['id'] . "';";

                $_results = $db->select( $_sql, 'true' );

                if ( !empty( $this->_results['data']['debit'] ) )
                {

                    $_results['data'] = '';

                    $_results['redirect'] = true;

                }

            }

            return $_results;

        }

    }

}
