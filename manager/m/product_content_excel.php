<?php 
$menu_flag = "product";
$pope	   = "pope_view";
include_once ("header.php");
include_once ('../class/PHPExcel.php');
$objPHPExcel = new PHPExcel();

if(empty($in['ID']))
{
	Error::AlertJs('请选择您要导出的商品!');
	exit;
}


	$typearr = array(
		'0'			=>  '默认',
		'1'			=>  '推荐',
		'2'			=>  '特价',
		'3'			=>  '新款',
		'4'			=>  '热销',
		'9'			=>  '缺货',
 	 );


$productinfo = $db->get_row("SELECT i.*,c.* FROM ".DATATABLE."_order_content_index i left join ".DATATABLE."_order_content_1 c on i.ID=c.ContentIndexID where i.CompanyID=".$_SESSION['uinfo']['ucompany']." and i.ID=".intval($in['ID'])." limit 0,1");
if(empty($productinfo['ID'])) 
{
	Error::AlertJs('此商品不存在，或已经删除!');
	exit();
}

$sortarr = $db->get_row("SELECT SiteID,ParentID,SiteName FROM ".DATATABLE."_order_site where CompanyID=".$_SESSION['uinfo']['ucompany']." and SiteID=".$productinfo['SiteID']." ORDER BY SiteOrder DESC,SiteID ASC limit 0,1");

$objPHPExcel->getProperties()->setCreator("医统天下 订货管理系统 DHB.HK")
							 ->setLastModifiedBy("DingHuoBao")
							 ->setTitle("医统天下-商品数据")
							 ->setSubject("医统天下-商品数据")
							 ->setDescription("医统天下-商品数据")
							 ->setKeywords("医统天下 订货管理系统")
							 ->setCategory("商品数据");

$objPHPExcel->setActiveSheetIndex(0);
$objActSheet = $objPHPExcel->getActiveSheet();
$objActSheet->setTitle('商品详细');
$objActSheet->getDefaultRowDimension()->setRowHeight(20);

$objActSheet->setCellValue('A1', $productinfo['Name']);
$objActSheet->mergeCells('A1:B1');
$objStyleA5 = $objActSheet->getStyle('A1'); 
//设置对齐方式
$objStyleA5->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//设置字体    
$objFontA5 = $objStyleA5->getFont();   
$objFontA5->setName('黑体' );   
$objFontA5->setSize(14);   
$objFontA5->setBold(true);

