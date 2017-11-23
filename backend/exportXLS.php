<?PHP
  require("phpexcel/PHPExcel.php");
  require('fpdf/fpdf.php');
  //error_reporting(E_ALL);
  //ini_set('display_errors', TRUE);
  //ini_set('display_startup_errors', TRUE);
  date_default_timezone_set('America/Mexico_City');

//if (PHP_SAPI == 'cli')
  //die('This example should only be run from a Web Browser');
  function cleanDataCSV(&$str)
  {
    if($str == 't') $str = 'TRUE';
    if($str == 'f') $str = 'FALSE';
    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
      $str = "'$str";
    }
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }
  function exportCSV($queryRst,$pantalla,$headers){
    // filename for download
    $filename = $pantalla."-". date('Y-m-d-h-i-s') . ".csv";

    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: text/csv");

    $out = fopen("php://output", 'w');

    $flag = false;
    foreach($queryRst['root'] as $row) {
      if(!$flag) {
        // display field/column names as first row
        fputcsv($out, $headers, ',', '"');
        $flag = true;
      }
      array_walk($row,'\cleanDataCSV');
      fputcsv($out, array_values($row), ',', '"');
    }

    fclose($out);
    exit;
  }
  function exportExcel($queryRst,$pantalla,$headers){
    // Create new PHPExcel object
    $filename = $pantalla."-". date('Y-m-d-h-i-s');
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $sheet = $objPHPExcel->getActiveSheet();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Menu Engeeniring")
                   ->setLastModifiedBy("Menu Engeeniring")
                   ->setTitle("Office 2007 XLSX Test Document")
                   ->setSubject("Office 2007 XLSX Test Document")
                   ->setDescription("Reporte de Sistema")
                   ->setKeywords("office 2007 openxml php")
                   ->setCategory("Reportes");
    $range = 'A1'.':'.'K'.($queryRst['records']+1);
    $sheet->getStyle($range)->getNumberFormat() ->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
    // Add some data
    for ($col = ord('a'),$i=0; $i < count($headers); $i++,$col++) {
      $sheet->getColumnDimension(chr($col))->setAutoSize(true);
    } 
    $sheet->fromArray($headers, NULL, 'A1'  );
    for($i=0,$index=2;$i<$queryRst['records'];$i++,$index++){
        $sheet->fromArray($queryRst['root'][$i], NULL, 'A' . $index);
    }
    $sheet->setTitle($pantalla);

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename='".$filename.".xlsx'");
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    //header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    //header ('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;

  }
  function exportPDF($queryRst,$pantalla,$headers){
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $arrayKeys = array_keys($queryRst['root'][0]);
    $width = ($pdf->w)-6;
    $widthCell = $width/$sizeof($headers);
    for($i = 0;$i<$queryRst['records'];$i++){
      if($pdf->GetY() > 270){
        $pdf->AddPage();
        $pdf = encabezado($fecha, $pdf);
      }
      $pdf->Cell(6);
      for($j=0;$j<count($arrayKeys);$j++){
        $pdf->Cell($widthCell,6,$queryRst['root'][0][$arrayKeys[$j]],0,0,'C');
      }
      $pdf->Ln(6);

    }
    $pdf->Output();
  }
   function encabezado($pdf,$idUsuario,$pantalla,$headers){
        $pdf->SetFont('Arial','B',11);
        # cabecero
        $EncabezadoCen1 = fn_ejecuta_query("SELECT * FROM usuariosTbl WHERE idUsuario = ".$idUsuario);
        $EncabezadoDer1 = date("Y-m-d");
        $EncabezadoCen2 = "REPORTE DE ".strtoupper($pantalla);
        $EncabezadoDer2 = "Hoja " . sprintf("%2d", $this -> PageNo());
        $pdf->Cell(6);
        $pdf->Cell(80);
        $pdf->Cell((strlen($EncabezadoCen1)),10,$EncabezadoCen1,0,0,'C');
        $pdf->Cell(52);
        $pdf->SetFont('Arial','',7);
        $pdf->Cell((strlen($EncabezadoDer1)),10,$EncabezadoDer1,0,0,'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(80);
        $pdf->Cell((strlen($EncabezadoCen2)),10,$EncabezadoCen2,0,0,'C');
        $pdf->Cell(50);
        $pdf->SetFont('Arial','',7);
        $pdf->Cell((strlen($EncabezadoDer2)),10,$EncabezadoDer2,0,0,'C');
        $pdf->Ln(5);
        
        # tabla
        $pdf->Ln();
        $pdf->SetFont('Arial','B',5);
        $pdf->SetFillColor(224,224,224);
        $width = ($pdf->w)-6;
        $widthCell = $width/$sizeof($headers);
        for($i = 0; $i< sizeof($headers);$i++){
          $pdf->Cell($widthCell,6, $headers[$i] ,1,0,'C',true);
        }
        $pdf->Ln(6);
        return $pdf;
    }

?>