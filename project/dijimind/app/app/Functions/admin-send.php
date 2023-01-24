<?php function adminSend($subject,$text) {
    @mailsend("umit.tunc@truncgil.com",$subject,$text);
    @mailsend("servet@dijimindakademi.com",$subject,$text);
} ?>