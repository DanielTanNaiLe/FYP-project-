<?php
require '../admin_panel/config/dbconnect.php';
require 'mailer.php';

session_start();

if (!isset($_SESSION['checkout_details'])) {
    header("Location: checkout.php");
    exit();
}

function generateOtp() {
    return rand(100000, 999999); // Generate a 6-digit OTP
}

function sendOtp($email, $otp) {
    global $mail;
    
    try {
        $mail->setFrom('liangyuel44@gmail.com', 'LDK sport');
        $mail->addAddress($email);
        $mail->Subject = "Your OTP Code";
        $mail->Body = "Your OTP code is: $otp";
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cardNumber'])) {
    $otp = generateOtp();
    $_SESSION['otp'] = $otp;

    $email = $_SESSION['checkout_details']['email'];
    if (sendOtp($email, $otp)) {
        header("Location: otp_verification.php");
        exit();
    } else {
        $error = "Failed to send OTP. Please try again.";
    }
}

$checkout_details = $_SESSION['checkout_details'];
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title> Credit Card Form for Payment Gateway </title>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
<link rel="stylesheet" href="./mastercard.css">
<link rel="stylesheet" href="./general.css">
</head>
<body>
<!-- partial:index.partial.html -->
<div class="wrapper" id="app">
    <div class="card-form">
      <div class="card-list">
        <div class="card-item" v-bind:class="{ '-active' : isCardFlipped }">
          <div class="card-item__side -front">
            <div class="card-item__focus" v-bind:class="{'-active' : focusElementStyle }" v-bind:style="focusElementStyle" ref="focusElement"></div>
            <div class="card-item__cover">
              <img
              v-bind:src="'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/' + currentCardBackground + '.jpeg'" class="card-item__bg">
            </div>
            
            <div class="card-item__wrapper">
              <div class="card-item__top">
                <img src="https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/chip.png" class="card-item__chip">
                <div class="card-item__type">
                  <transition name="slide-fade-up">
                    <img v-bind:src="'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/' + getCardType + '.png'" v-if="getCardType" v-bind:key="getCardType" alt="" class="card-item__typeImg">
                  </transition>
                </div>
              </div>
              <label for="cardNumber" class="card-item__number" ref="cardNumber">
                <template v-if="getCardType === 'amex'">
                 <span v-for="(n, $index) in amexCardMask" :key="$index">
                  <transition name="slide-fade-up">
                    <div
                      class="card-item__numberItem"
                      v-if="$index > 4 && $index < 14 && cardNumber.length > $index && n.trim() !== ''"
                    >*</div>
                    <div class="card-item__numberItem"
                      :class="{ '-active' : n.trim() === '' }"
                      :key="$index" v-else-if="cardNumber.length > $index">
                      {{cardNumber[$index]}}
                    </div>
                    <div
                      class="card-item__numberItem"
                      :class="{ '-active' : n.trim() === '' }"
                      v-else
                      :key="$index + 1"
                    >{{n}}</div>
                  </transition>
                </span>
                </template>

                <template v-else>
                  <span v-for="(n, $index) in otherCardMask" :key="$index">
                    <transition name="slide-fade-up">
                      <div
                        class="card-item__numberItem"
                        v-if="$index > 4 && $index < 15 && cardNumber.length > $index && n.trim() !== ''"
                      >*</div>
                      <div class="card-item__numberItem"
                        :class="{ '-active' : n.trim() === '' }"
                        :key="$index" v-else-if="cardNumber.length > $index">
                        {{cardNumber[$index]}}
                      </div>
                      <div
                        class="card-item__numberItem"
                        :class="{ '-active' : n.trim() === '' }"
                        v-else
                        :key="$index + 1"
                      >{{n}}</div>
                    </transition>
                  </span>
                </template>
              </label>
              <div class="card-item__content">
                <label for="cardName" class="card-item__info" ref="cardName">
                  <div class="card-item__holder">Card Holder</div>
                  <transition name="slide-fade-up">
                    <div class="card-item__name" v-if="cardName.length" key="1">
                      <transition-group name="slide-fade-right">
                        <span class="card-item__nameItem" v-for="(n, $index) in cardName.replace(/\s\s+/g, ' ')" v-if="$index === $index" v-bind:key="$index + 1">{{n}}</span>
                      </transition-group>
                    </div>
                    <div class="card-item__name" v-else key="2">Full Name</div>
                  </transition>
                </label>
                <div class="card-item__date" ref="cardDate">
                  <label for="cardMonth" class="card-item__dateTitle">Expires</label>
                  <label for="cardMonth" class="card-item__dateItem">
                    <transition name="slide-fade-up">
                      <span v-if="cardMonth" v-bind:key="cardMonth">{{cardMonth}}</span>
                      <span v-else key="2">MM</span>
                    </transition>
                  </label>
                  /
                  <label for="cardYear" class="card-item__dateItem">
                    <transition name="slide-fade-up">
                      <span v-if="cardYear" v-bind:key="cardYear">{{String(cardYear).slice(2,4)}}</span>
                      <span v-else key="2">YY</span>
                    </transition>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="card-item__side -back">
            <div class="card-item__cover">
              <img
              v-bind:src="'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/' + currentCardBackground + '.jpeg'" class="card-item__bg">
            </div>
            <div class="card-item__band"></div>
            <div class="card-item__cvv">
                <div class="card-item__cvvTitle">CVV</div>
                <div class="card-item__cvvBand">
                  <span v-for="(n, $index) in cardCvv" :key="$index">
                    *
                  </span>

              </div>
                <div class="card-item__type">
                    <img v-bind:src="'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/' + getCardType + '.png'" v-if="getCardType" class="card-item__typeImg">
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-form__inner">
      <form action="mastercard.php" method="post">
          <div class="card-input">
            <label for="cardNumber" class="card-input__label">Card Number</label>
            <input type="text" id="cardNumber" name="cardNumber" class="card-input__input" v-mask="generateCardNumberMask" v-model="cardNumber" v-on:focus="focusInput" v-on:blur="blurInput" data-ref="cardNumber" autocomplete="off" required>
          </div>
          <div class="card-input">
            <label for="cardName" class="card-input__label">Card Holder</label>
            <input type="text" id="cardName" name="cardName" class="card-input__input" v-model="cardName" v-on:focus="focusInput" v-on:blur="blurInput" data-ref="cardName" autocomplete="off" required>
          </div>
        <div class="card-form__row">
          <div class="card-form__col">
            <div class="card-form__group">
              <label for="cardMonth" class="card-input__label">Expiration Date</label>
              <select class="card-input__input -select" id="cardMonth" v-model="cardMonth" v-on:focus="focusInput" v-on:blur="blurInput" data-ref="cardDate">
                <option value="" disabled selected>Month</option>
                <option v-bind:value="n < 10 ? '0' + n : n" v-for="n in 12" v-bind:disabled="n < minCardMonth" v-bind:key="n">
                    {{n < 10 ? '0' + n : n}}
                </option>
              </select>
              <select class="card-input__input -select" id="cardYear" v-model="cardYear" v-on:focus="focusInput" v-on:blur="blurInput" data-ref="cardDate">
                <option value="" disabled selected>Year</option>
                <option v-bind:value="$index + minCardYear" v-for="(n, $index) in 12" v-bind:key="n">
                    {{$index + minCardYear}}
                </option>
              </select>
            </div>
          </div>
          <div class="card-form__col -cvv">
            <div class="card-input">
              <label for="cardCvv" class="card-input__label">CVV</label>
              <input type="text" class="card-input__input" id="cardCvv" v-mask="'####'" maxlength="4" v-model="cardCvv" v-on:focus="flipCard(true)" v-on:blur="flipCard(false)" autocomplete="off">
            </div>
          </div>
        </div>
        <button type="submit" class="card-form__button" name="order">Proceed with OTP Verification</button>
</from>
      </div>
    </div>
    
    
</div>

<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js'></script>
<script src='https://unpkg.com/vue-the-mask@0.11.1/dist/vue-the-mask.js'></script>
<script src="./payment_method.js"></script>

</body>
</html>
