<?php
header("Access-Control-Allow-Origin: *");
session_start();// À placer obligatoirement avant tout code HTML.
set_time_limit(0);
// var_dump($_GET);
require 'connexion.php';

if($_GET['p'] == 'tg' OR $_GET['p'] == 'sn' OR $_GET['p'] == 'ci' OR $_GET['p'] == 'ml' OR $_GET['p'] == 'bf' OR $_GET['p'] == 'bn' ) {

  $devise = "CFA";

} else if ($_GET['p'] == 'cm') {
  $devise = "XAF";
} else if($_GET['p'] == 'rdc') {
  $devise = "CDF";
}

$opt = explode('-',$_GET['ref']);
if (isset($opt[0]) AND $opt[0]=='AFPRCH') {
  $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$_GET['ref']."'";
  $trsx = $bdd -> query($re);
  $trsxWha = $trsx -> fetch();
  $montant = $trsxWha['montant_rechargement'];
  $optwal = "&wall=1";
} else {
  $re = "SELECT * FROM transaction WHERE reference_transaction = '".$_GET['ref']."'";
  $trsx = $bdd -> query($re);
  $trsxWha = $trsx -> fetch();
  $montant = $trsxWha['montant_transaction'];
  $optwal = "";
}


?>
<!DOCTYPE html>
<html>
<head>

<script charset="utf-8" src="https://www.cinetpay.com/cdn/seamless_sdk/latest/cinetpay.prod.min.js" type="text/javascript"></script>
<style>
  body {
    min-height: 100vh;
    display: grid;
    place-items: center;
  }
  svg.tea {
    --secondary: #33406f;
  }
  svg.tea #teabag {
    transform-origin: top center;
    transform: rotate(3deg);
    animation: swing 2s infinite;
  }
  svg.tea #steamL {
    stroke-dasharray: 13;
    stroke-dashoffset: 13;
    animation: steamLarge 2s infinite;
  }
  svg.tea #steamR {
    stroke-dasharray: 9;
    stroke-dashoffset: 9;
    animation: steamSmall 2s infinite;
  }
  @-moz-keyframes swing {
    50% {
      transform: rotate(-3deg);
    }
  }
  @-webkit-keyframes swing {
    50% {
      transform: rotate(-3deg);
    }
  }
  @-o-keyframes swing {
    50% {
      transform: rotate(-3deg);
    }
  }
  @keyframes swing {
    50% {
      transform: rotate(-3deg);
    }
  }
  @-moz-keyframes steamLarge {
    0% {
      stroke-dashoffset: 13;
      opacity: 0.6;
    }
    100% {
      stroke-dashoffset: 39;
      opacity: 0;
    }
  }
  @-webkit-keyframes steamLarge {
    0% {
      stroke-dashoffset: 13;
      opacity: 0.6;
    }
    100% {
      stroke-dashoffset: 39;
      opacity: 0;
    }
  }
  @-o-keyframes steamLarge {
    0% {
      stroke-dashoffset: 13;
      opacity: 0.6;
    }
    100% {
      stroke-dashoffset: 39;
      opacity: 0;
    }
  }
  @keyframes steamLarge {
    0% {
      stroke-dashoffset: 13;
      opacity: 0.6;
    }
    100% {
      stroke-dashoffset: 39;
      opacity: 0;
    }
  }
  @-moz-keyframes steamSmall {
    10% {
      stroke-dashoffset: 9;
      opacity: 0.6;
    }
    80% {
      stroke-dashoffset: 27;
      opacity: 0;
    }
    100% {
      stroke-dashoffset: 27;
      opacity: 0;
    }
  }
  @-webkit-keyframes steamSmall {
    10% {
      stroke-dashoffset: 9;
      opacity: 0.6;
    }
    80% {
      stroke-dashoffset: 27;
      opacity: 0;
    }
    100% {
      stroke-dashoffset: 27;
      opacity: 0;
    }
  }
  @-o-keyframes steamSmall {
    10% {
      stroke-dashoffset: 9;
      opacity: 0.6;
    }
    80% {
      stroke-dashoffset: 27;
      opacity: 0;
    }
    100% {
      stroke-dashoffset: 27;
      opacity: 0;
    }
  }
  @keyframes steamSmall {
    10% {
      stroke-dashoffset: 9;
      opacity: 0.6;
    }
    80% {
      stroke-dashoffset: 27;
      opacity: 0;
    }
    100% {
      stroke-dashoffset: 27;
      opacity: 0;
    }
  }


</style>
</head>

