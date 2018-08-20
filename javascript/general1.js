/* pdlt */
function preventMyanmarFont(evt) {
    var e = window.event || evt;
    var charCode = e.which || e.keyCode;
    if (charCode >= 4096 && charCode <= 4185) {
        if (window.event) //IE
            window.event.returnValue = false;
        else //Firefox
            e.preventDefault();
    }
    return true;
}

function preventMM3Font(txtvalue,txtbox_id,errshow_div_id){
	for( var chr in txtvalue ){
		var string = txtvalue[chr];
	   //8203 is ZERO WIDTH SPACE 
	    if( ( string.charCodeAt(txtvalue[chr]) >= 4096 && string.charCodeAt(txtvalue[chr]) <= 4225 ) ||  string.charCodeAt(txtvalue[chr]) == 8203) {
	    	$("#"+txtbox_id).val('');
	    	$("#"+errshow_div_id).html('Please enter in english!');
	    	return false;
	    }
	}
}
/*	function preventMM3Font(value,id) {
		console.log(event);
		console.log(event.key);
	    var key = event.keyCode;
	    console.log('preventMM3Font '+key);
	    if (key == 229) {
	        $("#"+id).val('');
	    }
	}
*/

function create_autocomplete(txt_id, sel_id, criteria, source)
{
	var query = ( criteria ) ? criteria : '';
	var data = ( source ) ? source : 'combobox_exec.php?id='+ txt_id +'&criteria='+ query;
		
	$('#'+ txt_id).autocomplete({
		source: data,
		select: function( event, ui )
		{
			$('#'+ sel_id).val(ui.item.id);
		}
	});
}

//remove commas from number 
function clean_number (num) {
	var clean_num = num.replace(/,/g, '');
	return clean_num;
}
// Format numbers to two decimals with commas
function formatMoney(num) {
	if(!isNaN(num)){
		num = Number(num);
	    var p = num.toFixed(2).split(".");
	    var chars = p[0].split("").reverse();
	    var newstr = '';
	    var count = 0;
	    for (x in chars) {
	        count++;
	        if(count%3 == 1 && count != 1) {
	            newstr = chars[x] + ',' + newstr;
	        } else {
	            newstr = chars[x] + newstr;
	        }
	    }
	    return newstr + "." + p[1];
	}
	else
		return 0;
}

function chk_hasvalue (id,errshow_id,msg) {
	if($("#"+id).val()!='' && $("#"+id).val()!='0'){
		$("#"+errshow_id).html('');
	}
	else{
		$("#"+errshow_id).html('*'+msg);
	}
}

function OnlyAllowNumber(id){
	setTimeout(function() {
		var typed_value = jQuery("#"+id).val() ;
		typed_value = typed_value.replace(/[^0-9+]/g,'');
		jQuery("#"+id).val(typed_value);
	},100);
}

