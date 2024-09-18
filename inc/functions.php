<?php
function check($page, $numOfPages)
{
    if ($page > $numOfPages || $page < 1) {
        return false;
    } else {
        return true;
    }
}