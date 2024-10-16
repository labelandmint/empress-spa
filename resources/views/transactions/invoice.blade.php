<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Report_html</title>
  </head>

  <style>
    @font-face {
      font-family: "Poppins";
      src: url("fonts/Poppins-Regular.ttf") format("truetype");
      font-weight: 100 900;
      font-style: normal;
    }

    @media print {
      * {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }
    
    @page {
        margin: 0;
    }
    

    body {
      background-color: #1b1c1c;
      font-family: "Poppins", sans-serif;
      --print-color-adjust: exact;
      padding: 20px 40px;
    }

   
.text-box{

  font-size: 12px;
}


    .parent {
       overflow: hidden;
      }


    .child1 img {
      width: 28px;
    }

    .child1 {
        display:inline-block;
        line-height:0.8;
      }


      
    .child2 span {
      font-size: 11px;
      color: white;
    }

    .child2 {
        display: inline-block;
        vertical-align: middle;
        margin-left: 8px;
        line-height: 1.1;
      }

    .tab2 {
      color: white;
      font-size: 10px;
      border: 0.3px solid #fad479;
      padding: 3px 5px;
    }

    .tab2 span {
      font-weight: 700;
    }

    .tab3 {
      background-color: #fad479;
      font-weight: 700;
      font-size: 12px;
      padding: 8px 2px;
    }

    .tab4 {
      color: white;
      font-size: 11px;
      padding: 10px 1px;
    }
  </style>

  <body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <td align="center" valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td width="100%" align="left" valign="top">
                    <span
                      style="color: #fad479; font-size: 11px; font-weight: 400"
                      >INVOICE TO</span
                    ><br /><span
                      style="font-size: 20px; font-weight: bold; color: white"
                      >{{$transaction->f_name.' '.$transaction->l_name}}</span
                    >
                  </td>
                  <td width="50%" align="right" valign="top">
                    <img
                      src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/colored-empress-spa-logo.png'))) }}"
                      alt="emp_logo"
                      style="width: 310px"
                    />
                  </td>
                </tr>
                <tr>
                  <td align="center" valign="top" height="20">&nbsp;</td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
            <td colspan="3">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                 
                  <tr>
                    <td width="44%"></td>
                    <td width="35%" align="left" valign="top">
                      <div class="parent">
                        <div class="child1">
                          <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/phone.png'))) }}" alt="phnicn" />
                        </div>
                        <div class="child2">
                          <span style="font-weight: bold">Phone Number</span><br />
                          <span>1800 868 888</span>
                        </div>
                      </div>
                    </td>
                    <td width="30%" align="left" valign="top">
                      <div class="parent">
                        <div class="child1">
                          <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/website.png'))) }}" alt="website" />
                        </div>
                        <div class="child2">
                          <span style="font-weight: bold">Website</span><br />
                          <span>www.empress.spa</span>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td colspan="3">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                  <tr>
                      <td align="center" valign="top" height="1">&nbsp;</td>
                    </tr>
                  <tr>
                    <td width="44%"></td>
                    <td width="35%" align="left" valign="top">
                      <div class="parent">
                        <div class="child1">
                          <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/email.png'))) }}" alt="email" />
                        </div>
                        <div class="child2">
                          <span style="font-weight: bold">Email</span><br />
                          <span>booking@empressspa.com.au</span>
                        </div>
                      </div>
                    </td>
                    <td width="30%" align="left" valign="top">
                      <div class="parent">
                        <div class="child1">
                          <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/address.png'))) }}" alt="address" />
                        </div>
                        <div class="child2">
                          <span style="font-weight: bold">Address</span><br />
                          <span>
                            226-228 Cooper St.<br />
                            Epping VIC 3076
                          </span>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        <tr>
          <td align="center" valign="top" height="10">&nbsp;</td>
        </tr>
        <tr>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
              <tr>
                <td width="50%" align="left" valign="top">
                  <span
                    style="
                      border-radius: 9px;
                      color: black;
                      background-color: #fad479;
                      font-weight: bold;
                      letter-spacing: 3px;
                      font-size: 25px;
                      padding: 3px 14px;
                    "
                    >TAX INVOICE</span
                  >
                </td>
                <td width="50%" align="right" valign="top" style="padding-right:20px;">
                  <span
                    style="color: #fad479; font-size: 11px; font-weight: 400"
                    >{{$transaction->title}}</span
                  ><br /><span
                    style="font-size: 20px; font-weight: bold; color: white"
                    >${{number_format($transaction->amount,2)}}</span
                  >
                </td>
              </tr>
              <tr>
                <td align="center" valign="top" height="40">&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </tr>
      </tbody>
    </table>
    <div
      style="background-color: #232323; 
      border-radius: 6px; 
      padding: 20px 0;
      background-image: url('/Assets/square-logo.png');
      background-position: center; 
      background-size:contain;
      background-repeat: no-repeat; /* Prevents the image from repeating */">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td align="center" valign="top">
              <table>
                <tbody>
                  <tr>
                    <td align="center" valign="top" height="10">&nbsp;</td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center" valign="top">
              <table
                width="100%"
                border="0"
                cellspacing="0"
                cellpadding="0"
                style="padding: 0px 8px"
              >
                <tbody>
                  <tr style="padding: 0 10px">
                    <td width="15%" align="left" valign="center">
                      <div
                        class="tab3"
                        style="
                          border-top-left-radius: 10px;
                          border-bottom-left-radius: 10px;padding-left:20px;
                        "
                      >
                        <span>No.</span>
                      </div>
                    </td>
                    <td width="30%" align="left" valign="center">
                      <div class="tab3"><span>SUBSCRIPTION</span></div>
                    </td>
                    <td width="20%" align="center" valign="center">
                      <div class="tab3"><span>PRICE</span></div>
                    </td>
                    <td width="20%" align="center" valign="center">
                      <div class="tab3"><span>QUANTITY</span></div>
                    </td>
                    <td width="20%" align="center" valign="center">
                      <div
                        class="tab3"
                        style="
                          border-top-right-radius: 10px;
                          border-bottom-right-radius: 10px;
                        "
                      >
                        <span>AMOUNT</span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center" valign="top">
              <table
                width="100%"
                border="0"
                cellspacing="0"
                cellpadding="0"
              >
                <tbody>
                  <tr style="padding: 0 10px">
                    <td width="15%" align="left" valign="center">
                      <div class="tab4" style="padding-left:20px;"><span>1</span></div>
                    </td>
                    <td width="30%" align="left" valign="center">
                      <div class="tab4"><span>{{$transaction->title}}</span><br/>
                        <span style="font-size: 10px;">{{$transaction->description}}</span></div>
                    </td>
                    <td width="20%" align="center" valign="center">
                      <div class="tab4"><span>${{$transaction->amount}}</span></div>
                    </td>
                    <td width="20%" align="center" valign="center">
                      <div class="tab4"><span>1</span></div>
                    </td>
                    <td width="20%" align="center" valign="center">
                      <div class="tab4"><span>${{$transaction->amount}}</span></div>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" valign="top" height="90">&nbsp;</td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center" valign="top">
              <table
                width="100%"
                border="0"
                cellspacing="0"
                cellpadding="0"
              >
                <tbody>
                  <tr style="padding: 0 10px">
                    <td width="53%" align="left" valign="center">
                      <div class="tab4" style="padding-left:20px;"><span></span></div>
                    </td>
                    <td width="32%" align="left" valign="center">
                      <div class="tab4"><span>Sub Total</span></div>
                    </td>
                    <td width="15%" align="center" valign="center">
                      <div class="tab4"><span>${{number_format($transaction->amount,2)}}</span></div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center" valign="top">
              <table
                width="100%"
                border="0"
                cellspacing="0"
                cellpadding="0"
              >
                <tbody>
                  <tr style="padding: 0 10px">
                    <td width="53%" align="left" valign="center">
                      <div class="tab4" style="padding-left:20px;padding-top:0px;padding-bottom:0px;"><span>&nbsp;</span></div>
                    </td>
                    <td width="47%" align="left" valign="center" style="padding-right:20px;">
                      <div class="tab4" style="border-top:2px solid white;padding-top:0px;padding-bottom:0px;"><span>&nbsp;</span></div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center" valign="top">
              <table
                width="100%"
                border="0"
                cellspacing="0"
                cellpadding="0"
              >
                <tbody>
                  <tr style="padding: 0 10px">
                    <td width="53%" align="left" valign="center">
                      <div class="tab4" style="padding-left:20px;padding-top:0px;"><span></span></div>
                    </td>
                    <td width="32%" align="left" valign="center">
                      <div class="tab4" style="padding-bottom:10px;"><span>GST (10%)</span></div>
                    </td>
                    <td width="15%" align="center" valign="center">
                      <div class="tab4" style="padding-bottom:10px;"><span>N/A</span></div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center" valign="top">
              <table
                width="100%"
                border="0"
                cellspacing="0"
                cellpadding="0"
              >
                <tbody>
                  <tr style="padding: 0 10px">
                    <td width="53%" align="left" valign="center">
                      <div class="tab4" style="padding-left:20px;padding-top:20px;"><span></span></div>
                    </td>
                    <td width="32%" align="left" valign="center">
                      <div class="tab4" style="padding:0px;border-radius: 9px;
                      color: black;
                      background-color: #fad479;
                      font-weight: bold;
                      padding: 3px 14px;border-top-right-radius:0px;border-bottom-right-radius:0px;"><spanm style="font-size:22px;">TOTAL</spanm></div>
                    </td>
                    <td width="15%" align="center" valign="center" style="padding-right:20px;">
                      <div class="tab4" style="padding:0px;border-radius: 9px;
                      color: black;
                      background-color: #fad479;
                      font-weight: bold;
                      padding: 3px 14px;border-top-left-radius:0px;border-bottom-left-radius:0px;"><span style="font-size:22px;">${{number_format($transaction->amount,2)}}</span></div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <table width="100%" style="margin-top: 5px;">
      <tr>
          <td width="50%" valign="top" style="padding: 20px; color: white;">
              <div class="heading" style="font-size: 15px;font-weight: bold;padding-bottom:1px;">
                  Thank You for choosing Empress Spa!
              </div>
              <div class="text-box">
                  We sincerely appreciate your trust in us and look forward to
                  providing you with a rejuvenating experience. If you have any
                  questions regarding your service or invoice, feel free to contact us
                  at info@empressspa.com.au or <span>1800 868 888</span>. We can't wait to welcome
                  you back soon!
              </div>
  
              <div style="color: white;margin-top: 30px;font-size:14px;">Warm regards,</div>
              <div class="text-box">The Empress Spa Team</div>
              <div class="text-box" style="color: white;">www.empress.spa</div>
  
              <div style="text-align: left; margin: 20px 0;">
                <hr style="background-color: #FAD479; height: 5px; border: none; width: 100%;" />
            </div>            
  
              <div class="es-font-poppins-bold es-text-xl mb-1" style="color: #FAD479;">Payment Method</div>
              <table style="color: white;">
                  <tr>
                      <td style="padding-right: 20px; font-size:13px;">Credit Card</td>
                      <td style="font-size: 13px;">: {{ str_repeat('xxxx ', 3) }}{{ substr($BankDetail->card_no, -4) }}</td>
                  </tr>

                  <tr>
                      <td style="padding-right: 20px;font-size:13px;">Account Name</td>
                      <td style="font-size:13px;">: {{$BankDetail->cardholder_name}}</td>
                  </tr>
                  <tr>
                      <td style="padding-right: 20px;font-size:13px;">Billing Email</td>
                      <td style="font-size:13px;">: {{Auth::user()->email}}</td>
                  </tr>
              </table>
          </td>
          <td width="50%" valign="top" style="padding: 20px; text-align: center;">
              <!-- <img
                  class="es-rounded-2xl"
                  src="/Assets/empress-spa-reception.png"
                  style="width: 100%; object-fit: contain;height: 330px;"
                  alt="Empress Spa Reception"
              /> -->
              <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/empress-spa-reception.png'))) }}" style="width: 100%; object-fit: contain;height: 330px;"
                  alt="Empress Spa Reception"  />
          </td>
      </tr>
  </table>
  </body>
</html>
