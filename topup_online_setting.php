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
                $("#sel_time_from").val("0");
			}
		});

		$("#sel_date_to").datepicker({
			onSelect: function(date){
				$("#sel_date_to").val(get_date(date));
                $("#sel_time_to").val("0");
			}
		});

	});
</script>

<style type="text/css">
	.topup-model-div{
        margin-top: 30px;
        width: 700px;
        border: 1px solid black;
        font-size: 23px;
        padding: 10px;
    }
    .topup-model-div table{
        width: 100%;
    }
    .topup-model-div .topup-detail span{
        font-weight: 500 !important;
    }
    
</style>

<div class="content_data">
    <h2>Online Topup Settings</h2>
    <a href="#online_topup_settings" rel="modal:open" class="control-button"> Add </a>
    <br/>
    <div class="topup-content"> 
        <div class="topup-model-div">
            <table>
                <tr> 
                    <td> <div class="topup-detail"> $50: Extra $2 for first time topup </div>  </td>
                    <td> <div class="buttons"> <a class="edit-button"> Edit </a>  &nbsp;&nbsp;&nbsp; <a class="edit-button"> Delete </a> </div> </td>
                </tr>
            </table>
        </div>
        <div class="topup-model-div">
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
        </div>
        <div class="topup-model-div">
            <table>
                <tr> 
                    <td> <div class="topup-detail"> $200: Extra $10 for first time topup </div>  </td>
                    <td> <div class="buttons"> <a class="edit-button"> Edit </a>  &nbsp;&nbsp;&nbsp; <a class="edit-button"> Delete </a> </div> </td>
                </tr>
            </table>
        </div>
    </div>
    
</div>

<div id="online_topup_settings" class="modal">
    <h1 align="center"> Online Topup Settings </h1>
	<table align="center">
        <tr>
            <td style="float:right;"> Topup Amount(SGD): </td>
            <td class="td-2"> 
                <input type="text" id="topup_amount" class="input-text-custom" /> 
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Description: </td>
            <td class="td-2"> 
                <textarea id='topup_description' class="input-text-custom" placeholder='Enter refund reason'> </textarea>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Bonus Type: </td>
            <td class="td-2">
                <select id="bonus_type" class="select-custom" onChange="changeBonusType(this)">
                    <option value='-1'> Percentage/Amount </option>
                    <option value='0'> Percentage </option>
                    <option value='1'> Amount </option>
                </select>
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Value: </td>
            <td class="td-2"> 
                <input type="text" id="bonus_value" class="input-text-custom" /> 
            </td>
        </tr>
        <tr>
            <td style="float:right;"> Limit: </td>
            <td class="td-2"> 
                <input type="text" id="bonus_limit" class="input-text-custom" /> 
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
                            <select id="sel_time_from" class="select-custom" onChange="changeTimeFrom(this)">
                                <option value='-1'> Choose Time </option>
                                <option value='0'> 00:00 AM </option>
                                <option value='1'> 01:00 AM </option>
                                <option value='2'> 02:00 AM </option>
                                <option value='3'> 03:00 AM </option>
                                <option value='4'> 04:00 AM </option>
                                <option value='5'> 05:00 AM </option>
                                <option value='6'> 06:00 AM </option>
                                <option value='7'> 07:00 AM </option>
                                <option value='8'> 08:00 AM </option>
                                <option value='9'> 09:00 AM </option>
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
                            <select id="sel_time_to" class="select-custom" onChange="changeTimeTo(this)">
                                <option value='-1'> Choose Time </option>
                                <option value='0'> 00:00 AM </option>
                                <option value='1'> 01:00 AM </option>
                                <option value='2'> 02:00 AM </option>
                                <option value='3'> 03:00 AM </option>
                                <option value='4'> 04:00 AM </option>
                                <option value='5'> 05:00 AM </option>
                                <option value='6'> 06:00 AM </option>
                                <option value='7'> 07:00 AM </option>
                                <option value='8'> 08:00 AM </option>
                                <option value='9'> 09:00 AM </option>
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

<style>
    #online_topup_settings{
        max-width: 800px !important;
        font-size:17px;
        padding-bottom:50px;
    }
    #online_topup_settings .td-2{
        padding-left:20px;
    }
</style>

<?php
	include("footer.php");
?>