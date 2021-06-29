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
    <h1><u>Staff - Volunteers</u></h1>
    <div style="border: solid black 4px; text-align: left; padding-left: 40px; background-color: #F6C61C; margin-right: 30%; margin-left: 30%;">
    <h2><u>Patient Check-In:</u></h2>
        <form method="POST" action="volunteers.php">
            <input type="hidden" id="updatePatientRequest" name="updatePatientRequest">
            Patient's Personal Health Number: <input type="text" name="PHN"><br><br>
            Volunteer Staff ID: <input type="text" name="staffID"> <br>
            <p><input type="submit" value="Check Patient In" name="updateSubmit" style="background-color: white; font-family: 'Roboto Slab';"></p>
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

        function handleUpdateRequest() {
            global $db_conn;
            $phn = $_POST['PHN'];
            $staff = $_POST['staffID'];

            executePlainSQL("UPDATE patients SET staff_ID='" . $staff . "' WHERE personal_health_num='" . $phn . "'");
            OCICommit($db_conn);
        }

        // HANDLE ALL POST ROUTES
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('updatePatientRequest', $_POST)) {
                    handleUpdateRequest();
                }
                disconnectFromDB();
            }
        }

		if (isset($_POST['updateSubmit'])) {
            handlePOSTRequest();
        }
		?>
    </body>
</html>