function htmlspecialchars(str) {
 if (typeof(str) == "string") {
  str = str.replace(/&/g, "&amp;"); /* must do &amp; first */
  str = str.replace(/"/g, "&quot;");
  str = str.replace(/'/g, "&#039;");
  str = str.replace(/</g, "&lt;");
  str = str.replace(/>/g, "&gt;");
  }
 return str;
 }
	// For Add and Edit Callback
	function add_and_update_exec_callback_dialog(result_str)
	{
		if( result_arr = exec_callback_sessionexpire(result_str, stringseparator, valueseparator) )		
		{
			if( result_arr['success'] == 1 )
			{
				close_dialog();				
				show_action_message(result_arr['message']);
				oTable.fnStandingRedraw();
			}
			else
			{
				form_id = 'alert_msg';
				if( result_arr['success'] == 2 )
					form_id = 'frmdialog';
				show_action_message(result_arr['message'], form_id);
				hideloadingimage_showbutton();
			}
		}
	}


function getdisplayrow()
{
	var valdisplayrow='';
		valdisplayrow="";
		//valdisplayrow="table-row";
	return valdisplayrow;
}
function getdisplayCell()
{
	var valdisplayrow='';
		//valdisplayrow="table-cell";
		valdisplayrow="";
	return valdisplayrow;
}
function Get_Cookie( check_name ) {
	// first we'll split this cookie up into name/value pairs
	// note: document.cookie only returns name=value, not the other components
	var a_all_cookies = document.cookie.split( ';' );
	var a_temp_cookie = '';
	var cookie_name = '';
	var cookie_value = '';
	var b_cookie_found = false; // set boolean t/f default f

	for (i=0; i<a_all_cookies.length; i++ )
	{
		// now we'll split apart each name=value pair
		a_temp_cookie = a_all_cookies[i].split( '=' );

		// and trim left/right whitespace while we're at it
		cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');

		// if the extracted name matches passed check_name
		if ( cookie_name == check_name )
		{
			b_cookie_found = true;
			// we need to handle case where cookie has no value but exists (no = sign, that is):
			if ( a_temp_cookie.length > 1 )
			{
				cookie_value = unescape( a_temp_cookie[1].replace(/^\s+|\s+$/g, '') );
			}
			// note that in cases where cookie is initialized but no value, null is returned
			return cookie_value;
			break;
		}
		a_temp_cookie = null;
		cookie_name = '';
	}
	if ( !b_cookie_found )
	{
		return null;
	}
}
	
	function get_standard_date(format, str , ret){ //sya 17-Jan-2011
		//Result will standard format-> date and string for user required (yyyy-mm-dd)
		//Parameter-> format: input format; str: input date string; ret: result type->(string or date); 
		arr_format = format.split("-");
		arr_datr = str.split("-"); 
		arr_str = new Array();
		var myDate = new Date(); myDate.setMonth(2);
		for(i=0;i<arr_format.length;i++)
		{
			if(arr_format[i]=="dd"){
				myDate.setDate(arr_datr[i]);
				arr_str[2] = arr_datr[i];
			}else if(arr_format[i]=="mm" || arr_format[i]=="MM"){
				myDate.setMonth(arr_datr[i]-1);
				arr_str[1] = arr_datr[i];
			}else if(arr_format[i]=="yy" || arr_format[i]=="yyyy"){
				myDate.setYear(arr_datr[i]);
				arr_str[0] = arr_datr[i];
			}
				
		}
		if(ret=="date")
			return myDate;//new Date(arr_str[0], arr_str[1]-1, arr_str[2]); //myDate ;
		else
			return 	arr_str[0]+"-"+arr_str[1]+"-"+arr_str[2];
			
	}
	
	function chk_number(event, escapeKeys)
	{
		var keyCode = event.which; // the keycode for the key pressed		
		if(keyCode==16)	
		{	
			alert('Please Enter Digit Only!');
		}
		else
		{
			var isStandard = (keyCode > 48 && keyCode < 58); 	// 48-57 Standard Keyboard Numbers		
			var isExtended = (keyCode > 95 && keyCode < 106);	// 96-105 Extended Keyboard Numbers (aka Keypad)	
			// 8 Backspace, 9 Tab, 46 Forward Delete
			// 37 Left Arrow, 38 Up Arrow, 39 Right Arrow, 40 Down Arrow
			//var validKeyCodes = ',8,9,37,38,39,40,46,190,110,';
			var validKeyCodes = ',8,9,37,38,39,40,46,48,190,110,';
			validKeyCodes += escapeKeys;
			var isOther = ( validKeyCodes.indexOf(',' + keyCode + ',') > -1 );				
			if ( isStandard || isExtended || isOther )
				return true;
			else
				return false;	
		}
	}

//create dialog from existing div 
	function create_dialog_div(divid, title)
	{			
		$('#'+ divid).dialog ({
			autoOpen: true,
			title: title ,
			resizable: false,
			draggable: true,
			modal:true,			
			overlay: {opacity: 0.2, background: "black"},			
			width: 500,
			height:'auto'
		});		
		jQuery('button, input:submit, input:button, input:reset').button();	
	}
//general.js
 
function trim(s)
{ 
    return s.replace(/^\s+|\s+$/g,"");

} 

function fitimagethumb(objimg, maxWidth, maxHeight) 
{
    var ratio = 0;  // Used for aspect ratio
    
    var width = $(objimg).width();    // Current image width
    var height = $(objimg).height();  // Current image height
	
    // Check if the current width is larger than the max
    if(width > maxWidth){
        ratio = maxWidth / width;   // get ratio for scaling image
        $(objimg).css("width", maxWidth); // Set new width
        $(objimg).css("height", height * ratio);  // Scale height based on ratio
        height = height * ratio;    // Reset height to match scaled image
        width = width * ratio;    // Reset width to match scaled image
    }

    // Check if current height is larger than max
    if(height > maxHeight){
        ratio = maxHeight / height; // get ratio for scaling image
        $(objimg).css("height", maxHeight);   // Set new height
        $(objimg).css("width", width * ratio);    // Scale width based on ratio
        width = width * ratio;    // Reset width to match scaled image
    }
}
// smt added 
function show_branch(cid)
{ 
	$.get('branch_exec.php?customerid=' + cid , refresh_branch )
}

function refresh_branch(data)
{			
	$('#branch').html(data);
	/*
	if(data.length > 49)			
		jQuery("#selbranch").rules("add", {'required':true,'messages':{'required':'Please select branch name!'}});
	else
	{		
		jQuery(".error[for='selbranch']").remove();		
		jQuery("#selbranch").rules("remove");
	}
	*/
}

function show_gwtbranch(tid)
{ 
	$.get('gwtbranch_exec.php?techid=' + tid , refresh_gwtbranch )
}
function refresh_gwtbranch(data)
{			
	$('#gwtbranch').html(data);
}

function check_val(val)
{		
	if( val == 'Assign' )		
		jQuery("#seltechname").rules("add", {'required':true,'messages':{'required':' Technician name is required if your status is assign!'}});
	else
	{
		jQuery(".error[for='seltechname']").remove();				
		jQuery("#seltechname").rules("remove");
	}		
}
function chk_key(event)
{
	// the keycode for the key pressed
	var keyCode = event.which;	
	// 48-57 Standard Keyboard Numbers
	var isStandard = (keyCode > 47 && keyCode < 58);	
	// 96-105 Extended Keyboard Numbers (aka Keypad)
	var isExtended = (keyCode > 95 && keyCode < 106);	
	// 8 Backspace, 46 Forward Delete ,37 Left Arrow, 38 Up Arrow, 39 Right Arrow, 40 Down Arrow 
	var validKeyCodes = ',8,37,38,39,40,46,';
	var isOther = ( validKeyCodes.indexOf(',' + keyCode + ',') > -1 );
	//alert(validKeyCodes.indexOf(',' + keyCode + ','));	
	if ( isStandard || isExtended || isOther )
		return true;		
	else 		
		return false;		
}

function isEmail(str)
{
		var regex = /^[-_.a-z0-9]+@(([-a-z0-9]+\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i;
		return regex.test(str);
}

function isMultipleEmail(str)
{	
	var mySplitResult = str.split(new RegExp("[,;]","g"));
	for(i = 0; i < mySplitResult.length; i++)
	{
		//alert(mySplitResult[i] );
		if( mySplitResult[i] =='' || mySplitResult[i] !='' && isEmail(trim(mySplitResult[i]))==false)
			return false;
	}
	return true;
}
 //ynw added 
 function get_productname_by_customerid(cid)
 {
	//alert(cid);
	$.get('product_exec.php?customerid=' + cid ,refresh_product)
 }
 function refresh_product(data)
{	
	$('#product').html(data);
}
 function set_product_cookie(pid)
 {	
	jQuery.cookie('issuereport[selproduct]', pid);			
 }	
 function create_from_to_datepicker(fromdate_id, todate_id, yearRange)
	{
		if(yearRange == undefined)
			yearRange = "-50:+50";
		var dates = $('#'+ fromdate_id + ', #' + todate_id).datepicker(
		{
			"showAnim":"fold",
			"changeYear":true,
			"changeMonth":true,
			"dateFormat":"yy-mm-dd",
			"yearRange":yearRange,
			onSelect: function( selectedDate ) 
			{
				var option = this.id == fromdate_id ? "minDate" : "maxDate";					
				dates.not( this ).datepicker( "option", option, selectedDate );
			}
		});
	}
function create_onedate_datepicker(fromdate_id,yearRange)
{
	if(yearRange == undefined)
		yearRange = "-50:+50";
	var dates = $('#'+ fromdate_id).datepicker(
	{
		"showAnim":"fold",
		"changeYear":true,
		"changeMonth":true,
		"dateFormat":"yy-mm-dd",
		"yearRange":yearRange
	});
}
 /*start for user history show*/
function userhistoryshow(customerid)
{
	jQuery(".loading").show();
	jQuery("#show_result").html('');
	var name = jQuery("#hiddencustomername").val();
	jQuery("#hiddencustomerid").val(customerid);
  	$( "#dialog-form" ).dialog( "open" );
	$(".ui-dialog-title").html("The History Of "+htmlspecialchars(name));
	jQuery.get("showcustomerByid.php?customerid="+customerid, showuserhistory);
	return false;
}

function change_branch(id)
{
	var customerid = jQuery("#hiddencustomerid").val();
	jQuery.get("showcustomerByid.php?customerid=" + customerid+"&&branchid=" + id, showuserhistory);
	return false;
}

function showuserhistory(data)
{
	jQuery("#seltag").html(data.branchseltag);
	jQuery(".loading").hide();
	jQuery("#show_result").html(data.resultrows);
}
 /*end for user history show*/
 
 /*start for Search Reason popup show*/
function create_search_popup(resultplace_txtarea_id)
{
	jQuery.modal("<div id='popupsearch' style='overflow:auto;'><img src='images/loading.gif' class='loading' align='bottom'/></div><div class='popup'><img src='images/blue_delete.gif' style='float:right;cursor:pointer;position:absolute;top:0px;right:0px' class='simplemodal-close' /></div>",{overlayClose:true, opacity:60, overlayCss: {backgroundColor:"#ffffff"}, containerCss:{
		background:"#ffffff",
		borderColor:"#000000",
		borderWidth:"5px",
		borderStyle:"solid",
		height:350,
		overflow: "auto",
		padding:5,
		width:900
	}});
	replace_txtid = resultplace_txtarea_id;
	jQuery.get("servicereason_exec.php?search=1" , showsearchresult);
	return false;
}

function showsearchresult(data)
{
	jQuery('#popupsearch').html(data);
	$('#tblsaledetail  > tbody').on('dblclick', '>tr', function () {
		var name = $('td', this).eq(0).text();
		jQuery('#'+replace_txtid).val(name);
		$.modal.close();
    } );
}

function identity_search()
{
	var searchval = $('#txtsearchname').val();
	jQuery.get("servicereason_exec.php?searchval="+searchval , searchresult);
}

function showall_search()
{
	$('#loading').css('display','block');
	jQuery.get("servicereason_exec.php?search=1", showsearchresult);
}

function searchresult(res)
{
	$('#loading').css('display','none');
	var txtsearchname = $('#txtsearchname').val();
	jQuery.get("servicereason_exec.php?search="+txtsearchname , showsearchresult);		
}
 /*end for Search Reason popup show*/