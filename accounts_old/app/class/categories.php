<?php

class categories
{
    private $table = "categories";

    public function retrieve($link = null)
    {
        global $db;

        $_results = $db->select("SELECT id, category, price FROM categories WHERE link='" . $link . "' AND canceled='false' ORDER BY category ASC;");

        return $_results;
    }

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            $vars = [
                "category" => $_POST['category'],
                "price" => $_POST['price'],
                "link" => $_POST['link'],
            ];

            if ($_POST['view_type'] == 'create')
            {
                $vars['canceled'] = 'false';
                $vars['canceled_date'] = null;

                $res = $db->insertData($this->table, $vars);
                if ($res)
                {
                    $_results['data']['data'] = 'true';
                    $_results['message'] = 'added';
                    $_results['data']['id'] = $db->lastInsertId();
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
                    $_vars = [
                        "clients" => null,
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => 'updated',
                        "data" => json_encode($vars),
                    ];
                    performAction('manager', 'updateLog', ['logs', $_vars]);
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

    public function update($state = null, $id = null)
    {
        if ($state != null)
        {
            if ($state == 'cancel' || $state == 'enable')
            {
                global $db;
                if ($state == 'cancel')
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='true', canceled_date='" . current_date() . "' WHERE id='" . $id . "';";
                }
                elseif ($state == 'enable')
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='false', canceled_date=null WHERE id='" . $id . "';";
                }
                $res = $db->update($_sql);
                if ($res)
                {
                    $_results['data'] = "true";
                    $_results['message'] = "state updated";
                    $_vars = [
                        "clients" => null,
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

    public function view($id = null)
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            if ($_POST['view_type'] == "view")
            {
                $_results = $db->select("SELECT * FROM categories ORDER BY category ASC;");
            }
            elseif ($_POST['view_type'] == "edit")
            {
                $_results = $db->select("SELECT * FROM categories WHERE id='" . $_POST['id'] . "';", 'true');
            }
            elseif ($_POST['view_type'] == "retrieve")
            {
                $_results = $db->select("SELECT id, category, price FROM categories WHERE link='" . $_POST['link'] . "' AND canceled='false' ORDER BY category ASC;");
            }

            return $_results;
        }
    }
}