<body>
<svg class="tea" width="37" height="48" viewbox="0 0 37 48" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M27.0819 17H3.02508C1.91076 17 1.01376 17.9059 1.0485 19.0197C1.15761 22.5177 1.49703 29.7374 2.5 34C4.07125 40.6778 7.18553 44.8868 8.44856 46.3845C8.79051 46.79 9.29799 47 9.82843 47H20.0218C20.639 47 21.2193 46.7159 21.5659 46.2052C22.6765 44.5687 25.2312 40.4282 27.5 34C28.9757 29.8188 29.084 22.4043 29.0441 18.9156C29.0319 17.8436 28.1539 17 27.0819 17Z" stroke="var(--secondary)" stroke-width="2"></path>
  <path d="M29 23.5C29 23.5 34.5 20.5 35.5 25.4999C36.0986 28.4926 34.2033 31.5383 32 32.8713C29.4555 34.4108 28 34 28 34" stroke="var(--secondary)" stroke-width="2"></path>
  <path id="teabag" fill="var(--secondary)" fill-rule="evenodd" clip-rule="evenodd" d="M16 25V17H14V25H12C10.3431 25 9 26.3431 9 28V34C9 35.6569 10.3431 37 12 37H18C19.6569 37 21 35.6569 21 34V28C21 26.3431 19.6569 25 18 25H16ZM11 28C11 27.4477 11.4477 27 12 27H18C18.5523 27 19 27.4477 19 28V34C19 34.5523 18.5523 35 18 35H12C11.4477 35 11 34.5523 11 34V28Z"></path>
  <path id="steamL" d="M17 1C17 1 17 4.5 14 6.5C11 8.5 11 12 11 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke="var(--secondary)"></path>
  <path id="steamR" d="M21 6C21 6 21 8.22727 19 9.5C17 10.7727 17 13 17 13" stroke="var(--secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>


</body>
<script>
var price = "<?php echo $montant; ?>";
var devise = "<?php echo $devise; ?>";
var refer = "<?php echo $_GET['ref']; ?>";
var name = "<?php echo $_GET['desc']; ?>";
var optwal = "<?php echo $optwal; ?>";



  CinetPay.setConfig({
      apikey: '16711883825fc40fa94905c8.76481156',
      site_id: 103571,
      // notify_url: 'https://bb62ec4708f6.ngrok.io/afreekaplay/gest/kcabllac.php'
      notify_url: 'https://afreekaplay.com/gest/kcabllac2.php'
  });

                      CinetPay.on('error', function (e) {

                      });
                      //ajax
                      CinetPay.on('ajaxStart', function () {

                      });
                      CinetPay.on('ajaxStop', function () {

                      });
                      //Lorsque la signature est généré
                      CinetPay.on('signatureCreated', function (token) {

                      });
                      CinetPay.on('paymentPending', function (e) {



                      });
                      CinetPay.on('paymentSuccessfull', function (paymentInfo) {
                          // var error_div = document.getElementById('error_info');
                          // var sucess_div = document.getElementById('success_info');
                          console.log("paymentSuccessfull");
                          console.table(paymentInfo);
                          // if (typeof paymentInfo.lastTime != 'undefined') {
                              if (paymentInfo.cpm_result == '00') {
                                  // error_div.innerHTML = '';
                                  // sucess_div.innerHTML = 'Votre paiement a été validé avec succès : <br> Montant :' + paymentInfo.cpm_amount + '<br>';
                                  // trans_id.value = Math.floor((Math.random() * 10000000) + 10000);
                                  // this.http.get<[]>(").subscribe(response => {
                                  //
                                  //     // console.log(response['reference']);
                                  //     // this.refer = response['reference'];
                                  //     // this.localStorageService.setLocalStorage('refachat', response['reference']);
                                  //     });
                                  $.ajax({
                                    url:'https://afreekaplay.com/gest/kcabllac2.php',
                                    type:"POST",
                                    data: "cpm_trans_id="+paymentInfo.cpm_trans_id+""+optwal,
                                    dataType : 'text',
                                    success:function(reponse){
                                      // alert("paie ok "+paymentInfo.cpm_trans_id);
                                      // document.location.href="https://afreekaplay.com/callback?ref="+paymentInfo.cpm_trans_id;
                                      // document.location.href="http://localhost:4203/callback?ref="+paymentInfo.cpm_trans_id;

                                    },

                                    error:function(xhr,status,error){
                                      // alert(xhr.responseText);
                                      /*$("#toast-insc").text("Erreur! Vous n'êtes pas connecté a internet.");
                                      $("#btn-toast-insc").trigger('click');*/

                                    }

                                  });


                              } else {
                                  // error_div.innerHTML = 'Une erreur est survenue :' + paymentInfo.cpm_error_message;
                                  // sucess_div.innerHTML = '';

                                  alert("erreur apres paiement ");

                              }
                          // }
                      });
                      CinetPay.setSignatureData({
                          amount: parseInt(price),
                          trans_id: refer,
                          currency: devise,
                          designation: name,
                          // cel_phone_num : this.numero,
                          // cpm_phone_prefixe : this.temoin1,
                          custom: ""
                      });
                      CinetPay.getSignature();
</script>
</html>