//$objActSheet->getColumnDimension('A' )->setAutoSize(true);
$objActSheet->getColumnDimension('A' )->setWidth(25);
$objActSheet->getColumnDimension('B' )->setWidth(85);
$objActSheet->getRowDimension('1')->setRowHeight(32);
		
		$k = 1;
		$k++;
		$objActSheet->setCellValue('A'.$k, '商品分类：');
		$objActSheet->setCellValue('B'.$k, $sortarr['SiteName']);
		
		$k++;
		$objActSheet->setCellValue('A'.$k, '编号：');
		$objActSheet->setCellValue('B'.$k, $productinfo['Coding']);
		
		$k++;
		$objActSheet->setCellValue('A'.$k, '计量单位：');
		$objActSheet->setCellValue('B'.$k, $productinfo['Units']);		
		
		$k++;
		$objActSheet->setCellValue('A'.$k, '厂 价：');
		$objActSheet->setCellValue('B'.$k, '¥ '.$productinfo['Price1']);
		
		$k++;
		$objActSheet->setCellValue('A'.$k, '批发价：');
		$objActSheet->setCellValue('B'.$k, '¥ '.$productinfo['Price2']);

				  if(!empty($productinfo['Price3']))
				  {
						$parr = unserialize($productinfo['Price3']);
						$valuearr = get_set_arr('clientlevel');
						if(!empty($valuearr))
					    {
							$l = $k+1;
							$objActSheet->setCellValue('A'.$l, '药店等级价格：');
							foreach($valuearr as $key=>$var)
							{
								$k++;								
								$objActSheet->setCellValue('B'.$k, $var.': ¥ '.$parr[$key]);
							}
							$objActSheet->mergeCells('A'.$l.':A'.$k);
					    }
				  }		

		
		$k++;
		$objActSheet->setCellValue('A'.$k, '商品包装：');
		$objActSheet->setCellValue('B'.$k, $productinfo['Casing']);
		
		$k++;
		$objActSheet->setCellValue('A'.$k, '可选颜色：');
		$objActSheet->setCellValue('B'.$k, $productinfo['Color']);

		$k++;
		$objActSheet->setCellValue('A'.$k, '可选规格：');
		$objActSheet->setCellValue('B'.$k, $productinfo['Specification']);

		$k++;
		$objActSheet->setCellValue('A'.$k, '搜索关键词(TAG)：');
		$objActSheet->setCellValue('B'.$k, $productinfo['ContentKeywords']);

		$k++;
		$objActSheet->setCellValue('A'.$k, '排序权重：');
		$objActSheet->setCellValue('B'.$k, $productinfo['OrderID']);	
		
		$k++;
		$objActSheet->setCellValue('A'.$k, '商品类型：');
		$objActSheet->setCellValue('B'.$k, $typearr[$productinfo['CommendID']]);

		$k++;
		$objActSheet->setCellValue('A'.$k, '商品人气：');
		$objActSheet->setCellValue('B'.$k, $productinfo['Count']);	
		$objActSheet->getStyle('A2:A'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objActSheet->getStyle('A2:A'.$k)->getFont()->setBold(true);
		$objActSheet->getStyle('B2:B'.$k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

				$imginfo = $db->get_results("SELECT Name,Path FROM ".DATATABLE."_order_resource where IndexID=".$productinfo['ID']." and CompanyID=".$_SESSION['uinfo']['ucompany']."");

				if(!empty($imginfo))
				{
					foreach($imginfo as $imgvar)
					{
						$k++;
						$imginfoarr = getInfo(RESOURCE_URL.$imgvar['Path'].'img_'.$imgvar['Name'].'');
						$objActSheet->getRowDimension($k)->setRowHeight($imginfoarr['height']);
						$objActSheet->mergeCells('A'.$k.':B'.$k);					

						$objDrawing = new PHPExcel_Worksheet_Drawing();
						$objDrawing->setName('商品图片');
						$objDrawing->setDescription('商品图片');
						$objDrawing->setPath(RESOURCE_URL.$imgvar['Path'].'img_'.$imgvar['Name'].'');
						$objDrawing->setHeight($imginfoarr['height']);
						$objDrawing->setCoordinates('A'.$k);
						$objDrawing->setOffsetX(10);
						$objDrawing->setWorksheet($objActSheet);
					}
				}else{				  
					if(!empty($productinfo['Picture']))
					{
				  		$k++;				
						$objActSheet->mergeCells('A'.$k.':B'.$k);
						$imginfoarr = getInfo(RESOURCE_URL.str_replace("thumb_","img_",$productinfo['Picture']).'');
						$objActSheet->getRowDimension($k)->setRowHeight($imginfoarr['height']);

						$objDrawing = new PHPExcel_Worksheet_Drawing();
						$objDrawing->setName('商品图片');
						$objDrawing->setDescription('商品图片');
						$objDrawing->setPath(RESOURCE_URL.str_replace("thumb_","img_",$productinfo['Picture']).'');
						$objDrawing->setHeight($imginfoarr['height']);
						$objDrawing->setCoordinates('A'.$k);
						$objDrawing->setOffsetX(10);
						$objDrawing->setWorksheet($objActSheet);

					}
				}
		$k++;
		$objActSheet->setCellValue('A'.$k, '详细描述：');
		$objActSheet->getStyle('A'.$k)->getFont()->setBold(true);
		$objActSheet->mergeCells('A'.$k.':B'.$k);
		$stringContent = html_entity_decode($productinfo['Content'], ENT_QUOTES,'UTF-8');
		$stringContent = preg_replace("#<.+?>#is", "", $stringContent);
		$k++;
		$objActSheet->setCellValue('A'.$k, $stringContent);
		$objActSheet->mergeCells('A'.$k.':B'.$k);
		$objActSheet->getRowDimension($k)->setRowHeight(180);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$k)->getAlignment()->setWrapText(true);

	//设置边框
	$styleThinBlackBorderOutline = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => 'FF666666'),
				),
			),
		);
	$objPHPExcel->getActiveSheet()->getStyle('A2:B'.$k)->applyFromArray($styleThinBlackBorderOutline);
	$objActSheet->getStyle('A2:B'.$k)->getFont()->setSize(10);

$ua = $_SERVER["HTTP_USER_AGENT"];
$filename = '商品详细_'.$productinfo['ID'].'.xls';
$encoded_filename = urlencode($filename);
$encoded_filename = str_replace("+", "%20", $encoded_filename);
header('Content-Type: application/vnd.ms-excel');
if (preg_match("/MSIE/", $ua)) {
	header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
} else if (preg_match("/Firefox/", $ua)) {
	header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '"');
} else {
	header('Content-Disposition: attachment; filename="' . $filename . '"');
}
header('Cache-Control: max-age=0');	


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;

function getInfo($file)
{
	$data = getimagesize($file);
	$imageInfo["width"] = $data[0];
	$imageInfo["height"]= $data[1];
	$imageInfo["type"]  = $data[2];
	$imageInfo["name"]  = basename($file);
	return $imageInfo;
}
?>