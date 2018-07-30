<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sign Up</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.1.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.16.0/jquery.validate.min.js" type="text/javascript"></script>
        <script>
            $.validator.addMethod("checkPwd", function (value, element, pattern) {
                if(value==($("#password").val()))
                {
                    return true;
                }
                else
                {
                    return false;
                }

            }, "Please enter a valid input.");
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
                $('#signupForm').validate({
                    rules: {
                        email: {
                            remote: {
                                url: 'user/email_available_service',
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
                        pseudo:
                                {
                                    remote: {
                                        url: 'user/pseudo_available_service', 
                                        type: 'post',
                                        data: {
                                            id: function () {
                                                return $("#pseudo").val();
                                            }
                                        }
                                    },
                                    required: true,
                                    minlength: 4,
                                    maxlength: 16,
                                    regex: /^[a-zA-Z][a-zA-Z0-9]*$/
                                },
                        full_name:
                                {
                                    required: true,
                                    minlength: 3,
                                    maxlength: 16,
                                    regex: /^[a-zA-Z][a-zA-Z0-9]*$/
                                },
                        password: {
                            required: true,
                            minlength: 8,
                            maxlength: 16,
                            regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/]
                        },
                        password_confirm: {
                            required: true,
                            minlength: 8,
                            maxlength: 16,
                            checkPwd: '',
                            regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/]
                        }
                    },
                    messages: {
                        email:
                                {
                                    remote: 'this email is already taken',
                                    required: 'required',
                                    regex: 'bad format for email'
                                },
                        pseudo: {
                            remote: 'this pseudo is already taken',
                            required: 'required',
                            minlength: 'minimum 4 characters',
                            maxlength: 'maximum 16 characters',
                            regex: 'bad format for pseudo'
                        },
                        full_name: {
                            required: 'required',
                            minlength: 'minimum 3 characters',
                            maxlength: 'maximum 16 characters',
                            regex: 'bad format for full_name'
                        },
                        password: {
                            required: 'required',
                            minlength: 'minimum 8 characters',
                            maxlength: 'maximum 16 characters',
                            regex: 'bad password format'
                        },
                        password_confirm: {
                            required: 'required',
                            minlength: 'minimum 8 characters',
                            maxlength: 'maximum 16 characters',
                            checkPwd: 'must be the same password',
                            regex: 'bad password format'
                        }
                    }
                });
                $("input:text:first").focus();
            });
        </script>           

    </head>
    <body>
        <div class="title">Sign Up</div>
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="main/login">Log In</a>
        </div>
        <div class="main">
            Please enter your details to sign up :
            <br><br>
            <form id="signupForm" action="main/signup" method="post">
                <table>
                    <tr>
                        <td>Email:</td>
                        <td><input id="email" name="email" type="text" size="16" value="<?php echo $email ?>"></td>
                        <td class="errors" id="errEmail"></td>
                    </tr>
                    <tr>
                        <td>Pseudo:</td>

                        <td><input id="pseudo" name="pseudo" type="text" size="16" value="<?= $pseudo ?>"></td>
                        <td class="errors" id="errPseudo"></td>
                    </tr>
                    <tr>
                        <td>Full name:</td>

                        <td><input id="full_name" name="full_name" type="text" size="16" value="<?= $full_name ?>"></td>
                        <td class="errors" id="errFull_name"></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input id="password" name="password" type="password" size="16" value="<?= $password ?>"></td>
                        <td class="errors" id="errPassword"></td>
                    </tr>
                    <tr>
                        <td>Confirm Password:</td>
                        <td><input id="passwordConfirm" name="password_confirm" size="16" type="password" value="<?= $password_confirm ?>"></td>
                        <td class="errors" id="errPasswordConfirm"></td>
                    </tr>
                </table>
                <input id="btn" type="submit" value="Sign Up">
            </form>
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <br><br><p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>