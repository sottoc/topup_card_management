<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');	
	
	$reportbol = new reportbol();

	require_once('header.php');	
?>
<script language="javascript">
	$(document).ready(function(){
        //---- set background for active menu -----
        if(localStorage.getItem("current_page") != undefined){
            for(var i=0; i < $("#nav ul li").length; i++){
                if($($("#nav ul li")[i]).html() == localStorage.getItem("current_page")){
                    $($("#nav ul li")[i]).css("background", '#b12226');
                }
            }
        }
        $($("#nav ul li")[0]).css("background", '#2c2c2c');
		$($("#nav ul li")[1]).css("background", '#b12226');
		$($("#nav ul li")[2]).css("background", '#2c2c2c');
		$($("#nav ul li")[3]).css("background", '#2c2c2c');
		$($("#nav ul li")[4]).css("background", '#2c2c2c');
        //------------------ End -----------------

        $("#btn_browse").click(function(){
            $("#excel_file").trigger('click');
        });

        $("#excel_file").change(function(e){
            e.preventDefault();
            let reader = new FileReader();
            let file = e.target.files[0];
            if(file === undefined){
                return;
            }
            reader.onloadend = (e) => {
                var filename = file.name;
                var len = filename.length;
                var type = filename.substring(len-5, len);
                if(filename.substring(len-4, len) == '.xls' || filename.substring(len-5, len) == '.xlsx'){
                    $("#file_name").val(filename);
                } else{
                    alert("File is invalid. Please choose excel file(.xls)!");
                }
            }
            reader.readAsDataURL(file);
        });

    });
</script>


<div class="create-multiple-card-div" style="width:80%; margin:0 auto;">
    <h1> <strong style="color:#2d2d2d"> Create Multiple Card </strong> </h1>
    <form id="upload_file" action="" method="post" enctype="multipart/form-data" style='padding-left:52px;'>
        <div style='font-size:20px;transform: translateX(-7px);'> Please upload the excel file. </div>
        <table>
            <tr>
                <td> <input type="text" id='file_name' class="input-text-custom" placeholder="No selected file"/> </td>
                <td> <a class="control-button" id="btn_browse"> Browse </a> </td>
            </tr>
        </table>
        <input type="file" name="file" id="excel_file" style='display:none'/>
        <div style="margin-top:30px;">
            <input type="submit" class="control-button" id="save_btn" value="Upload"/>
        </div>
    </form>
    
</div>

<?php
	include("footer.php");
?>

<style type="text/css">
	.create-multiple-card-div table input{
        transform: translate(-10px,2px);
        height: 21px;
    }
</style>

<script>
    $(document).ready(function(){
        $("#upload_file").on('submit',(function(e) {
                e.preventDefault();
                if($("#file_name").val() == ""){
                   alert("Please select excel file!");
                   return;
                }
                $.ajax({
                    url: "<?php echo $rootpath; ?>/api/upload_card_excel.php", // Url to which the request is send
                    type: "POST",             // Type of request to be send, called as method
                    data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    processData:false,        // To send DOMDocument or non processed data file it is set to false
                    success: function(msg)   // A function to be called if request succeeds
                    {
                        console.log(msg);
                        alert(msg);
                    }
                });
            })
        );
    });
</script>