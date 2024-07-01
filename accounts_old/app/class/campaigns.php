<?php

class campaigns
{
    private $table = "ad_campaigns";

    public function save()
    {
        if (isset($_POST['view_type'])) {
            global $db;

            $vars = [
                "name" => $_POST['name'],
            ];

            if ($_POST['view_type'] == 'create') {
                $vars["created"] = current_date();
                $res = $db->insertData($this->table, $vars);

                if ($res) {
                    $_results['data'] = 'true';
                    $_results['message'] = 'added';
                    performAction('manager', 'updateAdLog', [$db->lastInsertId(), $_SESSION['user'], 'inserted', $vars]);
                } elseif (!$res) {
                    $_results['data']['data'] = 'false';
                    $_results['message'] = $db->getError();
                }

            } elseif ($_POST['view_type'] == "save") {
                $res = $db->update($this->table, $vars, $_POST['id']);

                if ($res) {
                    performAction('manager', 'updateAdLog', [$_POST['id'], $_SESSION['user'], 'updated', $vars]);
                    $_results['data'] = 'true';
                    $_results['message'] = 'record updated';
                } elseif (!$res) {
                    $_results['data'] = 'false';
                    $_results['message'] = $db->getError();
                }

            }

            return $_results;
        }

    }

    public function view()
    {
        if (isset($_POST['view_type'])) {
            global $db;

            if ($_POST['view_type'] == "view") {
                $_results = $db->select("SELECT *, (SELECT (Sum(credit) - Sum(debit)) AS adsCredit FROM ad_transactions WHERE campaigns=ad_campaigns.id) AS credit FROM ad_campaigns;");
            } elseif ($_POST['view_type'] == "edit") {
                $_results = $db->select("SELECT * FROM ad_campaigns WHERE id='" . $_POST['id'] . "';", 'true');
            } elseif ($_POST['view_type'] == "retrieve") {
                $_results = $db->select("SELECT id, category, price FROM categories WHERE link='" . $_POST['link'] . "' AND canceled='false' ORDER BY category ASC;");
                $_results['nologin'] = 'true';
            }

            return $_results;
        }

    }

}
