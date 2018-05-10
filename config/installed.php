<?php

if (!file_exists(ROOT . DS . 'config' . DS . 'version.php'))
{
    $_file = fopen(ROOT . DS . 'config' . DS . 'version.php', 'w');
    fwrite($_file, '<?php' . "\n" . '$_data_base_version = "";' . "\n" . '$_control_panel_version = "1.5";');
    fclose($_file);
}

// include_once ROOT . DS . 'config' . DS . 'version.php';
if (file_exists(ROOT . DS . 'config' . DS . 'database.php'))
{
    include ROOT . DS . 'config' . DS . 'database.php';
}

if (!isset($_data_base_version))
{
    $_file = fopen(ROOT . DS . 'config' . DS . 'version.php', 'w');
    fwrite($_file, '<?php' . "\n" . '$_data_base_version = "";' . "\n" . '$_control_panel_version = "1.5";');
    fclose($_file);
}

if (!isset($db))
{
    include ROOT . DS . "app" . DS . "library" . DS . "sql.class.php";

    $db = new sql();
}

$tables = [];

//set list of default tables and columns in table
$_defaultTables = $_tableConfig;
//get tables from current database
$_tables = $db->getTables();
//temporary variable, used to store current query
$templine = '';

if (!empty($_tables))
{
//loop through current tables of database
    foreach ($_tables as $table)
    {
//loop through list of default tables
        foreach ($_defaultTables as $_default)
        {
//check if the current table exists in default list
            if ($_default['name'] == $table)
            {
                //get the columns of the current table loop
                $_currentColumns = $db->getColumns($table);
                //compare the columns of the current table to the default table
                $_tmpDif = array_diff($_default['columns'], $_currentColumns);

//if there are no differences then remove the default table from the main list
                if (empty($_tmpDif))
                {
                    unset($_defaultTables[array_search($_default, $_defaultTables)]);
                }
                else
                {
                    $index = array_search($_default, $_defaultTables);
                    $_defaultTables[$index]['columns'] = $_tmpDif;
                }

                break;
            }
        }
    }

//loop through default tables left and create them
    foreach ($_defaultTables as $_defaults)
    {
        if (!array_search($_defaults['name'], $_tables))
        {
            $lines = file(ROOT . DS . 'config' . DS . 'install' . DS . $_defaults['name'] . '.sql');

//loop through the missing columns
            foreach ($lines as $line)
            {
// Skip it if it's a comment
                if (substr($line, 0, 2) == '--' || $line == '')
                {
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

            echo 'Table added: ' . $_defaults['name'] . '<br>';
        }
        elseif ((isset($_tables[array_search($_defaults['name'], $_tables)])) && (file_exists(ROOT . DS . 'config' . DS . 'install' . DS . $_defaults['name'] . '.sql')))
        {
            //load the relevant sql file
            $contents = file_get_contents(ROOT . DS . 'config' . DS . 'install' . DS . $_defaults['name'] . '.sql');

//loop through the missing columns
            foreach ($_defaults['columns'] as $_col)
            {
                //prepare the search pattern
                $pattern = preg_quote($_col, '/');
                //finalise the regular expression, matching the whole line
                $pattern = "/^.*$pattern.*\$/m";

//search, and store all matching occurrence in $matches
                if (preg_match_all($pattern, $contents, $matches))
                {
                    if ($_col != 'id')
                    {
                        //add the column to the relevant table
                        $_sqlUpdate = 'ALTER TABLE `' . $_defaults['name'] . '` ADD ' . rtrim(implode("\n", $matches[0]), ',');
                    }
                    else
                    {
                        $_sqlUpdate = 'ALTER TABLE `' . $defaults['name'] . '` AND `id` int(10) NOT NULL AUTO_INCREMENT';
                    }

                    $res = $db->update($_sqlUpdate);

                    if ($res)
                    {
                        echo 'Table updated: ' . $_defaults['name'] . ' - ' . $_sqlUpdate . '<br>';
                        // echo implode("\n", $matches[0]);
                    }
                }
            }
        }
    }
}
else
{
//loop through default tables left and create them
    foreach ($_defaultTables as $_defaults)
    {
        if (file_exists(ROOT . DS . 'config' . DS . 'install' . DS . $_defaults['name'] . '.sql'))
        {
            //load the relevant sql file
            $lines = file(ROOT . DS . 'config' . DS . 'install' . DS . $_defaults['name'] . '.sql');

//loop through the missing columns
            foreach ($lines as $line)
            {
// Skip it if it's a comment
                if (substr($line, 0, 2) == '--' || $line == '')
                {
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

            echo 'Table added: ' . $_defaults['name'] . '<br>';
        }
    }

    if (file_exists(ROOT . DS . 'tmp' . DS . 'db' . DS . 'website.sql'))
    {
        //temporary variable, used to store current query
        $templine = '';

        //load the relevant sql file
        $lines = file(ROOT . DS . 'tmp' . DS . 'db' . DS . 'website.sql');

//loop through the missing columns
        foreach ($lines as $line)
        {
// Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
            {
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
    }

    if (file_exists(ROOT . DS . 'config' . DS . 'install' . DS . 'defaults.sql'))
    {
        //temporary variable, used to store current query
        $templine = '';

        //load the relevant sql file
        $lines = file(ROOT . DS . 'config' . DS . 'install' . DS . 'defaults.sql');

//loop through the missing columns
        foreach ($lines as $line)
        {
// Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
            {
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
    }

    echo "Tables imported successfully";
}
