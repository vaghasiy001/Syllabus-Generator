<?php 
          
    function updateClassResource(){
        $_POST['tabooks'] = "a";
        $_POST['taresources'] = "a";
        $_POST['tacp'] = "a";
        $_POST['taap'] = "a";
        $_POST['taai'] = "a";
        $_POST['tacc'] = "a";
        $_POST['ddlcnm'] = "a";
        $_SESSION['userid'] = "1";
        $_SESSION['ddlsem3'] = "1";
        require_once '../includes/connect.php';  
        $sql="update section set reqmaterials='".mysqli_real_escape_string($connection,$_POST["tabooks"])."',
                                 website='".mysqli_real_escape_string($connection,$_POST["taresources"])."',
                                 coursepolicy='".mysqli_real_escape_string($connection,$_POST["tacp"])."',
                                 attpolicy='".mysqli_real_escape_string($connection,$_POST["taap"])."',
                                 academicintegrity='".mysqli_real_escape_string($connection,$_POST["taai"])."',
                                 coursetopics='".mysqli_real_escape_string($connection,$_POST["tacc"])."' 
                                    where (csid=".$_POST["ddlcnm"]." 
                                        and uid=".$_SESSION["userid"]." 
                                            and semid=".$_SESSION["ddlsem3"].")";
        if($sql){
            return true;
        }else {
            return false;
        }
    }
?>