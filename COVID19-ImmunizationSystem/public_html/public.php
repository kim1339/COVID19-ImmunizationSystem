<!-- Some of the php code used was inspired by UBC CPSC 304 Tutorial #4 -->

<!DOCTYPE html>
<html>
    <head>
        <title>UBC CPSC 304</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css\style.css">
        <script src="https://kit.fontawesome.com/dee0481b32.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="navbar" style="margin-bottom: 25px;">
        <a href="index.php"><i class="fas fa-syringe fa-lg"></i></a>
        <div class="dropdown">
            <button class="dropbtn" style="margin-right: 100px;">User Login</button>
            <div class="dropdown-content">
                <a href="public.php">Public</a>
                <a href="volunteers.php">Staff - Volunteers</a>
                <a href="supervisors.php">Staff - Supervisors</a>
            </div>
        </div>
    </div>
    <h1><u>General Public</u></h1>

    <div style="border: solid black 4px; text-align: left; padding-left: 40px; background-color: #F6C61C; margin-left: 30%; margin-right: 30%;">
        <h2><u>Register for your vaccine:</u></h2>
        <form method="POST" action="public.php">
            <input type="hidden" id="insertPatientRequest" name="insertPatientRequest">
            Personal Health Number: <input type="text" name="PHN" required><br><br>
            Full Name: <input type="text" name="name" required> <br><br>
            Birth Date: <input type="date" name="date" style="font-family: 'Roboto Slab', serif;" required> <br><br>
            Email: <input type="text" name="email" required> <br><br>
            Phone: <input type="text" name="phone" required> <br><br>

            Building Number: <input type="text" name="building"> <br><br>
            Street: <input type="text" name="street"> <br><br>
            Postal Code: <input type="text" name="postal"> <br><br>
            City: <input type="text" name="city"> <br><br>
            Province: <input type="text" name="province"> <br>
            <p><input type="submit" value="Register" name="insertSubmit" style="background-color: white; font-family: 'Roboto Slab';"></p>
        </form>
    </div><br><br>

    <div style="border: solid black 4px; text-align: left; padding-left: 40px; background-color: #F6C61C; margin-left: 30%; margin-right: 30%;">
        <h2><u>Find an immunization clinic near you:</u></h2>
        <form method="POST" action="public.php">
            <input type="hidden" id="selectClinicsRequest" name="selectClinicsRequest">
            Select Province:
            <select id="prov" name="prov" style="font-family: 'Roboto Slab', serif;">
                <option value='BC'>British Columbia</option>
                <option value='YT'>Yukon</option>
                <option value='NT'>Northwest Territories</option>
                <option value='NU'>Nunavut</option>
                <option value='AB'>Alberta</option>
                <option value='MB'>Manitoba</option>
                <option value='SK'>Saskatchewan</option>
                <option value='QC'>Quebec</option>
                <option value='ON'>Ontario</option>
                <option value='NB'>New Brunswick</option>
                <option value='NS'>Nova Scotia</option>
                <option value='NL'>Newfoundland and Labrador</option>
                <option value='PE'>Prince Edward Island</option>
            </select>
            <p><input type="submit" value="Find Clinics" name="selectSubmit" style="background-color: white; font-family: 'Roboto Slab';"></p>
        </form>
    </div>

    <?php
        $success = False;
        $db_conn = NULL;

        function executePlainSQL($cmdstr) {
            global $db_conn, $success;
            $statement = OCIParse($db_conn, $cmdstr); 
            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }
            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement);
                echo htmlentities($e['message']);
                $success = False;
            }
			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    OCIBindByName($statement, $bind, $val);
                    unset ($val);
				}
                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement);
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }

        function connectToDB() {
            global $db_conn;
            $db_conn = OCILogon("", "", ""); // NEEDS TO BE FILLED W/ ORACLE ACCOUNT INFO
            if ($db_conn) {
                return true;
            } else {
                $e = OCI_Error();
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;
            OCILogoff($db_conn);
        }

        function handleInsertRequest() {
            global $db_conn;

            // getting the values from the user and inserting data into the patients table
            $tuple = array (
                ":b1" => $_POST['PHN'],
                ":b2" => $_POST['name'],
                ":b4" => $_POST['email'],
                ":b5" => $_POST['phone'],
                ":b6" => $_POST['building'],
                ":b7" => $_POST['street'],
                ":b8" => $_POST['postal'],
                ":b9" => $_POST['city'],
                ":b10" => $_POST['province']
            );

            // randomize registration code
            $code = substr(uniqid('R-'), 0 , 10);

            $date = date("Y-m-d", strtotime($_POST['date']));

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into patients (personal_health_num, full_name, email, phone, building_num, street, postal_code, city, province) values (:b1, :b2, :b4, :b5, :b6, :b7, :b8, :b9, :b10)", $alltuples);
            executePlainSQL("UPDATE patients SET birth_date= DATE '$date' WHERE personal_health_num='" . $_POST['PHN'] . "'");
            executePlainSQL("UPDATE patients SET registration_code='" . $code . "' WHERE personal_health_num='" . $_POST['PHN'] . "'");
            OCICommit($db_conn);

            echo '<script>alert("Thank you for registering! Please check for an email or text message with your registration code and booking details.")</script>';
        }

        function printResult($result, $province) { // prints results from a select statement
            switch ($province) {
                case "BC":
                  $province = "BRITISH COLUMBIA";
                  break;
                case "NU":
                  $province = "NUNAVUT";
                  break;
                case "SK":
                  $province = "SASKATCHEWAN";
                  break;
                case "NT":
                  $province = "NORTHWEST TERRITORIES";
                  break;
                case "PE":
                  $province = "PRINCE EDWARD ISLAND";
                  break;
                case "MB":
                  $province = "MANITOBA";
                  break;
                case "NS":
                  $province = "NOVA SCOTIA";
                  break;
                case "NL":
                  $province = "NEWFOUNDLAND AND LABRADOR";
                  break;
                case "ON":
                  $province = "ONTARIO";
                  break;
                case "AB":
                  $province = "ALBERTA";
                  break;
                case "NB":
                  $province = "NEW BRUNSWICK";
                  break;
                case "QC":
                  $province = "QUEBEC";
                  break;
                default:
                  $province = "YUKON";
              }
            echo "<br><u><b>CLINICS FOUND IN $province</b></u><br><br>";
            echo "<table>";
            echo "<tr><th>Clinic Name</th> <th>Clinic Type</th> <th>Building #</th> <th>Street</th> <th>Postal Code</th> <th>City</th> </tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td></tr>";
            }
            echo "</table>";
        }

        function handleSelectRequest() {
            global $db_conn;
            $province = $_POST['prov'];
            $result = executePlainSQL("SELECT clinic_name, clinic_type, building_num, street, postal_code, city FROM clinics WHERE province='" . $province . "'");
            printResult($result, $province);
        }

        // HANDLE ALL POST ROUTES
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('insertPatientRequest', $_POST)) {
                    handleInsertRequest();
                } else if (array_key_exists('selectClinicsRequest', $_POST)) {
                    handleSelectRequest();
                }
                disconnectFromDB();
            }
        }

		if (isset($_POST['insertSubmit']) || isset($_POST['selectSubmit'])) {
            handlePOSTRequest();
        }
		?>
    </body>
</html>