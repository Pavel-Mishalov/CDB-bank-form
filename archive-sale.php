<?php
/*--------------------------------------------------------------*/
	
if( get_field('currency', $sale_id) ):

	$sdb_options = get_option('sdb_id_option');

	$mac_data   = array();
	$amount     = (get_field('currency', $sale_id)) ? get_field('currency', $sale_id) : 0;  //Цена акции
	$currency   = 643;                              //Валюта рубль
	$order      = time();                           //Код заказа
	$desc       = get_post($sale_id)->post_title;   //Краткое описание
	$merch_name = 'SKIN LAZER MED';                 //Название компании
	$merch_url  = 'http://www.skinlazermed.ru/';
	$merchand   = $sdb_options['clientID'];         //Идентификатор торговца
	$terminal   = $sdb_options['terminal'];         //Идентификатор терминала
	$email      = '';
	$trtype     = 1;
	$timestamp  = date('omdHis');
	$nonce      = bin2hex('my_sdb_nonce');
	$backref    = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	
	array_push($mac_data, strlen($amount).$amount);
	array_push($mac_data, strlen($currency).$currency);
	array_push($mac_data, strlen($order).$order);
	array_push($mac_data, strlen($desc).$desc);
	array_push($mac_data, strlen($merch_name).$merch_name);
	array_push($mac_data, strlen($merch_url).$merch_url);
	array_push($mac_data, strlen($merchand).$merchand);
	array_push($mac_data, strlen($terminal).$terminal);
        array_push($mac_data, '-');
	array_push($mac_data, strlen($trtype).$trtype);
	array_push($mac_data, strlen($timestamp).$timestamp);
	array_push($mac_data, strlen($nonce).$nonce);
	array_push($mac_data, strlen($backref).$backref);

	$mac_data_str = implode('', $mac_data);

	$key = $sdb_options['sdbKey'];
?>

<form id="sdb-form<?php echo $sale_id; ?>" ACTION="https://3dst.sdm.ru/cgi-bin/cgi_link" METHOD="POST" class="text-center">
<label for="fameli">Введите фамилию имя</label>
	<input required TYPE="text" NAME="fameli" ID="fameli" VALUE="" SIZE="50" MAXLENGTH="50">
<label for="contact">Введите контактные данные (email, номер телефона или любые другие, чтобы мы могли с Вами связаться)</label>
	<input required TYPE="text" NAME="contact" ID="contact" VALUE="" SIZE="50" MAXLENGTH="50">
<input TYPE="hidden" NAME="AMOUNT" ID="AMOUNT" VALUE="<?php echo $amount; ?>" SIZE="12" MAXLENGTH="12">
<input TYPE="hidden" NAME="CURRENCY" ID="CURRENCY" VALUE="<?php echo $currency; ?>" SIZE="3" MAXLENGTH="3">
<input TYPE="hidden" NAME="ORDER" ID="ORDER" VALUE="<?php echo $order; ?>" SIZE="32" MAXLENGTH="32">
<input TYPE="hidden" NAME="DESC" ID="DESC" VALUE="<?php echo $desc; ?>" SIZE="50" MAXLENGTH="50">
<input TYPE="hidden" NAME="TERMINAL" ID="TERMINAL" SIZE="8" VALUE="<?php echo $terminal; ?>" MAXLENGTH="8">
<input TYPE="hidden" NAME="TRTYPE" ID="TRTYPE" SIZE="1" VALUE="<?php echo $trtype; ?>" MAXLENGTH="1">
<input TYPE="hidden" NAME="MERCH_NAME" ID="MERCH_NAME" SIZE="100" VALUE="<?php echo $merch_name; ?>" MAXLENGTH="100">
<input TYPE="hidden" NAME="MERCH_URL" ID="MERCH_URL" SIZE="100" VALUE="<?php echo $merch_url; ?>"  MAXLENGTH="100">
<input TYPE="hidden" NAME="MERCHANT" ID="MERCHANT" SIZE="32" VALUE="<?php echo $merchand; ?>" MAXLENGTH="32"> 
<input type="hidden" NAME="EMAIL" ID="EMAIL" SIZE="100" VALUE="" MAXLENGTH="100">
<input TYPE="hidden" NAME="TIMESTAMP" ID="TIMESTAMP" SIZE="32" VALUE="<?php echo $timestamp; ?>" MAXLENGTH="32">
<input TYPE="hidden" NAME="MERCH_GMT" ID="MERCH_GMT" SIZE="5" VALUE="+3" MAXLENGTH="5">
<input TYPE="hidden" NAME="NONCE" ID="NONCE" SIZE="32" VALUE="<?php echo $nonce; ?>" MAXLENGTH="32">
<input TYPE="hidden" NAME="BACKREF" ID="BACKREF" SIZE="100" VALUE="<?php echo $backref; ?>" MAXLENGTH="100">
<input TYPE="hidden" NAME="P_SIGN" SIZE="100" VALUE="<?php echo hash_hmac('sha1',$mac_data_str,hex2bin($key));?>" MAXLENGTH="100">
<input TYPE="hidden" NAME="KEY" SIZE="40" VALUE="<?php echo $key; ?>" MAXLENGTH="40">
<input TYPE="hidden" NAME="MAC_DATA" SIZE="100" VALUE="<?php echo $mac_data_str; ?>" MAXLENGTH="1000">
<input TYPE="hidden" NAME="MAC" SIZE="40" VALUE="<?php echo hash_hmac('sha1',$mac_data_str,hex2bin($key));?>" MAXLENGTH="40">
<input type=button value="Оплатить" onclick="SDB_start(ORDER.value, contact.value, fameli.value, DESC.value, <?php echo $sale_id; ?>);">
</form>

<script type="text/javascript">
	function SDB_start(data_order, data_email, data_fameli, data_name_product, data_id_product){
		jQuery.ajax({
			type:"POST",
			url: myajax.url, 
			data: {
				action:"SDB_action",
				'order': data_order,
				'email': data_email,
				'fameli': data_fameli,
				'name_product': data_name_product
				},
			success: function(){
				forms = 'form#sdb-form' + data_id_product;
				jQuery(forms).submit();
			}
		});
	}
</script>

<?php
	endif;
	/*--------------------------------------------------------------------------------------------------------*/
?>
