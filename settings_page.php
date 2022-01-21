<?php
    require "includes/menu.php";
    require "includes/forms/post_form.php";
    require "includes/forms/changepw_form.php";
    require "includes/forms/changepfp_form.php";
?>
    <div class="content p-5">
        <h1>Settings</h1>
        <div class="accordion pt-3" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Change Profile Picture
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form action="settings_page.php" method="POST" enctype="multipart/form-data">
                            <div>
                                <input type="file" name="changepfp_img" class="form-control mb-3" required>
                            </div>
                            <div>
                                <input type="submit" name="changepfp_submit" value="Save Changes" class="btn bg-pink btn-outline-dark w-100">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Change Password
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form action="settings_page.php" method="POST">
                            <div>
                                <div class="mb-2">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="changepw" class="form-control" id="password" required>
                                </div>
                                <label for="password" class="form-label">Confirm Password</label>
                                <input type="password" name="changepw2" class="form-control mb-3" id="c-pass" required>

                                <!-- if incorrect, display error -->
                                <?php 
                                    if(in_array("Your password must be between 8 and 35 characters", $error_msg)){
                                        echo "<div class='alert alert-danger'>Your password must be between 8 and 35 characters</div>";
                                    }elseif(in_array("Passwords don't match" , $error_msg)){
                                        echo "<div class='alert alert-danger'>Passwords don't match</div>";
                                    }
                                ?>
                            </div>

                            <div>
                                <input class="btn btn-primary bg-pink btn-outline-dark w-100"  type="submit" name="changepw_submit" value="Save Changes">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>