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
	jQuery(document).ready(function()
	{
        //---- set background for active menu -----
        if(localStorage.getItem("current_page") != undefined){
            for(var i=0; i < $("#nav ul li").length; i++){
                if($($("#nav ul li")[i]).html() == localStorage.getItem("current_page")){
                    $($("#nav ul li")[i]).css("background", '#b12226');
                }
            }
        }
        //---- End -----

		var sWidth= jQuery('body').width();
		sWidth = sWidth - 240;		
		jQuery('#prepaid_card_dtList').attr('width',sWidth);
		
		loadpagestate();
		if(jQuery.cookie('cardList[iDisplayStart]')==null)
			jQuery.cookie('cardList[iDisplayStart]', 0);

		if(jQuery.cookie('cardList[iDisplayLength]')==null)
			jQuery.cookie('cardList[iDisplayLength]', 10);
		
		if(jQuery.cookie('cardList[aaSorting]')==null)
		{
			jQuery.cookie('cardList[aaSorting]', "[[0,'asc']]");
			aasorting = [[0,'asc']];
		}
		else
		{
			aasorting = eval('(' + jQuery.cookie('cardList[aaSorting]') + ')'); //convert json string to json object
		}		
		ilength = parseInt(jQuery.cookie('cardList[iDisplayLength]'));
		istart = parseInt(jQuery.cookie('cardList[iDisplayStart]'));		
		sFilter=getFilter();	
		
		oTable=jQuery('#prepaid_card_dtList').dataTable({
			"iDisplayLength": ilength,
			"iDisplayStart": istart,
			"aaSorting": aasorting,
			"bProcessing": true,
			"bSortable": true,
			"asSorting": [ 'asc', 'desc' ],
			"lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ]	,	
            "sDom": 'Rfrtlip',	
			"bServerSide": true,
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			"bFilter": true,			
			"oSearch": {"sSearch": sFilter},			 
			"fnDrawCallback": function() {	
				var oSettings = oTable.fnSettings();
				var aaSorting = JSON.stringify(oSettings.aaSorting); //convert json object to json string because cookie only allow to save string
				jQuery.cookie('cardList[iDisplayLength]', oSettings._iDisplayLength);
				jQuery.cookie('cardList[iDisplayStart]', oSettings._iDisplayStart);
				jQuery.cookie('cardList[aaSorting]', aaSorting);

                var tds = $(".dataTables_wrapper td");
                for(var i=0;i<tds.length;i++){
                    if(i%9==8){
                        if($(tds[i]).html().indexOf("div") == -1){
                            var id = $(tds[i]).html();
                            $(tds[i]).html("<div class='edit-button' id='edit_btn_" + id + "'> Edit </div>");
                        }
                    }
					if(i%9==7){
						if($(tds[i]).html() == 'Active'){
							$(tds[i]).css('color', 'red');
						}
                        // if($(tds[i]).html().indexOf("div") == -1){
                        //     var id = $(tds[i]).html();
                        //     $(tds[i]).html("<div class='edit-button' id='edit_btn_" + id + "'> Edit </div>");
                        // }
                    }
                }

                //-------- when click Edit button -----
                setTimeout(() => {
                    $(".edit-button").click(function(){
                        console.log($(this).attr('id'));
                        var x = $(this).attr('id');
                        var id = x.split('_')[2];
                        window.location.replace('<?php echo $rootpath;?>/prepaid_card_edit.php?id=' + id);
                    });
                }, 1000);
				
			},	
			
			"bAutoWidth": false,
			"bEscapeRegex": false,
			"sAjaxSource": "get_prepaid_card.php",
			
			"aoColumns": [						
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},	
							{"bSortable": false,"sWidth":"auto"},											
							{"bSortable": false,"sWidth":"auto"},
							{"bSortable": false,"sWidth":"auto"},
							{"bSortable": false,"sWidth":"auto"}
						]
		});	
		jQuery('.dataTables_filter').hide();
		
		$('#btnsearch_a').click(function(){
			if($('#search_filter_by').val() == '-1'){
				$('#search_filter_by').focus();
				return;
			}
			$('#btnsearch').trigger('click');
		});
	});

	function loadpagestate()
	{
		updatecontrol('#search_txt_studentid', jQuery.cookie('cardList[search_txt_studentid]'));
		updatecontrol('#search_filter_by', jQuery.cookie('cardList[search_filter_by]'));
		updatecontrol('#search_txt', jQuery.cookie('cardList[search_txt]'));
	}
	function updatecontrol(parctl, parvalue)
	{
		if(parvalue)		
			jQuery(parctl).attr('value', parvalue);		
	}
	function getFilter()
	{
		var jsonfilter = {};
		jsonfilter.search_txt_studentid = jQuery.cookie('cardList[search_txt_studentid]');
		jsonfilter.search_filter_by = jQuery.cookie('cardList[search_filter_by]');
		jsonfilter.search_txt = jQuery.cookie('cardList[search_txt]');
		var cri_str = JSON.stringify(jsonfilter);
		return cri_str;
		
	}
	function savepagestate()
	{
		jQuery.cookie('cardList[search_txt_studentid]', jQuery('#search_txt_studentid').val());
		jQuery.cookie('cardList[search_filter_by]', jQuery('#search_filter_by').val());
		jQuery.cookie('cardList[search_txt]', jQuery('#search_txt').val());
		return true;
	}
	function clearpagestate()
	{
		jQuery.cookie('cardList[search_txt_studentid]', null);				
		jQuery.cookie('cardList[iDisplayStart]', null);
		return true;
	}
	function changeSearchKey(e){
		console.log($(e).val());
		switch($(e).val()){
			case '-1':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "");
				return;
			case '0':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "User code");
				$('#search_txt').focus();
				return;
			case '1':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Card Number");
				$('#search_txt').focus();
				return;
			case '2':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Last Name");
				$('#search_txt').focus();
				return;
			case '3':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "First Name");
				$('#search_txt').focus();
				return;
			case '4':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Level");
				$('#search_txt').focus();
				return;
			case '5':
				$('#search_txt').val('');
				$('#search_txt').attr("placeholder", "Family Code");
				$('#search_txt').focus();
				return;
			default:
				return;
		}
	}

