<?php
// load up global things
require_once 'includeAll.php';

// destroy the session (clears all session data)
session_destroy();
header("Location:".INCLUDE_DIR);
?>