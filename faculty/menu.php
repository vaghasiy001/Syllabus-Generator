<div id="cssmenu">
        <ul>
           <li class='active'><a href='welcome.php'><span>Home</span></a></li>
           <li class='has-sub'><a href='#'><span>View</span></a>
              <ul>
                 <li><a href='viewfaculties.php'><span>Faculty</span></a></li>
                 <li><a href='viewsubjects.php'><span>Courses</span></a></li>
                 <li><a href='coursesyllabus.php'><span>Download Course Syllabus</span></a></li>
              </ul>
           </li>
           <li class='has-sub'><a href='#'><span>Courses</span></a>
              <ul>
                 <li><a href='selectsem.php'><span>Modify Course Details</span></a></li>
                 <li><a href='selectcoursesem.php'><span>Course Syllabus</span></a></li>
               </ul>
           </li>
           <li class='has-sub'><a href='#'><span>Schedule</span></a>
              <ul>
                 <li><a href='viewdoorschedule.php'><span>Door Schedule</span></a></li>

              <li><a href='selectohsem.php'><span>Office Hours</span></a></li>
              </ul>
           </li>
        	<li class='has-sub'><a href='#'><span>Profile</span></a>
              <ul>
                 <li><a href='viewprofile.php'><span>View Profile</span></a></li>
                 <li class='last'><a href='changepassword.php'><span>Change Password</span></a></li>
              </ul>
           </li>
           <li class='last'><a href='logout.php' name="logout"><span>Log Out</span></a></li>
           <li style="float:right;color:#FFF;padding: 0.5em 1em;text-transform:uppercase;">[Welcome : <?php echo $_SESSION["username"] ?>]</li>
        </ul>
        </div>
     <!--Menu...-->