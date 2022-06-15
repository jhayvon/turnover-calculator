<?php

/**
 * Plugin Name:       Turnover calculator
 * Description:       Calculating the turnover cost of companies.
 * Version:           1.2
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Jhayvon Adelan
 */


// style imports
function themeslug_enqueue_style() {
    wp_enqueue_style( 'bootstrap', plugins_url('/bootstrap/css/bootstrap.css', __FILE__), false );
    wp_enqueue_style( 'my-style', plugins_url('/assets/mystyle.css', __FILE__), false, '1.0.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_style' );
add_action('showForm', 'showInterface');

function showInterface() {
    if(isset($_POST['calculate'])){
            $X = $_POST["x"];
            $Y = $_POST["y"];
            $Z = $_POST["z"];
            $V = $_POST["v"] / 100;
            $vOrig = $_POST["v"];
            $B = $_POST["b"];
            $N = $_POST["n"];
            $fName = $_POST["fName"];
            $lName = $_POST["lName"];
            $comName = $_POST["comName"];
            $comEmail = $_POST["comEmail"];
            // (Y*Z*V+(Y*Z*V*(B*2.75%))+((Y*Z*V*(N*1.37%))))=result
            $result = number_format(($Y*$Z*$V+($Y*$Z*$V*($B*0.0275))+(($Y*$Z*$V*($N*0.0137)))));
            ?>
            <form action="" method="post">
                <input type="hidden" name="x" id="x" value="<?php echo $X ?>">
                <input type="hidden" name="y" id="y" value="<?php echo $Y ?>">
                <input type="hidden" name="z" id="z" value="<?php echo $Z ?>">
                <input type="hidden" name="v" id="v" value="<?php echo $vOrig ?>">
                <input type="hidden" name="b" id="b" value="<?php echo $B ?>">
                <input type="hidden" name="n" id="n" value="<?php echo $N ?>">
                <input type="hidden" name="fName" id="fName" value="<?php echo $fName ?>">
                <input type="hidden" name="lName" id="lName" value="<?php echo $lName ?>">
                <input type="hidden" name="comName" id="comName" value="<?php echo $comName ?>">
                <input type="hidden" name="comEmail" id="comEmail" value="<?php echo $comEmail ?>">
                <input type="hidden" name="result" id="result" value="<?php echo $result ?>">
                <div class="row mb-4">
                    <div class="w-100 text-center" style="font-size: 1.8rem">Your estimate cost of attrition: </div>
                </div>
                <div class="row mb-5">
                    <div class="w-100 text-center" style="font-size: 2.5rem"><b>$<?php echo $result ?></b></div>
                </div>
                <div class="d-flex justify-content-around">
                    <button type="submit" name="change" id="change" class="btn-color btn round ">CHANGE PARAMETERS</button>
                    <button type="submit" name="sendEmail" id="sendEmail" class="btn-color btn round ">SEND VIA EMAIL</button>
                </div>
            </form>
            <?php
    }elseif(isset($_POST['sendEmail'])) {
        $fName = $_POST["fName"];
        $lName = $_POST["lName"];
        $comName = $_POST["comName"];
        $comEmail = $_POST["comEmail"];
        $result = $_POST["result"];
        wp_mail($comEmail, $comName." turnover cost result ", "the total attrition cost of the ".$comName." is $".$result);
        unset( $_POST["fName"], $_POST["lName"], $_POST["comName"], $_POST["comEmail"], $_POST["x"], $_POST["z"], $_POST["y"], $_POST["v"], $_POST["b"], $_POST["n"]);
        calculatorFunction();
    }else{
        calculatorFunction();
     }
}

add_shortcode('wporg', 'wporg_shortcode');
function wporg_shortcode( $atts = [], $content = null) {
    do_action('showForm');
}

function calculatorFunction(){
    ?>
    <div class='container p-5'>
        <form action="" method="post">
            <div class="row">
                <div class="col-sm-12 col-md-6 px-5">
                    <div class="input-group">
                        <label><span id='empCount'><?php echo isset($_POST["x"]) ? number_format($_POST["y"],0,",",",") : "0" ?></span> | EMPLOYEES IN YOUR COMPANY</label>
                        <input value="<?php echo isset($_POST["x"]) ? $_POST["x"] : "" ?>" type='range' name="x" id="x" max='5000' oninput="mysetval(this.value, 'empCount')"/>
                        <div class="input-bottom d-flex justify-content-between">
                            <div>0</div>
                            <div>5,000</div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label><span id='leaveCount'><?php echo isset($_POST["y"]) ? number_format($_POST["y"],0,",",",") : "0" ?></span> | EMPLOYEES WHO LEAVE EACH YEAR</label>
                        <input value="<?php echo isset($_POST["y"]) ? $_POST["y"] : "" ?>" type='range' name="y" id="y" max='5000' oninput="mysetval(this.value, 'leaveCount')"/>
                        <div class="input-bottom d-flex justify-content-between">
                            <div>0</div>
                            <div>5,000</div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label><span>$</span><span id='salaryCount'><?php echo isset($_POST["z"]) ? number_format($_POST["z"],0,",",",") : "0" ?></span> | AVERAGE SALARY</label>
                        <input value="<?php echo isset($_POST["z"]) ? $_POST["z"] : "" ?>" type='range' name="z" id="z" max='1000000' oninput="mysetval(this.value, 'salaryCount')"/>
                        <div class="input-bottom d-flex justify-content-between">
                            <div>0</div>
                            <div>1,000,000</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6  px-5">
                    <div class="input-group">
                        <label><span id='findTalent'><?php echo isset($_POST["v"]) ? $_POST["v"] : "0" ?></span><b>%</b> | OF SALARY COST TO FIND TALENT</label>
                        <input value="<?php echo isset($_POST["v"]) ? $_POST["v"] : "" ?>" type='range' name="v" id="v" max='100' oninput="mysetval(this.value, 'findTalent')"/>
                        <div class="input-bottom d-flex justify-content-between">
                            <div>0</div>
                            <div>100%</div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label><span id='days'><?php echo isset($_POST["b"]) ? $_POST["b"] : "0" ?></span> | DAYS TO FILL A POSITION</label>
                        <input value="<?php echo isset($_POST["b"]) ? $_POST["b"] : "" ?>" type='range' name="b" id="b" max='365' oninput="mysetval(this.value, 'days')"/>
                        <div class="input-bottom d-flex justify-content-between">
                            <div>0</div>
                            <div>365</div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label><span id='hire'><?php echo isset($_POST["n"]) ? $_POST["n"] : "0" ?></span> | DAYS TO RUMP UP NEW HIRE</label>
                        <input value="<?php echo isset($_POST["n"]) ? $_POST["n"] : "" ?>" type='range' name="n" id="n" max='365' oninput="mysetval(this.value, 'hire')"/>
                        <div class="input-bottom d-flex justify-content-between">
                            <div>0</div>
                            <div>365</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row px-5">
                <label>CURRENCY</label>
                <input type="text" name="currency" id="currency" disabled value="$ USD (default)" class="rounded border input-text" style="width: 150px">
            </div>
            <div class="row">
                <div class="col px-5">
                    <p class="border-top text-center details">YOUR DETAILS</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6  px-5">
                    <div class="mb-3 ">
                        <label>FIRST NAME</label>
                        <input required class="input-text rounded border border-secondary w-100" value="<?php echo isset($_POST["fName"]) ? $_POST["fName"] : "" ?>" type='text' name="fName" id="fName"/>
                    </div>
                    <div class="mb-3  ">
                        <label>LAST NAME</label>
                        <input required class="input-text rounded border border-secondary w-100" value="<?php echo isset($_POST["lName"]) ? $_POST["lName"] : "" ?>" type='text' name="lName" id="lName"/>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6  px-5">
                    <div class="mb-3 ">
                        <label>COMPANY NAME</label>
                        <input required class="input-text rounded border border-secondary w-100" value="<?php echo isset($_POST["comName"]) ? $_POST["comName"] : "" ?>" type='text' name="comName" id="comName"/>
                    </div>
                    <div class="mb-3 ">
                        <label>COMPANY EMAIL</label>
                        <input required class="input-text rounded border border-secondary w-100" value="<?php echo isset($_POST["comEmail"]) ? $_POST["comEmail"] : "" ?>" type='email' name="comEmail" id="comEmail"/>
                    </div>
                </div>   
                <div class=" d-flex justify-content-center align-content-center pt-5">
                    <button class="btn p-2 btn-color border" type='submit' name="calculate" id="calculate">Calculate</button>
                </div>            
            </div>

        </form>
    </div>
    <script>
            function mysetval(val, id) {
                let result = Number(val).toLocaleString(); // "1,234,567,890"
                document.getElementById(id).innerHTML = result;
                console.log(result);
            }
    </script>
    <?php
}