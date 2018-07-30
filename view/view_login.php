<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Log In</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.1.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.16.0/jquery.validate.min.js" type="text/javascript"></script>
        <script>
            $.validator.addMethod("regex", function (value, element, pattern) {
                if (pattern instanceof Array) {
                    for (p of pattern) {
                        if (!p.test(value))
                            return false;
                    }
                    return true;
                } else {
                    return pattern.test(value);
                }
            }, "Please enter a valid input.");
            $(function () {
                $('#loginForm').validate({
                    rules: {
                        email: {
                            //remote: 'member/pseudo_available_service',
                            remote: {
                                url: 'user/email_available_serviceLog',
                                type: 'post',
                                data: {
                                    id: function () {
                                        return $("#email").val();
                                    }
                                }
                            },
                            required: true,
                            regex: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                        },
                        password: {
                            required: true,
                            minlength: 8,
                            maxlength: 16,
                            regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/]
                        }
                    },
                    messages: {
                        email:
                                {
                                    remote: 'this email did not exist',
                                    required: 'required',
                                    regex: 'bad format for email'
                                },
                        password: {
                            required: 'required',
                            minlength: 'minimum 8 characters',
                            maxlength: 'maximum 16 characters',
                            regex: ' Bad format for password'
                        }

                    }
                });
                $("input:text:first").focus();
            });
        </script>     

    </head>
    <body>
        <div class="title">Log In</div>
        <div class="menu">
            <a href="main/index">Home</a>
            <a href="main/signup">Sign Up</a>
        </div>
        <div class="main">
            <form id="loginForm" action="main/login" method="post">
                <table>
                    <tr>
                        <td>Email:</td>
                        <td><input id="email" name="email" type="text" value="<?= $email ?>"></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input id="password" name="password" type="password" value="<?= $password ?>"></td>
                    </tr>
                </table>
                <input type="submit" value="Log In">
            </form>
            <?php if ($error): ?>
                <div class='errors'><br><br><?= $error ?></div>
                <?php endif; ?>
        </div>
    </body>
</html>
