<?php

function getCart() {
    if (isset($_COOKIE['cart'])) {
        return json_decode($_COOKIE['cart'], true);
    }
    return [];
}

function saveCart($cart) {
    setcookie(
        'cart',
        json_encode($cart),
        time() + (60 * 60 * 24 * 7), // 7 hari
        '/'
    );
}
