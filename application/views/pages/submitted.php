<html>
    <head>
        <title>Submitted</title>
    </head>
    <body>
        <h1><?php echo 'Your have successfully submited the information!'; ?></h1>
        <h3><?php echo 'Check your email for authentication.'; ?></h3>
        <h4><?php echo 'Here is the information you typed' ?></h4>
        <?php 
            //TODO: complete validation 
            $user_name = $user_email = $user_gender = $user_age = $user_job= ""; 

            //after the server made the post request, then load this
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                if(isset($_POST["fullname"])){
                    //avoid XSS atk
                    $user_name = convert_data($_POST["fullname"]);
                    //ensure the name doesn't have any special character/number
                    if (!preg_match("/^[a-zA-Z0-9_]*$/", $userName)) {
                        $errorMsg = "Chỉ được dùng chữ cái cho mục Họ Tên";
                    }
                } 
                if(isset($_POST["email"])){
                    $user_email = convert_data($_POST["email"]);
                    $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";  
                    // ensure the email is in the correct fomular  
                    if (!preg_match($pattern, $emailID) ){  
                        $errorMsg = "Invalid Email ID format";
                    }else{
                        echo $emailID;
                    }
                }
                if(isset($_POST["gender"])){
                    $user_gender = convert_data($_POST["gender"]);
                }
                if(isset($_POST["age"])){
                    $user_age = convert_data($_POST["age"]);
                    //ensure the age is valid number 
                    //TODO: VALID RANGE TOO
                    if (!preg_match("/^[0-9]*$/", $age)) {
                        $user_age= "Chỉ được dùng số cho mục tuổi";
                    }
                }
                if(isset($_POST["job"])){
                    $user_job = convert_data($_POST["job"]);
                }
                function convert_data($data){
                    //trail whitespace
                    $data = trim($data);
                    // convert special characters into their HTML entities
                    // Like: "&" => "&amp"
                    return htmlspecialchars($data);
                }
            }
            echo "Email: ", $user_email ,"\n";

            echo "Tên: ", $user_name ,"\n";

            echo "Giới tính: ", $user_gender,"\n";

            echo "Tuổi: ", $user_age,"\n";

            echo "Nghề Nghiệp: ", $user_job,"\n";
        ?>
    </body>
</html>