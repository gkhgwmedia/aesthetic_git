<?php
/**
 * WPSC Purchase log functions
 */
 
/**
 * Get the purchase log IDs by a product ID
 */
function aesthetic_get_logids_by_product( $product_id = '' ){
	global $wpdb;

	if( empty( $product_id ) )
		return false;
	
	$ret_arr = array();
	$qry = "SELECT purchaseid  FROM `" . WPSC_TABLE_CART_CONTENTS . "` WHERE `prodid`=" . $product_id . "";
	
	$result = $wpdb->get_results( $qry, 'ARRAY_A' );
	
	foreach( $result as $arr )
		$ret_arr[] = $arr['purchaseid'];
		
	return $ret_arr;
}

/**
 * Get the no of purchase made on a deal
 */
function get_no_of_bought( $product_id = '' ){
	if( !$product_id )
		return false;
		
	$log_arr = aesthetic_get_logids_by_product( $product_id );
	$bought_arr = aesthetic_get_log_by_ids( $log_arr, array( 2, 3 ) );
	
	return sizeof( $bought_arr );
}

/**
 * Get a purchase log by ID
 */
function aesthetic_get_log_by_id( $id = '' ){
	global $wpdb;

	if( empty( $id ) )
		return false;
		
	$qry = "SELECT * FROM " . WPSC_TABLE_PURCHASE_LOGS ." WHERE id = ". $id ;
	
	return $wpdb->get_row( $qry );
}

/**
 * Get a purchase log by IDs
 */
function aesthetic_get_log_by_ids( $ids = array(), $processed = array() ){
	global $wpdb;

	if( sizeof( $ids ) == 0 )
		return false;
		
	$qry = "SELECT * FROM " . WPSC_TABLE_PURCHASE_LOGS ." WHERE id IN ( ". implode( ', ', $ids ) ." )" ;
	
	if( sizeof( $processed ) )
		$qry .= " AND processed IN ( ". implode( ', ', $processed ) ." )";
	
	return $wpdb->get_results( $qry );
}

/**
 * User purchase log
 */
function aesthetic_purchase_log_user(){
?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery(".flex-orders").flexigrid({
				url : '<?php echo admin_url( 'admin-ajax.php?action=user_purchase_log' ); ?>',
				dataType : 'json',
				colModel : [ {
					display : 'ID',
					name : 'id',
					width : 50,
					sortable : true,
					align : 'left'
				}, {
					display : 'Receipt No',
					name : 'recno',
					width : 100,
					sortable : true,
					align : 'left'
				}, {
					display : 'Deal',
					name : 'item',
					width : 150,
					sortable : false,
					align : 'left'
				}, {
					display : 'Qty',
					name : 'qty',
					width : 50,
					sortable : true,
					align : 'left'
				}, {
					display : 'Price',
					name : 'price',
					width : 50,
					sortable : true,
					align : 'left'
				}, {
					display : 'Order Status',
					name : 'status',
					width : 100,
					sortable : true,
					align : 'left'
				}, {
					display : 'Friend\'s Mail',
					name : 'friend',
					width : 150,
					sortable : false,
					align : 'left'
				}, {
					display : 'Date',
					name : 'date',
					width : 100,
					sortable : true,
					align : 'left'
				}, {
					display : 'Return',
					name : 'return',
					width : 100,
					sortable : true,
					align : 'center'
				}],
				searchitems : [ {
					display : 'ID',
					name : 'id',
					isdefault : true
				}, {
					display : 'Reciept No',
					name : 'recno'
				}, {
					display : 'Deal',
					name : 'item'
				}, {
					display : 'Order Status',
					name : 'status'
				}, {
					display : 'Date',
					name : 'date'
				} ],
				sortname : "id",
				sortorder : "desc",
				usepager : true,
				title : 'Order History',
				useRp : true,
				rp : 15,
				showTableToggleBtn : true,
				width : 710,
				height : 600
			});
		});
	</script>
    <div>
        <table class="flex-orders" style="display: none"></table>
    </div>
<?php
}

/**
 * purchase log function for user
 */
