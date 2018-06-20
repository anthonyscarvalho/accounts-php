<?php

class products
{
    private $table = "products";

    public function delete( $id = null )
    {
        global $db;

        $_tmp = $db->select( "SELECT * FROM " . $this->table . " WHERE id=" . $id . ";", 'true' );

        if ( count( $_tmp['data'] ) > 0 )
        {
            $res = $db->update( "DELETE FROM " . $this->table . " WHERE id='" . $id . "';" );

            if ( $res )
            {
                $_results['data'] = "true";
                $_results['message'] = "product deleted";

                $_vars = [
                    "clients" => $_tmp['data']['clients'],
                    "date" => current_dateTime(),
                    "users" => $_SESSION['user'],
                    "affected_table" => $this->table,
                    "action" => "removed",
                    "data" => json_encode( $_tmp['data'] ),
                ];

                performAction( 'manager', 'updateLog', ['logs', $_vars] );
            }
            else
            {
                $_results['data'] = "false";
                $_results['message'] = $db->getError();
            }

        }
        else
        {
            $_results['data'] = "false";
            $_results['message'] = 'no products';
        }

        return $_results;
    }

    public function invoice()
    {

        if ( isset( $_POST['view_type'] ) )
        {
            global $db;

            if ( $_POST['view_type'] == 'due' )
            {
                $_tmpCompanies = $db->select( "SELECT id, company FROM companies WHERE canceled='false'" );

                if ( count( $_tmpCompanies['data'] ) > 0 )
                {
                    $_companies = $_tmpCompanies['data'];
                    $products = [];
                    $_count = 0;

                    $d1 = getdate( date( strtotime( $_POST['date'] ) ) );

                    foreach ( $_companies as $_company )
                    {
                        $_tmpProducts = [];

                        $_sql = "SELECT
                            products.*,
                            categories.category AS categoryName,
                            (SELECT date FROM invoices_items WHERE invoices_items.products=products.id ORDER BY date DESC LIMIT 1) AS lastInvoice
                            FROM categories RIGHT JOIN (clients RIGHT JOIN products ON clients.id=products.clients) ON categories.id=products.categories
                            WHERE products.canceled='false' AND ((products.renewable='a'  AND products.month='" . $d1['mon'] . "') OR (products.renewable='m' OR products.renewable='r' OR products.renewable='o'))";
                        $_sql .= " AND products.companies='" . $_company['id'] . "'";

                        if ( isset( $_POST['sortClients'] ) )
                        {
                            $_sql .= " AND products.clients='" . $_POST['sortClients'] . "'";
                        }

                        $_sql .= " ORDER BY products.date";

                        // echo $_sql;

                        $_totProd = $db->numRows( $_sql );

                        if ( $_totProd > 0 )
                        {
                            $res = $db->select( $_sql );

                            foreach ( $res['data'] as $prod )
                            {
                                $add = false;
                                //Difference between product set up date and posted date
                                $_prodMonthDiff = diff_months( $_POST['date'], $prod['date'] );
                                $_prodYearDiff = diff_years( $_POST['date'], $prod['date'] );

                                //Difference between last invoice date and posted date
                                $_invoiceMonthDiff = diff_months( $_POST['date'], $prod['lastInvoice'] );
                                $_invoiceYearDiff = diff_years( $_POST['date'], $prod['lastInvoice'] );

                                if ( $prod['renewable'] == 'a' )
                                {

                                    if ( empty( $prod['lastInvoice'] ) )
                                    {

                                        $add = true;

                                    }
                                    else
                                    {

                                        if (  ( $_invoiceYearDiff == 1 ) && ( $_prodMonthDiff == 0 ) )
                                        {
                                            $add = true;
                                        }

                                    }

                                }
                                elseif ( $prod['renewable'] == 'm' )
                                {

//if the difference between the posted date and last invoice is 1
                                    if ( $_invoiceMonthDiff == 1 )
                                    {
                                        $add = true;
                                    }

                                }
                                elseif ( $prod['renewable'] == 'r' )
                                {
                                    $add = true;
                                }
                                elseif ( $prod['renewable'] == 'o' && $prod['lastInvoice'] == '' )
                                {
                                    $add = true;
                                }

                                if ( $add )
                                {
                                    $_temp = $prod;

                                    array_push( $_tmpProducts, $_temp );
                                }

                            }

                        }
                        else
                        {
                            $_tmpProducts = [];
                        }

                        $products[$_count] = $_company;
                        $products[$_count]['items'] = $_tmpProducts;
                        $_count++;

                    }

                    if ( count( $products ) > 0 )
                    {
                        $_results['data'] = $products;

                        $_results['records'] = count( $products );
                    }
                    else
                    {
                        $_results['data'] = 'false';
                        $_results['message'] = 'no products';
                    }

                    $_results['query'] = $_sql;
                }

            }

            return $_results;
        }

    }

