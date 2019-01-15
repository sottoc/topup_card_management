<?php
	require_once('library/reference.php');
	require_once('autoload.php');
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	require_once('userauth.php');		
	
	$reportbol = new reportbol();
	$localizationbol= new localizationbol();
	
	//localization
	$localized_home_result=$localizationbol->get_localization_by_pagename('home',1);
	while($row=$localized_home_result->getNext())
	{
		$localized_home_data[$row['localization_name']]=$row['detail'];
	}
	
	$localized_result=$localizationbol->get_localization_by_pagename('organization',1);
	while($row=$localized_result->getNext())
	{
		$localized_data[$row['localization_name']]=$row['detail'];
	}
	
	require_once('header.php');
?>

<script>
	$(document).ready(function(){
		//---- set background for active menu -----
        if(localStorage.getItem("current_page") != undefined){
            for(var i=0; i < $("#nav ul li").length; i++){
				if(i == 10){
					$($("#nav ul li")[i]).css("background", '#b12226');
				}
            }
        }
        //---- End -----

        $("#sel_date_from").datepicker({
			onSelect: function(date){
				$("#sel_date_from").val(get_date(date));
                $("#sel_time_from").val("00");
			}
		});

		$("#sel_date_to").datepicker({
			onSelect: function(date){
				$("#sel_date_to").val(get_date(date));
                $("#sel_time_to").val("00");
			}
		});

	});
</script>

<style type="text/css">
	.topup-model-box{
        margin-top: 30px;
        width: 700px;
        border: 1px solid black;
        font-size: 21px;
        padding: 10px;
    }
    .topup-model-box table{
        width: 100%;
    }
    .topup-model-box .topup-detail span{
        font-weight: 500 !important;
    }
    .topup-model-box .limit-times{
        color: red;
    }
</style>

<?php
    require_once('api/api_common.php');
    $query = "SELECT * FROM `tbl_topup_box` ORDER BY amount";
    $result = $conn->query($query);
    $all_box = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $box = array();
            $box[] = $row['box_id'];
            $box[] = $row['amount'];
            $box[] = $row['description'];
            $box[] = $row['bonus_value'];
            $box[] = $row['limit_times'];
            $box[] = $row['datetime_from'];
            $box[] = $row['datetime_to'];
            $box[] = $row['box_status'];
            $box[] = $row['group_id'];
            array_push($all_box, $box);
        }
    }

    $query = "SELECT MAX(box_id)+1 FROM tbl_topup_box";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $group_id = $row['MAX(box_id)+1'];
        }
    }
?>

<script>
    $(document).ready(function(){
        $(".edit-button.delete-box").click(function(e){
            var id = $(e.target).attr('data-id');
            var flag = confirm("Are you sure remove this topup box?");
            if (flag == true) {
                var obj = {
                    id : id
                }
                var url = '<?php echo $rootpath;?>/api/remove_topup_box.php';
                var request = JSON.stringify(obj);
                console.log(request);
                $.ajax({
                    url : url,
                    type : 'POST',
                    data :  request,   
                    tryCount : 0,
                    retryLimit : 3,
                    success : function(info) {
                        var info = JSON.parse(info);
                        console.log(info);
                        window.location.replace("<?php echo $rootpath;?>/topup_online_setting.php");
                    },
                    error : function(xhr, textStatus, errorThrown ) {
                        console.log(xhr);
                    }
                });
            } 
        });     
    });
    
</script>

