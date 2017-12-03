<?php
$menu_flag = "order";
$pope	   = "pope_view";
include_once ("header.php");
include_once ("arr_data.php");

$clientdata = $db->get_results("select ClientID,ClientCompanyName,ClientCompanyPinyi from ".DATATABLE."_order_client where ClientCompany=".$_SESSION['uinfo']['ucompany']." and ClientFlag=0 order by ClientCompanyPinyi asc");

if(empty($in['cid'])) $in['cid'] = '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo SITE_NAME;?> - 管理平台</title>
<link href="css/main.css?v=<? echo VERID;?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/showpage.css" />
<link type="text/css" href="../plugin/jquery-ui/development-bundle/themes/base/ui.all.css" rel="stylesheet" />
<script src="../scripts/jquery.min.js" type="text/javascript"></script>
<script src="../scripts/jquery.blockUI.js" type="text/javascript"></script>
<script src="js/order.js?v=<? echo VERID;?>" type="text/javascript"></script>

<script type="text/javascript" src="../plugin/jquery-ui/development-bundle/ui/ui.core.js"></script>
<script type="text/javascript" src="../plugin/jquery-ui/development-bundle/ui/ui.datepicker.js"></script>
<script type="text/javascript">
	$(function(){
		$("#bdate").datepicker();
		$("#edate").datepicker();
	});
</script>
</head>

<body>
<? include_once ("top.php");?>
    
<div class="bodyline" style="height:25px;"></div>
<div class="bodyline" style="height:32px;"><div class="leftdiv" style="margin-top:8px; padding-left:12px;"><span><h4><?php echo $_SESSION['uc']['CompanyName'];?></h4></span><span valign="bottom">&nbsp;&nbsp;<? echo $_SESSION['uinfo']['usertruename']."(".$_SESSION['uinfo']['username'].")";?> 欢迎您！</span>&nbsp;&nbsp;<span>[<a href="change_pass.php">修改密码</a>]</span>&nbsp;&nbsp;<span>[<a href="do_login.php?m=logout">退出</a>]</span></div>
        <div class="rightdiv">
			<span style="width:35px; height:32px; overflow:hidden; float:left;"><img src="img/menu2_left.jpg" /></span>
            <span id="menu2">
            	<ul>
                  	<li ><a href="order.php">订单管理</a></li>	
					<li class="current2"><a href="order_product.php">订单商品</a></li>
					<li ><a href="statistics.php">订单统计</a></li>
                </ul>
            </span>
            <span style="width:9px; height:32px; overflow:hidden; float:left;"><img src="img/menu2_right.jpg" /></span>
        </div>
</div>    
    
    
    <div class="bodyline" style="height:70px; background-image:url(img/bodyline_bg.jpg);">
   		<div class="leftdiv"><img src="img/blue_left.jpg" /></div>
        <div class="leftdiv"><h1><? echo $menu_arr[$menu_flag];?></h1></div>
        <div class="rightdiv" style="color:#ffffff; padding-right:20px; padding-top:40px;">此栏目针对订单管理，主要包括订单处理，统计分析。</div>
    </div>

    <div id="bodycontent">
    	<div class="lineblank"></div>
    	<div id="searchline">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" >
        	  <form id="FormSearch" name="FormSearch" method="post" action="order_product.php">
        		<tr>
					<td width="80" align="center"><strong>订单商品：</strong></td> 
					<td width="120"><input type="text" name="kw" id="kw" class="inputline" value="<? if(!empty($in['kw'])) echo $in['kw'];?>"  onfocus="this.select();" /></td>
					<td width="180" align="center"><select id="cid" name="cid" style="width:160px;">
				<option value="" >⊙ 所有药店</option>
	<?php 
		$n = 0;
		foreach($clientdata as $areavar)
		{
			$n++;
			if($in['cid'] == $areavar['ClientID']) $smsg = 'selected="selected"'; else $smsg ="";
			$clientarr[$areavar['ClientID']] = $areavar['ClientCompanyName'];
			echo '<option value="'.$areavar['ClientID'].'" '.$smsg.' title="'.$areavar['ClientCompanyName'].'"  >'.substr($areavar['ClientCompanyPinyi'],0,1).' - '.$areavar['ClientCompanyName'].'</option>';
		}
	?>
				</select></td>
					<td align="center" width="80">起止时间：</td>
					<td width="200"><input type="text" name="bdate" id="bdate" class="inputline" style="width:80px;" value="<? if(!empty($in['bdate'])) echo $in['bdate'];?>" /> - <input type="text" name="edate" id="edate" class="inputline" style="width:80px;" value="<? if(!empty($in['edate'])) echo $in['edate'];?>" /></td>
       				<td width="60"><input name="searchbutton" type="submit" class="mainbtn" id="searchbutton" value="搜 索" /></td>
					<td aling="right"><div class="location"><strong>当前位置：</strong><a href="order.php">订单管理</a>  <? echo $locationmsg;?></div></td>
				</tr>
   	          </form>
			 </table>           
        </div>
    	
        <div class="line2"></div>
        <div class="bline">
		<fieldset  class="fieldsetstyle">
			<legend>订单商品明细数据</legend>
            <form id="MainForm" name="MainForm" method="post" action="order_excel.php" target="exe_iframe" >

