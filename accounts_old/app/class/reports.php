<?php

class reports
{
    public function get_adwords($year = null)
    {
        global $db;
        $_start = $year . '-03-01';
        $_end = ($year + 1) . '-03-01';
        $_sql = "SELECT adwords.clients AS clientId, clients.business AS clientName, Sum(adwords.credit) AS income, Sum(adwords.debit) AS expense, (SELECT Sum(debit) FROM adwords WHERE clients=clientId AND commission='true') AS commission "
            . "FROM clients RIGHT JOIN adwords ON clients.id=adwords.clients "
            . "WHERE date>='" . $_start . "' AND date<'" . $_end . "' "
            . "GROUP BY adwords.clients "
            . "ORDER BY clients.business;";

        return $db->select($_sql);
    }

    public function get_expense($id = null, $year = null)
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            if ($_POST['view_type'] == "view")
            {
                $_year = $_POST['year'];

                $_dates = ['03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '01', '02'];
                if ($_POST['summary'] == 'full')
                {
                    for ($i = 0; $i < 12; $i++)
                    {
                        if ($_dates[$i] == '01' || $_dates[$i] == '02')
                        {
                            $_year = ($_POST['year'] + 1);
                        }
                        $_date = $_year . '-' . $_dates[$i];
                        $_sql = "SELECT date, (SELECT company FROM companies WHERE id=expenditure.companies) AS companyName, (SELECt category FROM categories WHERE id=expenditure.categories) AS categoryName, description, amount "
                            . "FROM expenditure  "
                            . "WHERE Year(date)='" . $_year . "' AND Month(date)='" . $_dates[$i] . "' ";
                        if ($_POST['companies'] != '0')
                        {
                            $_sql .= "AND companies='" . $_POST['companies'] . "' ";
                        }
                        $_sql .= "ORDER BY date;";
                        // $_sql .= "GROUP BY expenditure.categories;";
                        $_tmp = $db->select($_sql);
                        $_temp[$i]['data'] = $_tmp['data'];
                        $_temp[$i]['date'] = $_date;
                    }
                }
                elseif ($_POST['summary'] == 'grouped')
                {
                    for ($i = 0; $i < 12; $i++)
                    {
                        if ($_dates[$i] == '01' || $_dates[$i] == '02')
                        {
                            $_year = ($_POST['year'] + 1);
                        }
                        $_date = $_year . '-' . $_dates[$i];
                        $_sql = "SELECT date, '', (SELECt category FROM categories WHERE id=expenditure.categories) AS categoryName, description, SUM(amount) AS amount "
                            . "FROM expenditure  "
                            . "WHERE Year(date)='" . $_year . "' AND Month(date)='" . $_dates[$i] . "' ";
                        if ($_POST['companies'] != '0')
                        {
                            $_sql .= "AND companies='" . $_POST['companies'] . "' ";
                        }
                        $_sql .= "GROUP BY categories ";
                        $_sql .= "ORDER BY date;";
                        // $_sql .= "GROUP BY expenditure.categories;";
                        $_tmp = $db->select($_sql);
                        $_temp[$i]['data'] = $_tmp['data'];
                        $_temp[$i]['date'] = $_date;
                    }
                }
                $_results['data'] = $_temp;
            }

            return $_results;
        }
    }

    public function get_income()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            if ($_POST['view_type'] == "view")
            {
                $_year = $_POST['year'];

                $_dates = ['03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '01', '02'];
                if ($_POST['summary'] == 'full')
                {
                    for ($i = 0; $i < 12; $i++)
                    {
                        if ($_dates[$i] == '01' || $_dates[$i] == '02')
                        {
                            $_year = ($_POST['year'] + 1);
                        }
                        $_date = $_year . '-' . $_dates[$i];
                        $_sql = "SELECT id, date, credit, description,  (SELECT business FROM clients WHERE id=transactions.clients) AS client "
                            . "FROM transactions  "
                            . "WHERE Year(date)='" . $_year . "' AND Month(date)='" . $_dates[$i] . "' AND credit IS NOT NULL ";
                        if ($_POST['companies'] != '0')
                        {
                            $_sql .= "AND companies='" . $_POST['companies'] . "' ";
                        }
                        $_sql .= "ORDER BY date;";
                        // $_sql .= "GROUP BY expenditure.categories;";
                        $_tmp = $db->select($_sql);
                        $_temp[$i]['data'] = $_tmp['data'];
                        $_temp[$i]['date'] = $_date;
                    }
                }
                elseif ($_POST['summary'] == 'grouped')
                {
                    for ($i = 0; $i < 12; $i++)
                    {
                        if ($_dates[$i] == '01' || $_dates[$i] == '02')
                        {
                            $_year = ($_POST['year'] + 1);
                        }
                        $_date = $_year . '-' . $_dates[$i];
                        $_sql = "SELECT '', '', sum(credit) AS credit, ('For the month') AS description,  ('All Clients') AS client "
                            . "FROM transactions  "
                            . "WHERE Year(date)='" . $_year . "' AND Month(date)='" . $_dates[$i] . "' AND credit IS NOT NULL ";
                        if ($_POST['companies'] != '0')
                        {
                            $_sql .= "AND companies='" . $_POST['companies'] . "' ";
                        }
                        $_sql .= 'GROUP BY Month(date) ';
                        $_sql .= "ORDER BY date;";
                        // $_sql .= "GROUP BY expenditure.categories;";
                        $_tmp = $db->select($_sql);
                        $_temp[$i]['data'] = $_tmp['data'];
                        $_temp[$i]['date'] = $_date;
                    }
                }
                $_results['data'] = $_temp;
            }

            return $_results;
        }
    }
}
