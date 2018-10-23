<?
try {
	$gpi_database_connection = new PDO("mysql:host=localhost;dbname=ipullupull_inventory_mgt", "ipullupull_dev", 'k8$o0Upe');
	$gpi_database_connection->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$gpi_get_all_records = 'SELECT * FROM `crush_inventory_photos`';
}
catch(PDOException $err) {
	echo '
    <div class="alert alert-danger" role="alert">
      <p>Unable to connect to the database: ' . $err->getMessage() . '</p>
    </div>
  ';
}
?>
