<?php
	
	session_start();
	$pageTitle = 'Login';
	if (isset($_SESSION['user'])) {
		header('Location: index.php');
	}
	include 'init.php';

    // start Check If User Coming From HTTP Post Request
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

              // start Login
              if (isset($_POST['login'])) {

                $email = $_POST['email'];
                $password = $_POST['password'];
                $hashedPass = sha1($password);
              
                
              
                    // Check If The User Exist In Database
              
                      $stmt = $con->prepare("SELECT 
                                                  UserID, Email, Password 
                                              FROM 
                                                  users 
                                              WHERE 
                                                  Email = ? 
                                              AND 
                                                  Password = ? 
                                            ");
              
                      $stmt->execute(array($email, $hashedPass));
                      $row = $stmt-> fetch();
                      $count = $stmt->rowCount();
                  
              
              
                  if ($count > 0) {
                          $_SESSION['user'] = $email; // Register Session Name
                          $_SESSION['ID']= $row['UserID'] ;// register Session ID

                          echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";// Redirect To homeworkPage
                        exit();
                      } else{
                        $falsepassword = 'false Password or Email';
                      }
                    
              
              }
              //end Login

             // start Logup
              else {

                      $formErrors = array();

                    $username 	= $_POST['username'];
                    $password 	= $_POST['password'];
                    $password2 	= $_POST['password2'];
                    $email 		= $_POST['email'];
                    $fullname 		= $_POST['fullname'];

                    if (isset($username)) {

                      $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

                      if (strlen($filterdUser) < 2) {

                        $formErrors[] = 'Username Must Be Larger Than 2 Characters';

                      }

                    }
                    if (isset($fullname )) {

                      $filterfullname = filter_var($fullname , FILTER_SANITIZE_STRING);

                      if (strlen($filterfullname) < 4) {

                        $formErrors[] = 'full name Must Be Larger Than 2 Characters';

                      }
                      

                    }

                    if (isset($password) && isset($password2)) {

                      if (empty($password)) {

                        $formErrors[] = 'Sorry Password Cant Be Empty';

                      }

                      if (sha1($password) !== sha1($password2)) {

                        $formErrors[] = 'Sorry Password Is Not Match';

                      }

                    }

                    if (isset($email)) {

                      $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
              
                      if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {
              
                        $formErrors[] = 'This Email Is Not Valid';
              
                      }
              
                    }

                    //start  Check If User Exist in Database

                      if (empty($formErrors)) {

                     

                  $check = checkItem("Email", "users", $email);

                  if ($check == 1) {

                    $formErrors[] = 'This Email Is exist';

                 

                  } else{
                                    
                      // Insert Userinfo In Database

                                    $stmt = $con->prepare("INSERT INTO 
                                        users(Username, Password, Email, FullName, Date)
                                          VALUES(:zuser, :zpass, :zmail, :zname, now()) ");
                                    $stmt->execute(array(

                                    'zuser' 	=> $username,
                                    'zpass' 	=> sha1($password),
                                    'zmail' 	=> $email,
                                    'zname' 	=> $fullname,
                                    

                                    ));

                                    // Echo Success Message


					                   $succesMsg = 'Congrats You Are Now Registerd User';
                                }
                    }
                    // end Check If User Exist in Database
                    

              }
              // end Logup

  }
  //end  Check If User Coming From HTTP Post Request

    ?>


<!---- Start Login navbardropdown-->


<!---- Start Login-->


 
<script>
       function schowpassword(idshowpassword) {
            
            if (document.getElementById(idshowpassword).type === "password") {
            document.getElementById(idshowpassword).type = "text";
            } else {
            document.getElementById(idshowpassword).type = "password";
            }
        }
</script>

  <form class=" loginanimate" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    

          <div class="logincontainer">
            <label for="email"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" name="email" required>

            <label for="psw"><b>Password</b></label>

            <div class="login_inputandshoweye">
            <input type="password" placeholder="Enter Password" name="password" id="loginpassword" required>
            <p  onclick="schowpassword('loginpassword')">&#128065;</p>
            </div>
              
            <button class='loginsubmit' type="submit" name='login'>Login</button>
            <label>
              <input type="checkbox" checked="checked" name="remember"> Remember me
            </label> <br>
            <span class="loginpsw"> <a href="#">Forgot password?</a></span>
          </div> 

          <?php 
             
            if (isset($falsepassword)) {

              echo '<div class="falsepassword">' . $falsepassword . '</div>';
      
            }
            ?>
          </form>
  <!---- end Login-->  
        
<!---- start Log up -->
  <form class="logupanimate" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <h2> Or neu Account</h2>
        <div class="logupcontainer">
              <label for="email"><b>Email</b></label>
              <input type="text" placeholder="Enter Email" name="email" required>

              <label for="psw"><b>Password</b></label>
            <div class="login_inputandshoweye">             
              <input minlength="6" type="password" placeholder="Enter Password" name="password" id="loguppassword" required>
              <p  onclick="schowpassword('loguppassword')">&#128065;</p>
            </div>

            <label for="psw"><b> Repait Password</b></label>
            <div class="login_inputandshoweye">
              <input type="password" placeholder="Repait Password" name="password2" id="repaitloguppassword" required>
              <p  onclick="schowpassword('repaitloguppassword')">&#128065;</p>
            </div>

              <label for="username"><b>Username</b></label>
              <input minlength="2"type="text" placeholder="Enter Username" name="username" required>

              <label for="fullname"><b>Full Name</b></label>
              <input pattern=".{2,}"
				      title="Username Must Be Between 2 Chars" type="text" placeholder="Enter fullname" name="fullname" required>
                
              <button class='logupsubmit' type="submit" name="signup">Logup</button>
              <label>
                <input type="checkbox" checked="checked" name="remember"> Remember me
              </label>


              <?php 
                          if (!empty($formErrors)) {

            foreach ($formErrors as $error) {

              echo '<div class="loguperror">' . $error . '</div>';

            }

            }
            if (isset($succesMsg)) {

              echo '<div class="msg success">' . $succesMsg . '</div>';
      
            }
            ?>
              
          </div>


  </form>
  <!---- end Log up--> 
 


