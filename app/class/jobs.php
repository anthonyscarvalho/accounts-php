<?php

class jobs
{
    private $table = "jobs";

    public function delete($id = null)
    {
        global $db;

        $res = $db->deleteData('jobs', 'id=' . $id);

        if ($res == "true")
        {
            $_results['data'] = "true";
            $_results['message'] = "deleted";
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
        if (isset($_POST['view_type']))
        {
            global $db;

            $vars = [
                "categories" => $_POST['categories'],
                "quoted" => $_POST['quoted'],
                "received" => $_POST['received'],
                "ended" => ((isset($_POST['ended']) && (!empty($_POST['ended']))) ? $_POST['ended'] : null),
                "design" => ((isset($_POST['design']) && (!empty($_POST['design']))) ? $_POST['design'] : null),
                "seo" => ((isset($_POST['seo']) && (!empty($_POST['seo']))) ? $_POST['seo'] : null),
                "google" => ((isset($_POST['google']) && (!empty($_POST['google']))) ? $_POST['google'] : null),
                "yahoo" => ((isset($_POST['yahoo']) && (!empty($_POST['yahoo']))) ? $_POST['yahoo'] : null),
                "bing" => ((isset($_POST['bing']) && (!empty($_POST['bing']))) ? $_POST['bing'] : null),
                "dmz" => ((isset($_POST['dmz']) && (!empty($_POST['dmz']))) ? $_POST['dmz'] : null),
                "traveldex" => ((isset($_POST['traveldex']) && (!empty($_POST['traveldex']))) ? $_POST['traveldex'] : null),
                "links" => ((isset($_POST['links']) && (!empty($_POST['links']))) ? $_POST['links'] : null),
                "portfolio" => ((isset($_POST['portfolio']) && (!empty($_POST['portfolio']))) ? $_POST['portfolio'] : null),
                "facebook" => ((isset($_POST['facebook']) && (!empty($_POST['facebook']))) ? $_POST['facebook'] : null),
                "invoice" => ((isset($_POST['invoice']) && (!empty($_POST['invoice']))) ? $_POST['invoice'] : null),
                "paid" => ((isset($_POST['paid']) && (!empty($_POST['paid']))) ? $_POST['paid'] : null),
                "notes" => ((isset($_POST['notes']) && (!empty($_POST['notes']))) ? $_POST['notes'] : null),
                "complete" => ((isset($_POST['complete']) && (!empty($_POST['complete']))) ? $_POST['complete'] : null)
            ];
            if($_SESSION['user'] == $_POST['users'])
            {
                $vars['start']=((isset($_POST['start']) && (!empty($_POST['start']))) ? $_POST['start'] : null);
                $vars['end']=((isset($_POST['end']) && (!empty($_POST['end']))) ? $_POST['end'] : null);
                $vars['allDay']=((isset($_POST['allDay']) && (!empty($_POST['allDay']))) ? $_POST['allDay'] : null);
            }

            if ($_POST['view_type'] == 'create')
            {
                $vars['clients'] = $_POST['clients'];
                $vars['users'] = $_POST['users'];
                $vars['creation_date'] = current_dateTime();
                $vars['canceled'] = 'false';
                $vars['canceled_date'] = null;

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
                $res = $db->deleteData($this->table, "id='" . $_POST['id'] . "'");

                if ($res)
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
                $_sql = "SELECT
                *,
                (SELECT CONCAT(name, ' ', surname) AS name FROM users WHERE id=jobs.users) AS userName ,
                (SELECT business FROM clients WHERE id=jobs.clients) AS business
                FROM jobs WHERE id='" . $_POST['id'] . "';";
                $_results = $db->select($_sql, 'true');
            }
            elseif ($_POST['view_type'] == "view")
            {
                $_sql = "SELECT jobs.*, clients.business FROM clients RIGHT JOIN jobs ON clients.id=jobs.clients";

                if ($_POST['state'] == "true" || $_POST['state'] == "false")
                {
                    $_state = "jobs.canceled='" . $_POST['state'] . "' ";
                }
                elseif ($_POST['state'] == 'incomplete')
                {
                    $_state = "jobs.canceled='false' AND complete='false' ";
                }
                elseif ($_POST['state'] == 'complete')
                {
                    $_state = "jobs.canceled='false' AND complete='true' ";
                }

                if (isset($_POST['client']))
                {
                    $_clients = "clients='" . $_POST['client'] . "'";
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
            elseif ($_POST['view_type'] == 'user')
            {
                $_sql = "SELECT
                jobs.*,
                clients.business
                FROM clients RIGHT JOIN jobs ON clients.id=jobs.clients
                WHERE jobs.canceled='false' AND jobs.complete='false' AND jobs.users=" . $_SESSION['user'] . ";";

                $_results = $db->select($_sql);
            }

            return $_results;
        }
    }
}
