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
    <h1><u>Staff - Supervisors</u></h1>

    <div style="border: solid black 4px; text-align: left; padding-left: 40px; background-color: #F6C61C; margin-right: 30%; margin-left: 30%;">
        <h2><u>Find the vaccine types stored in clinics:</u></h2>
        <form method="GET" action="supervisors.php">
            <input type="hidden" id="joinRequest" name="joinRequest">
            <p><input type="submit" value="Go" name="joinSubmit" style="background-color: white; font-family: 'Roboto Slab';"></p>
        </form>
    </div><br>

    <div style="border: solid black 4px; text-align: left; padding-left: 40px; background-color: #F6C61C; margin-right: 30%; margin-left: 30%;">
        <h2><u>Find clinics that store all vaccine types:</u></h2>
        <form method="GET" action="supervisors.php">
            <input type="hidden" id="divRequest" name="divRequest">
            <p><input type="submit" value="Go" name="divSubmit" style="background-color: white; font-family: 'Roboto Slab';"></p>
        </form>
    </div><br>

    <div style="border: solid black 4px; text-align: left; padding-left: 40px; background-color: #F6C61C; margin-right: 30%; margin-left: 30%;">
        <h2><u>Vaccine distribution amongst patients:</u></h2>
        <form method="POST" action="supervisors.php">
            <input type="hidden" id="nestedAggRequest" name="nestedAggRequest">
            Find the vaccine distribution amongst patients             
            <select id="symbol" name="symbol" style="font-family: 'Roboto Slab', serif;">
                <option value='>='>>=</option>
                <option value='<='><=</option>
            </select>
            <input type="text" name="age" size="2px"> 
            years old.
            <p><input type="submit" value="Go" name="nestedSubmit" style="background-color: white; font-family: 'Roboto Slab';"></p>
        </form>
    </div><br>

    <div style="border: solid black 4px; text-align: left; padding-left: 40px; background-color: #F6C61C; margin-right: 30%; margin-left: 30%;">
        <h2><u>Find # of registered patients at clinic:</u></h2>
        <form method="POST" action="supervisors.php">
            <input type="hidden" id="countRequest" name="countRequest">
            Clinic ID:  <input type="text" name="clinicID"><br>
            <p><input type="submit" value="Go" name="countSubmit" style="background-color: white; font-family: 'Roboto Slab';"></p>
        </form>
    </div><br>

    <div style="border: solid black 4px; text-align: left; padding-left: 40px; background-color: #F6C61C; margin-right: 30%; margin-left: 30%;">
        <h2><u>Remove clinic from system:</u></h2>
        <form method="POST" action="supervisors.php">
            <input type="hidden" id="deleteClinicRequest" name="deleteClinicRequest">
            Clinic ID:  <input type="text" name="clinicID"><br>
            <p><input type="submit" value="Remove Clinic" name="removeSubmit" style="background-color: white; font-family: 'Roboto Slab';"></p>
        </form>
    </div><br>

    <div style="border: solid black 4px; text-align: left; padding-left: 40px; background-color: #F6C61C; margin-right: 30%; margin-left: 30%;">
        <h2><u>Browse patient registry by:</u></h2>
        <form method="POST" action="supervisors.php">
            <input type="hidden" id="projectionRequest" name="projectionRequest">
            <input type="checkbox" name="patient[]" value="full_name">Name<br>
            <input type="checkbox" name="patient[]" value="personal_health_num">Personal Health Number<br>
            <input type="checkbox" name="patient[]" value="birth_date">Birth Date<br>
            <input type="checkbox" name="patient[]" value="email">Email<br>
            <input type="checkbox" name="patient[]" value="phone">Phone<br>
            <input type="checkbox" name="patient[]" value="city">City<br>
            <input type="checkbox" name="patient[]" value="province">Province<br>
            <input type="checkbox" name="patient[]" value="registration_code">Registration Code<br>
            <p><input type="submit" value="Go" name="projectionSubmit" style="background-color: white; font-family: 'Roboto Slab';"></p>
        </form>
    </div><br>
    
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

        function handleRemoveRequest() {
            global $db_conn;
            $clinic = $_POST['clinicID'];

            executePlainSQL("DELETE FROM clinics WHERE clinic_ID='" . $clinic . "'");
            OCICommit($db_conn);
        }

        function printResult($result, $x) {
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr>";
                for ($y = 0; $y < $x; $y++) {
                    echo "<td>" . $row[$y] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }

        function handleProjectionRequest() {
            global $db_conn;
            $result = executePlainSQL("SELECT count(*) FROM patients");
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $num_patients = $row[0];
            }
            echo "<br><u><b> $num_patients PATIENTS IN SYSTEM</b></u><br><br>";
            echo "<table>";
            echo "<tr>";
            $checkbox1 = $_POST['patient'];  
            $chk="";
            $x = 0;  
            foreach ($checkbox1 as $chk1) {
                if (!empty($chk1)) {
                    echo "<th>" . $chk1 . "</th>";
                    $chk .= $chk1.",";
                    $x += 1;
                }
            }
            echo "</tr>"; 
            $chk = rtrim($chk, ",");
            $result = executePlainSQL("SELECT $chk FROM patients");
            printResult($result, $x);
        }

        function printVaccines($result) {
            echo "<br><u><b>VACCINES USED IN SYSTEM</b></u><br><br>";
            echo "<table>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td></tr>";
            }
            echo "</table>";
        }

        function handleJoinRequest() {
            $result = executePlainSQL("SELECT DISTINCT vaccine_name FROM vaccines, store, manufacturers WHERE vaccines.vaccine_ID=store.vaccine_ID AND manufacturers.manufacturer_ID=vaccines.manufacturer_ID");
            printVaccines($result);
        }

        function handleCountRequest() {
            global $val;
            $clinic = $_POST['clinicID'];
            $result = executePlainSQL("SELECT count(*) FROM patients p, staff s WHERE s.clinic_id='" . $clinic . "' AND s.staff_ID=p.staff_ID");
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $val = $row[0];
            }
            if ($val == '1') {
                echo "There is 1 patient registered at this clinic.";
            } else {
                echo "There are $val patients registered at this clinic.";
            } 
        }

        function handleNestedAggRequest() {
            executePlainSQL("ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD'");
            $age = intval($_POST['age']);
            $symbol = $_POST['symbol'];
            $result = executePlainSQL("SELECT vaccine_name, COUNT(*) FROM (SELECT floor(months_between(CURRENT_DATE, birth_date) / 12), m.vaccine_name FROM patients p, patients_receive_vaccines pv, vaccines v, manufacturers m WHERE floor(months_between(CURRENT_DATE, birth_date) / 12) < 30 AND p.personal_health_num=pv.personal_health_num AND pv.vaccine_ID=v.vaccine_ID AND v.manufacturer_ID=m.manufacturer_ID) GROUP BY vaccine_name");
            echo "<br><u><b>VACCINE DISTRIBUTION AMONGST PATIENTS $symbol $age YEARS OLD</b></u><br><br>";
            echo "<table>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }
            echo "</table>";
        }

        function handleDivRequest() {
            $result = executePlainSQL("SELECT c.clinic_ID FROM clinics c WHERE NOT EXISTS ((SELECT DISTINCT m.vaccine_name from manufacturers m) MINUS (SELECT m.vaccine_name FROM store s, manufacturers m, vaccines v WHERE v.manufacturer_ID = m.manufacturer_ID AND c.clinic_ID = s.clinic_ID AND v.vaccine_ID = s.vaccine_ID))");
            echo "<br><u><b>CLINICS THAT STORE EVERY VACCINE TYPE</b></u><br><br>";
            echo "<table>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td></tr>";
            }
            echo "</table>";
        }

        // HANDLE ALL POST ROUTES
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('deleteClinicRequest', $_POST)) {
                    handleRemoveRequest();
                } else if (array_key_exists('projectionRequest', $_POST)) {
                    handleProjectionRequest();
                } else if (array_key_exists('countRequest', $_POST)) {
                    handleCountRequest();
                } else if (array_key_exists('nestedAggRequest', $_POST)) {
                    handleNestedAggRequest();
                }
                disconnectFromDB();
            }
        }

        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('joinRequest', $_GET)) {
                    handleJoinRequest();
                } else if (array_key_exists('divRequest', $_GET)) {
                    handleDivRequest();
                } 
                disconnectFromDB();
            }
        }
        
		if (isset($_POST['removeSubmit']) || isset($_POST['projectionSubmit']) || isset($_POST['countSubmit']) || isset($_POST['nestedSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['joinSubmit']) || isset($_GET['divSubmit'])) {
            handleGETRequest();
        }
		?>
    </body>
</html>