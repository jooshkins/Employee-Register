<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
  <head>
    <title>Add Employee</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="/styles.css" type="text/css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans&display=swap" rel="stylesheet">
  </head>
  <?php
    include 'logic.php';

    $LastName = $FirstName = $Location = $Status = "";
    $LastNameErr = $FirstNameErr = $LocationErr = $StatusErr = "";
    $error = FALSE;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      // Future Enhancement, use for each
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

    if ($LastNameErr === "" && $FirstNameErr === "" && $LocationErr === "" && $StatusErr === "") {
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        addEmployee($LastName, $FirstName, $Location, $Status);
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
        action="/add.php"
        style="<?php 
          if ($error) {
            echo "display: block;";
          } else {
            echo (hideWhen("post"));
          }
        ?>" 
      >
        <h2>Add Employee</h2>
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
              getLocation("options");
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
          <input type="submit" value="Add Employee">
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
        <h2><span>✔️</span> Employee Added!</h2>
      </div>
    </div>
  </body>
</html>