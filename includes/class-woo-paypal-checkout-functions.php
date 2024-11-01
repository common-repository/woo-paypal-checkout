<?php

function paltechwpdevwpc_has_active_session() {
    if (!WC()->session) {
        return false;
    }
    $paltechwpdevwpc_order_id = WC()->session->get('paltechwpdevwpc_order_id');
    if (isset($paltechwpdevwpc_order_id) && !empty($paltechwpdevwpc_order_id)) {
        return true;
    }
}

function paltechwpdevwpc_clear_session_data() {
    if (!WC()->session) {
        return false;
    }
    WC()->session->set('paltechwpdevwpc_order_details', null);
    WC()->session->set('paltechwpdevwpc_order_id', null);
    unset(WC()->session->paltechwpdevwpc_order_id);
    unset(WC()->session->paltechwpdevwpc_order_details);
}

function paltechwpdevwpc_number_format($price) {
    $decimals = 2;

    if (!paltechwpdevwpc_currency_has_decimals(get_woocommerce_currency())) {
        $decimals = 0;
    }

    return number_format($price, $decimals, '.', '');
}

function paltechwpdevwpc_round($price) {
    $precision = 2;

    if (!paltechwpdevwpc_currency_has_decimals(get_woocommerce_currency())) {
        $precision = 0;
    }

    return round($price, $precision);
}

function paltechwpdevwpc_currency_has_decimals($currency) {
    if (in_array($currency, array('HUF', 'JPY', 'TWD'))) {
        return false;
    }

    return true;
}

function paltechwpdevwpc_remove_empty_key($data) {
    $original = $data;
    $data = array_filter($data);
    $data = array_map(function ($e) {
        return is_array($e) ? paltechwpdevwpc_remove_empty_key($e) : $e;
    }, $data);
    return $original === $data ? $data : paltechwpdevwpc_remove_empty_key($data);
}

function paltechwpdevwpc_limit_length($string, $limit = 127) {
    $str_limit = $limit - 3;
    if (function_exists('mb_strimwidth')) {
        if (mb_strlen($string) > $limit) {
            $string = mb_strimwidth($string, 0, $str_limit) . '...';
        }
    } else {
        if (strlen($string) > $limit) {
            $string = substr($string, 0, $str_limit) . '...';
        }
    }
    return $string;
}
