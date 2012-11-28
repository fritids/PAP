<?php
class jr_pack {
	
	var $id;	 	 	 	 	 	 	
	var $pack_name;		 	 	 	 	 	 	
	var $pack_description;	 	 	 	 	 	 	 
	var $job_count;			 	 	 	 	 	 	 
	var $pack_duration;	 	 	 	 	 	 	
	var $job_duration; 	 	 	 	 	 	
	var $pack_cost;	 	 	 	 	 	 	 	 	
	var $pack_created;
		 	
	var $job_cats;
	var $job_offers;
	var $feat_job_offers;
	var $access; 	
	var $pack_order; 	
	
	function jr_pack( $id='', $pack_options = array() ) {
		if ($id>0) :
			$this->id = (int) $id;
			$this->get_pack();
		elseif (!empty($pack_options['name'])):
			$this->pack_name 		= $pack_options['name'];
			$this->pack_description	= $pack_options['description'];
			$this->job_count		= $pack_options['job_count'];
			$this->pack_duration	= $pack_options['pack_duration'];
			$this->job_duration		= $pack_options['job_duration'];
			$this->pack_cost 		= $pack_options['cost'];
			$this->job_offers 		= $pack_options['job_offers'];
			$this->feat_job_offers	= $pack_options['feat_job_offers'];
			$this->access 			= $pack_options['access'];
			$this->job_cats 		= $pack_options['job_cats'];
			$this->pack_order 		= $pack_options['pack_order'];

		endif;
	}
	
	function get_pack() {
		global $wpdb;
		$result = $wpdb->get_row("SELECT * FROM $wpdb->jr_job_packs WHERE id = ".$this->id.';');
		if ($result) : 	 	 	 	 	
			$this->pack_name 			= $result->pack_name; 	 	 	 	 	 	
			$this->pack_description 	= $result->pack_description;	 	 	 	 
			$this->job_count 			= $result->job_count; 	 	 	 
			$this->pack_duration 		= $result->pack_duration; 	
			$this->job_duration 		= $result->job_duration;	
			$this->pack_cost 			= $result->pack_cost;
			$this->pack_created 		= $result->pack_created;
			$this->job_offers 			= $result->job_offers;
			$this->feat_job_offers 		= $result->feat_job_offers;
			$this->access 				= !empty($result->access) ? explode(',', $result->access) : '';
			$this->job_cats 			= !empty($result->job_cats) ? explode(',', $result->job_cats) : '';
			$this->pack_order 			= $result->pack_order;
			
		endif;
	}
	
