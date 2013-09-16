<?php
if(!session_id()) {
    session_start();
}
ob_start();
?>
asdf
<?php
$content = ob_get_clean();
require_once 'layout.php';