<div class="content_data">
    <h2>Online Topup Settings</h2>
    <a href="#online_topup_settings" rel="modal:open" class="control-button"> Add </a>
    <br/>
    <div class="topup-content"> 
        <?php foreach ($all_box as $box) { if($box[7] == '1') { ?>
            <div class="topup-model-box">
                <table>
                    <tr> 
                        <td align="left" style="padding-left:20px;"> 
                            <div class="topup-detail"> 
                                $<?php echo $box[1]; ?> 
                                <?php if($box[3] != "0") { ?>
                                : Extra $<?php echo $box[3];?> for first <span class="limit-times"> <?php echo $box[4];?> </span> <?php if($box[4] =="1"){ echo "time"; } else { echo "times"; }?> topup
                                <?php } ?>
                            </div>  
                        </td>
                        <td align="right" style="padding-right:20px;"> 
                        <div class="buttons"> 
                        <a href="#online_topup_box_edit" rel="modal:open" class="edit-button edit-box" data-id="<?php echo $box[0];?>" data-amount="<?php echo $box[1];?>" data-description="<?php echo $box[2];?>" data-bonus-value="<?php echo $box[3];?>" data-limit-times="<?php echo $box[4];?>" data-datetime-from="<?php echo $box[5];?>" data-datetime-to="<?php echo $box[6];?>" data-group-id="<?php echo $box[8];?>"> Edit </a>  
                        &nbsp;&nbsp;&nbsp; <a data-id="<?php echo $box[0];?>" class="edit-button delete-box"> Delete </a> 
                        </div> 
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding-left:20px;">
                            <span style="color:#ea5f5f;font-size:17px;"> (<?php echo $box[2];?>) </span> 
                        </td>
                    </tr>
                </table>
            </div>
        <?php }} ?>
    </div>
</div>

<script>
    function changeBonusType(e){
        if($(e).val() == "0"){
            $("#bonus_type_symbol").html("($)");
        } else if($(e).val() == "1"){
            $("#bonus_type_symbol").html("(%)");
        } else{
            $("#bonus_value").val("0");
            $("#limit_times").val("0");
            $("#bonus_value").attr('disabled', true);
            $("#limit_times").attr('disabled', true);
            $("#sel_date_from").attr('disabled', true);
            $("#sel_time_from").attr('disabled', true);
            $("#sel_date_to").attr('disabled', true);
            $("#sel_time_to").attr('disabled', true);
            return;
        }
        $("#bonus_value").attr('disabled', false);
        $("#limit_times").attr('disabled', false);
        $("#sel_date_from").attr('disabled', false);
        $("#sel_time_from").attr('disabled', false);
        $("#sel_date_to").attr('disabled', false);
        $("#sel_time_to").attr('disabled', false);
        $("#bonus_value").focus();
        $("#bonus_value").val("1");
        $("#limit_times").val("1");
    }

    

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function isZero(e){
        var value = $(e).val();
        var x = value.substring(0,1);
        if(x == "0"){
            $(e).val("");
            return false;
        }
        // if($(e).attr('id') == 'bonus_value'){
        //     console.log($(e).attr('id'));
        //     if($("#bonus_type").val() == "1"){
        //         if(parseInt(value) >= 100){
        //             var v = value.substring(0,2);
        //             $("#bonus_value").val(v);
        //         }
        //     } else if($("#bonus_type").val() == "0"){
        //         if(parseInt(value) >= parseInt($("#box_amount").val())){
        //             var v = value.substring(0, value.length - 1);
        //             console.log(v);
        //             $("#bonus_value").val(v);
        //         }
        //     }
        // }
    }

    $(document).ready(function(){
        $("#box_description").val("");
        $("#add_new_box").click(function(){
            var amount = $("#box_amount").val();
            if(amount == ""){ $("#box_amount").focus(); return; }  // return;
            var description = $("#box_description").val();
            if(description == ""){ $("#box_description").focus(); return; } // return;
            if($("#bonus_type").val() == "-1"){ $("#bonus_type").focus(); return; } // return;
            var bonus_value = $("#bonus_value").val();
            if($("#bonus_type").val() == "1"){
                bonus_value = parseInt(amount)*bonus_value/100;
            }
            var limit_times = $("#limit_times").val();
            var group_id = $("#group_id").val();
            if(group_id == ""){ $("#group_id").focus(); return; } // return;
            var sel_date_from = $("#sel_date_from").val();
            if($("#bonus_type").val() != "2" && sel_date_from == "Choose Date"){ $("#sel_date_from").focus(); return; } // return;
            var sel_time_from = $("#sel_time_from").val();
            if($("#bonus_type").val() == "2"){
                sel_time_from = "00";
            }
            sel_time_from = sel_time_from + ":00:00";
            var sel_date_to = $("#sel_date_to").val();
            if($("#bonus_type").val() != "2" && sel_date_to == "Choose Date"){ $("#sel_date_to").focus(); return; } // return;
            var sel_time_to = $("#sel_time_to").val();
            if($("#bonus_type").val() == "2"){
                sel_time_to = "00";
            }
            sel_time_to = sel_time_to + ":00:00";
            //----- call API -------
            var obj = {
                amount : amount,
                description : description,
                bonus_value : bonus_value,
                limit_times : limit_times,
                group_id : group_id,
                sel_date_from : sel_date_from,
                sel_time_from : sel_time_from,
                sel_date_to : sel_date_to,
                sel_time_to : sel_time_to
            }
            var url = '<?php echo $rootpath;?>/api/add_topup_box.php';
            var request = JSON.stringify(obj);
            console.log(request);
            $.ajax({
                url : url,
                type : 'POST',
                data :  request,   
                tryCount : 0,
                retryLimit : 3,
                success : function(info) {
                    var info = JSON.parse(info);
                    console.log(info);
                    alert(info.response.data);
                    window.location.replace("<?php echo $rootpath;?>/topup_online_setting.php");
                },
                error : function(xhr, textStatus, errorThrown ) {
                    console.log(xhr);
                }
            });
        });
    });
