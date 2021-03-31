
<div clas="content-wrap" style="width:100%">
	<div class="dashboard-activity" style="min-height:550px">
        <div style="width:50%">
    	    <h4>Ingenico : Offline Verification</h4>
    	    <div class="dashboard-activity-list" style="max-height: fit-content;border: 0px;overflow-y: auto;">
    	        <table class="table table-bordered table-hover">
    	            <tr class="info">
    	                <th width="40%">Field Name</th>
    	                <th width="60%">Value</th>
    	            </tr>
    	            <tr>
    	                <td><label>Transaction Identifier <span style="color:red;">*</span></label></td>
    	                <td>
    	                    <input class="form-control" type="text" name="transactionIdentifier1"  id="transactionIdentifier1" value=""/>
    	                    <input class="form-control" type="hidden" name="merchantid" id="merchantid" value="{$paymentData['merchant_id']}"/>
    	                    <input class="form-control" type="hidden" name="currency" id="currency" value="{$paymentData['currency']}"/>
    	                </td>
    	            </tr>
    	            <tr>
    	                <td><label>Transaction Date <span style="color:red;">*</span></label></td>
    	                <td><input class="form-control" type="date" name="offlineDate" id="offlineDate" value=""/></td>
    	            </tr>
    	            <tr>
    	                <td colspan=2>
    	                    <button id="submit1" class="btn btn-info">Check</button>
    	                </td>              
    	            </tr>
    	        </table>
    	        <div id="offline_result">
    	        </div>
    	    </div>    
        </div>
        <div style="width:50%">
            <div class="alert alert-success" id="successMessage"></div>
            <div class="alert alert-danger" id="errorMessage"></div>
        </div>
	</div>
</div>

{literal}
<script>
$(document).ready(function(){
    $('#successMessage').hide();
    $('#errorMessage').hide();
	$('#submit1').click(function(){
        var identifier = $('#transactionIdentifier1').val();
        var date = $('#offlineDate').val();
        var merchantid = $('#merchantid').val();
        var currency = $('#currency').val();
        
        $.ceAjax('request', fn_url('ingenico.offline'), {
            cache: false,
            data: {identifier:identifier,date:date,merchantid:merchantid,currency:currency},
            callback: function(data) {
                if(data.status == "0300"){
                    $('#successMessage').show();
                    $('#successMessage').append(data.message);
                    $('#offline_result').html('');
                    $('#offline_result').append(data.responseHtml);
                    $("#successMessage").fadeTo(2000, 500).slideUp(500, function() {
                        $("#successMessage").slideUp(500);
                    });
                }else{
                    $('#errorMessage').show();
                    $('#errorMessage').append(data.message);
                    $('#offline_result').html('');
                    $('#offline_result').append(data.responseHtml);
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
