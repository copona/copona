<?php

$success = empty($success) ? false : $success;
$message = empty($message) ? false : $message;
$meta = empty($meta) ? [] : $meta;

echo json_encode( ['success' => $success, 'message' => $message, 'meta' => $meta] );