function aesthetic_user_purchase_log(){
	global $wpdb, $user_ID, $wpsc_purchlog_statuses, $gateway_checkout_form_fields, $purchase_log, $col_count, $wpsc_purchase_log;
	
	$sql = "SELECT * FROM " . WPSC_TABLE_PURCHASE_LOGS ." WHERE user_ID = ". get_current_user_id();
	
	$purchase_log = $wpdb->get_results( $sql, 'ARRAY_A' );
	
//	echo '<pre>';
//	print_r( $purchase_log );
//	echo '</pre>';
//	exit;
	
	$orders = array();
	foreach( $purchase_log as $order ){
		$orders[$order['sessionid']] = array(
			'id' => $order['id'],
			'item' => aesthetic_get_ordered_items( $order['id'] ),
			'recno' => $order['sessionid'],
			'qty' => aesthetic_get_ordered_items( $order['id'], 'quantity' ),
			'price' => $order['totalprice'],
			'status' => aesthetic_order_status( $order['processed'] ),
			'friend' => aesthetic_log_friend_mail( $order['id'] ),
			'date' => $order['date'],
			'processed' => $order['processed']
		);
	}
	
	$page = isset($_POST['page']) ? $_POST['page'] : 1;
	$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
	$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'name';
	$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
	$query = isset($_POST['query']) ? $_POST['query'] : false;
	$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
	
	$rows = $orders;
	
	### Search and paging 
	if($qtype && $query){
		$query = strtolower(trim($query));
		foreach($rows AS $key => $row){
			if(strpos(strtolower($row[$qtype]),$query) === false){
				unset($rows[$key]);
			}
		}
	}
	//Make PHP handle the sorting
	$sortArray = array();
	foreach($rows AS $key => $row){
		$sortArray[$key] = $row[$sortname];
	}
	$sortMethod = SORT_ASC;
	if($sortorder == 'desc'){
		$sortMethod = SORT_DESC;
	}
	array_multisort($sortArray, $sortMethod, $rows);
	$total = count($rows);
	$rows = array_slice($rows,($page-1)*$rp,$rp);	
	
	header("Content-type: application/json");
	$jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
	
	foreach($rows AS $row){
		
		$row['date'] = date( 'Y-m-d', $row['date'] );
		$row['item'] = '<a href="'. get_permalink( aesthetic_get_ordered_items( $row['id'], 'prodid' ) ) .'">'. $row['item'] .'</a>';
		$row['price'] = wpsc_currency_display( $row['price'] );
		$row['return'] = ( in_array( $row['processed'], array( 1, 2, 3, 4 ) ) ) ? '<a class="button-grid" href="javascript:returnDeal('. $row['id'] .')">Return?</a>' : '';
		
		$entry = array( 'id'=>$row['id'], 'cell' => $row );
		$jsonData['rows'][] = $entry;
	}
	echo json_encode($jsonData);
	exit;
}
add_action('wp_ajax_user_purchase_log', 'aesthetic_user_purchase_log');

/**
 * Merchant purchase log
 */
function aesthetic_purchase_log_merchant(){

	if( !current_user_can( 'author' ) )
		return;
?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery(".flex-mer-orders").flexigrid({
				url : jQuery( '#merchant_order_action' ).val(),
				dataType : 'json',
				colModel : [ {
					display : 'ID',
					name : 'id',
					width : 50,
					sortable : true,
					align : 'left'
				}, {
					display : 'Receipt No',
					name : 'recno',
					width : 100,
					sortable : true,
					align : 'left'
				}, {
					display : 'Deal',
					name : 'item',
					width : 150,
					sortable : false,
					align : 'left'
				}, {
					display : 'Qty',
					name : 'qty',
					width : 50,
					sortable : true,
					align : 'left'
				}, {
					display : 'Price',
					name : 'price',
					width : 50,
					sortable : true,
					align : 'left'
				}, {
					display : 'Order Status',
					name : 'status',
					width : 100,
					sortable : true,
					align : 'left'
				}, {
					display : 'User',
					name : 'user',
					width : 150,
					sortable : false,
					align : 'left'
				}, {
					display : 'Friend\'s Mail',
					name : 'friend',
					width : 150,
					sortable : false,
					align : 'left'
				}, {
					display : 'Date',
					name : 'date',
					width : 100,
					sortable : true,
					align : 'left'
				}, {
					display : 'Return',
					name : 'return',
					width : 100,
					sortable : true,
					align : 'center'
				}],
				searchitems : [ {
					display : 'ID',
					name : 'id',
					isdefault : true
				}, {
					display : 'Reciept No',
					name : 'recno'
				}, {
					display : 'Deal',
					name : 'item'
				}, {
					display : 'Order Status',
					name : 'status'
				}, {
					display : 'Date',
					name : 'date'
				} ],
				sortname : "id",
				sortorder : "desc",
				usepager : true,
				title : 'Order history',
				useRp : true,
				rp : 15,
				showTableToggleBtn : true,
				width : 710,
				height : 600
			});
		});
	</script>
    <div>
        <table class="flex-mer-orders" style="display: none"></table>
		<input type="hidden" id="merchant_order_action" value="<?php echo admin_url( 'admin-ajax.php?action=merchant_purchase_log' ); ?>" />
    </div>
