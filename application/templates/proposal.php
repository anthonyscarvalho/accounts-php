<!DOCTYPE html>
<html ng-app="invoiceSystem">

<head>
    <title>Accounts Application</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/assets/css/fonts.css" rel="stylesheet" type="text/css">
    <link href="/assets/css/proposal.css" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/ico" href="/media/main/favicon.ico?v=2">
</head>

<body>
    <div class="container">
        <div class="well">
            <?php
            echo '<p>Hi ' . $_contact['data']['name'] . ' ' . $_contact['data']['surname'] . '</p>';
            echo '<p>Attached please find our proposal generated on: ' . $_quotation['data']['creation_date'] . '</p>';

            if (($_timeLeft < 30) && ($_quotation['data']['accepted'] == 'false')) {
                echo '<p>This proposal is still valid for another ' . $_timeLeft . ' days.</p>';
                echo '<p>In order to accept this proposal with us please click on the relevant link below, if you would like any amendments done to this proposal before you accept please contact us with any requests.</p>';
            } elseif ($_quotation['data']['accepted'] == 'true') {
                echo '<p>This porposal has already been accepted.</p>';
                echo '<p>Please scroll to the bottom of the page to view who accepted the quote.</p>';
            } else {
                echo '<p>This quotation is no longer valid, please contact us to create a new one.</p>';
            }


            echo '<p>Please note that once this has been accepted the terms will be final and will only be changed by accepting a new proposal!</p>';

            ?>
        </div>
    </div>
    <hr />
    <div class="container">
        <div class="well proposal">
            <?php
            echo $_company['data']['invoice_header'];
            echo '<p><strong>Company:</strong>&nbsp;&nbsp;' . $_client['data']['business'] . '</p>';
            echo '<p><strong>Date:</strong>&nbsp;&nbsp;' . current_date() . '</p>';
            echo $_quotation['data']['scope'];

            // echo $_quotation['data']['signature'];

            # Products
            $_tableHead = '<table class="table table-bordered" style="background-color:#ccc;">'
                . '<tr style="font-weight:bold; text-align:center; background-color:#efefef;">'
                . '<td style="width:60px;">Date</td>'
                . '<td>Item</td>'
                . '<td style="width:150px;">Price</td>'
                . '</tr>';
            $_a_items_html = $_tableHead;
            $_m_items_html = $_tableHead;
            $_o_items_html = $_tableHead;

            $o_subtotal = 0;
            $m_subtotal = 0;
            $a_subtotal = 0;

            $o_count = 0;
            $m_count = 0;
            $a_count = 0;

            $_products = json_decode($_quotation['data']['products'], true);

            foreach ($_products as $items) {
                if ($items['renewable'] == 'o' || $items['renewable'] == 'r') {
                    $_o_items_html .= '<tr style="background-color:#fff;">'
                        . '<td>' . $items['date'] . '</td>'
                        . '<td>' . $items['categoryName'] . '</td>'
                        . '<td>R ' . number_format($items['price'], 2, '.', ' ') . '</td>'
                        . '</tr>';

                    if ((isset($items['description'])) && ($items['description'] != "")) {
                        $_o_items_html .= '<tr style="background-color:#fff;"><td></td><td colspan="2">' . $items['description'] . '</td></tr>';
                    }

                    $o_subtotal += $items['price'];
                    $o_count++;
                }

                if ($items['renewable'] == 'a') {
                    $_a_items_html .= '<tr style="background-color:#fff;">'
                        . '<td>' . $items['date'] . '</td>'
                        . '<td>' . $items['categoryName'] . '</td>'
                        . '<td>R ' . number_format($items['price'], 2, '.', ' ') . '</td>'
                        . '</tr>';

                    if ($items['description'] != "") {
                        $_a_items_html .= '<tr style="background-color:#fff;"><td></td><td colspan="2">' . $items['description'] . '</td></tr>';
                    }

                    $a_subtotal += $items['price'];
                    $a_count++;
                }

                if ($items['renewable'] == 'm') {
                    $_m_items_html .= '<tr style="background-color:#fff;">'
                        . '<td>' . $items['date'] . '</td>'
                        . '<td>' . $items['categoryName'] . '</td>'
                        . '<td>R ' . number_format($items['price'], 2, '.', ' ') . '</td>'
                        . '</tr>';

                    if ($items['description'] != "") {
                        $_m_items_html .= '<tr style="background-color:#fff;"><td></td><td colspan="2">' . $items['description'] . '</td></tr>';
                    }

                    $m_subtotal += $items['price'];
                    $m_count++;
                }
            }

            $items_html = '';

            if ($a_count > 0) {
                $items_html .= '<h2>Annual Fees</h2>' . $_a_items_html . '</table>';
            }

            if ($m_count > 0) {
                $items_html .= '<h2>Monthly Fees</h2>' . $_m_items_html . '</table>';
            }

            if ($o_count > 0) {
                $items_html .= '<h2>Once Off Fees</h2>' . $_o_items_html . '</table>';
            }

            # Set up the total table

            $items_html .= '<h2>Total Costs</h2><table  class="table table-bordered" style="background-color:#ccc;">';

            if (!empty($_quotation['data']['deposit'])) {
                $items_html .= '<tr style="background-color: #efefef;">'
                    . '<td style="text-align:right;">Deposit Required</td>'
                    . '<td style="width:150px;">R ' . number_format($_quotation['data']['deposit'], 2, '.', ' ') . '</td>'
                    . '</tr>';
            }

            $items_html .= '<tr height="30" style="background-color: #010180; color:#fff;">'
                . '<td style="text-align:right;">Proposal Total</td>'
                . '<td style="width:150px;">R ' . number_format(($a_subtotal + $m_subtotal + $o_subtotal), 2, '.', ' ') . '</td>'
                . '</tr>';

            $items_html .= '</table>';
            echo $items_html;

            echo $_quotation['data']['signature'];

            echo $_quotation['data']['content'];

            echo $_quotation['data']['annexure'];
            ?>
        </div>
    </div>
    <hr />
    <div class="container">
        <div class="well">
            <p>We hope that the above proposal is adequate.</p>
            <p>Should you have any queries reagrding the above proposal please contact us before accepting the quote.</p>
            <p>Once the quote has been accepted it is final, any changes required after acceptance can only be done with a new proposal.</p>
            <p>This proposal will act as a contract between <strong><em><?php echo $_client['data']['business'] ?></em></strong> and <strong><em><?php echo $_company['data']['company'] ?></em></strong> for a period as specified above and further renewable as specified above.</p>
            <?php

            if ($_accepted == false) {
            ?>
                <form action="" method="post" id="proposal">
                    <p>To accept or deny the above proposal please click on the relevant links below:</p>
                    <div class="row">
                        <div class="col-md-2 col-md-offset-3">
                            <button class="btn btn-success btn-block" type="submit" name="submit" value="accept"><span class="fa fa-check"></span> Accept</button>
                        </div>
                        <div class="col-md-2 col-md-offset-2">
                            <button class="btn btn-danger btn-block" type="submit" name="submit" value="deny"><span class="fa fa-times"></span> Deny</button>
                        </div>
                    </div>
                </form>
            <?php }

            ?>
        </div>
    </div>
</body>

</html>

<?php

// print_r($_quotation);
