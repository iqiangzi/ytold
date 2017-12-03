<?php 
$menu_flag = "product";
$pope	   = "pope_form";
include_once ("header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='robots' content='noindex,nofollow' />
<title><? echo SITE_NAME;?> - 管理平台</title>
<link rel="stylesheet" href="css/showpage.css" />
<script src="../scripts/jquery.min.js" type="text/javascript"></script>
<script src="../scripts/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
function Jtrim(str){ 
	return str.replace(/^\s*|\s*$/g,"");  
} 

function set_change_client_price(cid)
{
	var cp = $("#client_price_"+cid).val();

	$.post("do_product.php",
			{m:"do_set_save_client_price", cid: cid, clientprice:cp,vmsg:$("#vmsg").val()},
			function(data){
				data = Jtrim(data);
				if(data != "")
				{
					$("#vmsg").val(data);
					parent.set_input_price4(data);
					$.growlUI("设置成功");
				}		
			}		
		);
}
</script>
<style type="text/css">
<!--
body { margin:0; padding:0; font-size:12px; background-color:#ffffff; font-family: 'Lucida Grande', Verdana, sans-serif; color:#333;}
a{text-decoration:none; color:#277DB7; }
a:hover{text-decoration:underline; color:#cc0000}
td,div,p,strong{color:#333333; font-size:12px; line-height:150%; font-family: "微软雅黑", "宋体",Arial, Helvetica, sans-serif !important; font-family: "宋体",Arial, Helvetica, sans-serif;}
.tc thead tr td{font-weight:bold; background: #efefef; height:22px; padding:2px;}
.tc tbody tr td{ background: #ffffff; font-weight:100; height:22px; padding:2px;}
.tcheader{font-weight:bold; background: #efefef; height:25px; padding:2px;}
.button_2{ width:72px; height:24px; line-height:22px; font-weight:bold; color:#fff; border:0; margin:5px 0 0 5px; background:url(./img/anns.jpg) 0 0 no-repeat; cursor: pointer;margin-right:10px; font-family: "微软雅黑", "宋体",Arial, Helvetica, sans-serif !important; font-family: "宋体",Arial, Helvetica, sans-serif;}
.button_2:hover {background:url(./img/anns.jpg) 0 -26px no-repeat;}
.button_3{ width:72px; height:24px; line-height:22px; font-weight:bold; color:#fff; border:0; margin:5px 0 0 5px; background:url(./img/dnn5.jpg)  0 0 no-repeat; cursor: pointer;margin-right:10px;}
.button_3:hover {background:url(./img/dnn5.jpg) 0 -26px no-repeat;}

input{font-weight:100; font-size:12px;font-family: Verdana, Arial, Helvetica, sans-serif; color:#333333;}
form{margin:0; padding:0;}
.mainbtn {
    background: #0774bc;    border:#0774bc 1px solid;	background-image:url(./img/f2.jpg);    color: #fff;    font-size: 12px;  line-height:18px;  cursor: pointer;	margin:1px;	height:22px;	font-weight:bold; font-family: "微软雅黑", "宋体",Arial, Helvetica, sans-serif !important; font-family: "宋体",Arial, Helvetica, sans-serif;
}
.mainbtn:hover {
    background: #cccccc;	background-image:url(./img/dao_s.jpg);	height:22px; 
}
.blockUI p{margin:4px; padding:4px 10px; font-size:14px; font-weight:bold}
.growlUI {  }
.growlUI h1, div.growlUI h2 {
	color: white; padding: 5px 5px 5px 15px; text-align: left; font-size:14px;
}
-->
</style>
</head>

<body>
<?php

	$parr['clientprice'] = null;
	$setmsg = '';
	if(!empty($in['vmsg']))
	{
		$parr['clientprice'] = unserialize(urldecode($in['vmsg']));
		$setmsg = urlencode(serialize($parr['clientprice']));
	}
?>
<div >
		<table width="100%" border="0" cellspacing="4" cellpadding="0">
     			 <form name="changetypeform" id="changetypeform" action="set_product_client_price.php" method="post">
				 <input name="vmsg" id="vmsg" type="hidden" value='<? echo $setmsg;?>' />
				 <tr>
					<td align="left" width="120" align="center"><strong>搜索药店：</strong></td>
					<td align="left" width="160"> <input type="text" name="kw" id="kw" size="24" value="<? if(!empty($in['kw'])) echo $in['kw'];?>" onfocus="this.select();" style="width:150px; height:20px; line-height:20px; " /></td>
                 	<td align="left" width="160">					
					<select name="aid" id="aid" onchange="javascript:submit();" style="width:150px; height:24px; line-height:24px; ">
                    <option value="">⊙ 所有地区</option>
                    <?php 
					$sortarr = $db->get_results("SELECT AreaID,AreaParentID,AreaName,AreaPinyi,AreaAbout FROM ".DATATABLE."_order_area where AreaCompany=".$_SESSION['uinfo']['ucompany']." ORDER BY AreaID ASC ");
					echo ShowTreeMenu($sortarr,0,$in['aid'],1);
					?>
                  </select>
					</select></td>
					<td><input type="submit" name="buttonset" id="buttonset" value=" 搜 索 " class="button_2" />	</td>
     			 </tr></form>
          </table>
          <form id="MainForm" name="MainForm" method="post" action="" target="" >
		  <input name="cpid" id="cpid" type="hidden" value='<? echo $in['pid'];?>' />
		  <div style="font-size:16px; font-weight:bold; width:92%; maring:2px 10px; text-align:center;"><? echo $productinfo['Name'];?></div>
        	  <table width="96%" border="0" cellpadding="2" cellspacing="1" bgcolor="#CCCCCC" class="tc">               
                <thead>
				<tr>
				  <td width="6%" class="tcheader" align="center"><strong>序号</strong></td>
				  <td  class="tcheader">&nbsp;名称</td>
				  <td width="16%"  class="tcheader">&nbsp;联系人</td>
				  <td width="16%"  class="tcheader">&nbsp;价格(元)</td>
                </tr>
				</thead>
				<tbody>
<?php
	$clientrow = $db->get_row("SELECT count(*) AS allrow FROM ".DATATABLE."_order_client where ClientCompany = ".$_SESSION['uinfo']['ucompany']." and ClientFlag=0 ");
	
	$sqlmsg = '';
	if(!empty($in['aid']))
	{
		if(empty($areaarr_p[$in['aid']]))
		{
			$sqlmsg .= "and (ClientArea in (SELECT AreaID FROM ".DATATABLE."_order_area where AreaCompany=".$_SESSION['uinfo']['ucompany']." and (AreaParentID=".$in['aid']." OR AreaID=".$in['aid'].") ORDER BY AreaID ASC ) or ClientArea=".$in['aid'].") ";
		}else{
			$sqlmsg .= ' and ClientArea='.$in['aid'].' ';
		}
	}
	if(!empty($in['kw']))  $sqlmsg .= " and (ClientName like binary '%".$in['kw']."%' or ClientCompanyName like binary '%".$in['kw']."%' or ClientCompanyPinyi like binary '%".strtoupper($in['kw'])."%' ) ";
	$InfoDataNum = $db->get_row("SELECT count(*) AS allrow FROM ".DATATABLE."_order_client where ClientCompany = ".$_SESSION['uinfo']['ucompany']." ".$sqlmsg." and ClientFlag=0 ");
	$page = new ShowPage;
    $page->PageSize = 10;
    $page->Total   = $InfoDataNum['allrow'];
    $page->LinkAry = array("kw"=>$in['kw'],"aid"=>$in['aid'],"pid"=>$in['pid'],"vmsg"=>urlencode($in['vmsg']));        
	$datasql   = "SELECT ClientID,ClientLevel,ClientArea,ClientName,ClientCompanyName,ClientTrueName,ClientPhone FROM ".DATATABLE."_order_client where ClientCompany = ".$_SESSION['uinfo']['ucompany']." ".$sqlmsg." and ClientFlag=0  ORDER BY ClientID ASC";
	$list_data = $db->get_results($datasql." ".$page->OffSet());

	$n=1;
	if(!empty($list_data))
	{
     if(!empty($in['page'])) $n = ($in['page']-1)*$page->PageSize+1;
	 foreach($list_data as $lsv)
	 {
?>
                <tr id="line_<? echo $lsv['ClientID'];?>" >
                  <td align="center"><? echo $n++;?></td>
                  <td ><a href="client_content.php?ID=<? echo $lsv['ClientID'];?>" target="_blank"><? echo $lsv['ClientCompanyName'];?></a></td>
				  <td ><? echo $lsv['ClientTrueName'];?></td>
				  <td ><input type="text" name="client_price" id="client_price_<? echo $lsv['ClientID'];?>" onKeypress="if ((event.keyCode < 48 || event.keyCode > 57) && event.keyCode!=46) event.returnValue = false;" maxlength="10"  style="width:92%; height:18px; line-height:18px;" value="<? if(!empty($parr['clientprice'][$lsv['ClientID']])) echo $parr['clientprice'][$lsv['ClientID']];?>" onfocus="this.select();" onblur="set_change_client_price('<? echo $lsv['ClientID'];?>')" /></td>         
                </tr>
<?php } }else{?>
     			 <tr>
       				 <td colspan="5" height="30" align="center">暂无符合此条件的内容!</td>
       			 </tr>
<?php }?>
				</tbody>
              </table>			
              </form>
       	  </div>

          <table width="92%" border="0" cellspacing="0" cellpadding="4" align="center">
     			 <tr>
					<td  align="right"><? echo $page->ShowLink('set_product_client_price.php');?></td>  
     			 </tr>
          </table>
</body>
</html>
<?php
 	function ShowTreeMenu($resultdata,$p_id=0,$s_id=0,$layer=1) 
	{
		$frontMsg  = "";
		$frontTitleMsg = "";
		$selectmsg = "";		
		if($var['AreaParentID']=="0") $layer = 1; else $layer++;
					
		foreach($resultdata as $key => $var)
		{
			if($var['AreaParentID'] == $p_id)
			{
				$repeatMsg = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $layer);
				if($var['AreaID'] == $s_id) $selectmsg = "selected"; else $selectmsg = "";
				
				$frontMsg  .= "<option value='".$var['AreaID']."' ".$selectmsg." >".$frontTitleMsg.$repeatMsg.$var['AreaName']."</option>";		$frontMsg2  = "";
				$frontMsg2 .= ShowTreeMenu($resultdata,$var['AreaID'],$s_id,$layer);
				$frontMsg  .= $frontMsg2;
			}
		}		
		return $frontMsg;
	}
?>