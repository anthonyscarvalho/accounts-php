<?php

function performAction($class, $action, $queryString = null) {
    $dispatch = new $class();

    return call_user_func_array([$dispatch, $action], $queryString);
}

//Custom helper classes

function highestVal($val) {

    if ($val == "") {
        return 0;
    } elseif ($val != "") {
        return $val;
    }
}

function extract_email_address($string) {

    foreach (preg_split('/\s/', $string) as $token) {
        $email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);

        if ($email !== false) {
            // $emails[] = $email;
            return $email;
        }
    }

    // return $emails;
}

function current_dateTime() {
    $date = getdate(date("U"));

    return $date['year'] . "-" . $date['mon'] . "-" . $date['mday'] . " " . $date['hours'] . ":" . $date['minutes'] . ":" . $date['seconds'];
}

function current_date() {
    $date = getdate(date("U"));

    return $date['year'] . "-" . $date['mon'] . "-" . $date['mday'];
}

function get_month($_date) {
    $date = getdate(date("U", strtotime($_date)));

    return $date['mon'];
}

function get_year($_date) {
    $date = getdate(date("U", strtotime($_date)));

    return $date['year'];
}

function current_year() {
    $date = getdate(date("U"));

    return $date['year'];
}

function current_month() {
    $date = getdate(date("U"));

    return $date['mon'];
}

function yesterday() {
    $date = getdate(date("U", time() - 60 * 60 * 24));

    return $date['year'] . "-" . $date['mon'] . "-" . $date['mday'];
}

function current_time() {
    $date = getdate(date("U"));

    return $date['hours'] . ":" . $date['minutes'] . ":" . $date['seconds'];
}

function diff_days($_date1, $_date2) {
    $date1 = new DateTime($_date1);
    $date2 = new DateTime($_date2);

    return $date2->diff($date1)->format("%a");
}

function diff_months($_date1, $_date2) {
    $d1 = getdate(strtotime($_date1));
    $d2 = getdate(strtotime($_date2));

    if ($d1['mon'] == '1' && $d2['mon'] == '12') {
        return '1';
    } else {

        return ($d1['mon'] - $d2['mon']);
    }
}

function diff_years($_date1, $_date2) {
    $d1 = getdate(strtotime($_date1));
    $d2 = getdate(strtotime($_date2));

    return ($d1['year'] - $d2['year']);
}

function createPDF($state, $data) {
    require_once ROOT . DS . 'assets' . DS . 'tcpdf' . DS . 'tcpdf.php';

    $pdf = new TCPDF();

    $pdf->setImageScale(1);
    $pdf->setPrintHeader(false);
    $pdf->setFooterData([0, 64, 0], [0, 64, 128]);
    $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->AddPage();

    if (isset($data['overlay'])) {

        if ($data['overlay'] == 'true') {
            $pdf->SetFillColor($data['overlayFill'][0], $data['overlayFill'][1], $data['overlayFill'][2]);
            $pdf->SetDrawColor($data['overlayDraw'][0], $data['overlayDraw'][1], $data['overlayDraw'][2]);
            $pdf->SetXY(0, 0);
            $pdf->SetTextColor(255);
            $pdf->SetLineWidth(0.750);
            $pdf->StartTransform();
            $pdf->Rotate(-35, 100, 225);
            $pdf->Cell(100, 10, strtoupper($data['overlayText']), 'TB', 0, 'C', '1');
            $pdf->StopTransform();
            $pdf->SetTextColor(0);
            $pdf->Ln(10);
        }
    }

    $count = 1;

    $totPage = count($data['page']);

    foreach ($data['page'] as $_page) {

        if (is_array($_page)) {

            foreach ($_page as $page => $value) {

                if ($page == 'overlayFill') {
                    $pdf->SetFillColor($value[0], $value[1], $value[2]);
                }

                if ($page == 'overlayDraw') {
                    $pdf->SetDrawColor($value[0], $value[1], $value[2]);
                }

                if ($page == 'overlayText') {
                    $pdf->SetXY(0, 0);
                    $pdf->SetTextColor(255);
                    $pdf->SetLineWidth(0.750);
                    $pdf->StartTransform();
                    $pdf->Rotate(-35, 100, 225);
                    $pdf->Cell(100, 10, strtoupper($value), 'TB', 0, 'C', '1');
                    $pdf->StopTransform();
                    $pdf->SetTextColor(0);
                    $pdf->Ln(10);
                }

                if ($page == 'body') {
                    $pdf->writeHTMLCell(0, 0, '', '', $value, 0, 1, 0, true, '', true);
                }
            }
        } else {
            $pdf->writeHTMLCell(0, 0, '', '', $_page, 0, 1, 0, true, '', true);
        }

        if ($count != $totPage) {
            $pdf->AddPage();
        }

        $count++;
    }

    if ($state == 'print') {
        $pdf->Output($data['pdfName'], 'I');
    } elseif ($state == 'email') {
        return $pdf->Output($data['pdfName'], 'S');
    }
}

