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
            array_push($all_box, $box);
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
        <?php foreach ($all_box as $box) {  ?>
            <div class="topup-model-box">
                <table>
                    <tr> 
                        <td> <div class="topup-detail"> $<?php echo $box[1]; ?>: Extra $<?php echo $box[3];?> for first <span class="limit-times"> <?php echo $box[4];?> </span> times topup </div>  </td>
                        <td> <div class="buttons"> 
                        <a href="#online_topup_box_edit" rel="modal:open" class="edit-button edit-box" data-id="<?php echo $box[0];?>" data-amount="<?php echo $box[1];?>" data-description="<?php echo $box[2];?>" data-bonus-value="<?php echo $box[3];?>" data-limit-times="<?php echo $box[4];?>" data-datetime-from="<?php echo $box[5];?>" data-datetime-to="<?php echo $box[6];?>"> Edit </a>  
                        &nbsp;&nbsp;&nbsp; <a data-id="<?php echo $box[0];?>" class="edit-button delete-box"> Delete </a> 
                        </div> </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="color:#72ab72;"> Top Up Amount(SGD): </span> 
                            <span style="color:#ea5f5f;"> <?php echo $box[2];?> </span> 
                        </td>
                    </tr>
                </table>
            </div>
        <?php } ?>
        
        <!-- <div class="topup-model-box">
            <table>
                <tr> 
                    <td> 
                        <div class="topup-detail">
                            <span style="color:#1b8e1b;"> Top Up Amount(SGD): </span> 
                            <span style="color:#fd3333;"> Description </span> 
                        </div> 
                    </td>
                    <td> <div class="buttons"> <a class="edit-button"> Edit </a>  &nbsp;&nbsp;&nbsp; <a class="edit-button"> Delete </a> </div> </td>
                </tr>
            </table>
        </div> -->
        
    </div>
</div>

