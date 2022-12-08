<?php


add_action( 'init', 'viva_script_enqueuer' );

function viva_script_enqueuer() {
    wp_register_script( "viva_script", get_template_directory_uri().'/viva/viva.js', array('jquery') );
    wp_localize_script( 'viva_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script( 'viva_script' );

}

add_action( 'enqueue_scripts', 'viva_style_enqueuer' );

function viva_style_enqueuer() {

    wp_enqueue_style('viva-admin', get_stylesheet_directory_uri().'/viva/viva.css' );

}

add_action("wp_ajax_viva_token", "viva_token");
add_action("wp_ajax_nopriv_viva_token", "viva_token");


function viva_token(){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://demo-accounts.vivapayments.com/connect/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic NGtxbG84cjh1czZmdXJjbjN1MnRqNnQ5Nm5xM3UzNGpwZHU1enpoNDRvMmM3LmFwcHMudml2YXBheW1lbnRzLmNvbTpWVk40ZDRrR3M5eHoxNW5ONGE2UjVlY0RiMUhxZks='
        ),
    ));

// 4kqlo8r8us6furcn3u2tj6t96nq3u34jpdu5zzh44o2c7.apps.vivapayments.com:VVN4d4kGs9xz15nN4a6R5ecDb1HqfK

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
    die();
}


add_action("wp_ajax_viva_curl", "viva_curl");
add_action("wp_ajax_nopriv_viva_curl", "viva_curl");


function viva_curl(){

    $data = $_POST['stuff'];
    //var_dump($data);
    $tokenx = $data['token'];
    $price = intval($data['amount']);
    //echo $price;
    $headers[] = "Authorization: Bearer ".$tokenx;
    $headers[] = "Content-Type: application/json";
    //var_dump($headers);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://demo-api.vivapayments.com/checkout/v2/orders',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
 "amount": 2000,
 "customerTrns": "Transaction for mkmendis test",
 "customer": {"email": "'.$data['email'].'","fullName": "'.$data['name'].'","countryCode": "GR","requestLang": "el-GR"},
 "paymentTimeout": 1800,
 "preauth": true,
 "allowRecurring": false,
 "maxInstallments": 0,
 "paymentNotification": false,
 "tipAmount": 1,
 "disableExactAmount": false,
 "disableCash": true,
 "disableWallet": false,
 "sourceCode": "4812",
 "merchantTrns": "test tolis",
 "tags": ["mkmendis"]
}',
        CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;

    die();

}

function viva_form(){
    $nonce = wp_create_nonce( 'viva_nonce' ); // create nonce
    ?>
    <form action="" method="get" class="form-example">
        <div class="form-example">
            <label for="name">Enter your name: </label>
            <input type="text" name="name" id="name" value=""  required>
        </div>
        <div class="form-example">
            <label for="email">Enter your email: </label>
            <input type="email" name="email" id="email" value=""  required>
        </div>

        <div class="form-example" style="display:none;">
            <label for="nonce">nonce</label>
            <input type="text" name="nonce" id="nonce"  value="<?php echo $nonce; ?>" disabled >
        </div>
        <div class="form-example">
            <input type="submit" value="submit" id="viva_submit">
        </div>
    </form>
    <div id="result"></div>
<?php }