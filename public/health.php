<?php
// Page ultra-légère que Render peut vérifier très vite
http_response_code(200);
echo "OK - Service vivant";
exit;