<?php
}

/**
 * purchase log function for merchant
 */
function aesthetic_merchant_purchase_log(){
	global $wpdb, $user_ID;
	
	if( !current_user_can( 'author' ) || empty( $_REQUEST['product_id'] ) )
		return false;
		
	$logs_ids = aesthetic_get_logids_by_product( $_REQUEST['product_id'] );
	
	$sql = "SELECT * FROM " . WPSC_TABLE_PURCHASE_LOGS ." WHERE id IN( ". implode( ', ', $logs_ids ) ." ) ";
		
	$purchase_log = $wpdb->get_results( $sql, 'ARRAY_A' );
	
//	echo '<pre>';
//	print_r( $purchase_log );
//	echo '</pre>';
//	exit;
	
	$orders = array();
	foreach( $purchase_log as $order ){
		$orders[$order['sessionid']] = array(
			'id' => $order['id'],
			'item' => aesthetic_get_ordered_items( $order['id'] ),
			'recno' => $order['sessionid'],
			'qty' => aesthetic_get_ordered_items( $order['id'], 'quantity' ),
			'price' => $order['totalprice'],
			'status' => aesthetic_order_status( $order['processed'] ),
			'user' => $order['user_ID'],
			'friend' => aesthetic_log_friend_mail( $order['id'] ),
			'date' => $order['date'],
			'processed' => $order['processed']
		);
	}
	
	$page = isset($_POST['page']) ? $_POST['page'] : 1;
	$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
	$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'name';
	$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
	$query = isset($_POST['query']) ? $_POST['query'] : false;
	$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
	
	$rows = $orders;
	
	### Search and paging 
	if($qtype && $query){
		$query = strtolower(trim($query));
		foreach($rows AS $key => $row){
			if(strpos(strtolower($row[$qtype]),$query) === false){
				unset($rows[$key]);
			}
		}
	}
	//Make PHP handle the sorting
	$sortArray = array();
	foreach($rows AS $key => $row){
		$sortArray[$key] = $row[$sortname];
	}
	$sortMethod = SORT_ASC;
	if($sortorder == 'desc'){
		$sortMethod = SORT_DESC;
	}
	array_multisort($sortArray, $sortMethod, $rows);
	$total = count($rows);
	$rows = array_slice($rows,($page-1)*$rp,$rp);	
	
	header("Content-type: application/json");
	$jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
	
	foreach($rows AS $row){
		
		$row['date'] = date( 'Y-m-d', $row['date'] );
		$row['item'] = '<a href="'. get_permalink( aesthetic_get_ordered_items( $row['id'], 'prodid' ) ) .'">'. $row['item'] .'</a>';
		$row['price'] = wpsc_currency_display( $row['price'] );
		$row['user'] = '<a href="'. get_edit_user_link( $row['user'] ) .'">'. aesthetic_get_user_mail( $row['user'] ) .'</a>';
		$row['return'] = ( $row['processed'] == 8 ) ? '<a href="javascript:returnApprove('. $row['id'] .')" class="button-grid">Return Approve</a>' : '';
		
		$entry = array( 'id'=>$row['id'], 'cell' => $row );
		$jsonData['rows'][] = $entry;
	}
	echo json_encode($jsonData);
	exit;
}
add_action('wp_ajax_merchant_purchase_log', 'aesthetic_merchant_purchase_log');

/**
 * Order status
 */
