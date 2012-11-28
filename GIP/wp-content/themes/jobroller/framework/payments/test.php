<?php

class APP_TestGateway extends APP_Gateway{

	public function __construct() {
	
		parent::__construct( 'test-gateway', 'Developer Gateway' );
		add_filter( 'appthemes_dev_gateway_fields', array( $this, 'display_order' ), 10, 2 );
		add_filter( 'appthemes_dev_gateway_fields', array( $this, 'display_items' ), 10, 2 );
		
	}

	public function form() {
		return array();
	}

	public function process( $order, $options ) {
	
		if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'approve' ){
			$order->complete();
		}

		?>
		<div class="section-head"><h2>Developer Payment Gateway</h2></div>
		<form id="create-listing" method="POST" action="<?php echo $order->get_return_url(); ?>">
			<?php $this->view_transaction( $order, $options ); ?>
			<fieldset>
			<input type='hidden' name='action' value='approve' />
			<input type="submit" value="Approve Transaction">
			</fieldset>
		</form>
		<?php
		
	}
	
	public function view_transaction( $order, $options ){
		
		$sections = apply_filters( 'appthemes_dev_gateway_fields', array(), $order );
		
		$output = '';
		foreach( $sections as $title => $fields ){
			
			$output .= '<fieldset>';
			
			$output .= $this->display_section( $title );
			foreach( $fields as $field => $value ){
				$output .= $this->display_field( $field, $value );
			}
			
			$output .= '</fieldset>';
		}
	
		echo $output;
	}
	
	public function display_order( $sections, $order ){
	
		$sections['General Information'] = array(
			'ID' => $order->get_id(),
			'Status' => ucfirst( $order->get_status() ),
		);
		
		$sections['Money &amp; Currency'] = array(
			'Currency' => APP_Currencies::get_name( $order->get_currency() ) . ' (' . $order->get_currency() . ')',
			'Total' => APP_Currencies::get_price( $order->get_total(), $order->get_currency() ),
		);
	
		return $sections;
	
	}
	
	public function display_items( $sections, $order ){
	
		$count = 1;
		foreach( $order->get_items() as $item ){
		
			$title = 'Item #' . $count;
			$sections[ $title ] = array(
				'Post ID' => $item['post']->ID,
				'Price' => APP_Currencies::get_price( $item['price'], $order->get_currency() )
			);
		
		}
		
		return $sections;
	
	}
	
	private function display_section( $title ){
	
		return '<div class="featured-head"><h3>' . $title . ' </h3></div>';
	
	}
	
	private function display_field( $field, $value ){
	
		return '<div class="form-field"><label>' . $field . ': <strong>' . $value . '</strong></label></div>';
	
	}
}

appthemes_register_gateway( 'APP_TestGateway' );

