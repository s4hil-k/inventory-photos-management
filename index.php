<?php include_once('header.php'); ?>

		<div class="col-12">
			<?php include_once('gpi-upload.php'); ?>
		</div>

    <div class="col-4">

			<h1>Image Uploader</h1>
			<!-- begin form -->
			<form action="?" method="post" name="image_upload" id="image_upload" enctype="multipart/form-data">

				<p><label>You may upload files that end with .gif, .jpg and .png</label></p>

				<table class="form-group table table-striped table-bordered">
					<thead>
						<th>
							<p>Image to Upload</p>
						</th>
						<th colspan="2">
							<p>Stock ID for Image</p>
						</th>
					</thead>
					<tr>
						<td>
							<input class="form-control-file" type="file" size="45" name="uploadfile" id="uploadfile" class="file margin_5_0" />
						</td>
						<td>
							<input class="form-control form-control-lg" type='text' name='gpi_sku' placeholder="Ex. #STK00001" />
						</td>
						<td>
							<button class="btn btn-dark btn-lg">Submit</button>
						</td>
					</tr>
				</table>
			</form>
		</div><!-- end of .grid-item -->


		<div class="col-8">
			<h1>Uploaded Images</h1>
			<p>Click the image to see the enlarged version.</p>
			<?
			$gpi_original_images_directory = 'original-images';
			$gpi_resized_images_directory = 'resized-images';
			$gpi_scan_original_images_directory = array_slice(scandir($gpi_original_images_directory), 2);
			$gpi_scan_resized_images_directory = array_slice(scandir($gpi_resized_images_directory), 2);

			$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			?>

			<table class="table table-striped table-bordered">
				<thead>
					<th width="25px">Stock Id</th>
					<th>Resized Image</th>
					<th>Original Filename</th>
					<th>Resized Filename</th>
				</thead>

				<?
				foreach ( $gpi_database_connection->query($gpi_get_all_records) as $gpi_row) {
					$gpi_from_database_id = $gpi_row['gpi_id'];
					$gpi_from_database_sku = $gpi_row['gpi_sku'];
					$gpi_from_database_original_filename = $gpi_row['gpi_original_filename'];
					$gpi_from_database_resized_filename = $gpi_row['gpi_resized_filename'];
					$gpi_from_database_original_filename_edited = strtolower(preg_replace('/\s/', '_', $gpi_from_database_original_filename));
					$gpi_filename_extension = substr($gpi_from_database_original_filename_edited, strpos($gpi_from_database_original_filename_edited, ".") + 1);    
					$gpi_resized_image_name_with_extension = $gpi_from_database_sku . "." . $gpi_filename_extension;

					foreach ( $gpi_scan_resized_images_directory as $gpi_scan_resized_image) {

						if ( $gpi_scan_resized_image == $gpi_resized_image_name_with_extension ) {
							echo "<tr>";
								echo "<td width='25px'>";
									echo "<p>" . $gpi_from_database_sku . "</p>";
								echo "</td>";
								echo "<td width='150px'>";
									echo "<a href='" . $actual_link . "original-images/" . $gpi_from_database_original_filename_edited . "' alt='Click to enlarge.' title='Click to enlarge.' target='_blank' data-toggle='modal' data-target='#exampleModalCenter'><img src='resized-images/" . $gpi_scan_resized_image . "'. /></a>";
								echo "</td>";
								echo "<td>";
									echo "<p><a href='" . $actual_link . "original-images/" . $gpi_from_database_original_filename_edited . "' alt='Click to enlarge.' title='Click to enlarge.' target='_blank'>" . $gpi_from_database_original_filename_edited . "</a></p>";
								echo "</td>";
								echo "<td>";
									echo "<p><a href='" . $actual_link . "resized-images/" . $gpi_scan_resized_image . "' alt='Click to enlarge.' title='Click to enlarge.' target='_blank'>" . $gpi_scan_resized_image . "</a></p>";
								echo "</td>";
							echo "</tr>";
						}
					}
				}

				$gpi_database_connection = null;
				?>
			</table>
		</div><!-- end of .grid-item -->

<?php include_once('footer.php'); ?>