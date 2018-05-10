<?php

class sql
{
    protected $_query;

    private $db;

    public function __construct()
    {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;

        try
        {
            $this->db = new PDO($dsn, DB_USERNAME, DB_PASSWORD, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
            exit;
        }
    }

    public function blob($table, $id)
    {
        try

        {
            $_sql = "SELECT attachment FROM " . $table . " WHERE id='" . $id . "';";
            $stmt = $this->db->prepare($_sql);
            $stmt->execute();
            $stmt->bindColumn(1, $lob, PDO::PARAM_LOB);
            $rows = $stmt->fetch(PDO::FETCH_BOUND);
            echo $lob;
        }
        catch (PDOException $e)
        {
            $response["data"] = $e->getMessage();
        }

        // return $response;
    }

    public function deleteData($table, $where)
    {
        try
        {
            $_sql = "DELETE FROM " . $table . " WHERE " . $where;
            $stmt = $this->db->prepare($_sql);
            $res = $stmt->execute() || die(print_r($stmt->errorInfo(), true));
            return $res;
        }
        catch (PDOException $e)
        {
            return "error";
        }
    }

    public function getColumns($table)
    {
        $stmt = $this->db->prepare("DESCRIBE " . $table);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getError()
    {
        return $this->db->errorInfo();
    }

    public function getTables()
    {
        $stmt = $this->db->prepare("SHOW TABLES");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function insertData($table, $_array = null)
    {
        try

        {
            if (isset($_array))
            {
                $_col = '';
                $_val = '';

                foreach ($_array as $key => $val)
                {
                    $_col .= $key . ", ";
                    $_val .= ":" . $key . ", ";
                }

                $_sql = "INSERT INTO " . $table . " (" . rtrim($_col, ", ") . ") VALUES (" . rtrim($_val, ", ") . ");";
                $stmt = $this->db->prepare($_sql);

                foreach ($_array as $key => $val)
                {
                    $stmt->bindValue(":" . $key, $val);
                }
            }
            else
            {
                $_sql = $table;
                $stmt = $this->db->prepare($_sql);
            }

            return $stmt->execute() || die(print_r($stmt->errorInfo(), true));
        }
        catch (PDOException $e)
        {
            return "error";
        }
    }

    public function lastInsertId($name = null)
    {
        if (!$this->db)
        {
            throw new Exception('not connected');
        }

        return $this->db->lastInsertId($name);
    }

    public function numRows($_sql)
    {
        $stmt = $this->db->prepare($_sql);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function select($_sql, $single = null)
    {
        try
        {
            $stmt = $this->db->prepare($_sql);
            $stmt->execute();

            if ($single == 'true')
            {
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            else
            {
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // $response[ "query" ] = $_sql;
            $response["data"] = $rows;
        }
        catch (PDOException $e)
        {
            $response["data"] = $e->getMessage();
        }

        return $response;
    }

    public function sumRows($_sql)
    {
        $stmt = $this->db->prepare($_sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($table, $_array = null, $id = null)
    {
        try
        {
            if (isset($_array))
            {
                $_sql = "UPDATE " . $table . " SET ";

                foreach ($_array as $key => $val)
                {
                    $_sql .= $key . "=:" . $key . ",";
                }

                $_sql = rtrim($_sql, ",");
                $_sql .= " WHERE id='" . $id . "';";
                $stmt = $this->db->prepare($_sql);

                foreach ($_array as $key => $val)
                {
                    $stmt->bindValue(":" . $key, $val);
                }
            }
            else
            {
                $stmt = $this->db->prepare($table);
            }

            return $stmt->execute() || die(print_r($stmt->errorInfo(), true));
        }
        catch (PDOException $e)
        {
            return "error";
        }
    }
}
