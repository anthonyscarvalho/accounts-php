<?php

class attachments
{
    private $table = 'attachments';

    public function add()
    {
        $fileName = $_FILES['file']['name'];
        $tmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        $ext = strtolower(end(explode('.', $fileName)));
        if ($ext == "pdf")
        {
            $fp = fopen($tmpName, 'r');
            $content = fread($fp, filesize($tmpName));

            fclose($fp);
            global $db;
            $vars = [
                "attachment" => $content,
            ];

            $res = $db->insertData($this->table, $vars);

            if ($res)
            {
                $_results['data'] = 'true';
                $_results['id'] = $db->lastInsertId();
            }
            elseif (!$res)
            {
                $_results['data'] = 'false';
                $_results['message'] = $db->getError();
            }
        }
        else
        {
            $_results['data'] = 'false';
            $_results['message'] = "only pdf's allowed";
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

    public function save()
    {
        if (isset($_POST['view_type']))
        {
            global $db;
            $vars = [
                "date" => $_POST['date'],
                "clients" => $_POST['clients'],
                "description" => ((isset($_POST['description'])) ? $_POST['description'] : null),
            ];

            $res = $db->update($this->table, $vars, $_POST['id']);

            if ($res)
            {
                $_results['data'] = 'true';
                $_results['message'] = 'record updated';
            }
            elseif (!$res)
            {
                $_results['data'] = 'false';
                $_results['message'] = $db->getError();
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
                $_sql = "SELECT id, date, description, accepted, accepted_date FROM attachments WHERE clients='" . $_POST['id'] . "';";
                $_results = $db->select($_sql);

                $res = $db->select("SELECT * FROM clients WHERE id='" . $_POST['id'] . "';", 'true');
                $_results['business'] = $res['data']['business'];
            }

            return $_results;
        }
    }
}
