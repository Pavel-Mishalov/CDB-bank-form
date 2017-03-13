<?php //Template Name: СДБ Банк ?>

<?php get_header('SDB'); ?>

<?php

$data_oplat = get_transient('order_'. $_POST['Order']);

if(isset($_POST)):
	$list = array ('Дата операции', date('o-m-d H:i:s'));
	$list2 = array ('Сумма операции', 'Валюта заказа', 'Номер заказа интернет-магазина', 'Тип операции', 'Код ответа шлюза', 'Код транзакционного ответа', 'Код положительного ответа банка клиента', 'Уникальный ссылочный номер', 'Внутренний ссылочный номер шлюза', 'Дополнительная комиссия банка', 'MAC-код ответа в шестнадцатеричном формате', 'Имя покупателя', 'Контакты', 'Оплоченая акция');
	$fp = fopen(get_template_directory().'/SDB/otchet.csv', a);

	//Данная строка формирует файл в кодировке UTF-8
	fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
	fputcsv($fp, $list, ';');
	fputcsv($fp, $list2, ';');

	$amount   = $_POST['Amount'];    //Сумма операции
	$currency = $_POST['Currency'];  //Валюта заказа 643
	$order    = $_POST['Order'];     //Номер заказа интернет-магазина
	$trtype   = $_POST['TRType'];    //Тип операции 1
	$result   = $_POST['Result'];    //Код ответа шлюза
	$rc       = $_POST['RC'];        //Код транзакционного ответа
	$authcode = $_POST['AuthCode'];  //Код положительного ответа банка клиента (код авторизации) (поле 38 протокола ISO-8583)
	$rrn      = $_POST['RRN'];       //Уникальный ссылочный номер СДМ-БАНКа (поле 37 протокола ISO-8583)
	$int_ref  = $_POST['IntRef'];    //Внутренний ссылочный номер шлюза
	$fee      = $_POST['Fee'];       //Дополнительная комиссия банка.
	$p_sing   = $_POST['P_Sign'];    //MAC-код ответа в шестнадцатеричном формате
	$client   = $data_oplat['fameli'];
	$contact  = $data_oplat['email'];
	$product  = $data_oplat['name_product'];

	$list3 = array($amount, $currency, $order, $trtype, $result, $rc, $authcode, $rrn, $int_ref, $fee, $p_sing, $client, $contact, $product);
	fputcsv($fp, $list3, ';');
	//fputcsv($fp, $_POST, ';');
	//fputcsv($fp, array_keys($_POST), ';');

$to      = get_field('email_adress');
$from    = get_bloginfo('admin_email');
$subject = get_field('subject');
$message = get_field('content');
$message .= '<table>';
$data_arr = get_field('data_bank');
$message .= emailMassage('Покупатель акции',                                          $data_arr, $client);
$message .= emailMassage('Контактные данные покупателя',                              $data_arr, $contact);
$message .= emailMassage('Название оплаченной акции',                                 $data_arr, $product);
$message .= emailMassage('Сумма операции',                                            $data_arr, $amount);
$message .= emailMassage('Валюта заказа',                                             $data_arr, $currency.' (рубль)');
$message .= emailMassage('Номер заказа интернет-магазина',                            $data_arr, $order);
$message .= emailMassage('Тип операции',                                              $data_arr, $trtype.' (1 - операция покупки)');
$message .= emailMassage('Код ответа шлюза',                                          $data_arr, $result);
$message .= emailMassage('Код транзакционного ответа',                                $data_arr, $rc);
$message .= emailMassage('Код положительного ответа банка клиента (код авторизации)', $data_arr, $authcode);
$message .= emailMassage('Уникальный ссылочный номер СДМ-Банка',                      $data_arr, $rrn);
$message .= emailMassage('Внутренний ссылочный номер шлюза',                          $data_arr, $int_ref);
$message .= emailMassage('Дополнительная комиссия банка',                             $data_arr, $fee);
$message .= emailMassage('MAC-код ответа',                                            $data_arr, $p_sing);
$message .= '</table>';
$headers[] = 'From: Me Myself <me@example.net>';
$headers[] = 'content-type: text/html';

wp_mail( $to, $subject, $message, $headers );

endif;

function emailMassage($data_in, $data_arr, $data_out){
	if(in_array($data_in, $data_arr)):
		return '<tr><td>'.$data_in.'</td><td>'.$data_out.'</td></tr>';
	endif;
	return '';
}

$x = get_option('sdb_id_option');
echo $x['terminal'].'<br>';
echo $x['clientID'].'<br>';
echo $x['sdbKey'];

?>

<?php get_footer('SDB'); ?> 
