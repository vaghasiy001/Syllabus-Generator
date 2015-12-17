<?php
    error_reporting(0);
    require_once dirname("/Applications/XAMPP/xamppfiles/htdocs/Workspace/Syllabus-Generator-Sprint_1/faculty/test/..").'/autorun.php';
    require_once dirname("/Applications/XAMPP/xamppfiles/htdocs/Workspace/Syllabus-Generator-Sprint_1/faculty/test/..").'/web_tester.php';
    SimpleTest::prefer(new TextReporter());
    require_once '../includes/DataAccess.php';
    require_once 'modifyCourseDetailsFunctions.php';

    class TestMapCams extends UnitTestCase{
            function test_ModifyCourseDetailLink(){
                $_GET["semid"] = 2;
                $_GET["csid"] = 3;
                if(isset($_GET["semid"]) && isset($_GET["csid"])) { 
                    $this->assertIsA("?semid=".$_GET["semid"]."&csid=".$_GET["csid"],'string'); 
                }
            }
    }//class end
?>