<script>
    function changeBonusType(e){
        if($(e).val() == "0"){
            $("#bonus_type_symbol").html("($)");
        } else{
            $("#bonus_type_symbol").html("(%)");
        }
        $("#bonus_value").focus();
        $("#bonus_value").val("1");
        $("#limit_times").val("1");
    }

    $(document).ready(function(){
        $("#box_description").val("");

        $("#add_new_box").click(function(){
            var amount = $("#box_amount").val();
            if(amount == "-1"){ $("#box_amount").focus(); return; }  // return;
            var description = $("#box_description").val();
            if(description == ""){ $("#box_description").focus(); return; } // return;
            if($("#bonus_type").val() == "-1"){ $("#bonus_type").focus(); return; } // return;
            var bonus_value = $("#bonus_value").val();
            if($("#bonus_type").val() == "1"){
                bonus_value = parseInt(amount)*bonus_value/100;
            }
            var limit_times = $("#limit_times").val();
            var sel_date_from = $("#sel_date_from").val();
            if(sel_date_from == "Choose date"){ $("#sel_date_from").focus(); return; } // return;
            var sel_time_from = $("#sel_time_from").val();
            sel_time_from = sel_time_from + ":00:00";
            var sel_date_to = $("#sel_date_to").val();
            if(sel_date_to == "Choose date"){ $("#sel_date_to").focus(); return; } // return;
            var sel_time_to = $("#sel_time_to").val();
            sel_time_to = sel_time_to + ":00:00";
            //----- call API -------
            var obj = {
                amount : amount,
                description : description,
                bonus_value : bonus_value,
                limit_times : limit_times,
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
                <!-- <input type="text" id="topup_amount" class="input-text-custom" />  -->
                <select id="box_amount" class="select-custom" onChange="">
                    <option value='-1'> Choose Amount </option>
                    <option value='10'> 10 </option>
                    <option value='30'> 30 </option>
                    <option value='50'> 50 </option>
                    <option value='100'> 100 </option>
                    <option value='150'> 150 </option>
                    <option value='200'> 200 </option>
                    <option value='500'> 500 </option>
                </select>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Description: </td>
            <td class="td-2"> 
                <textarea id='box_description' class="input-text-custom" placeholder='Enter refund reason'> </textarea>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Bonus Type: </td>
            <td class="td-2">
                <select id="bonus_type" class="select-custom" onChange="changeBonusType(this)">
                    <option value='-1'> Amount/Percentage </option>
                    <option value='0'> Amount </option>
                    <option value='1'> Percentage </option>
                </select>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Value: </td>
            <td class="td-2">
                <input type="number" id="bonus_value" class="input-text-custom" min="1" max="100" style="width:100px;"/> 
                <span id="bonus_type_symbol">  </span>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Limit: </td>
            <td class="td-2">
                <input type="number" id="limit_times" class="input-text-custom" min="1" max="10" style="width:100px;"/> 
                <span> times </span>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Effective Date & Time From: </td>
            <td class="td-2">
                <table>
                    <tr> 
                        <td> 
                            <input type="text" value="Choose date" name="sel_date_from" id="sel_date_from" class='input-text-custom' style="width:150px"/>
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
                            <input type="text" value="Choose date" name="sel_date_to" id="sel_date_to" class='input-text-custom' style="width:150px"/>
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

        $("#add_new_box_edit").click(function(e){
            var id = $(e.target).attr('data-id');
            var amount = $("#box_amount_edit").val();
            if(amount == "-1"){ $("#box_amount_edit").focus(); return; }  // return;
            var description = $("#box_description_edit").val();
            if(description == ""){ $("#box_description_edit").focus(); return; } // return;
            if($("#bonus_type_edit").val() == "-1"){ $("#bonus_type_edit").focus(); return; } // return;
            var bonus_value = $("#bonus_value_edit").val();
            if($("#bonus_type_edit").val() == "1"){
                bonus_value = parseInt(amount)*bonus_value/100;
            }
            var limit_times = $("#limit_times_edit").val();
            var sel_date_from = $("#sel_date_from_edit").val();
            if(sel_date_from == "Choose date"){ $("#sel_date_from_edit").focus(); return; } // return;
            var sel_time_from = $("#sel_time_from_edit").val();
            sel_time_from = sel_time_from + ":00:00";
            var sel_date_to = $("#sel_date_to_edit").val();
            if(sel_date_to == "Choose date"){ $("#sel_date_to_edit").focus(); return; } // return;
            var sel_time_to = $("#sel_time_to_edit").val();
            sel_time_to = sel_time_to + ":00:00";
            //----- call API -------
            var obj = {
                id : id,
                amount : amount,
                description : description,
                bonus_value : bonus_value,
                limit_times : limit_times,
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
                $("#add_new_box_edit").attr('data-id', id);
                $("#box_amount_edit").val($(e.target).attr('data-amount'));
                $("#box_description_edit").html($(e.target).attr('data-description'));
                $("#bonus_value_edit").val($(e.target).attr('data-bonus-value'));
                $("#limit_times_edit").val($(e.target).attr('data-limit-times'));

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
                <!-- <input type="text" id="topup_amount" class="input-text-custom" />  -->
                <select id="box_amount_edit" class="select-custom" onChange="">
                    <option value='-1'> Choose Amount </option>
                    <option value='10'> 10 </option>
                    <option value='30'> 30 </option>
                    <option value='50'> 50 </option>
                    <option value='100'> 100 </option>
                    <option value='150'> 150 </option>
                    <option value='200'> 200 </option>
                    <option value='500'> 500 </option>
                </select>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Description: </td>
            <td class="td-2"> 
                <textarea id='box_description_edit' class="input-text-custom" placeholder='Enter refund reason'> </textarea>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Bonus Type: </td>
            <td class="td-2">
                <select id="bonus_type_edit" class="select-custom" onChange="changeBonusTypeEdit(this)">
                    <option value='-1'> Amount/Percentage </option>
                    <option value='0' selected> Amount </option>
                    <option value='1'> Percentage </option>
                </select>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Value: </td>
            <td class="td-2">
                <input type="number" id="bonus_value_edit" class="input-text-custom" min="1" max="100" style="width:100px;"/> 
                <span id="bonus_type_symbol_edit"> ($) </span>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Limit: </td>
            <td class="td-2">
                <input type="number" id="limit_times_edit" class="input-text-custom" min="1" max="10" style="width:100px;"/> 
                <span> times </span>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Effective Date & Time From: </td>
            <td class="td-2">
                <table>
                    <tr> 
                        <td> 
                            <input type="text" value="Choose date" name="sel_date_from" id="sel_date_from_edit" class='input-text-custom' style="width:150px"/>
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
                            <input type="text" value="Choose date" name="sel_date_to" id="sel_date_to_edit" class='input-text-custom' style="width:150px"/>
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
    </table>
    <div align="center" style="margin-top:50px;">
        <a class="control-button" href="#" rel="modal:close" id='close_modal_btn'> Cancel </a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="control-button" id="add_new_box_edit"> Update </a>
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