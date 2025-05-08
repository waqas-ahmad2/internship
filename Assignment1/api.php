<?php

require_once 'server.php';

function fetch_api($con){

    echo fetch_student_data($con);
}
fetch_api($con)

?>