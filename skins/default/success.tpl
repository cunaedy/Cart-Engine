<!-- BEGINMODULE page_gallery -->
// Success text
page_id = 5
title = 1
body = 1
<!-- ENDMODULE -->

<!-- BEGINIF $howtopay -->
<h1>{$l_how_to_pay}</h1>
{$howtopay}
<!-- ENDIF -->

<!-- BEGINIF $pay_redirect_to_gateway -->
<h1>{$l_pay_redir}</h1>
<form method="{$method}" action="{$action}" name="payment_gateway" id="payment_gateway">
{$hidden_field}
<button type="submit" class="btn btn-success">{$l_pay_redir_3s}</button>
</form>
<script type="text/javascript">
t=setTimeout('$("#payment_gateway").submit()', 3000);
</script>
<!-- ENDIF -->