    public function save()
    {
        if ( isset( $_POST['view_type'] ) )
        {
            $_date = getdate( date( strtotime( $_POST['date'] ) ) );

            global $db;

            $vars = [
                "companies" => $_POST['companies'],
                "year" => $_date['year'],
                "month" => $_date['mon'],
                "description" => ( isset( $_POST['description'] ) ? $_POST['description'] : null ),
                "price" => $_POST['price'],
                "renewable" => $_POST['renewable'],
            ];

            if ( $_POST['view_type'] == 'create' )
            {
                $vars['clients'] = $_POST['clients'];
                $vars['categories'] = $_POST['categories'];
                $vars['canceled'] = 'false';
                $vars['canceled_date'] = null;
                $vars['date'] = $_POST['date'];

                $res = $db->insertData( $this->table, $vars );

                if ( $res )
                {
                    $_results['data'] = 'true';
                    $_results['message'] = 'added';
                    $_results['id'] = $db->lastInsertId();

                    $_vars = [

                        "clients" => $_POST['clients'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => "created",
                        "data" => json_encode( $vars ),
                    ];

                    performAction( 'manager', 'updateLog', ['logs', $_vars] );
                }
                elseif ( !$res )
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }

            }
            elseif ( $_POST['view_type'] == "save" )
            {

                $res = $db->update( $this->table, $vars, $_POST['id'] );

                if ( $res )
                {
                    $_vars = [
                        "clients" => $_POST['clients'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => "updated",
                        "data" => json_encode( $vars ),
                    ];

                    performAction( 'manager', 'updateLog', ['logs', $_vars] );

                    $_results['data'] = 'true';
                    $_results['message'] = 'record updated';
                }
                elseif ( !$res )
                {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }

            }

            return $_results;
        }

    }

    public function update()
    {
        if ( $_POST['state'] != null )
        {
            if ( $_POST['state'] == 'cancel' || $_POST['state'] == 'enable' )
            {
                global $db;
                $_res = $db->select( "SELECT clients FROM " . $this->table . " WHERE id='" . $_POST['id'] . "';", 'true' );

                if ( $_POST['state'] == 'cancel' )
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='true', canceled_date='" . current_date() . "' WHERE id='" . $_POST['id'] . "';";
                }
                elseif ( $_POST['state'] == 'enable' )
                {
                    $_sql = "UPDATE " . $this->table . " SET canceled='false', canceled_date=null WHERE id='" . $_POST['id'] . "';";
                }

                $res = $db->update( $_sql );

                if ( $res )
                {
                    $_results['data'] = "true";
                    $_results['message'] = "state updated";

                    $_vars = [
                        "clients" => $_res['data']['clients'],
                        "date" => current_dateTime(),
                        "users" => $_SESSION['user'],
                        "affected_table" => $this->table,
                        "action" => $_POST['state'],
                        "data" => json_encode( ["id" => $_POST['id']] ),
                    ];

                    performAction( 'manager', 'updateLog', ['logs', $_vars] );
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

    public function view()
    {
        if ( isset( $_POST['view_type'] ) )
        {
            global $db;

            if ( $_POST['view_type'] == "view" )
            {
                $_whereArray = [];
                $_searchArray = [];

                if ( $_POST['state'] == 'true' || $_POST['state'] == 'false' )
                {
                    array_push( $_whereArray, ['column' => 'products.canceled', 'value' => $_POST['state'], 'operator' => '=', 'selector' => 'AND'] );
                }

                if ( isset( $_POST['client'] ) )
                {
                    array_push( $_whereArray, ['column' => 'products.clients', 'value' => $_POST['client'], 'operator' => '=', 'selector' => 'AND'] );
                }

                if ( isset( $_POST['sortCompany'] ) && ( $_POST['sortCompany'] != '' ) && ( $_POST['sortCompany'] != '0' ) )
                {
                    array_push( $_whereArray, ['column' => 'products.companies', 'value' => $_POST['sortCompany'], 'operator' => '=', 'selector' => 'AND'] );
                }

                if ( isset( $_POST['sortSearch'] ) && ( $_POST['sortSearch'] != '' ) )
                {
                    array_push( $_searchArray, ['column' => 'products.description', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR'] );
                    array_push( $_searchArray, ['column' => 'categories.category', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR'] );
                    array_push( $_searchArray, ['column' => 'clients.business', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR'] );
                    array_push( $_searchArray, ['column' => 'products.date', 'value' => "%" . $_POST['sortSearch'] . "%", 'operator' => ' LIKE ', 'selector' => 'OR'] );
                }

                if ( count( $_whereArray ) > 0 )
                {
                    $_where = " WHERE ";

                    foreach ( $_whereArray as $_tmp )
                    {
                        $_where .= " " . $_tmp['column'] . $_tmp['operator'] . "'" . $_tmp['value'] . "' " . $_tmp['selector'];
                    }

                    if ( count( $_searchArray ) == 0 )
                    {
                        $_where = rtrim( $_where, "AND" );
                        $_where = rtrim( $_where, "OR" );
                    }

                }

                if ( count( $_searchArray ) > 0 )
                {
                    if ( count( $_whereArray ) == 0 )
                    {
                        $_where = " WHERE ";
                    }

                    $_where .= " ( ";

                    foreach ( $_searchArray as $_tmp )
                    {
                        $_where .= " " . $_tmp['column'] . $_tmp['operator'] . "'" . $_tmp['value'] . "' " . $_tmp['selector'];
                    }

                    $_where = rtrim( $_where, "AND" );
                    $_where = rtrim( $_where, "OR" );

                    $_where .= " ) ";
                }

                $_sql = "SELECT products.*, clients.business AS clientName, categories.category AS categoryName, (SELECT date FROM invoices_items WHERE invoices_items.products=products.id ORDER BY date DESC LIMIT 1) AS lastInvoice FROM categories RIGHT JOIN (clients RIGHT JOIN products ON clients.id=products.clients) ON categories.id=products.categories";

                if ( isset( $_where ) )
                {
                    $_sql = $_sql . $_where;
                }

                $_total = $db->numRows( $_sql );

                if ( isset( $_POST['sort'] ) )
                {
                    $_sql .= " ORDER BY " . $_POST['sort'] . (  ( isset( $_POST['sortOrder'] ) ) ? " " . $_POST['sortOrder'] : '' );
                }

                if ( isset( $_POST['page'] ) && isset( $_POST['records'] ) )
                {
                    $_limitOffset = (  ( $_POST['page'] - 1 ) * $_POST['records'] );

                    $_sql .= " LIMIT  " . $_limitOffset . ", " . $_POST['records'];
                }

                $_sql .= ';';

                $_results = $db->select( $_sql );
                $_results['records'] = $_total;

                if ( isset( $_POST['client'] ) )
                {
                    $res = $db->select( "SELECT * FROM clients WHERE id = '" . $_POST['client'] . "';", 'true' );

                    $_results['business'] = $res['data']['business'];
                }

                $_results['query'] = $_sql;
            }
            elseif ( $_POST['view_type'] == 'due' )
            {
                $_sql = "SELECT
                    products.*,
                    clients.business AS clientName,
                    categories.category AS categoryName,
                    (SELECT date FROM invoices_items WHERE invoices_items.products=products.id ORDER BY date DESC LIMIT 1) AS lastInvoice
                    FROM categories RIGHT JOIN (clients RIGHT JOIN products ON clients.id=products.clients) ON categories.id=products.categories
                    WHERE products.canceled='false' AND ((products.renewable='a' ";

                $products = [];

                $d1 = getdate( date( strtotime( $_POST['date'] ) ) );
                $d2 = getdate( date( strtotime( current_date() ) ) );

                if ( !isset( $_POST['invoice_type'] ) )
                {
                    $_sql .= " AND products.month='" . $d1['mon'] . "' ";
                }
                elseif ( isset( $_POST['invoice_type'] ) )
                {
                    switch ( $_POST['invoice_type'] )
                    {
                        case "due":
                            $_sql .= " AND products.month='" . $d1['mon'] . "' ";
                            break;
                        case "now":
                            $_sql .= " AND products.month='" . $d2['mon'] . "' ";
                            break;
                        default:
                            break;
                    }

                }

                $_sql .= " ) OR (products.renewable='m' OR products.renewable='r' OR products.renewable='o'))";

                if ( isset( $_POST['sortClients'] ) )
                {
                    $_sql .= " AND products.clients='" . $_POST['sortClients'] . "'";
                }

                if ( isset( $_POST['sortCompany'] ) && ( $_POST['sortCompany'] != '0' ) )
                {
                    $_sql .= " AND products.companies='" . $_POST['sortCompany'] . "'";
                }

                if ( isset( $_POST['sort'] ) )
                {
                    $_sql .= " ORDER BY " . $_POST['sort'] . (  ( isset( $_POST['sortOrder'] ) ) ? " " . $_POST['sortOrder'] : '' );
                }

                // echo $_sql;

                $_totProd = $db->numRows( $_sql );

                if ( $_totProd > 0 )
                {
                    $res = $db->select( $_sql );

                    foreach ( $res['data'] as $prod )
                    {
                        $add = false;
                        //Difference between product set up date and posted date
                        $_prodMonthDiff = diff_months( $_POST['date'], $prod['date'] );
                        $_prodYearDiff = diff_years( $_POST['date'], $prod['date'] );

                        //Difference between last invoice date and posted date
                        $_invoiceMonthDiff = diff_months( $_POST['date'], $prod['lastInvoice'] );
                        $_invoiceYearDiff = diff_years( $_POST['date'], $prod['lastInvoice'] );

                        if ( $prod['renewable'] == 'a' )
                        {

                            if ( empty( $prod['lastInvoice'] ) )
                            {

                                if ( !isset( $_POST['invoice_type'] ) )
                                {

                                    if (  ( $_invoiceYearDiff >= 1 ) && ( $_prodMonthDiff == 0 ) )
                                    {
                                        $add = true;
                                    }

                                }
                                elseif ( isset( $_POST['invoice_type'] ) )
                                {

                                    if ( $_POST['invoice_type'] == "now" )
                                    {

                                        if (  ( $_invoiceYearDiff >= 0 ) && ( $_prodMonthDiff == 0 ) )
                                        {
                                            $add = true;
                                        }

                                    }
                                    elseif ( $_POST['invoice_type'] == "due" )
                                    {

                                        if (  ( $_invoiceYearDiff >= 1 ) && ( $_prodMonthDiff == 0 ) )
                                        {
                                            $add = true;
                                        }

                                    }

                                }

                            }
                            else
                            {

                                if (  ( $_invoiceYearDiff == 1 ) && ( $_prodMonthDiff == 0 ) )
                                {
                                    $add = true;
                                }

                            }

                        }
                        elseif ( $prod['renewable'] == 'm' )
                        {

//if the difference between the posted date and last invoice is 1
                            if ( $_invoiceMonthDiff == 1 )
                            {
                                $add = true;
                            }

                        }
                        elseif ( $prod['renewable'] == 'r' )
                        {
                            $add = true;
                        }
                        elseif ( $prod['renewable'] == 'o' && $prod['lastInvoice'] == '' )
                        {
                            $add = true;
                        }

                        if ( $add )
                        {
                            $_temp = $prod;

                            array_push( $products, $_temp );
                        }

                    }

                }

                if ( count( $products ) > 0 )
                {
                    $_results['data'] = $products;

                    $_results['records'] = count( $products );
                }
                else
                {
                    $_results['data'] = 'false';
                    $_results['message'] = 'no products';
                }

                $_results['query'] = $_sql;
            }
            elseif ( $_POST['view_type'] == 'edit' )
            {
                $_sql = "SELECT * FROM products WHERE id='" . $_POST['id'] . "';";
                $_results = $db->select( $_sql, 'true' );
            }

            return $_results;
        }

    }

}
