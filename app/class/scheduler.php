<?php

class scheduler
{
    private $table = "scheduler";

    public function view()
    {

        if ( isset( $_POST['view_type'] ) )
        {
            global $db;

            if ( $_POST['view_type'] == "view" )
            {

                if ( isset( $_POST['date'] ) && ( !empty( $_POST['date'] ) ) )
                {
                    $_month = get_month( $_POST['date'] );
                    $_year = get_year( $_POST['date'] );
                    $_start = date( 'Y-m-d', strtotime( "-1 months", strtotime( $_POST['date'] ) ) );
                    $_end = date( 'Y-m-t', strtotime( "+1 months", strtotime( $_POST['date'] ) ) );
                    $_sql = "SELECT start, CONCAT(end,'T23:59:00Z') as end, allDay,
                    (SELECT color FROM users WHERE id=jobs.users) AS color,
                    (SELECT business FROM clients WHERE id=jobs.clients) as title
                     FROM jobs
                     WHERE (start BETWEEN '" . $_start . "' AND '" . $_end . "')
                     AND canceled='false'
                     AND categories='120';";

                    $_results = $db->select( $_sql );
                    $_results['query']=$_sql;
                }
                else
                {
                    $_results['data'] = 'false';
                    $_results['message'] = 'no data passed';
                }

            }
            elseif ( $_POST['view_type'] == 'user' )
            {
                $_sql = "SELECT
                jobs.*,
                clients.business
                FROM clients RIGHT JOIN jobs ON clients.id=jobs.clients
                WHERE jobs.canceled='false' AND jobs.complete='false' AND jobs.users=" . $_SESSION['user'] . ";";

                $_results = $db->select( $_sql );
            }

            return $_results;
        }

    }

}
