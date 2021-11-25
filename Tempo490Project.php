<?php
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
$find = array();
$find['student_examid'] = "";
$find['questions']=array();
//var_dump($_POST['questions'][0]['testcases']);
foreach ($_POST['questions'] as $question) {

    $questionRow = array(
        "student_questionid" => $question['student_questionid'],
        "instructions" => $question['instructions'],
        "topic" => $question['topic'],
        "pointvalue" => $question['pointvalue'],
        "answer" => $question['answer'],
        "name" => $question['name'], 
        "testcases" => array(),
        "methodname" => "",
        "forloop" => "",
        "whileloop" => "",
        "feedback" => ""
    ); //Make element for : error
    $i = 0;
    foreach ($_POST['questions'][$i]['testcases'] as $testcase) {
        $testcaserow = array(
            "input" => $testcase['input'],
            "result" => $testcase['result']
        );
        array_push($questionRow['testcases'], $testcaserow);
        //var_dump($testcaserow);
    }
    $find['student_examid'] = $question['student_examid'];
    array_push($find['questions'], $questionRow);
    $i++;

} //push element when grading
$please = json_encode($find);
$find = json_decode($please);
$array = $find->questions; // specifies into questions array
$i = 0;
foreach ($array as $key => $value){ // sloops through each object
    $argvcases = $find->questions[$key]->testcases;
    $deductions = array();
    $table = $find->questions[$key]->feedback;
    $pointvalue = $find->questions[$key]->pointvalue;
    $addon = ':';
    $code = $find->questions[$key]->answer; // Students  answer code
    $testarg1 = $find->questions[$key]->input1; // input1
    $testarg2 = $find->questions[$key]->input2; // input2
    $testresult1 = $find->questions[$key]->result1; // result1
    $testresult2 = $find->questions[$key]->result2; // result2
    $topic = $find->questions[$key]->instructions;
    $methodname = $find->questions[$key]->name; // grabs mandatory string name
    $foundmethname = strstr($code, $methodname); //check for keyword array in method name(handle integers)
    $arrayinput = strstr($topic, 'array'); //checks for keyword array in
    $integerinput = strstr($topic, 'integer');
    $forloop = strstr($topic, 'for');
    $forsearch = strstr($code, 'for');
    $whileloop = strstr($topic, 'while');
    $whilesearch = strstr($code, 'while');

if($integerinput == true){ // Crosschecks instructions for the word integer
    if($foundmethname == true){
        //Methodname found
        $CLchecker = strtok($code, "\n"); /////////START OF CHECKER
        $CL1checker = strstr($CLchecker, ":"); //Checks the first line for the colon
        if($CL1checker == false){ //if false explodes into array and inserts the : at the end of the first line
            //echo "String not found";
            $find->questions[$key]->feedback="Missing : at the beginning of code -3";
            $find->questions[$key]->pointvalue-=3;
            $try1 = explode("\n", $code);
            foreach($try1 as $value){
                if($value == $try1[0]){
                    $try1[0] = $try1[0] . $addon;
                }
            }
            $code = implode("\n", $try1); //Combines everything into new code segment
        }  ////////////////////END OF CHECKER
        //$find->questions[$key]->methodname = "Methodname found";
        $return = strstr($code, 'return'); // Checks for return statement
        $alternate = strstr($code, 'print');
        if($return == true){
            $sys = "import sys\r\n"; // Adds in import sys
            $tophalf = $sys . $code; // writes in import sys at top of code
            $parameters = "(sys.argv[1]))";
            $print = "\nprint(";
            $funcpart1 = $print . $methodname;
            $funcall = $funcpart1 . $parameters; // combines method and paramters to allow function call
            $final = $tophalf . $funcall; // combines top half of code with function call to complete code
        }elseif($alternate == true){
            $sys = "import sys\r\n";
            $tophalf = $sys . $code;
            $parameters = "(sys.argv[1])";
            $funcall = $methodname . $parameters;
            $final = $tophalf . "\n" . $funcall;
        }else{ // combine both return and print function
            $sys = "import sys\r\n";
            $tophalf = $sys . $code;
            $parameters = "(sys.argv[1]))";
            $print = "\nprint(";
            $funcpart1 = $print . $methodname;
            $funcall = $funcpart1 . $parameters;
            $final = $tophalf . $funcall;
        }
        file_put_contents("StudentCode.py", $final); // puts contents into executable file
        $j = 0;
        /*
        foreach ($argvcases as $key => $value) {
            $IInput = $argvcases[$key]->input;
            $RResult = $argvcases[$key]->result;

            //echo "Entering Integer loop\n";
            $inttestresult = (int)$RResult;
            $inttestarg = (int)$IInput;

            $case = escapeshellcmd("python StudentCode.py $inttestarg"); // attempts with first test argument
            $result = shell_exec($case); //saves result
            $intresult = (int)$result;
            //might need to (int) result1 and result2
            if ($intresult != $inttestresult) {
                // both results didn't check out
                $find->questions[$i]->pointvalue -= 25; //95% off
                $arr = array("message"=>"Result $j didn't match","pointsRemoved"=>"-5");
                array_push($arr,$find->questions[$i]->deductions);
            }
            $j++;
        }
        */
    }else{
        //Wrong method name
        $find->questions[$key]->pointvalue-=5; //5 points off for method name
        $find->questions[$key]->methodname="Didn't use the correct method name: -5";
        $seewhatgrabs = substr($code, strpos($code, " ") +1);
        $tether = explode("(", $seewhatgrabs, 2);
        $usermethodname = $tether[0];
        $CLchecker = strtok($code, "\n"); /////////START OF CHECKER
        $CL1checker = strstr($CLchecker, ":"); //Checks the first line for the colon
        if($CL1checker == false){ //if false explodes into array and inserts the : at the end of the first line
            //echo "String not found";
            $find->questions[$key]->feedback="Missing : at the beginning of code -3";
            $find->questions[$key]->pointvalue-=3;
            $try1 = explode("\n", $code);
            foreach($try1 as $value){
                if($value == $try1[0]){
                    $try1[0] = $try1[0] . $addon;
                }
            }
            $code = implode("\n", $try1); //Combines everything into new code segment
        }
        $return = strstr($code, 'return'); // Checks for return statement
        $alternate = strstr($code, 'print');
        if($return == true){
            $sys = "import sys\r\n"; // Adds in import sys
            $tophalf = $sys . $code; // writes in import sys at top of code
            $parameters = "(sys.argv[1]))";
            $print = "\nprint(";
            $funcpart1 = $print . $usermethodname;
            $funcall = $funcpart1 . $parameters; // combines method and paramters to allow function call
            $final = $tophalf . $funcall; // combines top half of code with function call to complete code
        }elseif($alternate == true){
            $sys = "import sys\r\n";
            $tophalf = $sys . $code;
            $parameters = "(sys.argv[1])";
            $funcall = $usermethodname . $parameters;
            $final = $tophalf . "\n" . $funcall;
        }else{ // combine both return and print function
            $sys = "import sys\r\n";
            $tophalf = $sys . $code;
            $parameters = "(sys.argv[1]))";
            $print = "\nprint(";
            $funcpart1 = $print . $usermethodname;
            $funcall = $funcpart1 . $parameters;
            $final = $tophalf . $funcall;
        }
        file_put_contents("StudentCode.py", $final); // puts contents into executable file
        $j = 0; /*
        foreach ($argvcases as $key => $value) {
            $IInput = $argvcases[$key]->input;
            $RResult = $argvcases[$key]->result;

            //echo "Entering Integer loop\n";
            $inttestresult = (int)$RResult;
            $inttestarg = (int)$IInput;

            $case = escapeshellcmd("python StudentCode.py $inttestarg"); // attempts with first test argument
            $result = shell_exec($case); //saves result
            $intresult = (int)$result;
            //might need to (int) result1 and result2
            if ($intresult != $inttestresult) {
                // both results didn't check out
                $find->questions[$i]->pointvalue -= 25; //95% off
                $arr = array("message"=>"Result $j didn't match","pointsRemoved"=>"-5");
                array_push($arr,$find->questions[$i]->deductions);
            }
            $j++;
        }
        */
    }
    if($forloop == true){  //Checking the constraints for-while
        if($forsearch == true){
            //Used for loop all good
            //echo "For loop found";
            //$find->questions[$key]->pointvalue+=$half;
        }else{
            //no for loop found take off majority of points
            $find->questions[$key]->pointvalue-=4;
            $find->questions[$key]->forloop = "No for loop found -4 points";
        }
    }elseif($whileloop == true){
        if($whilesearch == true){
            //while loop found
            //$find->questions[$key]->pointvalue+=$half;
        }else{
            //no while loop found take off points
            $find->questions[$key]->pointvalue-=4;
            $find->questions[$key]->whileloop="No while loop found -4 points";
        }
    } /*
}elseif($arrayinput = true){ //Array aspect
    if($foundmethname == true){
        //Methodname found
        $CLchecker = strtok($code, "\n"); /////////START OF CHECKER
        $CL1checker = strstr($CLchecker, ":"); //Checks the first line for the colon
        if($CL1checker == false){ //if false explodes into array and inserts the : at the end of the first line
            //echo "String not found";
            $find->questions[$key]->feedback="Missing : at the beginning of code -3";
            $find->questions[$key]->pointvalue-=3;
            $try1 = explode("\n", $code);
            foreach($try1 as $value){
                if($value == $try1[0]){
                    $try1[0] = $try1[0] . $addon;
                }
            }
            $code = implode("\n", $try1); //Combines everything into new code segment
        }  ////////////////////END OF CHECKER
        //$find->questions[$key]->methodname = "Methodname found";
        $return = strstr($code, 'return'); // Checks for return statement
        $alternate = strstr($code, 'print');
        if($return == true){
            $sys = "import sys\r\n"; // Adds in import sys
            $tophalf = $sys . $code; // writes in import sys at top of code
            $parameters = "(sys.argv[1]))";
            $print = "\nprint(";
            $funcpart1 = $print . $methodname;
            $funcall = $funcpart1 . $parameters; // combines method and paramters to allow function call
            $final = $tophalf . $funcall;// combines top half of code with function call to complete code
        }elseif($alternate == true){
            $sys = "import sys\r\n";
            $tophalf = $sys . $code;
            $parameters = "(sys.argv[1])";
            $funcall = $methodname . $parameters;
            $final = $tophalf . "\n" . $funcall;
        }else{ // combine both return and print function
            $sys = "import sys\r\n";
            $tophalf = $sys . $code;
            $parameters = "(sys.argv[1]))";
            $print = "\nprint(";
            $funcpart1 = $print . $methodname;
            $funcall = $funcpart1 . $parameters;
            $final = $tophalf . $funcall;
        }
        file_put_contents("StudentCode.py", $final); // puts contents into executable file
        $j = 0;
        foreach ($argvcases as $key => $value) {
            $IInput = $argvcases[$key]->input;
            $RResult = $argvcases[$key]->result;

            $test1array = explode(",", $RResult);
            $testarray1 = array_map('intval', $test1array); /////COMPARE /////COMPARE
            $testfinal = implode(" ", $testarray1);

            $case = escapeshellcmd("python StudentCode.py $IInput"); // attempts with first test argument
            $result = shell_exec($case);
            $result1array = explode(",", $result);
            $resultarray1 = array_map('intval', $result1array);
            $resultfinal = implode(" ", $resultarray1);

            //might need to (int) result1 and result2
            if ($testfinal != $resultfinal) {
                // both results didn't check out
                $find->questions[$i]->pointvalue -= 25; //95% off
                $arr = array("message"=>"Result $j didn't match","pointsRemoved"=>"-5");
                array_push($arr,$find->questions[$i]->deductions);
            }
            $j++;
        }
    }else{
        // Wrong method name
        $find->questions[$key]->pointvalue-=5; //5 points off for name
        $find->questions[$key]->methodname="Didn't use the correct method name, -5 points";
        $seewhatgrabs = substr($code, strpos($code, " ") +1);
        $tether = explode("(", $seewhatgrabs, 2);
        $usermethodname = $tether[0];
        $CLchecker = strtok($code, "\n"); /////////START OF CHECKER
        $CL1checker = strstr($CLchecker, ":"); //Checks the first line for the colon
        if($CL1checker == false){ //if false explodes into array and inserts the : at the end of the first line
            //echo "String not found";
            $find->questions[$key]->feedback="Missing : at the beginning of code -3";
            $find->questions[$key]->pointvalue-=3;
            $try1 = explode("\n", $code);
            foreach($try1 as $value){
                if($value == $try1[0]){
                    $try1[0] = $try1[0] . $addon;
                }
            }
            $code = implode("\n", $try1); //Combines everything into new code segment
        }  ////////////////////END OF CHECKER
        $return = strstr($code, 'return'); // Checks for return statement
        $alternate = strstr($code, 'print');
        if($return == true){
            $sys = "import sys\r\n"; // Adds in import sys
            $tophalf = $sys . $code; // writes in import sys at top of code
            $parameters = "(sys.argv[1]))";
            $print = "\nprint(";
            $funcpart1 = $print . $usermethodname;
            $funcall = $funcpart1 . $parameters; // combines method and paramters to allow function call
            $final = $tophalf . $funcall;// combines top half of code with function call to complete code
        }elseif($alternate == true){
            $sys = "import sys\r\n";
            $tophalf = $sys . $code;
            $parameters = "(sys.argv[1])";
            $funcall = $usermethodname . $parameters;
            $final = $tophalf . "\n" . $funcall;
        }else{ // combine both return and print function
            $sys = "import sys\r\n";
            $tophalf = $sys . $code;
            $parameters = "(sys.argv[1]))";
            $print = "\nprint(";
            $funcpart1 = $print . $usermethodname;
            $funcall = $funcpart1 . $parameters;
            $final = $tophalf . $funcall;
        }
        file_put_contents("StudentCode.py", $final); // puts contents into executable file
        $j = 0;
        foreach ($argvcases as $key => $value) {
            $IInput = $argvcases[$key]->input;
            $RResult = $argvcases[$key]->result;

            $test1array = explode(",", $RResult);
            $testarray1 = array_map('intval', $test1array); /////COMPARE /////COMPARE
            $testfinal = implode(" ", $testarray1);

            $case = escapeshellcmd("python StudentCode.py $IInput"); // attempts with first test argument
            $result = shell_exec($case);
            $result1array = explode(",", $result);
            $resultarray1 = array_map('intval', $result1array);
            $resultfinal = implode(" ", $resultarray1);

            //might need to (int) result1 and result2
            if ($testfinal != $resultfinal) {
                // both results didn't check out
                $find->questions[$i]->pointvalue -= 25; //95% off
                $arr = array("message"=>"Result $j didn't match","pointsRemoved"=>"-5");
                array_push($arr,$find->questions[$i]->deductions);
            }
            $j++;
        }
    }
    if($forloop == true){
        if($forsearch == true){
            //Used for loop all good
            //echo "For loop found";
            //$find->questions[$key]->pointvalue+=$half;
        }else{
            //echo "For loop not found";
            $find->questions[$key]->pointvalue-=4;
            $find->questions[$key]->forloop = "No for loop found -4 points";
        }
    }elseif($whileloop == true){
        if($whilesearch == true){
            //while loop found
            //$find->questions[$key]->pointvalue+=$half;
        }else{
            //no while loop found take off points
            $find->questions[$key]->pointvalue-=4;
            $find->questions[$key]->whileloop = "No while loop found -4 points";
        }
    }
}
elseif($foundmethname == true){
    $CLchecker = strtok($code, "\n"); /////////START OF CHECKER
    $CL1checker = strstr($CLchecker, ":"); //Checks the first line for the colon
    if($CL1checker == false){ //if false explodes into array and inserts the : at the end of the first line
        //echo "String not found";
        $find->questions[$key]->feedback="Missing : at the beginning of code -3";
        $find->questions[$key]->pointvalue-=3;
        $try1 = explode("\n", $code);
        foreach($try1 as $value){
            if($value == $try1[0]){
                $try1[0] = $try1[0] . $addon;
            }
        }
        $code = implode("\n", $try1); //Combines everything into new code segment
    }  ////////////////////END OF CHECKER
    //$find->questions[$key]->methodname = "Methodname found";
    $return = strstr($code, 'return'); // Checks for return statement
    $alternate = strstr($code, 'print');
    if($return == true){
        $sys = "import sys\r\n"; // Adds in import sys
        $tophalf = $sys . $code; // writes in import sys at top of code
        $parameters = "(sys.argv[1]))";
        $print = "\nprint(";
        $funcpart1 = $print . $methodname;
        $funcall = $funcpart1 . $parameters; // combines method and paramters to allow function call
        $final = $tophalf . $funcall; // combines top half of code with function call to complete code
    }elseif($alternate == true){
        $sys = "import sys\r\n";
        $tophalf = $sys . $code;
        $parameters = "(sys.argv[1])";
        $funcall = $methodname . $parameters;
        $final = $tophalf . "\n" . $funcall;
    }else{ // combine both return and print function
        $sys = "import sys\r\n";
        $tophalf = $sys . $code;
        $parameters = "(sys.argv[1]))";
        $print = "\nprint(";
        $funcpart1 = $print . $methodname;
        $funcall = $funcpart1 . $parameters;
        $final = $tophalf . $funcall;
    }
    file_put_contents("StudentCode.py", $final); // puts contents into executable file
    $j = 0;
    foreach ($argvcases as $key => $value) {
        $IInput = $argvcases[$key]->input;
        $RResult = $argvcases[$key]->result;

        //echo "Entering Integer loop\n";
        $inttestresult = (int)$RResult;
        $inttestarg = (int)$IInput;

        $case = escapeshellcmd("python StudentCode.py $inttestarg"); // attempts with first test argument
        $result = shell_exec($case); //saves result
        $intresult = (int)$result;
        //might need to (int) result1 and result2
        if ($intresult != $inttestresult) {
            // both results didn't check out
            $find->questions[$i]->pointvalue -= 25; //95% off
            $arr = array("message"=>"Result $j didn't match","pointsRemoved"=>"-5");
            array_push($arr,$find->questions[$i]->deductions);
        }
        $j++;
    }
}else{
        //Wrong method name
        $find->questions[$key]->pointvalue-=5; //5 points off for method name
        $find->questions[$key]->methodname="Didn't use the correct method name: -5";
        $seewhatgrabs = substr($code, strpos($code, " ") +1);
        $tether = explode("(", $seewhatgrabs, 2);
        $usermethodname = $tether[0];
        $CLchecker = strtok($code, "\n"); /////////START OF CHECKER
        $CL1checker = strstr($CLchecker, ":"); //Checks the first line for the colon
        if($CL1checker == false){ //if false explodes into array and inserts the : at the end of the first line
            //echo "String not found";
            $find->questions[$key]->feedback="Missing : at the beginning of code -3";
            $find->questions[$key]->pointvalue-=3;
            $try1 = explode("\n", $code);
            foreach($try1 as $value){
                if($value == $try1[0]){
                    $try1[0] = $try1[0] . $addon;
                }
            }
            $code = implode("\n", $try1); //Combines everything into new code segment
        }
        $return = strstr($code, 'return'); // Checks for return statement
        $alternate = strstr($code, 'print');
        if($return == true){
            $sys = "import sys\r\n"; // Adds in import sys
            $tophalf = $sys . $code; // writes in import sys at top of code
            $parameters = "(sys.argv[1]))";
            $print = "\nprint(";
            $funcpart1 = $print . $usermethodname;
            $funcall = $funcpart1 . $parameters; // combines method and paramters to allow function call
            $final = $tophalf . $funcall; // combines top half of code with function call to complete code
        }elseif($alternate == true){
            $sys = "import sys\r\n";
            $tophalf = $sys . $code;
            $parameters = "(sys.argv[1])";
            $funcall = $usermethodname . $parameters;
            $final = $tophalf . "\n" . $funcall;
        }else{ // combine both return and print function
            $sys = "import sys\r\n";
            $tophalf = $sys . $code;
            $parameters = "(sys.argv[1]))";
            $print = "\nprint(";
            $funcpart1 = $print . $usermethodname;
            $funcall = $funcpart1 . $parameters;
            $final = $tophalf . $funcall;
        }
        file_put_contents("StudentCode.py", $final); // puts contents into executable file
        $j = 0;
        foreach ($argvcases as $key => $value) {
            $IInput = $argvcases[$key]->input;
            $RResult = $argvcases[$key]->result;

            //echo "Entering Integer loop\n";

            $case = escapeshellcmd("python StudentCode.py $IInput"); // attempts with first test argument
            $result = shell_exec($case); //saves result
            //might need to (int) result1 and result2
            if ($RResult != $result) {
                // both results didn't check out
                $find->questions[$i]->pointvalue -= 25; //95% off
                $arr = array("message"=>"Result $j didn't match","pointsRemoved"=>"-5");
                array_push($arr,$find->questions[$i]->deductions);
            }
            $j++;
        }

    }
    if($forloop == true){
        if($forsearch == true){
            //Used for loop all good
            //echo "For loop found";
            //$find->questions[$key]->pointvalue+=$half;
        }else{
            //echo "For loop not found";
            $find->questions[$key]->pointvalue-=4;
            $find->questions[$key]->forloop = "No for loop found -4 points";
        }
    }elseif($whileloop == true){
        if($whilesearch == true){
            //while loop found
            //$find->questions[$key]->pointvalue+=$half;
        }else{
            //no while loop found take off points
            $find->questions[$key]->pointvalue-=4;
            $find->questions[$key]->whileloop = "No while loop found -4 points";
        }
    }
    */
    unset($find->questions[$key]->instructions);
    unset($find->questions[$key]->topic);
    unset($find->questions[$key]->name);
    var_dump($find);
    $i++;
}




































}





















?>
