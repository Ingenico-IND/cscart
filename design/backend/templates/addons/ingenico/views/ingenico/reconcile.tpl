<div clas="content-wrap" style="width:100%">
    <div class="dashboard-activity" style="min-height:550px">
        <div style="width:50%; float: left;">
            <h4>Ingenico : Reconciliation</h4>
            <div class="dashboard-activity-list" style="max-height: fit-content;border: 0px;overflow-y: auto;">
                <table class="table table-bordered table-hover">
                    <tr class="info">
                        <th width="40%">Field Name</th>
                        <th width="60%">Value</th>
                    </tr>
                    <tr>
                        <td><label>Transaction Date From <span style="color:red;">*</span></label></td>
                        <td><input class="form-control" type="date" name="reconcileFromDate" id="reconcileFromDate" value=""/></td>
                    </tr>
                    <tr>
                        <td><label>Transaction Date To <span style="color:red;">*</span></label></td>
                        <td><input class="form-control" type="date" name="reconcileToDate" id="reconcileToDate" value=""/></td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <button id="submit3" class="btn btn-info">Check</button>
                        </td>
                    </tr>
                </table>
                <div id="reconcile_result">
                </div>
            </div>
        </div>
        <div style="width:50%;">
            <div class="alert alert-success" id="message"></div>
        </div>
    </div>
</div>
{literal}
<script>

$(document).ready(function(){
    $('#message').hide();
    $('#submit3').click(function(){
    var reconcileFromDate = $('#reconcileFromDate').val();
    var reconcileToDate = $('#reconcileToDate').val();
    console.log(reconcileFromDate);
    console.log(reconcileToDate);
        $.ceAjax('request', fn_url('ingenico.reconcile'), {
            cache: false,
            data: {reconcileFromDate,reconcileToDate},
            callback: function(data) {
                $('#reconcile_result').html('');
                $('#reconcile_result').append(data.ids);
                $('#message').show();
                $('#message').html('Reconciliation successful !!!');
                $("#message").fadeTo(2000, 500).slideUp(500, function() {
                    $("#message").slideUp(500);
                });
            }
        });
    });
});
</script>
{/literal}