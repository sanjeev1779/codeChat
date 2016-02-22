<?php

    $code = $_GET['code'];
    $lang = $_GET['lang'];
    $input = $_GET['input'];
    $data = array("status");

    if(empty($code) || empty($lang)) {
        $data['status'] = "error in code or lang";
        return $data;
    }
    exec("rm temp/*");
    chdir("temp");
    $code_file = "code.".$lang;
    $input_file = "input.txt";
    $output_file = "output.txt";
    $log = "log.txt";

    $writeSuccess = writeCode($code_file, $input_file, $code, $input, $lang);
    if($writeSuccess) {
        $data = runCode($code_file, $input_file, $output_file, $input, $lang);
    }
    else {
        $data["status"] = "Some error occured...";
    }
    echo json_encode($data);

    function writeCode($code_file, $input_file, $code, $input, $lang) {
        try {
            $temp = fopen($code_file, "w");
            fwrite($temp, $code);
            fclose($temp);
            $temp = fopen($input_file, "w");
            fwrite($temp, $input);
            fclose($temp);
            return true;
        }
        catch(Exception $e) {
            return false;
        }
    }

    function runCode($code_file, $input_file, $output_file, $input, $lang) {
        $data = array();
        switch ($lang) {
            case 'c':
                $cmd = "gcc ".$code_file. " 2>log.txt" ;
                exec($cmd);
                if(file_exists("a.out")) {
                    $cmd = "./a.out <". $input_file. " >".$output_file;
                    // $data["running"] = executeCode($cmd,10);
                    exec($cmd);
                    $data["status"] = "compile success";
                    $data["msg"] = filesize($output_file) ? file_get_contents($output_file) : "Output Empty";
                }
                else {
                    $data["status"] = "compile error";
                    $data["msg"] = file_get_contents("log.txt");
                }
                break;
            
            default:
                echo "Invalid language";
                break;
        }
        return $data;
    }

    function executeCode($cmd, $timeout) {
        $ps = exec($cmd);
        $starttime = time();
        while(time() < $starttime + $timeout) //until the current time is greater than our start time, plus the timeout
        {
            $status = proc_get_status($ps);
            if($status['running'])
                sleep(1);
            else
                return true; //command completed :)
        }

        proc_terminate($ps);
        return false; //command timed out :(
    }
?>
