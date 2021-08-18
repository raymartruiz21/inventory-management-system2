<?php

//view_order.php

if(isset($_GET["pdf"]) && isset($_GET['order_id'])) {


	// require_once 'pdf.php';
	require_once 'fpdf/fpdf.php';
	include('database_connection.php');
	include('function.php');
	if(!isset($_SESSION['type'])) {
		header('location:login.php');
	}
	
	$statement = $connect->prepare("
		SELECT * FROM order_tbl 
		WHERE order_id = :order_id
		LIMIT 1
	");

	$statement->execute(
		array(
			':order_id'       =>  $_GET["order_id"]
		)
	);

	$result = $statement->fetchAll();

	$pdf = new FPDF('P','mm','A4');
	$pdf->AddPage();
	$pdf->Image('img/st2.png',10,6,30);
	$pdf->Cell(130,5,'',0,1);

	$pdf->SetFont('Arial','B',14);
	$pdf->Cell(130,5,'SWEET TREAT SHOP',0,0);
	$pdf->Cell(59,5,'INVOICE',0,1);

	$pdf->SetFont('Arial','',12);
	$pdf->Cell(130,5,'Pawili, Pili, Camarines Sur',0,0);
	$pdf->Cell(130,5,'',0,1);
	$pdf->Cell(130,5,'4418',0,0);

	foreach($result as $row) {
		$pdf->Cell(25,5,'Date:',0,0);
		$pdf->Cell(34,5,$row['order_created_date'],0,1);
		$pdf->Cell(130,5,'#09777895621',0,0);
		$pdf->Cell(25,5,'Invoice No:',0,0);
		$pdf->Cell(34,5,$row['order_id'],0,1);

		$pdf->Cell(189,10,'',0,1);

		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(10,5,'Bill to:',0,1);

		$pdf->SetFont('Arial','',12);
		$pdf->Cell(40,5,'Reciever name:',0,0);
		$pdf->Cell(90,5,strtoupper($row["order_name"]),0,1);
		$pdf->Cell(40,5,'Reciever Address:',0,0);
		$pdf->Cell(90,5,strtoupper($row["order_address"]),0,1);

		$pdf->Cell(189,10,'',0,1);
		$pdf->Cell(40,5,'Payment Status:',0,0);
		$pdf->Cell(90,5,ucfirst($row["payment_status"]),0,1);

		$pdf->Cell(189,10,'',0,1);

		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(20,5,'No.',1,0);
		$pdf->Cell(60,5,'Product',1,0);
		$pdf->Cell(30,5,'Quantity',1,0);
		$pdf->Cell(40,5,'Price',1,0);
		$pdf->Cell(40,5,'Amount',1,1);

		$statement = $connect->prepare("
			SELECT * FROM invoice 
			WHERE order_id = :order_id
		");
		$statement->execute(
			array(
				':order_id'       =>  $_GET["order_id"]
			)
		);
		$product_result = $statement->fetchAll();
		$count = $total = $total_actual_amount = $actual_amount = $total_product_amount = 0;
		// $product_data = '';
		foreach($product_result as $sub_row) {
			
			$count = $count + 1;
			$product_data = fetch_product_details($sub_row['product_id'], $connect);
			$actual_amount = $sub_row["quantity"] * $sub_row["price"];
			$total_product_amount = $actual_amount + $total_product_amount;
			$total_actual_amount = $total_actual_amount + $actual_amount;
			$total = $total + $total_product_amount;

			$pdf->SetFont('Arial','',12);
			$pdf->Cell(20,5,$count,1,0);
			$pdf->Cell(60,5,$product_data['product_name'],1,0);
			$pdf->Cell(30,5,$sub_row["quantity"],1,0);
			$pdf->Cell(40,5,$sub_row["price"],1,0);
			$pdf->Cell(40,5,number_format($actual_amount, 2),1,1);
		}
		$pdf->Cell(150,5,'Total of Order:',1,0);
		$pdf->Cell(40,5,number_format($total_actual_amount, 2),1,0);

		$pdf->Cell(189,10,'',0,1);
		$pdf->Cell(189,10,'',0,1);
		$pdf->Cell(189,10,'',0,1);
		$pdf->Cell(189,10,'',0,1);
		$pdf->SetFont('Arial','I',12);
		$pdf->Cell(110,5,'',0,0);
		$pdf->Cell(40,5,'______________________________',0,0);
		$pdf->Cell(189,10,'',0,1);
		$pdf->Cell(130,5,'',0,0);
		$pdf->Cell(59,5,'Receiver Signature',0,1);
	}
	$pdf->Output();
}

?>