<table width="100%" border="0" cellspacing="2" cellpadding="0" >
  <thead>
  <tr>
    <td width="5%" >&nbsp;行号</td>
	<td width="12%">编号/货号</td>
    <td>&nbsp;商品名称</td>
    <td width="14%">&nbsp;颜色/规格</td>
    <td width="6%" align="right">订购数</td>    
    <td width="6%" align="right">发货数</td>
	<td width="8%" align="right">订购价</td>  	
	<td width="5%" align="center">单位</td>
    <td width="12%" align="right">订单号&nbsp;</td> 
  </tr>
   </thead>
   <tbody>
	<? 
			$sqlmsg = '';
			if(!empty($in['cid']))  $sqlmsg .= " and c.ClientID=".$in['cid']." ";

			if(!empty($in['kw']))  $sqlmsg .= " and (i.Name like binary '%%".$in['kw']."%%' or i.Coding like binary '%%".$in['kw']."%%' or i.Pinyi like binary '%%".$in['kw']."%%') ";

			if(!empty($in['bdate'])) $sqlmsg .= ' and o.OrderDate > '.strtotime($in['bdate'].'00:00:00').' ';
			if(!empty($in['edate'])) $sqlmsg .= ' and o.OrderDate < '.strtotime($in['edate'].'00:00:00').' ';
			
			$InfoDataNum = $db->get_row("SELECT count(*) AS allrow from ".DATATABLE."_order_cart c left join ".DATATABLE."_order_orderinfo o on c.OrderID=o.OrderID  left join ".DATATABLE."_order_content_index i on c.ContentID=i.ID  where  c.CompanyID=".$_SESSION['uinfo']['ucompany']." and i.CompanyID=".$_SESSION['uinfo']['ucompany']." and o.OrderStatus!=8 and o.OrderStatus!=9 ".$sqlmsg." ");

			$page = new ShowPage;
			$page->PageSize = 20;
			$page->Total   = $InfoDataNum['allrow'];
			$page->LinkAry = array("kw"=>$in['kw'],"cid"=>$in['cid'],"bdate"=>$in['bdate'],"edate"=>$in['edate']);  

			$datasql = "select c.ID,c.OrderID,c.ContentID,c.ContentName,c.ContentColor,c.ContentSpecification,c.ContentPrice,c.ContentNumber,c.ContentPercent,c.ContentSend,o.OrderSN,i.Coding,i.Units from ".DATATABLE."_order_cart c left join ".DATATABLE."_order_orderinfo o on c.OrderID=o.OrderID  left join ".DATATABLE."_order_content_index i on c.ContentID=i.ID  where  c.CompanyID=".$_SESSION['uinfo']['ucompany']." and i.CompanyID=".$_SESSION['uinfo']['ucompany']." and o.OrderStatus!=8 and o.OrderStatus!=9 ".$sqlmsg." order by c.ID desc ";
			$list_data = $db->get_results($datasql." ".$page->OffSet());
			$n = 1;
			if(!empty($list_data))
			{
				if(!empty($in['page'])) $n = ($in['page']-1)*$page->PageSize+1;
				foreach($list_data as $ckey=>$cvar)
				{
	?>
    <tr id="linegoods_<? echo $cvar['ID'];?>" <? if(fmod($ckey,2)==0) echo 'style="background-color:#f9f9f9;"'; else echo 'style="background-color:#ffffff;"';?>  >
    <td height="30">&nbsp;<? echo $n++;?></td>
	<td ><? echo $cvar['Coding'];?></td>
    <td ><a href="product_content.php?ID=<? echo $cvar['ContentID'];?>" target="_blank"><? echo $cvar['ContentName'];?></a></td>
    <td>&nbsp;<?if(!empty($cvar['ContentColor'])) echo $cvar['ContentColor'];?> / <?if(!empty($cvar['ContentSpecification'])) echo $cvar['ContentSpecification'];?> </td>
    <td align="right" title="<? echo $cvar['Units'];?>"><? echo $cvar['ContentNumber'];?>	</td>

    <td align="right"><? echo $cvar['ContentSend'];	?></td>
	<td align="right">¥ <? 
		echo $pricepencent = $cvar['ContentPrice']*$cvar['ContentPercent']/10;
	?> </td>
	<td align="center"><? echo $cvar['Units'];?></td> 

    <td  align="right"><a href="order_manager.php?ID=<? echo $cvar['OrderID'];?>" target="_blank"><? echo $cvar['OrderSN'];?></a>&nbsp;</td>
  </tr>
   <? }}?> 
   </tbody>
</table>


              <table width="98%" border="0" cellspacing="0" cellpadding="0">
     			 <tr>
				   <td  align="right"><? echo $page->ShowLink('order_product.php');?></td>
     			 </tr>
              </table>

              <INPUT TYPE="hidden" name="referer" value ="" >
              </form>
			  </fieldset>
       	  </div>
        <br style="clear:both;" />
    </div>

<? include_once ("bottom.php");?>
<iframe name="exe_iframe" style="width:0; height:0;" frameborder="0" scrolling="no"></iframe>  
</body>
</html>