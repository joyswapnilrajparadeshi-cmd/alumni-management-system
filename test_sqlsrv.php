<?php
// Check if the SQL Server extensions are loaded
if (extension_loaded("sqlsrv")) {
    echo "sqlsrv extension is loaded successfully.";
} else {
    echo "sqlsrv extension is not loaded.";
}

if (extension_loaded("pdo_sqlsrv")) {
    echo "<br>pdo_sqlsrv extension is loaded successfully.";
} else {
    echo "<br>pdo_sqlsrv extension is not loaded.";
}
?>
