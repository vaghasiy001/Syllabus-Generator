<?php
    //error_reporting(1);
    require_once dirname("/Applications/XAMPP/xamppfiles/htdocs/Workspace/Syllabus-Generator-Sprint_2/faculty/test/..").'/autorun.php';
    require_once '../includes/DataAccess.php';
    //require_once 'modifyCourseDetailsFunctions.php';

    class addCourse extends UnitTestCase{

        function test_addSpecialCourse(){
            $_POST['spCourse'] = 1;
            $_POST['cbxactive'] = 1;
            $_POST['txtcredits'] = 3;
            if(isset($_POST['spCourse'])){  
                $spActive = $_POST['spCourse'];
            }
            $i=0;
            if(isset($_POST["cbxactive"])){
                $i=1;
            }
            //@@ -59,8 +60,8 @@
            $credits = $_POST['txtcredits'];
            //$credits=$_POST["txtcredits"];
            // $cdet=str_replace("'","\'",$cdet);

            $cid = 1;
            $cnm = "Test Name";
            $cdet = "Test description";
            $prereq = 20;
            $coreq = 21;
            $credits = 3;

            $sql="insert into courses(courseno,coursename,special,description,prereqid,coreqid,credits,active,deptid)values(";
            $sql.="'".strtoupper($cid)."','".$cnm."',$spActive,'$cdet',$prereq,$coreq,$credits,$i,1)";
            echo $sql;
            $result = ExecuteNonQuery($sql);
            $this->assertTrue($result);

        }

        
    }
?>