function aesthetic_order_status( $processed = 1 ){
	$return = '';
	
	if( $processed == 1 )
		return 'Incomplete sale';
	else if( $processed == 2 )
		return 'Order recieved';
	else if( $processed == 3 )
		return 'Accepted Payment';
	else if( $processed == 4 )
		return 'Job dispatched';
	else if( $processed == 5 )
		return 'Closed order';
	else if( $processed == 6 )
		return 'Payment declined';
	else if( $processed == 7 )
		return 'Refunded';
	else if( $processed == 8 )
		return 'Refund pending';
}

/**
 * Get the purchase log friend email ID
 */
function aesthetic_log_friend_mail( $log_id = '' ){
	if( empty( $log_id ) )
		return false;
		
	$meta_arr = wpsc_get_meta( $log_id, 'wpsc_log_friend', 'wpsc_purchase_log' );
	
	if( $meta_arr['to_mail'] )
		return $meta_arr['to_mail'];
		
	return '';
}

/**
 * Get the order items
 */
function aesthetic_get_ordered_items( $id = '', $key = 'name' ){
	if( empty( $id ) )
		return false;
		
	$items = new wpsc_purchaselogs_items( $id );
	
	$item = end( $items->allcartcontent );
	
//	echo '<pre>';
//	print_r( $item );
//	echo '</pre>';
//	exit;
	
	if( isset( $item->$key ) )
		return $item->$key;
	else
		return '';
}

/**
 * Return deal form
 */
function aesthetic_return_deal_form(){
	if( $_POST['return_deal_id'] ){
?>
		<script>
			jQuery(document).ready(function($){
				$('#return_reason').NobleCount('#reasonLmt',{
					max_chars: 330,
					on_negative: 'go_red',
					on_positive: 'go_green',
					block_negative: true,
					on_negative: function( textArea, charArea, sets, charRem ){
						var content = textArea.val();
						
						textArea.val( content.substring( 0, sets.max_chars ) );
					}
				});
			});
		</script>
		<div class="popup_friend">
		<h1>Return Deal - Deal name</h1>
		<div class="form">
			<form name="return_deal_form" id="return_deal_form" action="" method="post">
				<input type="hidden" name="return_deal_id" value="<?php echo $_POST['return_deal_id']; ?>" />
				<div class="clsFormField">
					<label>Reason <span class="red">*</span></label>
					<textarea id="return_reason" name="return_reason" rows="50" cols="5"></textarea>
					<span class="note">(Maximum of <span id="reasonLmt"></span> characters) - Optional </span>
				</div>
				<div class="clear"></div>
				<div class="buttons">
					<input type="submit" class="btnSubmit" name="return_deal_submit" id="return_deal_submit" value="Return" />
					<input type="button" id="return_cancel" class="btnSubmit" value="Cancel" />
				</div>
			</form>
		</div>
	</div>
<?php
	}
	exit;
}
add_action( 'wp_ajax_return_deal_form', 'aesthetic_return_deal_form' );

/**
 * Return deal action
 */
function aesthetic_return_deal(){

	
	if( $_REQUEST['return_deal_id'] ){
		
		if( aesthetic_update_log_status( $_REQUEST['return_deal_id'], 8 ) ){
		
			$log_obj = aesthetic_get_log_by_id( $_REQUEST['return_deal_id'] );
			$log_content = new wpsc_purchaselogs_items( $_REQUEST['return_deal_id'] );
			do_action( 'aesthetic_return_deal', $log_obj, $log_content ); 
		
			aesthetic_action_msg( 'Deal has been returned successfully', false );
		}
	}
}
add_action( 'wp_ajax_return_deal', 'aesthetic_return_deal', 2 );

/**
 * Deal return approval
 */
function aesthetic_deal_return_approve(){
	if( !current_user_can( 'author' ) )
		return false;
	
	if( $_REQUEST['return_deal_id'] ){
		if( aesthetic_update_log_status( $_REQUEST['return_deal_id'], 7 ) ){
		
			$log_obj = aesthetic_get_log_by_id( $_REQUEST['return_deal_id'] );
			$log_content = new wpsc_purchaselogs_items( $_REQUEST['return_deal_id'] );
			do_action( 'aesthetic_return_deal_approve', $log_obj, $log_content ); 
		
			aesthetic_action_msg( 'Deal return has been approved successfully', false );
		}
	}
}
add_action( 'wp_ajax_return_approve', 'aesthetic_deal_return_approve' );

/**
 * Update purchase log status
 */
