<?php 
set_time_limit(-1);
ini_set('max_execution_time', 30000000);
//mailsend("umitunc@msn.com","test","test");
//echo mailsend("umitreva@gmail.com","test","test");


//@mailsend("umit.tunc@truncgil.com","Cron Çalıştı","Cron çalıştı");
?>
@foreach (glob(base_path() . '/resources/views/cron/*.blade.php') as $file)

    @include('cron.' . basename(str_replace('.blade.php', '', $file)))
@endforeach