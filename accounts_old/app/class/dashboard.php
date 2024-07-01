<?php

class dashboard
{
    private $months = ['03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '01', '02'];

    ## Get monthly income from transactions
    public function income()
    {
        global $db;
        $_dates = $this->months;
        $_year = $_POST['year'];
        $_company = $_POST['company'];

        for ($i = 0; $i < 12; $i++)
        {
            if ($_dates[$i] == '01' || $_dates[$i] == '02')
            {
                $_year = ($_POST['year'] + 1);
            }

            $res = $db->sumRows("SELECT SUM(price) AS total FROM products WHERE companies='" . $_company . "' AND Month(date)='" . $_dates[$i] . "' AND Year(date)<'" . $_year . "' AND renewable='a' AND canceled='false';");
            $_results['estimated'][$i] = highestVal($res['total']);
            $res = $db->sumRows("SELECT Sum(credit) AS total FROM transactions WHERE companies='" . $_company . "' AND Year(date)='" . $_year . "' AND Month(date)='" . $_dates[$i] . "';");
            $_results['actual'][$i] = highestVal($res['total']);
            $res = $db->sumRows("SELECT Sum(amount) AS total FROM expenditure WHERE companies='" . $_company . "' AND Year(date)='" . $_year . "' AND Month(date)='" . $_dates[$i] . "';");
            $_results['expense'][$i] = highestVal($res['total']);
        }
        $_results['months'] = $_dates;
        $_results['series'] = ['Estimated', 'Actual', 'Expenses'];

        return $_results;
    }

    ## Get annual income from transactions
    public function incomeAnual()
    {
        global $db;
        $_company = $_POST['company'];
        $_year = ($_POST['year'] + 3);
        $_a = 0;

        for ($i = 5; $i > 0; $i--)
        {
            $year = ($_year - $i);
            $_d1 = $year . '-03-' . '01';
            $_d2 = ($year - 1) . '-03-' . '01';

            $res = $db->sumRows("SELECT SUM(invoice_total) AS total FROM invoices WHERE companies='" . $_company . "' AND due_date>='" . $_d2 . "' AND due_date<'" . $_d1 . "' AND canceled='false';");
            $_results['total'][$_a] = highestVal($res['total']);
            $res = $db->sumRows("SELECT Sum(credit) AS total FROM transactions WHERE companies='" . $_company . "' AND date<='" . $_d1 . "' AND date>='" . $_d2 . "';");
            $_results['income'][$_a] = highestVal($res['total']);
            $res = $db->sumRows("SELECT Sum(amount) AS total FROM expenditure WHERE companies='" . $_company . "' AND date<='" . $_d1 . "' AND date>='" . $_d2 . "';");
            $_results['expense'][$_a] = highestVal($res['total']);

            $_results['years'][$_a] = ($year - 1) . '-' . $year;
            $_a++;
        }
        $_results['series'] = ['Estimated', 'Income', 'Expense'];

        return $_results;
    }

    ## Get monthly income from transactions
    public function incomeMonthly()
    {
        global $db;
        $_dates = $this->months;
        $_year = $_POST['year'];
        $_company = $_POST['company'];
        for ($i = 0; $i < 12; $i++)
        {
            if ($_dates[$i] == '01' || $_dates[$i] == '02')
            {
                $_year = ($_POST['year'] + 1);
            }

            $res = $db->sumRows("SELECT Sum(credit) AS total FROM transactions WHERE companies='" . $_company . "' AND Year(date)='" . ($_year - 2) . "' AND Month(date)='" . $_dates[$i] . "';");
            $_results['income']['res3'][$i] = highestVal($res['total']);
            $res = $db->sumRows("SELECT Sum(credit) AS total FROM transactions WHERE companies='" . $_company . "' AND Year(date)='" . ($_year - 1) . "' AND Month(date)='" . $_dates[$i] . "';");
            $_results['income']['res2'][$i] = highestVal($res['total']);
            $res = $db->sumRows("SELECT Sum(credit) AS total FROM transactions WHERE companies='" . $_company . "' AND Year(date)='" . $_year . "' AND Month(date)='" . $_dates[$i] . "';");
            $_results['income']['res1'][$i] = highestVal($res['total']);
        }
        $_results['months'] = $_dates;
        $_results['series'] = [($_POST['year'] - 2) . '-' . ($_POST['year'] - 1), ($_POST['year'] - 1) . '-' . $_POST['year'], $_POST['year'] . '-' . ($_POST['year'] + 1)];

        return $_results;
    }

    ## Get invoices
    public function invoices()
    {
        global $db;
        $_dates = $this->months;
        $_year = $_POST['year'];
        $_company = $_POST['company'];

        for ($i = 0; $i < 12; $i++)
        {
            if ($_dates[$i] == '01' || $_dates[$i] == '02')
            {
                $_year = ($_POST['year'] + 1);
            }

            $_results['paid'][$i] = $db->numRows("SELECT * FROM invoices WHERE companies='" . $_company . "' AND Year(due_date)='" . $_year . "' AND Month(due_date)='" . $_dates[$i] . "' AND paid='true' AND canceled='false';");
            $_results['unpaid'][$i] = $db->numRows("SELECT * FROM invoices WHERE companies='" . $_company . "' AND Year(due_date)='" . $_year . "' AND Month(due_date)='" . $_dates[$i] . "' AND paid='false' AND canceled='false';");
            $_results['canceled'][$i] = $db->numRows("SELECT * FROM invoices WHERE companies='" . $_company . "' AND Year(due_date)='" . $_year . "' AND Month(due_date)='" . $_dates[$i] . "' AND canceled='true';");
            $_results['total'][$i] = $db->numRows("SELECT * FROM invoices WHERE companies='" . $_company . "' AND Year(due_date)='" . $_year . "' AND Month(due_date)='" . $_dates[$i] . "' AND canceled='false';");
        }
        $_results['months'] = $_dates;
        $_results['series'] = ['Total', 'Canceled', 'Unpaid', 'Paid'];

        return $_results;
    }

    ## Get annual income from invoices
    public function invoicesAnual()
    {
        global $db;

        $_year = $_POST['year'];
        $_company = $_POST['company'];

        $_year = ($_POST['year'] + 3);
        $_a = 0;
        for ($i = 5; $i > 0; $i--)
        {
            $year = ($_year - $i);
            $_d1 = $year . '-03-' . '01';
            $_d2 = ($year - 1) . '-03-' . '01';

            $res = $db->sumRows("SELECT SUM(invoice_total) AS total FROM invoices WHERE companies='" . $_company . "' AND due_date>='" . $_d2 . "' AND due_date<'" . $_d1 . "' AND canceled='false';");
            $_results['total'][$_a] = highestVal($res['total']);
            $res = $db->sumRows("SELECT SUM(invoice_total) AS total FROM invoices WHERE companies='" . $_company . "' AND due_date>='" . $_d2 . "' AND due_date<'" . $_d1 . "' AND paid='true' AND canceled='false';");
            $_results['paid'][$_a] = highestVal($res['total']);
            if ($_results['total'][$_a] > 0)
            {
                $_results['unpaid'][$_a] = ($_results['total'][$_a] - $_results['paid'][$_a]);
            }
            else
            {
                $_results['unpaid'][$_a] = 0;
            }
            $res = $db->sumRows("SELECT SUM(invoice_total) AS total FROM invoices WHERE companies='" . $_company . "' AND due_date>='" . $_d2 . "' AND due_date<'" . $_d1 . "' AND canceled='true';");
            $_results['canceled'][$_a] = highestVal($res['total']);
            $res = $db->sumRows("SELECT SUM(price) AS total FROM products WHERE companies='" . $_company . "' AND date<'" . $_d2 . "' AND renewable='a' AND canceled='false';");
            $_results['predicted'][$_a] = highestVal($res['total']);
            $res = $db->sumRows("SELECT SUM(price) AS total FROM products WHERE companies='" . $_company . "' AND renewable='m' AND canceled='false';");

            if ($res['total'] != "")
            {
                $_results['predicted'][$_a] += ($res['total'] * 12);
            }

            $_results['years'][$_a] = ($year - 1) . '-' . $year;
            $_a++;
        }
        $_results['series'] = ['Estimated', 'Paid', 'Unpaid', 'Canceled', 'Predicted'];

        return $_results;
    }

    ## Get monthly income from invoices
    public function invoicesMonthly()
    {
        global $db;
        $_dates = $this->months;
        $_year = $_POST['year'];
        $_company = $_POST['company'];

        for ($i = 0; $i < 12; $i++)
        {
            if ($_dates[$i] == '01' || $_dates[$i] == '02')
            {
                $_year = ($_POST['year'] + 1);
            }

            $res = $db->sumRows("SELECT SUM(invoice_total) AS total FROM invoices WHERE companies='" . $_company . "' AND Year(due_date)='" . $_year . "' AND Month(due_date)='" . $_dates[$i] . "' AND canceled='false';");
            $_results['total'][$i] = highestVal($res['total']);
            $res = $db->sumRows("SELECT SUM(invoice_total) AS total FROM invoices WHERE companies='" . $_company . "' AND Year(due_date)='" . $_year . "' AND Month(due_date)='" . $_dates[$i] . "' AND paid='true' AND canceled='false';");
            $_results['paid'][$i] = highestVal($res['total']);
            $res = $db->sumRows("SELECT SUM(invoice_total) AS total FROM invoices WHERE companies='" . $_company . "' AND Year(due_date)='" . $_year . "' AND Month(due_date)='" . $_dates[$i] . "' AND paid='false' AND canceled='false';");
            $_results['unpaid'][$i] = highestVal($res['total']);
            $res = $db->sumRows("SELECT SUM(invoice_total) AS total FROM invoices WHERE companies='" . $_company . "' AND Year(due_date)='" . $_year . "' AND Month(due_date)='" . $_dates[$i] . "' AND canceled='true';");
            $_results['canceled'][$i] = highestVal($res['total']);
        }
        $_results['months'] = $_dates;
        $_results['series'] = ['Estimated', 'Paid', 'Unpaid', 'Canceled'];

        return $_results;
    }

    public function recentemails()
    {
        global $db;

        $email_query = "SELECT email_log.id, users.username AS username, contacts.name AS contactname, email_log.subject, email_log.date FROM contacts LEFT JOIN (users RIGHT JOIN email_log ON users.id=email_log.users) ON contacts.id=email_log.contacts ORDER BY email_log.date DESC LIMIT 20;";
        $_results = $db->select($email_query);

        return $_results;
    }
}
