<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <style>
        .bg {
            width: 100%;
            height: auto;
            min-height: 100vh;
            background-image: url(http://i.imgur.com/w16HASj.png);
            background-size: 100% 100%;
            background-position: top center
        }

        .content {
            margin-top: 20%
        }

        .centered {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%)
        }

        .InputStyle {
            border-radius: 25px;
            border: solid 1px white;
            background: transparent;
            width: 300px;
            padding: 10px 20px
        }

        input,
        input::-webkit-input-placeholder {
            font-size: 12px;
            color: white
        }

        .social-btn {
            position: absolute;
            bottom: 20px;
            left: 47%
        }

        i {
            padding: 5px;
            color: white
        }

        input,
        input:focus {
            border: solid 1px white;
            outline: 0;
            -webkit-appearance: none;
            box-shadow: nones;
            -moz-box-shadow: none;
            -webkit-box-shadow: none
        }

        .secondLine {
            font-weight: 350;
            font-size: 15px;
            margin-bottom: 15%;
            color: white
        }

        .firstLine {
            font-size: 30px;
            color: white
        }

        @media only screen and (max-width: 600px) {
            .firstLine {
                font-size: 20px
            }
        }
    </style>
</head>
<body>
@if($flag == 'reverify_yes')
    <div class="container"
         style="font-family: Arial; width:80%;height:750px; margin: auto; border-style: dashed; border-color: #ace5ee">
        <div class="form-group mt-4 mb-0" style="float: right;margin-top: 5px;margin-right: 5px;">
            <img src="{{asset('images/NSID_logo_new.PNG')}}">
        </div>
        <h2 style="text-align: center;margin-top: 90px;"><u>Inventory Re-Verification</u></h2>
        <p style="font-size: 18px;margin-left: 20px;">
            User: <b>{{$data['emp_name']}}</b><br/>
            Inventory: {{$data['subcategory']}}<br/>
            Serial#: {{$data['product_sn']}}<br/>
            Make: {{$data['make']}}<br/>
            Model: {{$data['inventory_name']}}<br/>
        </p>
        <p style="font-size: 18px;margin-left: 20px;">
            By submitting this page, you are confirming that you have received this equipment and now you are
            responsible
            for following.
        </p>
        <p style="font-size: 18px;margin-left: 50px; line-height: 1.5;">
            • This equipment is now in your responsibility.<br/>
            • Keep this equipment safe and protect it from any damage.<br/>
            • Do not keep beverages or any liquid material close to it to protect it from any accident.<br/>
            • Do not travel with this equipment (other than Laptop).<br/>
            • If this equipment is Laptop and you are travelling with it, keep it in Car Trunk.<br/>
            • Do not replace it with similar equipment of another user.<br/>
        </p>
        <div style="width: 50%;height: 300px; ">
            <form method="POST" action="{{ url('reverify_inventory_feedback/'.$employee->id.'/'.$data['inventory_id'].'/'.$flag.'/'.$find->id) }}">
                @csrf
                <div class="col-md-8" style="margin-left: 10px;">
                    <label style="font-size: 25px;margin-left: 50px;"><b>Remarks:</b> </label><br/>
                    <textarea rows="5" cols="70" name="feedback"
                              style="font-family: Roboto;width: auto;  margin-left: 50px; font-size: 20px; border-radius: 10px;padding-left: 10px;padding-top: 10px;"></textarea>
                </div>
                <div class="button" style="margin-left: 55px ">
                    <input type="submit" value="Submit" name="add_remarks"
                           style="width: 200px;height: 50px; font-size: 20px; border-radius: 10px; background-color:#4682b4;">
                    <span class="small text-danger">{{ $errors->first('feedback') }}</span>
                </div>
            </form>
        </div>
    </div>
@else
    <div class="container"
         style="font-family: Arial; width:80%;height:750px; margin: auto; border-style: dashed; border-color: #ace5ee">
        <div class="form-group mt-4 mb-0" style="float: right;margin-top: 5px;margin-right: 5px;">
            <img src="{{asset('images/NSID_logo_new.PNG')}}">
        </div>
        <h2 style="text-align: center;margin-top: 90px;"><u>Inventory Re-Verification</u></h2>
        <p style="font-size: 18px;margin-left: 20px;">
            User: <b>{{$data['emp_name']}}</b><br/>
            Inventory: {{$data['subcategory']}}<br/>
            Serial#: {{$data['product_sn']}}<br/>
            Make: {{$data['make']}}<br/>
            Model: {{$data['inventory_name']}}<br/>
        </p>
        <p style="font-size: 18px;margin-left: 20px;">
            By submitting this page, you are confirming that you have <b>NOT</b> received this equipment and IT Store
            will check and revoke this equipment from you.
        </p>

        <div style="width: 50%;height: 300px; ">
            <form method="POST" action="{{ url('reverify_inventory_feedback/'.$employee->id.'/'.$data['inventory_id'].'/'.$flag.'/'.$find->id) }}">
                @csrf
                <div class="col-md-8" style="margin-left: 10px;">
                    <label style="font-size: 25px;margin-left: 50px;"><b>Remarks:</b> </label><br/>
                    <textarea rows="5" cols="70" name="feedback"
                              style="font-family: Roboto;width: auto;  margin-left: 50px; font-size: 20px; border-radius: 10px;padding-left: 10px;padding-top: 10px;"></textarea>
                    <span class="small text-danger">{{ $errors->first('feedback') }}</span>
                </div>
                <div class="button" style="margin-left: 55px ">
                    <input type="submit" value="Submit" name="add_remarks"
                           style="width: 200px;height: 50px; font-size: 20px; border-radius: 10px; background-color:#4682b4;">
                </div>
            </form>
            @if (session('msg'))
                <div class="alert alert-success">
                    <b>{{ session('msg') }}</b>
                </div>
            @endif
        </div>
    </div>
@endif
</body>
</html>