<div class="control-group">
    <label class="control-label" for="merchant_id">Merchant Id:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][merchant_id]" id="merchant_id"
               value="{$processor_params.merchant_id}" class="input-text"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="merchant_scheme_code">Merchant Scheme Code:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][merchant_scheme_code]" id="merchant_scheme_code"
               value="{$processor_params.merchant_scheme_code}" class="input-text"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="salt">Salt:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][salt]" id="salt"
               value="{$processor_params.salt}" class="input-text"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="test_mode">Payment Type:</label>
    <div class="controls">
        <select name="payment_data[processor_params][test_mode]" id="test_mode">
            <option value="TEST" {if $processor_params.test_mode == "TEST"}selected="selected"{/if}>TEST</option>
            <option value="LIVE" {if $processor_params.test_mode == "LIVE"}selected="selected"{/if}>LIVE</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="currency">Currency:</label>
    <div class="controls">
        <select name="payment_data[processor_params][currency]" id="currency">
            <option value="INR" {if $processor_params.currency == "INR"}selected="selected"{/if}>INR</option>
            <option value="USD" {if $processor_params.currency == "USD"}selected="selected"{/if}>USD</option>
            <option value="YEN" {if $processor_params.currency == "YEN"}selected="selected"{/if}>YEN</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="primaryColor">Primary Color:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][primaryColor]" id="primaryColor"
               value="{$processor_params.primaryColor}" class="input-text"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="secondaryColor">Secondary Color:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][secondaryColor]" id="secondaryColor"
               value="{$processor_params.secondaryColor}" class="input-text"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="buttonColor1">Button Color 1:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][buttonColor1]" id="buttonColor1"
               value="{$processor_params.buttonColor1}" class="input-text"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="buttonColor2">Button Color 2:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][buttonColor2]" id="buttonColor2"
               value="{$processor_params.buttonColor2}" class="input-text"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="logoURL">Logo URL:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][logoURL]" id="logoURL"
               value="{$processor_params.logoURL}" class="input-text"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="enableExpressPay">Enable Express Pay:</label>
    <div class="controls">
        <select name="payment_data[processor_params][enableExpressPay]" id="enableExpressPay">
            <option value="1"{if $processor_params.enableExpressPay == 1} selected="selected"{/if}>{__("Enable")}</option>
            <option value="0"{if $processor_params.enableExpressPay == 0} selected="selected"{/if}>{__("Disable")}</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="separateCardMode">Separate Card Mode:</label>
    <div class="controls">
        <select name="payment_data[processor_params][separateCardMode]" id="separateCardMode">
            <option value="1"{if $processor_params.separateCardMode == 1} selected="selected"{/if}>{__("Enable")}</option>
            <option value="0"{if $processor_params.separateCardMode == 0} selected="selected"{/if}>{__("Disable")}</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="enableNewWindowFlow">Enable New Window Flow:</label>
    <div class="controls">
        <select name="payment_data[processor_params][enableNewWindowFlow]" id="enableNewWindowFlow">
            <option value="1"{if $processor_params.enableNewWindowFlow == 1} selected="selected"{/if}>{__("Enable")}</option>
            <option value="0"{if $processor_params.enableNewWindowFlow == 0} selected="selected"{/if}>{__("Disable")}</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="merchantMessage">Merchant Message:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][merchantMessage]" id="merchantMessage"
               value="{$processor_params.merchantMessage}" class="input-text"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="disclaimerMessage">Disclaimer Message:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][disclaimerMessage]" id="disclaimerMessage"
               value="{$processor_params.disclaimerMessage}" class="input-text"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="paymentMode">Payment Mode:</label>
    <div class="controls">
        <select name="payment_data[processor_params][paymentMode]" id="paymentMode">
			<option value="all"{if $processor_params.paymentMode == "all"} selected="selected"{/if}>all</option>
			<option value="cards"{if $processor_params.paymentMode == "cards"} selected="selected"{/if}>cards</option>
			<option value="netBanking"{if $processor_params.paymentMode == "netBanking"} selected="selected"{/if}>netBanking</option>
			<option value="UPI"{if $processor_params.paymentMode == "UPI"} selected="selected"{/if}>UPI</option>
			<option value="imps"{if $processor_params.paymentMode == "imps"} selected="selected"{/if}>imps</option>
			<option value="wallets"{if $processor_params.paymentMode == "wallets"} selected="selected"{/if}>wallets</option>
			<option value="cashCards"{if $processor_params.paymentMode == "cashCards"} selected="selected"{/if}>cashCards</option>
			<option value="NEFTRTGS"{if $processor_params.paymentMode == "NEFTRTGS"} selected="selected"{/if}>NEFTRTGS</option>
			<option value="emiBanks"{if $processor_params.paymentMode == "emiBanks"} selected="selected"{/if}>emiBanks</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="paymentModeOrder">Payment Mode Order:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][paymentModeOrder]" id="paymentModeOrder"
               value="{$processor_params.paymentModeOrder}" class="input-text"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="enableInstrumentDeRegistration">Enable InstrumentDeRegistration:</label>
    <div class="controls">
        <select name="payment_data[processor_params][enableInstrumentDeRegistration]" id="enableInstrumentDeRegistration">
            <option value="1"{if $processor_params.enableInstrumentDeRegistration == 1} selected="selected"{/if}>{__("Enable")}</option>
            <option value="0"{if $processor_params.enableInstrumentDeRegistration == 0} selected="selected"{/if}>{__("Disable")}</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="transactionType">Transaction Type:</label>
    <div class="controls">
        <select name="payment_data[processor_params][transactionType]" id="transactionType">
            <option value="SALE"{if $processor_params.transactionType == "SALE"} selected="selected"{/if}>{__("SALE")}</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="hideSavedInstruments">Hide Saved Instruments:</label>
    <div class="controls">
        <select name="payment_data[processor_params][hideSavedInstruments]" id="hideSavedInstruments">
            <option value="1"{if $processor_params.hideSavedInstruments == 1} selected="selected"{/if}>{__("Enable")}</option>
            <option value="0"{if $processor_params.hideSavedInstruments == 0} selected="selected"{/if}>{__("Disable")}</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="saveInstrument">Save Instrument:</label>
    <div class="controls">
        <select name="payment_data[processor_params][saveInstrument]" id="saveInstrument">
            <option value="1"{if $processor_params.saveInstrument == 1} selected="selected"{/if}>{__("Enable")}</option>
            <option value="0"{if $processor_params.saveInstrument == 0} selected="selected"{/if}>{__("Disable")}</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="EmbedPaymentGatewayOnPage">Embed Payment Gateway On Page:</label>
    <div class="controls">
        <select name="payment_data[processor_params][embedPaymentGatewayOnPage]" id="embedPaymentGatewayOnPage">
            <option value="1"{if $processor_params.embedPaymentGatewayOnPage == 1} selected="selected"{/if}>{__("Enable")}</option>
            <option value="0"{if $processor_params.embedPaymentGatewayOnPage == 0} selected="selected"{/if}>{__("Disable")}</option>
        </select>
    </div>
</div>