function aesthetic_update_log_status( $log_id = '', $status = 1 ){
	global $wpdb;

	if( empty( $log_id ) )
		return false;

	$ret = $wpdb->update(
		WPSC_TABLE_PURCHASE_LOGS,
		array(
			'processed' => $status
		),
		array(
			'id' => $log_id
		),
		array( '%d', '%d' )
	);
	
	return $ret;
}

/**
 * Add an admin menu for purchase log
 */
function aesthetic_purchase_log_menu( $page_hooks, $base_page ) {
	if( is_admin() )
		$page_hooks[] = add_submenu_page( $base_page, 'Orders', '- Orders', 10, 'aesthetic-orders', 'aesthetic_purchase_log_admin' );
	return $page_hooks;
}
add_filter('wpsc_additional_pages', 'aesthetic_purchase_log_menu', 10, 2);

/**
 * Admin purchase log
 */
function aesthetic_purchase_log_admin(){
?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery(".flex_orders").flexigrid({
				url : '<?php echo admin_url( 'admin-ajax.php?action=aesthetic_purchase_log' ); ?>',
				dataType : 'json',
				colModel : [ {
					display : 'ID',
					name : 'id',
					width : 50,
					sortable : true,
					align : 'left'
				}, {
					display : 'Receipt No',
					name : 'recno',
					width : 100,
					sortable : true,
					align : 'left'
				}, {
					display : 'Deal',
					name : 'item',
					width : 150,
					sortable : false,
					align : 'left'
				}, {
					display : 'Qty',
					name : 'qty',
					width : 50,
					sortable : true,
					align : 'left'
				}, {
					display : 'Price',
					name : 'price',
					width : 50,
					sortable : true,
					align : 'left'
				}, {
					display : 'Order Status',
					name : 'status',
					width : 100,
					sortable : true,
					align : 'left'
				}, {
					display : 'User',
					name : 'user',
					width : 150,
					sortable : false,
					align : 'left'
				}, {
					display : 'Friend\'s Nail',
					name : 'friend',
					width : 150,
					sortable : false,
					align : 'left'
				}, {
					display : 'Date',
					name : 'date',
					width : 100,
					sortable : true,
					align : 'left'
				}, {
					display : 'Return',
					name : 'return',
					width : 100,
					sortable : true,
					align : 'left'
				}],
				searchitems : [ {
					display : 'ID',
					name : 'id',
					isdefault : true
				}, {
					display : 'Reciept No',
					name : 'recno'
				}, {
					display : 'Deal',
					name : 'item'
				}, {
					display : 'Order Status',
					name : 'status'
				}, {
					display : 'Date',
					name : 'date'
				} ],
				sortname : "id",
				sortorder : "desc",
				usepager : true,
				title : 'Order History',
				useRp : true,
				rp : 15,
				showTableToggleBtn : true,
				width : 1150,
				height : 600
			});
		});
	</script>
   <div class="wrap">
		<div id="icon-users" class="icon32"></div>
		<h2>Purchase Log</h2>
        <div style="margin-top:20px">
			<table class="flex_orders" style="display: none"></table>
        </div>
	</div>
<?php
}

/**
 * purchase log function for merchant
 */
