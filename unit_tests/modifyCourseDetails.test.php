<?php
    error_reporting(0);
    require_once dirname("/Applications/XAMPP/xamppfiles/htdocs/Workspace/Syllabus-Generator-Sprint_1/faculty/test/..").'/autorun.php';
    require_once dirname("/Applications/XAMPP/xamppfiles/htdocs/Workspace/Syllabus-Generator-Sprint_1/faculty/test/..").'/web_tester.php';
    require_once '../includes/DataAccess.php';
    require_once 'modifyCourseDetailsFunctions.php';

    //SimpleTest::prefer(new TextReporter());

   class FormTests extends WebTestCase {
    
        function test_checkButtonSubmit_BeforeCourseSelection() {
            $this->get('http://sg.workdemos.com/faculty/index.php');
            $this->assertTrue($this->setField('username', 'sFrezza'));
            $this->assertTrue($this->setField('password', 'gannon123'));
            $this->click('Submit');
            $this->assertText('Home Page');
            $this->assertTrue($this->clickLink('Modify Course Details'));
            $this->assertTrue($this->setField('ddlsem',6));
            $this->assertTrue($this->click('View Courses'));
            $this->assertFalse($this->click('btnsubmit'));
        }
    }

    class TestModifyCourseDetails extends UnitTestCase{

        function test_Update_classLocation_resources_Query(){
            $this->assertTrue(updateClassResource());
        }

        function test_AssertionMappingMessage(){
                $_GET["flag"] = 1;
                $_GET["msg"] = "msg";
                //
                if($_GET["flag"]==1) { 
                    $_GET["msg"]; 
                }
                $this->assertEqual(1,$_GET["flag"]);
        }

        function test_AddRow_CourseAssessmentMethod(){
                $_POST['hfcamtot'] = 2;

                for($i=1;$i<=$_POST["hfcamtot"];$i++)
                {
                    $tval1="name";
                    //$_POST[$tval1] = "name";
                    $tval2="description"; 
                    $_POST[$tval2] = "description";
                    $_POST["ddlcnm"]=205;
                    $_SESSION["userid"] = 3;
                    $_SESSION["ddlsem3"]=4;
                    if(isset($_POST[$tval1]) && trim($_POST[$tval1])!="")
                    {
                        $sql="insert into cams(camname,camdetails,csid,uid,semid)values('".$_POST[$tval1]."','".$_POST[$tval2]."',".$_POST["ddlcnm"].",".$_SESSION["userid"].",".$_SESSION["ddlsem3"].")";
                        $insertedId = ReturnInsertedID($sql);
                        $savedIds = $savedIds.$insertedId.",";

                    }
                    else
                    {
                        $hfval = "hfval";
                        $_POST[$hfval] = 545;
                        if(isset($_POST[$hfval]) && trim($_POST[$hfval])!="")
                        {
                            $tval1="name1";
                            $_POST[$tval1] = "name1";
                            $tval2="name2";
                            $_POST[$tval2] = "name2";
                            if(trim($_POST[$tval1]) != "") {
                                $sql="update cams set camname='".$_POST[$tval1]."',camdetails='".$_POST[$tval2]."' where camid=".$_POST[$hfval];
                                ExecuteNonQuery($sql);
                                $savedIds = $savedIds.$_POST[$hfval].",";
                            }
                        }
                    }  
                }//for end

                $this->assertNotNull($savedIds);

        }

        //Special Courses Co Req Issues Test - Soham
        function test_coReq(){
            $special = false;
            $coreq="";
            if($special == false) { 
                //echo "hello";
                if(isset($coreq) && $coreq!="") {
                    $this->assertEqual("Hello",$coreq);
                }
                else {    
                    $data=ExecuteNonQuery("select * from co_req");   
                    $this->assertTrue($data); 
                    $nccnm="";     
                    while($info = mysqli_fetch_assoc($data)) 
                    {
                        if(strlen($info["ccname"])>50){
                           $nccnm=substr($info["ccname"],0,50)."..";  
                           $this->assertNotNull($nccnm);

                        }
                        else{
                            $nccnm=$info["ccname"];  
                            $this->assertNotNull($nccnm);                                
                        }
                     }
                }
            }
        }//

        //Special Courses Pre Req Issues Test - Soham
        function test_preReq(){
            $special = true;
            $prereq = 20;
            $prereqid = 20;
            if($special == false) { 
                if(isset($prereq) && $prereq!="") {
                    $this->assertNotNull($prereq);
                } 
            }
            else { 
     
                $data=ExecuteNonQuery("select * from pre_req");    
                $this->assertTrue($data);
                $npcnm="";     
                while($info = mysqli_fetch_assoc($data)) 
                {
                    if(strlen($info["pcname"])>50){
                        $npcnm=substr($info["pcname"],0,50)."..";                                
                        $this->assertNotNull($npcnm);
                    }
                    else{
                        $npcnm=$info["pcname"];  
                        $this->assertNotNull($npcnm);  
                    }                              
                    if($info["prereqid"]==$prereqid) 
                        $this->assertNotNull($npcnm); 
                } 
            }
        }

        //Disable Submit Button if select
        function test_Select(){
            $_POST["ddlcnm"] = 0;
            if($_POST["ddlcnm"]==0) {
                //echo "disabled";
                $this->assertEqual($_POST['ddlcnm'], 0);
            } 
        }

        //Disabled Continued
        function test_QueryString(){
            $query_string = array('flag'=>1,
                              'semid'=>$_SESSION["ddlsem3"],
                              'csid'=>$_POST["ddlcnm"],
                               'msg'=>'Course details are saved successfully.');
            $this->assertIsA($query_string,'array');
        }

        //Fixed issue of admin and faculty portal shares same session for authe..
        //By Soham
        function test_GetSingleField(){
            $_SESSION["a_userid"] = 10;
            $dept=GetSingleField("select deptid from users where uid=".$_SESSION["a_userid"],"deptid");
            $this->assertIsA($dept,'string');
        }

        //session
        function test_session(){
            if (!isset($_SESSION)) {
                session_name("admin");
                session_start();
                $this->assertTrue(session_name());
            }
        }



    }//class end
?>