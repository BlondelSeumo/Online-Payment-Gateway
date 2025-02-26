'use strict';

function restrictNumberToPrefdecimalOnInput(e)
{
    let num = $.trim(e.value);
    if (num.length > 0 && !isNaN(num)) {
        e.value = digitCheck(num, 8, tokenDecimals);
        return e.value;
    }
}

if ($('.content').find('#crypto-send-create').length) {
    var merchantAddress;
    var userAddress;
    var amount;
    var userId;
    var userAddressErrorFlag = false;
    var amountErrorFlag = false;

    $("#user_id").select2({});

    function checkSubmitBtn() {
        if (!userAddressErrorFlag && !amountErrorFlag) {
            $("#admin-crypto-send-submit-btn").attr("disabled", false);
        } else {
            $("#admin-crypto-send-submit-btn").attr("disabled", true);
        }
    }

    //Check Minimum Amount
    function checkMinimumAmount(message) {
        $(".amount-validation-error").text(message);
        userAddressErrorFlag = true;
        amountErrorFlag = true;
        checkSubmitBtn();
    }

    //Check Amount Validity
    function checkMerchantAmountValidity(
        amount,
        merchantAddress,
        tokenAddress,
        userAddress,
        network
    ) {
        if (amount < tatumIoMinLimit) {
            checkMinimumAmount(
                `${minAmount.replace(
                    ":x",
                    tatumIoMinLimit + " " + network
                )}`
            );
        } else {
            $(".amount-validation-error").text("");
            userAddressErrorFlag = false;
            amountErrorFlag = false;
            checkSubmitBtn();

            $.ajax({
                method: "GET",
                url: validateBalanceUrl,
                dataType: "json",
                data: {
                    network: network,
                    amount: amount,
                    merchantAddress: merchantAddress,
                    tokenAddress: tokenAddress,
                    tokenDecimals: tokenDecimals,
                    userAddress: userAddress
                },
                beforeSend: function () {
                    swal(pleaseWait, loading, {
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        buttons: false,
                    });
                },
            })
                .done(function (res) {
                    swal.close();
                    if (res.status == 401) {
                        $(".amount-validation-error").text(res.message);
                        userAddressErrorFlag = true;
                        amountErrorFlag = true;
                        checkSubmitBtn();
                    }
                })
                .fail(function (error) {
                    alert(JSON.parse(error.responseText).exception);
                });
        }
    }

    $(window).on("load", function (e) {
        let userId = window.localStorage.getItem("user_id");
        if (userId) {
            getMerchantAndUserNetworkAddressWithMerchantBalance(userId);
        }
    });

    //Get merchant network address, merchant network balance and user network address
    $(document).on("change", "#user_id", function (e) {
        //Remove merchant address, merchant balance and amount div on change of network
        $(
            "#merchant-address-div, #merchant-balance-div, #user-address-div, #amount-div, #submit-anchor-div, #merchant-token-balance-div, #merchant-token-address-div"
        ).remove();

        userId = $(this).val();
        let userName = $("#user_id option:selected").text();
        $(".user-full-name").text(userName);

        if (userId) {
            getMerchantAndUserNetworkAddressWithMerchantBalance(userId);
        }
    });

    //Get merchant network address, merchant network balance and user network address
    function getMerchantAndUserNetworkAddressWithMerchantBalance(userId) {
        $.ajax({
            url: addressBalanceUrl,
            type: "get",
            dataType: "json",
            data: {
                network: network,
                tokenAddress: tokenAddress,
                tokenDecimals: tokenDecimals,
                user_id: userId,
            },
            beforeSend: function () {
                swal(pleaseWait, loading, {
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: false,
                });
            },
        })
            .done(function (res) {
                if (res.status == 401) {
                    $(".amount-validation-error").text(res.message);
                    userAddressErrorFlag = true;
                    amountErrorFlag = true;
                    checkSubmitBtn();

                    swal({
                        title: errorText,
                        text: res.message,
                        icon: "error",
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                    });
                } else {
                    //merchant-address-div
                    $("#user-div")
                        .after(`<div class="form-group row" id="merchant-address-div">
                      <label class="col-sm-3 control-label f-14 fw-bold text-end" for="merchant-address">${merchantCryptoAddress}</label>
                      <div class="col-sm-6">
                          <input type="text" class="form-control f-14" name="merchantAddress" id="merchant-address" value="${res.merchantAddress}"/>
                      </div>
                  </div>`);

                    //merchant-balance-div
                    $("#merchant-address-div")
                        .after(`<div class="form-group row" id="merchant-balance-div">
                      <label class="col-sm-3 control-label f-14 fw-bold text-end" for="merchant-balance">${merchantCryptoBalance}</label>
                      <div class="col-sm-6">
                          <input type="text" class="form-control f-14" name="merchantBalance" id="merchant-balance" value="${res.merchantAddressBalance}"/>
                          <span class="crypto-amount-validation-error"></span>
                      </div>
                  </div>`);

                  $("#merchant-balance-div")
                        .after(`<div class="form-group row" id="merchant-token-address-div">
                      <label class="col-sm-3 control-label f-14 fw-bold text-end" for="merchant-token-address">${merchantTokenAddress}</label>
                      <div class="col-sm-6">
                          <input type="text" class="form-control f-14" name="merchantTokenAddress" id="merchant-token-address" value="${res.tokenAddress}"/>
                      </div>
                    </div>`);

                    $("#merchant-token-address-div")
                        .after(`<div class="form-group row" id="merchant-token-balance-div">
                      <label class="col-sm-3 control-label f-14 fw-bold text-end" for="merchant-token-balance">${merchantTokenBalance}</label>
                      <div class="col-sm-6">
                          <input type="text" class="form-control f-14" name="merchantTokenBalance" id="merchant-token-balance" value="${res.tokenBalace}"/>
                      </div>
                    </div>`);

                    //user-address-div
                    $("#merchant-token-balance-div")
                        .after(`<div class="form-group row" id="user-address-div">
                      <label class="col-sm-3 control-label f-14 fw-bold text-end" for="user-address">${userCryptoAddress}</label>
                      <div class="col-sm-6">
                          <input type="text" class="form-control f-14" name="userAddress" id="user-address" value="${res.userAddress}"/>
                      </div>
                    </div>`);

                    var previousCrytoSentUrl = window.localStorage.getItem(
                        "previousCrytoSentUrl"
                    );
                    var cryptoSendAmount =
                        window.localStorage.getItem("crypto-sent-amount");
                    var userId = window.localStorage.getItem("user_id");

                    if (
                        confirmationCryptoSentUrl == previousCrytoSentUrl &&
                        cryptoSendAmount != null
                    ) {

                        //amount-div
                        $("#user-address-div")
                            .after(`<div class="form-group row" id="amount-div">
                          <label class="col-sm-3 control-label f-14 fw-bold text-end require" for="Amount">${cryptoSentAmount}</label>
                          <div class="col-sm-6" id="amount-input-div">
                              <input type="text" class="form-control f-14 amount" name="amount" placeholder="${tatumIoMinLimit}" id="amount" value="${cryptoSendAmount}" onkeypress="return isNumberOrDecimalPointKey(this, event);" oninput="restrictNumberToPrefdecimalOnInput(this)" required data-value-missing="${requiredField}"/>
                              <span class="amount-validation-error"></span>
                          </div>
                      </div>`);

                        // Old User
                        $(
                            "select#user_id option[value=" +
                                window.localStorage.getItem("user_id") +
                                "]"
                        ).prop("selected", true);
                        $("#select2-user_id-container")
                            .attr(
                                "title",
                                $("select#user_id option:selected").text()
                            )
                            .text($("select#user_id option:selected").text());

                        //Get network fees
                        checkMerchantAmountValidity($('.amount').val().trim(), $("#merchant-address").val().trim(), $("#merchant-token-address").val().trim(), $("#user-address").val().trim(), network, previousPriority)

                        $("#admin-crypto-send-submit-btn").attr(
                            "disabled",
                            false
                        );
                        $(".fa-spin").hide();
                        $("#admin-crypto-send-submit-btn-text").html(
                            `Next&nbsp;<i class="fa fa-angle-right"></i>`
                        );
                        window.localStorage.removeItem("user_id");
                        window.localStorage.removeItem("crypto-sent-amount");
                        window.localStorage.removeItem("previousCrytoSentUrl");
                    } else {

                        //amount-div
                        $("#user-address-div")
                            .after(`<div class="form-group row" id="amount-div">
                          <label class="col-sm-3 control-label f-14 fw-bold text-end require" for="Amount">${cryptoSentAmount}</label>
                          <div class="col-sm-6" id="amount-input-div">
                              <input type="text" class="form-control f-14 amount" name="amount" placeholder="${tatumIoMinLimit}" id="amount" onkeypress="return isNumberOrDecimalPointKey(this, event);" oninput="restrictNumberToPrefdecimalOnInput(this)" required data-value-missing="${requiredField}"/>
                              <span class="amount-validation-error"></span>
                          </div>
                      </div>`);
                    }

                    $(".amount-validation-error")
                        .after(`<div class="clearfix"></div>
                      <small class="form-text text-muted f-12 amount-hint"><b>*${cryptoTransactionText}</b></small><br/>
                      <small class="form-text text-muted f-12"><b>*${minWithdrawan
                          .replace(":x", tatumIoMinLimit + ' ' +cryptoToken)}</b></small><br/>
                  `);

                  $(".crypto-amount-validation-error")
                        .after(`<div class="clearfix"></div>
                        <small class="form-text text-muted f-12"><b>*${minNetworkFee
                          .replace(":x", 50 + ' ' + network)}</b></small><br/>
                        <small class="form-text text-muted f-12"><b>*${networkFeeText
                          .replace(":x", network)}</b></small><br/>
                  `);

                  
                    $("#amount-div")
                        .after(`<div class="form-group row" id="submit-anchor-div">
                      <label class="col-sm-3"></label>
                      <div class="col-sm-6">
                          <a href="${backButtonUrl}" class="btn btn-theme-danger pull-left"><span><i class="fa fa-angle-left"></i>&nbsp;${backButton}</span></a>
                          <button type="submit" class="btn btn-theme pull-right" id="admin-crypto-send-submit-btn">
                              <i class="fa fa-spinner fa-spin d-none"></i>
                              <span id="admin-crypto-send-submit-btn-text">${nextButton}&nbsp;<i class="fa fa-angle-right"></i></span>
                          </button>
                      </div>
                  </div>`);

                    $(
                        "#merchant-address, #merchant-balance, #user-address, #merchant-token-address, #merchant-token-balance", 
                    ).attr("readonly", true);

                    $(".amount-validation-error").text("");
                    userAddressErrorFlag = false;
                    amountErrorFlag = false;
                    checkSubmitBtn();


                    $("#amount").focus();

                    swal.close();
                }
            })
            .fail(function (error) {
                swal({
                    title: errorText,
                    text: JSON.parse(error.responseText).exception,
                    icon: "error",
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                });
            });
    }


    // Validate Amount
    $(document).on(
        "keyup",
        ".amount",
        $.debounce(900, function (e) {
            network = $("#network").val().trim();
            merchantAddress = $("#merchant-address").val().trim();
            tokenAddress = $("#merchant-token-address").val().trim();
            userAddress = $("#user-address").val().trim();
            amount = $(this).val().trim();

            if (amount.length > 0 && !isNaN(amount)) {
                checkMerchantAmountValidity(
                    amount,
                    merchantAddress,
                    tokenAddress,
                    userAddress,
                    network
                );
            } else {
                $(".amount-validation-error").text("");
                userAddressErrorFlag = false;
                amountErrorFlag = false;
                checkSubmitBtn();
            }
        })
    );


    $(document).on("submit", "#admin-crypto-send-form", function () {
        //Set amount to localstorage for showing on create page on going back from confirm page
        window.localStorage.setItem("user_id", $("#user_id").val());
        window.localStorage.setItem(
            "crypto-sent-amount",
            $(".amount").val().trim()
        );

        $("#admin-crypto-send-submit-btn").attr("disabled", true);
        $(".fa-spinner").removeClass("d-none");
        $("#admin-crypto-send-submit-btn-text").text(sending);

        setTimeout(function () {
            $(".fa-spinner").addClass("d-none");
            $("#admin-crypto-send-submit-btn").attr("disabled", false);
            $("#admin-crypto-send-submit-btn-text").text(send);
        }, 10000);
    });
}


