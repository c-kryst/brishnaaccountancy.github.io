<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - SB Admin</title>
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="../vendor/parsley/parsley.css"/>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <span id="error"></span>
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Brishna Admin Login</h3></div>
                                    <div class="card-body">
                                        <form method="POST" id="login_form">
                                            <div class="form-floating mb-3">
                                                <input type="text" name="admin_email_address" id="admin_email_address" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" placeholder="Enter Email Address..." />
                                                <label for="admin_email_address">Email address</label>
                                            </div>  
                                            <div class="form-floating mb-3">
                                                <input type="password" name="admin_password" id="admin_password" class="form-control" required  data-parsley-trigger="keyup" placeholder="Password" />
                                                <label for="admin_password">Password</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button type="submit" name="login_button" id="login_button" class="btn btn-primary btn-user btn-block">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small">Brishna @ <?php echo date('Y'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="../vendor/jquery/jquery-3.6.0.min.js"></script>
        <script src="../vendor/bootstrap/bootstrap.bundle.min.js"></script>
        <script src="../js/scripts.js"></script>
        <script src="../vendor/parsley/dist/parsley.min.js"></script>
    </body>
</html>

<script>

$(document).ready(function(){

    $('#login_form').parsley();

    $('#login_form').on('submit', function(event){
        event.preventDefault();
        if($('#login_form').parsley().isValid())
        {       
            $.ajax({
                url:"login_action.php",
                method:"POST",
                data:$(this).serialize(),
                dataType:'json',
                beforeSend:function()
                {
                    $('#login_button').attr('disabled', 'disabled');
                    $('#login_button').val('wait...');
                },
                success:function(data)
                {
                    $('#login_button').attr('disabled', false);
                    if(data.error != '')
                    {
                        $('#error').html(data.error);
                        $('#login_button').val('Login');
                    }
                    else
                    {
                        window.location.href = data.url;
                    }
                }
            })
        }
    });

});

</script>