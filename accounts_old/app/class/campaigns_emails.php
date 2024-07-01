<?php

class campaigns_emails
{
    private $table = "ad_emails";

    public function email()
    {
        global $db;

        $contacts_query = "SELECT * FROM contacts WHERE clients='" . $_POST['clients'] . "' AND adwords='true';";

        $total_contacts = $db->numRows($contacts_query);

        if ($total_contacts > 0)
        {
            $_client = $db->select("SELECT * FROM clients WHERE id='" . $_POST['clients'] . "';", 'true');

            $_template = $db->select("SELECT * FROM template_attachments WHERE id='4';", 'true');
            $template = $_template['data']['template'];

            $template = str_replace('#business#', $_client['data']['business'], $template);
            $template = str_replace('#billing_address#', $_client['data']['billing_address'], $template);
            $template = str_replace('#city#', $_client['data']['city'], $template);
            $template = str_replace('#postal_code#', $_client['data']['postal_code'], $template);
            $template = str_replace('#vat#', $_client['data']['vat'], $template);
            $template = str_replace('#client_id#', $_client['data']['id'], $template);

            $template = str_replace('#date#', $_POST['date'], $template);
            $template = str_replace('#due_date#', $_POST['due_date'], $template);
            $template = str_replace('#month#', $_POST['month'], $template);
            $template = str_replace('#amount#', number_format($_POST['amount'], 2, '.', ' '), $template);

            $_pdfData['pdfName'] = $_client['data']['id'] . '-' . $_client['data']['business'] . '-' . $_POST['date'] . '.pdf';
            $_pdfData['page'][0] = $template;

            $_invoicePDF = createPDF('email', $_pdfData);

            $subject = $_POST['emailsubject'];
            $body = $_POST['emailbody'];
            $_mes = "";

            $contacts = $db->select($contacts_query);

            $_vars = [
                "campaigns" => $_POST['campaigns'],
                "users" => $_SESSION['user'],
                "subject" => $subject,
            ];

            foreach ($contacts['data'] as $contact)
            {
                $body = str_replace('#name#', $contact['name'], $body);
                $body = str_replace('#surname#', $contact['surname'], $body);

                $res = sendEmail($contact, $subject, $body, $_pdfData['pdfName'], $_invoicePDF);

                $_vars['contacts'] = $contact['id'];
                $_vars['date'] = current_dateTime();
                $_vars['status'] = $res['server_log'];
                $_vars['body'] = $body;
                $_vars['attachment'] = $_invoicePDF;
                performAction('manager', 'updateLog', ['ad_emails', $_vars]);

                if ($res['status'])
                {
                    $_mes .= "email sent to: " . $contact['name'] . "<br/>";
                }
                else
                {
                    $_mes .= "email not sent to: " . $contact['name'] . "<br/>";
                }
            }
        }
        else
        {
            $_results['data'] = 'false';
            $_results['message'] = 'no contacts to send to';
            $_mes = '';
        }

        if ($_mes != "")
        {
            $_results['data'] = 'true';
            $_results['message'] = $_mes;
        }
        else
        {
            $_results['data'] = 'true';
            $_results['message'] = 'no message';
        }

        return $_results;
    }

    public function preview()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            if ($_POST['view_type'] == 'edit')
            {
                $_results = $db->blob($this->table, $_POST['id']);
            }

            return $_results;
        }
    }

    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            if ($_POST['view_type'] == 'view')
            {
                $_sql = "SELECT id, (SELECT name FROM users WHERE id=ad_emails.users) AS userName, (SELECT name FROM contacts WHERE id=ad_emails.contacts) AS contactName, subject, date " .
                    "FROM ad_emails " .
                    "WHERE campaigns='" . $_POST['campaigns'] . "';";

                $_results = $db->select($_sql);
            }
            elseif ($_POST['view_type'] == 'edit')
            {
                // $_sql = "SELECT *, (SELECT name FROM users WHERE id=ad_emails.users) AS userName, (SELECT name FROM contacts WHERE id=ad_emails.contacts) AS contactName FROM ad_emails WHERE id='" . $_POST['id'] . "';";

                $_results = $db->select("SELECT id, date, subject, body, status, (SELECT name FROM users WHERE id=ad_emails.users) AS userName, (SELECT name FROM contacts WHERE id=ad_emails.contacts) AS contactName FROM ad_emails WHERE id='" . $_POST['id'] . "';", 'true');
            }

            return $_results;
        }
    }
}
