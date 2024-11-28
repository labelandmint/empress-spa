<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New User </title>

    <style>
      @media print {
        * {
          -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
        }
      }

      body {
        font-family: Georgia, "Times New Roman", Times, serif;
      }

      .button {
        background-color: #1d1b18;
        color: #fff;
        padding: 10px 18px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
      }

      .button:hover {
        background-color: #d89556;
      }
    </style>
  </head>

  <body style="background-color: #f8f1eb; font-family: Raleway, sans-serif">
    <div class="outer_container">
      <div
        class="inner_container"
        style="background-color: white; max-width: 700px; margin: 0 auto"
      >
        <div
          class="upper_tab"
          style="
            border: 12px solid #1d1b18;
            border-bottom-right-radius: 50px;
            border-bottom-left-radius: 50px;
            height: 0px;
            display: flex;
            align-items: center;
          "
        ></div>
        <table class="main_table" style="margin-top: 30px; width: 100%">
          <tbody>
            <tr>
              <td align="center">
                <img src="{{url('/images/Logo.png')}}" style="width: 300px" />
              </td>
            </tr>
          </tbody>
        </table>
        <table
          class="main_table"
          style="margin-top: 30px; padding: 0px 50px; width: 100%"
        >
          <tbody>
            <tr>
              <td align="left">
                <span
                  class="inner_txt"
                  style="font-size: 17.5px; color: #1d1b18"
                  >Dear</span
                ><span
                  class="inner_txt"
                  style="font-size: 17.5px; color: #1d1b18; font-weight: 600"
                >
                  {{$name}},</span
                >
              </td>
            </tr>
            <tr>
              <td align="center" style="height: 18px"></td>
            </tr>
            <tr>
              <td align="left">
                <span
                  class="inner_txt"
                  style="font-size: 17.5px; color: #1d1b18"
                  >Welcome to Empress Spa! You have been added as a
                  <b>{{$user_role}}</b> member in our system. To complete your
                  registration and start using our platform, please set up your
                  account by creating a password.
                </span>
              </td>
            </tr>
            <tr>
              <td align="center" style="height: 12px"></td>
            </tr>
            <tr>
              <td align="left">
                <span
                  class="inner_txt"
                  style="color: #1d1b18; font-weight: 500; font-size: 18px"
                  >Click the link below to generate your password and gain
                  access to your Empress Spa account:
                </span>
                <p>
                  <a href="{{$resetLink}}" class="button" style="color:white;" 
                    >Generate Password</a
                  >
                </p>
              </td>
            </tr>
            <tr>
              <td align="left">
                <span
                  class="inner_txt"
                  style="font-size: 17.5px; color: #1d1b18"
                  >For your security,
                  <b>this link will expire in 24 hours.</b> If the link has
                  expired or you experience any issues, please contact our
                  support team at team@empress.spa or by calling Melv on 0490
                  783 531.</span
                >
              </td>
            </tr>
            <tr>
              <td align="center" style="height: 12px"></td>
            </tr>
            <tr>
              <td align="left">
                <span
                  class="inner_txt"
                  style="font-size: 17.5px; color: #1d1b18"
                  >We look forward to working with you!
                </span>
              </td>
            </tr>
            <tr>
              <td align="center" style="height: 12px"></td>
            </tr>
            <tr>
              <td align="left">
                <span
                  class="inner_txt"
                  style="font-size: 17.5px; color: #1d1b18"
                  >Best regards,<br /><b>Empress Spa Team</b></span
                >
              </td>
            </tr>
            <tr>
              <td align="center" style="height: 12px"></td>
            </tr>
            <tr>
              <td align="left">
                <span
                  class="inner_txt"
                  style="font-size: 17.5px; color: #1d1b18"
                ></span>
              </td>
            </tr>
            <tr>
              <td align="center" style="height: 30px"></td>
            </tr>
            <tr>
              <td
                align="center"
                style="display: flex; justify-content: center !important"
              >
                <div style="margin-left: auto; margin-right: auto">
                  <a href="https://www.facebook.com/empressspa"
                    >
                    <img
                    src="{{url('/images/facebook1.png')}}"
                     
                      style="
                        width: 40px;
                        border-radius: 50px;
                        margin: 0px 13px;
                      "
                    />
                  </a>
                  <a href="https://www.instagram.com/empressdayspa"
                    ><img
                      src="{{url('/images/instagram1.png')}}"
                      style="
                        width: 40px;
                        border-radius: 50px;
                        margin: 0px 13px;
                      "
                  /></a>
                </div>
              </td>
            </tr>
            <tr>
              <td align="center" style="height: 10px"></td>
            </tr>
            <tr>
              <td align="center">
                <a href="#" style="text-decoration: none"
                  ><span
                    class="inner_txt"
                    style="font-size: 16px; color: #4f4538"
                    >{{ $website_address ?? 'www.empress.spa'}} | {{ $email ?? 'team@empress.spa'}} | {{ $phone ?? '1800 868 888' }}</span
                  ></a>
              </td>
            </tr>
            <tr>
              <td align="center" style="height: 15px"></td>
            </tr>
            <tr>
              <td align="center">
                <div
                  style="
                    background-color: #1d1b18;
                    height: 1px;
                    border-radius: 6px;
                    border: none;
                  "
                ></div>
              </td>
            </tr>
            <tr>
              <td align="center" style="height: 10px"></td>
            </tr>
            <tr>
              <td align="center" style="height: 10px"></td>
            </tr>
          </tbody>
        </table>
        <div
          class="bottom_tab"
          style="
            border: 12px solid #1d1b18;
            border-top-right-radius: 50px;
            border-top-left-radius: 50px;
            height: 0px;
            display: flex;
            align-items: center;
          "
        ></div>
      </div>
    </div>
  </body>
</html>
