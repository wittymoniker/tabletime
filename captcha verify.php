 <?php
           if(isset($_REQUEST["submit"]))
            {
                $test=$_REQUEST["test"];
                $number1=$_REQUEST["no1"];
               $number2=$_REQUEST["no2"];
                $total=$number1+$number2;
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