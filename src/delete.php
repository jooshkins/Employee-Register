<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <title>Delete Employee</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="/styles.css" type="text/css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans&display=swap" rel="stylesheet">
    </head>
    <?php
        include 'logic.php';
        $id = $LastName = $FirstName = $Location = $Status = "";
        $idErr = "";
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
        }
        if ($idErr === "") {
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
               $employee = getEmployees($id);
               $LastName = $employee["LastName"];
               $FirstName = $employee["FirstName"];
               $Location = $employee["Location"];
               $Status = parseStatus($employee["Status"]);

            } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
                delEmployee($id);
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
        <h2><span class="error"><?=$idErr?></span></h2>
        <div
        style="<?php 
                    if ($error) {
                        echo "display: block;";
                    } else {
                        echo (hideWhen("post"));
                    }
                ?>"
        class="container">
            <h2>Delete Employee</h2>
            <table>
                <tr>
                    <th>Employee Name (Last, First)</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Confirm Delete</th>
                </tr>
                <tr>
                    <td>
                        <?="$LastName, $FirstName"?>
                    </td>
                    <td>
                        <?=$Location?>
                    </td>
                    <td>
                        <?=$Status?>
                    </td>
                    <td>
                        <form method="post" action="/delete.php">
                            <input type="submit" value="Delete Employee">
                        </form>
                    </td>
                </tr>
            </table>
        </div>
        <div 
        style="<?php 
            if ($error) {
                echo "display: none;";
            } else {
                echo (hideWhen("get"));
            }
        ?>"
        class="container">
            <h2><span>💀</span>Employee Deleted!</h2>
        </div>
    </body>
</html>