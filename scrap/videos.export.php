
<?php
	//include connection file 
	include_once("includes\dbh.inc.php");
	 
	// initilize all variable
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	//define index of column
	$columns = array( 
		0 => 'action',
		1 =>'videoID',
		2 =>'licenseNum', 
		3 => 'violation',
		4 => 'url',
		5 => 'date_time',
		6 => 'status',
		7 => 'badgeID',
	);

	$where = $sqlTot = $sqlRec = "";

	// check search value exist
	if( !empty($params['search']['value']) ) {   
		$where .=" WHERE ";
		$where .=" ( videoID LIKE '".$params['search']['value']."%' ";    
		$where .=" OR licenseNum LIKE '".$params['search']['value']."%' ";
		$where .=" OR violation LIKE '".$params['search']['value']."%' ";
		$where .=" OR url LIKE '".$params['search']['value']."%' ";
		$where .=" OR date_time LIKE '".$params['search']['value']."%' ";
		$where .=" OR status LIKE '".$params['search']['value']."%' ";
		$where .=" OR badgeID LIKE '".$params['search']['value']."%' )";
	}

	// getting total number records without any search
	$sql = "SELECT * FROM `video` ";
	$sqlTot .= $sql;
	$sqlRec .= $sql;
	//concatenate search sql if value exist
	if(isset($where) && $where != '') {

		$sqlTot .= $where;
		$sqlRec .= $where;
	}


 	$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";

	$queryTot = mysqli_query($conn, $sqlTot) or die("database error:". mysqli_error($conn));


	$totalRecords = mysqli_num_rows($queryTot);

	$queryRecords = mysqli_query($conn, $sqlRec) or die("error to fetch employees data");

	//iterate on results row and create new index array of data
	while( $row = mysqli_fetch_array($queryRecords) ) {
		$fieldname1 = $row['videoID'];
		$editdata = '<div>
			<form action="videoform.php" method="post">
			<div class="mb-05 content-hcenter weight-50">
			<input type="hidden" class="" maxlength="256" name="videoedit-id" data-name=""
			placeholder="" id="input" value="' . $fieldname1 . '" />
			<input type="submit" class="btn btn-default" name="Edit" value="Edit"  data-name="Edit" placeholder="" id="Edit"/></form>';
	
		$deletedata = '<div>
			<form action="videoform.php" method="post">
			<div class="mb-05 content-hcenter weight-50">
			<input type="hidden" class="" maxlength="256" name="videoDelete-id" data-name=""
			placeholder="" id="input" value="' . $fieldname1 . '" />
			<input type="submit" class="btn btn-danger" name="Delete" value="Delete"  data-name="Delete" placeholder="" id="Delete"/></form>';
	
		$actiondata = $editdata . ' ' . $deletedata;
	
		$rowData = array();
		$rowData[] = $actiondata;
		$rowData[] = $row['videoID'];
		$rowData[] = $row['licenseNum'];
		$rowData[] = $row['violation'];
		$rowData[] = $row['url'];
		$rowData[] = $row['date_time'];
		$rowData[] = $row['status'];
		$rowData[] = $row['badgeID'];
	
		$data[] = $rowData;
	}
	
	

	$json_data = array(
			"draw"            => intval( $params['draw'] ),   
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval($totalRecords),
			"data"            => $data   // total data array
			);

	echo json_encode($json_data);  // send data as json format
?>
	