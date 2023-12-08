<?php require_once "./connect.php"; ?>
<?php
    if(isset($_POST['ajax'])){
        try {
            $sql = "SELECT * FROM warehouse";
            $stmt = $conn_sql->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            // if no table yet
            $data = [['No data available at the moment!']];
            $headers = ['data'];
            echo json_encode(['header' => $headers, 'data' => $data]);
            exit;
        }
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
        if(!isset($res) || !$res){
            $data = [['No data available at the moment!']];
            $headers = ['data'];
        }else{
            $headers = array_keys($res[0]);
            foreach($res as $d){
                $temp = [];
                foreach($d as $k => $v){
                    $temp[] = $v;
                }
                $data[] = $temp;
            }
        }
        echo json_encode(['header' => $headers, 'data' => $data]);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require_once "./head.php"; ?>
    </head>
    <body>
        <!-- <div class="flex"> -->
        <?php require_once "./sidenav.php"; ?>
        <!-- <div class="grid grid-cols-12"> -->
        <div class="ml-60 px-8 py-3">
            <div class="flex w-full h-24 rounded-lg shadow-lg items-center justify-center mb-8">
                <h1 class="text-center uppercase font-bold text-3xl">Data Warehouse</h1>
            </div>
            <div class="grid grid-cols-8 gap-8">
                <div class="col-span-8" id="input-form">
                    <div class="flex w-full h-32 rounded-lg shadow-xl items-center justify-center py-3 pl-5 pr-8">
                        <h1 class="text-center uppercase font-bold text-md">Input file .csv</h1>
                        <input
                            class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary"
                            type="file"
                            id="form-warehouse" />
                    </div>
                </div>
                <div class="col-span-8">
                    <div class="w-full h-full rounded-lg shadow-xl items-center justify-center p-8">
                        <div id="datatable" data-te-fixed-header="true" data-te-clickable-rows="true"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
    </body>
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/tw-elements.umd.min.js"></script>
    <script>
        $(document).ready(function(){
            let instance;
            function ajaxCall(){
                $.ajax({
                    type: "POST",
                    data: {ajax: "hello"},
                    success: function(res){
                        res = JSON.parse(res);
                        console.log(res);
                        if(res.header[0] != "data"){
                            $("#input-form").attr("hidden",true);
                        }
                        let data = {
                                columns: res.header,
                                rows: res.data,
                            };
                        if(!instance){
                                instance = new te.Datatable(document.getElementById('datatable'), data)
                            }else{
                                instance.update(data);
                            }
                    }
                })
            }
            ajaxCall();
            $("#form-warehouse").on("change",function(){
                let file = $(this).prop("files")[0];
                let formData = new FormData();
                formData.append("file",file);
                formData.append("upload","warehouse");
                $.ajax({
                    url : './upload.php',
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res){
                        console.log(res);
                        $("#input-form").attr("hidden",true);
                        res = JSON.parse(res);
                        let data = {
                                columns: res.header,
                                rows: res.data,
                            };
                        if(!instance){
                                instance = new te.Datatable(document.getElementById('datatable'), data)
                            }else{
                                instance.update(data);
                            }
                    }
                })
            })
        });
    </script>
</html>