function make_comparer() {
    // Normalize criteria up front so that the comparer finds everything tidy

    $criteria = func_get_args();

    foreach ($criteria as $index => $criterion) {
        $criteria[$index] = is_array($criterion) ? array_pad($criterion, 3, null) : [$criterion, SORT_ASC, null];
    }

    return function ($first, $second) use ($criteria) {

        foreach ($criteria as $criterion) {
            // How will we compare this round?

            list($column, $sortOrder, $projection) = $criterion;
            $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;

            // If a projection was defined project the values now

            if ($projection) {
                $lhs = call_user_func($projection, $first[$column]);
                $rhs = call_user_func($projection, $second[$column]);
            } else {
                $lhs = $first[$column];
                $rhs = $second[$column];
            }

            // Do the actual comparison; do not return if equal

            if ($lhs < $rhs) {
                return -1 * $sortOrder;
            } elseif ($lhs > $rhs) {
                return 1 * $sortOrder;
            }
        }

        return 0; // tiebreakers exhausted, so $first == $second
    };
}

if (!function_exists('array_column')) {
    /**

     * Returns the values from a single column of the input array, identified by

     * the $columnKey.

     *

     * Optionally, you may provide an $indexKey to index the values in the returned

     * array by the values from the $indexKey column in the input array.

     *

     * @param array $input A multi-dimensional array (record set) from which to pull

     *                     a column of values.

     * @param mixed $columnKey The column of values to return. This value may be the

     *                         integer key of the column you wish to retrieve, or it

     *                         may be the string key name for an associative array.

     * @param mixed $indexKey (Optional.) The column to use as the index/keys for

     *                        the returned array. This value may be the integer key

     *                        of the column, or it may be the string key name.

     * @return array

     */

    function array_column($input = null, $columnKey = null, $indexKey = null) {

        // Using func_get_args() in order to check for proper number of

        // parameters and trigger errors exactly as the built-in array_column()

        // does in PHP 5.5.

        $argc = func_num_args();
        $params = func_get_args();

        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);

            return null;
        }

        if (!is_array($params[0])) {
            trigger_error('array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING);

            return null;
        }

        if (!is_int($params[1]) && !is_float($params[1]) && !is_string($params[1]) && $params[1] !== null && !(is_object($params[1]) && method_exists($params[1], '__toString'))) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);

            return false;
        }

        if (isset($params[2]) && !is_int($params[2]) && !is_float($params[2]) && !is_string($params[2]) && !(is_object($params[2]) && method_exists($params[2], '__toString'))) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);

            return false;
        }

        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
        $paramsIndexKey = null;

        if (isset($params[2])) {

            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }

        $resultArray = [];

        foreach ($paramsInput as $row) {
            $key = $value = null;
            $keySet = $valueSet = false;

            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }

            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }

            if ($valueSet) {

                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }
        }

        return $resultArray;
    }
}

callHook();
