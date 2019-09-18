<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
		<title>Edit Employee</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    	<link href="/styles.css" type="text/css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans&display=swap" rel="stylesheet">
    </head>
    <?php
        include 'logic.php';
        $id = $LastName = $FirstName = $Location = $Status = "";
		$idErr = $LastNameErr = $FirstNameErr = $LocationErr = $StatusErr = "";
		$error = FALSE;

        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            $res = testFormInput("id", "id");
            $id = $res["data"];
            $idErr = $res["err"];

        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $url = parse_url($_SERVER["HTTP_REFERER"]);
            $id = substr($url["query"], 3);

            $res = testFormInput("id", "id", $id);
            $id = $res["data"];
            $idErr = $res["err"];
            
            $lnRes = testFormInput("LastName", "name");
            $LastName = $lnRes["data"];
            $LastNameErr = $lnRes["err"];

            $fnRes = testFormInput("FirstName", "name");
            $FirstName = $fnRes["data"];
            $FirstNameErr = $fnRes["err"];

            $loRes = testFormInput("Location", "location");
            $Location = $loRes["data"];
            $LocationErr = $loRes["err"];

            $ssRes = testFormInput("Status", "status");
            $Status = $ssRes["data"];
            $StatusErr = $ssRes["err"];
        }

        if ($idErr === "" && $LastNameErr === "" && $FirstNameErr === "" && $LocationErr === "" && $StatusErr === "") {
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
               $employee = getEmployees($id);
               $LastName = $employee["LastName"];
               $FirstName = $employee["FirstName"];
               $Location = $employee["Location"];
               $Status = $employee["Status"];

            } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
                editEmployee($LastName, $FirstName, $Location, $Status, $id);
            }
        } else {
			$error = TRUE;
		}
    ?>
    <body>
		<div class="nav">
			<h1> 
				<span>🍕</span> 
				Rad Pizza Co. 
				<span>🎉</span>
			</h1>
			<ul class="nav">
				<li>
					<a href="/index.php">
						<span>🏠</span> Employee Directory
					</a>
				</li>
				<li>
					<a href="/add.php">
						<span>➕</span> Add Employee
					</a>
				</li>
			</ul>
		</div>
		<div class="container">
			<form 
				method="post" 
				style="<?php 
					if ($error) {
						echo "display: block;";
					} else {
						echo (hideWhen("post"));
					}
				?>" 
				action=<?="/edit.php/?id=$id"?>
			>
			<h2>Edit Employee</h2>
				<p>
					<span class="error">* Required field</span>
				</p>
				<div class="form-input">
					Last Name: <br>
					<input type="text" name="LastName" value="<?=$LastName?>">
					<span class="error">* <?=$LastNameErr?> </span>
				</div>
				<div class="form-input">
					First Name: <br>
					<input type="text" name="FirstName" value="<?=$FirstName?>">
					<span class="error">* <?=$FirstNameErr?> </span>
				</div>
				<div class="form-input">
					Location: <br>
					<select name="Location">
						<?php 
							$locations = getLocation("data");
							foreach ($locations as $val) {
								if ($val === $Location) {
									echo "<option value='$val' selected> $val </option>";
								} else {
									echo "<option value='$val'> $val </option>";
								}
							}
						?>
					</select>
					<span class="error">* <?=$LocationErr?> </span>
				</div>
				<div class="form-input">
					Status: <br>
					<select name="Status">
						<option value="1" <?php echo ($Status ? "selected" : NULL);?> >Active</option>
						<option value="0" <?php echo ($Status ? NULL : "selected");?> >Inactive</option>
					</select>
					<span class="error">* <?=$StatusErr?> </span>
				</div>
				<div class="form-input">
					<input type="submit" value="Edit Employee">
				</div>
			</form>
			<div
			style="<?php 
				if ($error) {
					echo "display: none;";
				} else {
					echo (hideWhen("get"));
				}
			?>" 
			>
				<h2><span>✔️</span> Employee Edited!</h2>
			</div>
		</div>
    </body>
</html>