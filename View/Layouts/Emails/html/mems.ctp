<!DOCTYPE html>
<head>
    <style type="text/css">
        html,body{
            margin:0;
            padding:0;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 13px;
            background-color: #F1F2F7;
            color: #555;
        }
        .big-container{
            width:100%;
            margin:0;
            padding:0;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 13px;
            background-color: #F1F2F7;
            color: #555;
        }
        hr{
            border-top: 1px solid #efefef;
            width: 80%;
            margin-top: 20px;
        }
        .small,small{
            font-size:11px;
        }
        .tiny{
            font-size: 10px;
        }
        .big,big{
            font-size:16px;
        }
        .bold{
            font-weight:bold;
        }
        .container{
            width: 550px;
            margin: 25px auto;
            min-height:200px;
            background-color : #FFF;
            border: 1px solid #ededed;
        }
        .header{
            background-color: #6ccac9;
            color:#FFFFFF;
            min-height: 50px;
            /*height: 90px ;*/
            padding: 10px;
        }
        .mems-logo{
            text-align:center;
        }
        .content{
            min-height:100px;
        }
        .content-text{
            background-color : #f1f2f7;
            width: 85%;
            min-height: 100px;
            margin: 10px auto;
            padding: 20px;
        }
        .clientside{
            /*border-top: 1px solid #efefef;*/
            min-height:20px;
            text-align:center;
            padding : 20px;
        }
        .footer{
            color:#FFF;
            background-color:#59ace2;
            padding: 10px;
            min-height: 20px;
            text-align:center;
        }
        a:link,a:visited,a:active{
            color : #428bca;
            /*font-weight:bold;*/
            text-decoration : none;
            /*font-style: italic;*/
        }
        .btn{
            padding:10px 15px;
            text-decoration: none;
            color:#FFF !important;
            display:block;
            text-align:center;
            margin : 10px 0;
        }
        .btn-primary{
            background-color: #39b2a9;
        }
        .btn-primary:hover{
            background-color: #39b2a9;
        }
        .btn-success{
            background-color:#78CD51;
        }
        .btn-success:hover{
            background-color: #6dbb4a;
        }
        .btn-danger{
            background-color: #ec6459;
        }
        .btn-danger:hover{
            background-color: #ec6459;
        }
        .grayscale{
            -webkit-filter: grayscale(1); -webkit-filter: grayscale(100%);
            filter: gray; filter: grayscale(100%);
        }
        .full-width{
            display:block;
            /*width: 100%;*/
        }
        .gray-text{
            color:#777;
        }
        .contact{
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="big-container" style="width:100%;margin:0;padding:0;font-family: Helvetica, Arial, sans-serif;font-size: 13px;   background-color: #F1F2F7;color: #555;">
        <div class="container" style="width: 550px;margin: 25px auto;min-height:200px;background-color : #FFF;border: 1px solid #ededed;">
            <div class="header" style="background-color: #6ccac9;color:#FFFFFF;min-height: 50px;padding: 10px;">
                <div class="mems-logo" style="text-align:center">
                    <?php
                        echo $this->Html->image(ACCESS_URL."img/mems-logo-email.png?".time(),array('style'=>'max-height:80px;width:auto'));
                        // echo "<br><small> by UNITAR International University </small>";
                    ?>
                </div>
            </div>

            <div class="content" style="min-height:100px;">
                <div class="content-text" style="background-color : #f1f2f7; width: 85%; min-height: 100px; margin: 10px auto; padding: 20px;">
                    <?php
                        echo $this->fetch('content');
                    ?>
                </div>
            </div>

            <hr style="border-top: 1px solid #efefef;width: 80%;margin-top: 20px;">

            <div class="clientside" style="min-height:20px;text-align:center;padding : 20px;">
                <?php
                    echo $this->Html->image(ACCESS_URL."img/unitar-logo.png?".time(),array('class'=>'grayscale','style'=>'max-height:40px;width:auto;-webkit-filter: grayscale(1); -webkit-filter: grayscale(100%);filter: gray; filter: grayscale(100%);'));
                ?>
                <div class="tiny gray-text" style="font-size: 10px;color:#777;">
                    UNITAR is not responsible for any loss and damage made by this email. <br>
                    For assistance, please contact CEO’s Office UNITAR <br>
                    <span class="contact" style="margin-right: 10px;"> <strong> Phone : </strong> 03-76277217 </span>
                    <span class="contact" style="margin-right: 10px;"> <strong> E-mail : </strong> admin@e-memo.unitar.my </span>
                </div>
            </div>

            <div class="footer" style="color:#FFF; background-color:#59ace2;padding: 10px;min-height: 20px;text-align:center;">
                <small style="font-size:11px;"> &copy; UNITAR eMEMO by UNITAR International University </small>
            </div>
        </div>
    </div>
</body>
</html>