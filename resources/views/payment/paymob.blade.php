<!-- <button onclick=firstStep()>paymob online card {{$orderId}}<button> -->
<script>
const API = "<?php echo env('PAYMOB_KEY') ?>";        // your api here
const iframeId="<?php echo $iframe_id; ?>";


async function firstStep () {
    let data = {
        "api_key": API
    }

    let request = await fetch('https://pakistan.paymob.com/api/auth/tokens' , {
        method : 'post',
        headers : {'Content-Type' : 'application/json'} ,
        body : JSON.stringify(data)
    })

    let response = await request.json()

    let token = response.token
    secondStep(token)
}

async function secondStep (token) {
    let data = {
        "auth_token":  token,
        "delivery_needed": "false",
        "amount_cents": "<?php echo $amount * 100;?>",
        "currency": "PKR",
        "merchant_order_id":"<?php echo $orderId; ?>",
        "items": [],
    }

    let request = await fetch('https://pakistan.paymob.com/api/ecommerce/orders' , {
        method : 'post',
        headers : {'Content-Type' : 'application/json'} ,
        body : JSON.stringify(data)
    })

    let response = await request.json()

    let id = response.id

    thirdStep(token , id)
}

async function thirdStep (token , id) {

    let data = {
        "auth_token": token,
        "amount_cents": "<?php echo $amount * 100;?>", 
        "expiration": 3600, 
        "order_id": id,
        "billing_data": {
            "apartment": "NA", 
            "email": "<?php echo $email; ?>", 
            "floor": "NA", 
            "first_name": "<?php echo $name; ?>", 
            "street": "NA", 
            "building": "NA", 
            "phone_number": "<?php echo $phone; ?>", 
            "shipping_method": "NA", 
            "postal_code": "NA", 
            "city": "NA", 
            "country": "NA", 
            "last_name": "<?php echo $name; ?>", 
            "state": "NA"
        }, 
        "currency": "PKR", 
        "integration_id": iframeId
    }

    let request = await fetch('https://pakistan.paymob.com/api/acceptance/payment_keys' , {
        method : 'post',
        headers : {'Content-Type' : 'application/json'} ,
        body : JSON.stringify(data)
    })

    let response = await request.json()

    let TheToken = response.token

    cardPayment(TheToken)
}


async function cardPayment (token) {
    if(iframeId==66108 || iframeId==61876){
    let iframURL = `https://pakistan.paymob.com/api/acceptance/iframes/84868?payment_token=${token}`
        location.href = iframURL
    }
    else if((iframeId==66112 || iframeId==62039)){
        let iframURL = `https://pakistan.paymob.com/iframe/${token}`

        location.href = iframURL

    }
    else{
        let iframURL = `https://pakistan.paymob.com/iframe/${token}`
        location.href = iframURL
        
    } 

}

firstStep()


</script>   