</script>

<div id="online_topup_settings" class="modal">
    <h1 align="center"> Online Topup Settings </h1>
	<table align="center">
        <tr>
            <td style="float:right;"> Topup Amount(SGD): </td>
            <td class="td-2"> 
                <input type="number" id="box_amount" onkeyup="return isZero(this)" onkeypress="return isNumberKey(event)" class="input-text-custom" min="1"/> 
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Description: </td>
            <td class="td-2"> 
                <textarea id='box_description' class="input-text-custom"> </textarea>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Bonus Type: </td>
            <td class="td-2">
                <select id="bonus_type" class="select-custom" onChange="changeBonusType(this)">
                    <option value='-1'> Choose Bonus Type </option>
                    <option value='0'> Amount </option>
                    <option value='1'> Percentage </option>
                    <option value='2'> No Bonus </option>
                </select>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Value: </td>
            <td class="td-2">
                <input type="number" id="bonus_value" onkeyup="return isZero(this)" onkeypress="return isNumberKey(event)" class="input-text-custom" min="1" max="100" style="width:100px;"/> 
                <span id="bonus_type_symbol">  </span>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Limit: </td>
            <td class="td-2">
                <input type="number" id="limit_times" onkeyup="return isZero(this)" onkeypress="return isNumberKey(event)" class="input-text-custom" min="1" max="10" style="width:100px;"/> 
                <span> times </span>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Group Id: </td>
            <td class="td-2">
                <input type="text" id="group_id" value="<?php echo $group_id; ?>" class="input-text-custom" min="1" style="width:100px;"/> 
                <span> </span>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Effective Date & Time From: </td>
            <td class="td-2">
                <table>
                    <tr> 
                        <td> 
                            <input type="text" value="Choose Date" name="sel_date_from" id="sel_date_from" class='input-text-custom' style="width:150px"/>
                        </td>
                        <td> 
                            <select id="sel_time_from" class="select-custom">
                                <option value='-1'> Choose Time </option>
                                <option value='00'> 00:00 AM </option>
                                <option value='01'> 01:00 AM </option>
                                <option value='02'> 02:00 AM </option>
                                <option value='03'> 03:00 AM </option>
                                <option value='04'> 04:00 AM </option>
                                <option value='05'> 05:00 AM </option>
                                <option value='06'> 06:00 AM </option>
                                <option value='07'> 07:00 AM </option>
                                <option value='08'> 08:00 AM </option>
                                <option value='09'> 09:00 AM </option>
                                <option value='10'> 10:00 AM </option>
                                <option value='11'> 11:00 AM </option>
                                <option value='12'> 12:00 PM </option>
                                <option value='13'> 01:00 PM </option>
                                <option value='14'> 02:00 PM </option>
                                <option value='15'> 03:00 PM </option>
                                <option value='16'> 04:00 PM </option>
                                <option value='17'> 05:00 PM </option>
                                <option value='18'> 06:00 PM </option>
                                <option value='19'> 07:00 PM </option>
                                <option value='20'> 08:00 PM </option>
                                <option value='21'> 09:00 PM </option>
                                <option value='22'> 10:00 PM </option>
                                <option value='23'> 11:00 PM </option>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="float:right;"> Effective Date & Time To: </td>
            <td class="td-2">
                <table>
                    <tr> 
                        <td> 
                            <input type="text" value="Choose Date" name="sel_date_to" id="sel_date_to" class='input-text-custom' style="width:150px"/>
                        </td>
                        <td> 
                            <select id="sel_time_to" class="select-custom">
                                <option value='-1'> Choose Time </option>
                                <option value='00'> 00:00 AM </option>
                                <option value='01'> 01:00 AM </option>
                                <option value='02'> 02:00 AM </option>
                                <option value='03'> 03:00 AM </option>
                                <option value='04'> 04:00 AM </option>
                                <option value='05'> 05:00 AM </option>
                                <option value='06'> 06:00 AM </option>
                                <option value='07'> 07:00 AM </option>
                                <option value='08'> 08:00 AM </option>
                                <option value='09'> 09:00 AM </option>
                                <option value='10'> 10:00 AM </option>
                                <option value='11'> 11:00 AM </option>
                                <option value='12'> 12:00 PM </option>
                                <option value='13'> 01:00 PM </option>
                                <option value='14'> 02:00 PM </option>
                                <option value='15'> 03:00 PM </option>
                                <option value='16'> 04:00 PM </option>
                                <option value='17'> 05:00 PM </option>
                                <option value='18'> 06:00 PM </option>
                                <option value='19'> 07:00 PM </option>
                                <option value='20'> 08:00 PM </option>
                                <option value='21'> 09:00 PM </option>
                                <option value='22'> 10:00 PM </option>
                                <option value='23'> 11:00 PM </option>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:16px;padding-left:20px;"> Note : When the effective date is on, Top-Up choices will be hidden when not in effective date </td>
        </tr>
    </table>
    <div align="center" style="margin-top:50px;">
        <a class="control-button" href="#" rel="modal:close" id='close_modal_btn'> Cancel </a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="control-button" id="add_new_box"> Add/Save </a>
    </div>
