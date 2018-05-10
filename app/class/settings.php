<?php

class settings
{
    private $table = 'settings';

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            $vars = [
                "name" => $_POST['name'],
                "value" => $_POST['value'],
            ];
            if ($_POST['view_type'] == "save")
            {
                $res = $db->update($this->table, $vars, $_POST['id']);

                if ($res)
                {
                    $_data = 'true';
                    $_message = 'record updated';
                }
                elseif (!$res)
                {
                    $_data = 'false';
                    $_message = $db->getError();
                }
            }
        }

        $_results['data'] = $_data;
        $_results['message'] = $_message;

        return $_results;
    }

    public function view()
    {
        if (isset($_POST['view_type']))
        {
            global $db;

            if ($_POST['view_type'] == 'view')
            {
                $_sql = "SELECT * FROM settings ORDER BY name;";
                $_results = $db->select($_sql);
            }
            elseif ($_POST['view_type'] == 'edit')
            {
                $_results = $db->select("SELECT * FROM settings WHERE id='" . $_POST['id'] . "';", 'true');
            }

            return $_results;
        }
    }
}
