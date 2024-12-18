<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Service</title>
</head>
<body>
    <h3 style="text-align: center;font-size: 24px;">Hello {{ $name }}</h3>
    <div class="d-flex align-items-center justify-content-between es-text-lg es-font-mulish-bold">
        <!-- <div class="d-flex justify-content-end">
            <button type="button" class="border-0 bg-transparent" data-bs-dismiss="modal">
                <img src="" alt="" />
            </button>
        </div> -->
    </div>
    <div class="" style="text-align: center;
               padding: 100px 0px;
             ">
        <img class="img600x100 img-fluid"
            style="width: 600px;
                   height: 100px;
                   object-fit: cover;
                   object-position: center;"
            src="{{ url('images/' . $logo) }}" alt="" />
    </div>
    <div class="" style="text-align: center;">
        <img class="img600x300 img-fluid"
            style="  width: 600px;
                      height: 300px;
                      object-fit: cover;
                      object-position: center;"
            src="{{ url('images/' . $graphic) }}" alt="" />
    </div>
    <div class="" style="text-align: center; font-size : 24px;">
        <h3>{{ $header }}</h3>
    </div>
    <div class="" style="text-align: center ; font-size:18px;margin-bottom: 20px;">
        {{ $body }}
    </div>
</body>
</html>
