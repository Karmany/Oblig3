<?php
// Destory session and redirect to index.php
session_start();
session_destroy();
header("Location: index.php");
