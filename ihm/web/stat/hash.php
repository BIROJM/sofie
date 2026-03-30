<?php

//print_r(hash_algos());

echo base64_encode(hash("sha256", 'eugene', true));
?>