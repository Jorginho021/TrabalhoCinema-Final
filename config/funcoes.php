<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function esc($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function flash($message)
{
    $_SESSION['flash_message'] = $message;
}

function getFlashMessage()
{
    if (!empty($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

function isLogged()
{
    return !empty($_SESSION['usuario_id']);
}

function isAdmin()
{
    return !empty($_SESSION['usuario_role']) && $_SESSION['usuario_role'] === 'admin';
}

function formatDate($date)
{
    return date('d/m/Y H:i', strtotime($date));
}
