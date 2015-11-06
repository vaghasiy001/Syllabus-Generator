<html>
<head>
<script type='text/javascript' src='js/jquery-1.10.2.min.js'></script>
<script type='text/javascript' src='js/menu_jquery.js'></script>
<link href="css/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id='cssmenu'>
<ul>
   <li class='active'><a href='welcome.php'><span style="background-color:#D9B522;color:#000">Home</span></a></li>
   <li class='has-sub' ><a href='statusincsem.php'><span  style="background-color:#8D141B;">Status Indicator</span></a>
   <li class='has-sub' ><a href='syllabussem.php'><span  style="background-color:#8D141B;">Download Syllabus</span></a>
   <li class='has-sub' ><a href='#'><span  style="background-color:#8D141B;">(1) Catalog</span></a>
      <ul>
         <li class='last'><a href='uploadcatelog.php'><span>Upload  Catalog(.PDF)</span></a></li>
         <li class='last'><a href='viewuacourses.php'><span>View UnApproved Courses</span></a></li>
     
      </ul>
      </li>
      <li class='has-sub'><a href='#'><span style="background-color:#8D141B;">(2) Courses</span></a>
      <ul>
         <li><a href='addcourse.php'><span>Add Course</span></a></li>
         <li class='last'><a href='viewcourses.php'><span>View Courses</span></a></li>
      </ul>
   </li>
    <li class='has-sub'><a href='#'><span style="background-color:#8D141B;">(3) Course Sections</span></a>
      <ul>
         <li><a href='selectdegree.php'><span>Add Course Section</span></a></li>
         <!--<li class='last'><a href='editcoursesection.php'><span>Edit Course Section</span></a></li>
         <li class='last'><a href='viewcoursesections.php'><span>View Course Section</span></a></li>-->
      </ul>
   </li>
 <li class='has-sub'><a href='selectsem.php' ><span style="background-color:#8D141B;">(4) Assign Course Section</span></a>
   </li>
   <li class='has-sub'><a href='csselectsem.php'><span  style="background-color:#8D141B;">(5) Course Schedule</span></a>
   </li>

  <li class='has-sub'><a href='#'><span style="background-color:#D9B522;color:#000">Matrix</span></a>
      <ul>
         <li><a href='selectdeg.php'><span>Upload Matrix(.CSV)</span></a></li>
<!--         <li><a href='#'><span>Update Matrix</span></a></li>
         <li><a href='#'><span>Download Matrix(.CSV)</span></a></li>-->
         <li><a href='selectdegforver.php'><span>Select Version</span></a></li>
      
      </ul>
   </li>
 <!--
    <li class='has-sub'><a href='#'><span>Departments</span></a>
      <ul>
         <li><a href='adddepartment.php'><span>Add Department</span></a></li>
       	 <li class='last'><a href='viewdepartments.php'><span>View Departments</span></a></li>
      </ul>
   </li>
   -->
 <!-- 
    <li class='has-sub'><a href='#'><span>Buildings</span></a>
      <ul>
         <li><a href='addbuilding.php'><span>Add Building</span></a></li>
       	 <li class='last'><a href='viewbuildings.php'><span>View Buildings</span></a></li>
      </ul>
   </li>
   -->  
    <li class='has-sub'><a href='#'><span>Users</span></a>
      <ul>
         <li><a href='adduser.php'><span>Add User</span></a></li>
         <li class='last'><a href='viewusers.php'><span>View Users</span></a></li>
      </ul>
   </li>
 
 
   </li>
 <li class='has-sub'><a href='#'><span>Semesters</span></a>
      <ul>
         <li><a href='viewsemesters.php'><span>View Semesters</span></a></li>
       </ul>
   </li>
   
 <li class='has-sub'><a href='#'><span>Pre reqs/Co reqs Options</span></a>
      <ul>
         <li><a href='viewprereqs.php'><span>View Prerequisites</span></a></li>
         <li><a href='viewcoreqs.php'><span>View Corequisites</span></a></li>
       </ul>
   </li>
    
   <li class='last'><a href='#'><span>Utilites</span></a>
      <ul>
         <li><a href='changepassword.php'><span>Change Password</span></a></li>
         <li><a href='viewbackups.php'><span>Database Backup</span></a></li>         
      </ul>
   </li>
   <li class='last'><a href='logout.php' name="logout"><span>Log Out</span></a> </li>
</ul>
</div>
</body>
</html>
