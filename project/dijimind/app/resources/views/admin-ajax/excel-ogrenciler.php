<?php  
	use App\Exports\OgrencilerExport;
	use Maatwebsite\Excel\Facades\Excel;
 //   mkdir("storage/app/excel-export",true);
    $user = u();
    $path = "excel/excel-export/{$user->alias}/dijimind_ogrenciler". date("d-m-Y H-i") . ".xlsx";
    $content = Excel::store(new OgrencilerExport,  $path,'local');
    yonlendir("/storage/app/".$path);