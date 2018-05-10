<?php
class database
{
    public function backup($install = null)
    {
        $_fileName = ROOT . DS . 'tmp' . DS . 'db' . DS . 'accounts.sql';
        // file header stuff

        if (empty($install))
        {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
            $output = 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";' . "\n\n";
            $output .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n"
                . "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n"
                . "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n"
                . "/*!40101 SET NAMES utf8mb4 */;\n\n";

            // $output .= "--\n-- Database: `" . DB_NAME . "`\n--\n";
            // get all table names in db and stuff them into an array

            $tables = [];
            $stmt = $pdo->query("SHOW TABLES");
            while ($row = $stmt->fetch(PDO::FETCH_NUM))
            {
                $tables[] = $row[0];
            }
            // process each table in the db
            foreach ($tables as $table)
            {
                $fields = "";
                $sep2 = "";
                $output .= "\n-- " . str_repeat("-", 60) . "\n\n";
                $output .= "--\n-- Table structure for table `$table`\n--\n\n";
                // get table create info
                $stmt = $pdo->query("SHOW CREATE TABLE $table");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $output .= '--' . $table . '--' . "\n";
                $output .= "DROP TABLE IF EXISTS `$table`;\n";
                $output .= $row[1] . ";\n\n";
                // get table data
                $output .= "--\n-- Dumping data for table `$table`\n--\n\n";
                $stmt = $pdo->query("SELECT * FROM $table");
                while ($row = $stmt->fetch(PDO::FETCH_OBJ))
                {
                    // runs once per table - create the INSERT INTO clause
                    if ($fields == "")
                    {
                        $fields = "INSERT INTO `$table` (";
                        $sep = "";
                        // grab each field name
                        foreach ($row as $col => $val)
                        {
                            $fields .= $sep . "`$col`";
                            $sep = ", ";
                        }
                        $fields .= ") VALUES";
                        // $output .= $fields . "\n";
                    }
                    // grab table data
                    $sep = "";
                    $fields .= $sep2 . "(";
                    foreach ($row as $col => $val)
                    {
                        // add slashes to field content
                        $val = addslashes($val);
                        // replace stuff that needs replacing
                        $search = ["\'", "\n", "\r"];
                        $replace = ["''", "\n", "\r"];
                        $val = str_replace($search, $replace, $val);
                        $fields .= $sep . "'$val'";
                        $sep = ", ";
                    }
                    // terminate row data
                    $fields .= ")";
                    $sep2 = ",\n";
                }
                // terminate insert data
                if ($fields != '')
                {
                    $fields .= ";\n";
                    $output .= $fields . "\n\n";
                }
                echo 'export complete: ' . $table . '<br>';
            }
            $handle = fopen($_fileName, 'w');
            fwrite($handle, $output);
            fclose($handle);
            $_results['nodata'] = 'true';
            echo 'backup complete';
        }
        elseif ($install === 'true')
        {
            global $db;
            // Temporary variable, used to store current query
            $templine = '';
            // Read in entire file
            $lines = file($_fileName);
            // Loop through each line
            foreach ($lines as $line)
            {
                // Skip it if it's a comment
                if (substr($line, 0, 2) == '--' || $line == '')
                {
                    echo 'begin import: ' . $line . "<br>";
                    continue;
                }
                // Add this line to the current segment
                $templine .= $line;
                // If it has a semicolon at the end, it's the end of the query
                if (substr(trim($line), -1, 1) == ';')
                {
                    // Perform the query
                    $db->update($templine);
                    // Reset temp variable to empty
                    $templine = '';
                }
            }
            $_results['nodata'] = 'true';
            echo "tables imported successfully";
        }
        return $_results;
    }
}
