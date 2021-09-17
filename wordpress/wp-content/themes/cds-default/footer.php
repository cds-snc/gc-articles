<?php

declare(strict_types=1);

$lang = get_active_language();
echo '<div style="display:none;" id="version" style="margin-top:30px;">'._S_VERSION.'</div>';
require_once 'footer_'.$lang.'.php';
