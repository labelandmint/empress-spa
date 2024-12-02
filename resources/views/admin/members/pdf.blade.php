<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Report_html</title>
  </head>
  <style>
  
  
@font-face {
    font-family: 'Poppins';
    src: url('{{ public_path('Font/Poppins-Regular.ttf') }}') format('truetype');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'Poppins';
    src: url('{{ public_path('Font/Poppins-Bold.ttf') }}') format('truetype');
    font-weight: bold;
    font-style: normal;
}

@font-face {
    font-family: 'Poppins';
    src: url('{{ public_path('Font/Poppins-ExtraBold.ttf') }}') format('truetype');
    font-weight: extra-bold;
    font-style: normal;
}

@font-face {
  font-family: "Poppins";
  src: url('{{ public_path('Font/Poppins-Light.ttf') }}') format('truetype');
  font-weight: 300; /* Light weight */
  font-style: normal;
}

@font-face {
  font-family: "Poppins";
  src: url('{{ public_path('Font/Poppins-Regular.ttf') }}') format('truetype');
  font-weight: 400; /* Regular weight */
  font-style: normal;
}


@font-face {
  font-family: "Poppins";
  src: url('{{ public_path('Font/Poppins-Medium.ttf') }}') format('truetype');
  font-weight: 500; /* Medium weight */
  font-style: normal;
}

@font-face {
  font-family: "Poppins";
  src: url('{{ public_path('Font/Poppins-SemiBold.ttf') }}') format('truetype');
  font-weight: 600; /* SemiBold weight */
  font-style: normal;
}

@font-face {
  font-family: "Poppins";
  src: url('{{ public_path('Font/Poppins-Bold.ttf') }}') format('truetype');
  font-weight: 700; /* Bold weight */
  font-style: normal;
}

@font-face {
  font-family: "Poppins";
  src: url('{{ public_path('Font/Poppins-ExtraBold.ttf') }}') format('truetype');
  font-weight: 800; /* ExtraBold weight */
  font-style: normal;
}