</div>

<script>
    function changeBonusTypeEdit(e){
        if($(e).val() == "0"){
            $("#bonus_type_symbol_edit").html("($)");
        } else{
            $("#bonus_type_symbol_edit").html("(%)");
        }
        $("#bonus_value_edit").focus();
        $("#bonus_value_edit").val("1");
        $("#limit_times_edit").val("1");
    }

    $(document).ready(function(){
        $("#sel_date_from_edit").datepicker({
			onSelect: function(date){
				$("#sel_date_from_edit").val(get_date(date));
                $("#sel_time_from_edit").val("00");
			}
		});

		$("#sel_date_to_edit").datepicker({
			onSelect: function(date){
				$("#sel_date_to_edit").val(get_date(date));
                $("#sel_time_to_edit").val("00");
			}
		});

        $("#edit_box_btn").click(function(e){
            var id = $(e.target).attr('data-id');
            var amount = $("#box_amount_edit").val();
            if(amount == ""){ $("#box_amount_edit").focus(); return; }  // return;
            var description = $("#box_description_edit").val();
            if(description == ""){ $("#box_description_edit").focus(); return; } // return;
            if($("#bonus_type_edit").val() == "-1"){ $("#bonus_type_edit").focus(); return; } // return;
            var bonus_value = $("#bonus_value_edit").val();
            if($("#bonus_type_edit").val() == "1"){
                bonus_value = parseInt(amount)*bonus_value/100;
            }
            var limit_times = $("#limit_times_edit").val();
            if(limit_times == ""){ $("#limit_times_edit").focus(); return; }  // return;
            var group_id_edit = $("#group_id_edit").val();
            var sel_date_from = $("#sel_date_from_edit").val();
            if($("#bonus_type_edit").val() != "2" && sel_date_from == "Choose Date"){ $("#sel_date_from_edit").focus(); return; } // return;
            var sel_time_from = $("#sel_time_from_edit").val();
            sel_time_from = sel_time_from + ":00:00";
            var sel_date_to = $("#sel_date_to_edit").val();
            if($("#bonus_type_edit").val() != "2" && sel_date_to == "Choose Date"){ $("#sel_date_to_edit").focus(); return; } // return;
            var sel_time_to = $("#sel_time_to_edit").val();
            sel_time_to = sel_time_to + ":00:00";
            //----- call API -------
            var obj = {
                id : id,
                amount : amount,
                description : description,
                bonus_value : bonus_value,
                limit_times : limit_times,
                group_id : group_id_edit,
                sel_date_from : sel_date_from,
                sel_time_from : sel_time_from,
                sel_date_to : sel_date_to,
                sel_time_to : sel_time_to
            }
            var url = '<?php echo $rootpath;?>/api/edit_topup_box.php';
            var request = JSON.stringify(obj);
            console.log(request);
            $.ajax({
                url : url,
                type : 'POST',
                data :  request,   
                tryCount : 0,
                retryLimit : 3,
                success : function(info) {
                    var info = JSON.parse(info);
                    console.log(info);
                    alert(info.response.data);
                    window.location.replace("<?php echo $rootpath;?>/topup_online_setting.php");
                },
                error : function(xhr, textStatus, errorThrown ) {
                    console.log(xhr);
                }
            });
        });

        setTimeout(() => {
            $(".edit-button.edit-box").click(function(e){
                var id = $(e.target).attr('data-id');
                $("#edit_box_btn").attr('data-id', id);
                $("#box_amount_edit").val($(e.target).attr('data-amount'));
                $("#box_description_edit").html($(e.target).attr('data-description'));
                $("#bonus_value_edit").val($(e.target).attr('data-bonus-value'));
                $("#limit_times_edit").val($(e.target).attr('data-limit-times'));
                $("#group_id_edit").val($(e.target).attr('data-group-id'));

                var datetime_from = $(e.target).attr('data-datetime-from');
                datetime_from = datetime_from.split(" ");
                $("#sel_date_from_edit").val(datetime_from[0]);
                time_from = datetime_from[1];
                $("#sel_time_from_edit").val(time_from.substring(0,2));
                var datetime_to = $(e.target).attr('data-datetime-to');
                datetime_to = datetime_to.split(" ");
                $("#sel_date_to_edit").val(datetime_to[0]);
                time_to = datetime_to[1];
                $("#sel_time_to_edit").val(time_to.substring(0,2));

                if($("#bonus_value_edit").val() == "0"){
                    $("#bonus_type_edit").val("2");
                    $("#limit_times_edit").attr('disabled', true);
                    $("#sel_date_from_edit").attr('disabled', true);
                    $("#sel_time_from_edit").attr('disabled', true);
                    $("#sel_date_to_edit").attr('disabled', true);
                    $("#sel_time_to_edit").attr('disabled', true);
                    $("#sel_date_from_edit").val("Choose Date");
                    $("#sel_time_from_edit").val("-1");
                    $("#sel_date_to_edit").val("Choose Date");
                    $("#sel_time_to_edit").val("-1");
                } else{
                    $("#bonus_type_edit").val("0");
                    $("#limit_times_edit").attr('disabled', false);
                    $("#sel_date_from_edit").attr('disabled', false);
                    $("#sel_time_from_edit").attr('disabled', false);
                    $("#sel_date_to_edit").attr('disabled', false);
                    $("#sel_time_to_edit").attr('disabled', false);
                }
            });
        }, 500);
    });
