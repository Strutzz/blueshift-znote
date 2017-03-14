<?php
protect_page();

// Import from config:
$pagseguro = $config['pagseguro'];
$paypal = $config['paypal'];
$prices = $config['paypal_prices'];

if ($paypal['enabled']) {
?>

<div class="content bg-image overflow-hidden" style="background-image: url('layouts/beastn/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Buy Points</h1>
        <h2 class="h5 text-white-op animated zoomIn"></h2>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-lg-12">

        <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">buy points with paypal</h3>
                </div>
                <div class="block-content">

<table id="buypointsTable" class="table table-striped table-hover">
	<tr class="yellow">
		<th>Price:</th>
		<th>Points:</th>
		<?php if ($paypal['showBonus']) { ?>
			<th>Bonus:</th>
		<?php } ?>
		<th>Action:</th>
	</tr>
		<?php
		foreach ($prices as $price => $points) {
		echo '<tr class="special">';
		echo '<td>'. $price .'('. $paypal['currency'] .')</td>';
		echo '<td>'. $points .'</td>';
		if ($paypal['showBonus']) echo '<td>'. calculate_discount(($paypal['points_per_currency'] * $price), $points) .' bonus</td>';
		?>
		<td>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="POST">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="<?php echo $paypal['email']; ?>">
				<input type="hidden" name="item_name" value="<?php echo $points .' shop points on '. $config['site_title']; ?>">
				<input type="hidden" name="item_number" value="1">
				<input type="hidden" name="amount" value="<?php echo $price; ?>">
				<input type="hidden" name="no_shipping" value="1">
				<input type="hidden" name="no_note" value="1">
				<input type="hidden" name="currency_code" value="<?php echo $paypal['currency']; ?>">
				<input type="hidden" name="lc" value="GB">
				<input type="hidden" name="bn" value="PP-BuyNowBF">
				<input type="hidden" name="return" value="<?php echo $paypal['success']; ?>">
				<input type="hidden" name="cancel_return" value="<?php echo $paypal['failed']; ?>">
				<input type="hidden" name="rm" value="2">
				<input type="hidden" name="notify_url" value="<?php echo $paypal['ipn']; ?>" />
				<input type="hidden" name="custom" value="<?php echo (int)$session_user_id; ?>">
				<input type="submit" value="  PURCHASE  ">
			</form>
		</td>
		<?php
		echo '</tr>';
		}
		?>
</table>
</div> 
</div>

<?php } ?>

<?php
if ($config['pagseguro']['enabled'] == true) {
?>
<div class="block block-themed">
        <div class="block-header bg-primary">
            <h3 class="block-title">buy points with Pagseguro</h3>
        </div>
        <div class="block-content">
	<form target="pagseguro" action="https://<?=$pagseguro['urls']['www']?>/checkout/checkout.jhtml" method="post">
		<input type="hidden" name="email_cobranca" value="<?=$pagseguro['email']?>">
		<input type="hidden" name="tipo" value="CP">
		<input type="hidden" name="moeda" value="<?=$pagseguro['currency']?>">
		<input type="hidden" name="ref_transacao" value="<?php echo (int)$session_user_id; ?>">
		<input type="hidden" name="item_id_1" value="1">
		<input type="hidden" name="item_descr_1" value="<?=$pagseguro['product_name']?>">
		<input type="number" name="item_quant_1" min="1" step="4" value="1">
		<input type="hidden" name="item_peso_1" value="0">
		<input type="hidden" name="item_valor_1" value="<?=$pagseguro['price']?>">
		<input type="submit" value="  PURCHASE  ">
	</form>
	<br>
    </div> 
    </div> 
<?php } ?>

<?php
if ($config['paygol']['enabled'] == true) {
?>
<div class="block block-themed">
        <div class="block-header bg-primary">
            <h3 class="block-title">buy points with Paygol</h3>
        </div>
        <div class="block-content">
<!-- PayGol Form using Post method -->
<?php $paygol = $config['paygol']; ?>
<p><?php echo $paygol['price'] ." ". $paygol['currency'] ."~ for ". $paygol['points'] ." points:"; ?></p>
<form name="pg_frm" method="post" action="http://www.paygol.com/micropayment/paynow" >
	<input type="hidden" name="pg_serviceid" value="<?php echo $paygol['serviceID']; ?>">
	<input type="hidden" name="pg_currency" value="<?php echo $paygol['currency']; ?>">
	<input type="hidden" name="pg_name" value="<?php echo $paygol['name']; ?>">
	<input type="hidden" name="pg_custom" value="<?php echo $session_user_id; ?>">
	<input type="hidden" name="pg_price" value="<?php echo $paygol['price']; ?>">
	<input type="hidden" name="pg_return_url" value="<?php echo $paygol['returnURL']; ?>">
	<input type="hidden" name="pg_cancel_url" value="<?php echo $paygol['cancelURL']; ?>">
	<input type="image" name="pg_button" src="http://www.paygol.com/micropayment/img/buttons/150/black_en_pbm.png" border="0" alt="Make payments with PayGol: the easiest way!" title="Make payments with PayGol: the easiest way!">
</form>
 </div>
 </div>
<?php }

if (!$config['paypal']['enabled'] && !$config['paygol']['enabled'] && !$config['pagseguro']['enabled']) echo '<h1>Buy Points system disabled.</h1><p>Sorry, this functionality is disabled.</p>';
 ?>


 </div>
  </div>
   </div>
