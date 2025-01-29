 <?php
 $number1;
 $number2;
           if(isset($_REQUEST["submit"]))
            {
                $test=$_REQUEST["test"];
                
                $total=$_REQUEST["no1"]+$_REQUEST["no2"];
                if ($total==$test)
               {
     
                   exit();
               }
              else {
                    echo "<p>
                                <font color=red 
                                    font face='arial' 
                                   size='5pt'>
                                Invalid captcha entered !
                                </font>
                            </p>";
                     }
           }
       ?>