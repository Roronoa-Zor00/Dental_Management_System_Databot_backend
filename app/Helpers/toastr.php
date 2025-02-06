<?php
// My common functions
function Toastr($message,$message_type='error')
{
//    dd($message);
    $type_type = ['success','error','info','warning'];
    $message_type =($message_type=='danger')?"error":$message_type;
//    $postion = ['top-right','top-center','top-left','bottom-right','bottom-center','bottom-left'];
//    $msg_html ="<div class='toastr error-toast'><button class='toast-close-button' role='button'></button><div class='toast-message'>".$message."</div></div>";
    if($message!='')
        $javascript_alert = "toastr.$message_type('$message');";
    return $javascript_alert;

}

