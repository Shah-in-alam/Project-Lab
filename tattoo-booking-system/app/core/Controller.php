<?php
protected function redirect($path)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    header("Location: $path");
    exit();
}