<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Service</title>
</head>
<body>
    <h3 style="text-align: center;font-size: 24px;">Hello {{$user->f_name .' '.$user->l_name}}</h3>
    <div class="d-flex align-items-center justify-content-between es-text-lg es-font-mulish-bold">
        <!-- <div class="d-flex justify-content-end">
            <button type="button" class="border-0 bg-transparent" data-bs-dismiss="modal">
                <img src="" alt="" />
            </button>
        </div> -->
    </div>
    <div class=""  style="text-align: center;padding: 100px 0px;" >
        <img class="es-w-40" src="{{url('/images/logo.svg')}}" alt=""/>
    </div>
    <div class="" style="text-align: center;">
        <img class="es-w-full" src="{{url('/images/settings-img.png')}}" alt="" />
    </div>
    <div class="" style="text-align: center; font-size : 24px;">
        <h3>Welcome to Empress Spa!</h3>
    </div>
    <div class="" style="text-align: center ; font-size:18px;margin-bottom: 20px;">
        We're excited to have you join our community. Your <br>
        registration is now complete, and you can start <br> exploring
        everything we have to offer. Whether you're <br> looking to unwind
        with a relaxing massage, rejuvenate <br> with a facial, or simply
        enjoy a moment of tranquility, <br>we have something special for
        you.
    </div>
    <div class="" style="font-size: 18px;text-align: center;">
        You can now view your subscription plan directly <br> through our
        app. Simply <a href="{{url('/')}}">log in</a> to access your account,<br> check your plan
        details, and discover exclusive offers tailored just for you!
    </div>
</body>
</html>
