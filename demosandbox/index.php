<!doctype html>
<html>
    <head>
       <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>mPesa Payment Process</title>
    </head>

    <body>
        <div style="width:100%;text-align:center;position:absolute;">
            <form name="mpesaSuccess" id="mpesaSuccess" action="<?php echo $_POST['return_url'];?>" style="text-align:center" method="post">
                <input type="hidden" value="<?php echo $_POST['custom']?>" name="custom" />
                <input type="hidden" name="returnAction" id="returnAction" value="success" />
                <!--<input type="button" name="cancel" id="cancel" value="Cancel"  onclick="document.getElementById('returnAction').value='fail';"/> &nbsp; &nbsp; &nbsp; -->
                <input type="submit" value="Pay by mPesa"  name="submit" id="submit"/>
            </form>
        </div>
    </body>
</html>
