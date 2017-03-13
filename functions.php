<?

/*-------------------------------------------------------------------------------------*/

add_action('wp_ajax_SDB_action', 'set_SDB_order');
add_action('wp_ajax_nopriv_SDB_action', 'set_SDB_order');
function set_SDB_order() {
	if( isset($_POST)){
		set_transient(
			'order_'.$_POST['order'],
			array(
				'name'         => $_POST['fameli'],
				'email'        => $_POST['email'],
				'fameli'       => $_POST['fameli'],
				'name_product' => $_POST['name_product'],
				'amount'       => $_POST['amount']
			),
			24 * HOUR_IN_SECONDS
		);
	}
}
add_action('admin_menu', 'register_sale_custom_submenu_page');

function register_sale_custom_submenu_page() {
	add_submenu_page( 'edit.php?post_type=sale', 'Данные СДБ', 'СДБ банк', 'manage_options', 'sdb-sale-submenu', 'get_sdb_submenu' );
}

function get_sdb_submenu(){
	echo '<form action="options.php" method="post">';
		settings_fields( 'sdb_option' );
		do_settings_sections( 'sdb_option' );
		submit_button();
	echo '</form>';
}

add_action('admin_init', 'register_sdb_options');

function register_sdb_options() {
	
	register_setting(
            'sdb_option',
            'sdb_id_option',
            'set_sdb_option'
        );

	add_settings_section(
            'sdb_section',
            'Настройки СДБ банка',
            'sdb_section_input',
            'sdb_option'
        );

    add_settings_field(
        'sdb_id_terminal',
        'Идентификатор терминала',
        'sdb_section_input',
	    'sdb_option',
        'sdb_section',
        [
            'label_for'         => 'terminal',
            'placeholder'       => 'Введите идентификатор терминала',
            'description'       => 'Поле для ввода идентификатор терминала',
            'type'              => 'text'
        ]
    );

    add_settings_field(
        'sdb_id_clietn',
        'Идентификатор клиента',
        'sdb_section_input',
	    'sdb_option',
        'sdb_section',
        [
            'label_for'         => 'clientID',
            'placeholder'       => 'Введите идентификатор клиента',
            'description'       => 'Поле для ввода идентификатор клиента',
            'type'              => 'text'
        ]
    );

    add_settings_field(
        'sdb_key',
        'Ключ',
        'sdb_section_input',
	    'sdb_option',
        'sdb_section',
        [
            'label_for'         => 'sdbKey',
            'placeholder'       => 'Введите ключ',
            'description'       => 'Поле для ввода ключа',
            'type'              => 'text'
        ]
    );
}

function sdb_section( $args ) {
        ?>
        <p id="<?php echo esc_attr( $args[ 'id' ] ); ?>">
            Настройки СДБ банка
        </p>
        <?php
    }

function sdb_section_input( $args ) {

	$options = get_option('sdb_id_option');

	if ( esc_attr( $args[ 'type' ] ) == 'text' ) : ?>

	<input id="<?php echo esc_attr( $args[ 'label_for' ] ); ?>"
	       type="text"
	       name="sdb_id_option[<?php echo esc_attr( $args[ 'label_for' ] ); ?>]"
	       value="<?php echo $options[ $args[ 'label_for' ] ]; ?>"
	       placeholder="<?php echo esc_attr( $args[ 'placeholder' ] ); ?>">
	<?php
		endif;
	?>
	<p class="description">
		<?php echo $args[ 'description' ]; ?>
	</p>

	<?php
}

function set_sdb_option( $input ) {
	$output                 = get_option( 'sdb_id_option' );
	$output['terminal']     = sanitize_text_field( $input['terminal'] );
	$output['clientID']  	= sanitize_text_field( $input['clientID'] );
	$output['sdbKey']       = sanitize_text_field( $input['sdbKey'] );

	return $output;
}