<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $User->pseudo ?>'s Profile</title>
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
                $('#editProfileForm').validate({
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
                                    required: true,
                                    minlength: 3,
                                    maxlength: 16,
                                    regex: /^[a-zA-Z][a-zA-Z0-9]*$/
                                },
                        full_name:
                                {
                                    required: true,
                                    minlength: 3,
                                    maxlength: 16,
                                    regex: /^[a-zA-Z][a-zA-Z0-9]*$/
                                }
                    },
                    messages: {
                        email:
                                {
                                    remote: 'this email is already taken',
                                    required: 'required',
                                    regex: 'bad format for email'
                                }
                        }
  
                });
                $("input:text:first").focus();
            });
        </script>

    </head>
    <body>
        <div class="title">Edit profile!</div>
        <?php include('menu.html'); ?>
        <a href="http://localhost/prwb_1617_G13/User/profile">Back</a>
        <div class="main">
            <form id="editProfileForm" method='post' action='user/edit_profile' enctype='multipart/form-data'>
            <table>
                    <tr>
                        <td>Email:</td>
                        <td><input id="email" name="email" type="text" size="16" value="<?= $User->email ?>"></td>
                        <td class="errors" id="errEmail"></td>
                    </tr>
                    <tr>
                        <td>Pseudo:</td>

                        <td><input id="pseudo" name="pseudo" type="text" size="16" value="<?= $User->pseudo ?>"></td>
                        <td class="errors" id="errPseudo"></td>
                    </tr>
                    <tr>
                        <td>Full name:</td>

                        <td><input id="full_name" name="full_name" type="text" size="16" value="<?= $User->full_name ?>"></td>
                        <td class="errors" id="errFull_name"></td>
                    </tr>
                </table>
                <input type='submit' name="saveprofile" value='Save Profile'>
   
            </form>

            <?php if (strlen($success) != 0): ?>
                <p><span class='success'><?= $success ?></span></p>
                <?php endif; ?>

        </div>
    </body>
</html>