@font-face {
  font-family: "Poppins";
  src: url('{{ public_path('Font/Poppins-Black.ttf') }}') format('truetype');
  font-weight: 900; /* Black weight */
  font-style: normal;
}


    @page {
        margin: 0;
    }
    
    
    @media print {
      * {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }
    
    
    body {
      background-color: #1B1C1C;
      font-family: "Poppins", sans-serif;
      --print-color-adjust: exact;
      padding: 20px 40px;
      margin:0;
      /*background-image: url('data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/square-logo.webp'))) }}');*/
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
      vertical-align:middle;
        
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
      border: 0.3px solid #FAD479;
      padding: 6px 5px;
    }
    
    
    .tab2 span {
      font-weight: 700;
    }
    
    
    .tab3 {
      background-color: #FAD479;
      font-weight: 700;
      font-size: 8px;
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
                      style="color: #FAD479; font-size: 11px; font-weight: 400"
                      >REPORT PRODUCED BY</span
                    ><br /><span
                      style="font-size: 20px; font-weight: bold; color: white"
                      >{{Auth::guard('admin')->user()->f_name .' '. auth::guard('admin')->user()->l_name}}</span
                    >
                  </td>
                  <td width="50%" align="right" valign="top">
                    <!-- <img
                      src="/Assets/colored-empress-spa-logo.png"
                      alt="emp_logo"
                      style="width: 310px"
                    /> -->
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/colored-empress-spa-logo.png'))) }}" alt=""  style="width: 310px" />
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
                          <!-- <img src="/Assets/phone.png" alt="phnicn" /> -->
                          <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/phone.png'))) }}" alt="" >
                        </div>
                        <div class="child2">
                          <span style="font-weight: bold">Phone Number</span><br />
                          <span>{{$setting->business_phone_number}}</span>
                        </div>
                      </div>
                    </td>
                    <td width="30%" align="left" valign="top">
                      <div class="parent">
                        <div class="child1">
                          <!-- <img src="/Assets/website.png" alt="website" /> -->
                          <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/website.png'))) }}" alt="" />
                        </div>
                        <div class="child2">
                          <span style="font-weight: bold">Website</span><br />
                          <span>{{$setting->business_website_address}}</span>
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
                          <!-- <img src="/Assets/email.png" alt="email" /> -->
                           <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/email.png'))) }}" alt=""  />
                        </div>
                        <div class="child2">
                          <span style="font-weight: bold">Email</span><br />
                          <span>{{$setting->business_email_address}}</span>
                        </div>
                      </div>
                    </td>
                    <td width="30%" align="left" valign="top">
                      <div class="parent">
                        <div class="child1">
                          <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/address.png'))) }}" alt=""  />
                        </div>
                        <div class="child2">
                          <span style="font-weight: bold">Address</span><br />
                          <span>{{$setting->business_address1}}</span><br />
                          <span>{{$setting->business_address2}}{{$setting->city}}</span><br />
                          <span>{{$setting->state}}{{$setting->postcode}}</span>
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
                <td width="100%" align="left" valign="top">
                  <span
                    style="
                      border-radius: 9px;
                      color: black;
                      background-color: #FAD479;
                      font-weight: bold;
                      letter-spacing: 3px;
                      font-size: 25px;
                      padding: 9px 14px;
                    "
                    >TRANSACTIONS SUMMARY</span
                  >
                </td>
              </tr>
              <tr>
                <td align="center" valign="top" height="50">&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </tr>
      </tbody>
    </table>
    <div
      style="border: 0.3px solid #FAD479; border-radius: 6px; min-height: 300px"
    >
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td align="center" valign="top">
              <table
                width="100%"
                border="0"
                cellspacing="0"
                cellpadding="10"
                style="padding: 20px 30px"
              >
                <tbody>
                  <tr>
                    <td width="23%" align="center" valign="center">
                      <div class="tab2">
                        <span>Total Subscriptions</span><br />{{$subsCount}}
                      </div>
                    </td>
                    <td width="23%" align="center" valign="center">
                      <div class="tab2">
                        <span>Total This Month</span><br />{{$subsCountThisMonth}}
                      </div>
                    </td>
                    <td width="23%" align="center" valign="center">
                      <div class="tab2">
                        <span>Total Last Month</span><br />{{$subsCountLastMonth}}
                      </div>
                    </td>
                    <td width="32%" align="center" valign="center">
                      <div class="tab2">
                        <span>Total Subscriptions Value</span><br />${{number_format($subsAmount,2)}}                      
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
                style="padding: 0px 8px"
              >
                <tbody>
                  <tr style="padding: 0 10px">
                    <td width="20%" align="center" valign="center">
                      <div
                        class="tab3"
                        style="
                          border-top-left-radius: 10px;
                          border-bottom-left-radius: 10px;
                        "
                      >
                        <span>NAME</span>
                      </div>
                    </td>
                    <td width="20%" align="center" valign="center">
                      <div class="tab3"><span>SUBSCRIPTION</span></div>
                    </td>
                    <td width="20%" align="center" valign="center">
                      <div class="tab3"><span>LAST SERVICE</span></div>
                    </td>
                    <td width="20%" align="center" valign="center">
                      <div class="tab3"><span>LAST BOOKING DATE & TIME</span></div>
                    </td>
                    <td width="20%" align="center" valign="center">
                      <div
                        class="tab3"
                        style="
                          border-top-right-radius: 10px;
                          border-bottom-right-radius: 10px;
                        "
                      >
                        <span>LAST PAYMENT DATE</span>
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
                style="padding: 0px 8px"
              >
                @foreach ($members as $member)
                    <tr style="padding: 0 10px">
                        <td width="20%" align="center" valign="center">
                            <div class="tab4"> <span>
                            {{ $member->f_name.' '.$member->l_name }}</span></div>
                        </td>
                        <td width="20%" align="center" valign="center">
                            <div class="tab4"> <span>
                            {{ $member->title }}</span></div>
                        </td>
                        <td width="20%" align="center" valign="center">
                            <div class="tab4"> <span>{{$member->service ?? 'N/A'}}</span></div>
                        </td>
                        <td width="20%" align="center" valign="center">
                            <div class="tab4"> 
                                <span>
                                    {{ $member->booking_date ? \Carbon\Carbon::parse($member->booking_date)->format('M d, Y') : 'N/A' }}
                                </span><br>
                                <span> 
                                    @if($member->start_time)
                                    {{ \Carbon\Carbon::parse($member->start_time)->format('g:i A') }} - 
                                    {{ \Carbon\Carbon::parse($member->end_time)->format('g:i A') }}
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td width="20%" align="center" valign="center">
                            <div class="tab4"> 
                                <span> {{ \Carbon\Carbon::parse($member->payment_date)->format('M d, Y') }}</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>
