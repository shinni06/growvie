<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getEcoMultiplier() {
    if (isset($_SESSION['double_eco_expires'])) {
        if (time() < $_SESSION['double_eco_expires']) {
            return 2;
        } else {
            unset($_SESSION['double_eco_expires']);
        }
    }
    return 1;
}

function activateDoubleEco() {
    $_SESSION['double_eco_expires'] = time() + 3600;
}

function getDoubleEcoRemainingTime() {
    if (isset($_SESSION['double_eco_expires'])) {
        return max(0, $_SESSION['double_eco_expires'] - time());
    }
    return 0;
}

function isDoubleEcoActive() {
    return getEcoMultiplier() === 2;
}