	function insert_pack() {
		global $wpdb;
		$wpdb->insert( $wpdb->jr_job_packs, array(
			'pack_name' 			=> $this->pack_name,
			'pack_description' 		=> $this->pack_description,
			'job_count' 			=> $this->job_count,
			'pack_duration' 		=> $this->pack_duration,
			'job_duration' 			=> $this->job_duration,
			'pack_cost'				=> $this->pack_cost,
			'job_offers' 			=> $this->job_offers,
			'feat_job_offers' 		=> $this->feat_job_offers,
			'access' 				=> is_array($this->access) ? implode(',', $this->access) : '',
			'job_cats' 				=> is_array($this->job_cats) ? implode(',', $this->job_cats) : '',
			'pack_order' 			=> ($this->pack_order>0?$this->pack_order:1),
		), array( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%d' ) );
		
		$this->id = $wpdb->insert_id;
	}
	
	function give_to_user( $user, $jobs_count = 1, $feat_count = 0 ) {
		global $wpdb;

		$result = '';
		if ($this->id > 0) :
				
			if ($this->pack_duration > 0) :
				$expires = date("Y-m-d H:i:s", strtotime("+".$this->pack_duration." day"));
			else :
				$expires = '';
			endif;
			
			$result=$wpdb->insert( $wpdb->jr_customer_packs, array(
				'pack_id' 					=> $this->id,	
				'pack_name' 				=> $this->pack_name, 
				'user_id' 					=> $user,
				'job_duration' 				=> $this->job_duration,
				'pack_expires' 				=> $expires,
				'jobs_count' 				=> $jobs_count,
				'jobs_limit' 				=> $this->job_count,
			
				'job_offers_count'			=> 0,
				'job_offers'				=> $this->job_offers,
				'feat_job_offers_count'		=> $feat_count,
				'feat_job_offers'			=> $this->feat_job_offers,
				'access' 					=> is_array($this->access) ? implode(',', $this->access) : '',
				'job_cats' 					=> is_array($this->job_cats) ? implode(',', $this->job_cats) : '',
				'pack_order' 				=> $this->pack_order
			
			), array( '%d' ,'%s', '%s', '%s', '%s', '%d', '%d', '%d','%d','%d','%d', '%s', '%s', '%d' ) );

		endif;

		return $result;
	}
}	

class jr_user_pack {

	var $id;
	var $pack_id;
	var $user_id;		 	 	 	 	 	
	var $pack_name;		
	var $job_duration;
	var $pack_expires;	 	 	 	 	 	 	 	 	 	 
	var $job_count;		
	var $job_limit;		 	 	 	 	 	 	 
	var $pack_purchased;	

	var $job_offers_count;
	var $job_offers;
	var $job_offer_remain;
	var $feat_job_offers_count;
	var $feat_job_offers;
	var $feat_job_offer_remain;
	var $access;
	var $job_cats;
	var $pack_order;
	
	function jr_user_pack( $id='' ) {
		$this->id = (int) $id;
	}
	
	function get_valid_pack() {
		global $wpdb;
		$result = $wpdb->get_row("SELECT * FROM $wpdb->jr_customer_packs WHERE id = ".$this->id." AND ".get_current_user_id()." AND (jobs_count+job_offers_count < jobs_limit+job_offers OR jobs_limit = 0) AND (pack_expires > NOW() OR pack_expires = NULL OR pack_expires = '0000-00-00 00:00:00')");
		if ($result) : 	 	 	 	 	
			$this->pack_id 				 = $result->pack_id;	
			$this->user_id 				 = $result->user_id; 	 	 	 	 	 	
			$this->pack_name 			 = $result->pack_name;	 	 	 	 
			$this->job_duration 		 = $result->job_duration; 	 	 	 
			$this->pack_expires 		 = $result->pack_expires; 	
			$this->job_count 			 = $result->jobs_count;	
			$this->job_limit 			 = $result->jobs_limit;
			$this->pack_purchased 		 = $result->pack_purchased;
			
			$this->job_offer_count 	 	 = $result->job_offers_count;
			$this->job_offers	 	 	 = $result->job_offers;
			$this->feat_job_offer_count  = $result->feat_job_offers_count;
			$this->feat_job_offers	 	 = $result->feat_job_offers;
			$this->access 	 	 		 = !empty($result->access) ? explode(',', $result->access) : '';
			$this->job_cats		 		 = !empty($result->job_cats) ? explode(',', $result->job_cats) : '';
			$this->pack_order	 		 = $result->pack_order;
			
			$this->job_offer_remain 	 = $this->job_offers - $this->job_offer_count;
			$this->feat_job_offer_remain = $this->feat_job_offers - $this->feat_job_offer_count;
			return true;	
		else :
			return false;
		endif;
	}
	
	function inc_job_count() {
		global $wpdb;
		
		if ( $this->job_offer_remain == 0 )
			$wpdb->update( $wpdb->jr_customer_packs, array( 'jobs_count' => ($this->job_count+1) ), array( 'id' => $this->id ), array( '%d' ), array( '%d' ) );
	}
	
	function inc_offers_count( $offer = 'job' ) {
		global $wpdb;
		
		$offer_col = array (
				'job' 		=>	array ( 'job_offers_count' 		=> $this->job_offer_count + 1 ),	
				'featured'  => 	array ( 'feat_job_offers_count' => $this->feat_job_offer_count + 1 ),
		);

		$offer_count = array (
				'job' 		=>	array ( 'limit' => $this->job_offer_remain, ),	
				'featured'  => 	array ( 'limit' => $this->feat_job_offer_remain ),		
		);
									
		if ( $offer_count[$offer]['limit'] > 0 )
			$wpdb->update( $wpdb->jr_customer_packs, $offer_col[$offer], array( 'id' => $this->id ), array( '%d' ), array( '%d' ) );
	
	}	
}