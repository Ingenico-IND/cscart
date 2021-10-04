
<div clas="content-wrap" style="width:100%">    
	<div class="dashboard-activity" style="min-height:550px">
        <div class="alert alert-success" id="successMessage"></div>
        <div class="alert alert-danger" id="errorMessage"></div>
        <div style="width:50%;">        
    	    <h4>Worldline : Refund </h4>
    	    <div class="dashboard-activity-list" style="max-height: fit-content;border: 0px;overflow-y: auto;">
    	        <table class="table table-bordered table-hover">
    	            <tr class="info">
    	                <th width="40%">Field Name</th>
    	                <th width="60%">Value</th>
    	            </tr>
    	            <tr>
    	                <td><label>Transaction Identifier <span style="color:red;">*</span></label></td>
    	                <td>
    	                    <input class="form-control" type="text" name="transactionIdentifier2"  id="transactionIdentifier2" value=""/>
    	                    <input class="form-control" type="hidden" name="merchantid" id="merchantid" value="{$paymentData['merchant_id']}"/>
    	                    <input class="form-control" type="hidden" name="currency" id="currency" value="{$paymentData['currency']}"/>
    	                </td>
    	            </tr>
    	            <tr>
    	                <td><label>Amount <span style="color:red;">*</span></label></td>
    	                <td>
    	                    <input class="form-control" type="text" name="amount" id="amount" value=""/>
    	                </td>
    	            </tr>
    	            <tr>
    	                <td><label>Transaction Date <span style="color:red;">*</span></label></td>
    	                <td><input class="form-control" type="date" name="refundDate" id="refundDate" value=""/></td>
    	            </tr>
    	            <tr>
    	                <td colspan=2>
                            <button id="submit2" class="btn btn-info">Submit</button>
    	                </td> 
    	            </tr>
    	        </table>
                <div id="refundResult"> 
                </div>
            </div>
	    </div>
	</div>
</div>

{literal}
<script>
$(document).ready(function(){
    $('#successMessage').hide();
    $('#errorMessage').hide();
	$('#submit2').click(function(){
        var identifier = $('#transactionIdentifier2').val();
        var amountR = $('#amount').val();
        var date = $('#refundDate').val();
        var merchantid = $('#merchantid').val();
        var currency = $('#currency').val();           

        $.ceAjax('request', fn_url('worldline.refund'), {
            cache: false,
            data: {identifier:identifier,amount:amountR,date:date,merchantid:merchantid,currency:currency},
            callback: function(data) {
                if(data.status == "0400"){
                    $('#successMessage').show();
                    $('#successMessage').append(data.message);
                    $('#refundResult').html('');
                    $('#refundResult').append(data.responseHtml);
                    $("#successMessage").fadeTo(2000, 500).slideUp(500, function() {
                        $("#successMessage").slideUp(500);
                    });
                }else{
                    $('#errorMessage').show();
                    $('#errorMessage').append(data.message);
                    $('#refundResult').html('');
                    $('#refundResult').append(data.responseHtml);
                    $("#errorMessage").fadeTo(2000, 500).slideUp(500, function() {
                        $("#errorMessage").slideUp(500);
                    });
                }                
            }
        });
           
    });
});
</script>
{/literal}