</script>

<div id="online_topup_box_edit" class="modal">
    <h1 align="center"> Online Topup Settings </h1>
	<table align="center">
        <tr>
            <td style="float:right;"> Topup Amount(SGD): </td>
            <td class="td-2"> 
                <input type="number" id="box_amount_edit" class="input-text-custom" min="1" disabled/> 
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Description: </td>
            <td class="td-2"> 
                <textarea id='box_description_edit' class="input-text-custom"> </textarea>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Bonus Type: </td>
            <td class="td-2">
                <select id="bonus_type_edit" class="select-custom" onChange="changeBonusTypeEdit(this)" disabled>
                    <option value='-1'> Choose Bonus Type </option>
                    <option value='0'> Amount </option>
                    <option value='1'> Percentage </option>
                    <option value='2'> No Bonus </option>
                </select>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Value: </td>
            <td class="td-2">
                <input type="number" id="bonus_value_edit" class="input-text-custom" min="1" max="100" style="width:100px;" disabled/> 
                <span id="bonus_type_symbol_edit"> ($) </span>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Limit: </td>
            <td class="td-2">
                <input type="number" id="limit_times_edit" onkeyup="return isZero(this)" onkeypress="return isNumberKey(event)" class="input-text-custom" min="1" max="10" style="width:100px;"/> 
                <span> times </span>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Group Id: </td>
            <td class="td-2">
                <input type="text" id="group_id_edit" class="input-text-custom" min="1" style="width:100px;" disabled/> 
                <span> </span>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Effective Date & Time From: </td>
            <td class="td-2">
                <table>
                    <tr> 
                        <td> 
                            <input type="text" value="Choose Date" name="sel_date_from" id="sel_date_from_edit" class='input-text-custom' style="width:150px"/>
                        </td>
                        <td> 
                            <select id="sel_time_from_edit" class="select-custom">
                                <option value='-1'> Choose Time </option>
                                <option value='00'> 00:00 AM </option>
                                <option value='01'> 01:00 AM </option>
                                <option value='02'> 02:00 AM </option>
                                <option value='03'> 03:00 AM </option>
                                <option value='04'> 04:00 AM </option>
                                <option value='05'> 05:00 AM </option>
                                <option value='06'> 06:00 AM </option>
                                <option value='07'> 07:00 AM </option>
                                <option value='08'> 08:00 AM </option>
                                <option value='09'> 09:00 AM </option>
                                <option value='10'> 10:00 AM </option>
                                <option value='11'> 11:00 AM </option>
                                <option value='12'> 12:00 PM </option>
                                <option value='13'> 01:00 PM </option>
                                <option value='14'> 02:00 PM </option>
                                <option value='15'> 03:00 PM </option>
                                <option value='16'> 04:00 PM </option>
                                <option value='17'> 05:00 PM </option>
                                <option value='18'> 06:00 PM </option>
                                <option value='19'> 07:00 PM </option>
                                <option value='20'> 08:00 PM </option>
                                <option value='21'> 09:00 PM </option>
                                <option value='22'> 10:00 PM </option>
                                <option value='23'> 11:00 PM </option>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="float:right;"> Effective Date & Time To: </td>
            <td class="td-2">
                <table>
                    <tr> 
                        <td> 
                            <input type="text" value="Choose Date" name="sel_date_to" id="sel_date_to_edit" class='input-text-custom' style="width:150px"/>
                        </td>
                        <td> 
                            <select id="sel_time_to_edit" class="select-custom">
                                <option value='-1'> Choose Time </option>
                                <option value='00'> 00:00 AM </option>
                                <option value='01'> 01:00 AM </option>
                                <option value='02'> 02:00 AM </option>
                                <option value='03'> 03:00 AM </option>
                                <option value='04'> 04:00 AM </option>
                                <option value='05'> 05:00 AM </option>
                                <option value='06'> 06:00 AM </option>
                                <option value='07'> 07:00 AM </option>
                                <option value='08'> 08:00 AM </option>
                                <option value='09'> 09:00 AM </option>
                                <option value='10'> 10:00 AM </option>
                                <option value='11'> 11:00 AM </option>
                                <option value='12'> 12:00 PM </option>
                                <option value='13'> 01:00 PM </option>
                                <option value='14'> 02:00 PM </option>
                                <option value='15'> 03:00 PM </option>
                                <option value='16'> 04:00 PM </option>
                                <option value='17'> 05:00 PM </option>
                                <option value='18'> 06:00 PM </option>
                                <option value='19'> 07:00 PM </option>
                                <option value='20'> 08:00 PM </option>
                                <option value='21'> 09:00 PM </option>
                                <option value='22'> 10:00 PM </option>
                                <option value='23'> 11:00 PM </option>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:16px;padding-left:20px;"> Note : When the effective date is on, Top-Up choices will be hidden when not in effective date </td>
        </tr>
    </table>
    <div align="center" style="margin-top:50px;">
        <a class="control-button" href="#" rel="modal:close" id='close_modal_btn'> Cancel </a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="control-button" id="edit_box_btn"> Update </a>
    </div>
</div>

<style>
    #online_topup_settings, #online_topup_box_edit {
        max-width: 800px !important;
        font-size:17px;
        padding-bottom:50px;
    }
    #online_topup_settings .td-2, #online_topup_box_edit .td-2{
        padding-left:20px;
    }
</style>

<?php
	include("footer.php");
?>