if ($('.content').find('#crypto-send-confirm').length) {

    function cryptoSendConfirmBack()
    {
        window.localStorage.setItem("previousCrytoSentUrl", document.URL);
        window.location.replace(cryptoSendBackConfirmUrl);
    }

    //Only go back by back button, if submit button is not clicked
    $(document).on('click', '.admin-user-crypto-send-confirm-back-btn', function (e) {
        e.preventDefault();
        cryptoSendConfirmBack();
    });

    $(document).on('submit', '#admin-user-crypto-send-confirm', function() {
        //Set amount to localstorage for showing on create page on going back from confirm page
        window.localStorage.removeItem('crypto-sent-amount');
        window.localStorage.removeItem("previousCrytoSentUrl");
        window.localStorage.removeItem('user_id');

        $("#admin-user-crypto-send-confirm-btn").attr("disabled", true);
        $(".fa-spinner").removeClass('d-none');
        $("#admin-user-crypto-send-confirm-btn-text").text(confirming);

        $('.admin-user-crypto-send-confirm-back-btn').attr("disabled", true).on('click', function (e) {
            e.preventDefault();
        });

        //Make back anchor prevent click
        $('.admin-user-crypto-send-confirm-back-link').on('click', function (e) {
            e.preventDefault();
        });

        setTimeout(function(){
            $(".fa-spinner").addClass('d-none');
            $("#admin-user-crypto-send-confirm-btn").attr("disabled", false);
            $("#admin-user-crypto-send-confirm-btn-text").text(confirm);
        }, 10000);
    });
}
