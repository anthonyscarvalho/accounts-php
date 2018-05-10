<?php

class emailer
{
    private $attachment = [];

    private $body = '';

    private $contact = [];

    private $subject = '';

    public function sendMail( $log = 'invoice' )
    {

        if ( !empty( $this->contact ) )
        {
            require_once ROOT . DS . 'assets' . DS . 'swift' . DS . 'swift_required.php';

//Set up the mail method

            if ( DEVELOPMENT_ENVIRONMENT == true )
            {
                $transport = Swift_SmtpTransport::newInstance( 'localhost', 25 );
            }
            else
            {
                $transport = Swift_SmtpTransport::newInstance( emailServer, 465 )->setUsername( emailAddress )->setPassword( emailPassword );
            }

            $mailer = Swift_Mailer::newInstance( $transport );

// To use the ArrayLogger

            $logger = new Swift_Plugins_Loggers_ArrayLogger();
            $mailer->registerPlugin( new Swift_Plugins_LoggerPlugin( $logger ) );

//Create admin email to be sent

            $admin = Swift_Message::newInstance();
            $admin->setPriority( 2 ); //High
            $admin->setSubject( $this->subject );
            $admin->setFrom( [emailAddress => emailCompany] );

            if ( !empty( $this->attachment ) )
            {
                $attach = Swift_Attachment::newInstance();
                $attach->setFilename( $this->attachment['name'] );
                $attach->setContentType( 'application/pdf' );
                $attach->setBody( $this->attachment['content'] );
                $admin->attach( $attach );

            }

            $admin->setTo( [$this->contact['email'] => $this->contact['name'] . " " . $this->contact['surname']] );

            $admin->setBody( $this->body, 'text/html' );

//Send the email contact

            $res = $mailer->send( $admin );

            if ( $res )
            {
                $_vars['contacts'] = $this->contact['id'];
                $_vars['status'] = $logger->dump();
                $_vars['date'] = current_dateTime();
                $_vars['body'] = $this->body;
                $_vars['subject'] = $this->subject;
                $_vars['users'] = $_SESSION['user'];

                if ( !empty( $this->attachment ) )
                {

                    if ( $this->attachment['type'] == 'invoices' )
                    {
                        $_vars['invoices'] = $this->attachment['id'];
                    }
                    elseif ( $this->attachment['type'] == 'quotes' )
                    {
                        $_vars['quotes'] = $this->attachment['id'];
                    }

                }

                if ( $log == 'invoice' )
                {
                    performAction( 'manager', 'updateLog', ['email_log', $_vars] );
                }
                elseif ( $log == 'ads' )
                {
                    $_vars['attachment'] = null;
                    performAction( 'manager', 'updateLog', ['ad_emails', $_vars] );
                }

                $_results['data'] = true;
                $_results['message'] = 'sent to: ' . $this->contact['name'];
            }
            else
            {
                $_results['data'] = false;
                $_results['message'] = 'not sent to: ' . $this->contact['name'];
            }

        }
        else
        {
            $_results['data'] = false;
            $_results['message'] = 'no contacts';
        }

        return $_results;

    }

    public function setAttachment( $type = null, $content = null, $name = null, $id = null )
    {

        if ( !empty( $type ) && !empty( $content ) && !empty( $name ) )
        {
            $add = false;

            if ( $type == 'statements' )
            {
                $add = true;
            }
            elseif ( $type != 'statements' && !empty( $id ) )
            {
                $add = true;
            }

            if ( $add )
            {
                $this->attachment = [
                    'id' => $id,
                    'name' => $name,
                    'type' => $type,
                    'content' => $content,
                ];
            }

        }

    }

    public function setContact( $id = null, $name = null, $surname = null, $email = null )
    {

        if ( !empty( $id ) && !empty( $name ) && !empty( $email ) )
        {
            $this->contact = [
                'id' => $id,
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
            ];
        }

    }

    public function setEmailBody( $body = '' )
    {
        $this->body = $body;
    }

    public function setEmailSubject( $subject = '' )
    {
        $this->subject = $subject;
    }

}
