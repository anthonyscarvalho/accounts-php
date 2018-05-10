<?php

class expenditure
{
    private $table = "expenditure";

    public function categories($year = null, $company = null)
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            if ($_POST['view_type'] == "view")
            {
                $curreYear = $_POST['searchYear'] . "-03-01";

                $nextYear = ($_POST['searchYear'] + 1) . "-03-01";

                $_results['nologin'] = true;

                $_sql = "SELECT SUM(expenditure.amount) AS catTotal, categories.category AS categoryName "

                    . "FROM companies RIGHT JOIN (categories RIGHT JOIN expenditure ON categories.id=expenditure.categories) ON companies.id=expenditure.companies "

                    . "WHERE expenditure.date>'" . $curreYear . "' AND expenditure.date<'" . $nextYear . "' ";

                if (!empty($_POST['company']))
                {
                    if ($_POST['company'] != 0)
                    {
                        $_sql .= "AND expenditure.companies=" . $_POST['company'] . " ";
                    }
                }

                $_sql .= "GROUP BY expenditure.categories "

                    . "ORDER BY categoryName;";

                $_results = $db->select($_sql);
            }

            return $_results;
        }
    }

    public function delete($id = null)
    {
        global $db;

        $res = $db->deleteData('expenditure', 'id=' . $id);

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

        return $_results;
    }

    public function save()
    {
        global $db;

        $vars = [

            "categories" => $_POST['categories'],

            "companies" => $_POST['companies'],

            "date" => $_POST['date'],

            "amount" => $_POST['amount'],

            "description" => (!empty($_POST['description']) ? $_POST['description'] : null),

            "type" => $_POST['type'],

        ];

        if ($_POST['view_type'] == 'create')
        {
            $res = $db->insertData($this->table, $vars);

            if ($res)
            {
                $_results['data'] = 'true';

                $_results['message'] = 'added';

                $_results['recent']['id'] = $db->lastInsertId();

                $_results['recent']['date'] = $_POST['date'];

                $_results['recent']['amount'] = $_POST['amount'];

                $_vars = [

                    "clients" => null,

                    "date" => current_dateTime(),

                    "users" => $_SESSION['user'],

                    "affected_table" => $this->table,

                    "action" => 'created',

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
        elseif ($_POST['view_type'] == "save")
        {
            $res = $db->update($this->table, $vars, $_POST['id']);

            if ($res)
            {
                $_results['data'] = 'true';

                $_results['message'] = 'record updated';

                $_vars = [

                    "clients" => null,

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

    public function view($year = null, $company = null)
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            if ($_POST['view_type'] == 'view')
            {
                $curreYear = $_POST['searchYear'] . "-03-01";

                $nextYear = ($_POST['searchYear'] + 1) . "-03-01";

                $_sql = "SELECT expenditure.id, expenditure.date, expenditure.description, expenditure.amount, companies.company AS companyName, categories.category AS categoryName "

                    . "FROM companies RIGHT JOIN (categories RIGHT JOIN expenditure ON categories.id=expenditure.categories) ON companies.id=expenditure.companies "

                    . "WHERE expenditure.date>'" . $curreYear . "' AND expenditure.date<'" . $nextYear . "' ";

                if (!empty($_POST['company']))
                {
                    if ($_POST['company'] != 0)
                    {
                        $_sql .= "AND expenditure.companies=" . $_POST['company'] . " ";
                    }
                }

                $_sql .= "ORDER BY expenditure.date;";

                $_results = $db->select($_sql);
                $_results['sql']=$_sql;
            }
            elseif ($_POST['view_type'] == 'edit')
            {
                $_results = $db->select("SELECT * FROM expenditure WHERE id='" . $_POST['id'] . "';", 'true');
            }

            return $_results;
        }
    }
}
