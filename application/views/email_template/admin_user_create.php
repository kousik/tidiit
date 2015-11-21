<div style="width:800px;">
    <div style="width:100%">
        <div style="width: 30%;text-align: left;float: left;"><img src="http://tidiit.com/resources/images/logo.png" title="Tidiit Inc Ltd" width="150px"></div>
        <div style="width: 70%;text-align: left;float: left;"><h1>Your login data at Tidiit Inc. Ltd.</h1></div>
    </div>
    <div style="clear: both;width:100%; height: 40px"></div>
    <div style="clear: both;width:100%;text-align: center;">
        <table style="text-align: center;">
            <tr><td><h3>Your login credentials are bellow</h3></td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td>Your Username : <?php echo $userDetails['userName'];?></td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td>Your Password is : <?php echo $userDetails['password'];?></td>
            </tr>
        </table>
    </div>
    <div style="clear: both;width:100%; height: 40px"></div>
    <div style="clear: both;width:100%; text-align: center;">Copyright &copy; <?php echo date('Y');?> Tidiit.com All rights reserved.</div>
</div>