function aesthetic_purchase_log(){
	global $wpdb, $user_ID;
		
	if( !is_admin() )
		return false;
		
	$sql = "SELECT * FROM " . WPSC_TABLE_PURCHASE_LOGS;
		
	$purchase_log = $wpdb->get_results( $sql, 'ARRAY_A' );
	
//	echo '<pre>';
//	print_r( $purchase_log );
//	echo '</pre>';
//	exit;
	
	$orders = array();
	foreach( $purchase_log as $order ){
		$orders[$order['sessionid']] = array(
			'id' => $order['id'],
			'item' => aesthetic_get_ordered_items( $order['id'] ),
			'recno' => $order['sessionid'],
			'qty' => aesthetic_get_ordered_items( $order['id'], 'quantity' ),
			'price' => $order['totalprice'],
			'status' => aesthetic_order_status( $order['processed'] ),
			'user' => $order['user_ID'],
			'friend' => aesthetic_log_friend_mail( $order['id'] ),
			'date' => $order['date'],
			'processed' => $order['processed']
		);
	}
	
	$page = isset($_POST['page']) ? $_POST['page'] : 1;
	$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
	$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'name';
	$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
	$query = isset($_POST['query']) ? $_POST['query'] : false;
	$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
	
	$rows = $orders;
	
	### Search and paging 
	if($qtype && $query){
		$query = strtolower(trim($query));
		foreach($rows AS $key => $row){
			if(strpos(strtolower($row[$qtype]),$query) === false){
				unset($rows[$key]);
			}
		}
	}
	//Make PHP handle the sorting
	$sortArray = array();
	foreach($rows AS $key => $row){
		$sortArray[$key] = $row[$sortname];
	}
	$sortMethod = SORT_ASC;
	if($sortorder == 'desc'){
		$sortMethod = SORT_DESC;
	}
	array_multisort($sortArray, $sortMethod, $rows);
	$total = count($rows);
	$rows = array_slice($rows,($page-1)*$rp,$rp);	
	
	header("Content-type: application/json");
	$jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
	
	foreach($rows AS $row){
		
		$row['date'] = date( 'Y-m-d', $row['date'] );
		$row['item'] = '<a href="'. get_permalink( aesthetic_get_ordered_items( $row['id'], 'prodid' ) ) .'">'. $row['item'] .'</a>';
		$row['price'] = wpsc_currency_display( $row['price'] );
		$row['user'] = '<a href="'. get_edit_user_link( $row['user'] ) .'">'. aesthetic_get_user_mail( $row['user'] ) .'</a>';
		$row['return'] = ( $row['processed'] == 8 ) ? '<a href="javascript:returnApprove('. $row['id'] .')">Return Approve</a>' : '';
		
		$entry = array( 'id'=>$row['id'], 'cell' => $row );
		$jsonData['rows'][] = $entry;
	}
	echo json_encode($jsonData);
	exit;
}
add_action('wp_ajax_aesthetic_purchase_log', 'aesthetic_purchase_log');

##### Start My Wallet section ##########################

/**
 * Update my wallet
 */
function aesthetic_update_user_wallet( $user_id = '', $amt = '', $mode = 'add' ){
	if( !$user_id )
		return false;

	if( !$amt )
		$amt = 0;
		
	$amt = absint( $amt );
	
	$prev_amt = aesthetic_get_user_wallet( $user_id );
	
	if( $mode == 'add' )
		$total = $prev_amt + $amt;
	else if( $mode == 'sub' )
		$total = absint( $prev_amt - $amt );

	return update_user_meta( $user_id, '_aes_wallet', $total ); 
}

/**
 * Get a user wallet amount
 */
function aesthetic_get_user_wallet( $user_id = '' ){
	if( !$user_id )
		return false;
		
	$amt = get_user_meta( $user_id, '_aes_wallet', true );
	
	return absint( $amt );
}

/**
 * Return the Deal amount to the user
 */
function aesthetic_return_deal_amt( $log_obj ){
	if( !is_object( $log_obj ) )
		return false;
	
	return aesthetic_update_user_wallet( $log_obj->user_ID, $log_obj->totalprice );
}
add_action( 'aesthetic_return_deal', 'aesthetic_return_deal_amt', 1 );

/**
 * Get a current user wallet amount - By AJAX
 */
function aesthetic_get_currentuser_wallet(){
	$amt = aesthetic_get_user_wallet( get_current_user_id() );
	echo wpsc_currency_display( $amt );
	exit;
}
add_action( 'wp_ajax_get_currentuser_wallet', 'aesthetic_get_currentuser_wallet' );

/**
 * Check an User having sufficient wallet amount or not
 */
function aesthetic_is_have_wallet_balance(){
	$user_balance = aesthetic_get_user_wallet( get_current_user_id() );
	$purchase_amt = wpsc_cart_total( false );

	return ( $user_balance >= $purchase_amt );
}

/**
 * Remove my wallet if current user have insufficient amoutn
 */
function aesthetic_remove_mywallet(){
	global $wpsc_gateway;
	
	if( !aesthetic_is_have_wallet_balance() ){
		foreach( $wpsc_gateway->wpsc_gateways as $gateway ){
			if( $gateway['internalname'] != 'wpsc_merchant_testmode' ){
				$new_gateways[] = $gateway;
			}
		}
		
		$wpsc_gateway->wpsc_gateways = $new_gateways;
	}
}

##### End My wallet section ############################



