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
        $db_conn = OCILogon("username", "password", "dbhost.students.cs.ubc.ca:1522/stu");
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
?>
