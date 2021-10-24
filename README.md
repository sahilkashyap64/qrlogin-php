# How to run the project
- rename .env.example to .env
- add db connection in .env
```
composer install
php artisan migrate 
php artisan serve
```
### Vist the link
- register user and login
- open QR scanner using http://baseurl/qrscanner

### Open incognito tab in browser
- open http://baseurl/qrstest
click on 'login with qr' button
qr code comes up save the qr as image.


- In your QR scanner tab
import the QR image(for localhost only), it will be scanned.
OR use the camera to scan(probably will work on prod)




# Show QR Login
### Display QR code 
Three APIs
- Create QR code from backend (api/login/create/qrcode)
1. Poll scan/qrcode api
2. Poll web/login/entry/login 

## Routes

### 1. Get QR code from backend
```sh
method: "POST"
url: "api/login/create/qrcode"
```
on success response
```js
success: function(data) {
        if (data.status == 1) {
            var qrcodeimg = data.msg;
            $('#key').val(data.key); //key key in DOM
            //qrcodeimg looks like this: baseurl/sjjh222.png 
                $('.qrcode-img').attr('src', qrcodeimg); //set img on DOM
            var inter = setInterval(function() {
                //hit QRscan api
                is_sacn_qrcode(); //Poll For QR scan status
            }, 3000);
            $('#timing').val(inter);
        }
```
### 2. Poll for scanned qr status
```sh
method: "POST"
url: "api/login/scan/qrcode",
data:{key:key}
```
on success response
```js
success: function(data) {
    if (data.status == 1) {
        // Scan code successfully   
        // Cancel timing tasks
        clearInterval($('#timing').val());
        $('#timing').val('');

        var is_login = setInterval(function() {
            //hit login api
            is_loginfun(); //Poll For Login status
        }, 3000);
        $('#is_login').val(is_login);

    } else if (data.status == 2) {
        $('.timeout,.mask').show();
        // Cancel timing tasks 
        clearInterval($('#timing').val());
        $('#timing').val('');
    }
}
```
### 3. Poll for login qr status
```sh
method: "POST"
url: "web/login/entry/login",
data:{key:key}
```
on success response
```js
success: function(data) {
    if (data.status == 1) {
        var uid = data.jwt;
        var user = data.user;
        console.log("user", user);
        //  var sign = data.sign;
        // Cancel timed tasks and clear cookies  
        clearInterval($('#is_login').val());
        $('#is_login').val('');

        console.log("login successfull", uid);

        $('.qrcode-img').attr('src', '');
        alert("login successfull", uid);
        $('#thelogindata').text(uid)
        window.location.href = '/';

    } else if (data.status == 2) {
        // Cancel timed tasks
        clearInterval($('#is_login').val());
        $('#is_login').val('');
        alert(data.msg);
    }
}
```






# QR scanner App
## Scanner to be accessed by Authenticated User
Two APIs
- Scan the QR code by qrreader,set the scanned text as api url
1.  Attach user passcode with the scanned QR code
2. Do login in the QRcode
## Routes

### 1. Send UserPasscode in header
```sh
method: "POST"
url: "/api/login/mobile/scan/qrcode?key=xxx&type=1",
headers: {'userpasscode': '$hashedid'}
```

on success response
```js
success:function(data) {
              if (data.status==1 ){
              var qrcodeloginurl = data.msg;
<!-- qrcodeloginurl looks like this: /api/login/qrcodedoLogin?key=xxxx&type=scan&login=xxxx&sign=xxxx              -->

                  //hit 2nd api using the url recieved in msg key
                  qrcodedoLogin(qrcodeloginurl);
              }else if(data.status==2){
              //qr expired
                  console.log("Error",data.msg)
              }

```
### 2. qrcodedoLogin(qrcodeloginurl), qrcodeloginurl: came from the 1st api response
```sh
method: "POST"
url: qrcodeloginurl,
```

on success response
```js
success:function(data) {
              if (data.status==1 ){
              var msg = data.msg;
                 
                  console.log(msg);
                  //QR code login successfull
              }else if(data.status==2){
              //qr expired
                  console.log("Error",data.msg)
              }

```

- QR test page in incognito mode
![QR test](picofWS/1.png?raw=true "QR test page")
- When QR scanned
![When QR scanned successfully](picofWS/2.png?raw=true "When QR scanned successfully")
- Things happening in the console
![WS console](picofWS/3.png?raw=true "console")