</script>

<label id="successmes" name="successmes" style="color:red;" >&nbsp;</label>

<style type="text/css">
	
</style>

<div class="content_data">
	<form id='frmcard' name='frmcard' method='POST'>
	
        <h2>Prepaid Card</h2>
        <table class="control-section">
            <tr> <span style="color:black; font-size:15px; font-weight:500;"> Search By </span> </tr>
            <tr>
                <td style="width:70%">
                    <div class="left-section">
                        <select class="select-custom" id="search_filter_by" name="search_filter_by" onChange="changeSearchKey(this)"> 
                            <option value='-1'> Please choose </option>
                            <option value='0'> User code </option>
                            <option value='1'> Card Number </option>
                            <option value='2'> Last Name </option>
							<option value='3'> First Name </option>
							<option value='4'> Level </option>
							<option value='5'> Family Code </option>
						</select>
                        &nbsp;&nbsp;&nbsp;
						<input type="text" class="input-text-custom" id="search_txt" name="search_txt" style="width:25%;"> </input>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a id="btnsearch_a" class="control-button"> Search </a>
						<input type="submit" id="btnsearch" name="btnsearch" value="Search" onclick=" return savepagestate() " class="btn" style="display:none"/>
                    <div>
                </td>
                <td style="width:30%;text-align:right;">
                    <a href='<?php echo $rootpath;?>/add_new_card.php' class="control-button"> Add New Card </a>
                </td>
            </tr>
        </table>
		<!--showing msgs-->
			<!-- <p>&nbsp;</p> -->
			<?php
				echo "<div id='message'>";
				if(isset($_SESSION['msg']))
				{
					$msg = $_SESSION['msg'];
					echo $msg;
				}
				echo "</div><br/>";
				unset ( $_SESSION ['msg'] );
				session_write_close ();
			?>
		<!--showing msgs-->
		
		<!--Searching criteria-->
		<!-- <div style="float: left; width: 50%;">
			<div class="frm">
				<div class="frm_label">Student Id : </div>			
				<input type="text" id="search_txt_studentid" name="search_txt_studentid" >
			</div>

			<div class="frm">
				<div class="frm_label">&nbsp;</div>
				<input type="submit" id="btnsearch" name="btnsearch" value="Search" onclick=" return savepagestate() " class="btn" /> &nbsp;
				<input type="submit" id="btnshowall" name="btnshowall" value="Show All" onclick="clearpagestate()" class="btn" />		
			</div>
		</div> -->
		<!--Searching criteria-->
		
		<!--datatable-->
		<div class="cleaner"></div>
		<table cellpadding="0"  border="0" class="display" id="prepaid_card_dtList" name="prepaid_card_dtList">
			<thead>
				<tr>							
					<th>User code</th>
					<th>Card Number</th>
					<th>Last Name</th>
					<th>First Name</th>
                    <th>Level</th>
					<th>Family code</th>
					<th>Card Value</th>
					<th>Card Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="9" class="dataTables_empty">Loading data from server</td>
				</tr>
			</tbody>
		</table>
		<!--datatable-->
		
	</form>
</div>

<?php
	include("footer.php");
?>

<style>
    .control-section{
        width:100%;
    }
</style>