<?php
    require_once './sql_config.php'; // move to a secure location

    function getEmployees($id) {
        $res = "";
        $conn = createSQLConn();
        
        if ($id === "ALL") {
            $statement = $conn->prepare("SELECT * FROM employee");
        } else {
            if (preg_match("/^[1-9]\d*$/",$id)) {
                $statement = $conn->prepare("SELECT * FROM employee WHERE id=?");
                $statement->bind_param("i", $id);
            } else {
                die("Error: Invalid id");
            }
        }

        $statement->execute();
        $result = $statement->get_result();

        if (mysqli_num_rows($result) > 0) {
            if ($id === "ALL") { // for employee listing page
                while ($row = $result->fetch_assoc()) {
                    $userId = $row["id"];
                    $fname = $row["FirstName"];
                    $lname = $row["LastName"];
                    $location = $row["Location"];
                    $status = parseStatus($row["Status"]);
                    $links = "<a href='/edit.php/?id=$userId'>Edit</a> <a href='/delete.php/?id=$userId'>Delete</a>";
                    echo "<tr><td>$lname, $fname</td> <td>$location</td> <td>$status</td> <td>$links</td></tr>";
                }    
            } else { // for edit and delete pages
                $res = $result->fetch_assoc();
            }
        } else {
            echo "no employees! you should check out zip recruiter, buddy.";
        }
        $statement->close();
        $conn->close();
        return $res;
    }

    function testFormInput ($name, $type, $custom_data) {
        if ($custom_data) {
            $data = $custom_data;
        } else {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $data = $_POST["$name"];
            } else if ($_SERVER["REQUEST_METHOD"] == "GET") {
                $data = $_GET["$name"];
            }
        }

        if ($type === "status") {
            if (is_null($data)) { // needed because status can be 0
                $res = ["data" => $data, "err" => "$name is required."];
                return $res;
            }
        } else {
            if (empty($data)) {
                $res = ["data" => $data, "err" => "$name is required."];
                return $res;
            }
        }

        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        
        // enhancement TODO: convert below to switch statement and remove extra returns
        if ($type === "name") { 
            if (!preg_match("/^[a-zA-Z ]*$/",$data)) {
                $res = ["data" => $data, "err" => "Only letters and white space allowed"];
            } else {
                $res = ["data" => $data, "err" => ""];
            }
            return $res;

        } else if ($type === "location") {
            if (!preg_match("/^[a-zA-Z ,]*$/",$data)) {
                $res = ["data" => $data, "err" => "Only letters, white space, and commas allowed"];
            } else {
                $res = ["data" => $data, "err" => ""];
            }
            return $res;

        } else if ($type === "status") {
            if ($data === "1" || $data === "0") {
                $res = ["data" => $data, "err" => ""];
            } else {
                $res = ["data" => $data, "err" => "Status must be either Active or Inactive"];
            }
            return $res;

        } else if ($type === "id") {
            if (!preg_match("/^[1-9]\d*$/",$data)) {
                $res = ["data" => $data, "err" => "id must be a positive number"];
            } else {
                $res = ["data" => $data, "err" => ""];
            }
            return $res;

        } else {
            return "";
        }
    }

    function addEmployee ($LastName, $FirstName, $Location, $Status) {     
        $conn = createSQLConn();

        $statement = $conn->prepare("INSERT INTO employee (LastName, FirstName, Location, Status) VALUES (?, ?, ?, ?)");
        $statement->bind_param("sssi", $LastName, $FirstName, $Location, $Status);

        $statement->execute();
        $statement->close();
        $conn->close();
        // enhancement TODO: return success / failure status
    }

    function editEmployee ($LastName, $FirstName, $Location, $Status, $id) {
        $conn = createSQLConn();

        $statement = $conn->prepare("UPDATE employee SET LastName = ?, FirstName = ?, Location = ?, Status = ? WHERE id = ?");
        $statement->bind_param("sssii", $LastName, $FirstName, $Location, $Status, $id);

        $statement->execute();
        $statement->close();
        $conn->close();
        // enhancement TODO: return success / failure status
    }

    function delEmployee ($id) {
        $conn = createSQLConn();

        $statement = $conn->prepare("DELETE FROM employee WHERE id = ?");
        $statement->bind_param("i", $id);

        $statement->execute();
        $statement->close();
        $conn->close();

        // enhancement TODO: return success / failure status
    }

    function createSQLConn () {
        $cred = getDbCred();
        $conn = mysqli_connect($cred["server"], $cred["user"], $cred["pass"], $cred["db"]);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $conn;
    }

    function getLocation ($format) {
        $res = [];
        $cred = getDbCred();
        $conn = mysqli_connect($cred["server"], $cred["user"], $cred["pass"], $cred["db"]);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "SELECT * FROM location";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $cityState = $row["City"] . ", " . $row["State"];
                if ($format === "options") {
                    echo "<option value='$cityState'> $cityState </option>";
                } else if ($format === "data") {
                    $res[] = $cityState;
                }
            }
        } else {
            echo "no locations!";
        }  
        mysqli_close($conn);
        return $res;
    }

    function parseStatus ($status) {
        if ($status == "1") {
            return "Active";
        } else if ($status == "0") {
            return "Inactive";
        } else {
            return "Error: expected 1 or 0";
        }
    }

    function hideWhen($method) {
        if ($method === "post") {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                return "display: none;";
            }
        } else if ($method === "get") {
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                return "display: none;";
            }
        } else {
            return "display: block;";
        }
    }
?>