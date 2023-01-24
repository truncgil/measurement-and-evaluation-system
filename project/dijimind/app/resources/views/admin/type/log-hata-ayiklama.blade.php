<div class="content">
    <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title"><i class="fa fa-{{$c->icon}}"></i> {{e2($c->title)}}</h3>
            </div>
            <div class="block-content">
                <?php 
                $logs = glob("storage/logs/*.log");
                if(getisset("delete")) {
                    bilgi("{$logs[get("delete")]} logları silindi");
                    unlink($logs[get("delete")]);
                    unset($logs[get("delete")]);
                    yonlendir("/admin/types/log-hata-ayiklama");
                    
                }
                foreach($logs AS $index => $fileName) {
                     ?>
                     <a href="?index={{$index}}" class="btn btn-<?php echo getesit("index",$index) ? "danger" : "primary"; ?> mb-5">{{$fileName}}</a>
                     <?php 
                }
                ?>

                <?php if(getisset("index")) {
                        $fileContent = file_get_contents($logs[get("index")]);
                        try {
                            $fileContent = substr($fileContent,strlen($fileContent)-1000000,1000000);
                        } catch (\Throwable $th) {
                            throw $th;
                        }
                        

                     ?>
                        <textarea name="" id="" cols="30" rows="30" class="form-control">
                            <?php echo $fileContent; ?>
                        </textarea>
                        <a href="?delete={{$index}}" class="btn btn-danger" teyit="Bu işlem geri alınamaz">Bu log kaydını temizle</a>
                     <?php 
                } ?>
            </div>

            

        </div>

    </div>
</div>